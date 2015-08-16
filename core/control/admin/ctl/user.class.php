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
include_once(BG_PATH_MODEL . "user.class.php"); //载入管理帐号模型

/*-------------管理员控制器-------------*/
class CONTROL_USER {

	private $adminLogged;
	private $obj_base;
	private $config; //配置
	private $obj_tpl;
	private $mdl_user;
	private $tplData;

	function __construct() { //构造函数
		$this->obj_base       = $GLOBALS["obj_base"]; //获取界面类型
		$this->config         = $this->obj_base->config;
		$this->adminLogged    = $GLOBALS["adminLogged"]; //获取已登录信息
		$this->mdl_user       = new MODEL_USER(); //设置管理员模型
		$this->obj_tpl        = new CLASS_TPL(BG_PATH_TPL_ADMIN . $this->config["ui"]); //初始化视图对象
		$this->tplData = array(
			"adminLogged" => $this->adminLogged
		);
	}


	/**
	 * ctl_list function.
	 *
	 * @access public
	 * @return void
	 */
	function ctl_list() {
		$_num_userId  = fn_getSafe(fn_get("user_id"), "int", 0);
		$_str_key     = fn_getSafe(fn_get("key"), "txt", "");
		$_str_status  = fn_getSafe(fn_get("status"), "txt", "");

		$_arr_search = array(
			"key"    => $_str_key,
			"status" => $_str_status,
		);

		if ($_num_userId > 0) {
			if (!isset($this->adminLogged["admin_allow"]["user"]["edit"])) {
				return array(
					"alert" => "x010303",
				);
				exit;
			}
			$_arr_userRow = $this->mdl_user->mdl_read($_num_userId);
			if ($_arr_userRow["alert"] != "y010102") {
				return $_arr_userRow;
				exit;
			}
		} else {
			if (!isset($this->adminLogged["admin_allow"]["user"]["browse"])) {
				return array(
					"alert" => "x010301",
				);
				exit;
			}
			$_arr_userRow = array(
				"user_id"       => 0,
				"user_mail"     => "",
				"user_nick"     => "",
				"user_note"     => "",
				"user_status"   => "enable",
			);
		}

		$_num_userCount   = $this->mdl_user->mdl_count($_str_key, $_str_status);
		$_arr_page        = fn_page($_num_userCount); //取得分页数据
		$_str_query       = http_build_query($_arr_search);
		$_arr_userRows    = $this->mdl_user->mdl_list(BG_DEFAULT_PERPAGE, $_arr_page["except"], $_str_key, $_str_status);

		$_arr_tpl = array(
			"query"      => $_str_query,
			"pageRow"    => $_arr_page,
			"search"     => $_arr_search,
			"userRow"    => $_arr_userRow,
			"userRows"   => $_arr_userRows,
		);

		$_arr_tplData = array_merge($this->tplData, $_arr_tpl);

		$this->obj_tpl->tplDisplay("user_list.tpl", $_arr_tplData);
		return array(
			"alert" => "y010302",
		);
	}
}
