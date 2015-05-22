<?php
/*-----------------------------------------------------------------
！！！！警告！！！！
以下为系统文件，请勿修改
-----------------------------------------------------------------*/

//不能非法包含或直接执行
if(!defined("IN_BAIGO")) {
	exit("Access Denied");
}

/*-------------设置项模型-------------*/
class MODEL_OPT {
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
		$_arr_optCreate = array(
			"opt_id"     => "smallint NOT NULL AUTO_INCREMENT COMMENT 'ID'",
			"opt_key"    => "varchar(100) NOT NULL COMMENT '设置键名'",
			"opt_value"  => "varchar(1000) NOT NULL COMMENT '设置键值'",
		);

		$_num_mysql = $this->obj_db->create_table(BG_DB_TABLE . "opt", $_arr_optCreate, "opt_id", "设置项");

		if ($_num_mysql > 0) {
			$_str_alert = "y040105"; //更新成功
		} else {
			$_str_alert = "x040105"; //更新成功
		}

		return array(
			"str_alert" => $_str_alert, //更新成功
		);
	}


	/** 检查字段
	 * mdl_column function.
	 *
	 * @access public
	 * @return void
	 */
	function mdl_column() {
		$_arr_colRows = $this->obj_db->show_columns(BG_DB_TABLE . "opt");

		foreach ($_arr_colRows as $_key=>$_value) {
			$_arr_col[] = $_value["Field"];
		}

		return $_arr_col;
	}


	/** 提交
	 * mdl_submit function.
	 *
	 * @access public
	 * @param mixed $str_optKey
	 * @param mixed $str_optValue
	 * @return void
	 */
	function mdl_submit($str_optKey, $str_optValue) {
		$_arr_optData = array(
			"opt_key"    => $str_optKey,
			"opt_value"  => $str_optValue,
		);

		$_arr_optRow = $this->mdl_read($str_optKey);

		if ($_arr_optRow["str_alert"] != "y040102") {
			$_str_optKey = $this->obj_db->insert(BG_DB_TABLE . "opt", $_arr_optData); //更新数据
			if ($_str_optKey) {
				$_str_alert = "y040101"; //更新成功
			} else {
				return array(
					"str_alert" => "x040101", //更新失败
				);
				exit;

			}
		} else {
			$_str_optKey = $str_optKey;
			$_num_mysql = $this->obj_db->update(BG_DB_TABLE . "opt", $_arr_optData, "opt_key='" . $_str_optKey . "'"); //更新数据
			if ($_num_mysql > 0) {
				$_str_alert = "y040103"; //更新成功
			} else {
				return array(
					"str_alert" => "x040103", //更新失败
				);
				exit;

			}
		}

		return array(
			"opt_key"    => $_str_optKey,
			"str_alert"  => $_str_alert, //成功
		);
	}


	/** 读取
	 * mdl_read function.
	 *
	 * @access public
	 * @param mixed $str_optKey
	 * @return void
	 */
	function mdl_read($str_optKey) {
		$_arr_optSelect = array(
			"opt_key",
			"opt_value",
		);

		$_str_sqlWhere = "opt_key='" . $str_optKey . "'";

		$_arr_optRows = $this->obj_db->select(BG_DB_TABLE . "opt", $_arr_optSelect, $_str_sqlWhere, "", "", 1, 0); //检查本地表是否存在记录

		if (isset($_arr_optRows[0])) { //用户名不存在则返回错误
			$_arr_optRow = $_arr_optRows[0];
		} else {
			return array(
				"str_alert" => "x040102", //不存在记录
			);
			exit;
		}

		$_arr_optRow["str_alert"] = "y040102";

		return $_arr_optRow;

	}


	/** 删除
	 * mdl_del function.
	 *
	 * @access public
	 * @param mixed $str_optKey
	 * @return void
	 */
	function mdl_del($str_optKey) {
		$_num_mysql = $this->obj_db->delete(BG_DB_TABLE . "opt", "opt_key='" . $str_optKey . "'"); //删除数据

		//如车影响行数小于0则返回错误
		if ($_num_mysql > 0) {
			$_str_alert = "y040104"; //成功
		} else {
			$_str_alert = "x040104"; //失败
		}

		return array(
			"str_alert" => $_str_alert,
		);
	}
}
