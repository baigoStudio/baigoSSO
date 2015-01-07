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
	}


	/**
	 * install_1_do function.
	 *
	 * @access public
	 * @return void
	 */
	function ajax_dbconfig() {
		if (!fn_token("chk")) { //令牌
			$this->obj_ajax->halt_alert("x030102");
		}

		$_str_dbHost      = fn_getSafe($_POST["db_host"], "txt", "localhost");
		$_str_dbName      = fn_getSafe($_POST["db_name"], "txt", "sso");
		$_str_dbUser      = fn_getSafe($_POST["db_user"], "txt", "sso");
		$_str_dbPass      = fn_getSafe($_POST["db_pass"], "txt", "");
		$_str_dbCharset   = fn_getSafe($_POST["db_charset"], "txt", "utf8");
		$_str_dbTable     = fn_getSafe($_POST["db_table"], "txt", "sso_");

		$_str_content = "<?php" . PHP_EOL;
			$_str_content .= "define(\"BG_DB_HOST\", \"" . $_str_dbHost . "\");" . PHP_EOL;
			$_str_content .= "define(\"BG_DB_NAME\", \"" . $_str_dbName . "\");" . PHP_EOL;
			$_str_content .= "define(\"BG_DB_USER\", \"" . $_str_dbUser . "\");" . PHP_EOL;
			$_str_content .= "define(\"BG_DB_PASS\", \"" . $_str_dbPass . "\");" . PHP_EOL;
			$_str_content .= "define(\"BG_DB_CHARSET\", \"" . $_str_dbCharset . "\");" . PHP_EOL;
			$_str_content .= "define(\"BG_DB_TABLE\", \"" . $_str_dbTable . "\");" . PHP_EOL;
		$_str_content .= "?>";

		file_put_contents(BG_PATH_CONFIG . "config_db.inc.php", $_str_content);

		$this->obj_ajax->halt_alert("y030404");
	}


	function ajax_dbtable() {
		$this->check_db();

		$this->table_admin();
		$this->table_user();
		$this->table_app();
		$this->table_app_belong();
		$this->table_log();
		$this->table_opt();

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
		$this->check_opt();

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
		$this->check_db();
		$this->check_opt();

		$_arr_optPost = $this->opt_post("reg");

		$this->obj_ajax->halt_alert("y030406");
	}


	function ajax_admin() {
		if (file_exists(BG_PATH_CONFIG . "is_install.php")) {
			$this->install_over();
			$this->obj_ajax->halt_alert("y030407");
		}

		$this->check_db();
		$this->check_opt();

		include_once(BG_PATH_MODEL . "admin.class.php"); //载入管理帐号模型
		$_mdl_admin  = new MODEL_ADMIN();

		$_arr_adminSubmit = $_mdl_admin->input_submit();

		if ($_arr_adminSubmit["str_alert"] != "ok") {
			$this->obj_ajax->halt_alert($_arr_adminSubmit["str_alert"]);
		}

		$_arr_adminPass = validateStr($_POST["admin_pass"], 1, 0);
		switch ($_arr_adminPass["status"]) {
			case "too_short":
				$this->obj_ajax->halt_alert("x020205");
			break;

			case "ok":
				$_str_adminPass = $_arr_adminPass["str"];
			break;
		}

		$_arr_adminPassConfirm = validateStr($_POST["admin_pass_confirm"], 1, 0);
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

		$_str_pathParent  = fn_getSafe($_POST["pathParent"], "txt", "");
		$this->target     = fn_getSafe($_POST["target"], "txt", "");
		$this->pathParent = base64_decode($_str_pathParent);

		if (!file_exists($this->pathParent)) {
			$this->obj_ajax->halt_alert("x030103");
		}

		$this->table_admin();
		$this->table_user();
		$this->table_app();
		$this->table_app_belong();
		$this->table_log();
		$this->table_opt();

		$this->record_app();

		$this->obj_ajax->halt_alert("y030104");
	}


	function ajax_over() {
		$this->check_db();
		$this->check_opt();

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


	private function table_admin() {
		include_once(BG_PATH_MODEL . "admin.class.php"); //载入管理帐号模型
		$_mdl_admin       = new MODEL_ADMIN();
		$_arr_adminRow    = $_mdl_admin->mdl_create();

		if ($_arr_adminRow["str_alert"] != "y020105") {
			$this->obj_ajax->halt_alert($_arr_adminRow["str_alert"]);
		}
	}


	private function table_user() {
		include_once(BG_PATH_MODEL . "user.class.php"); //载入管理帐号模型
		$_mdl_user    = new MODEL_USER();
		$_arr_userRow = $_mdl_user->mdl_create();

		if ($_arr_userRow["str_alert"] != "y010105") {
			$this->obj_ajax->halt_alert($_arr_userRow["str_alert"]);
		}
	}


	private function table_app() {
		include_once(BG_PATH_MODEL . "app.class.php"); //载入管理帐号模型
		$_mdl_app     = new MODEL_APP();
		$_arr_appRow  = $_mdl_app->mdl_create();

		if ($_arr_appRow["str_alert"] != "y050105") {
			$this->obj_ajax->halt_alert($_arr_appRow["str_alert"]);
		}
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
		include_once(BG_PATH_MODEL . "log.class.php"); //载入管理帐号模型
		$_mdl_log     = new MODEL_LOG();
		$_arr_logRow  = $_mdl_log->mdl_create();

		if ($_arr_logRow["str_alert"] != "y060105") {
			$this->obj_ajax->halt_alert($_arr_logRow["str_alert"]);
		}
	}


	private function table_opt() {
		include_once(BG_PATH_MODEL . "opt.class.php"); //载入管理帐号模型
		$_mdl_opt     = new MODEL_OPT();
		$_arr_optRow  = $_mdl_opt->mdl_create();

		if ($_arr_optRow["str_alert"] != "y040105") {
			$this->obj_ajax->halt_alert($_arr_optRow["str_alert"]);
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

			case "sm":
				$_str_appName = "SmartMuseum";
				$_str_const   = "SM_";
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
		$_str_content .= "?>";

		file_put_contents($this->pathParent . "opt_sso.inc.php", $_str_content);
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

	private function check_opt() {
		$_arr_tableSelect = array(
			"table_name",
		);

		$_str_sqlWhere    = "table_schema='" . BG_DB_NAME . "'";
		$_arr_tableRows   = $GLOBALS["obj_db"]->select_array("information_schema`.`tables", $_arr_tableSelect, $_str_sqlWhere, 100, 0);

		foreach ($_arr_tableRows as $_key=>$_value) {
			$_arr_chks[] = $_value["table_name"];
		}

		if (!in_array(BG_DB_TABLE . "opt", $_arr_chks)) {
			$this->obj_ajax->halt_alert("x030409");
		}
	}
}
?>