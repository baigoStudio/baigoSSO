<?php
/*-----------------------------------------------------------------
！！！！警告！！！！
以下为系统文件，请勿修改
-----------------------------------------------------------------*/

//不能非法包含或直接执行
if (!defined("IN_BAIGO")) {
    exit("Access Denied");
}

/*-------------日志模型-------------*/
class MODEL_LOG {
    public $logStatus   = array(); //状态
    public $logTypes    = array(); //类型
    public $logTargets  = array(); //目标

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
        foreach ($this->logStatus as $_key=>$_value) {
            $_arr_status[] = $_key;
        }
        $_str_status = implode("','", $_arr_status);

        foreach ($this->logTypes as $_key=>$_value) {
            $_arr_types[] = $_key;
        }
        $_str_types = implode("','", $_arr_types);

        foreach ($this->logTargets as $_key=>$_value) {
            $_arr_targets[] = $_key;
        }
        $_str_targets = implode("','", $_arr_targets);

        $_arr_logCreate = array(
            "log_id"             => "int NOT NULL AUTO_INCREMENT COMMENT 'ID'",
            "log_time"           => "int NOT NULL COMMENT '时间'",
            "log_operator_id"    => "smallint NOT NULL COMMENT '操作者 ID'",
            "log_targets"        => "text NOT NULL COMMENT '目标 JSON'",
            "log_target_type"    => "enum('" . $_str_targets . "') NOT NULL COMMENT '目标类型'",
            "log_title"          => "varchar(1000) NOT NULL COMMENT '操作标题'",
            "log_result"         => "varchar(1000) NOT NULL COMMENT '操作结果'",
            "log_type"           => "enum('" . $_str_types . "') NOT NULL COMMENT '日志类型'",
            "log_status"         => "enum('" . $_str_status . "') NOT NULL COMMENT '状态'",
            "log_level"          => "varchar(30) NOT NULL COMMENT '日志级别'",
        );

        $_num_mysql = $this->obj_db->create_table(BG_DB_TABLE . "log", $_arr_logCreate, "log_id", "应用");

        if ($_num_mysql > 0) {
            $_str_rcode = "y060105"; //更新成功
        } else {
            $_str_rcode = "x060105"; //更新成功
        }

        return array(
            "rcode" => $_str_rcode, //更新成功
        );
    }


    /** 列出字段
     * mdl_column function.
     *
     * @access public
     * @return void
     */
    function mdl_column() {
        $_arr_colRows = $this->obj_db->show_columns(BG_DB_TABLE . "log");

        $_arr_col = array();

        if (!fn_isEmpty($_arr_colRows)) {
            foreach ($_arr_colRows as $_key=>$_value) {
                $_arr_col[] = $_value["Field"];
            }
        }

        return $_arr_col;
    }


    /** 修改表
     * mdl_alter_table function.
     *
     * @access public
     * @return void
     */
    function mdl_alter_table() {
        foreach ($this->logStatus as $_key=>$_value) {
            $_arr_status[] = $_key;
        }
        $_str_status = implode("','", $_arr_status);

        foreach ($this->logTypes as $_key=>$_value) {
            $_arr_types[] = $_key;
        }
        $_str_types = implode("','", $_arr_types);

        foreach ($this->logTargets as $_key=>$_value) {
            $_arr_targets[] = $_key;
        }
        $_str_targets = implode("','", $_arr_targets);

        $_arr_col     = $this->mdl_column();
        $_arr_alter   = array();

        if (in_array("log_operator_id", $_arr_col)) {
            $_arr_alter["log_operator_id"] = array("CHANGE", "smallint NOT NULL COMMENT '操作者 ID'", "log_operator_id");
        }

        if (in_array("log_target_type", $_arr_col)) {
            $_arr_alter["log_target_type"] = array("CHANGE", "enum('" . $_str_targets . "') NOT NULL COMMENT '目标类型'", "log_target_type");
        }

        if (in_array("log_type", $_arr_col)) {
            $_arr_alter["log_type"] = array("CHANGE", "enum('" . $_str_types . "') NOT NULL COMMENT '目标类型'", "log_type");
        }

        if (in_array("log_status", $_arr_col)) {
            $_arr_alter["log_status"] = array("CHANGE", "enum('" . $_str_status . "')", "log_status");
        }

        $_str_rcode = "y060111";

        if (!fn_isEmpty($_arr_alter)) {
            $_reselt = $this->obj_db->alter_table(BG_DB_TABLE . "log", $_arr_alter);

            if (!fn_isEmpty($_reselt)) {
                $_str_rcode = "y060106";

                $_arr_logData = array(
                    "log_target_type" => $_arr_targets[0],
                );
                $this->obj_db->update(BG_DB_TABLE . "log", $_arr_logData, "LENGTH(`log_target_type`) < 1"); //更新数据

                $_arr_logData = array(
                    "log_type" => $_arr_types[0],
                );
                $this->obj_db->update(BG_DB_TABLE . "log", $_arr_logData, "LENGTH(`log_type`) < 1"); //更新数据

                $_arr_logData = array(
                    "log_status" => $_arr_status[0],
                );
                $this->obj_db->update(BG_DB_TABLE . "log", $_arr_logData, "LENGTH(`log_status`) < 1"); //更新数据
            }
        }

        return array(
            "rcode" => $_str_rcode,
        );
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
    function mdl_submit($arr_logData, $num_operatorId = 0, $str_logStatus = "wait", $str_logLevel = "normal") {

        $_arr_logData = array(
            "log_operator_id"    => $num_operatorId,

            "log_targets"        => $arr_logData["log_targets"],
            "log_target_type"    => $arr_logData["log_target_type"],
            "log_title"          => $arr_logData["log_title"],
            "log_result"         => $arr_logData["log_result"],
            "log_type"           => $arr_logData["log_type"],

            "log_status"         => $str_logStatus,
            "log_level"          => $str_logLevel,
            "log_time"           => time(),
        );

        $_num_logId = $this->obj_db->insert(BG_DB_TABLE . "log", $_arr_logData); //更新数据
        if ($_num_logId > 0) {
            $_str_rcode = "y060101"; //更新成功
        } else {
            return array(
                "rcode" => "x060101", //更新失败
            );
        }

        return array(
            "log_id"    => $_num_logId,
            "rcode"     => $_str_rcode, //成功
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

        $_num_mysql = $this->obj_db->update(BG_DB_TABLE . "log", $_arr_logUpdate, "`log_id` IN (" . $_str_logId . ")"); //删除数据

        //如影响行数大于0则返回成功
        if ($_num_mysql > 0) {
            $_str_rcode = "y060103"; //成功
        } else {
            $_str_rcode = "x060103"; //失败
        }

        return array(
            "rcode" => $_str_rcode,
        );
    }


    function mdl_isRead($num_logId) {
        $_arr_logUpdate = array(
            "log_status" => "read",
        );

        $_num_mysql = $this->obj_db->update(BG_DB_TABLE . "log", $_arr_logUpdate, "`log_id`=" . $num_logId); //删除数据

        //如影响行数大于0则返回成功
        if ($_num_mysql > 0) {
            $_str_rcode = "y060103"; //成功
        } else {
            $_str_rcode = "x060103"; //失败
        }

        return array(
            "rcode" => $_str_rcode,
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
                "rcode" => "x060102", //不存在记录
            );
        }

        /*if (isset($_arr_logRow["log_result"])) {
            $_arr_logRow["log_result"]    = json_decode($_arr_logRow["log_result"], true);
        }*/

        if (isset($_arr_logRow["log_targets"])) {
            $_arr_logRow["log_targets"]   = fn_jsonDecode($_arr_logRow["log_targets"], "no");
        } else {
            $_arr_logRow["log_targets"]   = array();
        }

        //$_arr_logRow["log_result"] = fn_htmlcode($_arr_logRow["log_result"], "encode");

        $_arr_logRow["rcode"]     = "y060102";

        return $_arr_logRow;
    }


    /** 列出
     * mdl_list function.
     *
     * @access public
     * @param mixed $num_no
     * @param int $num_except (default: 0)
     * @param array $arr_search (default: array())
     * @return void
     */
    function mdl_list($num_no, $num_except = 0, $arr_search = array()) {
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

        $_str_sqlWhere = $this->sql_process($arr_search);

        $_arr_order = array(
            array("log_id", "DESC"),
        );

        $_arr_logRows = $this->obj_db->select(BG_DB_TABLE . "log", $_arr_logSelect, $_str_sqlWhere, "", $_arr_order, $num_no, $num_except); //查询数据

        return $_arr_logRows;
    }


    /** 计数
     * mdl_count function.
     *
     * @access public
     * @param array $arr_search (default: array())
     * @return void
     */
    function mdl_count($arr_search = array()) {
        $_str_sqlWhere = $this->sql_process($arr_search);

        $_num_logCount = $this->obj_db->count(BG_DB_TABLE . "log", $_str_sqlWhere); //查询数据

        return $_num_logCount;
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
            $_str_rcode = "y060104"; //成功
        } else {
            $_str_rcode = "x060104"; //失败
        }

        return array(
            "rcode" => $_str_rcode,
        );
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
                "rcode" => "x030206",
            );
        }

        $_arr_logIds = fn_post("log_ids");

        if (fn_isEmpty($_arr_logIds)) {
            $_str_rcode = "x030202";
        } else {
            foreach ($_arr_logIds as $_key=>$_value) {
                $_arr_logIds[$_key] = fn_getSafe($_value, "int", 0);
            }
            $_str_rcode = "ok";
        }

        $this->logIds = array(
            "rcode"     => $_str_rcode,
            "log_ids"   => array_unique($_arr_logIds),
        );

        return $this->logIds;
    }


    /** 列出及统计 SQL 处理
     * sql_process function.
     *
     * @access private
     * @param array $arr_search (default: array())
     * @return void
     */
    private function sql_process($arr_search = array()) {
        $_str_sqlWhere = "1=1";

        if (isset($arr_search["key"]) && !fn_isEmpty($arr_search["key"])) {
            $_str_sqlWhere .= " AND (`log_target_type` LIKE '%" . $arr_search["key"] . "%' OR `log_result` LIKE '%" . $arr_search["key"] . "%')";
        }

        if (isset($arr_search["type"]) && !fn_isEmpty($arr_search["type"])) {
            $_str_sqlWhere .= " AND `log_type`='" . $arr_search["type"] . "'";
        }

        if (isset($arr_search["status"]) && !fn_isEmpty($arr_search["status"])) {
            $_str_sqlWhere .= " AND `log_status`='" . $arr_search["status"] . "'";
        }

        if (isset($arr_search["level"]) && !fn_isEmpty($arr_search["level"])) {
            $_str_sqlWhere .= " AND `log_level`='" . $arr_search["level"] . "'";
        }

        if (isset($arr_search["operator_id"]) && $arr_search["operator_id"] > 0) {
            $_str_sqlWhere .= " AND `log_operator_id`=" . $arr_search["operator_id"];
        }

        return $_str_sqlWhere;
    }
}
