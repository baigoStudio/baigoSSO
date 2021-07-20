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

// json 编码解码 兼容用
class Json extends Arrays {

    /** JSON 编码
     * encode function.
     *
     * @access public
     * @param string $array (default: '') 数组
     * @param string $encode (default: '') 指定编码形式
     * @param mixed $option (default: false) 编码选项
     * @return 编码后的字符串
     */
    static function encode($array = array(), $encode = 'json_safe', $option = false) {
        return parent::toJson($array, $encode, $option);
    }


    /** JSON 解码
     * decode function.
     *
     * @access public
     * @param string $string (default: '') json 字符串
     * @param string $decode (default: '') 指定解码形式
     * @param mixed $option (default: false) 解码选项
     * @return 解码后的数组
     */
    static function decode($string = '', $decode = false, $option = true) {
        return parent::fromJson($string, $decode, $option);
    }
}
