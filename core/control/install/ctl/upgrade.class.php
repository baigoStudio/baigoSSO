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

class CONTROL_UPGRADE {

	private $obj_tpl;
	private $mdl_opt;

	function __construct() { //构造函数
		$this->obj_tpl    = new CLASS_TPL(BG_PATH_TPL_INSTALL);
	}

	/**
	 * upgrade_2 function.
	 *
	 * @access public
	 * @return void
	 */
	function ctl_dbtable() {
		if (!$this->check_db()) {
			return array(
				"str_alert" => "x030404",
			);
			exit;
		}

		$_arr_tplData = array();

		$this->obj_tpl->tplDisplay("upgrade_dbtable.tpl", $_arr_tplData);

		return array(
			"str_alert" => "y030404",
		);
	}


	/**
	 * upgrade_3 function.
	 *
	 * @access public
	 * @return void
	 */
	function ctl_base() {
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

		$this->obj_tpl->tplDisplay("upgrade_base.tpl", $_arr_tplData);

		return array(
			"str_alert" => "y030404",
		);
	}


	/**
	 * upgrade_4 function.
	 *
	 * @access public
	 * @return void
	 */
	function ctl_reg() {
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

		$this->obj_tpl->tplDisplay("upgrade_reg.tpl", $_arr_tplData);

		return array(
			"str_alert" => "y030404",
		);
	}


	function ctl_over() {
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

		$this->obj_tpl->tplDisplay("upgrade_over.tpl", $_arr_tplData);

		return array(
			"str_alert" => "y030404",
		);
	}


	private function check_db() {
		if (strlen(BG_DB_HOST) < 1 || strlen(BG_DB_NAME) < 1 || strlen(BG_DB_USER) < 1 || strlen(BG_DB_PASS) < 1 || strlen(BG_DB_CHARSET) < 1) {
			return false;
		} else {
			$_cfg_host = array(
				"host"      => BG_DB_HOST,
				"name"      => BG_DB_NAME,
				"user"      => BG_DB_USER,
				"pass"      => BG_DB_PASS,
				"charset"   => BG_DB_CHARSET,
				"debug"     => BG_DB_DEBUG,
			);
			$GLOBALS["obj_db"]   = new CLASS_MYSQL($_cfg_host); //初始化基类
			$this->obj_db        = $GLOBALS["obj_db"];
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
}
