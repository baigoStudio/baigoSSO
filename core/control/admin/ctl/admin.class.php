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
class CONTROL_ADMIN {

	private $adminLogged;
	private $obj_base;
	private $config; //配置
	private $obj_tpl;
	private $mdl_admin;
	private $tplData;

	function __construct() { //构造函数
		$this->obj_base       = $GLOBALS["obj_base"]; //获取界面类型
		$this->config         = $this->obj_base->config;
		$this->adminLogged    = $GLOBALS["adminLogged"]; //获取已登录信息
		$this->mdl_admin      = new MODEL_ADMIN(); //设置管理员模型
		$this->obj_tpl        = new CLASS_TPL(BG_PATH_TPL . "admin/" . $this->config["ui"]); //初始化视图对象
		$this->tplData = array(
			"adminLogged" => $this->adminLogged
		);
	}

	function ctl_show() {
		$_num_adminId = fn_getSafe(fn_get("admin_id"), "int", 0);

		if (!isset($this->adminLogged["admin_allow"]["admin"]["browse"])) {
			return array(
				"alert" => "x020303",
			);
			exit;
		}
		$_arr_adminRow = $this->mdl_admin->mdl_read($_num_adminId);
		if ($_arr_adminRow["alert"] != "y020102") {
			return $_arr_adminRow;
			exit;
		}

		$this->tplData["adminRow"] = $_arr_adminRow; //管理员信息

		$this->obj_tpl->tplDisplay("admin_show.tpl", $this->tplData);

		return array(
			"alert" => "y020102",
		);
	}


	/**
	 * ctl_form function.
	 *
	 * @access public
	 * @return void
	 */
	function ctl_form() {
		$_num_adminId = fn_getSafe(fn_get("admin_id"), "int", 0);

		if ($_num_adminId > 0) {
			if (!isset($this->adminLogged["admin_allow"]["admin"]["edit"])) {
				return array(
					"alert" => "x020303",
				);
				exit;
			}
			if ($_num_adminId == $this->adminLogged["admin_id"]) {
				return array(
					"alert" => "x020306",
				);
				exit;
			}
			$_arr_adminRow = $this->mdl_admin->mdl_read($_num_adminId);
			if ($_arr_adminRow["alert"] != "y020102") {
				return $_arr_adminRow;
				exit;
			}
		} else {
			if (!isset($this->adminLogged["admin_allow"]["admin"]["add"])) {
				return array(
					"alert" => "x020302",
				);
				exit;
			}
			$_arr_adminRow = array(
				"admin_id"      => 0,
				"admin_nick"    => "",
				"admin_note"    => "",
				"admin_status"  => "enable",
			);
		}

		$this->tplData["adminRow"] = $_arr_adminRow; //管理员信息

		$this->obj_tpl->tplDisplay("admin_form.tpl", $this->tplData);

		return array(
			"alert" => "y020102",
		);
	}


	/**
	 * ctl_list function.
	 *
	 * @access public
	 * @return void
	 */
	function ctl_list() {
		if (!isset($this->adminLogged["admin_allow"]["admin"]["browse"])) {
			return array(
				"alert" => "x020301",
			);
			exit;
		}

		$_str_key     = fn_getSafe(fn_get("key"), "txt", "");
		$_str_status  = fn_getSafe(fn_get("status"), "txt", "");

		$_arr_search = array(
			"key"        => $_str_key,
			"status"     => $_str_status,
		);

		$_num_adminCount  = $this->mdl_admin->mdl_count($_str_key, $_str_status);
		$_arr_page        = fn_page($_num_adminCount); //取得分页数据
		$_str_query       = http_build_query($_arr_search);
		$_arr_adminRows   = $this->mdl_admin->mdl_list(BG_DEFAULT_PERPAGE, $_arr_page["except"], $_str_key, $_str_status);

		$_arr_tpl = array(
			"query"      => $_str_query,
			"pageRow"    => $_arr_page,
			"search"     => $_arr_search,
			"adminRows"  => $_arr_adminRows,
		);

		$_arr_tplData = array_merge($this->tplData, $_arr_tpl);

		$this->obj_tpl->tplDisplay("admin_list.tpl", $_arr_tplData);
		return array(
			"alert" => "y020302",
		);
	}
}