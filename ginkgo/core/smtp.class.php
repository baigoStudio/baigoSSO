<?php
/*-----------------------------------------------------------------
！！！！警告！！！！
以下为系统文件，请勿修改
-----------------------------------------------------------------*/

namespace ginkgo;

// 不能非法包含或直接执行
if (!defined('IN_GINKGO')) {
  return 'Access denied';
}

// 邮件发送类
class Smtp {

  public $config = array(); // 配置
  public $error; // 错误
  public $rcpt; // 收件人
  public $reply; // 回复地址
  public $subject; // 主题
  public $content; // 内容
  public $contentAlt; // 纯文本内容

  protected static $instance; // 当前实例

  private $configThis = array(
    'method'        => 'smtp',
    'host'          => '',
    'secure'        => 'off',
    'port'          => 25,
    'auth'          => 'true',
    'user'          => '',
    'pass'          => '',
    'from_addr'     => 'root@localhost',
    'from_name'     => 'root',
    'reply_addr'    => 'root@localhost',
    'reply_name'    => 'root',
  );

  private $serverCaps = array(); // 服务器说明
  private $crlf = "\r\n"; // 换行符 (主要用于发送命令)
  private $le = "\n"; // 换行符 (主要用于数据换行)
  private $res_conn; // 连接资源
  private $init; // 是否初始化


  /** 构造函数
   * __construct function.
   *
   * @access protected
   * @param array $config (default: array()) 配置
   * @return void
   */
  protected function __construct($config = array()) {
    $this->config($config);

    $this->obj_request = Request::instance();
  }

  protected function __clone() { }

  /** 实例化
   * instance function.
   *
   * @access public
   * @static
   * @param array $config (default: array()) 配置
   * @return void
   */
  public static function instance($config = array()) {
    if (Func::isEmpty(self::$instance)) {
      self::$instance = new static($config);
    }
    return self::$instance;
  }

  // 配置 since 0.2.0
  public function config($config = array()) {
    $_arr_config   = Config::get('smtp', 'var_extra'); // 取得配置

    $_arr_configDo = $this->configThis;

    if (is_array($_arr_config) && Func::notEmpty($_arr_config)) {
      $_arr_configDo = array_replace_recursive($_arr_configDo, $_arr_config); // 合并配置
    }

    if (is_array($this->config) && Func::notEmpty($this->config)) {
      $_arr_configDo = array_replace_recursive($_arr_configDo, $this->config); // 合并配置
    }

    if (is_array($config) && Func::notEmpty($config)) {
      $_arr_configDo = array_replace_recursive($_arr_configDo, $config); // 合并配置
    }

    $this->reply[0]['addr'] = $_arr_configDo['reply_addr'];

    if (Func::notEmpty($_arr_configDo['reply_name'])) {
      $this->reply[0]['name'] = $_arr_configDo['reply_name'];
    }

    $this->config  = $_arr_configDo;
  }

  /** 连接服务器
   * connect function.
   *
   * @access public
   * @return void
   */
  public function connect() {
    switch ($this->config['method']) { // 发送方法
      case 'smtp': // smtp 服务器发送
        switch ($this->config['secure']) { // 加密类型
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

        $_res_socket = stream_context_create(); // 创建资源流上下文

        // 打开互联网套接字连接
        $this->res_conn = stream_socket_client($_str_host . ':' . $this->config['port'], $errno, $errstr, 30, STREAM_CLIENT_CONNECT, $_res_socket);

        if (!$this->res_conn) { // 报错
          $this->error['connect'] = 'Socket Connection Error: Cannot conect to ' . $this->config['host'] . ', Error No.: ' . $errno . ', Error string: ' . $errstr;
          return false;
        }

        $this->getResult(); // 取得结果
      break;

      default: // php 函数发送
      break;
    }

    $this->init = true;

    return true;
  }


  /** 增加收件人
   * addRcpt function.
   *
   * @access public
   * @param string $addr 地址
   * @param string $name (default: '') 名称
   * @return void
   */
  public function addRcpt($addr, $name = '') {
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

  /** 设置主题
   * setSubject function.
   *
   * @access public
   * @param string $subject (default: '') 主题
   * @return void
   */
  public function setSubject($subject) {
    $this->subject = $subject;
  }

  /** 设置发件人
   * setFrom function.
   *
   * @access public
   * @param string $addr 地址
   * @param string $name (default: '') 名称
   * @return void
   */
  public function setFrom($addr, $name = '') {
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

  /** 增加回复地址
   * addReply function.
   *
   * @access public
   * @param string $addr 地址
   * @param string $name (default: '') 名称
   * @return void
   */
  public function addReply($addr, $name = '') {
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


  /** 设置内容
   * setContentAlt function.
   *
   * @access public
   * @param string $content (default: '') 内容
   * @return void
   */
  public function setContent($content) {
    $this->content = $content;
  }


  /** 设置纯文本内容
   * setContentAlt function.
   *
   * @access public
   * @param string $content (default: '') 纯文本内容
   * @return void
   */
  public function setContentAlt($content) {
    $this->contentAlt = strip_tags($content);
  }


  /** 获取错误
   * getError function.
   *
   * @access public
   * @return 错误
   */
  public function getError($name = '') {
    $_return = '';
    if (Func::isEmpty($name)) {
      $_return = $this->error;
    } else if (isset($this->error[$name])) {
      $_return = $this->error[$name];
    }
    return $_return;
  }


  /** 发送邮件
   * send function.
   *
   * @access public
   * @return void
   */
  public function send() {
    if (Func::isEmpty($this->init)) { // 判断是否已连接
      if (!$this->connect()) {
        return false;
      }
    }

    // 收件人为空的话返回错误
    if (Func::isEmpty($this->rcpt)) {
      return false;
    }

    $_unique_id     = md5(uniqid(GK_NOW));
    $_boundary      = 'b1_' . $_unique_id;
    $_message_id    = sprintf('<%s@%s>', $_unique_id, $this->obj_request->host());

    switch ($this->config['method']) { // 发送方法
      case 'smtp': // smtp 服务器发送
        // HELO 向服务器标识用户身份, 发送者 收到 220 或 250 时 OK
        if (!$this->createHello($this->obj_request->host())) {
          return false;
        }

        $_authtype = $this->getAuthtype(); // 取得服务器认证类型

        if ($_authtype != 'none' && $this->config['auth'] = true) { // 如果需要身份认证
          switch ($_authtype) {
            case 'plain':
              // AUTH PLAIN 请求登录认证 334 OK
              if (!$this->sendCmd('AUTH', 'AUTH PLAIN', 334)) {
                return false;
              }

              // 发送经 Base64 加密的用户名 & 密码 334 OK
              if (!$this->sendCmd('User & Password', base64_encode("\0" . $this->config['user'] . "\0" . $this->config['pass']), 334)) {
                return false;
              }
            break;

            case 'login':
              // AUTH LOGIN 请求登录认证 334 OK
              if (!$this->sendCmd('AUTH', 'AUTH LOGIN', 334)) {
                return false;
              }

              // 发送经 Base64 加密的用户账号 334 OK
              if (!$this->sendCmd('Username', base64_encode($this->config['user']), 334)) {
                return false;
              }

              // 发送经 Base64 加密的用户密码 235 OK
              if (!$this->sendCmd('Password', base64_encode($this->config['pass']), 235)) {
                return false;
              }
            break;
          }
        }

        // Mail From 发送发件人邮箱 250 OK
        if (!$this->sendCmd('MAIL FROM', 'MAIL FROM:<' . $this->config['from_addr'] . '>', 250)) {
          return false;
        }

        // 发送收件人邮箱 250 OK
        foreach ($this->rcpt as $_key=>$_value) {
          if (!isset($_value['addr']) || Func::isEmpty($_value['addr'])) { // 发件人地址参数未设置返回错误
            $this->error['rcpt'] = 'Recipient is not set';
            return false;
          }

          if (!$this->sendCmd('RCPT TO', 'RCPT TO:<' . $_value['addr'] . '>', array(250, 251))) {
            return false;
          }
        }

        $this->createBody($_boundary, $_message_id); // 创建邮件主体

        // 结束会话 221
        if (!$this->sendCmd('QUIT', 'QUIT', 221)) {
          return false;
        }

        // 关闭连接
        fclose($this->res_conn);
      break;

      default: // php 函数发送
        if (!function_exists('mail')) {
          $this->error['func'] = 'PHP Function &quot;mail&quot; does not exist';
          return false;
        }

        $_header    = $this->headerProcess($_boundary, $_message_id);
        $_body      = $this->bodyProcess($_boundary);

        // 发送收件人邮箱 250 OK
        foreach ($this->rcpt as $_key=>$_value) {
          if (!isset($_value['addr']) || Func::isEmpty($_value['addr'])) { // 发件人地址参数未设置返回错误
            $this->error['rcpt'] = 'Recipient is not set';
            return false;
          }

          if (!mail($_value['addr'], $this->subject, $_body, $_header)) {
            return false;
          }
        }
      break;
    }

    return true;
  }


  /** 创建 hello 消息
   * hello function.
   *
   * @access private
   * @param string $host (default: '')
   * @return void
   */
  private function createHello($host) {
    // 首先尝试扩展 hello（RFC 2821）
    $_return = $this->sendHello('EHLO', $host);

    // 如果失败继续尝试
    if (!$_return) {
      $_return = $this->sendHello('HELO', $host);
    }

    return $_return;
  }


  /** 创建邮件主体
   * createBody function.
   *
   * @access private
   * @return 发送命令结果
   */
  private function createBody($boundary, $message_id) {
    // 开始发送邮件数据 354 OK
    if (!$this->sendCmd('DATA', 'DATA', 354)) {
      return false;
    }

    $_header    = $this->headerProcess($boundary, $message_id);
    $_body      = $this->bodyProcess($boundary);

    $this->sendDo($_header . $_body);

    // 结束发送邮件数据 250
    $_result = $this->sendCmd('Data End', $this->crlf . '.', 250);

    return $_result;
  }


  /** 发送命令
   * sendCmd function.
   *
   * @access private
   * @param mixed $cmd 命令标记
   * @param mixed $cmd_str 命令字符
   * @param int $expect (default: 250) 期待返回代码
   * @return void
   */
  private function sendCmd($cmd, $cmd_str, $expect = 250) {
    // 发送请求信息
    $this->sendDo($cmd_str . $this->crlf); // 第一个换行符

    // 接收信息
    $this->lastResult  = $this->getResult();

    $_arr_matches = array(); // 匹配结果

    if (!is_array($expect) && is_scalar($expect)) { // 如果期待返回代码不是数组
      $expect = array($expect);
    }

    if (preg_match('/^([0-9]{3})[ -](?:([0-9]\\.[0-9]\\.[0-9]) )?/', $this->lastResult, $_arr_matches)) { // 用正则匹配返回信息
      $_str_code      = $_arr_matches[1]; // 返回代码
      $_str_codeEx    = null; // 返回代码扩展
      $_str_regex     = ''; // 正则规则
      if (isset($_arr_matches[2])) { // 如果有代码扩展, 则定义
        $_str_codeEx = $_arr_matches[2];
      }
      if ($_str_codeEx) {
        $_str_regex = str_replace('.', '\\.', $_str_codeEx) . ' '; // 处理正则规则
      }
      // 用正则解析
      $_str_detail     = preg_replace("/{$_str_code}[ -]" . $_str_regex . "/m", '', $this->lastResult); // 切断每个响应行的错误代码
    } else { // 如果正则匹配失败, 则简单解析
      $_str_code      = substr($this->lastResult, 0, 3); // 截取代码
      $_str_detail    = substr($this->lastResult, 4);
    }

    if (!in_array($_str_code, $expect)) {
      $this->error[$cmd] = $_str_code . ' - ' . $_str_detail;
      return false;
    }

    return true;
  }


  /** 发送 hello 命令
   * sendHello function.
   *
   * @access private
   * @param mixed $hello 消息
   * @param mixed $host 主机
   * @return 发送结果
   */
  private function sendHello($hello, $host) {
    $_return = $this->sendCmd($hello, $hello . ' ' . $host, 250);

    if ($_return) {
      $this->serverCapsProcess($hello);
    }

    return $_return;
  }


  /** 发送真实命令
   * sendDo function.
   *
   * @access private
   * @param mixed $data 命令数据
   * @return 发送结果
   */
  private function sendDo($data) {
    return fwrite($this->res_conn, $data);
  }


  private function headerProcess($boundary, $message_id) {
    $_header = '';

    // 邮件头 -> 日期
    $_header .= $this->headerLine('Date', date('D, j M Y H:i:s O'));

    // 邮件头 -> 收件人
    if (Func::notEmpty($this->rcpt)) {
      // 邮件头 -> 收件人
      $_header .= $this->addrProcess('To', $this->rcpt);
    }

    // 邮件头 -> 发件人
    $_arr_from[0]['addr'] = $this->config['from_addr'];

    if (isset($this->config['from_name']) && Func::notEmpty($this->config['from_name'])) {
      $_arr_from[0]['name'] = $this->config['from_name'];
    }

    $_header .= $this->addrProcess('From', $_arr_from);

    // 邮件头 -> 回复地址
    if (Func::notEmpty($this->reply)) {
      $_header .= $this->addrProcess('Reply-To', $this->reply);
    }

    // 邮件头 -> 标题
    $_header .= $this->headerLine('Subject', $this->subject);

    // 邮件头 -> Message-ID
    $_header .= $this->headerLine('Message-ID', $message_id);

    // 邮件头 -> 发件代理客户端
    $_header .= $this->headerLine('X-Mailer', 'ginkgo');

    // 邮件头 -> 邮件重要级别 1（Highest） 3（Normal） 5（Lowest）
    $_header .= $this->headerLine('X-Priority', '1 (Highest)');

    // 邮件头 -> mime
    $_header .= $this->headerLine('MIME-Version', '1.0');

    // 邮件头 -> 多段内容
    $_header .= $this->headerLine('Content-Type', 'multipart/alternative;');

    // 邮件头 -> 边界
    $_header .= $this->contentLine("\tboundary=\"" . $boundary . '"');

    // 邮件头 -> 传输编码
    $_header .= $this->headerLine('Content-Transfer-Encoding', '8bit');

    return $_header;
  }


  private function bodyProcess($boundary) {
    $_body = '';

    // 声明多段内容
    $_body .= $this->contentLine($this->le . 'This is a multi-part message in MIME format.' . $this->le);

    // 内容
    if (Func::isEmpty(strip_tags($this->contentAlt))) { // 如果没有专门设置纯文本内容, 则使用内容过滤标签
      $this->contentAlt = strip_tags($this->content);
    }

    $_body .= $this->contentProcess($boundary, strip_tags($this->contentAlt));

    // 内容
    $_body .= $this->contentProcess($boundary, $this->content, 'text/html');

    // 边界结束
    $_body .= $this->contentLine($this->le . '--' . $boundary . '--' . $this->le);

    return $_body;
  }


  /** 地址信息处理
   * addrProcess function.
   *
   * @access private
   * @param mixed $type 类型
   * @param array $addr 地址
   * @return 拼合后的结果
   */
  private function addrProcess($type, $addr) {
    $_str_return = '';

    foreach ($addr as $_key=>$_value) { // 遍历
      $_str_return .= $type . ': ';

      if (isset($_value['name']) && Func::notEmpty($_value['name'])) { // 如果定义了 name 则加上
        $_str_return .= $_value['name'] . ' ';
      }

      $_str_return .= '<' . $_value['addr'] . '>' . $this->le;
    }

    return $_str_return;
  }


  /** 服务器说明处理
   * serverCapsProcess function.
   *
   * @access private
   * @param mixed $type 类型
   * @return void
   */
  private function serverCapsProcess($type) {
    $_arr_result = explode("\n", $this->lastResult); // 用换行符分拆接收到的说明信息

    foreach ($_arr_result as $_key => $_value) { // 遍历说明信息
      // 前 4 个字符包含响应代码，后跟 - 或空格
      $_value = trim(substr($_value, 4));
      if (Func::isEmpty($_value)) { // 如果为空则继续
        continue;
      }
      $_fields = explode(' ', $_value); // 用空格分拆字段
      if (Func::notEmpty($_fields)) { // 如果有内容
        if (!$_key) { // 如果不是第一条说明信息
          $_name      = $type;
          $_fields    = $_fields[0];
        } else {
          $_name = array_shift($_fields);
          switch ($_name) {
            case 'SIZE':
                $_fields = ($_fields ? $_fields[0] : 0);
            break;

            case 'AUTH': // 认证类型
                if (!is_array($_fields)) {
                    $_fields = array();
                }
            break;

            default:
                $_fields = true;
            break;
          }
        }
        $_arr_serverCaps[$_name] = $_fields; // 向服务器说明属性中写入该字段
      }
    }

    $this->serverCaps = $_arr_serverCaps;
  }


  /** 处理内容
   * contentProcess function.
   *
   * @access private
   * @param mixed $boundary
   * @param mixed $content
   * @param string $type (default: 'text/plain')
   * @return void
   */
  private function contentProcess($boundary, $content, $type = 'text/plain') {
    $_str_content = '';
    $_str_content .= $this->contentLine($this->le . '--' . $boundary);
    $_str_content .= $this->headerLine('Content-Type', $type. '; charset=UTF-8');
    $_str_content .= $this->headerLine('Content-Transfer-Encoding', 'base64');
    $_str_content .= $this->contentEncode($content);

    return $_str_content;
  }

  /** 获取认证类型
   * getAuthtype function.
   *
   * @access private
   * @return 认证类型
   */
  private function getAuthtype() {
    if (!is_array($this->serverCaps) || !isset($this->serverCaps['AUTH']) || !is_array($this->serverCaps['AUTH'])) { // 如果服务器说明不是数组, 没有关于认证的信息, 认证类型不是数组
      return 'none';
    }

    if (in_array('LOGIN', $this->serverCaps['AUTH'])) {
      return 'login';
    } else if (in_array('PLAIN', $this->serverCaps['AUTH'])) {
      return 'plain';
    }

    return 'none';
  }

  /** 处理头部行
   * headerLine function.
   *
   * @access private
   * @param mixed $name 名称
   * @param mixed $value 值
   * @return 拼合后结果
   */
  private function headerLine($name, $value) {
    return $name . ': ' . $value . $this->le;
  }

  /** 处理内容行
   * contentLine function.
   *
   * @access private
   * @param mixed $value 值
   * @return 拼合后结果
   */
  private function contentLine($value) {
    return $value . $this->le;
  }


  /** 内容编码
   * contentEncode function.
   *
   * @access private
   * @param mixed $string 内容字符串
   * @return 编码后结果
   */
  private function contentEncode($string) {
    $string = base64_encode($string);
    $string = chunk_split($string, 76, $this->le);
    return $this->le . $string . $this->le;
  }


  /** 获取命令发送结果
   * getResult function.
   *
   * @access private
   * @return 发送结果
   */
  private function getResult() {
    if (!is_resource($this->res_conn)) {
      $this->error[] = 'Socket connection is not a resource';
      return '';
    }

    $_str_return = '';

    //stream_set_timeout($this->res_conn, 30);

    $endtime = GK_NOW + 30;

    while (is_resource($this->res_conn) && !feof($this->res_conn)) { // 遍历连接资源
      $_str_get     = fgets($this->res_conn, 515); // 从连接资源取得消息
      $_str_return .= $_str_get; // 拼合

      if ((isset($_str_get[3]) && $_str_get[3] == ' ')) { // 第四个数组为返回消息
        break;
      }

      $info = stream_get_meta_data($this->res_conn); // 从连接资源用流取得消息
      if ($info['timed_out']) { // 超时
        break;
      }

      if (GK_NOW > $endtime) { // 超时
        break;
      }
    }

    return $_str_return;
  }
}
