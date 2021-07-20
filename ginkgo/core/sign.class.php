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

// 签名类
class Sign {

    /** 生成签名
     * make function.
     *
     * @access public
     * @static
     * @param string $string 待签名字符
     * @param string $salt (default: '') 盐
     * @param bool $is_upper (default: true) 是否大写
     * @return 签名
     */
    public static function make($string, $salt = '', $is_upper = true) {
        $_str_sign = md5($string . $salt);
        if ($is_upper) {
            $_str_sign = strtoupper($_str_sign);
        }
    	return $_str_sign;
    }

    /** 验证签名
     * check function.
     *
     * @access public
     * @static
     * @param string $string 待签名字符
     * @param string $sign 签名
     * @param string $salt (default: '') 盐
     * @param bool $is_upper (default: true) 是否大写
     * @return 验证结果
     */
    public static function check($string, $sign, $salt = '', $is_upper = true) {
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
