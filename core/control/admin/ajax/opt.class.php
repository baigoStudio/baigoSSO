<?php
/*-----------------------------------------------------------------
！！！！警告！！！！
以下为系统文件，请勿修改
-----------------------------------------------------------------*/

//不能非法包含或直接执行
if(!defined("IN_BAIGO")) {
	exit("Access Denied");
}

include_once(BG_PATH_FUNC . "mailsend.func.php"); //载入模板类
include_once(BG_PATH_CLASS . "ajax.class.php"); //载入 AJAX 基类
include_once(BG_PATH_MODEL . "opt.class.php"); //载入管理帐号模型
include_once(BG_PATH_MODEL . "log.class.php"); //载入管理帐号模型

/*-------------管理员控制器-------------*/
class AJAX_OPT {

	private $adminLogged;
	private $obj_ajax;
	private $mdl_opt;
	private $mdl_log;

	function __construct() { //构造函数
		$this->adminLogged    = $GLOBALS["adminLogged"]; //已登录商家信息
		$this->obj_ajax       = new CLASS_AJAX(); //初始化 AJAX 基对象
		$this->obj_ajax->chk_install();
		$this->log            = $this->obj_ajax->log; //初始化 AJAX 基对象
		$this->mdl_opt        = new MODEL_OPT(); //设置管理组模型
		$this->mdl_log        = new MODEL_LOG(); //设置管理员模型

		if ($this->adminLogged["alert"] != "y020102") { //未登录，抛出错误信息
			$this->obj_ajax->halt_alert($this->adminLogged["alert"]);
		}
	}

	/**
	 * ajax_reg function.
	 *
	 * @access public
	 * @return void
	 */
	function ajax_reg() {
		if (!isset($this->adminLogged["admin_allow"]["opt"]["reg"])) {
			$this->obj_ajax->halt_alert("x040301");
		}

		$_num_countSrc = 0;

		foreach ($this->obj_ajax->opt["reg"] as $_key=>$_value) {
			if ($_value["min"] > 0) {
				$_num_countSrc++;
			}
		}

		$_arr_const = $this->mdl_opt->input_const("reg");

		$_num_countInput = count(array_filter($_arr_const));

		if ($_num_countInput < $_num_countSrc) {
			$this->obj_ajax->halt_alert("x030212");
		}

		$_arr_return = $this->mdl_opt->mdl_const("reg");

		if ($_arr_return["alert"] != "y040101") {
			$this->obj_ajax->halt_alert($_arr_return["alert"]);
		}

		$_arr_targets[]   = "reg";
		$_str_targets     = json_encode($_arr_targets);
		$_str_return      = json_encode($_arr_return);

		$this->mdl_log->mdl_submit($_str_targets, "opt", $this->log["opt"]["edit"], $_str_return, "admin", $this->adminLogged["admin_id"]);

		$this->obj_ajax->halt_alert("y040402");
	}


	/**
	 * ajax_mail function.
	 *
	 * @access public
	 * @return void
	 */
	function ajax_mail() {
		if (!isset($this->adminLogged["admin_allow"]["opt"]["mail"])) {
			$this->obj_ajax->halt_alert("x040301");
		}

		$_arr_return = $this->mdl_opt->mdl_const("mail");

		if ($_arr_return["alert"] != "y040101") {
			$this->obj_ajax->halt_alert($_arr_return["alert"]);
		}

		$_arr_mail    = array(BG_MAIL_FROM);
		$_arr_mailRow = fn_mailsend($_arr_mail, "test", "test", false);

		if ($_arr_mailRow["alert"] != "y030201") {
			$this->obj_ajax->halt_alert("x040403");
		}

		$this->obj_ajax->halt_alert("y040403");
	}

	/**
	 * ajax_base function.
	 *
	 * @access public
	 * @return void
	 */
	function ajax_base() {
		if (!isset($this->adminLogged["admin_allow"]["opt"]["base"])) {
			$this->obj_ajax->halt_alert("x040301");
		}

		$_num_countSrc = 0;

		foreach ($this->obj_ajax->opt["base"] as $_key=>$_value) {
			if ($_value["min"] > 0) {
				$_num_countSrc++;
			}
		}

		$_arr_const = $this->mdl_opt->input_const("base");

		$_num_countInput = count(array_filter($_arr_const));

		if ($_num_countInput < $_num_countSrc) {
			$this->obj_ajax->halt_alert("x030212");
		}

		$_arr_return = $this->mdl_opt->mdl_const("base");

		if ($_arr_return["alert"] != "y040101") {
			$this->obj_ajax->halt_alert($_arr_return["alert"]);
		}

		$_arr_targets[]   = "base";
		$_str_targets     = json_encode($_arr_targets);
		$_str_return      = json_encode($_arr_return);

		$this->mdl_log->mdl_submit($_str_targets, "opt", $this->log["opt"]["edit"], $_str_return, "admin", $this->adminLogged["admin_id"]);

		$this->obj_ajax->halt_alert("y040401");
	}


	/**
	 * ajax_db function.
	 *
	 * @access public
	 * @return void
	 */
	function ajax_db() {
		if (!isset($this->adminLogged["admin_allow"]["opt"]["db"])) {
			$this->obj_ajax->halt_alert("x040304");
		}

		$_arr_dbconfig = $this->mdl_opt->input_dbconfig();
		if ($_arr_dbconfig["alert"] != "ok") {
			$this->obj_ajax->halt_alert($_arr_dbconfig["alert"]);
		}

		$_arr_return = $this->mdl_opt->mdl_dbconfig();
		if ($_arr_return["alert"] != "y040101") {
			$this->obj_ajax->halt_alert($_arr_return["alert"]);
		}

		$_arr_targets[]   = "dbconfig";
		$_str_targets     = json_encode($_arr_targets);
		$_str_return      = json_encode($_arr_return);

		$this->mdl_log->mdl_submit($_str_targets, "opt", $this->log["opt"]["edit"], $_str_return, "admin", $this->adminLogged["admin_id"]);

		$this->obj_ajax->halt_alert("y040404");
	}

}