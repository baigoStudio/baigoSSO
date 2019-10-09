<?php
/*-----------------------------------------------------------------
！！！！警告！！！！
以下为系统文件，请勿修改
-----------------------------------------------------------------*/

namespace ginkgo;

//不能非法包含或直接执行
defined('IN_GINKGO') or exit('Access denied');

class Exception extends \Exception {

    private $statusCode;
    private static $data = array();

    function __construct($message, $statusCode = 0, $code = 0, $file = '', $line = '', \Exception $previous = null) {
        //print_r($statusCode);
        $this->message      = $message;
        $this->file         = $file;
        $this->line         = $line;
        $this->statusCode   = $statusCode;
        //$this->headers    = $headers;

        parent::__construct($message, $code, $previous);
    }

    function getStatusCode() {
        //print_r($this->statusCode);
        return $this->statusCode;
    }

    function setData($name, $data = array()) {
        self::$data[$name] = $data;
    }

    function getData($name = '') {
        $_value = '';
        if (Func::isEmpty($name)) {
            $_value = self::$data;
        } else {
            if (isset(self::$data[$name])) {
                $_value = self::$data[$name];
            }
        }

        return $_value;
    }
}
