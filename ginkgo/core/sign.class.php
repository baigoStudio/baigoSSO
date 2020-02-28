<?php
/*-----------------------------------------------------------------
！！！！警告！！！！
以下为系统文件，请勿修改
-----------------------------------------------------------------*/

namespace ginkgo;

//不能非法包含或直接执行
defined('IN_GINKGO') or exit('Access denied');

class Sign {

    //生成签名
    static function make($string, $salt = '', $is_upper = true) {
        $_str_sign = md5($string . $salt);
        if ($is_upper) {
            $_str_sign = strtoupper($_str_sign);
        }
    	return $_str_sign;
    }

    //验证签名
    static function check($string, $sign, $salt = '', $is_upper = true) {
        $_str_signChk = self::make($string, $salt, $is_upper);

        /*print_r($_str_signChk);
        print_r('<br>');
        print_r($sign);*/

        if ($_str_signChk == $sign) {
            return true;
        } else {
            return false;
        }
    }
}
