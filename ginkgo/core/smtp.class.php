<?php
/*-----------------------------------------------------------------
！！！！警告！！！！
以下为系统文件，请勿修改
-----------------------------------------------------------------*/

namespace ginkgo;

//不能非法包含或直接执行
defined('IN_GINKGO') or exit('Access denied');

/*-------------邮件类-------------*/
class Smtp {

    protected static $instance;
    private $config;
    private $rcpt;
    private $reply;
    private $subject;
    private $content;
    private $contentAlt;
    protected $error;
    private $crlf = "\r\n";
    private $le = "\n";

    protected function __construct($config = array()) {
        $this->config = Config::get('smtp', 'var_extra');

        if (!Func::isEmpty($config)) {
            $this->config = array_replace_recursive($this->config, $config);
        }

        $this->obj_request = Request::instance();
    }

    protected function __clone() {

    }

    public static function instance($config = array()) {
        if (Func::isEmpty(static::$instance)) {
            static::$instance = new static($config);
        }
        return static::$instance;
    }

    function connect() {
        if (!$this->initConfig()) {
            return false;
        }

        switch ($this->config['secure']) {
            case 'ssl':
                $_str_host = 'ssl://' . $this->config['host'];
            break;

            case 'tls':
                $_str_host = 'tls://' . $this->config['host'];
            break;

            default:
                $_str_host = 'tcp://' . $this->config['host'];
            break;
        }

        $socket_context = stream_context_create();

        $this->obj_conn = stream_socket_client($_str_host . ':' . $this->config['port'], $errno, $errstr, 30, STREAM_CLIENT_CONNECT, $socket_context);

        if (!$this->obj_conn) {
            $this->error = 'FSOCKOPEN Error: Cannot conect to ' . $this->config['host'];
            return false;
        }

        $this->getResult();

        return true;
    }

    function send() {
        //HELO 向服务器标识用户身份发送者 收到 220 或 250 时OK
        if (!$this->hello($this->obj_request->host())) {
            return false;
        }

        $_authtype = $this->getAuthtype();

        if ($_authtype != 'none' && $this->config['auth'] = true) {
            switch ($_authtype) {
                case 'plain':
                    //AUTH PLAIN 请求登录认证 334 OK
                    if (!$this->sendCmd('AUTH', 'AUTH PLAIN', 334)) {
                        return false;
                    }

                    //发送经 Base64 加密的用户名 & 密码 334 OK
                    if (!$this->sendCmd('User & Password', base64_encode("\0" . $this->config['user'] . "\0" . $this->config['pass']), 334)) {
                        return false;
                    }
                break;

                case 'login':
                    //AUTH LOGIN 请求登录认证 334 OK
                    if (!$this->sendCmd('AUTH', 'AUTH LOGIN', 334)) {
                        return false;
                    }

                    //发送经 Base64 加密的用户账号 334 OK
                    if (!$this->sendCmd('Username', base64_encode($this->config['user']), 334)) {
                        return false;
                    }

                    //发送经 Base64 加密的用户密码 235 OK
                    if (!$this->sendCmd('Password', base64_encode($this->config['pass']), 235)) {
                        return false;
                    }
                break;
            }
        }

        //Mail From 发送发件人邮箱 250 OK
        if (!$this->sendCmd('MAIL FROM', 'MAIL FROM:<' . $this->config['from_addr'] . '>', 250)) {
            return false;
        }

        if (Func::isEmpty($this->rcpt)) {
            return false;
        }

        //发送收件人邮箱 250 OK
        foreach ($this->rcpt as $_key=>$_value) {
            if (!isset($_value['addr'])) {
                return false;
            }

            if (!$this->sendCmd('RCPT TO', 'RCPT TO:<' . $_value['addr'] . '>', array(250, 251))) {
                return false;
            }
        }

        $this->createBody();

        //结束会话 221
        if (!$this->sendCmd('QUIT', 'QUIT', 221)) {
            return false;
        }

        //关闭连接
        fclose($this->obj_conn);

        return true;
    }

    function addRcpt($addr, $name = '') {
        if (Func::isEmpty($name)) {
            $this->rcpt[] = array(
                'addr' => $addr,
            );
        } else {
            $this->rcpt[] = array(
                'addr' => $addr,
                'name' => $name,
            );
        }
    }

    function setSubject($subject = '') {
        $this->subject = $subject;
    }

    function setFrom($addr, $name = '') {
        if (Func::isEmpty($name)) {
            $this->config['from'] = array(
                'addr' => $addr,
            );
        } else {
            $this->config['from'] = array(
                'addr' => $addr,
                'name' => $name,
            );
        }
    }

    function addReply($addr, $name = '') {
        if (Func::isEmpty($name)) {
            $this->reply[] = array(
                'addr' => $addr,
            );
        } else {
            $this->reply[] = array(
                'addr' => $addr,
                'name' => $name,
            );
        }
    }

    function setContentAlt($content = '') {
        $this->contentAlt = $content;
    }

    function setContent($content = '') {
        $this->content = $content;
    }

    function getError() {
        return $this->error;
    }

    private function sendCmd($cmd, $cmd_str, $expect = 250) {
        //发送请求信息
        //$this->clientSend($cmd_str . $this->crlf);

        $this->clientSend($cmd_str . $this->crlf);

        //接收信息
        $this->lastResult  = $this->getResult();

        $_arr_matches = array();

        if (is_numeric($expect)) {
            $_arr_expect = array($expect);
        } else {
            $_arr_expect = $expect;
        }

        if (preg_match('/^([0-9]{3})[ -](?:([0-9]\\.[0-9]\\.[0-9]) )?/', $this->lastResult, $_arr_matches)) {
            $code       = $_arr_matches[1];
            $code_ex    = null;
            $_str_regex = '';
            if (isset($_arr_matches[2])) {
                $code_ex = $_arr_matches[2];
            }
            if ($code_ex) {
                $_str_regex = str_replace('.', '\\.', $code_ex) . ' ';
            }
            // Cut off error code from each response line
            $detail     = preg_replace("/{$code}[ -]" . $_str_regex . "/m", '', $this->lastResult);
        } else {
            // Fall back to simple parsing if regex fails
            $code       = substr($this->lastResult, 0, 3);
            $code_ex    = null;
            $detail     = substr($this->lastResult, 4);
        }

        if (!in_array($code, $_arr_expect)) {
            $this->error[$cmd] = $code . ' - ' .$detail;
            return false;
        }

        return true;
    }

    private function clientSend($data) {
        return fwrite($this->obj_conn, $data);
    }

    private function headerLine($name, $value) {
        return $name . ': ' . $value . $this->le;
    }

    private function textLine($value) {
        return $value . $this->le;
    }

    private function addrAppend($type, $addr) {
        $_str_return = '';

        foreach ($addr as $_key=>$_value) {
            $_str_return .= $type . ': ';

            if (isset($_value['name']) && !Func::isEmpty($_value['name'])) {
                $_str_return .= $_value['name'] . ' ';
            }

            $_str_return .= '<' . $_value['addr'] . '>' . $this->le;
        }

        return $_str_return;
    }

    private function contentEncode($string) {
        return $this->le . chunk_split(base64_encode($string), 76, $this->le) . $this->le;
    }

    private function createBody() {
        $_uniqueid     = md5(uniqid(GK_NOW));
        $_boundary     = 'b1_' . $_uniqueid;
        $_messageID    = sprintf('<%s@%s>', $_uniqueid, $this->obj_request->host());

        if (Func::isEmpty(strip_tags($this->contentAlt))) {
            $this->contentAlt = strip_tags($this->content);
        }

        //开始发送邮件数据 354 OK
        if (!$this->sendCmd('DATA', 'DATA', 354)) {
            return false;
        }

        //邮件头 -> 日期
        $this->clientSend($this->headerLine('Date', date('D, j M Y H:i:s O')));

        //邮件头 -> 收件人
        if (!Func::isEmpty($this->rcpt)) {
            //邮件头 -> 收件人
            $this->clientSend($this->addrAppend('To', $this->rcpt));
        }

        $_arr_from[0]['addr'] = $this->config['from_addr'];
        if (isset($this->config['from_name']) && !Func::isEmpty($this->config['from_name'])) {
            $_arr_from[0]['name'] = $this->config['from_name'];
        }

        //邮件头 -> 发件人
        $this->clientSend($this->addrAppend('From', $_arr_from));
        //邮件头 -> 回复地址
        if (!Func::isEmpty($this->reply)) {
            $this->clientSend($this->addrAppend('Reply-To', $this->reply));
        }

        //邮件头 -> 标题
        $this->clientSend($this->headerLine('Subject', $this->subject));

        //邮件头 -> Message-ID
        $this->clientSend($this->headerLine('Message-ID', $_messageID));

        //邮件头 -> 发件代理客户端
        $this->clientSend($this->headerLine('X-Mailer', 'ginkgo'));

        //邮件头 -> 邮件重要级别 1（Highest） 3（Normal） 5（Lowest）
        $this->clientSend($this->headerLine('X-Priority', '1 (Highest)'));

        //邮件头 -> mime
        $this->clientSend($this->headerLine('MIME-Version', '1.0'));

        //邮件头 -> 多段内容
        $this->clientSend($this->headerLine('Content-Type', 'multipart/alternative;'));

        //邮件头 -> 边界
        $this->clientSend($this->textLine("\tboundary=\"" . $_boundary . '"'));

        //邮件头 -> 传输编码
        $this->clientSend($this->headerLine('Content-Transfer-Encoding', '8bit'));

        //邮件头 -> 多段内容
        $this->clientSend($this->textLine($this->le . 'This is a multi-part message in MIME format.' . $this->le));

        //内容
        $this->clientSend($this->createContent($_boundary, strip_tags($this->contentAlt)));

        //内容
        $this->clientSend($this->createContent($_boundary, $this->content, 'text/html'));

        //边界结束
        $this->clientSend($this->textLine($this->le . '--' . $_boundary . '--' . $this->le));

        //结束发送邮件数据 250
        $_result = $this->sendCmd('Data End', $this->crlf . '.', 250);

        return $_result;
    }

    private function createContent($boundary, $content, $type = 'text/plain') {
        $_str_content = '';
        $_str_content .= $this->textLine($this->le . '--' . $boundary);
        $_str_content .= $this->headerLine('Content-Type', $type. '; charset=UTF-8');
        $_str_content .= $this->headerLine('Content-Transfer-Encoding', 'base64');
        $_str_content .= $this->contentEncode($content);

        return $_str_content;
    }


    private function hello($host = '') {
        $_return = $this->sendHello('EHLO', $host);

        //Try extended hello first (RFC 2821)
        if (!$_return) {
            $_return = $this->sendHello('HELO', $host);
        }

        return $_return;
    }


    private function sendHello($hello, $host) {
        $_return = $this->sendCmd($hello, $hello . ' ' . $host, 250);

        $this->helloResult = $this->lastResult;

        if ($_return) {
            $this->parseHello($hello);
        } else {
            $this->serverCaps = null;
        }

        return $_return;
    }

    private function parseHello($type) {
        $this->serverCaps = array();

        $_result = explode("\n", $this->lastResult);

        foreach ($_result as $_key => $_value) {
            //First 4 chars contain response code followed by - or space
            $_value = trim(substr($_value, 4));
            if (empty($_value)) {
                continue;
            }
            $_fields = explode(' ', $_value);
            if (!empty($_fields)) {
                if (!$_key) {
                    $_name      = $type;
                    $_fields    = $_fields[0];
                } else {
                    $_name = array_shift($_fields);
                    switch ($_name) {
                        case 'SIZE':
                            $_fields = ($_fields ? $_fields[0] : 0);
                        break;
                        case 'AUTH':
                            if (!is_array($_fields)) {
                                $_fields = array();
                            }
                        break;
                        default:
                            $_fields = true;
                        break;
                    }
                }
                $this->serverCaps[$_name] = $_fields;
            }
        }
    }

    private function getAuthtype() {
        if (!isset($this->serverCaps['AUTH'])) {
            return 'none';
        }

        if (in_array('LOGIN', $this->serverCaps['AUTH'])) {
            return 'login';
        } else if (in_array('PLAIN', $this->serverCaps['AUTH'])) {
            return 'plain';
        }

        return 'none';
    }

    private function getResult() {
        if (!is_resource($this->obj_conn)) {
            return '';
        }

        $_str_return = '';

        //stream_set_timeout($this->obj_conn, 30);

        $endtime = GK_NOW + 30;

        while (is_resource($this->obj_conn) && !feof($this->obj_conn)) {
            $_str_get = fgets($this->obj_conn, 515);
            $_str_return .= $_str_get;

            if ((isset($_str_get[3]) && $_str_get[3] == ' ')) {
                break;
            }

            $info = stream_get_meta_data($this->obj_conn);
            if ($info['timed_out']) {
                break;
            }

            if (GK_NOW > $endtime) {
                break;
            }
        }

        return $_str_return;
    }


    private function initConfig() {
        if (!isset($this->config['host'])) {
            return false;
        }
        if (!isset($this->config['user'])) {
            return false;
        }
        if (!isset($this->config['pass'])) {
            return false;
        }

        isset($this->config['port']) or $this->config['port'] = 25;
        isset($this->config['auth']) or $this->config['auth'] = true;

        isset($this->config['from_addr']) or $this->config['from_addr'] = 'root@localhost';
        isset($this->config['secure']) or $this->config['secure'] = 'off';
        isset($this->config['debug']) or $this->config['debug'] = 0; //SMTP 调试开关 0 关闭，1 客户端消息, 2 客户端与服务端消息

        if ($this->config['auth'] == 'true' || $this->config['auth'] === true) {
            $this->config['auth'] = true;
        }

        if (!isset($this->config['reply_addr']) || Func::isEmpty($this->config['reply_addr'])) {
            $this->config['reply_addr'] = $this->config['from_addr'];
        }

        $this->reply[0]['addr'] = $this->config['reply_addr'];
        if (isset($this->config['reply_name']) && !Func::isEmpty($this->config['reply_name'])) {
            $this->reply[0]['name'] = $this->config['reply_name'];
        }

        return true;
    }
}