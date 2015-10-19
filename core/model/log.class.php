<?php
/*-----------------------------------------------------------------
！！！！警告！！！！
以下为系统文件，请勿修改
-----------------------------------------------------------------*/

//不能非法包含或直接执行
if(!defined("IN_BAIGO")) {
	exit("Access Denied");
}

/*-------------日志模型-------------*/
class MODEL_LOG {
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
		$_arr_logCreate = array(
			"log_id"             => "int NOT NULL AUTO_INCREMENT COMMENT 'ID'",
			"log_time"           => "int NOT NULL COMMENT '时间'",
			"log_operator_id"    => "smallint NOT NULL COMMENT '操作者 ID'",
			"log_targets"        => "text NOT NULL COMMENT '目标 JSON'",
			"log_target_type"    => "enum('admin','app','user','log','opt') NOT NULL COMMENT '目标类型'",
			"log_title"          => "varchar(1000) NOT NULL COMMENT '操作标题'",
			"log_result"         => "varchar(1000) NOT NULL COMMENT '操作结果'",
			"log_type"           => "enum('admin','app','system') NOT NULL COMMENT '日志类型'",
			"log_status"         => "enum('wait','read') NOT NULL COMMENT '状态'",
			"log_level"          => "varchar(30) NOT NULL COMMENT '日志级别'",
		);

		$_num_mysql = $this->obj_db->create_table(BG_DB_TABLE . "log", $_arr_logCreate, "log_id", "应用");

		if ($_num_mysql > 0) {
			$_str_alert = "y060105"; //更新成功
		} else {
			$_str_alert = "x060105"; //更新成功
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
		$_arr_colRows = $this->obj_db->show_columns(BG_DB_TABLE . "log");

		foreach ($_arr_colRows as $_key=>$_value) {
			$_arr_col[] = $_value["Field"];
		}

		return $_arr_col;
	}


	/** 提交
	 * mdl_submit function.
	 *
	 * @access public
	 * @param mixed $str_targets
	 * @param mixed $str_targetType
	 * @param mixed $str_logTitle
	 * @param mixed $str_logResult
	 * @param mixed $str_logType
	 * @param int $num_operatorId (default: 0)
	 * @param string $str_logStatus (default: "wait")
	 * @param string $str_logLevel (default: "normal")
	 * @return void
	 */
	function mdl_submit($str_targets, $str_targetType, $str_logTitle, $str_logResult, $str_logType, $num_operatorId = 0, $str_logStatus = "wait", $str_logLevel = "normal") {
		$_arr_logData = array(
			"log_operator_id"    => $num_operatorId,
			"log_targets"        => $str_targets,
			"log_target_type"    => $str_targetType,
			"log_title"          => $str_logTitle,
			"log_result"         => $str_logResult,
			"log_type"           => $str_logType,
			"log_status"         => $str_logStatus,
			"log_level"          => $str_logLevel,
			"log_time"           => time(),
		);

		$_num_logId = $this->obj_db->insert(BG_DB_TABLE . "log", $_arr_logData); //更新数据
		if ($_num_logId > 0) {
			$_str_alert = "y060101"; //更新成功
		} else {
			return array(
				"alert" => "x060101", //更新失败
			);
			exit;

		}

		return array(
			"log_id"     => $_num_logId,
			"alert"  => $_str_alert, //成功
		);
	}


	/** 更新状态
	 * mdl_status function.
	 *
	 * @access public
	 * @param mixed $str_status
	 * @return void
	 */
	function mdl_status($str_status) {
		$_str_logId = implode(",", $this->logIds["log_ids"]);

		$_arr_logUpdate = array(
			"log_status" => $str_status,
		);

		$_num_mysql = $this->obj_db->update(BG_DB_TABLE . "log", $_arr_logUpdate, "log_id IN (" . $_str_logId . ")"); //删除数据

		//如影响行数大于0则返回成功
		if ($_num_mysql > 0) {
			$_str_alert = "y060103"; //成功
		} else {
			$_str_alert = "x060103"; //失败
		}

		return array(
			"alert" => $_str_alert,
		);
	}


	/** 读取
	 * mdl_read function.
	 *
	 * @access public
	 * @param mixed $num_logId
	 * @return void
	 */
	function mdl_read($num_logId) {
		$_arr_logSelect = array(
			"log_id",
			"log_time",
			"log_operator_id",
			"log_targets",
			"log_target_type",
			"log_title",
			"log_result",
			"log_type",
			"log_status",
			"log_level",
		);

		$_str_sqlWhere = "log_id=" . $num_logId;

		$_arr_logRows = $this->obj_db->select(BG_DB_TABLE . "log", $_arr_logSelect, $_str_sqlWhere, "", "", 1, 0); //检查本地表是否存在记录

		if (isset($_arr_logRows[0])) { //用户名不存在则返回错误
			$_arr_logRow = $_arr_logRows[0];
		} else {
			return array(
				"alert" => "x060102", //不存在记录
			);
			exit;
		}

		/*if (isset($_arr_logRow["log_result"])) {
			$_arr_logRow["log_result"]    = json_decode($_arr_logRow["log_result"], true);
		}*/

		if (isset($_arr_logRow["log_targets"])) {
			$_arr_logRow["log_targets"]   = fn_jsonDecode($_arr_logRow["log_targets"], "no");
		} else {
			$_arr_logRow["log_targets"]   = array();
		}

		$_arr_logRow["alert"]     = "y060102";

		return $_arr_logRow;
	}


	/** 列出
	 * mdl_list function.
	 *
	 * @access public
	 * @param mixed $num_no
	 * @param int $num_except (default: 0)
	 * @param string $str_key (default: "")
	 * @param string $str_type (default: "")
	 * @param string $str_status (default: "")
	 * @param string $str_level (default: "")
	 * @param int $num_operatorId (default: 0)
	 * @return void
	 */
	function mdl_list($num_no, $num_except = 0, $str_key = "", $str_type = "", $str_status = "", $str_level = "", $num_operatorId = 0) {
		$_arr_logSelect = array(
			"log_id",
			"log_time",
			"log_target_type",
			"log_title",
			"log_type",
			"log_status",
			"log_result",
			"log_operator_id",
		);

		$_str_sqlWhere = "1=1";

		if ($str_key) {
			$_str_sqlWhere .= " AND (log_title LIKE '%" . $str_key . "%')";
		}

		if ($str_type) {
			$_str_sqlWhere .= " AND log_type='" . $str_type . "'";
		}

		if ($str_status) {
			$_str_sqlWhere .= " AND log_status='" . $str_status . "'";
		}

		if ($str_level) {
			$_str_sqlWhere .= " AND log_level='" . $str_level . "'";
		}

		if ($num_operatorId > 0) {
			$_str_sqlWhere .= " AND log_operator_id=" . $num_operatorId;
		}

		$_arr_logRows = $this->obj_db->select(BG_DB_TABLE . "log", $_arr_logSelect, $_str_sqlWhere, "", "log_id DESC", $num_no, $num_except); //查询数据

		return $_arr_logRows;
	}


	/** 删除
	 * mdl_del function.
	 *
	 * @access public
	 * @return void
	 */
	function mdl_del() {
		$_str_logId = implode(",", $this->logIds["log_ids"]);

		$_num_mysql = $this->obj_db->delete(BG_DB_TABLE . "log", "log_id IN (" . $_str_logId . ")"); //删除数据

		//如车影响行数小于0则返回错误
		if ($_num_mysql > 0) {
			$_str_alert = "y060104"; //成功
		} else {
			$_str_alert = "x060104"; //失败
		}

		return array(
			"alert" => $_str_alert,
		);
	}


	/*============统计管理员============
	返回数量
	*/
	function mdl_count($str_key = "", $str_type = "", $str_status = "", $str_level = "", $num_operatorId = 0) {
		$_str_sqlWhere = "1=1";

		if ($str_key) {
			$_str_sqlWhere .= " AND (log_target_type LIKE '%" . $str_key . "%' OR log_result LIKE '%" . $str_key . "%')";
		}

		if ($str_type) {
			$_str_sqlWhere .= " AND log_type='" . $str_type . "'";
		}

		if ($str_status) {
			$_str_sqlWhere .= " AND log_status='" . $str_status . "'";
		}

		if ($str_level) {
			$_str_sqlWhere .= " AND log_level='" . $str_level . "'";
		}

		if ($num_operatorId > 0) {
			$_str_sqlWhere .= " AND log_operator_id=" . $num_operatorId;
		}

		$_num_logCount = $this->obj_db->count(BG_DB_TABLE . "log", $_str_sqlWhere); //查询数据

		return $_num_logCount;
	}


	/** 选择
	 * input_ids function.
	 *
	 * @access public
	 * @return void
	 */
	function input_ids() {
		if (!fn_token("chk")) { //令牌
			return array(
				"alert" => "x030102",
			);
			exit;
		}

		$_arr_logIds = fn_post("log_id");

		if ($_arr_logIds) {
			foreach ($_arr_logIds as $_key=>$_value) {
				$_arr_logIds[$_key] = fn_getSafe($_value, "int", 0);
			}
			$_str_alert = "ok";
		} else {
			$_str_alert = "none";
		}

		$this->logIds = array(
			"alert"  => $_str_alert,
			"log_ids"    => $_arr_logIds
		);

		return $this->logIds;
	}

}
