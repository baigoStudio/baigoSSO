<?php
/*-----------------------------------------------------------------
！！！！警告！！！！
以下为系统文件，请勿修改
-----------------------------------------------------------------*/

namespace ginkgo;

// 不能非法包含或直接执行
defined('IN_GINKGO') or exit('Access denied');

// 常用函数
class Func {

    /** 是否为空
     * isEmpty function.
     *
     * @access public
     * @static
     * @param mixed $data 待判断变量
     * @return bool
     */
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

    /** 是否为奇数
     * isOdd function.
     *
     * @access public
     * @static
     * @param number $num 数字
     * @return bool
     */
    static function isOdd($num) {
        if ($num % 2 == 0) {
            $_bool_return = false;
        } else {
            $_bool_return = true;
        }

        return $_bool_return;
    }

    /** 文件是否存在
     * isFile function.
     *
     * @access public
     * @static
     * @param string $path 路径
     * @return bool
     */
    static function isFile($path) {
        return is_file(strtolower($path));
    }


    /** 强化版 strtotime, 可以自动过滤转义过的符号
     * strtotime function.
     *
     * @access public
     * @static
     * @param string $time 日期时间字符串
     * @return 时间戳
     */
    static function strtotime($time) {
        $_arr_src = array('&#45;', '&#58;');

        $_arr_dst = array('-', ':');
        $time = str_replace($_arr_src, $_arr_dst, $time);

        $_tm_return = strtotime($time);

        return $_tm_return;
    }


    /** 将字符串中每个单词的首字母转换为大写
     * ucwords function.
     *
     * @access public
     * @static
     * @param string $string 字符串
     * @param string $delimiter (default: "\t\r\n\f\v") 分隔符
     * @return 转换后的字符串
     */
    static function ucwords($string, $delimiter = "\t\r\n\f\v") {
        $string         = (string)$string;
        $string         = strtolower($string);

        $_str_return    = '';

        //print_r(version_compare(PHP_VERSION, '5.4.32', '>'));

        if (version_compare(PHP_VERSION, '5.4.32', '>')) { // 大于 5.4.32, 直接使用 php 函数
            $_str_return = ucwords($string, $delimiter);
        } else { // 否则, 另行处理
            $_arr_string = explode($delimiter, $string); // 用分隔符转换成数组
            //print_r($_arr_string);
            foreach ($_arr_string as $_key=>$_value) { // 遍历
                $_str_return .= $delimiter . ucfirst(strtolower($_value)); // 首字母大写并拼合
            }

            $_str_return = trim($_str_return, $delimiter); // 去除首尾的分隔符
        }

        return $_str_return;
    }


    /** 转换为驼峰命名
     * toHump function.
     *
     * @access public
     * @static
     * @param string $string 字符串
     * @param string $delimiter (default: '_') 分隔符
     * @param bool $lcfirst (default: false) 首字母是否小写
     * @return 转换后的字符串
     */
    static function toHump($string, $delimiter = '_', $lcfirst = false) {
        $delimiter  = (string)$delimiter; // 转换为字符串类型
        $string     = (string)$string; // 转换为字符串类型
        $string     = self::ucwords($string, $delimiter); // 将字符串中每个单词的首字母转换为大写
        $string     = str_replace($delimiter, '', $string); // 去除分隔符

        if ($lcfirst === true) {
            $string = lcfirst($string); // 首字母小写
        }

        return $string;
    }


    /** 转换为分隔符命名
     * toLine function.
     *
     * @access public
     * @static
     * @param string $string 字符串
     * @param string $delimiter (default: '_') 分隔符
     * @return void
     */
    static function toLine($string, $delimiter = '_') {
        $string = (string)$string; // 转换为字符串类型

        $_bool_result = preg_match_all('/([A-Z]{1})/', $string, $_arr_matches); // 正则匹配

        //print_r($_arr_matches);

        if (isset($_arr_matches[0]) && !Func::isEmpty($_arr_matches[0])) {
            foreach ($_arr_matches[0] as $_key=>$_value) { // 便利匹配结果
                $string = str_replace($_value, strtolower($delimiter . $_value), $string); // 全部转换为小写, 并拼合
            }
        }

        return trim($string, $delimiter); // 去除首尾的分隔符
    }


    /** 安全过滤函数
     * safe function.
     *
     * @access public
     * @static
     * @param string $string 字符串
     * @param bool $htmlmode (default: false) 支持 html
     * @return 过滤后的字符串
     */
    static function safe($string, $htmlmode = false) {

        //正则剔除
        $_arr_dangerRegs = array(
            /*-------- 跨站 --------*/

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

            /*-------- sql 注入 --------*/

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

        // 特殊字符
        $_arr_replace = array(
            /*'!'  => '&#33;',
            '$'  => '&#36;',
            '%'  => '&#37;',
            '\'' => '&#39;',*/
            '('  => '&#40;',
            ')'  => '&#41;',
            /*'*'  => '&#42;',
            '+'  => '&#43;',
            '-'  => '&#45;',
            ':'  => '&#58;',
            '='  => '&#61;',
            '?'  => '&#63;',
            '['  => '&#91;',
            ']'  => '&#93;',
            '^'  => '&#94;',*/
            '`'  => '&#96;',
            /*'{'  => '&#123;',
            '}'  => '&#125;',
            '~'  => '&#126;'*/
        );

        $_str_return = trim($string);

        $_str_return = preg_replace($_arr_dangerRegs, '', $_str_return);

        $_str_return = str_replace($_arr_dangerChars, '', $_str_return);

        $_str_return = Html::encode($_str_return);

        $_str_return = str_replace(array_keys($_arr_replace), array_values($_arr_replace), $_str_return);

        //print_r($htmlmode);

        if ($htmlmode) {
            $_str_return = Html::decode($_str_return); // 假如为 html 模式, 则用 html 解码
        }

        return trim($_str_return);
    }


    /** 容量格式化
     * sizeFormat function.
     *
     * @access public
     * @static
     * @param int $size (default: 0) 容量
     * @param int $float (default: 2) 小数点
     * @return 格式化后的容量字符串
     */
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


    /** 数字格式化
     * numFormat function.
     *
     * @access public
     * @static
     * @param int $num 数字
     * @param int $float (default: 2) 小数点
     * @return 格式化后的数字
     */
    static function numFormat($num, $float = 2) {
        if (!is_numeric($num)) {
            $num = floatval($num);
        }

        if (!is_numeric($num)) {
            $num = 0;
        }

        return number_format($num, $float);
    }


    /** 隐私字符串
     * strSecret function.
     *
     * @access public
     * @static
     * @param string $string 字符串
     * @param int $left (default: 5) 保留左边字符数
     * @param int $right (default: 5) 保留右边字符数
     * @param string $hide (default: '*') 替换字符
     * @return 隐私处理后的字符串
     */
    static function strSecret($string, $left = 5, $right = 5, $hide = '*'){
        $string     = (string)$string;
        $_str_mid   = ''; // 中间字符串
        if (function_exists('mb_strlen')) {
            $_num_len   = mb_strlen($string); // 计算长度
        } else {
            $_num_len   = strlen($string);
        }

        if ($_num_len <= $left + $right) { // 如果左右截取加起来大于等于长度, 则左右个保留一个字符
            $left   = 1;
            $right  = 1;
        }

        $_num_mid = $_num_len - $left - $right;

        if (function_exists('mb_substr')) {
            $_str_left      = mb_substr($string, 0, $left); // 取左边字符
        } else {
            $_str_left      = substr($string, 0, $left);
        }
        if (function_exists('mb_substr')) {
            $_str_right     = mb_substr($string, 0 - $_num_len, $right); // 取右边字符
        } else {
            $_str_right     = substr($string, 0 - $_num_len, $right);
        }

        for ($_iii = 0; $_iii < $_num_mid; ++$_iii) { // 隐藏
            $_str_mid .= $hide;
        }

        return $_str_left . $_str_mid . $_str_right; // 拼合返回
    }


    /** 处理目录分隔符, 将多余的分隔符去除并在最后补全分隔符
     * fixDs function.
     *
     * @access public
     * @static
     * @param mixed $path
     * @param mixed $ds (default: DS)
     * @return 处理后的路径
     */
    static function fixDs($path, $ds = DS) {
        $path = rtrim($path, '/\\') . $ds;

        return preg_replace('/([A-Za-z0-9\-\_]{1})(\\\|\/){2,}([A-Za-z0-9\-\_]{1})/i', '$1$2$3', $path);
    }


    /** 补全 url
     * fillUrl function.
     *
     * @access public
     * @static
     * @param string $url 路径
     * @param string $baseUrl 基本路径
     * @return 补全后的 url
     */
    static function fillUrl($url, $baseUrl) {
        $url        = strtolower($url); // 转换为小写
        $url        = str_replace('\\', '/', $url); // 替换目录分隔符

        $baseUrl    = strtolower($baseUrl); // 转换为小写
        $baseUrl    = str_replace('\\', '/', $baseUrl); // 替换目录分隔符

        $_arr_urlParsed = parse_url($baseUrl); // 解析 url

        //判断类型
        if (substr($url, 0, 2) != '//' && substr($url, 0, 8) != 'https://' && substr($url, 0, 7) != 'http://' && substr($url, 0, 7) != 'mailto:' && substr($url, 0, 11) != 'javascript:' && substr($url, 0, 1) != '#') { //http mailto javascript 开头的 url 类型要跳过
            $_str_urlRoot   = '';

            if (isset($_arr_urlParsed['scheme']) && !self::isEmpty($_arr_urlParsed['scheme'])) {
                $_str_urlRoot = $_arr_urlParsed['scheme'] . '://'; // 协议名
            }

            if (isset($_arr_urlParsed['host']) && !self::isEmpty($_arr_urlParsed['host'])) {
                $_str_urlRoot = $_str_urlRoot . $_arr_urlParsed['host']; // 主机
            }

            if (isset($_arr_urlParsed['port']) && !self::isEmpty($_arr_urlParsed['port'])) {
                $_str_urlRoot = $_str_urlRoot . ':' . $_arr_urlParsed['port']; // 端口
            }

            if (self::isEmpty($_str_urlRoot)) {
                return $url; // 如果没有主机部分则直接返回
            }

            $_str_basePath = '';

            if (isset($_arr_urlParsed['path']) && $_arr_urlParsed['path'] != '\\') { // 如果有路径部分
                $_str_basePath = $_arr_urlParsed['path'];

                if (stristr(basename($_str_basePath), '.')) { // 如果路径带有扩展名, 则只截取目录部分
                    $_str_basePath = dirname($_str_basePath);
                }
            }

            if (substr($url, 0, 1) == '/') { // 绝对路径
                $_str_urlEnd = $url;
            } else if (substr($url, 0, 3) == '../') { // 相对路径
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


    /** 补全 html 标签的图片地址部分
     * fillImg function.
     *
     * @access public
     * @static
     * @param string $content html 全文
     * @param string $baseUrl 基本 url
     * @return 补全后的 html
     */
    static function fillImg($content, $baseUrl) {
        $_pattern         = '/<img[^>]*src[=\"\'\s]+([^\.]*\/[^\.]+\.[^\"\']+)[\"\']?[^>]*>/i'; // 图片标签的正则
        //$_pattern_2         = '/\ssrc=["|\']?.*?["|\']?\s/i'; // 匹配图片src
        $_str_contentTemp   = Html::decode($content); // html 解码
        $_str_contentTemp   = str_replace('\\', '', $_str_contentTemp); // 替换反斜杠

        preg_match_all($_pattern, $_str_contentTemp, $_arr_match); // 匹配图片

        //print_r($_arr_match);

        if (isset($_arr_match[1])) { //匹配成功
            $_arr_urlSrc    = array();
            $_arr_urlDst    = array();
            foreach ($_arr_match[1] as $_key=>$_value) { // 遍历匹配结果
                $_str_urlSrc    = trim($_value);
                $_str_urlSrc    = str_ireplace('src=', '', $_str_urlSrc); // 剔除属性
                $_str_urlSrc    = str_replace('"', '', $_str_urlSrc);
                $_str_urlSrc    = str_replace('\'', '', $_str_urlSrc);

                $_arr_urlSrc[]  = trim($_str_urlSrc); // 源路径
                $_arr_urlDst[]  = self::fillUrl($_str_urlSrc, $baseUrl); // 补全后的替换路径
            }

            /*print_r($_arr_urlSrc);
            print_r('<br>');
            print_r($_arr_urlDst);
            print_r('<br>');*/

            $_arr_urlSrc = self::arrayFilter($_arr_urlSrc); // 剔除重复项目
            $_arr_urlDst = self::arrayFilter($_arr_urlDst);

            $content = str_replace($_arr_urlSrc, $_arr_urlDst, $content); // 替换
        }

        return $content;
    }


    /** 取得正则匹配结果
     * getRegex function.
     *
     * @access public
     * @static
     * @param string $string 待匹配字符串
     * @param string $regex 正则字符串
     * @param bool $wild (default: false) 是否有通配符
     * @return 匹配结果数组
     */
    static function getRegex($string, $regex, $wild = false) {
        $_str_reg = trim($regex);
        $_str_reg = preg_quote($_str_reg, '/');

        if ($wild === true) {
            $_str_reg = str_replace(array('\\*', ' '), array('.*', ''), $_str_reg);
            $_str_reg = '/^(' . $_str_reg . ')$/i';
        } else {
            $_str_reg = '/(' . $_str_reg . ')$/i';
        }

        $_str_reg = str_replace(array('\|', '|)'), array('|', ')'), $_str_reg);

        $_bool_result = preg_match($_str_reg, $string, $_arr_matches);

        return array(
            'result'   => $_bool_result, // 是否成功
            'matches'  => $_arr_matches, // 匹配成果
        );
    }


    /** 正则验证
     * checkRegex function.
     *
     * @access public
     * @static
     * @param string $string 待匹配字符串
     * @param string $regex 正则字符串
     * @param bool $wild (default: false) 是否有通配符
     * @return 是否匹配
     */
    static function checkRegex($string, $regex, $wild = false) {

        $_arr_return = self::getRegex($string, $regex, $wild);

        return $_arr_return['result'];
    }


    /** 产生随机字符串
     * rand function.
     *
     * @access public
     * @static
     * @param int $length (default: 32) 字符串长度
     * @return 随机字符串
     */
    static function rand($length = 32) {
        $_str_char  = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789';
        $_str_rand  = '';
        while (strlen($_str_rand) < $length) {
            $_str_rand .= substr($_str_char, (rand(0, strlen($_str_char))), 1);
        }
        return $_str_rand;
    }


    /** 过滤数组重复项目
     * arrayFilter function.
     *
     * @access public
     * @static
     * @param array $arr 数组
     * @param bool $pop_false (default: true) 是否剔除 false 元素
     * @return void
     */
    static function arrayFilter($arr, $pop_false = true) {
        if (!self::isEmpty($arr)) {
            $arr = array_unique($arr);

            if ($pop_false === true) {
                $arr = array_filter($arr); // 剔除 false 元素
            }
        }
        return $arr;
    }


    /** 遍历数组并用指定函数处理
     * arrayEach function.
     *
     * @access public
     * @static
     * @param array $arr 数组
     * @param string $func (default: '') 处理函数
     * @param int $left (default: 5) 保留左边字符数, 仅在处理函数为 secret 时有效
     * @param int $right (default: 5) 保留右边字符数, 仅在处理函数为 secret 时有效
     * @param string $hide (default: '*') 替换字符, 仅在处理函数为 secret 时有效
     * @return 处理后的数组
     */
    static function arrayEach($arr, $func = '', $left = 5, $right = 5, $hide = '*') {
        if (is_array($arr) && !self::isEmpty($arr)) {
            foreach ($arr as $_key=>$_value) {
                if (is_array($_value) && !self::isEmpty($_value)) {
                    $arr[$_key] = self::arrayEach($_value, $func, $left, $right, $hide);
                } else if (is_scalar($_value) && !self::isEmpty($_value)) {
                    //$_value = self::safe($_value);

                    switch ($func) {
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


