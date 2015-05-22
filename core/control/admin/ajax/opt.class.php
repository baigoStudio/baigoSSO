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
		$this->log            = $this->obj_ajax->log; //初始化 AJAX 基对象
		$this->mdl_opt        = new MODEL_OPT(); //设置管理组模型
		$this->mdl_log        = new MODEL_LOG(); //设置管理员模型

		if (file_exists(BG_PATH_CONFIG . "is_install.php")) { //验证是否已经安装
			include_once(BG_PATH_CONFIG . "is_install.php");
			if (!defined("BG_INSTALL_PUB") || PRD_SSO_PUB > BG_INSTALL_PUB) {
				$this->obj_ajax->halt_alert("x030411");
			}
		} else {
			$this->obj_ajax->halt_alert("x030410");
		}

		if ($this->adminLogged["str_alert"] != "y020102") { //未登录，抛出错误信息
			$this->obj_ajax->halt_alert($this->adminLogged["str_alert"]);
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

		$_arr_optPost = $this->opt_post("reg");

		$this->obj_ajax->halt_alert($_arr_optPost["str_alert"]);
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

		$this->opt_post("mail");

		$_arr_mail    = array(BG_MAIL_FROM);
		$_arr_mailRow = fn_mailsend($_arr_mail, "test", "test", false);

		if ($_arr_mailRow["str_alert"] != "y030201") {
			$this->obj_ajax->halt_alert("x040101");
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

		$_arr_optPost = $this->opt_post("base");

		$this->obj_ajax->halt_alert($_arr_optPost["str_alert"]);
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

		$_str_dbHost      = fn_getSafe(fn_post("db_host"), "txt", "localhost");
		$_str_dbName      = fn_getSafe(fn_post("db_name"), "txt", "baigo_sso");
		$_str_dbUser      = fn_getSafe(fn_post("db_user"), "txt", "baigo_sso");
		$_str_dbPass      = fn_getSafe(fn_post("db_pass"), "txt", "");
		$_str_dbCharset   = fn_getSafe(fn_post("db_charset"), "txt", "utf8");
		$_str_dbTable     = fn_getSafe(fn_post("db_table"), "txt", "sso_");

		$_str_content = "<?php" . PHP_EOL;
		$_str_content .= "define(\"BG_DB_HOST\", \"" . $_str_dbHost . "\");" . PHP_EOL;
		$_str_content .= "define(\"BG_DB_NAME\", \"" . $_str_dbName . "\");" . PHP_EOL;
		$_str_content .= "define(\"BG_DB_USER\", \"" . $_str_dbUser . "\");" . PHP_EOL;
		$_str_content .= "define(\"BG_DB_PASS\", \"" . $_str_dbPass . "\");" . PHP_EOL;
		$_str_content .= "define(\"BG_DB_CHARSET\", \"" . $_str_dbCharset . "\");" . PHP_EOL;
		$_str_content .= "define(\"BG_DB_TABLE\", \"" . $_str_dbTable . "\");" . PHP_EOL;

		file_put_contents(BG_PATH_CONFIG . "config_db.inc.php", $_str_content);

		$this->obj_ajax->halt_alert("y040404");
	}

	/**
	 * opt_post function.
	 *
	 * @access private
	 * @param mixed $str_type
	 * @return void
	 */
	private function opt_post($str_type) {
		if (!fn_token("chk")) { //令牌
			return array(
				"str_alert" => "x030102",
			);
			exit;
		}

		$_arr_opt = fn_post("opt");

		$_str_content = "<?php" . PHP_EOL;
		foreach ($_arr_opt as $_key=>$_value) {
			$_arr_optChk = validateStr($_value, 1, 900);
			$_str_optValue = $_arr_optChk["str"];
			if (is_numeric($_value)) {
				$_str_content .= "define(\"" . $_key . "\", " . $_str_optValue . ");" . PHP_EOL;
			} else {
				$_str_content .= "define(\"" . $_key . "\", \"" . str_replace(PHP_EOL, "|", $_str_optValue) . "\");" . PHP_EOL;
			}
			$_arr_optRow = $this->mdl_opt->mdl_submit($_key, $_str_optValue);
			if ($_arr_optRow["str_alert"] == "y040101" || $_arr_optRow["str_alert"] == "y040103") {
				$_arr_targets[] = array(
					"opt_key" => $_key,
				);
			}
		}

		if ($str_type == "base") {
			$_str_content .= "define(\"BG_SITE_SSIN\", \"" . fn_rand(6) . "\");" . PHP_EOL;
		}

		$_str_content = str_replace("||", "", $_str_content);

		file_put_contents(BG_PATH_CONFIG . "opt_" . $str_type . ".inc.php", $_str_content);

		switch ($str_type) {
			case "base":
				$_arr_return = array(
					"opt_type"     => "base",
					"str_alert"    => "y040401",
				);
			break;

			case "reg":
				$_arr_return = array(
					"opt_type"     => "reg",
					"str_alert"    => "y040402",
				);
			break;
		}

		if ($_arr_targets) {
			$_str_targets    = json_encode($_arr_targets);
			$_str_return     = json_encode($_arr_return);
			$this->mdl_log->mdl_submit($_str_targets, "opt", $this->log["opt"]["edit"], $_str_return, "admin", $this->adminLogged["admin_id"]);
		}

		return $_arr_return;
	}
}