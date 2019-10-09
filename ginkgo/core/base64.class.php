<?php
/*-----------------------------------------------------------------
！！！！警告！！！！
以下为系统文件，请勿修改
-----------------------------------------------------------------*/

namespace ginkgo;

//不能非法包含或直接执行
defined('IN_GINKGO') or exit('Access denied');

class Base64 {
    /**
     * URL base64解码
     * '-' -> '+'
     * '_' -> '/'
     * 字符串长度%4的余数，补'='
     * @param unknown $string
     */

    static function decode($string, $url_safe = true) {

        if ($url_safe) {
            $string = str_replace(array('-', '_'), array('+', '/'), $string);
        }

        $_num_mod4 = strlen($string) % 4;

        if ($_num_mod4) {
            $string .= substr('====', $_num_mod4);
        }

        return base64_decode($string);
    }



    /**
     * URL base64编码
     * '+' -> '-'
     * '/' -> '_'
     * '=' -> ''
     * @param unknown $string
     */

    static function encode($string, $url_safe = true) {

        $string = base64_encode($string);

        if ($url_safe) {
            $string = str_replace(array('+', '/', '='), array('-', '_', ''), $string);
        }

        return $string;
    }
}


