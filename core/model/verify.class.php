<?php
/*-----------------------------------------------------------------
！！！！警告！！！！
以下为系统文件，请勿修改
-----------------------------------------------------------------*/

//不能非法包含或直接执行
if (!defined("IN_BAIGO")) {
    exit("Access Denied");
}

/*-------------验证模型-------------*/
class MODEL_VERIFY {
    private $obj_db;
    public $verifyStatus = array();

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
        foreach ($this->verifyStatus as $_key=>$_value) {
            $_arr_status[] = $_key;
        }
        $_str_status = implode("','", $_arr_status);

        $_arr_verifyCreate = array(
            "verify_id"             => "int NOT NULL AUTO_INCREMENT COMMENT 'ID'",
            "verify_user_id"        => "int NOT NULL COMMENT '用户 ID'",
            "verify_token"          => "char(32) NOT NULL COMMENT '访问口令'",
            "verify_token_expire"   => "int NOT NULL COMMENT '口令过期时间'",
            "verify_rand"           => "char(6) NOT NULL COMMENT '随机串'",
            "verify_mail"           => "varchar(300) NOT NULL COMMENT '待验证邮箱'",
            "verify_status"         => "enum('" . $_str_status . "') NOT NULL COMMENT '状态'",
            "verify_time"           => "int NOT NULL COMMENT '发起时间'",
            "verify_time_refresh"   => "int NOT NULL COMMENT '更新时间'",
            "verify_time_disable"   => "int NOT NULL COMMENT '使用时间'",
        );

        $_num_mysql = $this->obj_db->create_table(BG_DB_TABLE . "verify", $_arr_verifyCreate, "verify_id", "验证");

        if ($_num_mysql > 0) {
            $_str_alert = "y120105"; //更新成功
        } else {
            $_str_alert = "x120105"; //更新成功
        }

        return array(
            "alert" => $_str_alert, //更新成功
        );
    }


    /** 列出字段
     * mdl_column function.
     *
     * @access public
     * @return void
     */
    function mdl_column() {
        $_arr_colRows = $this->obj_db->show_columns(BG_DB_TABLE . "verify");

        foreach ($_arr_colRows as $_key=>$_value) {
            $_arr_col[] = $_value["Field"];
        }

        return $_arr_col;
    }


    /** 提交
     * mdl_submit function.
     *
     * @access public
     * @return void
     */
    function mdl_submit($num_userId, $str_mail) {
        $_arr_verifyRow = $this->mdl_read($num_userId, "verify_user_id");

        $_str_rand      = fn_rand(6);
        $_str_token     = fn_rand(32);
        $_str_tokenDo   = fn_baigoEncrypt($_str_token, $_str_rand);

        $_arr_verifyData = array(
            "verify_user_id"        => $num_userId,
            "verify_mail"           => $str_mail,
            "verify_token"          => $_str_token,
            "verify_rand"           => $_str_rand,
            "verify_token_expire"   => time() + BG_VERIFY_EXPIRE * 60,
            "verify_status"         => "enable",
            "verify_time_refresh"   => time(),
        );

        if ($_arr_verifyRow["alert"] == "x120102") {
            $_arr_verifyData["verify_time"] = time();
            $_num_verifyId = $this->obj_db->insert(BG_DB_TABLE . "verify", $_arr_verifyData); //更新数据
            if ($_num_verifyId > 0) {
                $_str_alert = "y120101"; //更新成功
            } else {
                return array(
                    "alert" => "x120101", //更新失败
                );
            }
        } else {
            $_num_verifyId  = $_arr_verifyRow["verify_id"];
            $_num_mysql     = $this->obj_db->update(BG_DB_TABLE . "verify", $_arr_verifyData, "verify_id=" . $_num_verifyId); //更新数据
            if ($_num_mysql > 0) {
                $_str_alert = "y120103"; //更新成功
            } else {
                return array(
                    "alert" => "x120103", //更新失败
                );

            }
        }

        return array(
            "verify_id"     => $_num_verifyId,
            "verify_token"  => $_str_tokenDo,
            "alert"         => $_str_alert, //成功
        );
    }


    /** 失效
     * mdl_disable function.
     *
     * @access public
     * @return void
     */
    function mdl_disable() {
        $_arr_verifyUpdate = array(
            "verify_status"         => "disable",
            "verify_time_disable"   => time(),
        );

        $_num_mysql = $this->obj_db->update(BG_DB_TABLE . "verify", $_arr_verifyUpdate, "verify_id=" . $this->verifySubmit["verify_id"]);

        //如影响行数大于0则返回成功
        if ($_num_mysql > 0) {
            $_str_alert = "y120103"; //成功
        } else {
            $_str_alert = "x120103"; //失败
        }

        return array(
            "alert" => $_str_alert,
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
        $_str_verifyId = implode(",", $this->verifyIds["verify_ids"]);

        $_arr_verifyUpdate = array(
            "verify_status" => $str_status,
        );

        $_num_mysql = $this->obj_db->update(BG_DB_TABLE . "verify", $_arr_verifyUpdate, "verify_id IN (" . $_str_verifyId . ")"); //删除数据

        //如影响行数大于0则返回成功
        if ($_num_mysql > 0) {
            $_str_alert = "y120103"; //成功
        } else {
            $_str_alert = "x120103"; //失败
        }

        return array(
            "alert" => $_str_alert,
        );
    }


    /** 读取
     * mdl_read function.
     *
     * @access public
     * @param mixed $str_verify
     * @param string $str_by (default: "verify_id")
     * @param int $num_notId (default: 0)
     * @return void
     */
    function mdl_read($str_verify, $str_by = "verify_id") {
        $_arr_verifySelect = array(
            "verify_id",
            "verify_user_id",
            "verify_token",
            "verify_token_expire",
            "verify_mail",
            "verify_status",
            "verify_rand",
            "verify_time",
            "verify_time_refresh",
            "verify_time_disable",
        );

        if (is_numeric($str_verify)) {
            $_str_sqlWhere = $str_by . "=" . $str_verify;
        } else {
            $_str_sqlWhere = $str_by . "='" . $str_verify . "'";
        }

        $_arr_verifyRows = $this->obj_db->select(BG_DB_TABLE . "verify", $_arr_verifySelect, $_str_sqlWhere, "", "", 1, 0); //检查本地表是否存在记录

        if (isset($_arr_verifyRows[0])) { //用户名不存在则返回错误
            $_arr_verifyRow = $_arr_verifyRows[0];
        } else {
            return array(
                "alert" => "x120102", //不存在记录
            );
        }

        if ($_arr_verifyRow["verify_token_expire"] < time()) {
            $_arr_verifyRow["verify_status"] = "expired";
        }

        $_arr_verifyRow["alert"] = "y120102";

        return $_arr_verifyRow;
    }


    /** 列出
     * mdl_list function.
     *
     * @access public
     * @param mixed $num_no
     * @param int $num_except (default: 0)
     * @return void
     */
    function mdl_list($num_no, $num_except = 0) {
        $_arr_verifySelect = array(
            "verify_id",
            "verify_user_id",
            "verify_token",
            "verify_token_expire",
            "verify_mail",
            "verify_status",
            "verify_time",
            "verify_time_refresh",
            "verify_time_disable",
        );

        $_arr_verifyRows = $this->obj_db->select(BG_DB_TABLE . "verify", $_arr_verifySelect, "", "", "verify_id DESC", $num_no, $num_except); //查询数据

        foreach ($_arr_verifyRows as $_key=>$_value) {
            if ($_value["verify_token_expire"] < time()) {
                $_arr_verifyRows[$_key]["verify_status"] = "expired";
            }
        }

        return $_arr_verifyRows;
    }


    /** 计数
     * mdl_count function.
     *
     * @access public
     * @return void
     */
    function mdl_count() {
        $_num_verifyCount = $this->obj_db->count(BG_DB_TABLE . "verify"); //查询数据

        return $_num_verifyCount;
    }


    /** 删除
     * mdl_del function.
     *
     * @access public
     * @return void
     */
    function mdl_del() {
        $_str_verifyId = implode(",", $this->verifyIds["verify_ids"]);

        $_num_mysql = $this->obj_db->delete(BG_DB_TABLE . "verify", "verify_id IN (" . $_str_verifyId . ")"); //删除数据

        //如车影响行数小于0则返回错误
        if ($_num_mysql > 0) {
            $_str_alert = "y120104"; //成功
        } else {
            $_str_alert = "x120104"; //失败
        }

        return array(
            "alert" => $_str_alert,
        );
    }


    /** 表单验证
     * input_verify function.
     *
     * @access public
     * @return void
     */
    function input_verify() {
        if (!fn_token("chk")) { //令牌
            return array(
                "alert" => "x030206",
            );
        }

        $_arr_verifyId = validateStr(fn_post("verify_id"), 1, 0);
        switch ($_arr_verifyId["status"]) {
            case "too_short":
                return array(
                    "alert" => "x120201",
                );
            break;

            case "ok":
                $this->verifySubmit["verify_id"] = $_arr_verifyId["str"];
            break;

        }

        $_arr_verifyToken = validateStr(fn_post("verify_token"), 1, 0);
        switch ($_arr_verifyToken["status"]) {
            case "too_short":
                return array(
                    "alert" => "x120202",
                );
            break;

            case "ok":
                $this->verifySubmit["verify_token"] = $_arr_verifyToken["str"];
            break;
        }

        $this->verifySubmit["alert"] = "ok";

        return $this->verifySubmit;
    }



    /** 选择 verify
     * input_ids function.
     *
     * @access public
     * @return void
     */
    function input_ids() {
        if (!fn_token("chk")) { //令牌
            return array(
                "alert" => "x030206",
            );
        }

        $_arr_verifyIds = fn_post("verify_ids");

        if ($_arr_verifyIds) {
            foreach ($_arr_verifyIds as $_key=>$_value) {
                $_arr_verifyIds[$_key] = fn_getSafe($_value, "int", 0);
            }
            $_str_alert = "ok";
        } else {
            $_str_alert = "x030202";
        }

        $this->verifyIds = array(
            "alert"         => $_str_alert,
            "verify_ids"    => array_unique($_arr_verifyIds),
        );

        return $this->verifyIds;
    }
}
