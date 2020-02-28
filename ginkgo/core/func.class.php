<?php
/*-----------------------------------------------------------------
！！！！警告！！！！
以下为系统文件，请勿修改
-----------------------------------------------------------------*/

namespace ginkgo;

//不能非法包含或直接执行
defined('IN_GINKGO') or exit('Access denied');

class Func {

    static function isEmpty($data) {
        if (!isset($data)) {
        	return true;
        }
    	if ($data === null) {
    		return true;
    	}
    	if (is_object($data)) {
        	if (empty($data)) {
        		return true;
        	}
        } else if (is_array($data)) {
        	if (count($data) < 1) {
        		return true;
        	}
    	} else if (is_numeric($data)) {
        	if ($data === 0) {
        		return false;
        	} else {
                if (empty($data) || trim($data) === '') {
                    return true;
                }
        	}
    	} else {
        	if (empty($data) || trim($data) === '' || trim($data) === 'NULL') {
        		return true;
        	}
    	}

    	return false;
    }

    static function isOdd($num) {
        if ($num % 2 == 0) {
            $_bool_return = false;
        } else {
            $_bool_return = true;
        }

        return $_bool_return;
    }

    static function isFile($path) {
        return is_file(strtolower($path));
    }


    static function strtotime($time) {
        $_arr_src = array('&#45;', '&#58;');

        $_arr_dst = array('-', ':');
        $time = str_replace($_arr_src, $_arr_dst, $time);

        $_tm_return = strtotime($time);

        return $_tm_return;
    }


    static function ucwords($string, $delimiter = "\t\r\n\f\v") {
        $string         = (string)$string;
        $string         = strtolower($string);

        $_str_return    = '';

        //print_r(version_compare(PHP_VERSION, '5.4.32', '>'));

        if (version_compare(PHP_VERSION, '5.4.32', '>')) {
            $_str_return = ucwords($string, $delimiter);
        } else {
            $_arr_string = explode($delimiter, $string);
            //print_r($_arr_string);
            foreach ($_arr_string as $_key=>$_value) {
                $_str_return .= $delimiter . ucfirst($_value);
            }
            $_str_return = trim($_str_return, $delimiter);
        }

        return $_str_return;
    }


    static function toHump($string, $delimiter = '_', $lcfirst = false) {
        $delimiter  = (string)$delimiter;
        $string     = (string)$string;
        $string     = strtolower($string);
        $string     = self::ucwords($string, $delimiter);
        $string     = str_replace($delimiter, '', $string);

        if ($lcfirst === true) {
            $string = lcfirst($string);
        }

        return $string;
    }


    static function toLine($string, $delimiter = '_') {
        $string = (string)$string;

        $_bool_result = preg_match_all('/([A-Z]{1})/', $string, $_arr_matches);

        //print_r($_arr_matches);

        if (isset($_arr_matches[0]) && !Func::isEmpty($_arr_matches[0])) {
            foreach ($_arr_matches[0] as $_key=>$_value) {
                $string = str_replace($_value, strtolower($delimiter . $_value), $string);
            }
        }

        return trim($string, $delimiter);
    }


    static function safe($string, $htmlmode = false) {
        //正则剔除
        $_arr_dangerRegs = array(
            /* -------- 跨站 --------*/

            //html 标签
            '/<(script|frame|iframe|blink|object|applet|embed|style|layer|ilayer|bgsound|link|base|meta).*>/i',

            //html 标签结束
            '/<\/(script|frame|iframe|blink|object|applet|embed|style|layer|ilayer)>/i',

            //html 事件
            '/on\w+\s*=\s*("|\')?\S*("|\')?/i',

            //html 属性包含脚本
            '/(java|vb)script:\s*\S*/i',

            //js 对象
            '/(document|location)\s*\.\s*\S*/i',

            //js 函数
            '/(eval|alert|prompt|msgbox)\s*\(.*\)/i',

            //css
            '/expression\s*:\s*\S*/i',

            /* -------- sql 注入 --------*/

            //显示 数据库 | 表 | 索引 | 字段
            '/show\s+(databases|tables|index|columns)/i',

            //创建 数据库 | 表 | 索引 | 视图 | 存储过程 | 存储过程
            '/create\s+(database|table|(unique\s+)?index|view|procedure|proc)/i',

            //更新 数据库 | 表
            '/alter\s+(database|table)/i',

            //丢弃 数据库 | 表 | 索引 | 视图 | 字段
            '/drop\s+(database|table|index|view|column)/i',

            //备份 数据库 | 日志
            '/backup\s+(database|log)/i',

            //初始化 表
            '/truncate\s+table/i',

            //替换 视图
            '/replace\s+view/i',

            //创建 | 更改 字段
            '/(add|change)\s+column/i',

            //选择 | 更新 | 删除 记录
            '/(select|update|delete)\s+\S*\s+from/i',

            //插入 记录 | 选择到文件
            '/insert\s+into/i',

            //sql 函数
            '/load_file\s*\(.*\)/i',

            //sql 其他
            '/(outfile|infile)\s+("|\')?\S*("|\')/i',
        );

        //特殊字符 直接剔除
        $_arr_dangerChars = array(
            "\t", "\r", "\n", PHP_EOL
        );

        /*$_arr_src = array('!', '$', '%', '\'', '(', ')', '+', '-', ':', '=', '?', '[', ']', '^', '`', '{', '}', '~');
        $_arr_dst = array('&#33;', '&#36;', '&#37;', '&#39;', '&#40;', '&#41;', '&#43;', '&#45;', '&#58;', '&#61;', '&#63;', '&#91;', '&#93;', '&#94;', '&#96;', '&#123;', '&#125;', '&#126;');*/

        $_str_return = trim($string);

        $_str_return = preg_replace($_arr_dangerRegs, '', $_str_return);

        $_str_return = str_replace($_arr_dangerChars, '', $_str_return);

        $_str_return = Html::encode($_str_return);

        //$_str_return = str_replace($_arr_src, $_arr_dst, $_str_return);

        //print_r($htmlmode);

        if ($htmlmode) {
            $_str_return = Html::decode($_str_return);
        }

        return trim($_str_return);
    }


    static function sizeFormat($size = 0, $float = 2) {
        $_return = 0;

        if ($size > 0) {
            $_arr_unit = array('B', 'KB', 'MB', 'GB', 'TB', 'PB');

            if (!is_numeric($size)) {
                $size = floatval($size);
            }

            if (!is_numeric($size)) {
                $size = 0;
            }

            $_return = number_format($size / pow(1024, ($_iii = floor(log($size, 1024)))), $float) . ' ' . $_arr_unit[$_iii];
        }

        return $_return;
    }


    static function numFormat($num, $float = 2) {
        if (!is_numeric($num)) {
            $num = floatval($num);
        }

        if (!is_numeric($num)) {
            $num = 0;
        }

        return number_format($num, $float);
    }


    static function strSecret($string, $left = 5, $right = 5, $hide = '*'){
        $string     = (string)$string;
        $_str_mid   = ''; //中间字符串
        if (function_exists('mb_strlen')) {
            $_num_len   = mb_strlen($string); //计算长度
        } else {
            $_num_len   = strlen($string);
        }

        if ($_num_len <= $left + $right) { //如果左右截取加起来大于等于长度, 则左右个保留一个字符
            $left   = 1;
            $right  = 1;
        }

        $_num_mid = $_num_len - $left - $right;

        if (function_exists('mb_substr')) {
            $_str_left      = mb_substr($string, 0, $left); //取左边字符
        } else {
            $_str_left      = substr($string, 0, $left);
        }
        if (function_exists('mb_substr')) {
            $_str_right     = mb_substr($string, 0 - $_num_len, $right); //取右边字符
        } else {
            $_str_right     = substr($string, 0 - $_num_len, $right);
        }

        for ($_iii = 0; $_iii < $_num_mid; ++$_iii) { //隐藏
            $_str_mid .= $hide;
        }

        return $_str_left . $_str_mid . $_str_right; //拼合返回
    }


    static function fixDs($path, $ds = DS) {
        $path = rtrim($path, '/\\') . $ds;

        return preg_replace('/([A-Za-z0-9\-\_]{1})(\\\|\/){2,}([A-Za-z0-9\-\_]{1})/i', '$1$2$3', $path);
    }


    static function fillUrl($url, $baseUrl) {
        $url        = strtolower($url);
        $url        = str_replace('\\', '/', $url);

        $baseUrl    = strtolower($baseUrl);
        $baseUrl    = str_replace('\\', '/', $baseUrl);

        $_arr_urlParsed = parse_url($baseUrl);

        //判断类型
        if (substr($url, 0, 2) != '//' && substr($url, 0, 8) != 'https://' && substr($url, 0, 7) != 'http://' && substr($url, 0, 7) != 'mailto:' && substr($url, 0, 11) != 'javascript:' && substr($url, 0, 1) != '#') { //http mailto javascript 开头的 url 类型要跳过
            $_str_urlRoot   = '';

            if (isset($_arr_urlParsed['scheme']) && !self::isEmpty($_arr_urlParsed['scheme'])) {
                $_str_urlRoot = $_arr_urlParsed['scheme'] . '://';
            }

            if (isset($_arr_urlParsed['host']) && !self::isEmpty($_arr_urlParsed['host'])) {
                $_str_urlRoot = $_str_urlRoot . $_arr_urlParsed['host'];
            }

            if (isset($_arr_urlParsed['port']) && !self::isEmpty($_arr_urlParsed['port'])) {
                $_str_urlRoot = $_str_urlRoot . ':' . $_arr_urlParsed['port'];
            }

            if (self::isEmpty($_str_urlRoot)) {
                return $url;
            }

            $_str_basePath = '';

            if (isset($_arr_urlParsed['path']) && $_arr_urlParsed['path'] != '\\') {
                $_str_basePath = $_arr_urlParsed['path'];

                if (stristr(basename($_str_basePath), '.')) {
                    $_str_basePath = dirname($_str_basePath);
                }
            }

            if (substr($url, 0, 1) == '/') { //绝对路径
                $_str_urlEnd = $url;
            } else if (substr($url, 0, 3) == '../') { //相对路径
                while (substr($url, 0, 3) == '../') {
                    $url = substr($url, strlen($url) - (strlen($url) - 3), strlen($url) - 3);
                    if (!self::isEmpty($_str_basePath)) {
                        $_str_basePath = dirname($_str_basePath);
                    }
                }
                $_str_urlEnd = $_str_basePath . '/' . $url;
            } else if (substr($url, 0, 2) == './') {
                $_str_urlEnd = $_str_basePath . substr($url, strlen($url) - (strlen($url) - 1), strlen($url) - 1);
            } else {
                $_str_urlEnd = $_str_basePath . '/' . $url;
            }

            $url = $_str_urlRoot . str_replace('//', '/', $_str_urlEnd);
        }

        if (substr($url, 0, 2) == '//') {
            if (isset($_arr_urlParsed['scheme']) && !self::isEmpty($_arr_urlParsed['scheme'])) {
                $url = $_arr_urlParsed['scheme'] . ':' . $url;
            }
        }

        return $url;
    }


    static function fillImg($content, $baseUrl) {
        $_pattern         = '/<img[^>]*src[=\"\'\s]+([^\.]*\/[^\.]+\.[^\"\']+)[\"\']?[^>]*>/i'; //匹配图片
        //$_pattern_2         = '/\ssrc=["|\']?.*?["|\']?\s/i'; //匹配图片src
        $_str_contentTemp   = Html::decode($content); //html解码
        $_str_contentTemp   = str_replace('\\', '', $_str_contentTemp); //替换反斜杠

        preg_match_all($_pattern, $_str_contentTemp, $_arr_match);

        //print_r($_arr_match);

        if (isset($_arr_match[1])) { //匹配成功
            $_arr_urlSrc    = array();
            $_arr_urlDst    = array();
            foreach ($_arr_match[1] as $_key=>$_value) { //遍历匹配结果
                $_str_urlSrc    = trim($_value);
                $_str_urlSrc    = str_ireplace('src=', '', $_str_urlSrc); //剔除属性
                $_str_urlSrc    = str_replace('"', '', $_str_urlSrc);
                $_str_urlSrc    = str_replace('\'', '', $_str_urlSrc);

                $_arr_urlSrc[]  = trim($_str_urlSrc);
                $_arr_urlDst[]  = self::fillUrl($_str_urlSrc, $baseUrl);
            }

            /*print_r($_arr_urlSrc);
            print_r('<br>');
            print_r($_arr_urlDst);
            print_r('<br>');*/

            $_arr_urlSrc = self::arrayFilter($_arr_urlSrc);
            $_arr_urlDst = self::arrayFilter($_arr_urlDst);

            $content = str_replace($_arr_urlSrc, $_arr_urlDst, $content);
        }

        return $content;
    }


    static function checkRegex($string, $regex, $wild = false) {
        $_str_reg = trim($regex);
        $_str_reg = preg_quote($_str_reg, '/');

        if ($wild == true) {
            $_str_reg = str_replace(array('\\*', ' '), array('.*', ''), $_str_reg);
            $_str_reg = '/^(' . $_str_reg . ')$/i';
        } else {
            $_str_reg = '/(' . $_str_reg . ')$/i';
        }

        $_str_reg = str_replace(array('\|', '|)'), array('|', ')'), $_str_reg);

        /*print_r($_str_reg . '<br>');
        preg_match($_str_reg, $string, $aaaa);
        print_r($aaaa);*/

        return preg_match($_str_reg, $string);
    }


    static function rand($length = 32) {
        $_str_char  = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789';
        $_str_rand  = '';
        while (strlen($_str_rand) < $length) {
            $_str_rand .= substr($_str_char, (rand(0, strlen($_str_char))), 1);
        }
        return $_str_rand;
    }


    static function arrayFilter($arr) {
        if (!self::isEmpty($arr)) {
            $arr = array_filter(array_unique($arr));
        }
        return $arr;
    }


    static function arrayEach($arr, $encode = '', $left = 5, $right = 5, $hide = '*') {
        if (is_array($arr) && !self::isEmpty($arr)) {
            foreach ($arr as $_key=>$_value) {
                if (is_array($_value) && !self::isEmpty($_value)) {
                    $arr[$_key] = self::arrayEach($_value, $encode, $left, $right, $hide);
                } else if (is_scalar($_value) && !self::isEmpty($_value)) {
                    //$_value = self::safe($_value);

                    switch ($encode) {
                        case 'json_safe':
                            $arr[$_key] = Html::decode($_value);
                        break;

                        case 'urlencode':
                            $arr[$_key] = rawurlencode($_value);
                        break;

                        case 'urldecode':
                            $arr[$_key] = rawurldecode($_value);
                        break;

                        case 'base64encode':
                            $arr[$_key] = Base64::encode($_value);
                        break;

                        case 'base64decode':
                            $arr[$_key] = Base64::decode($_value);
                        break;

                        /*case 'utf8encode':
                            $arr[$_key] = utf8_encode($_value);
                        break;

                        case 'utf8decode':
                            $arr[$_key] = utf8_decode($_value);
                        break;*/

                        case 'md5':
                            $arr[$_key] = md5($_value);
                        break;

                        case 'secret':
                            $arr[$_key] = self::strSecret($_value, $left, $right, $hide);
                        break;
                    }
                } else {
                    $arr[$_key] = '';
                }
            }
        } else {
            $arr = array();
        }

        return $arr;
    }
}


