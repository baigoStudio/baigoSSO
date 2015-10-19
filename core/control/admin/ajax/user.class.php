<?php
/*-----------------------------------------------------------------
！！！！警告！！！！
以下为系统文件，请勿修改
-----------------------------------------------------------------*/

//不能非法包含或直接执行
if(!defined("IN_BAIGO")) {
	exit("Access Denied");
}

include_once(BG_PATH_CLASS . "ajax.class.php"); //载入模板类
include_once(BG_PATH_MODEL . "user.class.php"); //载入管理帐号模型
include_once(BG_PATH_MODEL . "log.class.php"); //载入管理帐号模型

/*-------------用户控制器-------------*/
class AJAX_USER {

	private $adminLogged;
	private $obj_ajax;
	private $log;
	private $mdl_user;
	private $mdl_log;

	function __construct() { //构造函数
		$this->adminLogged    = $GLOBALS["adminLogged"]; //已登录用户信息
		$this->obj_ajax       = new CLASS_AJAX(); //获取界面类型
		$this->obj_ajax->chk_install();
		$this->log            = $this->obj_ajax->log; //初始化 AJAX 基对象
		$this->mdl_user       = new MODEL_USER(); //设置用户模型
		$this->mdl_log        = new MODEL_LOG(); //设置管理员模型

		if ($this->adminLogged["alert"] != "y020102") { //未登录，抛出错误信息
			$this->obj_ajax->halt_alert($this->adminLogged["alert"]);
		}
	}

	/*============提交用户============
	返回数组
		user_id ID
		str_alert 提示信息
	*/
	function ajax_submit() {
		$_arr_userSubmit  = $this->mdl_user->input_submit();

		$_str_userPassDo  = "";
		$_str_userRand    = "";

		if ($_arr_userSubmit["alert"] != "ok") {
			$this->obj_ajax->halt_alert($_arr_userSubmit["alert"]);
		}

		if ($_arr_userSubmit["user_id"] > 0) {
			if (!isset($this->adminLogged["admin_allow"]["user"]["edit"])) {
				$this->obj_ajax->halt_alert("x010303");
			}
			$_str_userPass = fn_post("user_pass");
			if ($_str_userPass) {
				$_str_userRand      = fn_rand(6);
				$_str_userPassDo    = fn_baigoEncrypt($_str_userPass, $_str_userRand);
			}
		} else {
			if (!isset($this->adminLogged["admin_allow"]["user"]["add"])) {
				$this->obj_ajax->halt_alert("x010302");
			}
			$_arr_userPass = validateStr(fn_post("user_pass"), 1, 0);
			switch ($_arr_userPass["status"]) {
				case "too_short":
					$this->obj_ajax->halt_alert("x010212");
				break;

				case "ok":
					$_str_userPass = $_arr_userPass["str"];
				break;
			}
			$_str_userRand   = fn_rand(6);
			$_str_userPassDo = fn_baigoEncrypt($_str_userPass, $_str_userRand);
		}

		$_arr_userRow = $this->mdl_user->mdl_submit($_str_userPassDo, $_str_userRand);

		$this->obj_ajax->halt_alert($_arr_userRow["alert"]);
	}

	/*============更改用户状态============
	@arr_userId 用户 ID 数组
	@str_status 状态

	返回提示信息
	*/
	function ajax_status() {
		if (!isset($this->adminLogged["admin_allow"]["user"]["edit"])) {
			$this->obj_ajax->halt_alert("x010303");
		}

		$_str_status = fn_getSafe($GLOBALS["act_post"], "txt", "");

		$_arr_userIds = $this->mdl_user->input_ids();
		if ($_arr_userIds["alert"] != "ok") {
			$this->obj_ajax->halt_alert($_arr_userIds["alert"]);
		}

		$_arr_userRow = $this->mdl_user->mdl_status($_str_status);

		$this->obj_ajax->halt_alert($_arr_userRow["alert"]);
	}

	/*============删除用户============
	@arr_userId 用户 ID 数组

	返回提示信息
	*/
	function ajax_del() {
		if (!isset($this->adminLogged["admin_allow"]["user"]["del"])) {
			$this->obj_ajax->halt_alert("x010304");
		}

		$_arr_userIds = $this->mdl_user->input_ids();
		if ($_arr_userIds["alert"] != "ok") {
			$this->obj_ajax->halt_alert($_arr_userIds["alert"]);
		}

		$_arr_userRow = $this->mdl_user->mdl_del($_arr_userIds["user_ids"]);

		if ($_arr_userRow["alert"] == "y010104") {
			foreach ($_arr_userIds["user_ids"] as $_value) {
				$_arr_targets[] = array(
					"user_id" => $_value,
				);
				$_str_targets = json_encode($_arr_targets);
			}
			$_str_userRow = json_encode($_arr_userRow);
			$this->mdl_log->mdl_submit($_str_targets, "user", $this->log["user"]["del"], $_str_userRow, "admin", $this->adminLogged["admin_id"]);
		}

		$this->obj_ajax->halt_alert($_arr_userRow["alert"]);
	}


	/**
	 * ajax_chkname function.
	 *
	 * @access public
	 * @return void
	 */
	function ajax_chkname() {
		$_arr_userName = $this->mdl_user->input_user_name();

		if ($_arr_userName["alert"] != "ok") {
			$this->obj_ajax->halt_re($_arr_userName["alert"]);
		}

		$_arr_userRow = $this->mdl_user->mdl_read($_arr_userName["user_name"], "user_name", $_arr_userName["not_id"]);

		if ($_arr_userRow["alert"] == "y010102") {
			$this->obj_ajax->halt_re("x010205");
		}

		$arr_re = array(
			"re" => "ok"
		);

		exit(json_encode($arr_re));
	}

	/**
	 * ajax_chkmail function.
	 *
	 * @access public
	 * @return void
	 */
	function ajax_chkmail() {
		$_arr_userMail = $this->mdl_user->input_user_mail();

		if ($_arr_userMail["alert"] != "ok") {
			$this->obj_ajax->halt_re($_arr_userMail["alert"]);
		}

		if ($_arr_userMail["user_mail"]) {
			$_arr_userRow = $this->mdl_user->mdl_read($_arr_userMail["user_mail"], "user_mail", $_arr_userMail["not_id"]);
			if ($_arr_userRow["alert"] == "y010102") {
				$this->obj_ajax->halt_re("x010211");
			}
		}

		$arr_re = array(
			"re" => "ok"
		);

		exit(json_encode($arr_re));
	}
}