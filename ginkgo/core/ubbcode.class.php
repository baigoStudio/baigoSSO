<?php
/*-----------------------------------------------------------------
！！！！警告！！！！
以下为系统文件，请勿修改
-----------------------------------------------------------------*/

namespace ginkgo;

// 不能非法包含或直接执行
defined('IN_GINKGO') or exit('Access denied');

// Ubb code
class Ubbcode {

    // 成对规则
    public static $pairRules    = array('strong', 'code', 'del', 'kbd', 'u', 'i', 'blockquote', 'h1', 'h2', 'h3', 'h4', 'h5', 'h6');

    // 单独规则
    public static $singleRules  = array('hr', 'br');

    // 替换规则
    public static $replaceRules = array(
        'quote' => 'blockquote',
        'b'     => 'strong',
        'em'    => 'i',
        's'     => 'del',
    );

    // 正则规则
    public static $pregRules = array(
        '/\[url\](.+?)\[\/url\]/i'               => '<a href="$1" target="_blank">$1</a>',
        '/\[url=(.+?)\](.+?)\[\/url\]/i'         => '<a href="$1" target="_blank" title="$1">$2</a>',
        '/\[img\](.+?)\[\/img\]/i'               => '<img src="$1">',
        '/\[img=(.+?)\](.+?)\[\/img\]/i'         => '<img src="$1" alt="$2" title="$2">',
        '/\[color=(.+?)\](.+?)\[\/color\]/i'     => '<span style="color:$1">$2</span>',
        '/\[bgcolor=(.+?)\](.+?)\[\/bgcolor\]/i' => '<span style="background-color:$1">$2</span>',
        '/\[size=(.+?)\](.+?)\[\/size\]/i'       => '<span style="font-size:$1">$2</span>',
    );


    // 添加成对规则
    static function addPair($pair) {
        if (is_array($pair)) {
            self::$pairRules = array_merge(self::$pairRules, $pair);
        } else if (is_string($pair)) {
            self::$pairRules[] = $pair;
        }
    }


    // 添加单独规则
    static function addSingle($single) {
        if (is_array($single)) {
            self::$singleRules = array_merge(self::$singleRules, $single);
        } else if (is_string($single)) {
            self::$singleRules[] = $single;
        }
    }


    // 添加替换规则
    static function addReplace($src, $dst = '') {
        if (is_array($src)) {
            self::$replaceRules = array_replace_recursive(self::$replaceRules, $src);
        } else if (is_string($src)) {
            self::$replaceRules[$src] = $dst;
        }
    }

    // 添加正则规则
    static function addPreg($src, $dst = '') {
        if (is_array($src)) {
            self::$pregRules = array_replace_recursive(self::$pregRules, $src);
        } else if (is_string($src)) {
            self::$pregRules[$src] = $dst;
        }
    }


    // 去除标签
    static function stripCode($string) {
        $_arr_regs = array(
            '/\[img=(.+?)\](.+?)\[\/img\]/i',
            '/\[img\](.+?)\[\/img\]/i',
            '/\[(.+?)\]/i',
            '/\[(.+?)=(.+?)\]/i',
            '/\[\/(.+?)\]/i',
        );

        $string = preg_replace($_arr_regs, '', $string);

        return $string;
    }

    // 转换 ubbcode
    static function convert($string) {
        $_arr_src = array();
        $_arr_dst = array();

        // 转换成对规则
        foreach (self::$pairRules as $_key=>$_value) {
            $_arr_src[] = '[' . $_value . ']';
            $_arr_src[] = '[/' . $_value . ']';
            $_arr_dst[] = '<' . $_value . '>';
            $_arr_dst[] = '</' . $_value . '>';
        }

        $string = str_ireplace($_arr_src, $_arr_dst, $string);

        $_arr_src = array();
        $_arr_dst = array();

        // 转换替换规则
        foreach (self::$replaceRules as $_key=>$_value) {
            $_arr_src[] = '[' . $_key . ']';
            $_arr_src[] = '[/' . $_key . ']';
            $_arr_dst[] = '<' . $_value . '>';
            $_arr_dst[] = '</' . $_value . '>';
        }

        $string = str_ireplace($_arr_src, $_arr_dst, $string);

        $_arr_src = array();
        $_arr_dst = array();

        // 转换单独规则
        foreach (self::$singleRules as $_key=>$_value) {
            $_arr_src[] = '[' . $_value . ']';
            $_arr_dst[] = '<' . $_value . '>';
        }

        $string = str_ireplace($_arr_src, $_arr_dst, $string);

        $_arr_regs = array();
        $_arr_dsts = array();

        // 转换正则规则
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