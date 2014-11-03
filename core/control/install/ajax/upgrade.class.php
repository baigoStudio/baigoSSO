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
	}


	function ajax_dbtable() {
		$this->check_db();

		$this->table_admin();
		//$this->table_user();
		//$this->table_app();
		$this->table_app_belong();
		//$this->table_log();
		//$this->table_opt();

		$this->obj_ajax->halt_alert("y030103");
	}


	/**
	 * install_2_do function.
	 *
	 * @access public
	 * @return void
	 */
	function ajax_base() {
		$_arr_optPost = $this->opt_post("base");

		$this->obj_ajax->halt_alert("y030405");
	}


	/**
	 * install_3_do function.
	 *
	 * @access public
	 * @return void
	 */
	function ajax_reg() {
		$_arr_optPost = $this->opt_post("reg");

		$this->obj_ajax->halt_alert("y030406");
	}


	function ajax_over() {
		$_str_content = "<?php" . PHP_EOL;
		$_str_content .= "define(\"BG_INSTALL_VER\", \"" . PRD_SSO_VER . "\");" . PHP_EOL;
		$_str_content .= "define(\"BG_INSTALL_PUB\", " . PRD_SSO_PUB . ");" . PHP_EOL;
		$_str_content .= "define(\"BG_INSTALL_TIME\", " . time() . ");" . PHP_EOL;
		$_str_content .= "?>";

		file_put_contents(BG_PATH_CONFIG . "is_install.php", $_str_content);

		$this->obj_ajax->halt_alert("y030408");
	}

	/**
	 * opt_post function.
	 *
	 * @access private
	 * @param mixed $str_type
	 * @return void
	 */
	private function opt_post($str_type) {
		$this->check_db();

		$_mdl_opt = new MODEL_OPT(); //设置管理组模型

		$_arr_opt = $_POST["opt"];

		$_str_content = "<?php" . PHP_EOL;
		foreach ($_arr_opt as $_key=>$_value) {
			$_arr_optChk = validateStr($_value, 1, 900);
			$_str_optValue = $_arr_optChk["str"];
			if (is_numeric($_value)) {
				$_str_content .= "define(\"" . $_key . "\", " . $_str_optValue . ");" . PHP_EOL;
			} else {
				$_str_content .= "define(\"" . $_key . "\", \"" . str_replace(PHP_EOL, "|", $_str_optValue) . "\");" . PHP_EOL;
			}
			$_arr_optRow = $_mdl_opt->mdl_submit($_key, $_str_optValue);
		}

		if ($str_type == "base") {
			$_str_content .= "define(\"BG_SITE_SSIN\", \"" . fn_rand(6) . "\");" . PHP_EOL;
		}

		$_str_content .= "?>";

		$_str_content = str_replace("||", "", $_str_content);

		file_put_contents(BG_PATH_CONFIG . "opt_" . $str_type . ".inc.php", $_str_content);
	}


	private function check_db() {
		if (!fn_token("chk")) { //令牌
			$this->obj_ajax->halt_alert("x030102");
		}

		if (strlen(BG_DB_HOST) < 1 || strlen(BG_DB_NAME) < 1 || strlen(BG_DB_USER) < 1 || strlen(BG_DB_PASS) < 1 || strlen(BG_DB_CHARSET) < 1) {
			$this->obj_ajax->halt_alert("x030404");
		} else {
			$GLOBALS["obj_db"]   = new CLASS_MYSQL(); //初始化基类
			$this->obj_db        = $GLOBALS["obj_db"];
		}
	}


	private function table_admin() {
		include_once(BG_PATH_MODEL . "admin.class.php"); //载入管理帐号模型
		$_mdl_admin   = new MODEL_ADMIN();
		$_arr_col     = $_mdl_admin->mdl_column();

		if (!in_array("admin_nick", $_arr_col)) {
			$_arr_alert["admin_nick"] = array("ADD", "varchar(30) NOT NULL COMMENT '昵称'");
		}

		if ($_arr_alert) {
			$_reselt = $this->obj_db->alert_table(BG_DB_TABLE . "admin", $_arr_alert);
			if (!$_reselt) {
				$this->obj_ajax->halt_alert("x020106");
			}
		}
	}


	private function table_user() {
		/*include_once(BG_PATH_MODEL . "user.class.php"); //载入管理帐号模型
		$_mdl_user    = new MODEL_USER();
		$_arr_userRow = $_mdl_user->mdl_create();

		if ($_arr_userRow["str_alert"] != "y010105") {
			$this->obj_ajax->halt_alert($_arr_userRow["str_alert"]);
		}*/
	}


	private function table_app() {
		/*include_once(BG_PATH_MODEL . "app.class.php"); //载入管理帐号模型
		$_mdl_app     = new MODEL_APP();
		$_arr_appRow  = $_mdl_app->mdl_create();

		if ($_arr_appRow["str_alert"] != "y050105") {
			$this->obj_ajax->halt_alert($_arr_appRow["str_alert"]);
		}*/
	}


	private function table_app_belong() {
		include_once(BG_PATH_MODEL . "appBelong.class.php"); //载入管理帐号模型
		$_mdl_appBelong       = new MODEL_APP_BELONG();
		$_arr_appBelongRow    = $_mdl_appBelong->mdl_create();

		if ($_arr_appBelongRow["str_alert"] != "y070105") {
			$this->obj_ajax->halt_alert($_arr_appBelongRow["str_alert"]);
		}

		$_arr_appBelongRow    = $_mdl_appBelong->mdl_create_view();

		if ($_arr_appBelongRow["str_alert"] != "y070108") {
			$this->obj_ajax->halt_alert($_arr_appBelongRow["str_alert"]);
		}
	}


	private function table_log() {
		/*include_once(BG_PATH_MODEL . "log.class.php"); //载入管理帐号模型
		$_mdl_log     = new MODEL_LOG();
		$_arr_logRow  = $_mdl_log->mdl_create();

		if ($_arr_logRow["str_alert"] != "y060105") {
			$this->obj_ajax->halt_alert($_arr_logRow["str_alert"]);
		}*/
	}


	private function table_opt() {
		include_once(BG_PATH_MODEL . "opt.class.php"); //载入管理帐号模型
		$_mdl_opt     = new MODEL_OPT();
		$_arr_optRow  = $_mdl_opt->mdl_create();

		if ($_arr_optRow["str_alert"] != "y040105") {
			$this->obj_ajax->halt_alert($_arr_optRow["str_alert"]);
		}
	}
}
?>