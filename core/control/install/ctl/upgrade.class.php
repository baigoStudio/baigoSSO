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

class CONTROL_UPGRADE {

	private $obj_tpl;

	function __construct() { //构造函数
		$this->obj_base   = $GLOBALS["obj_base"];
		$this->config     = $this->obj_base->config;
		$this->obj_tpl    = new CLASS_TPL(BG_PATH_TPL . "install/" . $this->config["ui"]);
		$this->upgrade_init();
	}


	function ctl_ext() {
		$this->obj_tpl->tplDisplay("upgrade_ext.tpl", $this->tplData);

		return array(
			"alert" => "y030403",
		);
	}


	function ctl_dbconfig() {
		if ($this->errCount > 0) {
			return array(
				"alert" => "x030414",
			);
			exit;
		}

		$this->obj_tpl->tplDisplay("upgrade_dbconfig.tpl", $this->tplData);

		return array(
			"alert" => "y030404",
		);
	}


	function ctl_form() {
		if ($this->errCount > 0) {
			return array(
				"alert" => "x030414",
			);
			exit;
		}

		if (!$this->check_db()) {
			return array(
				"alert" => "x030412",
			);
			exit;
		}

		$this->obj_tpl->tplDisplay("upgrade_form.tpl", $this->tplData);

		return array(
			"alert" => "y030405",
		);
	}


	/**
	 * upgrade_2 function.
	 *
	 * @access public
	 * @return void
	 */
	function ctl_dbtable() {
		if ($this->errCount > 0) {
			return array(
				"alert" => "x030414",
			);
			exit;
		}

		if (!$this->check_db()) {
			return array(
				"alert" => "x030412",
			);
			exit;
		}

		$this->table_admin();
		$this->table_user();
		$this->table_app();
		$this->table_app_belong();
		$this->table_log();
		$this->view_user();

		$this->obj_tpl->tplDisplay("upgrade_dbtable.tpl", $this->tplData);

		return array(
			"alert" => "y030404",
		);
	}


	function ctl_over() {
		if ($this->errCount > 0) {
			return array(
				"alert" => "x030414",
			);
			exit;
		}

		if (!$this->check_db()) {
			return array(
				"alert" => "x030412",
			);
			exit;
		}

		$this->obj_tpl->tplDisplay("upgrade_over.tpl", $this->tplData);

		return array(
			"alert" => "y030405",
		);
	}


	private function check_db() {
		if (strlen(BG_DB_HOST) < 1 || strlen(BG_DB_NAME) < 1 || strlen(BG_DB_USER) < 1 || strlen(BG_DB_PASS) < 1 || strlen(BG_DB_CHARSET) < 1) {
			return false;
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
				"debug"     => BG_DEBUG_DB,
				"port"      => BG_DB_PORT,
			);

			$GLOBALS["obj_db"]   = new CLASS_MYSQLI($_cfg_host); //设置数据库对象
			$this->obj_db        = $GLOBALS["obj_db"];

			if (!$this->obj_db->connect()) {
				return false;
				exit;
			}

			if (!$this->obj_db->select_db()) {
				return false;
				exit;
			}

			return true;
		}
	}


	private function upgrade_init() {
		$_arr_extRow      = get_loaded_extensions();
		$this->errCount   = 0;

		foreach ($this->obj_tpl->type["ext"] as $_key=>$_value) {
			if (!in_array($_key, $_arr_extRow)) {
				$this->errCount++;
			}
		}

		$this->act_get = fn_getSafe($GLOBALS["act_get"], "txt", "ext");

		$this->tplData = array(
			"errCount"   => $this->errCount,
			"extRow"     => $_arr_extRow,
			"act_get"    => $this->act_get,
			"act_next"   => $this->install_next($this->act_get),
		);
	}

	private function install_next($act_get) {
		$_arr_optKeys = array_keys($this->obj_tpl->opt);
		$_index       = array_search($act_get, $_arr_optKeys);
		$_arr_opt     = array_slice($this->obj_tpl->opt, $_index + 1, 1);
        if ($_arr_opt) {
    		$_key = key($_arr_opt);
        } else {
    		$_key = "over";
        }

		return $_key;
	}


	private function table_admin() {
		include_once(BG_PATH_MODEL . "admin.class.php"); //载入管理帐号模型
		$_mdl_admin       = new MODEL_ADMIN();
		$_arr_adminTable  = $_mdl_admin->mdl_alert_table();

		$this->tplData["db_alert"]["admin_table"] = array(
    		"alert"   => $_arr_adminTable["alert"],
    		"status"  => substr($_arr_adminTable["alert"], 0, 1),
		);
	}


	private function table_user() {
		include_once(BG_PATH_MODEL . "user.class.php"); //载入管理帐号模型
		$_mdl_user        = new MODEL_USER();
		$_arr_userTable   = $_mdl_user->mdl_alert_table();

		$this->tplData["db_alert"]["user_table"] = array(
    		"alert"   => $_arr_userTable["alert"],
    		"status"  => substr($_arr_userTable["alert"], 0, 1),
		);
	}


	private function table_app() {
		include_once(BG_PATH_MODEL . "app.class.php"); //载入管理帐号模型
		$_mdl_app         = new MODEL_APP();
		$_arr_appTable    = $_mdl_app->mdl_alert_table();

		$this->tplData["db_alert"]["app_table"] = array(
    		"alert"   => $_arr_appTable["alert"],
    		"status"  => substr($_arr_appTable["alert"], 0, 1),
		);
	}


	private function table_app_belong() {
		include_once(BG_PATH_MODEL . "appBelong.class.php"); //载入管理帐号模型
		$_mdl_appBelong       = new MODEL_APP_BELONG();
		$_arr_belongCreate    = $_mdl_appBelong->mdl_create_table();
		$_arr_belongAlert     = $_mdl_appBelong->mdl_alert_table();

		$this->tplData["db_alert"]["app_belong_table_create"] = array(
    		"alert"   => $_arr_belongCreate["alert"],
    		"status"  => substr($_arr_belongCreate["alert"], 0, 1),
		);
		$this->tplData["db_alert"]["app_belong_table_alert"] = array(
    		"alert"   => $_arr_belongAlert["alert"],
    		"status"  => substr($_arr_belongAlert["alert"], 0, 1),
		);
	}


	private function table_log() {
		include_once(BG_PATH_MODEL . "log.class.php"); //载入管理帐号模型
		$_mdl_log         = new MODEL_LOG();
		$_arr_logTable    = $_mdl_log->mdl_alert_table();

		$this->tplData["db_alert"]["log_table"] = array(
    		"alert"   => $_arr_logTable["alert"],
    		"status"  => substr($_arr_logTable["alert"], 0, 1),
		);
	}


	private function view_user() {
		include_once(BG_PATH_MODEL . "user.class.php"); //载入管理帐号模型
		$_mdl_user        = new MODEL_USER();
		$_arr_userView    = $_mdl_user->mdl_create_view();

		$this->tplData["db_alert"]["user_view"] = array(
    		"alert"   => $_arr_userView["alert"],
    		"status"  => substr($_arr_userView["alert"], 0, 1),
		);
	}
}
