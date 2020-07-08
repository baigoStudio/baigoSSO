<?php
/*-----------------------------------------------------------------
！！！！警告！！！！
以下为系统文件，请勿修改
-----------------------------------------------------------------*/

namespace ginkgo;

// 不能非法包含或直接执行
defined('IN_GINKGO') or exit('Access denied');

// 错误处理
class Error {

    // 错误类型
    private static $errType = array(
        E_ERROR              => 'Error - E_ERROR',
        E_CORE_ERROR         => 'Core Error - E_CORE_ERROR',
        E_COMPILE_ERROR      => 'Compile Error - E_COMPILE_ERROR',
        E_USER_ERROR         => 'User Error - E_USER_ERROR',
        E_RECOVERABLE_ERROR  => 'Catchable Fatal Error - E_RECOVERABLE_ERROR',

        E_PARSE              => 'Parsing Error - E_PARSE',

        E_WARNING            => 'Warning - E_WARNING',
        E_CORE_WARNING       => 'Core Warning - E_CORE_WARNING',
        E_COMPILE_WARNING    => 'Compile Warning - E_COMPILE_WARNING',
        E_USER_WARNING       => 'User Warning - E_USER_WARNING',

        E_NOTICE             => 'Notice - E_NOTICE',
        E_USER_NOTICE        => 'User Notice - E_USER_NOTICE',

        E_STRICT             => 'Runtime Notice - E_STRICT',
    );

    // 致命错误类型
    private static $errFatal = array(
        E_ERROR,
        E_PARSE,
        E_CORE_ERROR,
        E_COMPILE_ERROR,
        E_RECOVERABLE_ERROR,
        E_STRICT,
    );

    private static $configDebug; // 调试配置

    protected function __construct() {

    }

    protected function __clone() {

    }

    // 注册错误处理方法
    static function register() {
        self::$configDebug = Config::get('debug'); // 取得调试配置

        error_reporting(0); // 禁用系统报错
        error_reporting(E_ALL);
        libxml_use_internal_errors(true); // 禁止 html xml 解析报错
        set_error_handler(array(__CLASS__, 'appError')); // 注册错误处理方法
        set_exception_handler(array(__CLASS__, 'appException')); // 注册异常处理方法
        register_shutdown_function(array(__CLASS__, 'appShutdown')); // 注册关闭处理方法
    }


    /** 错误处理
     * appError function.
     *
     * @access public
     * @static
     * @param int $err_no 错误号
     * @param string $err_msg 错误消息
     * @param string $err_file 出错文件
     * @param int $err_line 出错行号
     * @return void
     */
    static function appError($err_no, $err_msg, $err_file, $err_line) {
        $_str_errType   = 'Unknown error'; // 默认消息

        //print_r($err_msg);

        if (isset(self::$errType[$err_no])) { // 设置错误类型
            $_str_errType = self::$errType[$err_no];
        }

        $_arr_error = array(
            'type'          => $err_no,
            'err_type'      => $_str_errType,
            'err_message'   => $err_msg,
        );

        if (self::$configDebug['dump']) { // 假如配置为输出
            $_arr_error['err_file'] = $err_file;
            $_arr_error['err_line'] = $err_line;
        }

        $_str_key = md5($err_no . $err_msg . $err_file . $err_line); // 生成错误号 (避免冲突)

        if (self::isFatal($err_no)) { // 如果是致命错误, 则直接报错
            self::sendErr($_arr_error);
        } else { // 否则只记录错误
            Debug::record($_str_key, $_arr_error);

            $_arr_error['err_file'] = $err_file;
            $_arr_error['err_line'] = $err_line;

            Log::record($_arr_error, 'error');
        }
    }



    /** 异常处理
     * appException function.
     *
     * @access public
     * @static
     * @param object $excpt 异常实例
     * @return void
     */
    static function appException($excpt) {
        $_str_type      = $excpt->getCode(); // 取得错误号
        $_str_errType   = 'Unknown error';

        if (isset(self::$errType[$_str_type])) { // 设置错误类型
            $_str_errType = self::$errType[$_str_type];
        }

        $_arr_error['err_level']    = 'Framework error';
        $_arr_error['err_type']     = $_str_errType;
        $_arr_error['status_code']  = 500;

        if (method_exists($excpt, 'getStatusCode')) {
            $_arr_error['status_code']  = $excpt->getStatusCode(); // 取得 http 状态码
        }

        if (method_exists($excpt, 'getData')) { // 取得错误详情
            $err_detail = $excpt->getData('err_detail');
        } else {
            $err_detail = '';
        }

        $err_message    = $excpt->getMessage(); // 错误消息
        $err_file       = $excpt->getFile(); // 出错文件
        $err_line       = $excpt->getLine(); // 出错行号

        if (self::$configDebug['dump']) {
            $_arr_error['err_message']  = $err_message;
            $_arr_error['err_file']     = $err_file;
            $_arr_error['err_line']     = $err_line;
            $_arr_error['err_detail']   = $err_detail;
        }

        //print_r($excpt->getTrace());

        unset($excpt); //销毁异常实例

        // 记录日志
        if (class_exists('ginkgo\Log')) {
            $_arr_errorRecord = $_arr_error;

            $_arr_errorRecord['err_message']  = $err_message;
            $_arr_errorRecord['err_file']     = $err_file;
            $_arr_errorRecord['err_line']     = $err_line;
            $_arr_errorRecord['err_detail']   = $err_detail;

            Log::record($_arr_errorRecord, 'excpt'); // 记录日志
        }

        self::sendErr($_arr_error); // 输出报错信息
    }


    /** 程序关闭处理
     * appShutdown function.
     *
     * @access public
     * @static
     * @return void
     */
    static function appShutdown() {
        $_error_last = error_get_last(); // 取得最后一个错误

        //print_r($_error_last);

        if (!Func::isEmpty($_error_last)) { // 假如有错误, 则处理
            if (self::isFatal($_error_last['type'])) { // 仅处理致命错误
                $_obj_except = new Exception($_error_last['message'], 500, $_error_last['type'], $_error_last['file'], $_error_last['line']);

                self::appException($_obj_except);
            }
        }

        Log::save(); // 写入日志

        //print_r((memory_get_usage() - GK_START_MEM) / 1024 / 1024);
    }


    /** 判断是否为致命错误
     * isFatal function.
     *
     * @access private
     * @static
     * @param mixed $type
     * @return 是否为致命错误 (bool)
     */
    private static function isFatal($type) {
        return in_array($type, self::$errFatal);
    }



    /** 输出报错信息
     * sendErr function.
     *
     * @access private
     * @static
     * @param array $error 错误数组
     * @return void
     */
    private static function sendErr($error) {
        if (!isset($error['status_code'])) { // 假如未设置 http 状态码, 则设为 500
            $error['status_code'] = 500;
        }

        $_obj_response  = Response::create('', '', $error['status_code']); // 实例化响应类
        $_obj_request   = Request::instance();

        $error['http_status'] = $_obj_response->getStatus(); // 设置响应状态

        $_arr_configDefault  = Config::get('var_default'); // 读取默认配置

        // 处理请求类型
        if ($_obj_request->isAjax()) {
            if (Func::isEmpty($_arr_configDefault['return_type_ajax'])) {
                $_str_type = $_arr_configDefault['return_type'];
            } else {
                $_str_type = $_arr_configDefault['return_type_ajax'];
            }
        } else {
            $_str_type = $_obj_request->type();
        }

        if ($_str_type == 'json') {
            $_str_content   = Json::encode($error);
        } else {

            // 用模板渲染错误
            $_str_content = View_Sys::fetch($error['status_code'], $error);
        }

        // 设置响应内容
        $_obj_response->setContent($_str_content);

        // 输出响应内容
        $_obj_response->send('error');
    }

}


