<?php
/*-----------------------------------------------------------------
！！！！警告！！！！
以下为系统文件，请勿修改
-----------------------------------------------------------------*/

//不能非法包含或直接执行
if(!defined("IN_BAIGO")) {
	exit("Access Denied");
}

include_once(BG_PATH_CLASS . "ajax.class.php"); //载入 AJAX 基类
include_once(BG_PATH_MODEL . "log.class.php"); //载入管理帐号模型

/*-------------管理员控制器-------------*/
class AJAX_ADMIN {

	private $adminLogged;
	private $obj_ajax;
	private $log;
	private $mdl_admin;
	private $mdl_log;

	function __construct() { //构造函数
		$this->adminLogged    = $GLOBALS["adminLogged"]; //已登录商家信息
		$this->obj_ajax       = new CLASS_AJAX(); //初始化 AJAX 基对象
		$this->obj_ajax->chk_install();
		$this->log            = $this->obj_ajax->log; //初始化 AJAX 基对象
		$this->mdl_admin      = new MODEL_ADMIN(); //设置管理组模型
		$this->mdl_log        = new MODEL_LOG(); //设置管理员模型

		if ($this->adminLogged["alert"] != "y020102") { //未登录，抛出错误信息
			$this->obj_ajax->halt_alert($this->adminLogged["alert"]);
		}
	}


	/**
	 * ajax_submit function.
	 *
	 * @access public
	 * @return void
	 */
	function ajax_submit() {
		$_arr_adminSubmit = $this->mdl_admin->input_submit();

		if ($_arr_adminSubmit["alert"] != "ok") {
			$this->obj_ajax->halt_alert($_arr_adminSubmit["alert"]);
		}

		$_str_adminPassDo = "";
		$_str_adminRand   = "";

		if ($_arr_adminSubmit["admin_id"] > 0) {
			if (!isset($this->adminLogged["admin_allow"]["admin"]["edit"])) {
				$this->obj_ajax->halt_alert("x020303");
			}

			if ($_arr_adminSubmit["admin_id"] == $this->adminLogged["admin_id"]) {
				$this->obj_ajax->halt_alert("x020306");
			}

			$_str_adminPass = fn_post("admin_pass");
			if ($_str_adminPass) {
				$_str_adminRand     = fn_rand(6);
				$_str_adminPassDo   = fn_baigoEncrypt($_str_adminPass, $_str_adminRand);
			}
		} else {
			if (!isset($this->adminLogged["admin_allow"]["admin"]["add"])) {
				$this->obj_ajax->halt_alert("x020302");
			}
			$_arr_adminPass = validateStr(fn_post("admin_pass"), 1, 0);
			switch ($_arr_adminPass["status"]) {
				case "too_short":
					$this->obj_ajax->halt_alert("x020205");
				break;

				case "ok":
					$_str_adminPass = $_arr_adminPass["str"];
				break;
			}
			$_str_adminRand      = fn_rand(6);
			$_str_adminPassDo    = fn_baigoEncrypt($_str_adminPass, $_str_adminRand);
		}

		$_arr_adminRow = $this->mdl_admin->mdl_submit($_str_adminPassDo, $_str_adminRand);

		if ($_arr_adminRow["alert"] == "y020101" || $_arr_adminRow["alert"] == "y020103") {
			$_arr_targets[] = array(
				"admin_id" => $_arr_adminRow["admin_id"],
			);
			$_str_targets = json_encode($_arr_targets);
			if ($_arr_adminRow["alert"] == "y020101") {
				$_type = "add";
			} else {
				$_type = "edit";
			}
			$_str_adminRow = json_encode($_arr_adminRow);
			$this->mdl_log->mdl_submit($_str_targets, "admin", $this->log["admin"][$_type], $_str_adminRow, "admin", $this->adminLogged["admin_id"]);
		}

		$this->obj_ajax->halt_alert($_arr_adminRow["alert"]);
	}


	/**
	 * ajax_status function.
	 *
	 * @access public
	 * @return void
	 */
	function ajax_status() {
		if (!isset($this->adminLogged["admin_allow"]["admin"]["edit"])) {
			$this->obj_ajax->halt_alert("x020303");
		}

		$_str_status = fn_getSafe($GLOBALS["act_post"], "txt", "");

		$_arr_adminIds = $this->mdl_admin->input_ids();
		if ($_arr_adminIds["alert"] != "ok") {
			$this->obj_ajax->halt_alert($_arr_adminIds["alert"]);
		}

		$_arr_adminRow = $this->mdl_admin->mdl_status($_str_status);

		if ($_arr_adminRow["alert"] == "y020103") {
			foreach ($_arr_adminIds["admin_ids"] as $_value) {
				$_arr_targets[] = array(
					"admin_id" => $_value,
				);
				$_str_targets = json_encode($_arr_targets);
			}
			$_str_adminRow = json_encode($_arr_adminRow);
			$this->mdl_log->mdl_submit($_str_targets, "admin", $this->log["admin"]["edit"], $_str_adminRow, "admin", $this->adminLogged["admin_id"]);
		}

		$this->obj_ajax->halt_alert($_arr_adminRow["alert"]);
	}


	/**
	 * ajax_del function.
	 *
	 * @access public
	 * @return void
	 */
	function ajax_del() {
		if (!isset($this->adminLogged["admin_allow"]["admin"]["del"])) {
			$this->obj_ajax->halt_alert("x020304");
		}

		$_arr_adminIds = $this->mdl_admin->input_ids();
		if ($_arr_adminIds["alert"] != "ok") {
			$this->obj_ajax->halt_alert($_arr_adminIds["alert"]);
		}

		$_arr_adminRow = $this->mdl_admin->mdl_del();

		if ($_arr_adminRow["alert"] == "y020104") {
			foreach ($_arr_adminIds["admin_ids"] as $_value) {
				$_arr_targets[] = array(
					"admin_id" => $_value,
				);
				$_str_targets = json_encode($_arr_targets);
			}
			$_str_adminRow = json_encode($_arr_adminRow);
			$this->mdl_log->mdl_submit($_str_targets, "admin", $this->log["admin"]["del"], $_str_adminRow, "admin", $this->adminLogged["admin_id"]);
		}

		$this->obj_ajax->halt_alert($_arr_adminRow["alert"]);
	}


	/**
	 * ajax_chkname function.
	 *
	 * @access public
	 * @return void
	 */
	function ajax_chkname() {
		$_str_adminName   = fn_getSafe(fn_get("admin_name"), "txt", "");
		$_num_adminId     = fn_getSafe(fn_get("admin_id"), "int", 0);

		$_arr_adminRow = $this->mdl_admin->mdl_read($_str_adminName, "admin_name", $_num_adminId);

		if ($_arr_adminRow["alert"] == "y020102") {
			$this->obj_ajax->halt_re("x020204");
		}

		$arr_re = array(
			"re" => "ok"
		);

		exit(json_encode($arr_re));
	}
}
