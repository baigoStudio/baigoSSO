<?php
/*-----------------------------------------------------------------

！！！！警告！！！！
以下为系统文件，请勿修改

-----------------------------------------------------------------*/

//不能非法包含或直接执行
if(!defined("IN_BAIGO")) {
	exit("Access Denied");
}


/** 随机数
 * fn_rand function.
 *
 * @access public
 * @param int $num_rand (default: 32)
 * @return void
 */
function fn_rand($num_rand = 32) {
	$_str_char = "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789";
	$_str_rnd = "";
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
function fn_getIp($str_ipTrue = true) {
	if (isset($_SERVER)) {
		if ($str_ipTrue) {
			if (fn_server("HTTP_X_FORWARDED_FOR")) {
				$_arr_ips = explode(",", fn_server("HTTP_X_FORWARDED_FOR"));
				foreach ($_arr_ips as $_value) {
					$_value = trim($_value);
					if ($_value != "unknown") {
						$_str_ip = $_value;
						break;
					}
				}
			} elseif (fn_server("HTTP_CLIENT_IP")) {
				$_str_ip = fn_server("HTTP_CLIENT_IP");
			} elseif (fn_server("REMOTE_ADDR")) {
				$_str_ip = fn_server("REMOTE_ADDR");
			} else {
				$_str_ip = "0.0.0.0";
			}
		} else {
			if (fn_server("REMOTE_ADDR")) {
				$_str_ip = fn_server("REMOTE_ADDR");
			} else {
				$_str_ip = "0.0.0.0";
			}
		}
	} else {
		if ($str_ipTrue) {
			if (getenv("HTTP_X_FORWARDED_FOR")) {
				$_str_ip = getenv("HTTP_X_FORWARDED_FOR");
			} elseif (getenv("HTTP_CLIENT_IP")) {
				$_str_ip = getenv("HTTP_CLIENT_IP");
			} else {
				$_str_ip = getenv("REMOTE_ADDR");
			}
		} else {
			$_str_ip = getenv("REMOTE_ADDR");
		}
	}
	return $_str_ip;
}


/** 验证码校对
 * fn_seccode function.
 *
 * @access public
 * @return void
 */
function fn_seccode() {
	$_str_seccode = strtolower(fn_post("seccode"));
	if ($_str_seccode != fn_session("seccode")) {
		return false;
	} else {
		return true;
	}
}


/** 令牌生成、校对
 * fn_token function.
 *
 * @access public
 * @param string $token_action (default: "mk")
 * @param string $token_method (default: "post")
 * @return void
 */
function fn_token($token_action = "mk", $session_method = "post", $cookie_method = "cookie") {
	switch ($token_action) {
		case "chk":
			switch ($session_method) {
				case "get":
					$_str_tokenSession = fn_getSafe(fn_get("token_session"), "txt", "");
				break;
				default:
					$_str_tokenSession = fn_getSafe(fn_post("token_session"), "txt", "");
				break;
			}

			switch ($cookie_method) {
				case "get":
					$_str_tokenCookie = fn_getSafe(fn_get("token_cookie"), "txt", "");
				break;
				case "post":
					$_str_tokenCookie = fn_getSafe(fn_post("token_cookie"), "txt", "");
				break;
				default:
					$_str_tokenCookie = fn_cookie("token_cookie");
				break;
			}

			if (BG_SWITCH_TOKEN == true) {
				 if ($_str_tokenSession != fn_session("token_session") || $_str_tokenCookie != fn_session("token_cookie")) {
					$_str_return = false;
				 } else {
					$_str_return = true;
				 }
			} else {
				$_str_return = true;
			}
		break;

		default:
			if (BG_SWITCH_TOKEN == true) {
				$_num_tokenSessionDiff = fn_session("token_session_time") + 300; //session有效期
				if (!fn_session("token_session") || !fn_session("token_session_time") || $_num_tokenSessionDiff < time()) {
					$_str_tokenSession                 = fn_rand();
					fn_session("token_session", "mk", $_str_tokenSession);
					fn_session("token_session_time", "mk", time());
				} else {
					$_str_tokenSession = fn_session("token_session");
				}

				$_num_tokenCookieDiff = fn_session("token_cookie_time") + 300; //cookie有效期
				if (!fn_session("token_cookie") || !fn_session("token_cookie_time") || $_num_tokenCookieDiff < time()) {
					$_str_tokenCookie              = fn_rand();
					fn_session("token_cookie", "mk", $_str_tokenCookie);
					fn_session("token_cookie_time", "mk", time());
				} else {
					$_str_tokenCookie = fn_session("token_cookie");
				}

				$_str_return = $_str_tokenSession;
				fn_cookie("token_cookie", "mk", $_str_tokenCookie);


			}
		break;
	}

	return $_str_return;
}

/*============清除全部cookie============
无返回
*/
function fn_clearCookie() {
	fn_cookie("cookie_ui", "unset");
	fn_cookie("cookie_lang", "unset");
}


/** 过滤数据
 * fn_getSafe function.
 *
 * @access public
 * @param mixed $str_string
 * @param string $str_type (default: "txt")
 * @param string $str_default (default: "")
 * @return void
 */
function fn_getSafe($str_string, $str_type = "txt", $str_default = "") {

	if ($str_string) {
		$_str_string = $str_string;
	} else {
		$_str_string = $str_default;

	}

	switch ($str_type) {

		case "int": //数值型
			if (is_numeric($_str_string)) {
				$_str_return = intval($_str_string); //如果是数值型则赋值
			} else {
				$_str_return = 0; //如果默认值为空则赋值为0
			}
		break;

		default: //默认
			$_str_return = htmlentities($_str_string, ENT_QUOTES, "UTF-8");
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
	preg_match_all("/./us", $str_string, $match);
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
	preg_match_all("/[\x01-\x7f]|[\xc2-\xdf][\x80-\xbf]|xe0[\xa0-\xbf][\x80-\xbf]|[\xe1-\xef][\x80-\xbf][\x80-\xbf]|xf0[\x90-\xbf][\x80-\xbf][\x80-\xbf]|[\xf1-\xf7][\x80-\xbf][\x80-\xbf][\x80-\xbf]/i", $str_string, $_arr);

    return join("", array_slice($_arr[0], $begin, $length));
}


/** 分页参数
 * fn_page function.
 *
 * @access public
 * @param mixed $num_total
 * @param mixed $num_per (default: BG_DEFAULT_PERPAGE)
 * @return void
 */
function fn_page($num_total, $num_per = BG_DEFAULT_PERPAGE) {

	$_num_pageThis = fn_getSafe(fn_get("page"), "int", 1);

	if ($_num_pageThis < 1) {
		$_num_pageThis = 1;
	} else {
		$_num_pageThis = $_num_pageThis;
	}

	$_num_pageTotal = $num_total / $num_per;

	if (intval($_num_pageTotal) < $_num_pageTotal) {
		$_num_pageTotal = intval($_num_pageTotal) + 1;
	} elseif ($_num_pageTotal < 1) {
		$_num_pageTotal = 1;
	} else {
		$_num_pageTotal = intval($_num_pageTotal);
	}

	if ($_num_pageThis > $_num_pageTotal) {
		$_num_pageThis = $_num_pageTotal;
	}

	if ($_num_pageThis <= 1) {
		$_num_except = 0;
	} else {
		$_num_except = ($_num_pageThis - 1) * $num_per;
	}

	$_p        = intval(($_num_pageThis - 1) / 10); //是否存在上十页、下十页参数
	$_begin    = $_p * 10 + 1; //列表起始页
	$_end      = $_p * 10 + 10; //列表结束页

	if ($_end >= $_num_pageTotal) {
		$_end = $_num_pageTotal;
	}

	return array(
		"page"    => $_num_pageThis,
		"p"       => $_p,
		"begin"   => $_begin,
		"end"     => $_end,
		"total"   => $_num_pageTotal,
		"except"  => $_num_except,
	);
}


/** JSON 编码（内容可编码成 base64）
 * fn_jsonEncode function.
 *
 * @access public
 * @param string $arr_json (default: "")
 * @param string $method (default: "")
 * @return void
 */
function fn_jsonEncode($arr_json = "", $method = "") {
	if ($arr_json) {
		$arr_json = fn_eachArray($arr_json, $method);
		//print_r($method);
		$str_json = json_encode($arr_json); //json编码
	} else {
		$str_json = "";
	}
	return $str_json;
}


/** JSON 解码 (内容可解码自 base64)
 * fn_jsonDecode function.
 *
 * @access public
 * @param string $str_json (default: "")
 * @param string $method (default: "")
 * @return void
 */
function fn_jsonDecode($str_json = "", $method = "") {
	if (isset($str_json)) {
		$arr_json = json_decode($str_json, true); //json解码
		$arr_json = fn_eachArray($arr_json, $method);
	} else {
		$arr_json = array();
	}
	return $arr_json;
}



/** 遍历数组，并进行 base64 解码编码
 * fn_eachArray function.
 *
 * @access public
 * @param mixed $arr
 * @param string $method (default: "encode")
 * @return void
 */
function fn_eachArray($arr, $method = "encode") {
	$_is_magic = get_magic_quotes_gpc();
	if (is_array($arr)) {
		foreach ($arr as $_key=>$_value) {
			if (is_array($_value)) {
				$arr[$_key] = fn_eachArray($_value, $method);
			} else {
				switch ($method) {
					case "encode":
						if (!$_is_magic) {
							$_str = addslashes($_value);
						} else {
							$_str = $_value;
						}
						$arr[$_key] = base64_encode($_str);
					break;

					case "decode":
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
			}
		}
	} else {
		$arr = array();
	}
	return $arr;
}



/** 密码加密
 * fn_baigoEncrypt function.
 *
 * @access public
 * @param mixed $str
 * @param mixed $rand
 * @return void
 */
function fn_baigoEncrypt($str, $rand, $is_md5 = false) {
	if ($is_md5) {
		$_str = $str;
	} else {
		$_str = md5($str);
	}
	$_str_return = md5($_str . $rand);
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
	$_str_reg = preg_quote($_str_reg, "/");

	if ($str_wild == true) {
		$_str_reg = str_replace("\\*", ".*", $_str_reg);
		$_str_reg = str_replace(" ", "", $_str_reg);
		$_str_reg = "/^(" . $_str_reg . ")$/i";
	} else {
		$_str_reg = "/(" . $_str_reg . ")$/i";
	}

	$_str_reg = str_replace("\|", "|", $_str_reg);
	$_str_reg = str_replace("|)", ")", $_str_reg);

	/*print_r($_str_reg . "<br>");
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
function fn_get($key) {
	if (isset($_GET[$key])) {
		return $_GET[$key];
	} else {
		return null;
	}
}


/** 封装 $_POST
 * fn_post function.
 *
 * @access public
 * @param mixed $key
 * @return void
 */
function fn_post($key) {
	if (isset($_POST[$key])) {
		return $_POST[$key];
	} else {
		return null;
	}
}


/** 封装 $_COOKIE
 * fn_cookie function.
 *
 * @access public
 * @param mixed $key
 * @return void
 */
function fn_cookie($key, $method = "get", $value = "") {
	switch ($method) {
		case "mk":
			setcookie($key . "_" . BG_SITE_SSIN, $value, time()+300);
		break;

		case "unset":
			setcookie($key . "_" . BG_SITE_SSIN, "", time() - 3600);
		break;

		default:
			if (isset($_COOKIE[$key . "_" . BG_SITE_SSIN])) {
				return $_COOKIE[$key . "_" . BG_SITE_SSIN];
			} else {
				return null;
			}
		break;
	}
}


function fn_session($key, $method = "get", $value = "") {
	switch ($method) {
		case "mk":
			$_SESSION[$key . "_" . BG_SITE_SSIN] = $value;
		break;

		case "unset":
			unset($_SESSION[$key . "_" . BG_SITE_SSIN]);
		break;

		default:
			if (isset($_SESSION[$key . "_" . BG_SITE_SSIN])) {
				return $_SESSION[$key . "_" . BG_SITE_SSIN];
			} else {
				return null;
			}
		break;
	}
}


/** 封装 $_REQUEST
 * fn_request function.
 *
 * @access public
 * @param mixed $key
 * @return void
 */
function fn_request($key) {
	if (isset($_REQUEST[$key])) {
		return $_REQUEST[$key];
	} else {
		return null;
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
		return $_SERVER[$key];
	} else {
		return null;
	}
}
