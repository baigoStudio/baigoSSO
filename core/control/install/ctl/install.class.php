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
		$this->obj_tpl    = new CLASS_TPL(BG_PATH_TPL . "install/" . $this->config["ui"]);
		$this->install_init();
	}


	function ctl_ext() {

		$this->obj_tpl->tplDisplay("install_ext.tpl", $this->tplData);

		return array(
			"alert" => "y030403",
		);
	}


    function ctl_form() {
		if ($this->errCount > 0) {
			return array(
				"alert" => "x030413",
			);
			exit;
		}

		if ($this->act_get != "dbconfig")
    		if (!$this->check_db()) {
    			return array(
    				"alert" => "x030404",
    			);
    			exit;
    		}
		}

		$this->obj_tpl->tplDisplay("install_form.tpl", $this->tplData);

		return array(
			"alert" => "y030405",
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


	private function install_init() {
		$_arr_extRow      = get_loaded_extensions();
		$this->errCount   = 0;

		foreach ($this->obj_tpl->type["ext"] as $_key=>$_value) {
			if (!in_array($_key, $_arr_extRow)) {
				$this->errCount++;
			}
		}

		$this->act_get = fn_getSafe($GLOBALS["act_get"], "txt", "base");

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
    		$_key = "dbtable";
        }

		return $_key;
	}
}
