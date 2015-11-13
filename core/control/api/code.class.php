<?php
/*-----------------------------------------------------------------
！！！！警告！！！！
以下为系统文件，请勿修改
-----------------------------------------------------------------*/

//不能非法包含或直接执行
if(!defined("IN_BAIGO")) {
	exit("Access Denied");
}

include_once(BG_PATH_FUNC . "baigocode.func.php"); //载入模板类
include_once(BG_PATH_CLASS . "api.class.php"); //载入模板类
include_once(BG_PATH_MODEL . "app.class.php"); //载入后台用户类
include_once(BG_PATH_MODEL . "log.class.php"); //载入管理帐号模型

/*-------------用户类-------------*/
class API_CODE {

	private $obj_api;
	private $log;
	private $mdl_app;
	private $appAllow;
	private $appGet;

	function __construct() { //构造函数
		$this->obj_api    = new CLASS_API();
		$this->obj_api->chk_install();
		$this->log        = $this->obj_api->log; //初始化 AJAX 基对象
		$this->mdl_app    = new MODEL_APP(); //设置管理组模型
		$this->mdl_log    = new MODEL_LOG(); //设置管理员模型
	}


	/**
	 * api_encode function.
	 *
	 * @access public
	 * @return void
	 */
	function api_encode() {
		$this->app_check("post");
		if (!isset($this->appAllow["code"]["encode"])) {
			$_arr_return = array(
				"alert" => "x050314",
			);
			$this->log_do($_arr_return, "encode");
			$this->obj_api->halt_re($_arr_return);
		}

		$_arr_data = validateStr(fn_post("data"), 1, 0);
		switch ($_arr_data["status"]) {
			case "too_short":
				$_arr_return = array(
					"alert" => "x080201",
				);
				$this->obj_api->halt_re($_arr_return);
			break;

			case "ok":
				$_str_data = html_entity_decode($_arr_data["str"]);
			break;
		}

		$_str_key     = fn_rand(6);
		$_str_code    = fn_baigoEncode($_str_data, $_str_key);

		$_arr_return = array(
			"code"   => $_str_code,
			"key"    => $_str_key,
			"alert"  => "y050405",
		);

		$this->obj_api->halt_re($_arr_return);
	}


	/**
	 * api_decode function.
	 *
	 * @access public
	 * @return void
	 */
	function api_decode() {
		$this->app_check("post");
		if (!isset($this->appAllow["code"]["decode"])) {
			$_arr_return = array(
				"alert" => "x050315",
			);
			$this->log_do($_arr_return, "decode");
			$this->obj_api->halt_re($_arr_return);
		}

		$_arr_code = validateStr(fn_post("code"), 1, 0);
		switch ($_arr_code["status"]) {
			case "too_short":
				$_arr_return = array(
					"alert" => "x080202",
				);
				$this->obj_api->halt_re($_arr_return);
			break;

			case "ok":
				$_str_code = $_arr_code["str"];
			break;
		}

		$_arr_key = validateStr(fn_post("key"), 1, 0);
		switch ($_arr_key["status"]) {
			case "too_short":
				$_arr_return = array(
					"alert" => "x080203",
				);
				$this->obj_api->halt_re($_arr_return);
			break;

			case "ok":
				$_str_key = $_arr_key["str"];
			break;
		}

		$_str_result  = fn_baigoDecode($_str_code, $_str_key);

		exit($_str_result);
	}


	/**
	 * app_check function.
	 *
	 * @access private
	 * @return void
	 */
	private function app_check($str_method = "get") {
		$this->appGet = $this->obj_api->app_get($str_method);

		if ($this->appGet["alert"] != "ok") {
			$this->obj_api->halt_re($this->appGet);
		}

		$_arr_appRow = $this->mdl_app->mdl_read($this->appGet["app_id"]);
		if ($_arr_appRow["alert"] != "y050102") {
			$this->log_do($_arr_appRow, "read");
			$this->obj_api->halt_re($_arr_appRow);
		}
		$this->appAllow = $_arr_appRow["app_allow"];

		$_arr_appChk = $this->obj_api->app_chk($this->appGet, $_arr_appRow);
		if ($_arr_appChk["alert"] != "ok") {
			$this->log_do($_arr_appChk, "check");
			$this->obj_api->halt_re($_arr_appChk);
		}
	}


	/**
	 * log_do function.
	 *
	 * @access private
	 * @param mixed $arr_logResult
	 * @param mixed $str_logType
	 * @return void
	 */
	private function log_do($arr_logResult, $str_logType) {
		$_arr_targets[] = array(
			"app_id" => $this->appGet["app_id"],
		);
		$_str_targets     = json_encode($_arr_targets);
		$_str_logResult   = json_encode($arr_logResult);
		$this->mdl_log->mdl_submit($_str_targets, "app", $this->log["app"][$str_logType], $_str_logResult, "app", $this->appGet["app_id"]);
	}
}
