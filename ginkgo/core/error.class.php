<?php
/*-----------------------------------------------------------------
！！！！警告！！！！
以下为系统文件，请勿修改
-----------------------------------------------------------------*/

namespace ginkgo;

//不能非法包含或直接执行
defined('IN_GINKGO') or exit('Access denied');

class Error {

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

    private static $errFatal = array(
        E_ERROR,
        E_PARSE,
        E_CORE_ERROR,
        E_COMPILE_ERROR,
        E_RECOVERABLE_ERROR,
        E_STRICT,
    );

    private static $configDebug;
    private static $lang;
    private static $obj;
    private static $obj_request;
    private static $suffix = GK_EXT_TPL; // 默认模板文件后缀

    protected function __construct() {

    }

    protected function __clone() {

    }

    //运行
    static function register() { //运行
        self::$configDebug = Config::get('debug');

        error_reporting(0);
        libxml_use_internal_errors(true); //禁止 html xml 解析报错
        set_error_handler(array(__CLASS__, 'appError'));
        set_exception_handler(array(__CLASS__, 'appException'));
        register_shutdown_function(array(__CLASS__, 'appShutdown'));

        self::$obj_request = Request::instance();
    }


    static function appError($err_no, $err_msg, $err_file, $err_line) {
        $_str_errType   = 'Unknown error';

        //print_r($err_msg);

        if (isset(self::$errType[$err_no])) {
            $_str_errType = self::$errType[$err_no];
        }

        $_arr_error = array(
            'type'          => $err_no,
            'err_type'      => $_str_errType,
            'err_message'   => $err_msg,
        );

        if (self::$configDebug['dump']) {
            $_arr_error['err_file'] = $err_file;
            $_arr_error['err_line'] = $err_line;
        }

        $_str_key = md5($err_no . $err_msg . $err_file . $err_line);

        if (self::isFatal($err_no)) {
            self::sendErr($_arr_error);
        } else {
            Debug::record($_str_key, $_arr_error);

            $_arr_error['err_file'] = $err_file;
            $_arr_error['err_line'] = $err_line;

            Log::record($_arr_error, 'error');
        }
    }


    static function appException($excpt) {
        $_str_type      = $excpt->getCode();
        $_str_errType   = 'Unknown error';

        if (isset(self::$errType[$_str_type])) {
            $_str_errType = self::$errType[$_str_type];
        }

        $_arr_error['err_level']    = 'Framework error';
        $_arr_error['err_type']     = $_str_errType;
        $_arr_error['status_code']  = 500;

        if (method_exists($excpt, 'getStatusCode')) {
            $_arr_error['status_code']  = $excpt->getStatusCode();
        }

        if (method_exists($excpt, 'getData')) {
            $err_detail = $excpt->getData('err_detail');
        } else {
            $err_detail = '';
        }

        $err_message    = $excpt->getMessage();
        $err_file       = $excpt->getFile();
        $err_line       = $excpt->getLine();

        if (self::$configDebug['dump']) {
            $_arr_error['err_message']  = $err_message;
            $_arr_error['err_file']     = $err_file;
            $_arr_error['err_line']     = $err_line;
            $_arr_error['err_detail']   = $err_detail;
        }

        //print_r($excpt->getTrace());

        unset($excpt);

        if (class_exists('ginkgo\Log')) {
            $_arr_errorRecord = $_arr_error;

            $_arr_errorRecord['err_message']  = $err_message;
            $_arr_errorRecord['err_file']     = $err_file;
            $_arr_errorRecord['err_line']     = $err_line;
            $_arr_errorRecord['err_detail']   = $err_detail;

            Log::record($_arr_errorRecord, 'excpt');
        }

        self::sendErr($_arr_error);
    }


    static function appShutdown() {
        $_error_last = error_get_last();

        //print_r($_error_last);

        if (!Func::isEmpty($_error_last)) {
            if (self::isFatal($_error_last['type'])) {
                $_obj_except = new Exception($_error_last['message'], 500, $_error_last['type'], $_error_last['file'], $_error_last['line']);

                self::appException($_obj_except);
            }
        }

        Log::save();

        //print_r((memory_get_usage() - GK_START_MEM) / 1024 / 1024);
    }


    private static function isFatal($type) {
        return in_array($type, self::$errFatal);
    }


    private static function sendErr($error) {
        if (!isset($error['status_code'])) {
            $error['status_code'] = 500;
        }

        $_obj_response  = Response::create('', '', $error['status_code']);

        $error['http_status'] = $_obj_response->getStatus();

        $_arr_configDefault  = Config::get('var_default');

        if (self::$obj_request->isAjax()) {
            if (Func::isEmpty($_arr_configDefault['return_type_ajax'])) {
                $_str_type = $_arr_configDefault['return_type'];
            } else {
                $_str_type = $_arr_configDefault['return_type_ajax'];
            }
        } else {
            $_str_type = self::$obj_request->type();
        }

        if ($_str_type == 'json') {
            $_str_content   = Json::encode($error);
        } else {
            self::$lang     = Lang::instance();
            self::$lang->range('__ginkgo__');  //设置语言作用域

            self::$obj['lang'] = self::$lang;

            $_str_content = self::fetch($error['status_code'], $error);
        }

        $_obj_response->setContent($_str_content);

        $_obj_response->send();
    }


    public static function fetch($tpl = '', $data = array()) {
        $_arr_configTplSys     = Config::get('tpl_sys');
        $_arr_configExceptPage = Config::get('exception_page');

        if (!Func::isEmpty($_arr_configTplSys['suffix'])) {
            self::$suffix = $_arr_configTplSys['suffix'];
        }

        $_str_pathTpl = GK_PATH_TPL;

        if (!Func::isEmpty($_arr_configTplSys['path'])) {
            if (strpos($_arr_configTplSys['path'], DS) !== false) {
                $_str_pathTpl = Func::fixDs($_arr_configTplSys['path']);
            } else {
                $_str_pathTpl .= Func::fixDs($_arr_configTplSys['path']);
            }
        }

        if (self::$configDebug['dump']) {
            $_str_tpl = $_str_pathTpl . 'exception' . self::$suffix;

            if (!Func::isFile($_str_tpl)) {
                return '<pre>' . var_export($data, true) . '</pre>';
            }

            if (!Func::isEmpty($data)) {
                extract($data, EXTR_OVERWRITE);
            }
        } else {
            if (!Func::isEmpty($tpl) && isset($_arr_configExceptPage[$tpl])) {
                $_str_tplName = $_arr_configExceptPage[$tpl];
                if (strpos($_str_tplName, DS) !== false) {
                    $_str_tpl = $_str_tplName;
                } else {
                    $_str_tpl = $_str_pathTpl . $_str_tplName . self::$suffix;
                }
            } else if (!Func::isEmpty($tpl)) {
                $_str_tpl = $_str_pathTpl . $tpl . self::$suffix;
            }

            $_str_ext = pathinfo($_str_tpl, PATHINFO_EXTENSION);

            if (Func::isEmpty($_str_ext)) {
                $_str_tpl .= self::$suffix;
            }

            if (!Func::isFile($_str_tpl)) {
                return '<div>' . $data['http_status'] . '</div>';
            }
        }

        $_str_content = '';

        ob_start();
        ob_implicit_flush(0);

        if (!Func::isEmpty(self::$obj)) {
            extract(self::$obj, EXTR_OVERWRITE);
        }

        require($_str_tpl);
        $_str_content = ob_get_clean();
        //ob_end_clean();

        $_str_urlBase       = Func::fixDs(self::$obj_request->baseUrl(true), '/');
        $_str_urlRoot       = Func::fixDs(self::$obj_request->root(true), '/');
        $_str_dirRoot       = Func::fixDs(self::$obj_request->root(), '/');
        $_str_routeRoot     = Func::fixDs(self::$obj_request->baseUrl(), '/');

        $_arr_replaceSrc = array(
            '{:URL_BASE}',
            '{:URL_ROOT}',
            '{:DIR_ROOT}',
            '{:DIR_STATIC}',
            '{:ROUTE_ROOT}',
        );

        $_arr_replaceDst = array(
            $_str_urlBase,
            $_str_urlRoot,
            $_str_dirRoot,
            $_str_dirRoot . GK_NAME_STATIC . '/',
            $_str_routeRoot,
        );

        $_str_content = str_ireplace($_arr_replaceSrc, $_arr_replaceDst, $_str_content);

        return $_str_content;
    }
}


