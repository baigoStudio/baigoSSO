<?php
/*-----------------------------------------------------------------
！！！！警告！！！！
以下为系统文件，请勿修改
-----------------------------------------------------------------*/

namespace ginkgo;

// 不能非法包含或直接执行
defined('IN_GINKGO') or exit('Access denied');

// 异常处理
class Exception extends \Exception { // 继承 php 异常处理类

    private $statusCode; // http 状态码
    private $data = array(); // 异常内容

    /** 构造函数
     * __construct function.
     *
     * @access public
     * @param string $message 异常消息
     * @param int $statusCode (default: 0) http 状态码
     * @param int $code (default: 0) 异常码
     * @param string $file (default: '') 异常文件
     * @param string $line (default: '') 异常行号
     * @param \Exception $previous (default: null) 上一个异常
     * @return void
     */
    function __construct($message, $statusCode = 0, $code = 0, $file = '', $line = '', \Exception $previous = null) {
        //print_r($statusCode);
        $this->message      = $message;
        $this->file         = $file;
        $this->line         = $line;
        $this->statusCode   = $statusCode;
        //$this->headers    = $headers;

        parent::__construct($message, $code, $previous);
    }

    /** 取得 http 状态码
     * getStatusCode function.
     *
     * @access public
     * @return http 状态码
     */
    function getStatusCode() {
        //print_r($this->statusCode);
        return $this->statusCode;
    }

    /** 设置异常详细内容
     * setData function.
     *
     * @access public
     * @param string $name 名称
     * @param array $data (default: array()) 内容
     * @return void
     */
    function setData($name, $data = array()) {
        $this->data[$name] = $data;
    }

    /** 取得异常详细内容
     * getData function.
     *
     * @access public
     * @param string $name (default: '') 名称
     * @return 异常内容
     */
    function getData($name = '') {
        $_value = '';
        if (Func::isEmpty($name)) {
            $_value = $this->data;
        } else {
            if (isset($this->data[$name])) {
                $_value = $this->data[$name];
            }
        }

        return $_value;
    }
}
