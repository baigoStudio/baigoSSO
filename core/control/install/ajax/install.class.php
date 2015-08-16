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

class AJAX_INSTALL {

	private $obj_ajax;
	private $obj_db;

	function __construct() { //构造函数
		$this->obj_ajax       = new CLASS_AJAX(); //初始化 AJAX 基对象

		if (file_exists(BG_PATH_CONFIG . "is_install.php")) {
			$this->obj_ajax->halt_alert("x030403");
		}

		$this->install_init();
		$this->mdl_opt = new MODEL_OPT();
	}


	/**
	 * install_1_do function.
	 *
	 * @access public
	 * @return void
	 */
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

		$this->obj_ajax->halt_alert("y030103");
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


	function ajax_admin() {
		if (file_exists(BG_PATH_CONFIG . "is_install.php")) {
			$this->install_over();
			$this->obj_ajax->halt_alert("y030407");
		}

		$this->check_db();

		include_once(BG_PATH_MODEL . "admin.class.php"); //载入管理帐号模型
		$_mdl_admin  = new MODEL_ADMIN();

		$_arr_adminSubmit = $_mdl_admin->input_submit();

		if ($_arr_adminSubmit["alert"] != "ok") {
			$this->obj_ajax->halt_alert($_arr_adminSubmit["alert"]);
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

		$_arr_adminPassConfirm = validateStr(fn_post("admin_pass_confirm"), 1, 0);
		switch ($_arr_adminPassConfirm["status"]) {
			case "too_short":
				$this->obj_ajax->halt_alert("x020211");
			break;

			case "ok":
				$_str_adminPassConfirm = $_arr_adminPassConfirm["str"];
			break;
		}

		if ($_str_adminPass != $_str_adminPassConfirm) {
			$this->obj_ajax->halt_alert("x020206");
		}

		$_str_adminRand      = fn_rand(6);
		$_str_adminPassDo    = fn_baigoEncrypt($_str_adminPass, $_str_adminRand);

		$_arr_adminRow = $_mdl_admin->mdl_submit($_str_adminPassDo, $_str_adminRand);

		$this->obj_ajax->halt_alert("y030407");
	}


	function ajax_auto() {
		$this->check_db();

		$_str_pathParent  = fn_getSafe(fn_post("pathParent"), "txt", "");
		$this->target     = fn_getSafe(fn_post("target"), "txt", "");
		$this->pathParent = base64_decode($_str_pathParent);

		if (!file_exists($this->pathParent)) {
			$this->obj_ajax->halt_alert("x030103");
		}

		$this->table_admin();
		$this->table_user();
		$this->table_app();
		$this->table_app_belong();
		$this->table_log();
		$this->view_user();

		$this->record_app();

		$this->obj_ajax->halt_alert("y030104");
	}


	function ajax_over() {
		$this->check_db();

		$_arr_return = $this->mdl_opt->mdl_over();

		if ($_arr_return["alert"] != "y040101") {
			$this->obj_ajax->halt_alert($_arr_return["alert"]);
		}

		$this->obj_ajax->halt_alert("y030408");
	}


	private function table_admin() {
		include_once(BG_PATH_MODEL . "admin.class.php"); //载入管理帐号模型
		$_mdl_admin       = new MODEL_ADMIN();
		$_arr_adminRow    = $_mdl_admin->mdl_create_table();

		if ($_arr_adminRow["alert"] != "y020105") {
			$this->obj_ajax->halt_alert($_arr_adminRow["alert"]);
		}
	}


	private function table_user() {
		include_once(BG_PATH_MODEL . "user.class.php"); //载入管理帐号模型
		$_mdl_user    = new MODEL_USER();
		$_arr_userRow = $_mdl_user->mdl_create_table();

		if ($_arr_userRow["alert"] != "y010105") {
			$this->obj_ajax->halt_alert($_arr_userRow["alert"]);
		}
	}


	private function table_app() {
		include_once(BG_PATH_MODEL . "app.class.php"); //载入管理帐号模型
		$_mdl_app     = new MODEL_APP();
		$_arr_appRow  = $_mdl_app->mdl_create_table();

		if ($_arr_appRow["alert"] != "y050105") {
			$this->obj_ajax->halt_alert($_arr_appRow["alert"]);
		}
	}


	private function table_app_belong() {
		include_once(BG_PATH_MODEL . "appBelong.class.php"); //载入管理帐号模型
		$_mdl_appBelong       = new MODEL_APP_BELONG();
		$_arr_appBelongRow    = $_mdl_appBelong->mdl_create_table();

		if ($_arr_appBelongRow["alert"] != "y070105") {
			$this->obj_ajax->halt_alert($_arr_appBelongRow["alert"]);
		}
	}


	private function table_log() {
		include_once(BG_PATH_MODEL . "log.class.php"); //载入管理帐号模型
		$_mdl_log     = new MODEL_LOG();
		$_arr_logRow  = $_mdl_log->mdl_create_table();

		if ($_arr_logRow["alert"] != "y060105") {
			$this->obj_ajax->halt_alert($_arr_logRow["alert"]);
		}
	}


	private function view_user() {
		include_once(BG_PATH_MODEL . "user.class.php"); //载入管理帐号模型
		$_mdl_user       = new MODEL_USER();
		$_arr_user    = $_mdl_user->mdl_create_view();

		if ($_arr_user["alert"] != "y070108") {
			$this->obj_ajax->halt_alert($_arr_user["alert"]);
		}
	}


	private function record_app() {

		$_arr_appAllow = array(
			"user" => array(
				"reg"       => 1,
				"login"     => 1,
				"get"       => 1,
				"edit"      => 1,
				"del"       => 1,
				"chkname"   => 1,
				"chkmail"   => 1,
			),
			"code" => array(
				"signature" => 1,
				"verify"    => 1,
				"encode"    => 1,
				"decode"    => 1,
			),
		);

		$_str_appAllow    = json_encode($_arr_appAllow);
		$_str_appKey      = fn_rand(64);

		switch ($this->target) {
			case "cms":
				$_str_appName = "baigo CMS";
				$_str_const   = "BG_";
			break;

			case "im":
				$_str_appName = "iWee Museum";
				$_str_const   = "IW_";
			break;

			case "ib":
				$_str_appName = "iWee Book";
				$_str_const   = "IW_";
			break;
		}

		$_arr_appInsert = array(
			"app_name"           => $_str_appName,
			"app_key"            => $_str_appKey,
			"app_notice"         => "sso_note.php",
			"app_token"          => "",
			"app_token_expire"   => 604800,
			"app_token_time"     => time(),
			"app_status"         => "enable",
			"app_note"           => $_str_appName,
			"app_time"           => time(),
			"app_ip_allow"       => "",
			"app_ip_bad"         => "",
			"app_sync"           => "on",
			"app_allow"          => $_str_appAllow,
		);

		$_num_appId = $this->obj_db->insert(BG_DB_TABLE . "app", $_arr_appInsert);

		if ($_num_appId <= 0 || !$_num_appId) {
			$this->obj_ajax->halt_alert("x050101");
		}

		$_str_content = "<?php" . PHP_EOL;
		$_str_content .= "define(\"" . $_str_const . "SSO_URL\", \"" . BG_SITE_URL . BG_URL_API . "api.php\");" . PHP_EOL;
		$_str_content .= "define(\"" . $_str_const . "SSO_APPID\", " . $_num_appId . ");" . PHP_EOL;
		$_str_content .= "define(\"" . $_str_const . "SSO_APPKEY\", \"" . $_str_appKey . "\");" . PHP_EOL;
		$_str_content .= "define(\"" . $_str_const . "SSO_SYNLOGON\", \"on\");" . PHP_EOL;

		file_put_contents($this->pathParent . "opt_sso.inc.php", $_str_content);
	}


	private function check_db() {
		if (!fn_token("chk")) { //令牌
			$this->obj_ajax->halt_alert("x030102");
		}

		if (strlen(BG_DB_HOST) < 1 || strlen(BG_DB_NAME) < 1 || strlen(BG_DB_USER) < 1 || strlen(BG_DB_PASS) < 1 || strlen(BG_DB_CHARSET) < 1) {
			$this->obj_ajax->halt_alert("x030404");
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


	private function install_init() {
		$_arr_extRow     = get_loaded_extensions();
		$_num_errCount   = 0;

		foreach ($this->obj_ajax->type["ext"] as $_key=>$_value) {
			if (!in_array($_key, $_arr_extRow)) {
				$_num_errCount++;
			}
		}

		if ($_num_errCount > 0) {
			$this->obj_ajax->halt_alert("x030417");
		}
	}

}
