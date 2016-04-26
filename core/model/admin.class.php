<?php
/*-----------------------------------------------------------------
！！！！警告！！！！
以下为系统文件，请勿修改
-----------------------------------------------------------------*/

//不能非法包含或直接执行
if(!defined("IN_BAIGO")) {
    exit("Access Denied");
}

/*-------------管理员模型-------------*/
class MODEL_ADMIN {
    private $obj_db;
    public $adminStatus = array(); //状态

    function __construct() { //构造函数
        $this->obj_db = $GLOBALS["obj_db"]; //设置数据库对象
    }


    /** 创建表 在安装或升级时调用
     * mdl_create function.
     *
     * @access public
     * @return void
     */
    function mdl_create_table() {
        foreach ($this->adminStatus as $_key=>$_value) {
            $_arr_status[] = $_key;
        }
        $_str_status = implode("','", $_arr_status);

        $_arr_adminCreate = array(
            "admin_id"           => "smallint NOT NULL AUTO_INCREMENT COMMENT 'ID'",
            "admin_name"         => "varchar(30) NOT NULL COMMENT '用户名'",
            "admin_pass"         => "char(32) NOT NULL COMMENT '密码'",
            "admin_rand"         => "char(6) NOT NULL COMMENT '随机串'",
            "admin_note"         => "varchar(30) NOT NULL COMMENT '备注'",
            "admin_nick"         => "varchar(30) NOT NULL COMMENT '昵称'",
            "admin_status"       => "enum('" . $_str_status . "') NOT NULL COMMENT '状态'",
            "admin_allow"        => "varchar(3000) NOT NULL COMMENT '权限'",
            "admin_time"         => "int NOT NULL COMMENT '创建时间'",
            "admin_time_login"   => "int NOT NULL COMMENT '登录时间'",
            "admin_ip"           => "varchar(15) NOT NULL COMMENT '最后 IP 地址'",
        );

        $_num_mysql = $this->obj_db->create_table(BG_DB_TABLE . "admin", $_arr_adminCreate, "admin_id", "管理员");

        if ($_num_mysql > 0) {
            $_str_alert = "y020105"; //更新成功
        } else {
            $_str_alert = "x020105"; //更新成功
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
        $_arr_colRows = $this->obj_db->show_columns(BG_DB_TABLE . "admin");

        foreach ($_arr_colRows as $_key=>$_value) {
            $_arr_col[] = $_value["Field"];
        }

        return $_arr_col;
    }


    /** 修改表 升级时调用
     * mdl_alert_table function.
     *
     * @access public
     * @return void
     */
    function mdl_alert_table() {
        foreach ($this->adminStatus as $_key=>$_value) {
            $_arr_status[] = $_key;
        }
        $_str_status = implode("','", $_arr_status);

        $_arr_col     = $this->mdl_column();
        $_arr_alert   = array();

        if (!in_array("admin_nick", $_arr_col)) {
            $_arr_alert["admin_nick"] = array("ADD", "varchar(30) NOT NULL COMMENT '昵称'");
        }

        if (in_array("admin_id", $_arr_col)) {
            $_arr_alert["admin_id"] = array("CHANGE", "smallint NOT NULL AUTO_INCREMENT COMMENT 'ID'", "admin_id");
        }

        if (in_array("admin_status", $_arr_col)) {
            $_arr_alert["admin_status"] = array("CHANGE", "enum('" . $_str_status . "') NOT NULL COMMENT '状态'", "admin_status");
        }

        $_arr_adminData = array(
            "admin_status" => $_arr_status[0],
        );
        $this->obj_db->update(BG_DB_TABLE . "admin", $_arr_adminData, "LENGTH(admin_status) < 1"); //将 admin_status 字段为空的记录，更新为默认值

        if (in_array("admin_pass", $_arr_col)) {
            $_arr_alert["admin_pass"] = array("CHANGE", "char(32) NOT NULL COMMENT '密码'", "admin_pass");
        }

        if (in_array("admin_rand", $_arr_col)) {
            $_arr_alert["admin_rand"] = array("CHANGE", "char(6) NOT NULL COMMENT '随机串'", "admin_rand");
        }

        $_str_alert = "y0201111";

        if ($_arr_alert) {
            $_reselt = $this->obj_db->alert_table(BG_DB_TABLE . "admin", $_arr_alert);

            if ($_reselt) {
                $_str_alert = "y020106";
            }
        }

        return array(
            "alert" => $_str_alert,
        );
    }


    /** 登录时更新用户信息
     * mdl_login function.
     *
     * @access public
     * @param mixed $num_adminId
     * @param mixed $str_adminPass
     * @param mixed $str_adminRand
     * @return void
     */
    function mdl_login($num_adminId, $str_adminPass, $str_adminRand) {
        $_arr_adminData = array(
            "admin_pass"         => $str_adminPass, //密码 md5 加密，加盐后再次 md5 加密，每次登录更新加盐值
            "admin_rand"         => $str_adminRand, //加盐
            "admin_time_login"   => time(),
            "admin_ip"           => fn_getIp(true),
        );

        $_num_mysql = $this->obj_db->update(BG_DB_TABLE . "admin", $_arr_adminData, "admin_id=" . $num_adminId); //更新数据
        if ($_num_mysql > 0) {
            $_str_alert = "y020103"; //更新成功
        } else {
            return array(
                "alert" => "x020103", //更新失败
            );
        }

        return array(
            "admin_id"   => $_num_adminId,
            "alert"      => $_str_alert, //成功
        );
    }


    /** 修改个人信息
     * mdl_profile function.
     *
     * @access public
     * @param mixed $num_adminId
     * @return void
     */
    function mdl_profile($num_adminId) {
        $_arr_adminData = array(
            "admin_nick" => $this->adminProfile["admin_nick"],
        );

        $_num_adminId = $num_adminId;
        $_num_mysql   = $this->obj_db->update(BG_DB_TABLE . "admin", $_arr_adminData, "admin_id=" . $_num_adminId); //更新数据
        if ($_num_mysql > 0) {
            $_str_alert = "y020108"; //更新成功
        } else {
            return array(
                "alert" => "x020103", //更新失败
            );
        }

        return array(
            "admin_id"   => $_num_adminId,
            "alert"      => $_str_alert, //成功
        );
    }


    /** 修改密码
     * mdl_pass function.
     *
     * @access public
     * @param mixed $num_adminId
     * @return void
     */
    function mdl_pass($num_adminId) {
        $_arr_adminData = array(
            "admin_pass" => $this->adminPass["admin_pass_do"], //密码 md5 加密，加盐后再次 md5 加密
            "admin_rand" => $this->adminPass["admin_rand"], //加盐
        );

        $_num_adminId = $num_adminId;
        $_num_mysql   = $this->obj_db->update(BG_DB_TABLE . "admin", $_arr_adminData, "admin_id=" . $_num_adminId); //更新数据
        if ($_num_mysql > 0) {
            $_str_alert = "y020109"; //更新成功
        } else {
            return array(
                "alert" => "x020103", //更新失败
            );
        }

        return array(
            "admin_id"   => $_num_adminId,
            "alert"      => $_str_alert, //成功
        );
    }


    /** 管理员创建、编辑提交
     * mdl_submit function.
     *
     * @access public
     * @param string $str_adminPass (default: "")
     * @param string $str_adminRand (default: "")
     * @return void
     */
    function mdl_submit($str_adminPass = "", $str_adminRand = "") {
        $_arr_adminData = array(
            "admin_name"     => $this->adminSubmit["admin_name"],
            "admin_note"     => $this->adminSubmit["admin_note"],
            "admin_status"   => $this->adminSubmit["admin_status"],
            "admin_allow"    => $this->adminSubmit["admin_allow"],
            "admin_nick"     => $this->adminSubmit["admin_nick"],
        );

        if ($this->adminSubmit["admin_id"] < 1) {
            $_arr_insert = array(
                "admin_pass"        => $str_adminPass,
                "admin_rand"        => $str_adminRand,
                "admin_time"        => time(),
                "admin_time_login"  => time(),
                "admin_ip"          => fn_getIp(),
            );
            $_arr_data = array_merge($_arr_adminData, $_arr_insert);

            $_num_adminId = $this->obj_db->insert(BG_DB_TABLE . "admin", $_arr_data); //更新数据
            if ($_num_adminId > 0) {
                $_str_alert = "y020101"; //更新成功
            } else {
                return array(
                    "alert" => "x020101", //更新失败
                );
            }
        } else {
            if ($str_adminPass) {
                $_arr_adminData["admin_pass"] = $str_adminPass; //如果密码不为空则修改
            }
            if ($str_adminRand) {
                $_arr_adminData["admin_rand"] = $str_adminRand; //如果密码不为空则修改
            }
            $_num_adminId    = $this->adminSubmit["admin_id"];
            $_num_mysql      = $this->obj_db->update(BG_DB_TABLE . "admin", $_arr_adminData, "admin_id=" . $_num_adminId); //更新数据
            if ($_num_mysql > 0) {
                $_str_alert = "y020103"; //更新成功
            } else {
                return array(
                    "alert" => "x020103", //更新失败
                );

            }
        }

        return array(
            "admin_id"   => $_num_adminId,
            "alert"      => $_str_alert, //成功
        );
    }


    /** 编辑状态
     * mdl_status function.
     *
     * @access public
     * @param mixed $str_status
     * @return void
     */
    function mdl_status($str_status) {
        $_str_adminId = implode(",", $this->adminIds["admin_ids"]);

        $_arr_adminUpdate = array(
            "admin_status" => $str_status,
        );

        $_num_mysql = $this->obj_db->update(BG_DB_TABLE . "admin", $_arr_adminUpdate, "admin_id IN (" . $_str_adminId . ")"); //删除数据

        //如影响行数大于0则返回成功
        if ($_num_mysql > 0) {
            $_str_alert = "y020103"; //成功
        } else {
            $_str_alert = "x020103"; //失败
        }

        return array(
            "alert" => $_str_alert,
        );
    }


    /** 读取
     * mdl_read function.
     *
     * @access public
     * @param mixed $str_admin
     * @param string $str_by (default: "admin_id")
     * @param int $num_notId (default: 0)
     * @return void
     */
    function mdl_read($str_admin, $str_by = "admin_id", $num_notId = 0) {
        $_arr_adminSelect = array(
            "admin_id",
            "admin_name",
            "admin_pass",
            "admin_note",
            "admin_nick",
            "admin_rand",
            "admin_time",
            "admin_time_login",
            "admin_ip",
            "admin_allow",
            "admin_status",
        );

        if (is_numeric($str_admin)) {
            $_str_sqlWhere = $str_by . "=" . $str_admin; //如果读取值为数字
        } else {
            $_str_sqlWhere = $str_by . "='" . $str_admin . "'";
        }

        if ($num_notId > 0) {
            $_str_sqlWhere .= " AND admin_id<>" . $num_notId;
        }

        $_arr_adminRows = $this->obj_db->select(BG_DB_TABLE . "admin", $_arr_adminSelect, $_str_sqlWhere, "", "", 1, 0); //检查本地表是否存在记录

        if (isset($_arr_adminRows[0])) { //用户名不存在则返回错误
            $_arr_adminRow = $_arr_adminRows[0];
        } else {
            return array(
                "alert" => "x020102", //不存在记录
            );
        }

        if (isset($_arr_adminRow["admin_allow"])) {
            $_arr_adminRow["admin_allow"] = fn_jsonDecode($_arr_adminRow["admin_allow"], "no"); //json 解码
        } else {
            $_arr_adminRow["admin_allow"] = array();
        }

        $_arr_adminRow["alert"]   = "y020102";

        return $_arr_adminRow;

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
        $_arr_adminSelect = array(
            "admin_id",
            "admin_name",
            "admin_note",
            "admin_nick",
            "admin_status",
            "admin_time",
            "admin_time_login",
            "admin_ip",
        );

        $_str_sqlWhere = $this->sql_process($arr_search);

        $_arr_adminRows = $this->obj_db->select(BG_DB_TABLE . "admin", $_arr_adminSelect, $_str_sqlWhere, "", "admin_id DESC", $num_no, $num_except); //查询数据

        return $_arr_adminRows;
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

        $_num_adminCount = $this->obj_db->count(BG_DB_TABLE . "admin", $_str_sqlWhere); //查询数据

        return $_num_adminCount;
    }


    /** 删除
     * mdl_del function.
     *
     * @access public
     * @return void
     */
    function mdl_del() {
        $_str_adminId = implode(",", $this->adminIds["admin_ids"]);

        $_num_mysql = $this->obj_db->delete(BG_DB_TABLE . "admin", "admin_id IN (" . $_str_adminId . ")"); //删除数据

        //如车影响行数小于0则返回错误
        if ($_num_mysql > 0) {
            $_str_alert = "y020104"; //成功
        } else {
            $_str_alert = "x020104"; //失败
        }

        return array(
            "alert" => $_str_alert,
        );
    }


    /** 修改个人信息表单验证
     * input_profile function.
     *
     * @access public
     * @return void
     */
    function input_profile() {
        if (!fn_token("chk")) { //令牌
            return array(
                "alert" => "x030206",
            );
        }

        $_arr_adminNick = validateStr(fn_post("admin_nick"), 0, 30);
        switch ($_arr_adminNick["status"]) {
            case "too_long":
                return array(
                    "alert" => "x020212",
                );
            break;

            case "ok":
                $this->adminProfile["admin_nick"] = $_arr_adminNick["str"];
            break;

        }

        $this->adminProfile["alert"]  = "ok";

        return $this->adminProfile;
    }


    /** 修改密码表单验证
     * input_pass function.
     *
     * @access public
     * @return void
     */
    function input_pass() {
        if (!fn_token("chk")) { //令牌
            return array(
                "alert" => "x030206",
            );
        }

        $_arr_adminPassOld = validateStr(fn_post("admin_pass"), 1, 0);
        switch ($_arr_adminPassOld["status"]) {
            case "too_short":
                return array(
                    "alert" => "x020210",
                );
            break;

            case "ok":
                $this->adminPass["admin_pass"] = $_arr_adminPassOld["str"];
            break;
        }

        $_arr_adminPassNew = validateStr(fn_post("admin_pass_new"), 1, 0);
        switch ($_arr_adminPassNew["status"]) {
            case "too_short":
                return array(
                    "alert" => "x020213",
                );
            break;

            case "ok":
                $this->adminPass["admin_pass_new"] = $_arr_adminPassNew["str"];
            break;
        }

        $_arr_adminPassConfirm = validateStr(fn_post("admin_pass_confirm"), 1, 0);
        switch ($_arr_adminPassConfirm["status"]) {
            case "too_short":
                return array(
                    "alert" => "x020215",
                );
            break;

            case "ok":
                $this->adminPass["admin_pass_confirm"] = $_arr_adminPassConfirm["str"];
            break;
        }

        if ($this->adminPass["admin_pass_new"] != $this->adminPass["admin_pass_confirm"]) {
            return array(
                "alert" => "x020211",
            );
        }

        $this->adminPass["admin_rand"]    = fn_rand(6);
        $this->adminPass["admin_pass_do"] = fn_baigoEncrypt($this->adminPass["admin_pass_new"], $this->adminPass["admin_rand"]);
        $this->adminPass["alert"]     = "ok";

        return $this->adminPass;
    }


    /** 登录验证
     * input_login function.
     *
     * @access public
     * @return void
     */
    function input_login() {
        $this->adminLogin["forward"] = fn_getSafe(fn_post("forward"), "txt", "");
        if (!$this->adminLogin["forward"]) {
            $this->adminLogin["forward"] = base64_encode(BG_URL_ADMIN . "ctl.php");
        }

        if (!fn_seccode()) { //验证码
            return array(
                "forward"   => $this->adminLogin["forward"],
                "alert"     => "x030205",
            );
        }

        if (!fn_token("chk")) { //令牌
            return array(
                "forward"   => $this->adminLogin["forward"],
                "alert"     => "x030206",
            );
        }

        $_arr_adminName = validateStr(fn_post("admin_name"), 1, 30, "str", "strDigit");
        switch ($_arr_adminName["status"]) {
            case "too_short":
                return array(
                    "forward"   => $this->adminLogin["forward"],
                    "alert"     => "x020201",
                );
            break;

            case "too_long":
                return array(
                    "forward"   => $this->adminLogin["forward"],
                    "alert"     => "x020202",
                );
            break;

            case "format_err":
                return array(
                    "forward"   => $this->adminLogin["forward"],
                    "alert"     => "x020203",
                );
            break;

            case "ok":
                $this->adminLogin["admin_name"] = $_arr_adminName["str"];
            break;

        }

        $_arr_adminPass = validateStr(fn_post("admin_pass"), 1, 0);
        switch ($_arr_adminPass["status"]) {
            case "too_short":
                return array(
                    "forward"   => $this->adminLogin["forward"],
                    "alert"     => "x020205",
                );
            break;

            case "ok":
                $this->adminLogin["admin_pass"] = $_arr_adminPass["str"];
            break;

        }

        $this->adminLogin["alert"]  = "ok";

        return $this->adminLogin;
    }


    /** 创建、编辑表单验证
     * input_submit function.
     *
     * @access public
     * @return void
     */
    function input_submit() {
        if (!fn_token("chk")) { //令牌
            return array(
                "alert" => "x030206",
            );
        }

        $this->adminSubmit["admin_id"] = fn_getSafe(fn_post("admin_id"), "int", 0);

        if ($this->adminSubmit["admin_id"] > 0) {
            //检验用户是否存在
            $_arr_adminRow = $this->mdl_read($this->adminSubmit["admin_id"]);
            if ($_arr_adminRow["alert"] != "y020102") {
                return $_arr_adminRow;
            }
        }

        $_arr_adminName = validateStr(fn_post("admin_name"), 1, 30);
        switch ($_arr_adminName["status"]) {
            case "too_short":
                return array(
                    "alert" => "x020201",
                );
            break;

            case "too_long":
                return array(
                    "alert" => "x020202",
                );
            break;

            case "ok":
                $this->adminSubmit["admin_name"] = $_arr_adminName["str"];
            break;
        }

        //检验用户名是否重复
        $_arr_adminRow = $this->mdl_read($this->adminSubmit["admin_name"], "admin_name", $this->adminSubmit["admin_id"]);
        if ($_arr_adminRow["alert"] == "y020102") {
            return array(
                "alert" => "x020204",
            );
        }

        $_arr_adminNote = validateStr(fn_post("admin_note"), 0, 30);
        switch ($_arr_adminNote["status"]) {
            case "too_long":
                return array(
                    "alert" => "x020208",
                );
            break;

            case "ok":
                $this->adminSubmit["admin_note"] = $_arr_adminNote["str"];
            break;
        }

        $_arr_adminStatus = validateStr(fn_post("admin_status"), 1, 0);
        switch ($_arr_adminStatus["status"]) {
            case "too_short":
                return array(
                    "alert" => "x020209",
                );
            break;

            case "ok":
                $this->adminSubmit["admin_status"] = $_arr_adminStatus["str"];
            break;

        }

        $_arr_adminNick = validateStr(fn_post("admin_nick"), 0, 30);
        switch ($_arr_adminNick["status"]) {
            case "too_long":
                return array(
                    "alert" => "x020212",
                );
            break;

            case "ok":
                $this->adminSubmit["admin_nick"] = $_arr_adminNick["str"];
            break;
        }

        $this->adminSubmit["admin_allow"] = fn_jsonEncode(fn_post("admin_allow"), "no");
        $this->adminSubmit["alert"]       = "ok";

        return $this->adminSubmit;
    }


    /** api 创建验证
     * api_add function.
     *
     * @access public
     * @return void
     */
    function api_add() {
        if (!fn_token("chk")) { //令牌
            return array(
                "alert" => "x030206",
            );
        }

        $_arr_adminName = validateStr(fn_post("admin_name"), 1, 30);
        switch ($_arr_adminName["status"]) {
            case "too_short":
                return array(
                    "alert" => "x020201",
                );
            break;

            case "too_long":
                return array(
                    "alert" => "x020202",
                );
            break;

            case "ok":
                $this->adminSubmit["admin_name"] = $_arr_adminName["str"];
            break;
        }

        //检验用户名是否重复
        $_arr_adminRow = $this->mdl_read($this->adminSubmit["admin_name"], "admin_name");
        if ($_arr_adminRow["alert"] == "y020102") {
            return array(
                "alert" => "x020204",
            );
        }

        $_arr_adminPass = validateStr(fn_post("admin_pass"), 1, 0);
        switch ($_arr_adminPass["status"]) {
            case "too_short":
                return array(
                    "alert" => "x020210",
                );
            break;

            case "ok":
                $this->adminSubmit["admin_pass"] = $_arr_adminPass["str"];
            break;
        }

        $this->adminSubmit["admin_nick"]    = $this->adminSubmit["admin_name"];
        $this->adminSubmit["admin_note"]    = $this->adminSubmit["admin_name"];
        $this->adminSubmit["admin_id"]      = 0;
        $this->adminSubmit["admin_status"]  = "enable";

        $_arr_adminAllow = array(
            "user" => array(
                "browse"   => 1,
                "add"      => 1,
                "edit"     => 1,
                "del"      => 1,
            ),
            "app" => array(
                "browse"   => 1,
                "add"      => 1,
                "edit"     => 1,
                "del"      => 1,
            ),
            "log" => array(
                "browse"   => 1,
                "edit"     => 1,
                "del"      => 1,
            ),
            "admin" => array(
                "browse"   => 1,
                "add"      => 1,
                "edit"     => 1,
                "del"      => 1,
            ),
            "opt" => array(
                "dbconfig" => 1,
                "base"     => 1,
                "reg"      => 1,
                "smtp"     => 1,
            ),
        );

        $this->adminSubmit["admin_allow"] = fn_jsonEncode($_arr_adminAllow, "no");
        $this->adminSubmit["alert"]       = "ok";

        return $this->adminSubmit;
    }


    /** 选择管理员
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

        $_arr_adminIds = fn_post("admin_ids");

        if ($_arr_adminIds) {
            foreach ($_arr_adminIds as $_key=>$_value) {
                $_arr_adminIds[$_key] = fn_getSafe($_value, "int", 0);
            }
            $_str_alert = "ok";
        } else {
            $_str_alert = "x030202";
        }

        $this->adminIds = array(
            "alert"      => $_str_alert,
            "admin_ids"  => $_arr_adminIds
        );

        return $this->adminIds;
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

        if (isset($arr_search["key"]) && $arr_search["key"]) {
            $_str_sqlWhere .= " AND (admin_name LIKE '%" . $arr_search["key"] . "%' OR admin_note LIKE '%" . $arr_search["key"] . "%' OR admin_nick LIKE '%" . $arr_search["key"] . "%')";
        }

        if (isset($arr_search["status"]) && $arr_search["status"]) {
            $_str_sqlWhere .= " AND admin_status='" . $arr_search["status"] . "'";
        }

        return $_str_sqlWhere;
    }
}
