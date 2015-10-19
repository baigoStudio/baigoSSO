<?php
/*-----------------------------------------------------------------
！！！！警告！！！！
以下为系统文件，请勿修改
-----------------------------------------------------------------*/

//不能非法包含或直接执行
if(!defined("IN_BAIGO")) {
	exit("Access Denied");
}

include_once(BG_PATH_CLASS . "tpl.class.php"); //载入模板类

/*-------------管理员控制器-------------*/
class CONTROL_LOGON {

	private $obj_base;
	private $config; //配置
	private $obj_tpl;
	private $mdl_admin;
	private $tplData;

	function __construct() { //构造函数
		$this->obj_base   = $GLOBALS["obj_base"]; //获取界面类型
		$this->config     = $this->obj_base->config;
		$this->mdl_admin  = new MODEL_ADMIN(); //设置管理员模型
	}

	/**
	 * ctl_login function.
	 *
	 * @access public
	 * @return void
	 */
	function ctl_login() {
		$_arr_adminLogin = $this->mdl_admin->input_login();
		if ($_arr_adminLogin["alert"] != "ok") {
			return $_arr_adminLogin;
			exit;
		}

		$_arr_adminRow = $this->mdl_admin->mdl_read($_arr_adminLogin["admin_name"], "admin_name");
		if ($_arr_adminRow["alert"] != "y020102") {
			return $_arr_adminRow;
			exit;
		}

		if (fn_baigoEncrypt($_arr_adminLogin["admin_pass"], $_arr_adminRow["admin_rand"]) != $_arr_adminRow["admin_pass"]) {
			return array(
				"forward"   => $_arr_adminLogin["forward"],
				"alert" => "x020207",
			);
			exit;
		}

		if ($_arr_adminRow["admin_status"] != "enable") {
			return array(
				"forward"   => $_arr_adminLogin["forward"],
				"alert" => "x020402",
			);
			exit;
		}

		$_str_adminRand = fn_rand(6);

		$this->mdl_admin->mdl_login($_arr_adminRow["admin_id"], fn_baigoEncrypt($_arr_adminLogin["admin_pass"], $_str_adminRand), $_str_adminRand);

		fn_session("admin_id", "mk", $_arr_adminRow["admin_id"]);
		fn_session("admin_ssin_time", "mk", time());
		fn_session("admin_hash", "mk", fn_baigoEncrypt($_arr_adminRow["admin_time"], $_str_adminRand));

		return array(
			"admin_id"   => $_arr_adminLogin["admin_id"],
			"forward"    => $_arr_adminLogin["forward"],
			"alert"  => "y020201",
		);
	}

	/*============登出============
	无返回
	*/
	function ctl_logout() {
		$_str_forward  = fn_getSafe(fn_get("forward"), "txt", "");
		if (!$_str_forward) {
			$_str_forward = base64_encode(BG_URL_ADMIN . "ctl.php");
		}

		fn_ssin_end();

		return array(
			"forward" => $_str_forward,
		);
	}

	/*============登录界面============
	无返回
	*/
	function ctl_logon() {
		$this->obj_tpl    = new CLASS_TPL(BG_PATH_TPL . "admin/" . $this->config["ui"]);
		$_str_forward     = fn_getSafe(fn_get("forward"), "txt", "");
		$_str_alert       = fn_getSafe(fn_get("alert"), "txt", "");

		$_arr_tplData = array(
			"forward"    => $_str_forward,
			"alert"      => $_str_alert,
		);

		$this->obj_tpl->tplDisplay("logon.tpl", $_arr_tplData);
	}
}
