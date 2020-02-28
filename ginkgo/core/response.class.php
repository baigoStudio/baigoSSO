<?php
/*-----------------------------------------------------------------
！！！！警告！！！！
以下为系统文件，请勿修改
-----------------------------------------------------------------*/

namespace ginkgo;

//不能非法包含或直接执行
defined('IN_GINKGO') or exit('Access denied');

class Response {

    protected $data;
    protected $statusCode;
    protected $header;
    protected $replace  = array();

    protected $statusRow = array(
        100 => 'Continue',
        101 => 'Switching Protocols',

        200 => 'OK',
        201 => 'Created',
        202 => 'Accepted',
        203 => 'Non-Authoritative Information',
        204 => 'No Content',
        205 => 'Reset Content',
        206 => 'Partial Content',

        300 => 'Multiple Choices',
        301 => 'Moved Permanently',
        302 => 'Found',
        303 => 'See Other',
        304 => 'Not Modified',
        305 => 'Use Proxy',
        307 => 'Temporary Redirect',

        400 => 'Bad Request',
        401 => 'Unauthorized',
        402 => 'Payment Required',
        403 => 'Forbidden',
        404 => 'Not Found',
        405 => 'Method Not Allowed',
        406 => 'Not Acceptable',
        407 => 'Proxy Authentication Required',
        408 => 'Request Time-out',
        409 => 'Conflict',
        410 => 'Gone',
        411 => 'Length Required',
        412 => 'Precondition Failed',
        413 => 'Request Entity Too Large',
        414 => 'Request-URI Too Large',
        415 => 'Unsupported Media Type',
        416 => 'Requested range not satisfiable',
        417 => 'Expectation Failed',

        500 => 'Internal Server Error',
        501 => 'Not Implemented',
        502 => 'Bad Gateway',
        503 => 'Service Unavailable',
        504 => 'Gateway Time-out',
        505 => 'HTTP Version not supported',
    );

    protected $charset;
    protected $type;
    protected $contentType = 'text/html';

    protected $config;

    public function __construct($data = '', $code = 200, $header = array()) {
        $this->config       = Config::get('var_default');

        $this->setStatusCode($code);
        $this->setContent($data);
        $this->setHeader($header);

        $this->obj_request  = Request::instance();
    }

    protected function __clone() {

    }

    public static function create($data = '', $type = '', $code = 200, $header = array()) {
        if (Func::isEmpty($type)) {
            return new static($data, $code, $header);
        } else {
            if (strpos($type, '\\')) {
                $_class = $type;
            } else {
                $_class = 'ginkgo\\response\\' . Func::ucwords($type, '_');
            }
        }

        if (class_exists($_class)) {
            return new $_class($data, $code, $header);
        } else {
            return new static($data, $code, $header);
        }
    }


    function setStatusCode($statusCode = 200) {
        $this->statusCode = $statusCode;
        $this->header['HTTP/1.0 ' . $this->getStatusCode()] = '';
    }

    function setHeader($header, $value = '') {
        if (is_array($header)) {
            $this->header = array_replace_recursive($this->header, $header);
        } else {
            $this->header[$header] = $value;
        }
    }

    function setContent($data) {
        $this->data = $data;

        //print_r($this->data);
        //print_r('<br>');
    }

    function setReplace($replace, $value = '') {
        if (is_array($replace)) {
            $this->replace = array_replace_recursive($this->replace, $replace);
        } else {
            $this->replace[$replace] = $value;
        }
    }

    function getStatus() {
        if (isset($this->statusRow[$this->statusCode])) {
            $_str_status = $this->statusRow[$this->statusCode];
        } else {
            $_str_status = $this->statusRow[200];
        }

        return $_str_status;
    }

    function getStatusCode() {
        return $this->statusCode;
    }

    function getHeader() {
        return $this->header;
    }

    function getContent() {
        return $this->output($this->data);
    }

    function expires($time) {
        $this->header['Expires'] = $time;
    }


    function cacheControl($cache) {
        $this->header['Cache-Control'] = $cache;
    }

    /**
     * 页面输出类型
     * @param string $contentType 输出类型
     * @param string $charset     输出编码
     * @return $this
     */
    function contentType($contentType, $charset = 'UTF-8') {
        $this->contentType  = $contentType;
        $this->charset      = $charset;
        $this->header['Content-Type'] = $contentType . '; Charset=' . $charset;
    }

    protected function output($data) {
        if (is_array($data)) {
            $data = Debug::inject($data, 'arr');
        } else if (is_string($data)) {
            $data = Debug::inject($data, 'str');
        }

        return $data;
    }

    function send($id = 0) {
        Plugin::listen('action_fw_response_send');

        $_str_content = $this->getContent();

        $_str_content = $this->replaceProcess($_str_content);

        if (!headers_sent() && !Func::isEmpty($this->header)) {
            //print_r($this->header);
            // 发送头部信息
            foreach ($this->header as $_key => $_value) {
                if (Func::isEmpty($_value)) {
                    header($_key);
                } else {
                    header($_key . ': ' . $_value);
                }
            }
        }

        Plugin::listen('action_fw_response_end');

        exit($_str_content);
    }


    protected function replaceProcess($content) {
        $_arr_replace = $this->replace;

        if (is_array($_arr_replace) && !Func::isEmpty($_arr_replace)) {
            $_arr_keys   = array_keys($_arr_replace);
            $_arr_values = array_values($_arr_replace);

            $content = str_ireplace($_arr_keys, $_arr_values, $content);
        }

        return $content;
    }
}


