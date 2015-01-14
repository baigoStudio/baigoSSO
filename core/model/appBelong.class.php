<?php
/*-----------------------------------------------------------------
！！！！警告！！！！
以下为系统文件，请勿修改
-----------------------------------------------------------------*/

//不能非法包含或直接执行
if(!defined("IN_BAIGO")) {
	exit("Access Denied");
}

/*-------------用户类-------------*/
class MODEL_APP_BELONG {

	private $obj_db;

	function __construct() { //构造函数
		$this->obj_db = $GLOBALS["obj_db"]; //设置数据库对象
	}

	function mdl_create_view() {
		$_arr_userCreat = array(
			"user_id"            => BG_DB_TABLE . "user",
			"user_name"          => BG_DB_TABLE . "user",
			"user_mail"          => BG_DB_TABLE . "user",
			"user_nick"          => BG_DB_TABLE . "user",
			"user_note"          => BG_DB_TABLE . "user",
			"user_status"        => BG_DB_TABLE . "user",
			"user_time"          => BG_DB_TABLE . "user",
			"user_time_login"    => BG_DB_TABLE . "user",
			"user_ip"            => BG_DB_TABLE . "user",
			"belong_app_id"      => BG_DB_TABLE . "app_belong",
		);

		$_str_sqlJoin = "LEFT JOIN `" . BG_DB_TABLE . "user` ON (`" . BG_DB_TABLE . "app_belong`.`belong_user_id`=`" . BG_DB_TABLE . "user`.`user_id`)";

		$_num_mysql = $this->obj_db->create_view(BG_DB_TABLE . "user_view", $_arr_userCreat, BG_DB_TABLE . "app_belong", $_str_sqlJoin);

		if ($_num_mysql > 0) {
			$_str_alert = "y070108"; //更新成功
		} else {
			$_str_alert = "x070108"; //更新成功
		}

		return array(
			"str_alert" => $_str_alert, //更新成功
		);
	}


	function mdl_create() {
		$_arr_belongCreat = array(
			"belong_id"          => "int(11) NOT NULL AUTO_INCREMENT COMMENT 'ID'",
			"belong_app_id"     => "int(11) NOT NULL COMMENT '应用 ID'",
			"belong_user_id"  => "int(11) NOT NULL COMMENT '用户 ID'",
		);

		$_num_mysql = $this->obj_db->create_table(BG_DB_TABLE . "app_belong", $_arr_belongCreat, "belong_id", "应用从属");

		if ($_num_mysql > 0) {
			$_str_alert = "y070105"; //更新成功
		} else {
			$_str_alert = "x070105"; //更新成功
		}

		return array(
			"str_alert" => $_str_alert, //更新成功
		);
	}


	function mdl_column() {
		$_arr_colSelect = array(
			"column_name"
		);

		$_str_sqlWhere = "table_schema='" . BG_DB_NAME . "' AND table_name='" . BG_DB_TABLE . "app_belong'";

		$_arr_colRows = $this->obj_db->select_array("information_schema`.`columns", $_arr_colSelect, $_str_sqlWhere, 100, 0);

		foreach ($_arr_colRows as $_key=>$_value) {
			$_arr_col[] = $_value["column_name"];
		}

		return $_arr_col;
	}


	/**
	 * mdl_submit function.
	 *
	 * @access public
	 * @param mixed $num_belongId
	 * @param mixed $num_appId
	 * @param mixed $num_belongId
	 * @return void
	 */
	function mdl_submit($num_userId, $num_appId) {

		$_arr_belongData = array(
			"belong_user_id" => $num_userId,
			"belong_app_id"  => $num_appId,
		);

		$_arr_belongRow = $this->mdl_read($num_userId, $num_appId);

		if ($_arr_belongRow["str_alert"] == "x070102" && $num_userId > 0 && $num_appId > 0) { //插入
			$_num_belongId = $this->obj_db->insert(BG_DB_TABLE . "app_belong", $_arr_belongData);

			if ($_num_belongId > 0) { //数据库插入是否成功
				$_str_alert = "y070101";
			} else {
				return array(
					"str_alert" => "x070101",
				);
				exit;
			}
		} else {
			return array(
				"str_alert" => "x070101",
			);
			exit;
		}

		return array(
			"str_alert"  => $_str_alert,
		);
	}


	/**
	 * mdl_read function.
	 *
	 * @access public
	 * @param mixed $str_belong
	 * @param string $str_readBy (default: "belong_id")
	 * @param int $num_notThisId (default: 0)
	 * @param int $num_parentId (default: 0)
	 * @return void
	 */
	function mdl_read($num_userId = 0, $num_appId = 0) {
		$_arr_belongSelect = array(
			"belong_user_id",
			"belong_app_id",
		);

		$_str_sqlWhere = "belong_id>0";

		if ($num_userId > 0) {
			$_str_sqlWhere .= " AND belong_user_id=" . $num_userId;
		}

		if ($num_appId > 0) {
			$_str_sqlWhere .= " AND belong_app_id=" . $num_appId;
		}

		$_arr_belongRows  = $this->obj_db->select_array(BG_DB_TABLE . "app_belong",  $_arr_belongSelect, $_str_sqlWhere, 1, 0); //检查本地表是否存在记录

		if (isset($_arr_belongRows[0])) {
			$_arr_belongRow   = $_arr_belongRows[0];
		} else {
			return array(
				"str_alert" => "x070102", //不存在记录
			);
			exit;
		}

		$_arr_belongRow["str_alert"] = "y070102";

		return $_arr_belongRow;
	}


	function mdl_list($num_belongNo, $num_belongExcept = 0, $num_appId = 0, $num_userId = 0, $arr_userIds = false) {
		$_arr_belongSelect = array(
			"belong_app_id",
			"belong_user_id",
		);

		$_str_sqlWhere = "belong_id>0";

		if ($num_appId > 0) {
			$_str_sqlWhere .= " AND belong_app_id=" . $num_appId;
		}

		if ($num_userId > 0) {
			$_str_sqlWhere .= " AND belong_user_id=" . $num_userId;
		}

		if ($arr_userIds) {
			$_str_userIds = implode(",", $arr_userIds);
			$_str_sqlWhere  .= " AND belong_user_id IN (" . $_str_userIds . ")";
		}

		$_arr_belongRows = $this->obj_db->select_array(BG_DB_TABLE . "app_belong", $_arr_belongSelect, $_str_sqlWhere . " ORDER BY belong_id DESC", $num_belongNo, $num_belongExcept);

		return $_arr_belongRows;
	}


	/**
	 * mdl_count function.
	 *
	 * @access public
	 * @param int $num_appId (default: 0)
	 * @param int $num_userId (default: 0)
	 * @return void
	 */
	function mdl_count($num_appId = 0, $num_userId = 0, $arr_userIds = false) {

		$_str_sqlWhere = "belong_id > 0";

		if ($num_appId > 0) {
			$_str_sqlWhere .= " AND belong_app_id=" . $num_appId;
		}

		if ($num_userId > 0) {
			$_str_sqlWhere .= " AND belong_user_id=" . $num_userId;
		}

		if ($arr_userIds) {
			$_str_userIds = implode(",", $arr_userIds);
			$_str_sqlWhere  .= " AND belong_user_id IN (" . $_str_userIds . ")";
		}

		$_num_belongCount = $this->obj_db->count(BG_DB_TABLE . "app_belong", $_str_sqlWhere); //查询数据

		/*print_r($_arr_userRow);
		exit;*/

		return $_num_belongCount;
	}


	/**
	 * mdl_del function.
	 *
	 * @access public
	 * @param int $num_appId (default: 0)
	 * @param int $num_userId (default: 0)
	 * @return void
	 */
	function mdl_del($num_appId = 0, $num_userId = 0, $arr_appIds = false, $arr_userIds = false, $arr_notAppIds = false, $arr_notUserIds = false) {

		$_str_sqlWhere = "belong_id > 0";

		if ($num_appId > 0) {
			$_str_sqlWhere .= " AND belong_app_id=" . $num_appId;
		}

		if ($num_userId > 0) {
			$_str_sqlWhere .= " AND belong_user_id=" . $num_userId;
		}

		if ($arr_appIds) {
			$_str_appIds    = implode(",", $arr_appIds);
			$_str_sqlWhere  .= " AND belong_app_id IN (" . $_str_appIds . ")";
		}

		if ($arr_userIds) {
			$_str_userIds = implode(",", $arr_userIds);
			$_str_sqlWhere  .= " AND belong_user_id IN (" . $_str_userIds . ")";
		}

		if ($arr_notAppIds) {
			$_str_notAppIds    = implode(",", $arr_notAppIds);
			$_str_sqlWhere  .= " AND belong_app_id NOT IN (" . $_str_notAppIds . ")";
		}

		if ($arr_notUserIds) {
			$_str_notUserIds = implode(",", $arr_notUserIds);
			$_str_sqlWhere  .= " AND belong_user_id NOT IN (" . $_str_notUserIds . ")";
		}

		//print_r($_str_sqlWhere);

		$_num_mysql = $this->obj_db->delete(BG_DB_TABLE . "app_belong", $_str_sqlWhere); //删除数据

		//如车影响行数小于0则返回错误
		if ($_num_mysql > 0) {
			$_str_alert = "y070104";
		} else {
			$_str_alert = "x070104";
		}

		return array(
			"str_alert" => $_str_alert,
		); //成功
	}
}
?>