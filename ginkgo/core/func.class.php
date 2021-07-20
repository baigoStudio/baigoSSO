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
    public static function isEmpty($data) {
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
    public static function isOdd($num) {
        if ($num % 2 == 0) {
            $_bool_return = false;
        } else {
            $_bool_return = true;
        }

        return $_bool_return;
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
    public static function fixDs($path, $ds = DS) {
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
    public static function fillUrl($url, $baseUrl) {
        $url        = str_replace('\\', '/', $url); // 替换目录分隔符
        $baseUrl    = str_replace('\\', '/', $baseUrl); // 替换目录分隔符

        $_arr_urlParsed = parse_url($baseUrl); // 解析 url

        // 判断类型
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

    /** 安全过滤函数
     * safe function.
     *
     * @access public
     * @static
     * @param string $string 字符串
     * @param bool $htmlmode (default: false) 支持 html
     * @return 过滤后的字符串
     */
    public static function safe($string, $htmlmode = false) {

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
        //$_str_return = str_replace($_arr_dangerChars, '', $_str_return);
        $_str_return = Html::encode($_str_return);
        $_str_return = str_replace(array_keys($_arr_replace), array_values($_arr_replace), $_str_return);

        //print_r($htmlmode);

        if ($htmlmode) {
            $_str_return = Html::decode($_str_return); // 假如为 html 模式, 则用 html 解码
        }

        return trim($_str_return);
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
    public static function getRegex($string, $regex, $wild = false) {
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
    public static function checkRegex($string, $regex, $wild = false) {

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
    public static function rand($length = 32) {
        $_str_char  = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789';
        $_str_rand  = '';
        while (strlen($_str_rand) < $length) {
            $_str_rand .= substr($_str_char, (rand(0, strlen($_str_char))), 1);
        }
        return $_str_rand;
    }

    /** 以下为即将废弃的方法，供向下兼容 */

    /** 强化版 strtotime 向下兼容 */
    public static function strtotime($time) {
        return Strings::toTime($time);
    }

    /** 将字符串中每个单词的首字母转换为大写 向下兼容 */
    public static function ucwords($string, $delimiter = "\t\r\n\f\v") {
        return Strings::ucwords($string, $delimiter);
    }

    /** 转换为驼峰命名 向下兼容 */
    public static function toHump($string, $delimiter = '_', $lcfirst = false) {
        return Strings::toHump($string, $delimiter, $lcfirst);
    }

    /** 转换为分隔符命名 向下兼容 */
    public static function toLine($string, $delimiter = '_') {
        return Strings::toLine($string, $delimiter);
    }

    /** 容量格式化 向下兼容 */
    public static function sizeFormat($size = 0, $float = 2) {
        return Strings::sizeFormat($size = 0, $float);
    }

    /** 数字格式化 向下兼容 */
    public static function numFormat($num, $float = 2) {
        return Strings::numFormat($num, $float);
    }

    /** 隐私字符串 向下兼容 */
    public static function strSecret($string, $left = 5, $right = 5, $hide = '*'){
        return Strings::secrecy($string, $left, $right, $hide);
    }

    /** 文件是否存在 向下兼容 */
    public static function isFile($path) {
        return File::fileHas($path);
    }

    /** 补全 html 标签的图片地址部分 向下兼容 */
    public static function fillImg($content, $baseUrl) {
        return Html::fillImg($content, $baseUrl);
    }

    /** 过滤数组重复项目 向下兼容 */
    public static function arrayFilter($arr, $pop_false = true) {
        return Arrays::filter($arr, $pop_false);
    }

    /** 遍历数组并用指定函数处理 向下兼容 */
    public static function arrayEach($arr, $func = '', $left = 5, $right = 5, $hide = '') {
        return Arrays::each($arr, $func, $left, $right, $hide);
    }
}
