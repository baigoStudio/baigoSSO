<?php
/*-----------------------------------------------------------------
！！！！警告！！！！
以下为系统文件，请勿修改
-----------------------------------------------------------------*/

//不能非法包含或直接执行
if(!defined("IN_BAIGO")) {
	exit("Access Denied");
}

/*-------------应用归属-------------*/
class MODEL_APP_BELONG {

	private $obj_db;

	function __construct() { //构造函数
		$this->obj_db = $GLOBALS["obj_db"]; //设置数据库对象
	}


	/** 创建表
	 * mdl_create function.
	 *
	 * @access public
	 * @return void
	 */
	function mdl_create_table() {
		$_arr_belongCreat = array(
			"belong_id"      => "int NOT NULL AUTO_INCREMENT COMMENT 'ID'",
			"belong_app_id"  => "smallint NOT NULL COMMENT '应用 ID'",
			"belong_user_id" => "int NOT NULL COMMENT '用户 ID'",
		);

		$_num_mysql = $this->obj_db->create_table(BG_DB_TABLE . "app_belong", $_arr_belongCreat, "belong_id", "应用从属");

		if ($_num_mysql > 0) {
			$_str_alert = "y070105"; //更新成功
		} else {
			$_str_alert = "x070105"; //更新成功
		}

		return array(
			"alert" => $_str_alert, //更新成功
		);
	}

	/** 检查字段
	 * mdl_column function.
	 *
	 * @access public
	 * @return void
	 */
	function mdl_column() {
		$_arr_colRows = $this->obj_db->show_columns(BG_DB_TABLE . "app_belong");

		foreach ($_arr_colRows as $_key=>$_value) {
			$_arr_col[] = $_value["Field"];
		}

		return $_arr_col;
	}


	/** 提交
	 * mdl_submit function.
	 *
	 * @access public
	 * @param mixed $num_userId
	 * @param mixed $num_appId
	 * @return void
	 */
	function mdl_submit($num_userId, $num_appId) {

		$_arr_belongData = array(
			"belong_user_id" => $num_userId,
			"belong_app_id"  => $num_appId,
		);

		$_arr_belongRow = $this->mdl_read($num_userId, $num_appId);

		if ($_arr_belongRow["alert"] == "x070102" && $num_userId > 0 && $num_appId > 0) { //插入
			$_num_belongId = $this->obj_db->insert(BG_DB_TABLE . "app_belong", $_arr_belongData);

			if ($_num_belongId > 0) { //数据库插入是否成功
				$_str_alert = "y070101";
			} else {
				return array(
					"alert" => "x070101",
				);
				exit;
			}
		} else {
			return array(
				"alert" => "x070101",
			);
			exit;
		}

		return array(
			"alert"  => $_str_alert,
		);
	}


	/** 读取
	 * mdl_read function.
	 *
	 * @access public
	 * @param int $num_userId (default: 0)
	 * @param int $num_appId (default: 0)
	 * @return void
	 */
	function mdl_read($num_userId = 0, $num_appId = 0) {
		$_arr_belongSelect = array(
			"belong_user_id",
			"belong_app_id",
		);

		$_str_sqlWhere = "1=1";

		if ($num_userId > 0) {
			$_str_sqlWhere .= " AND belong_user_id=" . $num_userId;
		}

		if ($num_appId > 0) {
			$_str_sqlWhere .= " AND belong_app_id=" . $num_appId;
		}

		$_arr_belongRows  = $this->obj_db->select(BG_DB_TABLE . "app_belong",  $_arr_belongSelect, $_str_sqlWhere, "", "", 1, 0); //检查本地表是否存在记录

		if (isset($_arr_belongRows[0])) {
			$_arr_belongRow   = $_arr_belongRows[0];
		} else {
			return array(
				"alert" => "x070102", //不存在记录
			);
			exit;
		}

		$_arr_belongRow["alert"] = "y070102";

		return $_arr_belongRow;
	}


	/** 列出
	 * mdl_list function.
	 *
	 * @access public
	 * @param mixed $num_belongNo
	 * @param int $num_belongExcept (default: 0)
	 * @param int $num_appId (default: 0)
	 * @param int $num_userId (default: 0)
	 * @param bool $arr_userIds (default: false)
	 * @return void
	 */
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

		$_arr_belongRows = $this->obj_db->select(BG_DB_TABLE . "app_belong", $_arr_belongSelect, $_str_sqlWhere, "", "belong_id DESC", $num_belongNo, $num_belongExcept);

		return $_arr_belongRows;
	}


	/** 计数
	 * mdl_count function.
	 *
	 * @access public
	 * @param int $num_appId (default: 0)
	 * @param int $num_userId (default: 0)
	 * @param bool $arr_userIds (default: false)
	 * @return void
	 */
	function mdl_count($num_appId = 0, $num_userId = 0, $arr_userIds = false) {

		$_str_sqlWhere = "1=1";

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


	/** 删除
	 * mdl_del function.
	 *
	 * @access public
	 * @param int $num_appId (default: 0)
	 * @param int $num_userId (default: 0)
	 * @param bool $arr_appIds (default: false)
	 * @param bool $arr_userIds (default: false)
	 * @param bool $arr_notAppIds (default: false)
	 * @param bool $arr_notUserIds (default: false)
	 * @return void
	 */
	function mdl_del($num_appId = 0, $num_userId = 0, $arr_appIds = false, $arr_userIds = false, $arr_notAppIds = false, $arr_notUserIds = false) {

		$_str_sqlWhere = "1=1";

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
			"alert" => $_str_alert,
		); //成功
	}
}
