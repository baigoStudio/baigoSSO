<?php
/*-----------------------------------------------------------------
！！！！警告！！！！
以下为系统文件，请勿修改
-----------------------------------------------------------------*/

namespace ginkgo;

//不能非法包含或直接执行
defined('IN_GINKGO') or exit('Access denied');

class Json {

    private static $error = false;

    private static $errTypes = array(
        JSON_ERROR_DEPTH            => 'Maximum stack depth exceeded',
        JSON_ERROR_STATE_MISMATCH   => 'State mismatch (invalid or malformed JSON)',
        JSON_ERROR_CTRL_CHAR        => 'Control character error, possibly incorrectly encoded',
        JSON_ERROR_SYNTAX           => 'Syntax error',
    );

    /**
     * encode function.
     *
     * @access public
     * @param string $array (default: '')
     * @param string $encode (default: '')
     * @return void
     */
    static function encode($array = array(), $encode = 'json_safe', $option = false) {
        $_str_json   = '[]';

        if (is_array($array) && !Func::isEmpty($array)) {
            $array     = Func::arrayEach($array, $encode);
            $_str_json = json_encode($array, $option); //json编码

            self::backtrace();
        }

        return $_str_json;
    }


    /** JSON 解码
     * decode function.
     *
     * @access public
     * @param string $string (default: '')
     * @param string $encode (default: '')
     * @return void
     */
    static function decode($string = '', $decode = false, $option = true) {
        $_arr_json = array();

        if (!Func::isEmpty($string)) {
            //$string    = Html::decode($string, 'json');
            $_arr_json = json_decode($string, $option); //json解码
            $_arr_json = Func::arrayEach($_arr_json, $decode); //json解码

            self::backtrace();
        }

        if (!is_array($_arr_json)) {
            $_arr_json = array();
        }

        return $_arr_json;
    }


    static function getError() {
        return self::$error;
    }

    private static function backtrace() {
        $_err_no  = json_last_error();

        if ($_err_no) {
            if (isset(self::$errTypes[$_err_no])) {
                $_err_msg = self::$errTypes[$_err_no];
            } else {
                $_err_msg = 'Unknown';
            }

            self::$error = array(
                'err_type'      => $_err_no,
                'err_message'   => $_err_msg,
            );

            $_config = Config::get('dump', 'debug');

            if ($_config === 'trace') {
                $_arr_trace = debug_backtrace(false);

                if (isset($_arr_trace[1])) {
                    $_err_file = '';
                    $_err_line = 0;

                    if (isset($_arr_trace[1]['file'])) {
                        $_err_file = $_arr_trace[1]['file'];
                    }

                    if (isset($_arr_trace[1]['line'])) {
                        $_err_line = $_arr_trace[1]['line'];
                    }

                    self::$error['err_file'] = $_err_file;
                    self::$error['err_line'] = $_err_line;
                }
            }
        }
    }
}


