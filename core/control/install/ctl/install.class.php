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
include_once(BG_PATH_MODEL . "opt.class.php"); //载入管理帐号模型

class CONTROL_INSTALL {

	private $obj_tpl;
	private $mdl_opt;

	function __construct() { //构造函数
		$this->obj_base   = $GLOBALS["obj_base"];
		$this->config     = $this->obj_base->config;
		$this->obj_tpl    = new CLASS_TPL(BG_PATH_TPL_INSTALL . $this->config["ui"]);
		$this->install_init();
	}


	function ctl_ext() {
		$this->obj_tpl->tplDisplay("install_ext.tpl", $this->tplData);

		return array(
			"str_alert" => "y030403",
		);
	}


	/**
	 * install_1 function.
	 *
	 * @access public
	 * @return void
	 */
	function ctl_dbconfig() {
		if ($this->errCount > 0) {
			return array(
				"str_alert" => "x030413",
			);
			exit;
		}

		$_arr_tplData = array();

		$this->obj_tpl->tplDisplay("install_dbconfig.tpl", $_arr_tplData);

		return array(
			"str_alert" => "y030403",
		);
	}


	/**
	 * install_2 function.
	 *
	 * @access public
	 * @return void
	 */
	function ctl_dbtable() {
		if ($this->errCount > 0) {
			return array(
				"str_alert" => "x030413",
			);
			exit;
		}

		if (!$this->check_db()) {
			return array(
				"str_alert" => "x030404",
			);
			exit;
		}

		$_arr_tplData = array();

		$this->obj_tpl->tplDisplay("install_dbtable.tpl", $_arr_tplData);

		return array(
			"str_alert" => "y030404",
		);
	}


	/**
	 * install_3 function.
	 *
	 * @access public
	 * @return void
	 */
	function ctl_base() {
		if ($this->errCount > 0) {
			return array(
				"str_alert" => "x030413",
			);
			exit;
		}

		if (!$this->check_db()) {
			return array(
				"str_alert" => "x030404",
			);
			exit;
		}

		if (!$this->check_opt()) {
			return array(
				"str_alert" => "x030409",
			);
			exit;
		}


		foreach ($this->obj_tpl->opt["base"] as $_key=>$_value) {
			$_arr_optRows[$_key] = $this->mdl_opt->mdl_read($_key);
		}

		$_arr_tplData["optRows"] = $_arr_optRows;

		$this->obj_tpl->tplDisplay("install_base.tpl", $_arr_tplData);

		return array(
			"str_alert" => "y030404",
		);
	}


	/**
	 * install_4 function.
	 *
	 * @access public
	 * @return void
	 */
	function ctl_reg() {
		if ($this->errCount > 0) {
			return array(
				"str_alert" => "x030413",
			);
			exit;
		}

		if (!$this->check_db()) {
			return array(
				"str_alert" => "x030404",
			);
			exit;
		}

		if (!$this->check_opt()) {
			return array(
				"str_alert" => "x030409",
			);
			exit;
		}

		foreach ($this->obj_tpl->opt["reg"] as $_key=>$_value) {
			$_arr_optRows[$_key] = $this->mdl_opt->mdl_read($_key);
		}

		$_arr_tplData["optRows"] = $_arr_optRows;

		$this->obj_tpl->tplDisplay("install_reg.tpl", $_arr_tplData);

		return array(
			"str_alert" => "y030404",
		);
	}


	function ctl_auto() {
		if ($this->errCount > 0) {
			return array(
				"str_alert" => "x030413",
			);
			exit;
		}

		if (!$this->check_db()) {
			return array(
				"str_alert" => "x030404",
			);
			exit;
		}

		$_str_url     = fn_getSafe(fn_get("url"), "txt", "");
		$_str_path    = fn_getSafe(fn_get("path"), "txt", "");
		$_str_target  = fn_getSafe(fn_get("target"), "txt", "");

		$_arr_tplData = array(
			"url"    => base64_decode($_str_url),
			"path"   => $_str_path,
			"target" => $_str_target,
		);

		$this->obj_tpl->tplDisplay("install_auto.tpl", $_arr_tplData);

		return array(
			"str_alert" => "y030404",
		);
	}


	/**
	 * ctl_admin function.
	 *
	 * @access public
	 * @return void
	 */
	function ctl_admin() {
		if ($this->errCount > 0) {
			return array(
				"str_alert" => "x030413",
			);
			exit;
		}

		if (!$this->check_db()) {
			return array(
				"str_alert" => "x030404",
			);
			exit;
		}

		if (!$this->check_opt()) {
			return array(
				"str_alert" => "x030409",
			);
			exit;
		}

		$_arr_tplData = array();

		$this->obj_tpl->tplDisplay("install_admin.tpl", $_arr_tplData);

		return array(
			"str_alert" => "y030404",
		);
	}


	function ctl_over() {
		if ($this->errCount > 0) {
			return array(
				"str_alert" => "x030413",
			);
			exit;
		}

		if (!$this->check_db()) {
			return array(
				"str_alert" => "x030404",
			);
			exit;
		}

		if (!$this->check_opt()) {
			return array(
				"str_alert" => "x030409",
			);
			exit;
		}

		$_arr_tplData = array();

		$this->obj_tpl->tplDisplay("install_over.tpl", $_arr_tplData);

		return array(
			"str_alert" => "y030404",
		);
	}


	private function check_db() {
		if (strlen(BG_DB_HOST) < 1 || strlen(BG_DB_NAME) < 1 || strlen(BG_DB_USER) < 1 || strlen(BG_DB_PASS) < 1 || strlen(BG_DB_CHARSET) < 1) {
			return false;
			exit;
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
				return false;
				exit;
			}

			if (!$this->obj_db->select_db()) {
				return false;
				exit;
			}

			$this->mdl_opt       = new MODEL_OPT(); //设置管理员模型
			return true;
		}
	}


	private function check_opt() {
		$_arr_tableRows = $this->obj_db->show_tables();

		foreach ($_arr_tableRows as $_key=>$_value) {
			$_arr_tables[] = $_value["Tables_in_" . BG_DB_NAME];
		}

		if (!in_array(BG_DB_TABLE . "opt", $_arr_tables)) {
			return false;
		} else {
			return true;
		}
	}


	private function install_init() {
		$_arr_extRow      = get_loaded_extensions();
		$this->errCount   = 0;

		foreach ($this->obj_tpl->type["ext"] as $_key=>$_value) {
			if (!in_array($_key, $_arr_extRow)) {
				$this->errCount++;
			}
		}

		$this->tplData = array(
			"errCount"   => $this->errCount,
			"extRow"     => $_arr_extRow,
		);
	}
}
