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
include_once(BG_PATH_MODEL . "log.class.php"); //载入管理帐号模型
include_once(BG_PATH_MODEL . "user.class.php"); //载入文章模型类
include_once(BG_PATH_MODEL . "app.class.php"); //载入文章模型类

/*-------------管理员控制器-------------*/
class CONTROL_LOG {

	private $adminLogged;
	private $obj_base;
	private $config; //配置
	private $obj_tpl;
	private $mdl_log;
	private $tplData;

	function __construct() { //构造函数
		$this->obj_base       = $GLOBALS["obj_base"]; //获取界面类型
		$this->config         = $this->obj_base->config;
		$this->adminLogged    = $GLOBALS["adminLogged"]; //获取已登录信息
		$this->mdl_log        = new MODEL_LOG(); //设置管理员模型
		$this->mdl_admin      = new MODEL_ADMIN(); //设置管理员模型
		$this->mdl_user       = new MODEL_USER(); //设置管理员模型
		$this->mdl_app        = new MODEL_APP(); //设置管理员模型
		$this->obj_tpl        = new CLASS_TPL(BG_PATH_TPL . "admin/" . $this->config["ui"]); //初始化视图对象
		$this->tplData = array(
			"adminLogged" => $this->adminLogged
		);
	}

	/*============编辑管理员界面============
	返回提示
	*/
	function ctl_show() {
		$_num_logId = fn_getSafe(fn_get("log_id"), "int", 0);

		if ($_num_logId == 0) {
			return array(
				"alert" => "x050201",
			);
		}

		if (!isset($this->adminLogged["admin_allow"]["log"]["browse"])) {
			return array(
				"alert" => "x060301",
			);
			exit;
		}

		$_arr_logRow = $this->mdl_log->mdl_read($_num_logId);

		if ($_arr_logRow["alert"] != "y060102") {
			return $_arr_logRow;
			exit;
		}

		foreach ($_arr_logRow["log_targets"] as $_key=>$_value) {
			switch ($_arr_logRow["log_target_type"]) {
				case "admin":
					$_arr_adminRow = $this->mdl_admin->mdl_read($_value["admin_id"]);
					$_arr_logRow["log_targets"][$_key]["target_id"]   = $_value["admin_id"];
					if (isset($_arr_adminRow["admin_name"])) {
						$_arr_logRow["log_targets"][$_key]["target_name"] = $_arr_adminRow["admin_name"];
					} else {
						$_arr_logRow["log_targets"][$_key]["target_name"] = "";
					}
				break;

				case "user":
					$_arr_userRow = $this->mdl_user->mdl_read($_value["user_id"]);
					$_arr_logRow["log_targets"][$_key]["target_id"]   = $_value["user_id"];
					if (isset($_arr_userRow["user_name"])) {
						$_arr_logRow["log_targets"][$_key]["target_name"] = $_arr_userRow["user_name"];
					} else {
						$_arr_logRow["log_targets"][$_key]["target_name"] = "";
					}
				break;

				case "app":
					$_arr_appRow = $this->mdl_app->mdl_read($_value["app_id"]);
					$_arr_logRow["log_targets"][$_key]["target_id"]   = $_value["app_id"];
					if (isset($_arr_appRow["app_name"])) {
						$_arr_logRow["log_targets"][$_key]["target_name"] = $_arr_appRow["app_name"];
					} else {
						$_arr_logRow["log_targets"][$_key]["target_name"] = "";
					}
				break;

				case "log":
					$_arr_logRow["log_targets"][$_key]["target_id"]   = $_value["log_id"];
					$_arr_logRow["log_targets"][$_key]["target_name"] = "log";
				break;
			}
		}

		switch ($_arr_logRow["log_type"]) {
			case "admin":
				$_arr_adminRow = $this->mdl_admin->mdl_read($_arr_logRow["log_operator_id"]);
				$_arr_logRow["log_operator_name"] = $_arr_adminRow["admin_name"];
			break;

			case "app":
				$_arr_appRow = $this->mdl_app->mdl_read($_arr_logRow["log_operator_id"]);
				$_arr_logRow["log_operator_name"] = $_arr_appRow["app_name"];
			break;
		}

		//print_r($_arr_logRow);

		$this->tplData["logRow"] = $_arr_logRow; //管理员信息

		$this->obj_tpl->tplDisplay("log_show.tpl", $this->tplData);

		return array(
			"alert" => "y060102",
		);
	}


	/**
	 * ctl_list function.
	 *
	 * @access public
	 * @return void
	 */
	function ctl_list() {
		if (!isset($this->adminLogged["admin_allow"]["log"]["browse"])) {
			return array(
				"alert" => "x060301",
			);
			exit;
		}

		$_str_key         = fn_getSafe(fn_get("key"), "txt", "");
		$_str_type        = fn_getSafe(fn_get("type"), "txt", "");
		$_str_status      = fn_getSafe(fn_get("status"), "txt", "");
		$_str_level       = fn_getSafe(fn_get("level"), "txt", "");
		$_num_operatorId  = fn_getSafe(fn_get("operator_id"), "int", 0);

		$_arr_search = array(
			"key"            => $_str_key,
			"type"           => $_str_type,
			"status"         => $_str_status,
			"level"          => $_str_level,
			"operator_id"    => $_num_operatorId,
		);

		$_num_logCount    = $this->mdl_log->mdl_count($_str_key, $_str_type, $_str_status, $_str_level, $_num_operatorId);
		$_arr_page        = fn_page($_num_logCount); //取得分页数据
		$_str_query       = http_build_query($_arr_search);
		$_arr_logRows     = $this->mdl_log->mdl_list(BG_DEFAULT_PERPAGE, $_arr_page["except"], $_str_key, $_str_type, $_str_status, $_str_level, $_num_operatorId);

		foreach ($_arr_logRows as $_key=>$_value) {
			switch ($_value["log_type"]) {
				case "admin":
					$_arr_adminRow = $this->mdl_admin->mdl_read($_value["log_operator_id"]);
					if ($_arr_adminRow["alert"] == "y020102") {
						$_arr_logRows[$_key]["log_operator_name"] = $_arr_adminRow["admin_name"];
					}
				break;
				case "app":
					$_arr_appRow = $this->mdl_app->mdl_read($_value["log_operator_id"]);
					if ($_arr_adminRow["alert"] == "y050102") {
						$_arr_logRows[$_key]["log_operator_name"] = $_arr_appRow["app_name"];
					}
				break;
			}
		}

		$_arr_tpl = array(
			"query"      => $_str_query,
			"pageRow"    => $_arr_page,
			"search"     => $_arr_search,
			"logRows"    => $_arr_logRows,
		);

		$_arr_tplData = array_merge($this->tplData, $_arr_tpl);

		$this->obj_tpl->tplDisplay("log_list.tpl", $_arr_tplData);
		return array(
			"alert" => "y060302",
		);
	}
}
