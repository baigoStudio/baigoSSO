<?php
/*-----------------------------------------------------------------
！！！！警告！！！！
以下为系统文件，请勿修改
-----------------------------------------------------------------*/

//不能非法包含或直接执行
if(!defined("IN_BAIGO")) {
	exit("Access Denied");
}

include_once(BG_PATH_FUNC . "http.func.php"); //载入开放平台类
include_once(BG_PATH_FUNC . "baigocode.func.php"); //载入开放平台类
include_once(BG_PATH_CLASS . "api.class.php"); //载入模板类
include_once(BG_PATH_MODEL . "app.class.php"); //载入后台用户类
include_once(BG_PATH_MODEL . "admin.class.php"); //载入后台用户类
include_once(BG_PATH_MODEL . "log.class.php"); //载入管理帐号模型

/*-------------管理员控制器-------------*/
class API_ADMIN {

	private $obj_api;
	private $log;
	private $mdl_admin;
	private $appAllow;
	private $appRows;
	private $appGet;

	function __construct() { //构造函数
		$this->obj_api    = new CLASS_API();
		$this->log        = $this->obj_api->log; //初始化 AJAX 基对象
		$this->mdl_admin  = new MODEL_ADMIN();
		$this->mdl_app    = new MODEL_APP(); //设置管理组模型
		$this->mdl_log    = new MODEL_LOG(); //设置管理员模型

		//本接口只在安装状态下起作用
		if (file_exists(BG_PATH_CONFIG . "is_install.php")) {
			$_arr_return = array(
				"alert" => "x030403"
			);
			$this->obj_api->halt_re($_arr_return);
		}
	}


	private function app_check($str_method = "get") {
		$this->appGet = $this->obj_api->app_get($str_method);

		if ($this->appGet["alert"] != "ok") {
			$this->obj_api->halt_re($this->appGet);
		}

		$_arr_logTarget[] = array(
			"app_id" => $this->appGet["app_id"]
		);

		$_arr_appRow = $this->mdl_app->mdl_read($this->appGet["app_id"]);
		if ($_arr_appRow["alert"] != "y050102") {
			$_arr_logType = array("app", "read");
			$this->log_do($_arr_logTarget, "app", $_arr_appRow, $_arr_logType);
			$this->obj_api->halt_re($_arr_appRow);
		}
		$this->appAllow = $_arr_appRow["app_allow"];

		$_arr_appChk = $this->obj_api->app_chk($this->appGet, $_arr_appRow);
		if ($_arr_appChk["alert"] != "ok") {
			$_arr_logType = array("app", "check");
			$this->log_do($_arr_logTarget, "app", $_arr_appChk, $_arr_logType);
			$this->obj_api->halt_re($_arr_appChk);
		}

		$this->appRows = $this->mdl_app->mdl_list(100, 0, "", "enable", "on", true);
	}


	private function log_do($arr_logTarget, $str_targetType, $arr_logResult, $arr_logType) {
		$_str_targets = json_encode($arr_logTarget);
		$_str_result  = json_encode($arr_logResult);
		$this->mdl_log->mdl_submit($_str_targets, $str_targetType, $this->log[$arr_logType[0]][$arr_logType[1]], $_str_result, "app", $this->appGet["app_id"]);
	}


	/**
	 * ajax_submit function.
	 *
	 * @access public
	 * @return void
	 */
	function api_add() {
		$this->app_check("post");

		$_arr_adminAdd = $this->mdl_admin->api_add();

		if ($_arr_adminAdd["alert"] != "ok") {
			$this->obj_api->halt_re($_arr_adminAdd);
		}

		$_str_rand        = fn_rand(6);
		$_str_adminPassDo = fn_baigoEncrypt($_arr_adminAdd["admin_pass"], $_str_rand, true);
		$_arr_adminRow    = $this->mdl_admin->mdl_submit($_str_adminPassDo, $_str_rand);

		$_str_key        = fn_rand(6);
		$_str_code        = $this->obj_api->api_encode($_arr_adminRow, $_str_key);

		$_arr_return = array(
			"code"   => $_str_code,
			"key"    => $_str_key,
		);

		$_arr_return["alert"] = $_arr_adminRow["alert"];

		$this->obj_api->halt_re($_arr_return);
	}
}
