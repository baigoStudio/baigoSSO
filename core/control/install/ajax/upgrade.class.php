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
include_once(BG_PATH_MODEL . "opt.class.php"); //载入管理帐号模型
include_once(BG_PATH_MODEL . "admin.class.php"); //载入管理帐号模型

class AJAX_UPGRADE {

	private $obj_ajax;
	private $obj_db;

	function __construct() { //构造函数
		$this->obj_ajax       = new CLASS_AJAX(); //初始化 AJAX 基对象

		if (file_exists(BG_PATH_CONFIG . "is_install.php")) {
			include_once(BG_PATH_CONFIG . "is_install.php"); //载入栏目控制器
			if (defined("BG_INSTALL_PUB") && PRD_SSO_PUB <= BG_INSTALL_PUB) {
				$this->obj_ajax->halt_alert("x030403");
			}
		}
		$this->upgrade_init();
		$this->mdl_opt = new MODEL_OPT();
	}

	function ajax_dbconfig() {
		$_arr_return = $this->mdl_opt->mdl_dbconfig();

		if ($_arr_return["alert"] != "y040101") {
			$this->obj_ajax->halt_alert($_arr_return["alert"]);
		}

		$this->obj_ajax->halt_alert("y030404");
	}


	function ajax_dbtable() {
		$this->check_db();

		$this->table_admin();
		$this->table_user();
		$this->table_app();
		$this->table_app_belong();
		$this->table_log();
		$this->view_user();

		$this->obj_ajax->halt_alert("y030113");
	}


	/**
	 * install_2_do function.
	 *
	 * @access public
	 * @return void
	 */
	function ajax_base() {
		$this->check_db();

		$_arr_return = $this->mdl_opt->mdl_const("base");

		if ($_arr_return["alert"] != "y040101") {
			$this->obj_ajax->halt_alert($_arr_return["alert"]);
		}

		$this->obj_ajax->halt_alert("y030405");
	}


	/**
	 * install_3_do function.
	 *
	 * @access public
	 * @return void
	 */
	function ajax_reg() {
		$this->check_db();

		$_arr_return = $this->mdl_opt->mdl_const("reg");

		if ($_arr_return["alert"] != "y040101") {
			$this->obj_ajax->halt_alert($_arr_return["alert"]);
		}

		$this->obj_ajax->halt_alert("y030406");
	}


	function ajax_over() {
		$this->check_db();

		$_arr_return = $this->mdl_opt->mdl_over();

		if ($_arr_return["alert"] != "y040101") {
			$this->obj_ajax->halt_alert($_arr_return["alert"]);
		}

		$this->obj_ajax->halt_alert("y030409");
	}


	private function table_admin() {
		include_once(BG_PATH_MODEL . "admin.class.php"); //载入管理帐号模型
		$_mdl_admin   = new MODEL_ADMIN();
		$_arr_col     = $_mdl_admin->mdl_column();
		$_arr_alert   = array();

		if (!in_array("admin_nick", $_arr_col)) {
			$_arr_alert["admin_nick"] = array("ADD", "varchar(30) NOT NULL COMMENT '昵称'");
		}

		if (in_array("admin_id", $_arr_col)) {
			$_arr_alert["admin_id"] = array("CHANGE", "smallint NOT NULL AUTO_INCREMENT COMMENT 'ID'", "admin_id");
		}

		if (in_array("admin_status", $_arr_col)) {
			$_arr_alert["admin_status"] = array("CHANGE", "enum('enable','disable') NOT NULL COMMENT '状态'", "admin_status");
		}

		if (in_array("admin_pass", $_arr_col)) {
			$_arr_alert["admin_pass"] = array("CHANGE", "char(32) NOT NULL COMMENT '密码'", "admin_pass");
		}

		if (in_array("admin_rand", $_arr_col)) {
			$_arr_alert["admin_rand"] = array("CHANGE", "char(6) NOT NULL COMMENT '随机串'", "admin_rand");
		}

		if ($_arr_alert) {
			$_reselt = $this->obj_db->alert_table(BG_DB_TABLE . "admin", $_arr_alert);
			if (!$_reselt) {
				$this->obj_ajax->halt_alert("x020106");
			}
		}
	}


	private function table_user() {
		include_once(BG_PATH_MODEL . "user.class.php"); //载入管理帐号模型
		$_mdl_user    = new MODEL_USER();
		$_arr_col     = $_mdl_user->mdl_column();
		$_arr_alert   = array();

		if (in_array("user_status", $_arr_col)) {
			$_arr_alert["user_status"] = array("CHANGE", "enum('wait','enable','disable') NOT NULL COMMENT '状态'", "user_status");
		}

		if (in_array("user_pass", $_arr_col)) {
			$_arr_alert["user_pass"] = array("CHANGE", "char(32) NOT NULL COMMENT '密码'", "user_pass");
		}

		if (in_array("user_rand", $_arr_col)) {
			$_arr_alert["user_rand"] = array("CHANGE", "char(6) NOT NULL COMMENT '随机串'", "user_rand");
		}

		if ($_arr_alert) {
			$_reselt = $this->obj_db->alert_table(BG_DB_TABLE . "user", $_arr_alert);
			if (!$_reselt) {
				$this->obj_ajax->halt_alert("x010106");
			}
		}
	}


	private function table_app() {
		include_once(BG_PATH_MODEL . "app.class.php"); //载入管理帐号模型
		$_mdl_app     = new MODEL_APP();
		$_arr_col     = $_mdl_app->mdl_column();
		$_arr_alert   = array();

		if (in_array("app_id", $_arr_col)) {
			$_arr_alert["app_id"] = array("CHANGE", "smallint NOT NULL AUTO_INCREMENT COMMENT 'ID'", "app_id");
		}

		if (in_array("app_status", $_arr_col)) {
			$_arr_alert["app_status"] = array("CHANGE", "enum('enable','disable') NOT NULL COMMENT '状态'", "app_status");
		}

		if (in_array("app_sync", $_arr_col)) {
			$_arr_alert["app_sync"] = array("CHANGE", "enum('on','off') NOT NULL COMMENT '状态'", "app_sync");
		}

		if (in_array("app_key", $_arr_col)) {
			$_arr_alert["app_key"] = array("CHANGE", "char(64) NOT NULL COMMENT '校验码'", "app_key");
		}

		if (in_array("app_token", $_arr_col)) {
			$_arr_alert["app_token"] = array("CHANGE", "char(64) NOT NULL COMMENT '访问口令'", "app_token");
		}

		if ($_arr_alert) {
			$_reselt = $this->obj_db->alert_table(BG_DB_TABLE . "app", $_arr_alert);
			if (!$_reselt) {
				$this->obj_ajax->halt_alert("x050106");
			}
		}
	}


	private function table_app_belong() {
		include_once(BG_PATH_MODEL . "appBelong.class.php"); //载入管理帐号模型
		$_mdl_appBelong       = new MODEL_APP_BELONG();
		$_arr_appBelongRow    = $_mdl_appBelong->mdl_create_table();

		if ($_arr_appBelongRow["alert"] != "y070105") {
			$this->obj_ajax->halt_alert($_arr_appBelongRow["alert"]);
		}

		$_arr_col     = $_mdl_appBelong->mdl_column();
		$_arr_alert   = array();

		if (in_array("belong_app_id", $_arr_col)) {
			$_arr_alert["belong_app_id"] = array("CHANGE", "smallint NOT NULL COMMENT '应用 ID'", "belong_app_id");
		}

		if ($_arr_alert) {
			$_reselt = $this->obj_db->alert_table(BG_DB_TABLE . "app_belong", $_arr_alert);
			if (!$_reselt) {
				$this->obj_ajax->halt_alert("x070106");
			}
		}
	}


	private function table_log() {
		include_once(BG_PATH_MODEL . "log.class.php"); //载入管理帐号模型
		$_mdl_log     = new MODEL_LOG();
		$_arr_col     = $_mdl_log->mdl_column();
		$_arr_alert   = array();

		if (in_array("log_operator_id", $_arr_col)) {
			$_arr_alert["log_operator_id"] = array("CHANGE", "smallint NOT NULL COMMENT '操作者 ID'", "log_operator_id");
		}

		if (in_array("log_target_type", $_arr_col)) {
			$_arr_alert["log_target_type"] = array("CHANGE", "enum('admin','app','user','log','opt') NOT NULL COMMENT '目标类型'", "log_target_type");
		}

		if (in_array("log_type", $_arr_col)) {
			$_arr_alert["log_type"] = array("CHANGE", "enum('admin','app','system') NOT NULL COMMENT '目标类型'", "log_type");
		}

		if (in_array("log_status", $_arr_col)) {
			$_arr_alert["log_status"] = array("CHANGE", "enum('wait','read')", "log_status");
		}

		if ($_arr_alert) {
			$_reselt = $this->obj_db->alert_table(BG_DB_TABLE . "log", $_arr_alert);
			if (!$_reselt) {
				$this->obj_ajax->halt_alert("x060106");
			}
		}
	}


	private function view_user() {
		include_once(BG_PATH_MODEL . "user.class.php"); //载入管理帐号模型
		$_mdl_user       = new MODEL_USER();
		$_arr_user    = $_mdl_user->mdl_create_view();

		if ($_arr_user["alert"] != "y010108") {
			$this->obj_ajax->halt_alert($_arr_user["alert"]);
		}
	}


	private function check_db() {
		if (!fn_token("chk")) { //令牌
			$this->obj_ajax->halt_alert("x030102");
		}

		if (strlen(BG_DB_HOST) < 1 || strlen(BG_DB_NAME) < 1 || strlen(BG_DB_USER) < 1 || strlen(BG_DB_PASS) < 1 || strlen(BG_DB_CHARSET) < 1) {
			$this->obj_ajax->halt_alert("x030412");
		} else {
			if (!defined("BG_DB_PORT")) {
				define("BG_DB_PORT", "3306");
			}

			$_cfg_host = array(
				"host"      => BG_DB_HOST,
				"name"      => BG_DB_NAME,
				"user"      => BG_DB_USER,
				"pass"      => BG_DB_PASS,
				"charset"   => BG_DB_CHARSET,
				"debug"     => BG_DB_DEBUG,
				"port"      => BG_DB_PORT,
			);

			$GLOBALS["obj_db"]   = new CLASS_MYSQLI($_cfg_host); //设置数据库对象
			$this->obj_db        = $GLOBALS["obj_db"];

			if (!$this->obj_db->connect()) {
				$this->obj_ajax->halt_alert("x030111");
			}

			if (!$this->obj_db->select_db()) {
				$this->obj_ajax->halt_alert("x030112");
			}
		}
	}


	private function upgrade_init() {
		$_arr_extRow      = get_loaded_extensions();
		$_num_errCount    = 0;

		foreach ($this->obj_ajax->type["ext"] as $_key=>$_value) {
			if (!in_array($_key, $_arr_extRow)) {
				$_num_errCount++;
			}
		}

		if ($_num_errCount > 0) {
			$this->obj_ajax->halt_alert("x030418");
		}
	}
}