<?php
/*-----------------------------------------------------------------
！！！！警告！！！！
以下为系统文件，请勿修改
-----------------------------------------------------------------*/

namespace ginkgo;

//不能非法包含或直接执行
defined('IN_GINKGO') or exit('Access denied');

class Ubbcode {

    public static $pairRules    = array('strong', 'code', 'del', 'kbd', 'u', 'i', 'blockquote');
    public static $singleRules  = array('hr', 'br');

    public static $replaceRules = array(
        'b'     => 'strong',
        'em'    => 'i',
    );

    public static $pregRules = array(
        '/\[url\](.+?)\[\/url\]/i'       => '<a href="$1" target="_blank">$1</a>',
        '/\[url=(.+?)\](.+?)\[\/url\]/i' => '<a href="$1" target="_blank">$2</a>',
    );


    static function addPair($pair) {
        if (is_array($pair)) {
            self::$pairRules = array_merge(self::$pairRules, $pair);
        } else if (is_string($pair)) {
            self::$pairRules[] = $pair;
        }
    }


    static function addSingle($single) {
        if (is_array($single)) {
            self::$singleRules = array_merge(self::$singleRules, $single);
        } else if (is_string($single)) {
            self::$singleRules[] = $single;
        }
    }


    static function addReplace($src, $dst = '') {
        if (is_array($src)) {
            self::$replaceRules = array_replace_recursive(self::$replaceRules, $src);
        } else if (is_string($src)) {
            self::$replaceRules[$src] = $dst;
        }
    }

    static function addPreg($src, $dst = '') {
        if (is_array($src)) {
            self::$pregRules = array_replace_recursive(self::$pregRules, $src);
        } else if (is_string($src)) {
            self::$pregRules[$src] = $dst;
        }
    }


    static function convert($string) {
        $_arr_src = array();
        $_arr_dst = array();

        foreach (self::$pairRules as $_key=>$_value) {
            $_arr_src[] = '[' . $_value . ']';
            $_arr_src[] = '[/' . $_value . ']';
            $_arr_dst[] = '<' . $_value . '>';
            $_arr_dst[] = '</' . $_value . '>';
        }

        $string = str_ireplace($_arr_src, $_arr_dst, $string);

        $_arr_src = array();
        $_arr_dst = array();

        foreach (self::$replaceRules as $_key=>$_value) {
            $_arr_src[] = '[' . $_key . ']';
            $_arr_src[] = '[/' . $_key . ']';
            $_arr_dst[] = '<' . $_value . '>';
            $_arr_dst[] = '</' . $_value . '>';
        }

        $string = str_ireplace($_arr_src, $_arr_dst, $string);

        $_arr_src = array();
        $_arr_dst = array();

        foreach (self::$singleRules as $_key=>$_value) {
            $_arr_src[] = '[' . $_value . ']';
            $_arr_dst[] = '<' . $_value . '>';
        }

        $string = str_ireplace($_arr_src, $_arr_dst, $string);

        $_arr_regs = array();
        $_arr_dsts = array();

        foreach (self::$pregRules as $_key=>$_value) {
            if (strpos($_key, '/') === false && !preg_match('/\/[imsU]{0,4}$/', $_key)) {
                // 不是正则表达式则两端补上/
                $_key = '/^' . $_key . '$/';
            }

            $_arr_regs[] = $_key;
            $_arr_dsts[] = $_value;
        }

        $string = preg_replace($_arr_regs, $_arr_dsts, $string);

        return $string;
    }

}