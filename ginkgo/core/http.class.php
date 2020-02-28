<?php
/*-----------------------------------------------------------------
！！！！警告！！！！
以下为系统文件，请勿修改
-----------------------------------------------------------------*/

namespace ginkgo;

//不能非法包含或直接执行
defined('IN_GINKGO') or exit('Access denied');

class Http extends File {

    protected static $instance;
    public $header = false;
    public $verifyPeer = false;
    public $verifyHost = false;
    public $caInfo;

    private $httpHeader = array(
        'Content-Type'  => 'application/x-www-form-urlencoded; charset=UTF-8',
        'Accept'        => 'application/json',
    );

    private $contentType;
    private $charset;

    private $accept = 'application/json';
    private $result;
    private $customRequest;
    private $errno;
    private $statusCode;
    private $sizeTemp = 0;

    protected function __construct($port = '') {
        $this->curl  = curl_init();
        $this->port  = $port;
    }

    protected function __clone() {

    }


    public static function instance() {
        if (Func::isEmpty(static::$instance)) {
            static::$instance = new static();
        }

        return static::$instance;
    }


    /** http 函数
     * fn_http function.
     *
     * @access public
     * @param mixed $data
     * @param string $method (default: 'get')
     * @return void
     */
    function request($url, $data = false, $method = 'get') {
        /*print_r($url);
        print_r(PHP_EOL);*/

        if (Func::isEmpty($url)) {
            $this->error = 'Missing URL';
            return false;
        }

        $_arr_urlParsed = parse_url($url);
        if (isset($_arr_urlParsed['port']) && !Func::isEmpty($_arr_urlParsed['port'])) {
            $this->port = $_arr_urlParsed['port'];
        }

        if ($data) {
            $_str_data  = http_build_query($data);
        } else {
            $_str_data  = '';
        }

        $method         = strtolower($method);
        $_arr_header    = array();

        if (!Func::isEmpty($this->httpHeader)) {
            // 发送头部信息
            foreach ($this->httpHeader as $_key=>$_value) {
                if (Func::isEmpty($_value)) {
                    $_arr_header[] = $_key;
                } else {
                    $_arr_header[] = $_key . ': ' . $_value;
                }
            }

            //print_r($_arr_header);
        }

        switch ($method) {
            case 'post':
                curl_setopt($this->curl, CURLOPT_POST, true); //post 方法
                curl_setopt($this->curl, CURLOPT_POSTFIELDS, $_str_data);
            break;

            case 'get':
                if (strpos($url, '?')) {
                    $_str_conn = '&';
                } else {
                    $_str_conn = '?';
                }
                curl_setopt($this->curl, CURLOPT_HTTPGET, true);  //get 方法
                $url = $url . $_str_conn . $_str_data;
            break;
        }

        curl_setopt($this->curl, CURLOPT_URL, $url);
        curl_setopt($this->curl, CURLOPT_CONNECTTIMEOUT, 30); //设置超时
        curl_setopt($this->curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($this->curl, CURLOPT_SSL_VERIFYPEER, $this->verifyPeer);
        curl_setopt($this->curl, CURLOPT_SSL_VERIFYHOST, $this->verifyHost);

        if (!Func::isEmpty($_arr_header)) {
            curl_setopt($this->curl, CURLOPT_HTTPHEADER, $_arr_header);
        }

        if ($this->header !== false) {
            curl_setopt($this->curl, CURLOPT_HEADER, $this->header);
        }

        if (!Func::isEmpty($this->caInfo)) {
            curl_setopt($this->curl, CURLOPT_CAINFO, $this->caInfo);
        }

        if (!Func::isEmpty($this->port)) {
            curl_setopt($this->curl, CURLOPT_PORT, $this->port);
        }

        if (!Func::isEmpty($this->customRequest)) {
            curl_setopt($this->curl, CURLOPT_CUSTOMREQUEST, $this->customRequest);
        }

        $_result = curl_exec($this->curl);
        $this->result = $_result;

        $_result = $this->resultProcess($_result);

        $this->statusCode   = curl_getinfo($this->curl, CURLINFO_HTTP_CODE);

        $this->error        = curl_error($this->curl);
        $this->errno        = curl_errno($this->curl);

        return $_result;
    }

    function setHeader($name, $value = '') {
        $this->httpHeader[$name] = $value;
    }

    function setPort($port = '') {
        $this->port = $port;
    }

    function setAccept($type = 'application/json') {
        $this->httpHeader['Accept'] = $type;

        $this->accept = $type;
    }

    function setCustomRequest($customRequest = '') {
        $this->customRequest = $customRequest;
    }

    function contentType($contentType, $charset = 'UTF-8') {
        $this->contentType  = $contentType;
        $this->charset      = $charset;
        $this->httpHeader['Content-Type'] = $contentType . '; Charset=' . $charset;
    }

    function getError() {
        return $this->error;
    }

    function getErrno() {
        return $this->errno;
    }

    function getStatusCode() {
        return $this->statusCode;
    }

    function getResult() {
        return $this->result;
    }

    function getRemote($url, $data = false, $method = 'get') {
        $_result    = $this->request($url, $data, $method);

        /*print_r($url . ' -> ' . $this->statusCode);
        print_r(PHP_EOL);*/

        if ($this->statusCode != '200' || $this->errno > 0) {
            return false;
        }

        $_tmp_path  = GK_PATH_TEMP . md5($url);
        $_num_size  = 0;

        $_num_size  = $this->fileWrite($_tmp_path, $this->result);

        if (!$_num_size) {
            return false;
        }

        $_str_mime  = $this->getMime($_tmp_path);
        $_str_ext   = $this->getExt($url, $_str_mime);

        if (!self::checkFile($_str_ext, $_str_mime)) {
            $this->fileDelete($_tmp_path);
            return false;
        }

        $_str_name  = basename($url);

        $_arr_fileInfo = array(
            'name'      => Func::safe($_str_name),
            'tmp_name'  => $_tmp_path,
            'ext'       => $_str_ext,
            'mime'      => $_str_mime,
            'size'      => $_num_size,
        );

        $this->fileInfo = $_arr_fileInfo;

        return $_arr_fileInfo;
    }

    function move($path_dir, $name = true, $replace = true) {
        $name = $this->genFilename($name);

        if (Func::isEmpty($name)) {
            $this->error = 'Missing filename';

            return false;
        }

        $_str_path = Func::fixDs($path_dir) . $name;

        if (!$replace && Func::isFile($_str_path)) {
            $this->error = 'Has the same filename: ' . $_str_path;

            return false;
        }

        return $this->fileMove($this->fileInfo['tmp_name'], $_str_path);
    }


    private function resultProcess($result) {
        switch ($this->accept) {
            case 'application/json':
                $result = Json::decode($result);
            break;
        }

        return $result;
    }

    function __destruct() {
        if ($this->curl != null) {
            curl_close($this->curl);
            $this->curl = null;
        }
    }
}