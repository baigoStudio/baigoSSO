<?php
/*-----------------------------------------------------------------
！！！！警告！！！！
以下为系统文件，请勿修改
-----------------------------------------------------------------*/


//不能非法包含或直接执行
if (!defined('IN_BAIGO')) {
    exit('Access Denied');
}


/** 随机数
 * fn_rand function.
 *
 * @access public
 * @param int $num_rand (default: 32)
 * @return void
 */
function fn_rand($num_rand = 32) {
    $_str_char  = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789';
    $_str_rnd   = '';
    while (strlen($_str_rnd) < $num_rand) {
        $_str_rnd .= substr($_str_char, (rand(0, strlen($_str_char))), 1);
    }
    return $_str_rnd;
}


/** 获取 IP
 * fn_getIp function.
 *
 * @access public
 * @return void
 */
function fn_getIp() {
    if (isset($_SERVER)) {
        if (fn_isEmpty(fn_server('REMOTE_ADDR'))) {
            $_str_ip = '0.0.0.0';
        } else {
            $_str_ip = fn_server('REMOTE_ADDR');
        }
    } else {
        if (fn_isEmpty(getenv('REMOTE_ADDR'))) {
            $_str_ip = '0.0.0.0';
        } else {
            $_str_ip = getenv('REMOTE_ADDR');
        }
    }
    return $_str_ip;
}


/** 验证码校对
 * fn_captcha function.
 *
 * @access public
 * @return void
 */
function fn_captcha() {
    $_str_captcha = strtolower(fn_getSafe(fn_post('captcha'), 'txt', ''));
    if ($_str_captcha != fn_session('captcha')) {
        return false;
    } else {
        return true;
    }
}


/** 令牌生成、校对
 * fn_token function.
 *
 * @access public
 * @param string $token_action (default: 'mk')
 * @param string $token_method (default: 'post')
 * @return void
 */
function fn_token($token_action = 'mk') {
    if (fn_isEmpty(fn_session('admin_hash'))) {
        $_str_nameSession   = 'token_session';
        $_str_nameCookie    = 'token_cookie';
    } else {
        $_str_tokenName     = fn_session('admin_hash');
        $_str_nameSession   = 'token_session_' . $_str_tokenName;
        $_str_nameCookie    = 'token_cookie_' . $_str_tokenName;
    }

    switch ($token_action) {
        case 'chk':
            $_str_inputSession  = fn_getSafe(fn_post($_str_nameSession), 'txt', '');
            $_str_inputCookie   = fn_cookie($_str_nameCookie);

            if (BG_SWITCH_TOKEN == 1) {
                 if ($_str_inputSession != fn_session($_str_nameSession) || $_str_inputCookie != fn_session($_str_nameCookie)) {
                    return false;
                 } else {
                    return true;
                 }
            } else {
                return true;
            }
        break;

        default:
            if (BG_SWITCH_TOKEN == 1) {
                if (fn_isEmpty(fn_session($_str_nameSession))) {
                    $_str_tokenSession = fn_rand();
                    fn_session($_str_nameSession, 'mk', $_str_tokenSession);
                } else {
                    $_str_tokenSession = fn_session($_str_nameSession);
                }

                if (fn_isEmpty(fn_session($_str_nameCookie))) {
                    $_str_tokenCookie = fn_rand();
                    fn_session($_str_nameCookie, 'mk', $_str_tokenCookie);
                } else {
                    $_str_tokenCookie = fn_session($_str_nameCookie);
                }

                $_str_return = $_str_tokenSession;
                fn_cookie($_str_nameCookie, 'mk', $_str_tokenCookie);
            }
        break;
    }

    return array(
        'token'         => $_str_return,
        'name_session'  => $_str_nameSession,
        'name_cookie'   => $_str_nameCookie,
    );
}


/*============清除全部cookie============
无返回
*/
function fn_clearCookie() {
    fn_cookie('cookie_ui', 'unset');
    fn_cookie('cookie_lang', 'unset');
}


/** 过滤数据
 * fn_getSafe function.
 *
 * @access public
 * @param mixed $str_string
 * @param string $str_type (default: 'txt')
 * @param string $str_default (default: '')
 * @return void
 */
function fn_getSafe($str_string, $str_type = 'txt', $str_default = '') {
    if (fn_isEmpty($str_string)) {
        $_str_string = $str_default;
    } else {
        $_str_string = $str_string;
    }

    switch ($str_type) {
        case 'int': //数值型
            if (is_numeric($_str_string)) {
                $_str_return = intval($_str_string); //如果是数值型则赋值
            } else {
                $_str_return = 0; //如果默认值为空则赋值为0
            }
        break;

        default: //默认
            $_str_return = fn_safe($_str_string);
        break;
    }

    return $_str_return;
}


/** 获取 UTF8 字符长度
 * fn_strlen_utf8 function.
 *
 * @access public
 * @param mixed $str
 * @return void
 */
function fn_strlen_utf8($str_string) {
    // 将字符串分解为单元
    preg_match_all('/./us', $str_string, $match);
    // 返回单元个数
    return count($match[0]);
}


/**
 * fn_substr_utf8 function.
 *
 * @access public
 * @param mixed $str_string
 * @param mixed $begin
 * @param mixed $length
 * @return void
 */
function fn_substr_utf8($str_string, $begin, $length) {
    preg_match_all('/[\x01-\x7f]|[\xc2-\xdf][\x80-\xbf]|xe0[\xa0-\xbf][\x80-\xbf]|[\xe1-\xef][\x80-\xbf][\x80-\xbf]|xf0[\x90-\xbf][\x80-\xbf][\x80-\xbf]|[\xf1-\xf7][\x80-\xbf][\x80-\xbf][\x80-\xbf]/i', $str_string, $_arr);

    return join('', array_slice($_arr[0], $begin, $length));
}


/** 分页参数
 * fn_page function.
 *
 * @access public
 * @param mixed $num_count
 * @param mixed $num_per (default: BG_DEFAULT_PERPAGE)
 * @return void
 */
function fn_page($num_count, $num_per = BG_DEFAULT_PERPAGE) {
    if ($num_per < 1) {
        $num_per = 1;
    }

    $_num_this = fn_getSafe(fn_get('page'), 'int', 1);

    if ($_num_this < 1) {
        $_num_this = 1;
    } else {
        $_num_this = $_num_this;
    }

    $_num_total = $num_count / $num_per;

    if (intval($_num_total) < $_num_total) {
        $_num_total = intval($_num_total) + 1;
    } else if ($_num_total < 1) {
        $_num_total = 1;
    } else {
        $_num_total = intval($_num_total);
    }

    if ($_num_this > $_num_total) {
        $_num_this = $_num_total;
    }

    if ($_num_this <= 1) {
        $_num_except = 0;
    } else {
        $_num_except = ($_num_this - 1) * $num_per;
    }

    $_num_p        = intval(($_num_this - 1) / 10); //是否存在上十页、下十页参数
    $_num_begin    = $_num_p * 10 + 1; //列表起始页
    $_num_end      = $_num_p * 10 + 10; //列表结束页

    if ($_num_end >= $_num_total) {
        $_num_end = $_num_total;
    }

    return array(
        'page'    => $_num_this,
        'p'       => $_num_p,
        'begin'   => $_num_begin,
        'end'     => $_num_end,
        'total'   => $_num_total,
        'except'  => $_num_except,
    );
}


/** JSON 编码（内容可编码成 base64）
 * fn_jsonEncode function.
 *
 * @access public
 * @param string $arr_json (default: '')
 * @param string $method (default: '')
 * @return void
 */
function fn_jsonEncode($arr_json = '', $encode = false) {
    if ($encode) {
        $_str_encode = 'encode';
    } else {
        $_str_encode = '';
    }

    if (fn_isEmpty($arr_json)) {
        $str_json = '';
    } else {
        $arr_json = fn_eachArray($arr_json, $_str_encode);
        //print_r($method);
        $str_json = json_encode($arr_json); //json编码
    }

    return $str_json;
}


/** JSON 解码 (内容可解码自 base64)
 * fn_jsonDecode function.
 *
 * @access public
 * @param string $str_json (default: '')
 * @param string $method (default: '')
 * @return void
 */
function fn_jsonDecode($str_json = '', $decode = false) {
    if ($decode) {
        $_str_decode = 'decode';
    } else {
        $_str_decode = '';
    }

    if (fn_isEmpty($str_json)) {
        $arr_json = array();
    } else {
        $arr_json = json_decode($str_json, true); //json解码
        $arr_json = fn_eachArray($arr_json, $_str_decode);
    }

    return $arr_json;
}



/** 遍历数组，并进行 base64 解码编码
 * fn_eachArray function.
 *
 * @access public
 * @param mixed $arr
 * @param string $method (default: 'encode')
 * @return void
 */
function fn_eachArray($arr, $method = 'encode') {
    $_is_magic = get_magic_quotes_gpc();
    if (is_array($arr) && !fn_isEmpty($arr)) {
        foreach ($arr as $_key=>$_value) {
            if (is_array($_value) && !fn_isEmpty($_value)) {
                $arr[$_key] = fn_eachArray($_value, $method);
            } else if (!fn_isEmpty($_value)) {
                switch ($method) {
                    case 'encode':
                        if (!$_is_magic) {
                            $_str = addslashes($_value);
                        } else {
                            $_str = $_value;
                        }
                        $arr[$_key] = base64_encode($_str);
                    break;

                    case 'decode':
                        $_str = base64_decode($_value);
                        //if (!$_is_magic) {
                            $arr[$_key] = stripslashes($_str);
                        //} else {
                            //$arr[$_key] = $_str;
                        //}
                    break;

                    default:
                        if (!$_is_magic) {
                            $_str = addslashes($_value);
                        } else {
                            $_str = $_value;
                        }
                        $arr[$_key] = $_str;
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




/**
 * fn_baigoCrypt function.
 *
 * @access public
 * @param mixed $str
 * @param mixed $salt
 * @param bool $is_md5 (default: false)
 * @return void
 */
function fn_baigoCrypt($str, $salt, $is_md5 = false, $crypt_type = 2) {
    $key_pub = $GLOBALS['obj_base']->key_pub;

    if ($is_md5) {
        $_str = $str;
    } else {
        $_str = md5($str);
    }

    $_salt      = md5($salt); //用 md5 加密盐
    $_key_pub   = md5($key_pub); //用 md5 加密公钥

    switch ($crypt_type) {
        case 0:
            $_str_return = md5($_str . $salt); //保留历史加密方式
        break;

        case 1:
            $_str_return = md5($_str . $salt . $key_pub); //保留历史加密方式
        break;

        default:
            $_str_return = sha1($_key_pub . $_salt . sha1(md5($_str)) . $_salt . $_key_pub); //初步加密
            $_str_return = crypt($_str_return, $_salt); //php 内置加密
            $_str_return = md5($_str_return); //最终加密
        break;
    }

    return $_str_return;
}


/** 正则匹配
 * fn_regChk function.
 *
 * @access public
 * @param mixed $str_chk
 * @param mixed $str_reg
 * @param bool $str_wild (default: false)
 * @return void
 */
function fn_regChk($str_chk, $str_reg, $str_wild = false) {
    $_str_reg = trim($str_reg);
    $_str_reg = preg_quote($_str_reg, '/');

    if ($str_wild == true) {
        $_str_reg = str_ireplace('\\*', '.*', $_str_reg);
        $_str_reg = str_ireplace(' ', '', $_str_reg);
        $_str_reg = '/^(' . $_str_reg . ')$/i';
    } else {
        $_str_reg = '/(' . $_str_reg . ')$/i';
    }

    $_str_reg = str_ireplace('\|', '|', $_str_reg);
    $_str_reg = str_ireplace('|)', ')', $_str_reg);

    /*print_r($_str_reg . '<br>');
    preg_match($_str_reg, $str_chk, $aaaa);
    print_r($aaaa);*/

    return preg_match($_str_reg, $str_chk);
}


/** 封装 $_GET
 * fn_get function.
 *
 * @access public
 * @param mixed $key
 * @return void
 */
function fn_get($key = false, $arr_param = array()) {
    $_return    = null;
    $_arr_param = array_filter(array_unique($arr_param));

    if ($key) {
        if (isset($_GET[$key])) {
            $_return = $_GET[$key];
        }
    } else {
        if (isset($_GET) && !fn_isEmpty($_GET)) {
            if (fn_isEmpty($_arr_param)) {
                $_return = $_GET;
            } else {
                $_return = fn_paramChk($_GET, $_arr_param);
            }
        }
    }

    return $_return;
}


/** 封装 $_POST
 * fn_post function.
 *
 * @access public
 * @param mixed $key
 * @return void
 */
function fn_post($key = false, $arr_param = array()) {
    $_return    = null;
    $_arr_param = array_filter(array_unique($arr_param));

    if ($key) {
        if (isset($_POST[$key])) {
            $_return = $_POST[$key];
        }
    } else {
        if (isset($_POST) && !fn_isEmpty($_POST)) {
            if (fn_isEmpty($_arr_param)) {
                $_return = $_POST;
            } else {
                $_return = fn_paramChk($_POST, $_arr_param);
            }
        }
    }

    return $_return;
}


/** 封装 $_REQUEST
 * fn_request function.
 *
 * @access public
 * @param mixed $key
 * @return void
 */
function fn_request($key = false, $arr_param = array()) {
    $_return    = null;
    $_arr_param = array_filter(array_unique($arr_param));

    if ($key) {
        if (isset($_REQUEST[$key])) {
            $_return = $_REQUEST[$key];
        }
    } else {
        if (isset($_REQUEST) && !fn_isEmpty($_REQUEST)) {
            if (fn_isEmpty($_arr_param)) {
                $_return = $_REQUEST;
            } else {
                $_return = fn_paramChk($_REQUEST, $_arr_param);
            }
        }
    }

    return $_return;
}


function fn_paramChk($arr_data, $arr_param) {
    $_arr_return    = array();
    $_arr_param     = array_filter(array_unique($arr_param));

    foreach ($_arr_param as $_key=>$_value) {
        if (isset($arr_data[$_value])) {
            $_arr_return[$_value] = $arr_data[$_value];
        } else {
            $_arr_return[$_value] = '';
        }
    }

    return $_arr_return;
}


/** 封装 $_COOKIE
 * fn_cookie function.
 *
 * @access public
 * @param mixed $key
 * @return void
 */
function fn_cookie($key, $method = 'get', $value = '', $tm = 3600, $path = '') {
    switch ($method) {
        case 'mk':
            setcookie($key . '_' . BG_SITE_SSIN, $value, time() + $tm, $path);
        break;

        case 'unset':
            setcookie($key . '_' . BG_SITE_SSIN, null, time() - 3600, $path);
        break;

        default:
            if (isset($_COOKIE[$key . '_' . BG_SITE_SSIN])) {
                return fn_safe($_COOKIE[$key . '_' . BG_SITE_SSIN]);
            } else {
                return null;
            }
        break;
    }
}


function fn_session($key, $method = 'get', $value = '') {
    switch ($method) {
        case 'mk':
            $_SESSION[$key . '_' . BG_SITE_SSIN] = $value;
        break;

        case 'unset':
            unset($_SESSION[$key . '_' . BG_SITE_SSIN]);
        break;

        default:
            if (isset($_SESSION[$key . '_' . BG_SITE_SSIN])) {
                return fn_safe($_SESSION[$key . '_' . BG_SITE_SSIN]);
            } else {
                return null;
            }
        break;
    }
}


/** 封装 $_SERVER
 * fn_server function.
 *
 * @access public
 * @param mixed $key
 * @return void
 */
function fn_server($key) {
    if (isset($_SERVER[$key])) {
        return fn_safe($_SERVER[$key]);
    } else {
        return null;
    }
}


function fn_safe($str_string) {
    //正则剔除
    $_arr_dangerRegs = array(
        /* -------- 跨站 --------*/

        //html 标签
        '/<(script|frame|iframe|bgsound|link|blink|object|applet|embed|style|layer|ilayer|base|meta)(\s+\S*)*>/i',

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
        '\t', '\r', '\n', PHP_EOL
    );

    $_str_return = trim($str_string);

    $_str_return = str_ireplace(',', '|', $_str_return); //特殊字符，内部保留

    foreach ($_arr_dangerRegs as $_key=>$_value) {
        $_str_return = preg_replace($_value, '', $_str_return);
    }

    foreach ($_arr_dangerChars as $_key=>$_value) {
        $_str_return = str_ireplace($_value, '', $_str_return);
    }

    $_str_return = fn_htmlcode($_str_return);

    $_str_return = str_ireplace('!', '&#33;', $_str_return);
    $_str_return = str_ireplace('$', '&#36;', $_str_return);
    $_str_return = str_ireplace('%', '&#37;', $_str_return);
    $_str_return = str_ireplace('\'', '&#39;', $_str_return);
    $_str_return = str_ireplace('(', '&#40;', $_str_return);
    $_str_return = str_ireplace(')', '&#41;', $_str_return);
    $_str_return = str_ireplace('+', '&#43;', $_str_return);
    $_str_return = str_ireplace('-', '&#45;', $_str_return);
    $_str_return = str_ireplace(':', '&#58;', $_str_return);
    $_str_return = str_ireplace('=', '&#61;', $_str_return);
    $_str_return = str_ireplace('?', '&#63;', $_str_return);
    //$_str_return = str_ireplace('@', '&#64;', $_str_return);
    $_str_return = str_ireplace('[', '&#91;', $_str_return);
    $_str_return = str_ireplace(']', '&#93;', $_str_return);
    $_str_return = str_ireplace('^', '&#94;', $_str_return);
    $_str_return = str_ireplace('`', '&#96;', $_str_return);
    $_str_return = str_ireplace('{', '&#123;', $_str_return);
    $_str_return = str_ireplace('}', '&#125;', $_str_return);
    $_str_return = str_ireplace('~', '&#126;', $_str_return);

    return $_str_return;
}


function fn_htmlcode($str_html, $method = 'encode', $spec = false) {
    switch ($method) {
        case 'decode':
            $str_html = html_entity_decode($str_html, ENT_QUOTES, 'UTF-8');

            switch ($spec) {
                case 'json': //转换 json 特殊字符
                    $str_html = str_ireplace('&#58;', ':', $str_html);
                    $str_html = str_ireplace('&#91;', '[', $str_html);
                    $str_html = str_ireplace('&#93;', ']', $str_html);
                    $str_html = str_ireplace('&#123;', '{', $str_html);
                    $str_html = str_ireplace('&#125;', '}', $str_html);
                    $str_html = str_ireplace('|', ',', $str_html);
                break;
                case 'url': //转换 加密 特殊字符
                    $str_html = str_ireplace('&#58;', ':', $str_html);
                    $str_html = str_ireplace('&#45;', '-', $str_html);
                    $str_html = str_ireplace('&#61;', '=', $str_html);
                    $str_html = str_ireplace('&#63;', '?', $str_html);
                break;
                case 'crypt': //转换 加密 特殊字符
                    $str_html = str_ireplace('&#37;', '%', $str_html);
                break;
                case 'base64': //转换 base64 特殊字符
                    $str_html = str_ireplace('&#61;', '=', $str_html);
                break;
            }
        break;
        default:
            $str_html = htmlentities($str_html, ENT_QUOTES, 'UTF-8');
        break;
    }

    return $str_html;
}

function fn_strtotime($str_time) {
    $str_time   = str_ireplace('&#45;', '-', $str_time);
    $str_time   = str_ireplace('&#58;', ':', $str_time);
    $_tm_return = strtotime($str_time);

    return $_tm_return;
}


function fn_isEmpty($data) {
    if (!isset($data)) {
    	return true;
    }
	if ($data === null) {
		return true;
	}
	if (is_array($data) || is_object($data)) {
    	if (empty($data)) {
    		return true;
    	}
	} else {
    	if (empty($data) || trim($data) === '') {
    		return true;
    	}
	}

	return false;
}


function fn_forward($str_forward, $method = 'encode') {
    switch ($method) {
        case 'decode':
            $str_forward = fn_htmlcode($str_forward, 'decode', 'crypt');
            $str_forward = urldecode($str_forward);
            $str_forward = fn_htmlcode($str_forward, 'decode', 'base64');
            $str_forward = base64_decode($str_forward);
            $str_forward = fn_htmlcode($str_forward, 'decode', 'url');
            $str_forward = fn_safe($str_forward);
            $str_forward = fn_htmlcode($str_forward, 'decode', 'url');
            return $str_forward;
        break;

        default:
            return urlencode(base64_encode($str_forward));
        break;
    }
}