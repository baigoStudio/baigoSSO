<?php
/*-----------------------------------------------------------------
！！！！警告！！！！
以下为系统文件，请勿修改
-----------------------------------------------------------------*/

//不能非法包含或直接执行
if(!defined("IN_BAIGO")) {
	exit("Access Denied");
}

/*-------------应用模型-------------*/
class MODEL_APP {
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
		$_arr_appCreate = array(
			"app_id"             => "smallint NOT NULL AUTO_INCREMENT COMMENT 'ID'",
			"app_name"           => "varchar(30) NOT NULL COMMENT '应用名'",
			"app_key"            => "char(64) NOT NULL COMMENT '校验码'",
			"app_notice"         => "varchar(3000) NOT NULL COMMENT '通知接口URL'",
			"app_token"          => "char(64) NOT NULL COMMENT '访问口令'",
			"app_token_expire"   => "int NOT NULL COMMENT '口令存活期'",
			"app_token_time"     => "int NOT NULL COMMENT '上次授权时间'",
			"app_status"         => "enum('enable','disable') NOT NULL COMMENT '状态'",
			"app_note"           => "varchar(30) NOT NULL COMMENT '备注'",
			"app_time"           => "int NOT NULL COMMENT '创建时间'",
			"app_ip_allow"       => "varchar(1000) NOT NULL COMMENT '允许调用IP地址'",
			"app_ip_bad"         => "varchar(1000) NOT NULL COMMENT '禁止IP'",
			"app_sync"           => "enum('on','off') NOT NULL COMMENT '是否同步'",
			"app_allow"          => "varchar(3000) NOT NULL COMMENT '权限'",
		);

		$_num_mysql = $this->obj_db->create_table(BG_DB_TABLE . "app", $_arr_appCreate, "app_id", "应用");

		if ($_num_mysql > 0) {
			$_str_alert = "y050105"; //更新成功
		} else {
			$_str_alert = "x050105"; //更新成功
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
		$_arr_colRows = $this->obj_db->show_columns(BG_DB_TABLE . "app");

		foreach ($_arr_colRows as $_key=>$_value) {
			$_arr_col[] = $_value["Field"];
		}

		return $_arr_col;
	}


	/** 生成 token
	 * mdl_token function.
	 *
	 * @access public
	 * @param mixed $num_appId
	 * @return void
	 */
	function mdl_token($num_appId) {
		$_arr_appData = array(
			"app_token"      => fn_rand(64),
			"app_token_time" => time(),
		);

		$_num_mysql = $this->obj_db->update(BG_DB_TABLE . "app", $_arr_appData, "app_id=" . $num_appId); //更新数据

		return $_arr_appData;
	}


	/** 重置 app key
	 * mdl_reset function.
	 *
	 * @access public
	 * @param mixed $num_appId
	 * @return void
	 */
	function mdl_reset($num_appId) {
		$_arr_appData = array(
			"app_key" => fn_rand(64),
		);

		$_num_mysql = $this->obj_db->update(BG_DB_TABLE . "app", $_arr_appData, "app_id=" . $num_appId); //更新数据

		if ($_num_mysql > 0) {
			$_str_alert = "y050103"; //更新成功
		} else {
			return array(
				"alert" => "x050103", //更新失败
			);
			exit;
		}

		return array(
			"app_id"     => $num_appId,
			"alert"  => $_str_alert, //成功
		);
	}


	/** 提交
	 * mdl_submit function.
	 *
	 * @access public
	 * @return void
	 */
	function mdl_submit() {
		$_arr_appData = array(
			"app_name"       => $this->appSubmit["app_name"],
			"app_notice"     => $this->appSubmit["app_notice"],
			"app_note"       => $this->appSubmit["app_note"],
			"app_status"     => $this->appSubmit["app_status"],
			"app_ip_allow"   => $this->appSubmit["app_ip_allow"],
			"app_ip_bad"     => $this->appSubmit["app_ip_bad"],
			"app_sync"       => $this->appSubmit["app_sync"],
			"app_allow"      => $this->appSubmit["app_allow"],
		);

		if ($this->appSubmit["app_id"] == 0) {
			$_arr_insert = array(
				"app_key"           => fn_rand(64),
				"app_time"          => time(),
				"app_token"         => fn_rand(64),
				"app_token_time"    => time(),
				"app_token_expire"  => BG_DEFAULT_TOKEN,
			);
			$_arr_data = array_merge($_arr_appData, $_arr_insert);

			$_num_appId = $this->obj_db->insert(BG_DB_TABLE . "app", $_arr_data); //更新数据
			if ($_num_appId > 0) {
				$_str_alert = "y050101"; //更新成功
			} else {
				return array(
					"alert" => "x050101", //更新失败
				);
				exit;

			}
		} else {
			$_num_appId = $this->appSubmit["app_id"];
			$_num_mysql = $this->obj_db->update(BG_DB_TABLE . "app", $_arr_appData, "app_id=" . $_num_appId); //更新数据
			if ($_num_mysql > 0) {
				$_str_alert = "y050103"; //更新成功
			} else {
				return array(
					"alert" => "x050103", //更新失败
				);
				exit;

			}
		}

		return array(
			"app_id"     => $_num_appId,
			"alert"  => $_str_alert, //成功
		);
	}


	/** 更改状态
	 * mdl_status function.
	 *
	 * @access public
	 * @param mixed $str_status
	 * @return void
	 */
	function mdl_status($str_status) {
		$_str_appId = implode(",", $this->appIds["app_ids"]);

		$_arr_appUpdate = array(
			"app_status" => $str_status,
		);

		$_num_mysql = $this->obj_db->update(BG_DB_TABLE . "app", $_arr_appUpdate, "app_id IN (" . $_str_appId . ")"); //删除数据

		//如影响行数大于0则返回成功
		if ($_num_mysql > 0) {
			$_str_alert = "y050103"; //成功
		} else {
			$_str_alert = "x050103"; //失败
		}

		return array(
			"alert" => $_str_alert,
		);
	}


	/** 读取
	 * mdl_read function.
	 *
	 * @access public
	 * @param mixed $str_app
	 * @param string $str_by (default: "app_id")
	 * @param int $num_notId (default: 0)
	 * @return void
	 */
	function mdl_read($str_app, $str_by = "app_id", $num_notId = 0) {
		$_arr_appSelect = array(
			"app_id",
			"app_name",
			"app_notice",
			"app_key",
			"app_note",
			"app_token",
			"app_token_expire",
			"app_token_time",
			"app_status",
			"app_time",
			"app_ip_allow",
			"app_ip_bad",
			"app_sync",
			"app_allow",
		);

		switch ($str_by) {
			case "app_id":
				$_str_sqlWhere = "app_id=" . $str_app;
			break;
			default:
				$_str_sqlWhere = $str_by . "='" . $str_app . "'";
			break;
		}

		if ($num_notId > 0) {
			$_str_sqlWhere .= " AND app_id<>" . $num_notId;
		}

		$_arr_appRows = $this->obj_db->select(BG_DB_TABLE . "app", $_arr_appSelect, $_str_sqlWhere, "", "", 1, 0); //检查本地表是否存在记录

		if (isset($_arr_appRows[0])) { //用户名不存在则返回错误
			$_arr_appRow = $_arr_appRows[0];
		} else {
			return array(
				"alert" => "x050102", //不存在记录
			);
			exit;
		}

		if (isset($_arr_appRow["app_allow"])) {
			$_arr_appRow["app_allow"] = fn_jsonDecode($_arr_appRow["app_allow"], "no");
		} else {
			$_arr_appRow["app_allow"] = array();

		}
		$_arr_appRow["alert"] = "y050102";

		return $_arr_appRow;
	}


	/** 列出
	 * mdl_list function.
	 *
	 * @access public
	 * @param mixed $num_appNo
	 * @param int $num_appExcept (default: 0)
	 * @param string $str_key (default: "")
	 * @param string $str_status (default: "")
	 * @param string $str_sync (default: "")
	 * @param bool $str_notice (default: false)
	 * @return void
	 */
	function mdl_list($num_appNo, $num_appExcept = 0, $str_key = "", $str_status = "", $str_sync = "", $str_notice = false, $arr_notIds = false) {
		$_arr_appSelect = array(
			"app_id",
			"app_key",
			"app_name",
			"app_notice",
			"app_note",
			"app_status",
			"app_time",
		);

		$_str_sqlWhere = "1=1";

		if ($str_key) {
			$_str_sqlWhere .= " AND (app_name LIKE '%" . $str_key . "%' OR app_note LIKE '%" . $str_key . "%')";
		}

		if ($str_status) {
			$_str_sqlWhere .= " AND app_status='" . $str_status . "'";
		}

		if ($str_sync) {
			$_str_sqlWhere .= " AND app_sync='" . $str_sync . "'";
		}

		if ($str_notice) {
			$_str_sqlWhere .= " AND LENGTH(app_notice)>0";
		}

		if ($arr_notIds) {
			$_str_appIds     = implode(",", $arr_notIds);
			$_str_sqlWhere  .= " AND app_id NOT IN (" . $_str_appIds . ")";
		}

		$_arr_appRows = $this->obj_db->select(BG_DB_TABLE . "app", $_arr_appSelect, $_str_sqlWhere, "", "app_id DESC", $num_appNo, $num_appExcept); //查询数据

		return $_arr_appRows;
	}


	/** 删除
	 * mdl_del function.
	 *
	 * @access public
	 * @return void
	 */
	function mdl_del() {
		$_str_appId = implode(",", $this->appIds["app_ids"]);

		$_num_mysql = $this->obj_db->delete(BG_DB_TABLE . "app", "app_id IN (" . $_str_appId . ")"); //删除数据

		//如车影响行数小于0则返回错误
		if ($_num_mysql > 0) {
			$_str_alert = "y050104"; //成功
		} else {
			$_str_alert = "x050104"; //失败
		}

		return array(
			"alert" => $_str_alert,
		);
	}


	/** 计数
	 * mdl_count function.
	 *
	 * @access public
	 * @param string $str_key (default: "")
	 * @param string $str_status (default: "")
	 * @param string $str_sync (default: "")
	 * @param bool $str_notice (default: false)
	 * @return void
	 */
	function mdl_count($str_key = "", $str_status = "", $str_sync = "", $str_notice = false) {
		$_str_sqlWhere = "1=1";

		if ($str_key) {
			$_str_sqlWhere .= " AND (app_name LIKE '%" . $str_key . "%' OR app_note LIKE '%" . $str_key . "%')";
		}

		if ($str_status) {
			$_str_sqlWhere .= " AND app_status='" . $str_status . "'";
		}

		if ($str_sync) {
			$_str_sqlWhere .= " AND app_sync='" . $str_sync . "'";
		}

		if ($str_notice) {
			$_str_sqlWhere .= " AND LENGTH(app_notice)>0";
		}

		$_num_appCount = $this->obj_db->count(BG_DB_TABLE . "app", $_str_sqlWhere); //查询数据

		return $_num_appCount;
	}


	/** 表单验证
	 * input_submit function.
	 *
	 * @access public
	 * @return void
	 */
	function input_submit() {
		if (!fn_token("chk")) { //令牌
			return array(
				"alert" => "x030101",
			);
			exit;
		}

		$this->appSubmit["app_id"] = fn_getSafe(fn_post("app_id"), "int", 0);

		if ($this->appSubmit["app_id"] > 0) {
			//检查用户是否存在
			$_arr_appRow = $this->mdl_read($this->appSubmit["app_id"]);
			if ($_arr_appRow["alert"] != "y050102") {
				return $_arr_appRow;
				exit;
			}
		}

		$_arr_appName = validateStr(fn_post("app_name"), 1, 30);
		switch ($_arr_appName["status"]) {
			case "too_short":
				return array(
					"alert" => "x050201",
				);
				exit;
			break;

			case "too_long":
				return array(
					"alert" => "x050202",
				);
				exit;
			break;

			case "ok":
				$this->appSubmit["app_name"] = $_arr_appName["str"];
			break;

		}

		$_arr_appNotice = validateStr(fn_post("app_notice"), 1, 3000);
		switch ($_arr_appNotice["status"]) {
			case "too_short":
				return array(
					"alert" => "x050207",
				);
				exit;
			break;

			case "too_long":
				return array(
					"alert" => "x050208",
				);
				exit;
			break;

			case "format_err":
				return array(
					"alert" => "x050209",
				);
				exit;
			break;

			case "ok":
				$this->appSubmit["app_notice"] = $_arr_appNotice["str"];
			break;
		}

		$_arr_appNote = validateStr(fn_post("app_note"), 0, 30);
		switch ($_arr_appNote["status"]) {
			case "too_long":
				return array(
					"alert" => "x050205",
				);
				exit;
			break;

			case "ok":
				$this->appSubmit["app_note"] = $_arr_appNote["str"];
			break;

		}

		$_arr_appStatus = validateStr(fn_post("app_status"), 1, 0);
		switch ($_arr_appStatus["status"]) {
			case "too_short":
				return array(
					"alert" => "x050206",
				);
				exit;
			break;

			case "ok":
				$this->appSubmit["app_status"] = $_arr_appStatus["str"];
			break;
		}

		$_arr_appIpAllow = validateStr(fn_post("app_ip_allow"), 0, 3000);
		switch ($_arr_appIpAllow["status"]) {
			case "too_long":
				return array(
					"alert" => "x050210",
				);
				exit;
			break;

			case "ok":
				$this->appSubmit["app_ip_allow"] = $_arr_appIpAllow["str"];
			break;
		}

		$_arr_appIpBad = validateStr(fn_post("app_ip_bad"), 0, 3000);
		switch ($_arr_appIpBad["status"]) {
			case "too_long":
				return array(
					"alert" => "x050211",
				);
				exit;
			break;

			case "ok":
				$this->appSubmit["app_ip_bad"] = $_arr_appIpBad["str"];
			break;
		}

		$_arr_appSync = validateStr(fn_post("app_sync"), 1, 0);
		switch ($_arr_appSync["status"]) {
			case "too_short":
				return array(
					"alert" => "x050218",
				);
				exit;
			break;

			case "ok":
				$this->appSubmit["app_sync"] = $_arr_appSync["str"];
			break;
		}

		$this->appSubmit["app_allow"] = fn_jsonEncode(fn_post("app_allow"), "no");
		$this->appSubmit["alert"] = "ok";

		return $this->appSubmit;
	}


	/** 选择 app
	 * input_ids function.
	 *
	 * @access public
	 * @return void
	 */
	function input_ids() {
		if (!fn_token("chk")) { //令牌
			return array(
				"alert" => "x030101",
			);
			exit;
		}

		$_arr_appIds = fn_post("app_id");

		if ($_arr_appIds) {
			foreach ($_arr_appIds as $_key=>$_value) {
				$_arr_appIds[$_key] = fn_getSafe($_value, "int", 0);
			}
			$_str_alert = "ok";
		} else {
			$_str_alert = "none";
		}

		$this->appIds = array(
			"alert"  => $_str_alert,
			"app_ids"    => $_arr_appIds
		);

		return $this->appIds;
	}
}
