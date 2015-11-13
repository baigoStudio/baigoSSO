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
include_once(BG_PATH_MODEL . "appBelong.class.php");
include_once(BG_PATH_MODEL . "user.class.php"); //载入后台用户类
include_once(BG_PATH_MODEL . "log.class.php"); //载入管理帐号模型

/*-------------用户类-------------*/
class API_USER {

	private $obj_api;
	private $log;
	private $mdl_user;
	private $appAllow;
	private $appRows;
	private $appGet;

	function __construct() { //构造函数
		$this->obj_api        = new CLASS_API();
		$this->obj_api->chk_install();
		$this->log            = $this->obj_api->log; //初始化 AJAX 基对象
		$this->mdl_user       = new MODEL_USER(); //设置管理组模型
		$this->mdl_app        = new MODEL_APP(); //设置管理组模型
		$this->mdl_appBelong  = new MODEL_APP_BELONG();
		$this->mdl_log        = new MODEL_LOG(); //设置管理员模型
	}

	/**
	 * api_reg function.
	 *
	 * @access public
	 * @return void
	 */
	function api_reg() {
		$this->app_check("post");

		if (defined(BG_REG_ACC) && BG_REG_ACC != "enable") {
			$_arr_return = array(
				"alert" => "x050316",
			);
			$this->obj_api->halt_re($_arr_return);
		}

		if (!isset($this->appAllow["user"]["reg"])) {
			$_arr_return = array(
				"alert" => "x050305",
			);
			$_arr_logType = array("user", "reg");
			$_arr_logTarget[] = array(
				"app_id" => $this->appGet["app_id"],
			);
			$this->log_do($_arr_logTarget, "app", $_arr_return, $_arr_logType);
			$this->obj_api->halt_re($_arr_return);
		}

		$_arr_userReg = $this->mdl_user->api_input_reg(); //获取数据
		if ($_arr_userReg["alert"] != "ok") {
			$this->obj_api->halt_re($_arr_userReg);
		}


		$_str_rand        = fn_rand(6);
		$_str_userPass    = fn_baigoEncrypt($_arr_userReg["user_pass"], $_str_rand, true);
		$_arr_userRow     = $this->mdl_user->mdl_submit($_str_userPass, $_str_rand);

		$_str_key         = fn_rand(6);
		$_str_code        = $this->obj_api->api_encode($_arr_userRow, $_str_key);
		$this->mdl_appBelong->mdl_submit($_arr_userRow["user_id"], $this->appGet["app_id"]);

		$_arr_return = array(
			"code"   => $_str_code,
			"key"    => $_str_key,
		);

		//通知
		$_arr_notice              = $_arr_return;
		$_arr_notice["act_post"]  = "reg";
		$this->obj_api->api_notice($_arr_notice, $this->appRows);

		$_arr_return["alert"] = $_arr_userRow["alert"];

		$this->obj_api->halt_re($_arr_return);
	}


	/**
	 * api_login function.
	 *
	 * @access public
	 * @return void
	 */
	function api_login() {
		$this->app_check("post");

		if (!isset($this->appAllow["user"]["login"])) {
			$_arr_return = array(
				"alert" => "x050306",
			);
			$_arr_logType = array("user", "login");
			$_arr_logTarget[] = array(
				"app_id" => $this->appGet["app_id"],
			);
			$this->log_do($_arr_logTarget, "app", $_arr_return, $_arr_logType);
			$this->obj_api->halt_re($_arr_return);
		}

		$_arr_userLogin = $this->mdl_user->api_input_login();
		if ($_arr_userLogin["alert"] != "ok") {
			$this->obj_api->halt_re($_arr_userLogin);
		}

		$_arr_userRow = $this->mdl_user->mdl_read($_arr_userLogin["user_str"], $_arr_userLogin["user_by"]);
		if ($_arr_userRow["alert"] != "y010102") {
			$this->obj_api->halt_re($_arr_userRow);
		}

		if ($_arr_userRow["user_status"] != "enable") {
			$_arr_return = array(
				"alert" => "x010401",
			);
			$this->obj_api->halt_re($_arr_return);
		}

		if (fn_baigoEncrypt($_arr_userLogin["user_pass"], $_arr_userRow["user_rand"], true) != $_arr_userRow["user_pass"]) {
			$_arr_return = array(
				"alert" => "x010213",
			);
			$this->obj_api->halt_re($_arr_return);
		}

		//print_r($_arr_userRow);

		$this->mdl_user->mdl_login($_arr_userRow["user_id"]);

		unset($_arr_userRow["user_rand"], $_arr_userRow["user_pass"], $_arr_userRow["user_status"], $_arr_userRow["user_note"]);

		$_str_key     = fn_rand(6);
		$_str_code    = $this->obj_api->api_encode($_arr_userRow, $_str_key);

		$_arr_return = array(
			"code"   => $_str_code,
			"key"    => $_str_key,
		);

		$_arr_return["alert"]  = "y010401";

		$this->obj_api->halt_re($_arr_return);
	}


	/**
	 * api_get function.
	 *
	 * @access public
	 * @return void
	 */
	function api_get() {
		$this->app_check("get");

		if (!isset($this->appAllow["user"]["get"])) {
			$_arr_return = array(
				"alert" => "x050307",
			);
			$_arr_logTarget[] = array(
				"app_id" => $this->appGet["app_id"],
			);
			$_arr_logType = array("user", "get");
			$this->log_do($_arr_logTarget, "app", $_arr_return, $_arr_logType);
			$this->obj_api->halt_re($_arr_return);
		}

		$_arr_userGet = $this->mdl_user->input_get_by("get");

		if ($_arr_userGet["alert"] != "ok") {
			$this->obj_api->halt_re($_arr_userGet);
		}

		$_arr_userRow = $this->mdl_user->mdl_read($_arr_userGet["user_str"], $_arr_userGet["user_by"]);
		if ($_arr_userRow["alert"] != "y010102") {
			$this->obj_api->halt_re($_arr_userRow);
		}

		unset($_arr_userRow["user_rand"], $_arr_userRow["user_pass"], $_arr_userRow["user_status"], $_arr_userRow["user_note"]);

		$_str_key    = fn_rand(6);
		$_str_code    = $this->obj_api->api_encode($_arr_userRow, $_str_key);

		$_arr_return = array(
			"code"   => $_str_code,
			"key"    => $_str_key,
			"alert"  => $_arr_userRow["alert"],
		);

		$this->obj_api->halt_re($_arr_return);
	}


	/**
	 * api_edit function.
	 *
	 * @access public
	 * @return void
	 */
	function api_edit() {
		$this->app_check("post");

		if (!isset($this->appAllow["user"]["edit"])) {
			$_arr_return = array(
				"alert" => "x050308",
			);
			$_arr_logTarget[] = array(
				"app_id" => $this->appGet["app_id"],
			);
			$_arr_logType = array("user", "edit");
			$this->log_do($_arr_logTarget, "app", $_arr_return, $_arr_logType);
			$this->obj_api->halt_re($_arr_return);
		}

		$_arr_userEdit = $this->mdl_user->api_input_edit();

		if ($_arr_userEdit["alert"] != "ok") {
			$this->obj_api->halt_re($_arr_userEdit);
		}

		$_arr_userRow = $this->mdl_user->mdl_read($_arr_userEdit["user_str"], $_arr_userEdit["user_by"]);
		if ($_arr_userRow["alert"] != "y010102") {
			$this->obj_api->halt_re($_arr_userRow);
		}

		if (!isset($this->appAllow["user"]["global"])) {
			$_arr_appBelongRow = $this->mdl_appBelong->mdl_read($_arr_userRow["user_id"], $this->appGet["app_id"]);
			if ($_arr_appBelongRow["alert"] != "y070102") {
				$_arr_return = array(
					"alert" => "x050308",
				);
				$this->obj_api->halt_re($_arr_return);
			}
		}

		if ($_arr_userEdit["user_check_pass"] == true) {
			if (fn_baigoEncrypt($_arr_userEdit["user_pass"], $_arr_userRow["user_rand"], true) != $_arr_userRow["user_pass"]) {
				$_arr_return = array(
					"alert" => "x010213",
				);
				$this->obj_api->halt_re($_arr_return);
			}
		}

		if ($_arr_userRow["user_status"] != "enable") {
			return array(
				"alert" => "x010401",
			);
			exit;
		}

		if (BG_REG_ONEMAIL == "false" && BG_REG_NEEDMAIL == "on" && $_arr_userEdit["user_mail"]) {
			$_arr_userRow = $this->mdl_user->mdl_read($_arr_userEdit["user_mail"], "user_mail", $_arr_userRow["user_id"]);
			if ($_arr_userRow["alert"] == "y010102") {
				$_arr_return = array(
					"alert" => "x010211",
				);
				$this->obj_api->halt_re($_arr_return);
			}
		}

		//file_put_contents(BG_PATH_ROOT . "test.txt", $_str_userPass . "||" . $_str_rand);

		$_str_key                     = fn_rand(6);
		$_arr_userUpdate              = $this->mdl_user->mdl_edit($_arr_userRow["user_id"]);
		$_arr_userUpdate["user_name"] = $_arr_userRow["user_name"];
		$_str_code                    = $this->obj_api->api_encode($_arr_userUpdate, $_str_key);

		$_arr_return = array(
			"code"   => $_str_code,
			"key"    => $_str_key,
		);

		//通知
		$_arr_notice              = $_arr_return;
		$_arr_notice["act_post"]  = "edit";
		$this->obj_api->api_notice($_arr_notice, $this->appRows);

		$_arr_return["alert"]  = $_arr_userUpdate["alert"];

		$this->obj_api->halt_re($_arr_return);
	}


	/**
	 * api_del function.
	 *
	 * @access public
	 * @return void
	 */
	function api_del() {
		$this->app_check("post");

		if (!isset($this->appAllow["user"]["del"])) {
			$_arr_return = array(
				"alert" => "x050309",
			);
			$_arr_logTarget[] = array(
				"app_id" => $this->appGet["app_id"],
			);
			$_arr_logType = array("user", "del");
			$this->log_do($_arr_logTarget, "app", $_arr_return, $_arr_logType);
			$this->obj_api->halt_re($_arr_return);
		}

		$_arr_userIds = $this->mdl_user->input_ids();

		if ($_arr_userIds && is_array($_arr_userIds)) {
			foreach ($_arr_userIds as $_key=>$_value) {
				$_arr_userIds[$_key] = fn_getSafe($_value, "int", 0);
			}
		}

		if (!isset($this->appAllow["user"]["global"])) {
			$_arr_users = $this->mdl_appBelong->mdl_list(1000, 0, 0, $this->appGet["app_id"], $_arr_userIds);
		} else {
			$_arr_users = $_arr_userIds;
		}

		$_arr_userDel = $this->mdl_user->mdl_del($_arr_users);

		if ($_arr_userDel["alert"] == "y010104") {
			foreach ($_arr_userIds["user_ids"] as $_value) {
				$_arr_targets[] = array(
					"user_id" => $_value,
				);
				$_str_targets = json_encode($_arr_targets);
			}
			$this->mdl_log->mdl_submit($_str_targets, "user", $this->log["user"]["del"], $_str_targets, "app", $this->appGet["app_id"]);
		}

		$_arr_notice["user_ids"]  = $_arr_userIds;
		$_arr_notice["act_post"]  = "del";

		$this->obj_api->api_notice($_arr_notice, $this->appRows);

		$this->obj_api->halt_re($_arr_userDel);
	}


	/**
	 * api_chkname function.
	 *
	 * @access public
	 * @return void
	 */
	function api_chkname() {
		$this->app_check("get");

		if (!isset($this->appAllow["user"]["chkname"])) {
			$_arr_return = array(
				"alert" => "x050310",
			);
			$this->obj_api->halt_re($_arr_return);
		}

		$_arr_userName = $this->mdl_user->input_user_name();
		if ($_arr_userName["alert"] != "ok") {
			$this->obj_api->halt_re($_arr_userName);
		}

		$_arr_userRow = $this->mdl_user->mdl_read($_arr_userName["user_name"], "user_name", $_arr_userName["not_id"]);
		if ($_arr_userRow["alert"] == "y010102") {
			$_str_alert = "x010205";
		} else {
			$_str_alert = "y010205";
		}
		$_arr_return = array(
			"alert" => $_str_alert,
		);
		$this->obj_api->halt_re($_arr_return);
	}


	/**
	 * api_chkmail function.
	 *
	 * @access public
	 * @return void
	 */
	function api_chkmail() {
		if (BG_REG_ONEMAIL == "false" && BG_REG_NEEDMAIL == "on") {
			$this->app_check("get");

			if (!isset($this->appAllow["user"]["chkmail"])) {
				$_arr_return = array(
					"alert" => "x050311",
				);
				$this->obj_api->halt_re($_arr_return);
			}

			$_arr_userMail = fn_userChkMail();
			if ($_arr_userMail["alert"] != "ok") {
				$this->obj_api->halt_re($_arr_userMail);
			}

			$_arr_userRow = $this->mdl_user->mdl_read($_arr_userName["user_mail"], "user_mail", $_arr_userMail["not_id"]);
			if ($_arr_userRow["alert"] == "y010102") {
				$_str_alert = "x010211";
			} else {
				$_str_alert = "y010211";
			}
		} else {
			$_str_alert = "y010211";
		}

		$_arr_return = array(
			"alert" => $_str_alert,
		);
		$this->obj_api->halt_re($_arr_return);
	}


	/**
	 * app_check function.
	 *
	 * @access private
	 * @param mixed $num_appId
	 * @param string $str_method (default: "get")
	 * @return void
	 */
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


	/**
	 * log_do function.
	 *
	 * @access private
	 * @param mixed $arr_logResult
	 * @param mixed $str_logType
	 * @return void
	 */
	private function log_do($arr_logTarget, $str_targetType, $arr_logResult, $arr_logType) {
		$_str_targets = json_encode($arr_logTarget);
		$_str_result  = json_encode($arr_logResult);
		$this->mdl_log->mdl_submit($_str_targets, $str_targetType, $this->log[$arr_logType[0]][$arr_logType[1]], $_str_result, "app", $this->appGet["app_id"]);
	}
}
