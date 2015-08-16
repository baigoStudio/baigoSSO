<?php
/*-----------------------------------------------------------------
！！！！警告！！！！
以下为系统文件，请勿修改
-----------------------------------------------------------------*/

//不能非法包含或直接执行
if (!defined("IN_BAIGO")) {
	exit("Access Denied");
}

/*------验证表单------
@str 验证字符串
@min 最小
@max 最大
@type 类型
@format 格式

返回多维数组
	str 表单最终值
	status 状态
*/
function validateStr($str, $min, $max, $type = "str", $format = "text") {
	$_obj_v = new CLASS_VALIDATE();

	switch ($type) {
		case "str":
			$_status = $_obj_v->is_text($str, $min, $max, $format); //验证字符串
			$str     = htmlentities($str, ENT_QUOTES, "UTF-8");
		break;
		case "digit":
			$_status = $_obj_v->is_digit($str, $min, $max, $format); //验证字符串
		break;
		case "num":
			$_status = $_obj_v->is_num($str, $min, $max); //验证个数
		break;
	}

	return array(
		"str"     => $str,
		"status"  => $_status
	);
}

class CLASS_VALIDATE {

	/*------验证长度------
	@str 需验证字符串
	@length(min, max) 数组，(最小长度, 最大长度) 0 为不限制

	返回字符
	too_short 太短
	too_long 太长
	ok 正常
	*/
	function v_leng($str, $min, $max) {
		if ($min > 0 && strlen($str) < $min) {
			$_status = "too_short"; //如果定义最小长度，且短于，则返回太短
		} elseif ($max > 0 && strlen($str) > $max) {
			$_status = "too_long"; //如果定义最大长度，且长于，则返回太长
		} else {
			$_status = "ok"; //返回正确
		}
		return $_status;
	}

	/*------验证格式------
	@str 需验证字符串
	@format 格式，text 为任意
	*/
	function v_reg($str, $format) {
		switch ($format) {
			case "date":
				$_reg = "/^[0-9]{4}-(((0?[13578]|(10|12))-(0?[1-9]|[1-2][0-9]|3[0-1]))|(0?2-(0[1-9]|[1-2][0-9]))|((0?[469]|11)-(0[1-9]|[1-2][0-9]|30)))$/"; //日期
			break;
			case "time":
				$_reg = "/^(([1-9]{1})|([0-1][0-9])|([1-2][0-3])):([0-5][0-9])(:([0-5][0-9]))?$/";
			break;
			case "datetime": //日期时间
				$_reg = "/^[0-9]{4}-(((0?[13578]|(10|12))-(0?[1-9]|[1-2][0-9]|3[0-1]))|(0?2-(0[1-9]|[1-2][0-9]))|((0?[469]|11)-(0[1-9]|[1-2][0-9]|30)))\s(([1-9]{1})|([0-1][0-9])|([1-2][0-3])):([0-5][0-9])(:([0-5][0-9]))?$/";
			break;
			case "int":
				$_reg = "/^([+-]?)\d*$/"; //整数
			break;
			case "digit":
				$_reg = "/^([+-]?)\d*\.?\d+$/"; //数值，可以包含小数点
			break;
			case "email":
				$_reg = "/^\w{0,}(\.)?(\w+)@\w+(\.\w+)+$/"; //Email
			break;
			case "url":
				$_reg = "/^http[s]?:\/\/(.*|-)+\.(.*|-)+$/"; //URL地址
			break;
			case "alphabetDigit":
				$_reg = "/^[a-z|A-Z|\d]*$/"; //URL地址
			break;
			case "strDigit":
				$_reg = "/^[\\\u4e00-\\\u9fa5|\\\uf900-\\\ufa2d|\w]*$/"; //字母中文数字下划线
			break;
			default:
				$_reg = ""; //默认
			break;
		}

		if ($str && $format != "text") { //如果值不为空，且格式不为text则验证
			if (preg_match($_reg, $str)) {
				return true; //验证通过，返回正确
			} else {
				return false; //验证失败，返回错误
			}
		} else {
			return true; //如果为text，直接返回正确
		}
	}

	/*------验证是否为字符串------
	@str 需验证的字符串
	@length(min, max) 数组，(最小长度, 最大长度) 0 为不限制
	@format 格式
	*/
	function is_text($str, $min, $max, $format) {
		$_status_leng = $this->v_leng($str, $min, $max);
		if ($_status_leng != "ok") {
			$_status = $_status_leng; //如验证长度出错，直接返回错误
		} else {
			if ($this->v_reg($str, $format)) {
				$_status = "ok"; //格式验证成功，返回正确
			} else {
				$_status = "format_err"; //格式验证失败，返回错误
			}
		}
		return $_status;
	}

	/*------验证数字------
	@num 需验证的数字
	@length(min, max) 数组，(最小个数, 最大个数) 0 为不限制
	*/
	function is_digit($num, $min, $max, $format) {
		if ($this->v_reg($num, $format)) {
			if ($min > 0 && $num < $min ){
				$_status = "too_small"; //如果定义最小数，且小于，则返回太小
			} elseif ($max > 0 && $num > $max){
				$_status = "too_big"; //如果定义最大数，且大于，则返回太大
			} else {
				$_status = "ok"; //返回正确
			}
		} else {
			$_status = "format_err"; //格式验证失败，返回错误
		}
		return $_status;
	}

	/*------验证个数------
	@num 需验证的个数
	@length(min, max) 数组，(最小个数, 最大个数) 0 为不限制
	*/
	function is_num($num, $min, $max) {
		if ($min > 0 && $num < $min ){
			$_status = "too_few"; //如果定义最小个数，且少于，则返回太少
		} elseif ($max > 0 && $num > $max){
			$_status = "too_many"; //如果定义最大个数，且多于，则返回太多
		} else {
			$_status = "ok"; //返回正确
		}
		return $_status;
	}
}