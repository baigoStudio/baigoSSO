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

/*-------------管理员控制器-------------*/
class CONTROL_OPT {

	private $adminLogged;
	private $obj_base;
	private $config; //配置
	private $obj_tpl;
	private $tplData;

	function __construct() { //构造函数
		$this->obj_base       = $GLOBALS["obj_base"]; //获取界面类型
		$this->config         = $this->obj_base->config;
		$this->adminLogged    = $GLOBALS["adminLogged"]; //获取已登录信息
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
		if (!isset($this->adminLogged["admin_allow"]["opt"]["reg"])) {
			return array(
				"alert" => "x040302",
			);
			exit;
		}

		$this->obj_tpl->tplDisplay("opt_reg.tpl", $this->tplData);

		return array(
			"alert" => "y040302",
		);
	}

	/**
	 * ctl_reg function.
	 *
	 * @access public
	 * @return void
	 */
	function ctl_mail() {
		if (!isset($this->adminLogged["admin_allow"]["opt"]["mail"])) {
			return array(
				"alert" => "x040303",
			);
			exit;
		}

		$this->obj_tpl->tplDisplay("opt_mail.tpl", $this->tplData);

		return array(
			"alert" => "y040303",
		);
	}

	/**
	 * ctl_base function.
	 *
	 * @access public
	 * @return void
	 */
	function ctl_base() {
		if (!isset($this->adminLogged["admin_allow"]["opt"]["base"])) {
			return array(
				"alert" => "x040301",
			);
			exit;
		}

		$this->obj_tpl->tplDisplay("opt_base.tpl", $this->tplData);

		return array(
			"alert" => "y040301",
		);
	}


	/**
	 * ctl_db function.
	 *
	 * @access public
	 * @return void
	 */
	function ctl_db() {
		if (!isset($this->adminLogged["admin_allow"]["opt"]["db"])) {
			return array(
				"alert" => "x040304",
			);
			exit;
		}

		$this->obj_tpl->tplDisplay("opt_db.tpl", $this->tplData);

		return array(
			"alert" => "y040304",
		);
	}
}
