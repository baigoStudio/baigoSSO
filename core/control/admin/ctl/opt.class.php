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

/*-------------管理员控制器-------------*/
class CONTROL_OPT {

	private $adminLogged;
	private $obj_base;
	private $config; //配置
	private $obj_tpl;
	private $mdl_opt;
	private $tplData;

	function __construct() { //构造函数
		$this->obj_base       = $GLOBALS["obj_base"]; //获取界面类型
		$this->config         = $this->obj_base->config;
		$this->adminLogged    = $GLOBALS["adminLogged"]; //获取已登录信息
		$this->mdl_opt        = new MODEL_OPT(); //设置管理员模型
		$this->obj_tpl        = new CLASS_TPL(BG_PATH_TPL_ADMIN . $this->config["ui"]); //初始化视图对象
		$this->tplData = array(
			"adminLogged" => $this->adminLogged
		);
	}


	/**
	 * ctl_reg function.
	 *
	 * @access public
	 * @return void
	 */
	function ctl_reg() {
		if ($this->adminLogged["admin_allow"]["opt"]["reg"] != 1) {
			return array(
				"str_alert" => "x040302",
			);
			exit;
		}

		foreach ($this->obj_tpl->opt["reg"] as $_key=>$_value) {
			$_arr_optRows[$_key] = $this->mdl_opt->mdl_read($_key);
		}

		$this->tplData["optRows"] = $_arr_optRows;

		$this->obj_tpl->tplDisplay("opt_reg.tpl", $this->tplData);

		return array(
			"str_alert" => "y040302",
		);
	}

	/**
	 * ctl_reg function.
	 *
	 * @access public
	 * @return void
	 */
	function ctl_mail() {
		if ($this->adminLogged["admin_allow"]["opt"]["mail"] != 1) {
			return array(
				"str_alert" => "x040303",
			);
			exit;
		}

		foreach ($this->obj_tpl->opt["mail"] as $_key=>$_value) {
			$_arr_optRows[$_key] = $this->mdl_opt->mdl_read($_key);
		}

		$this->tplData["optRows"] = $_arr_optRows;

		$this->obj_tpl->tplDisplay("opt_mail.tpl", $this->tplData);

		return array(
			"str_alert" => "y040303",
		);
	}

	/**
	 * ctl_base function.
	 *
	 * @access public
	 * @return void
	 */
	function ctl_base() {
		if ($this->adminLogged["admin_allow"]["opt"]["base"] != 1) {
			return array(
				"str_alert" => "x040301",
			);
			exit;
		}

		foreach ($this->obj_tpl->opt["base"] as $_key=>$_value) {
			$_arr_optRows[$_key] = $this->mdl_opt->mdl_read($_key);
		}

		$this->tplData["optRows"] = $_arr_optRows;

		$this->obj_tpl->tplDisplay("opt_base.tpl", $this->tplData);

		return array(
			"str_alert" => "y040301",
		);
	}


	/**
	 * ctl_db function.
	 *
	 * @access public
	 * @return void
	 */
	function ctl_db() {
		if ($this->adminLogged["admin_allow"]["opt"]["db"] != 1) {
			return array(
				"str_alert" => "x040304",
			);
			exit;
		}

		$this->obj_tpl->tplDisplay("opt_db.tpl", $this->tplData);

		return array(
			"str_alert" => "y040304",
		);
	}
}
