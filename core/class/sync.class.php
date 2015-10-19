<?php
/*-----------------------------------------------------------------
！！！！警告！！！！
以下为系统文件，请勿修改
-----------------------------------------------------------------*/

//不能非法包含或直接执行
if(!defined("IN_BAIGO")) {
	exit("Access Denied");
}


/*-------------同步类-------------*/
class CLASS_SYNC {

	private $obj_base; //配置
	private $config; //配置
	public $log; //配置

	function __construct() { //构造函数
		$this->obj_base   = $GLOBALS["obj_base"]; //获取界面类型
		$this->config     = $this->obj_base->config;
		$this->log        = include_once(BG_PATH_LANG . $this->config["lang"] . "/log.php"); //载入日志内容
		$this->arr_return = array(
			"prd_sso_ver" => PRD_SSO_VER,
			"prd_sso_pub" => PRD_SSO_PUB,
		);
	}


	function app_chk($arr_appGet, $arr_appRow) {
		if ($arr_appGet["alert"] != "ok") {
			return $arr_appRow;
		}

		if ($arr_appRow["app_status"] != "enable") {
			return array(
				"alert" => "x050402",
			);
			exit;
		}

		if ($arr_appRow["app_key"] != $arr_appGet["app_key"]) {
			return array(
				"alert" => "x050217",
			);
			exit;
		}

		return array(
			"alert" => "ok",
		);
	}


	function sync_get($chk_token = false) {
		$_arr_time = validateStr(fn_get("time"), 1, 0);
		switch ($_arr_time["status"]) {
			case "too_short":
				return array(
					"alert" => "x090201",
				);
				exit;
			break;

			case "ok":
				$_tm_time = $_arr_time["str"];
			break;
		}

		$_arr_random = validateStr(fn_get("random"), 1, 0);
		switch ($_arr_random["status"]) {
			case "too_short":
				return array(
					"alert" => "x090202",
				);
				exit;
			break;

			case "ok":
				$_str_rand = $_arr_random["str"];
			break;
		}

		$_arr_signature = validateStr(fn_get("signature"), 1, 0);
		switch ($_arr_signature["status"]) {
			case "too_short":
				return array(
					"alert" => "x090203",
				);
				exit;
			break;

			case "ok":
				$_str_sign = $_arr_signature["str"];
			break;
		}

		if (!fn_baigoSignChk($_tm_time, $_str_rand, $_str_sign)) {
			$_arr_return = array(
				"alert" => "x050403",
			);
			return $_arr_return;
			exit;
		}

		$_arr_code = validateStr(fn_get("code"), 1, 0);
		switch ($_arr_code["status"]) {
			case "too_short":
				return array(
					"alert" => "x080202",
				);
				exit;
			break;

			case "ok":
				$_str_code = $_arr_code["str"];
			break;
		}

		$_arr_key = validateStr(fn_get("key"), 1, 0);
		switch ($_arr_key["status"]) {
			case "too_short":
				return array(
					"alert" => "x080203",
				);
				exit;
			break;

			case "ok":
				$_str_key = $_arr_key["str"];
			break;
		}

		$_arr_result = $this->sync_decode($_str_code, $_str_key);

		if (!isset($_arr_result["app_id"])) {
			return array(
				"alert" => "x050203",
			);
			exit;
		}

		$_arr_appId = validateStr($_arr_result["app_id"], 1, 0, "str", "int");
		switch ($_arr_appId["status"]) {
			case "too_short":
				return array(
					"alert" => "x050203",
				);
				exit;
			break;

			case "format_err":
				return array(
					"alert" => "x050204",
				);
				exit;
			break;

			case "ok":
				$_arr_syncGet["app_id"] = $_arr_appId["str"];
			break;
		}

		if (!isset($_arr_result["app_key"])) {
			return array(
				"alert" => "x050214",
			);
			exit;
		}

		$_arr_appKey = validateStr($_arr_result["app_key"], 1, 64, "str", "alphabetDigit");
		switch ($_arr_appKey["status"]) {
			case "too_short":
				return array(
					"alert" => "x050214",
				);
				exit;
			break;

			case "too_long":
				return array(
					"alert" => "x050215",
				);
				exit;
			break;

			case "format_err":
				return array(
					"alert" => "x050216",
				);
				exit;
			break;

			case "ok":
				$_arr_syncGet["app_key"] = $_arr_appKey["str"];
			break;
		}

		$_arr_syncGet["user_id"]  = $_arr_result["user_id"];
		$_arr_syncGet["alert"]    = "ok";

		return $_arr_syncGet;
	}

	/** 编码
	 * sync_encode function.
	 *
	 * @access public
	 * @param mixed $arr_data
	 * @param mixed $str_key
	 * @return void
	 */
	function sync_encode($arr_data, $str_key) {
		unset($arr_data["alert"]);

		$_str_src     = fn_jsonEncode($arr_data, "encode");
		$_str_code    = fn_baigoEncode($_str_src, $str_key);

		return $_str_code;
	}


	private function sync_decode($_str_code, $_str_key) {
		$_str_result  = fn_baigoDecode($_str_code, $_str_key);
		$_arr_result  = fn_jsonDecode($_str_result, "decode");

		return $_arr_result;
	}


	/** 返回结果
	 * halt_re function.
	 *
	 * @access public
	 * @param mixed $arr_re
	 * @return void
	 */
	function halt_re($arr_re) {
		$arr_halt = array_merge($this->arr_return, $arr_re);

		exit(fn_jsonEncode($arr_halt, "no")); //输出错误信息
	}


	function chk_install() {
		if (file_exists(BG_PATH_CONFIG . "is_install.php")) { //验证是否已经安装
			include_once(BG_PATH_CONFIG . "is_install.php");
			if (!defined("BG_INSTALL_PUB") || PRD_SSO_PUB > BG_INSTALL_PUB) {
				$_arr_return = array(
					"alert" => "x030411"
				);
				$this->halt_re($_arr_return);
			}
		} else {
			$_arr_return = array(
				"alert" => "x030410"
			);
			$this->halt_re($_arr_return);
		}
	}
}
