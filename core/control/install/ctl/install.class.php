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

class CONTROL_INSTALL {

	private $obj_tpl;

	function __construct() { //构造函数
		$this->obj_base   = $GLOBALS["obj_base"];
		$this->config     = $this->obj_base->config;
		$this->obj_tpl    = new CLASS_TPL(BG_PATH_TPL_INSTALL . $this->config["ui"]);
		$this->install_init();
	}


	function ctl_ext() {

		$this->obj_tpl->tplDisplay("install_ext.tpl", $this->tplData);

		return array(
			"alert" => "y030403",
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
				"alert" => "x030413",
			);
			exit;
		}

		$this->obj_tpl->tplDisplay("install_dbconfig.tpl", $this->tplData);

		return array(
			"alert" => "y030403",
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
				"alert" => "x030413",
			);
			exit;
		}

		if (!$this->check_db()) {
			return array(
				"alert" => "x030404",
			);
			exit;
		}

		$this->obj_tpl->tplDisplay("install_dbtable.tpl", $this->tplData);

		return array(
			"alert" => "y030404",
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
				"alert" => "x030413",
			);
			exit;
		}

		if (!$this->check_db()) {
			return array(
				"alert" => "x030404",
			);
			exit;
		}

		$this->obj_tpl->tplDisplay("install_base.tpl", $this->tplData);

		return array(
			"alert" => "y030404",
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
				"alert" => "x030413",
			);
			exit;
		}

		if (!$this->check_db()) {
			return array(
				"alert" => "x030404",
			);
			exit;
		}

		$this->obj_tpl->tplDisplay("install_reg.tpl", $this->tplData);

		return array(
			"alert" => "y030404",
		);
	}


	function ctl_auto() {
		if ($this->errCount > 0) {
			return array(
				"alert" => "x030413",
			);
			exit;
		}

		if (!$this->check_db()) {
			return array(
				"alert" => "x030404",
			);
			exit;
		}

		$_str_url     = fn_getSafe(fn_get("url"), "txt", "");
		$_str_path    = fn_getSafe(fn_get("path"), "txt", "");
		$_str_target  = fn_getSafe(fn_get("target"), "txt", "");

		$this->tplData["url"]     = base64_decode($_str_url);
		$this->tplData["path"]    = $_str_path;
		$this->tplData["target"]  = $_str_target;

		$this->obj_tpl->tplDisplay("install_auto.tpl", $this->tplData);

		return array(
			"alert" => "y030404",
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
				"alert" => "x030413",
			);
			exit;
		}

		if (!$this->check_db()) {
			return array(
				"alert" => "x030404",
			);
			exit;
		}

		$this->obj_tpl->tplDisplay("install_admin.tpl", $this->tplData);

		return array(
			"alert" => "y030404",
		);
	}


	function ctl_over() {
		if ($this->errCount > 0) {
			return array(
				"alert" => "x030413",
			);
			exit;
		}

		if (!$this->check_db()) {
			return array(
				"alert" => "x030404",
			);
			exit;
		}

		$this->obj_tpl->tplDisplay("install_over.tpl", $this->tplData);

		return array(
			"alert" => "y030404",
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
