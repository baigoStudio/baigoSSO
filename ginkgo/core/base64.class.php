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

/** 以下为即将废弃的方法，供向下兼容 */

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
    public static function decode($string, $url_safe = true) {
        return Strings::fromBase64($string, $url_safe);
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
    public static function encode($string, $url_safe = true) {
        return Strings::toBase64($string, $url_safe);
    }
}
