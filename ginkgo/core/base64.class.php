<?php
/*-----------------------------------------------------------------
！！！！警告！！！！
以下为系统文件，请勿修改
-----------------------------------------------------------------*/

namespace ginkgo;

// 不能非法包含或直接执行
defined('IN_GINKGO') or exit('Access denied');

// base64 编码解码
class Base64 {

    /** 解码
     * decode function.
     *
     * @access public
     * @static
     * @param string $string 待解码
     * @param bool $url_safe (default: true) 是否用 url 安全的形式解码
     * @return 解码后字符串
     */
    static function decode($string, $url_safe = true) {
        $string = (string)$string; //转换为字符串类型

        if ($url_safe) {
            $string = str_replace(array('-', '_'), array('+', '/'), $string); // 替换 URL 传输时容易出错的字符
        }

        $_num_mod4 = strlen($string) % 4;

        if ($_num_mod4) {
            $string .= substr('====', $_num_mod4);
        }

        return base64_decode($string);
    }


    /** 编码
     * encode function.
     *
     * @access public
     * @static
     * @param string $string 待编码
     * @param bool $url_safe (default: true) 是否用 url 安全的形式编码
     * @return 编码后字符串
     */
    static function encode($string, $url_safe = true) {
        $string = (string)$string; //转换为字符串类型

        $string = base64_encode($string);

        if ($url_safe) {
            $string = str_replace(array('+', '/', '='), array('-', '_', ''), $string); // 替换 URL 传输时容易出错的字符
        }

        return $string;
    }
}


