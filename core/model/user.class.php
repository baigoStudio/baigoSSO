<?php
/*-----------------------------------------------------------------
！！！！警告！！！！
以下为系统文件，请勿修改
-----------------------------------------------------------------*/

//不能非法包含或直接执行
if(!defined("IN_BAIGO")) {
    exit("Access Denied");
}

/*-------------用户模型-------------*/
class MODEL_USER {

    private $obj_db;
    private $csvRows;
    public $userStatus = array();

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
        foreach ($this->userStatus as $_key=>$_value) {
            $_arr_status[] = $_key;
        }
        $_str_status = implode("','", $_arr_status);

        $_arr_userCreate = array(
            "user_id"               => "int NOT NULL AUTO_INCREMENT COMMENT 'ID'",
            "user_name"             => "varchar(30) NOT NULL COMMENT '用户名'",
            "user_mail"             => "varchar(300) NOT NULL COMMENT '邮箱'",
            "user_contact"          => "varchar(3000) NOT NULL COMMENT '联系方式'",
            "user_pass"             => "char(32) NOT NULL COMMENT '密码'",
            "user_rand"             => "char(6) NOT NULL COMMENT '随机串'",
            "user_nick"             => "varchar(30) NOT NULL COMMENT '昵称'",
            "user_status"           => "enum('" . $_str_status . "') NOT NULL COMMENT '状态'",
            "user_note"             => "varchar(30) NOT NULL COMMENT '备注'",
            "user_time"             => "int NOT NULL COMMENT '创建时间'",
            "user_time_login"       => "int NOT NULL COMMENT '登录时间'",
            "user_ip"               => "varchar(15) NOT NULL COMMENT '最后 IP 地址'",
            "user_access_token"     => "char(32) NOT NULL COMMENT '访问口令'",
            "user_access_expire"    => "int NOT NULL COMMENT '访问过期时间'",
            "user_refresh_token"    => "char(32) NOT NULL COMMENT '刷新口令'",
            "user_refresh_expire"   => "int NOT NULL COMMENT '刷新过期时间'",
        );

        $_num_mysql = $this->obj_db->create_table(BG_DB_TABLE . "user", $_arr_userCreate, "user_id", "用户");

        if ($_num_mysql > 0) {
            $_str_alert = "y010105"; //更新成功
        } else {
            $_str_alert = "x010105"; //更新成功
        }

        return array(
            "alert" => $_str_alert, //更新成功
        );
    }


    /** 修改表
     * mdl_alert_table function.
     *
     * @access public
     * @return void
     */
    function mdl_alert_table() {
        foreach ($this->userStatus as $_key=>$_value) {
            $_arr_status[] = $_key;
        }
        $_str_status = implode("','", $_arr_status);

        $_arr_col     = $this->mdl_column();
        $_arr_alert   = array();

        if (in_array("user_status", $_arr_col)) {
            $_arr_alert["user_status"] = array("CHANGE", "enum('" . $_str_status . "') NOT NULL COMMENT '状态'", "user_status");
        }

        $_arr_userData = array(
            "user_status" => $_arr_status[0],
        );
        $this->obj_db->update(BG_DB_TABLE . "user", $_arr_userData, "LENGTH(user_status) < 1"); //更新数据

        if (in_array("user_pass", $_arr_col)) {
            $_arr_alert["user_pass"] = array("CHANGE", "char(32) NOT NULL COMMENT '密码'", "user_pass");
        }

        if (in_array("user_rand", $_arr_col)) {
            $_arr_alert["user_rand"] = array("CHANGE", "char(6) NOT NULL COMMENT '随机串'", "user_rand");
        }

        if (in_array("user_token", $_arr_col)) {
            $_arr_alert["user_token"] = array("CHANGE", "char(32) NOT NULL COMMENT '访问口令'", "user_access_token");
        } else if (!in_array("user_access_token", $_arr_col)) {
            $_arr_alert["user_access_token"] = array("ADD", "char(32) NOT NULL COMMENT '访问口令'");
        }

        if (in_array("user_token_expire", $_arr_col)) {
            $_arr_alert["user_token_expire"] = array("CHANGE", "int NOT NULL COMMENT '访问过期时间'", "user_access_expire");
        } else if (!in_array("user_access_expire", $_arr_col)) {
            $_arr_alert["user_access_expire"] = array("ADD", "int NOT NULL COMMENT '访问过期时间'");
        }

        if (!in_array("user_refresh_token", $_arr_col)) {
            $_arr_alert["user_refresh_token"] = array("ADD", "char(32) NOT NULL COMMENT '刷新口令'");
        }

        if (!in_array("user_refresh_expire", $_arr_col)) {
            $_arr_alert["user_refresh_expire"] = array("ADD", "int NOT NULL COMMENT '刷新过期时间'");
        }

        if (!in_array("user_contact", $_arr_col)) {
            $_arr_alert["user_contact"] = array("ADD", "varchar(3000) NOT NULL COMMENT '联系方式'");
        }

        $_str_alert = "y010111";

        if ($_arr_alert) {
            $_reselt = $this->obj_db->alert_table(BG_DB_TABLE . "user", $_arr_alert);

            if ($_reselt) {
                $_str_alert = "y010106";
            }
        }

        return array(
            "alert" => $_str_alert,
        );
    }


    /** 创建视图
     * mdl_create_view function.
     *
     * @access public
     * @return void
     */
    function mdl_create_view() {
        $_arr_userCreat = array(
            array("user_id",            BG_DB_TABLE . "user"),
            array("user_name",          BG_DB_TABLE . "user"),
            array("user_mail",          BG_DB_TABLE . "user"),
            array("user_nick",          BG_DB_TABLE . "user"),
            array("user_note",          BG_DB_TABLE . "user"),
            array("user_status",        BG_DB_TABLE . "user"),
            array("user_time",          BG_DB_TABLE . "user"),
            array("user_time_login",    BG_DB_TABLE . "user"),
            array("user_ip",            BG_DB_TABLE . "user"),
            array("belong_app_id",      BG_DB_TABLE . "belong"),
        );

        $_str_sqlJoin = "LEFT JOIN `" . BG_DB_TABLE . "belong` ON (`" . BG_DB_TABLE . "user`.`user_id`=`" . BG_DB_TABLE . "belong`.`belong_user_id`)";

        $_num_mysql = $this->obj_db->create_view(BG_DB_TABLE . "user_view", $_arr_userCreat, BG_DB_TABLE . "user", $_str_sqlJoin);

        if ($_num_mysql > 0) {
            $_str_alert = "y010108"; //更新成功
        } else {
            $_str_alert = "x010108"; //更新成功
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
        $_arr_colRows = $this->obj_db->show_columns(BG_DB_TABLE . "user");

        foreach ($_arr_colRows as $_key=>$_value) {
            $_arr_col[] = $_value["Field"];
        }

        return $_arr_col;
    }


    /** 登录
     * mdl_login function.
     *
     * @access public
     * @param mixed $num_userId
     * @return void
     */
    function mdl_login($num_userId) {
        $_str_accessToken   = fn_rand(32);
        $_tm_accessExpire   = time() + BG_ACCESS_EXPIRE * 60;
        $_str_refreshToken  = fn_rand(32);
        $_tm_refreshExpire  = time() + BG_REFRESH_EXPIRE * 86400;

        $_arr_userData = array(
            "user_pass"             => $this->apiLogin["user_pass_do"],
            "user_rand"             => $this->apiLogin["user_rand"],
            "user_time_login"       => time(),
            "user_ip"               => fn_getIp(true),
            "user_access_token"     => md5($_str_accessToken),
            "user_access_expire"    => $_tm_accessExpire,
            "user_refresh_token"    => md5($_str_refreshToken),
            "user_refresh_expire"   => $_tm_refreshExpire,
        );

        $_num_mysql = $this->obj_db->update(BG_DB_TABLE . "user", $_arr_userData, "user_id=" . $num_userId); //更新数据
        if ($_num_mysql > 0) {
            $_str_alert = "y010103"; //更新成功
        } else {
            return array(
                "alert" => "x010103", //更新失败
            );
        }

        return array(
            "user_id"               => $num_userId,
            "user_access_token"     => $_str_accessToken,
            "user_access_expire"    => $_tm_accessExpire,
            "user_refresh_token"    => $_str_refreshToken,
            "user_refresh_expire"   => $_tm_refreshExpire,
            "alert"                 => $_str_alert, //成功
        );
    }


    /** 刷新访问口令
     * mdl_refresh function.
     *
     * @access public
     * @param mixed $num_userId
     * @return void
     */
    function mdl_refresh($num_userId) {
        $_str_accessToken   = fn_rand(32);
        $_tm_accessExpire   = time() + BG_ACCESS_EXPIRE * 60;

        $_arr_userData = array(
            "user_access_token"     => md5($_str_accessToken),
            "user_access_expire"    => $_tm_accessExpire,
        );

        $_num_mysql = $this->obj_db->update(BG_DB_TABLE . "user", $_arr_userData, "user_id=" . $num_userId); //更新数据
        if ($_num_mysql > 0) {
            $_str_alert = "y010103"; //更新成功
        } else {
            return array(
                "alert" => "x010103", //更新失败
            );
        }

        return array(
            "user_id"               => $num_userId,
            "user_access_token"     => $_str_accessToken,
            "user_access_expire"    => $_tm_accessExpire,
            "alert"                 => $_str_alert, //成功
        );
    }


    /** 编辑
     * mdl_edit function.
     *
     * @access public
     * @param mixed $num_userId
     * @return void
     */
    function mdl_edit($num_userId) {
        $_arr_userData = array();

        if (isset($this->apiEdit["user_pass_do"])) { //如果 密码 为空，则不修改
            $_arr_userData["user_pass"] = $this->apiEdit["user_pass_do"];
            $_arr_userData["user_rand"] = $this->apiEdit["user_rand"];
        }

        if (isset($this->apiEdit["user_mail_new"]) && $this->apiEdit["user_mail_new"]) { //如果 新邮箱 为空，则不修改
            $_arr_userData["user_mail"] = $this->apiEdit["user_mail_new"];
        }

        if (isset($this->apiEdit["user_nick"]) && $this->apiEdit["user_nick"]) { //如果 昵称 为空，则不修改
            $_arr_userData["user_nick"] = $this->apiEdit["user_nick"];
        }

        if (isset($this->apiEdit["user_contact"])) { //如果 联系方式 为空，则不修改
            $_arr_userData["user_contact"] = $this->apiEdit["user_contact"];
        }

        if ($_arr_userData) {
            $_num_mysql   = $this->obj_db->update(BG_DB_TABLE . "user", $_arr_userData, "user_id=" . $num_userId); //更新数据
        }

        if ($_num_mysql > 0) {
            $_str_alert = "y010103"; //更新成功
        } else {
            return array(
                "alert" => "x010103", //更新失败
            );
        }

        return array(
            "user_id"    => $num_userId,
            "user_nick"  => $this->apiEdit["user_nick"],
            "alert"      => $_str_alert, //成功
        );
    }


    /** 忘记密码
     * mdl_forgot function.
     *
     * @access public
     * @param mixed $num_userId
     * @return void
     */
    function mdl_forgot($num_userId) {
        $_arr_userData = array();

        $_arr_userData["user_pass"] = $this->apiEdit["user_pass_do"];
        $_arr_userData["user_rand"] = $this->apiEdit["user_rand"];

        if ($_arr_userData) {
            $_num_mysql   = $this->obj_db->update(BG_DB_TABLE . "user", $_arr_userData, "user_id=" . $num_userId); //更新数据
        }

        if ($_num_mysql > 0) {
            $_str_alert = "y010103"; //更新成功
        } else {
            return array(
                "alert" => "x010103", //更新失败
            );
        }

        return array(
            "user_id"    => $num_userId,
            "alert"      => $_str_alert, //成功
        );
    }


    /** 修改邮箱
     * mdl_mail function.
     *
     * @access public
     * @param mixed $num_userId
     * @param mixed $str_mail
     * @return void
     */
    function mdl_mail($num_userId, $str_mail) {
        $_arr_userData = array(
            "user_mail" => $str_mail,
        );

        if ($_arr_userData) {
            $_num_mysql   = $this->obj_db->update(BG_DB_TABLE . "user", $_arr_userData, "user_id=" . $num_userId); //更新数据
        }

        if ($_num_mysql > 0) {
            $_str_alert = "y010103"; //更新成功
        } else {
            return array(
                "alert" => "x010103", //更新失败
            );
        }

        return array(
            "user_id"    => $num_userId,
            "user_mail"  => $str_mail,
            "alert"      => $_str_alert, //成功
        );
    }


    /** 激活用户
     * mdl_confirm function.
     *
     * @access public
     * @param mixed $num_userId
     * @return void
     */
    function mdl_confirm($num_userId) {
        $_arr_userData = array();

        $_arr_userData["user_status"] = "enable";

        if ($_arr_userData) {
            $_num_mysql   = $this->obj_db->update(BG_DB_TABLE . "user", $_arr_userData, "user_id=" . $num_userId); //更新数据
        }

        if ($_num_mysql > 0) {
            $_str_alert = "y010103"; //更新成功
        } else {
            return array(
                "alert" => "x010103", //更新失败
            );
        }

        return array(
            "user_id"    => $num_userId,
            "alert"      => $_str_alert, //成功
        );
    }


    /** 提交
     * mdl_submit function.
     *
     * @access public
     * @param string $str_userPass (default: "")
     * @param string $str_userRand (default: "")
     * @return void
     */
    function mdl_submit($str_userPass = "", $str_userRand = "", $str_status = "") {
        $_str_accessToken       = fn_rand(32);
        $_tm_accessExpire       = time() + BG_ACCESS_EXPIRE * 60;
        $_str_refreshToken      = fn_rand(32);
        $_tm_refreshExpire      = time() + BG_REFRESH_EXPIRE * 86400;

        $_arr_userData = array(
            "user_name"             => $this->userSubmit["user_name"],
            "user_mail"             => $this->userSubmit["user_mail"],
            "user_access_token"     => md5($_str_accessToken),
            "user_access_expire"    => $_tm_accessExpire,
            "user_refresh_token"    => md5($_str_refreshToken),
            "user_refresh_expire"   => $_tm_refreshExpire,
        );

        if (isset($this->userSubmit["user_nick"])) {
            $_arr_userData["user_nick"] = $this->userSubmit["user_nick"];
        }

        if (isset($this->userSubmit["user_contact"])) {
            $_arr_userData["user_contact"] = $this->userSubmit["user_contact"];
        }

        if ($str_status) {
            $_arr_userData["user_status"] = $str_status;
        } else {
            $_arr_userData["user_status"] = $this->userSubmit["user_status"];
        }

        if (isset($this->userSubmit["user_note"])) {
            $_arr_userData["user_note"] = $this->userSubmit["user_note"];
        }

        if ($this->userSubmit["user_id"] < 1) {
            $_arr_insert = array(
                "user_pass"         => $str_userPass,
                "user_rand"         => $str_userRand,
                "user_time"         => time(),
                "user_time_login"   => time(),
                "user_ip"           => fn_getIp(),
            );
            $_arr_data   = array_merge($_arr_userData, $_arr_insert);
            $_num_userId = $this->obj_db->insert(BG_DB_TABLE . "user", $_arr_data); //更新数据
            if ($_num_userId > 0) {
                $_str_alert = "y010101"; //更新成功
            } else {
                return array(
                    "alert" => "x010101", //更新失败
                );
            }
        } else {
            if ($str_userPass) {
                $_arr_userData["user_pass"] = $str_userPass; //如果密码为空，则不修改
            }
            if ($str_userRand) {
                $_arr_userData["user_rand"] = $str_userRand; //如果密码为空，则不修改
            }
            $_num_userId = $this->userSubmit["user_id"];
            $_num_mysql  = $this->obj_db->update(BG_DB_TABLE . "user", $_arr_userData, "user_id=" . $_num_userId); //更新数据
            if ($_num_mysql > 0) {
                $_str_alert = "y010103"; //更新成功
            } else {
                return array(
                    "alert" => "x010103", //更新失败
                );

            }
        }

        return array(
            "user_id"               => $_num_userId,
            "user_name"             => $this->userSubmit["user_name"],
            "user_mail"             => $this->userSubmit["user_mail"],
            "user_nick"             => $this->userSubmit["user_nick"],
            "user_access_token"     => $_str_accessToken,
            "user_access_expire"    => $_tm_accessExpire,
            "user_refresh_token"    => $_str_refreshToken,
            "user_refresh_expire"   => $_tm_refreshExpire,
            "alert"                 => $_str_alert, //成功
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
        $_str_userId = implode(",", $this->userIds["user_ids"]);

        $_arr_userUpdate = array(
            "user_status" => $str_status,
        );

        $_num_mysql = $this->obj_db->update(BG_DB_TABLE . "user", $_arr_userUpdate, "user_id IN (" . $_str_userId . ")"); //删除数据

        //如影响行数大于0则返回成功
        if ($_num_mysql > 0) {
            $_str_alert = "y010103"; //成功
        } else {
            $_str_alert = "x010103"; //失败
        }

        return array(
            "alert" => $_str_alert,
        );
    }


    /** 读取
     * mdl_read function.
     *
     * @access public
     * @param mixed $str_user
     * @param string $str_by (default: "user_id")
     * @param int $num_notId (default: 0)
     * @return void
     */
    function mdl_read($str_user, $str_by = "user_id", $num_notId = 0) {
        $_arr_userSelect = array(
            "user_id",
            "user_name",
            "user_pass",
            "user_mail",
            "user_contact",
            "user_nick",
            "user_note",
            "user_rand",
            "user_status",
            "user_time",
            "user_time_login",
            "user_ip",
            "user_access_token",
            "user_access_expire",
            "user_refresh_token",
            "user_refresh_expire",
        );

        if (is_numeric($str_user)) {
            $_str_sqlWhere = $str_by . "=" . $str_user;
        } else {
            $_str_sqlWhere = $str_by . "='" . $str_user . "'";
        }

        if ($num_notId > 0) {
            $_str_sqlWhere .= " AND user_id<>" . $num_notId;
        }

        $_arr_userRows    = $this->obj_db->select(BG_DB_TABLE . "user", $_arr_userSelect, $_str_sqlWhere, "", "", 1, 0); //检查本地表是否存在记录

        if (isset($_arr_userRows[0])) { //用户名不存在则返回错误
            $_arr_userRow = $_arr_userRows[0];
        } else {
            return array(
                "alert" => "x010102", //不存在记录
            );
        }

        $_arr_userRow["user_contact"]   = fn_jsonDecode($_arr_userRow["user_contact"], "decode");
        $_arr_userRow["alert"]          = "y010102";

        return $_arr_userRow;
    }


    /** api 读取
     * mdl_read_api function.
     *
     * @access public
     * @param mixed $str_user
     * @param string $str_by (default: "user_id")
     * @param int $num_notId (default: 0)
     * @return void
     */
    function mdl_read_api($str_user, $str_by = "user_id", $num_notId = 0) {
        $_arr_userSelect = array(
            "user_id",
            "user_name",
            "user_mail",
            "user_contact",
            "user_nick",
            "user_status",
            "user_time",
            "user_time_login",
            "user_ip",
        );

        switch ($str_by) {
            case "user_id":
                $_str_sqlWhere = "user_id=" . $str_user;
            break;
            default:
                $_str_sqlWhere = $str_by . "='" . $str_user . "'";
            break;
        }

        if ($num_notId > 0) {
            $_str_sqlWhere .= " AND user_id<>" . $num_notId;
        }

        $_arr_userRows    = $this->obj_db->select(BG_DB_TABLE . "user", $_arr_userSelect, $_str_sqlWhere, "", "", 1, 0); //检查本地表是否存在记录

        if (isset($_arr_userRows[0])) { //用户名不存在则返回错误
            $_arr_userRow = $_arr_userRows[0];
        } else {
            return array(
                "alert" => "x010102", //不存在记录
            );
        }

        $_arr_userRow["user_contact"]   = fn_jsonDecode($_arr_userRow["user_contact"], "decode");
        $_arr_userRow["alert"]          = "y010102";

        return $_arr_userRow;
    }


    /** 从视图列出
     * mdl_list_view function.
     *
     * @access public
     * @param array $arr_search (default: array())
     * @return void
     */
    function mdl_list_view($arr_search = array()) {
        $_arr_userSelect = array(
            "user_id",
            "user_name",
            "user_mail",
            "user_nick",
            "user_note",
            "user_status",
            "user_time",
            "user_time_login",
            "user_ip",
        );

        $_str_sqlWhere = "1=1";

        if (isset($arr_search["key"]) && $arr_search["key"]) {
            $_str_sqlWhere .= " AND (user_name LIKE '%" . $arr_search["key"] . "%' OR user_nick LIKE '%" . $arr_search["key"] . "%' OR user_note LIKE '%" . $arr_search["key"] . "%')";
        }

        if (isset($arr_search["app_id"]) && $arr_search["app_id"] > 0) {
            $_str_sqlWhere .= " AND belong_app_id=" . $arr_search["app_id"];
        }

        $_arr_userRows = $this->obj_db->select(BG_DB_TABLE . "user_view", $_arr_userSelect, $_str_sqlWhere, "", "user_id DESC"); //查询数据

        return $_arr_userRows;
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
        $_arr_userSelect = array(
            "user_id",
            "user_name",
            "user_mail",
            "user_nick",
            "user_note",
            "user_status",
            "user_time",
            "user_time_login",
            "user_ip",
        );

        $_str_sqlWhere = $this->sql_process($arr_search);

        $_arr_userRows = $this->obj_db->select(BG_DB_TABLE . "user", $_arr_userSelect, $_str_sqlWhere, "", "user_id DESC", $num_no, $num_except); //查询数据

        return $_arr_userRows;
    }


    /** 计数
     * mdl_count function.
     *
     * @access public
     * @param array $arr_search (default: array())
     * @return void
     */
    function mdl_count($arr_search = array()) {
        $_str_sqlWhere = "1=1";

        $_str_sqlWhere = $this->sql_process($arr_search);

        $_num_userCount = $this->obj_db->count(BG_DB_TABLE . "user", $_str_sqlWhere); //查询数据

        return $_num_userCount;
    }


     /** 删除
     * mdl_del function.
     *
     * @access public
     * @param mixed $_arr_userIds
     * @return void
     */
    function mdl_del($_arr_userIds) {
        $_str_userId  = implode(",", $_arr_userIds);
        $_num_mysql   = $this->obj_db->delete(BG_DB_TABLE . "user", "user_id IN (" . $_str_userId . ")"); //删除数据

        //如车影响行数小于0则返回错误
        if ($_num_mysql > 0) {
            $_str_alert = "y010104"; //成功
            $this->obj_db->delete(BG_DB_TABLE . "belong", "belong_user_id IN (" . $_str_userId . ")"); //删除数据
        } else {
            $_str_alert = "x010104"; //失败
        }

        return array(
            "alert" => $_str_alert,
        );
    }


    /** 导入预览
     * mdl_import function.
     *
     * @access public
     * @return void
     */
    function mdl_import() {
        if (file_exists(BG_PATH_CONFIG . "user_import.csv")) {
            $_obj_csv    = fopen(BG_PATH_CONFIG . "user_import.csv", "r");

            $_str_sample = fread($_obj_csv, 1000) + 'e';
            rewind($_obj_csv);

            $_str_encode = mb_detect_encoding($_str_sample, "GB2312, GBK, UTF-8, BIG5, EUC-JP, SJIS, eucJP-win, SJIS-win, JIS, ISO-2022-JP, UTF-7, ASCII", true);

            //print_r($_str_encode);

            if ($_str_encode && $_str_encode != "UTF-8" && $_str_encode != "ASCII") {
                stream_filter_append($_obj_csv, "convert.iconv." . $_str_encode . "/UTF-8");
            }

            $_num_row    = 0;
            while ($_arr_data = fgetcsv($_obj_csv)) {
                if ($_arr_data[0]) {
                    foreach ($_arr_data as $_key=>$_value) {
                        if ($_value) {
                            /*$_str_encode = mb_detect_encoding($_value , array("UTF-8", "GBK", "GB2312", "BIG5"));

                            if ($_str_encode != "UTF-8") {
                                $_str_value = mb_convert_encoding($_value, "UTF-8", "UTF-8, GBK, GB2312, BIG5");
                            } else {*/
                                $_str_value = $_value;
                            //}
                            $this->csvRows[$_num_row][] = fn_getSafe($_str_value, "txt", "");
                        } else {
                            $this->csvRows[$_num_row][] = "";
                        }
                    }
                    $_num_row++;
                }
            }
            fclose($_obj_csv);
        }

        return $this->csvRows;
    }


    /** 转换并导入数据库
     * mdl_convert function.
     *
     * @access public
     * @return void
     */
    function mdl_convert() {
        $_num_errChk      = 0;
        $_arr_csvRows     = $this->mdl_import();

        /*print_r($this->userConvert["user_list"]["convert"]);
        exit;*/

        foreach ($_arr_csvRows as $_key_row=>$_value_row) {
            foreach ($this->userConvert["user_convert"] as $_key_cel=>$_value_cel) {
                $_arr_userRow = $this->mdl_read($_value_row["user_name"], "user_name");
                if ($_arr_userRow["alert"] == "x010102") {
                    $_str_rand                  = fn_rand(6);
                    $_arr_userData["user_rand"] = $_str_rand;

                    switch ($_value_cel) {
                        case "user_pass":
                            $_str_userPass              = fn_baigoEncrypt($_value_row[$_key_cel], $_str_rand, true);
                            $_arr_userData["user_pass"] = $_str_userPass;
                        break;

                        case "abort":

                        break;

                        default:
                            $_arr_userData[$_value_cel] = $_value_row[$_key_cel];
                        break;
                    }
                }
            }

            //print_r($_arr_userData);

            $_num_userId = 0;

            if ($_key_row > 0) {
                $_num_userId = $this->obj_db->insert(BG_DB_TABLE . "user", $_arr_userData);
            }

            if ($_num_userId > 0) { //数据库插入是否成功
                $_num_errChk++;
            }

            unset($_arr_userData["user_abort"]);
        }

        if ($_num_errChk > 0) {
            $_str_alert = "y010402";
        } else {
            $_str_alert = "x010402";
        }

        return array(
            "user_id"    => $_num_userId,
            "alert"      => $_str_alert,
        );
    }


    /** 以 get 或 post 方式读取
     * input_get_by function.
     *
     * @access public
     * @param string $str_method (default: "get")
     * @return void
     */
    function input_get_by($str_method = "get") {
        if ($str_method == "post") {
            if (isset($_POST["user_id"])) {
                $_arr_userGet["user_by"]     = "user_id";
                $_arr_userChk                = $this->chk_user_id(fn_post("user_id"));
            } else if (isset($_POST["user_name"])) {
                $_arr_userGet["user_by"]     = "user_name";
                $_arr_userChk                = $this->chk_user_name(fn_post("user_name"));
            } else if (BG_LOGIN_MAIL == "on") {
                $_arr_userGet["user_by"]     = "user_mail";
                $_arr_userChk                = $this->chk_user_mail(fn_post("user_mail"));
            } else {
                $_arr_userChk["alert"] = "x010227";
            }
        } else {
            if (isset($_GET["user_id"])) {
                $_arr_userGet["user_by"]     = "user_id";
                $_arr_userChk                = $this->chk_user_id(fn_get("user_id"));
            } else if (isset($_GET["user_name"])) {
                $_arr_userGet["user_by"]     = "user_name";
                $_arr_userChk                = $this->chk_user_name(fn_get("user_name"));
            } else if (BG_LOGIN_MAIL == "on") {
                $_arr_userGet["user_by"]     = "user_mail";
                $_arr_userChk                = $this->chk_user_mail(fn_get("user_mail"));
            } else {
                $_arr_userChk["alert"] = "x010227";
            }
        }

        if ($_arr_userChk["alert"] != "ok") {
            return $_arr_userChk;
        }

        switch ($_arr_userGet["user_by"]) {
            case "user_id":
                $_arr_userGet["user_str"]    = $_arr_userChk["user_id"];
            break;

            case "user_name":
                $_arr_userGet["user_str"]    = $_arr_userChk["user_name"];
            break;

            default:
                $_arr_userGet["user_str"]    = $_arr_userChk["user_mail"];
            break;
        }

        $_arr_userGet["alert"] = "ok";

        return $_arr_userGet;
    }


    /** 表单验证用户名
     * input_chk_name function.
     *
     * @access public
     * @return void
     */
    function input_chk_name() {
        $_arr_userName = $this->chk_user_name(fn_get("user_name"));
        if ($_arr_userName["alert"] != "ok") {
            return $_arr_userName;
        }

        return array(
            "user_name"  => $_arr_userName["user_name"],
            "alert"      => "ok",
        );
    }


    /** 表单验证邮箱
     * input_chk_mail function.
     *
     * @access public
     * @return void
     */
    function input_chk_mail() {
        $_num_notId   = fn_getSafe(fn_get("not_id"), "int", 0);

        $_arr_userMail = $this->chk_user_mail(fn_get("user_mail"));
        if ($_arr_userMail["alert"] != "ok") {
            return $_arr_userMail;
        }

        return array(
            "not_id"     => $_num_notId,
            "user_mail"  => $_arr_userMail["user_mail"],
            "alert"      => "ok",
        );
    }


    /** api 注册表单验证
     * input_reg_api function.
     *
     * @access public
     * @return void
     */
    function input_reg_api() {
        $this->userSubmit["user_id"] = 0;

        $_arr_userName = $this->chk_user_name(fn_post("user_name"));
        if ($_arr_userName["alert"] != "ok") {
            return $_arr_userName;
        }
        $this->userSubmit["user_name"] = $_arr_userName["user_name"];

        $_arr_userRow = $this->mdl_read($this->userSubmit["user_name"], "user_name");
        if ($_arr_userRow["alert"] == "y010102") {
            return array(
                "alert" => "x010205",
            );
        }

        $_arr_userMail = $this->chk_user_mail(fn_post("user_mail"));
        if ($_arr_userMail["alert"] != "ok") {
            return $_arr_userMail;
        }
        $this->userSubmit["user_mail"] = $_arr_userMail["user_mail"];

        if ((BG_REG_ONEMAIL == "false" || BG_LOGIN_MAIL == "on") && $_arr_userMail["user_mail"]) {
            $_arr_userRow = $this->mdl_read($_arr_userMail["user_mail"], "user_mail"); //检查邮箱
            if ($_arr_userRow["alert"] == "y010102") {
                return array(
                    "alert" => "x010211",
                );
            }
        }

        $_arr_userPass = $this->chk_user_pass(fn_post("user_pass"));
        if ($_arr_userPass["alert"] != "ok") {
            return $_arr_userPass;
        }
        $this->userSubmit["user_pass"] = $_arr_userPass["user_pass"];

        $_arr_userNick = $this->chk_user_nick(fn_post("user_nick"));
        if ($_arr_userNick["alert"] != "ok") {
            return $_arr_userNick;
        }
        $this->userSubmit["user_nick"]    = $_arr_userNick["user_nick"];

        $_arr_userContact = fn_post("user_contact");
        $this->userSubmit["user_contact"] = fn_jsonEncode($_arr_userContact, "encode");

        $this->userSubmit["alert"]        = "ok";

        return $this->userSubmit;
    }


    /** api 登录表单验证
     * input_login_api function.
     *
     * @access public
     * @return void
     */
    function input_login_api() {
        $_arr_userGet = $this->input_get_by("post");

        if ($_arr_userGet["alert"] != "ok") {
            return $_arr_userGet;
        }

        $this->apiLogin   = $_arr_userGet;
        $_arr_userPass    = $this->chk_user_pass(fn_post("user_pass"));
        if ($_arr_userPass["alert"] != "ok") {
            return $_arr_userPass;
        }

        $this->apiLogin["user_rand"]                = fn_rand(6);
        $this->apiLogin["user_pass"]                = $_arr_userPass["user_pass"];
        $this->apiLogin["user_pass_do"]             = fn_baigoEncrypt($this->apiLogin["user_pass"], $this->apiLogin["user_rand"], true);
        $this->apiLogin["alert"]                    = "ok";

        return $this->apiLogin;
    }


    /** 忘记密码表单验证
     * input_forgot_verify function.
     *
     * @access public
     * @return void
     */
    function input_forgot_verify() {
        $_arr_userPassNew = validateStr(fn_post("user_pass_new"), 1, 0);
        switch ($_arr_userPassNew["status"]) {
            case "too_short":
                return array(
                    "alert" => "x010222",
                );
            break;

            case "ok":
                $this->apiEdit["user_pass_new"] = $_arr_userPassNew["str"];
            break;
        }

        $_arr_userPassConfirm = validateStr(fn_post("user_pass_confirm"), 1, 0);
        switch ($_arr_userPassConfirm["status"]) {
            case "too_short":
                return array(
                    "alert" => "x010224",
                );
            break;

            case "ok":
                $this->apiEdit["user_pass_confirm"] = $_arr_userPassConfirm["str"];
            break;
        }

        if ($this->apiEdit["user_pass_new"] != $this->apiEdit["user_pass_confirm"]) {
            return array(
                "alert" => "x010225",
            );
        }

        $this->apiEdit["user_rand"]     = fn_rand(6);
        $this->apiEdit["user_pass_do"]  = fn_baigoEncrypt($this->apiEdit["user_pass_new"], $this->apiEdit["user_rand"]);

        $this->apiEdit["alert"]         = "ok";

        return $this->apiEdit;
    }


    /** api 编辑表单验证
     * input_edit_api function.
     *
     * @access public
     * @return void
     */
    function input_edit_api() {
        $_arr_userGet = $this->input_get_by("post");

        if ($_arr_userGet["alert"] != "ok") {
            return $_arr_userGet;
        }

        $this->apiEdit = $_arr_userGet;

        $this->apiEdit["user_check_pass"] = fn_getSafe(fn_post("user_check_pass"), "txt", "");

        if ($this->apiEdit["user_check_pass"] == true) {
            $_arr_userPass = $this->chk_user_pass(fn_post("user_pass"));
            if ($_arr_userPass["alert"] != "ok") {
                return $_arr_userPass;
            }
            $this->apiEdit["user_pass"] = $_arr_userPass["user_pass"];
        }

        if (fn_post("user_pass_new")) {
            $this->apiEdit["user_pass_new"]  = fn_post("user_pass_new");
            $this->apiEdit["user_rand"]      = fn_rand(6);
            $this->apiEdit["user_pass_do"]   = fn_baigoEncrypt($this->apiEdit["user_pass_new"], $this->apiEdit["user_rand"], true);
        }

        if (fn_post("user_mail_new")) {
            $_arr_userMailNew = $this->chk_user_mail(fn_post("user_mail_new"));
            if ($_arr_userMailNew["alert"] != "ok") {
                return $_arr_userMailNew;
            }
            $this->apiEdit["user_mail_new"] = $_arr_userMailNew["user_mail"];
        }

        $_arr_userNick = $this->chk_user_nick(fn_post("user_nick"));
        if ($_arr_userNick["alert"] != "ok") {
            return $_arr_userNick;
        }
        $this->apiEdit["user_nick"]   = $_arr_userNick["user_nick"];

        $_arr_userContact = fn_post("user_contact");

        $this->apiEdit["user_contact"] = fn_jsonEncode($_arr_userContact, "encode");

        $this->apiEdit["alert"]       = "ok";

        return $this->apiEdit;
    }


    /** api 更换邮箱表单验证
     * input_mail_api function.
     *
     * @access public
     * @return void
     */
    function input_mail_api() {
        $_arr_userGet = $this->input_get_by("post");

        if ($_arr_userGet["alert"] != "ok") {
            return $_arr_userGet;
        }

        $this->apiMail = $_arr_userGet;

        $this->apiMail["user_check_pass"] = fn_getSafe(fn_post("user_check_pass"), "txt", "");

        if ($this->apiMail["user_check_pass"] == true) {
            $_arr_userPass = $this->chk_user_pass(fn_post("user_pass"));
            if ($_arr_userPass["alert"] != "ok") {
                return $_arr_userPass;
            }
            $this->apiMail["user_pass"] = $_arr_userPass["user_pass"];
        }

        $_arr_userMailNew = $this->chk_user_mail(fn_post("user_mail_new"), 1);
        if ($_arr_userMailNew["alert"] != "ok") {
            return $_arr_userMailNew;
        }
        $this->apiMail["user_mail_new"] = $_arr_userMailNew["user_mail"];

        $this->apiMail["alert"] = "ok";

        return $this->apiMail;
    }


    /**  api 验证访问口令
     * input_token_api function.
     *
     * @access public
     * @param string $str_method (default: "get")
     * @return void
     */
    function input_token_api($str_method = "get") {
        $_arr_userGet = $this->input_get_by($str_method);
        if ($_arr_userGet["alert"] != "ok") {
            return $_arr_userGet;
        }

        $_arr_userRequest = $_arr_userGet;

        if ($str_method == "post") {
            $str_accessToken    = fn_post("user_access_token");
        } else {
            $str_accessToken    = fn_get("user_access_token");
        }

        $_arr_accessToken = validateStr($str_accessToken, 1, 32);
        switch ($_arr_accessToken["status"]) {
            case "too_short":
                return array(
                    "alert" => "x010228",
                );
            break;

            case "too_long":
                return array(
                    "alert" => "x010229",
                );
            break;

            case "ok":
                $_arr_userRequest["user_access_token"] = $_arr_accessToken["str"];
            break;

        }

        $_arr_userRequest["alert"] = "ok";

        return $_arr_userRequest;
    }


    /** api 刷新访问口令表单验证
     * input_refresh_api function.
     *
     * @access public
     * @return void
     */
    function input_refresh_api() {
        $_arr_userGet = $this->input_get_by("post");
        if ($_arr_userGet["alert"] != "ok") {
            return $_arr_userGet;
        }

        $this->apiRefresh = $_arr_userGet;

        $_arr_refreshToken = validateStr(fn_post("user_refresh_token"), 1, 32);
        switch ($_arr_refreshToken["status"]) {
            case "too_short":
                return array(
                    "alert" => "x010232",
                );
            break;

            case "too_long":
                return array(
                    "alert" => "x010233",
                );
            break;

            case "ok":
                $this->apiRefresh["user_refresh_token"] = $_arr_refreshToken["str"];
            break;

        }

        $this->apiRefresh["alert"] = "ok";

        return $this->apiRefresh;
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
                "alert" => "x030206",
            );
        }

        $this->userSubmit["user_id"] = fn_getSafe(fn_post("user_id"), "int", 0);

        if ($this->userSubmit["user_id"] > 0) {
            //检查用户是否存在
            $_arr_userRow = $this->mdl_read_api($this->userSubmit["user_id"]);
            if ($_arr_userRow["alert"] != "y010102") {
                return $_arr_userRow;
            }
        }

        $_arr_userName = $this->chk_user_name(fn_post("user_name"));
        if ($_arr_userName["alert"] != "ok") {
            return $_arr_userName;
        }
        $this->userSubmit["user_name"] = $_arr_userName["user_name"];

        //检验用户名是否重复
        $_arr_userRowChk = $this->mdl_read($this->userSubmit["user_name"], "user_name", $this->userSubmit["user_id"]);
        if ($_arr_userRowChk["alert"] == "y010102") {
            return array(
                "alert" => "x010205",
            );
        }

        $_arr_userMail = $this->chk_user_mail(fn_post("user_mail"));
        if ($_arr_userMail["alert"] != "ok") {
            return $_arr_userMail;
        }
        $this->userSubmit["user_mail"] = $_arr_userMail["user_mail"];

        if ((BG_REG_ONEMAIL == "false" || BG_LOGIN_MAIL == "on") && $_arr_userMail["user_mail"]) {
            $_arr_userRowChk = $this->mdl_read($_arr_userMail["user_mail"], "user_mail", $this->userSubmit["user_id"]); //检查邮箱
            if ($_arr_userRowChk["alert"] == "y010102") {
                return array(
                    "alert" => "x010211",
                );
            }
        }

        $_arr_userNick = $this->chk_user_nick(fn_post("user_nick"));
        if ($_arr_userNick["alert"] != "ok") {
            return $_arr_userNick;
        }
        $this->userSubmit["user_nick"] = $_arr_userNick["user_nick"];

        $_arr_userNote = $this->chk_user_note(fn_post("user_note"));
        if ($_arr_userNote["alert"] != "ok") {
            return $_arr_userNote;
        }
        $this->userSubmit["user_note"] = $_arr_userNote["user_note"];

        $_arr_userStatus = validateStr(fn_post("user_status"), 1, 0);
        switch ($_arr_userStatus["status"]) {
            case "too_short":
                return array(
                    "alert" => "x010216",
                );
            break;

            case "ok":
                $this->userSubmit["user_status"] = $_arr_userStatus["str"];
            break;
        }


        $_arr_userContact = fn_post("user_contact");

        $this->userSubmit["user_contact"] = fn_jsonEncode($_arr_userContact, "encode");

        $this->userSubmit["alert"] = "ok";

        return $this->userSubmit;
    }


    /** 转换表单验证
     * input_convert function.
     *
     * @access public
     * @return void
     */
    function input_convert() {
        if (!fn_token("chk")) { //令牌
            return array(
                "alert" => "x030206",
            );
        }

        $this->userConvert["user_convert"] = fn_post("user_convert");

        if (!in_array("user_name", $this->userConvert["user_convert"])) {
            return array(
                "alert" => "x010220",
            );
        }

        if (!in_array("user_pass", $this->userConvert["user_convert"])) {
            return array(
                "alert" => "x010221",
            );
        }

        $this->userConvert["alert"]   = "ok";

        return $this->userConvert;
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
                "alert" => "x030206",
            );
        }

        $_arr_userIds = fn_post("user_ids");

        if ($_arr_userIds) {
            foreach ($_arr_userIds as $_key=>$_value) {
                $_arr_userIds[$_key] = fn_getSafe($_value, "int", 0);
            }
            $_str_alert = "ok";
        } else {
            $_str_alert = "x030202";
        }

        $this->userIds = array(
            "alert"      => $_str_alert,
            "user_ids"   => $_arr_userIds
        );

        return $this->userIds;
    }


    /** 验证用户 ID
     * chk_user_id function.
     *
     * @access private
     * @param mixed $num_id
     * @return void
     */
    private function chk_user_id($num_id) {
        $_arr_userId = validateStr($num_id, 1, 0, "str", "int");

        switch ($_arr_userId["status"]) {
            case "too_short":
                return array(
                    "alert" => "x010217",
                );
            break;

            case "format_err":
                return array(
                    "alert" => "x010218",
                );
            break;

            case "ok":
                $_num_userId = $_arr_userId["str"];
            break;
        }

        return array(
            "user_id"   => $_num_userId,
            "alert"     => "ok",
        );
    }


    /** 验证用户名
     * chk_user_name function.
     *
     * @access public
     * @param mixed $str_user
     * @return void
     */
    private function chk_user_name($str_name) {
        $_arr_userName = validateStr($str_name, 1, 30, "str", "strDigit");

        switch ($_arr_userName["status"]) {
            case "too_short":
                return array(
                    "alert" => "x010201",
                );
            break;

            case "too_long":
                return array(
                    "alert" => "x010202",
                );
            break;

            case "format_err":
                return array(
                    "alert" => "x010203",
                );
            break;

            case "ok":
                $_str_userName = $_arr_userName["str"];

                if (defined("BG_BAD_NAME") && strlen(BG_BAD_NAME)) {
                    if (fn_regChk($_str_userName, BG_BAD_NAME, true)) {
                        return array(
                            "alert" => "x010204",
                        );
                    }
                }
            break;
        }

        return array(
            "user_name"  => $_str_userName,
            "alert"      => "ok",
        );
    }


    /** 验证邮箱
     * chk_user_mail function.
     *
     * @access public
     * @param mixed $str_mail
     * @param mixed $num_mailMin
     * @return void
     */
    private function chk_user_mail($str_mail, $num_min = 0) {
        if (BG_REG_NEEDMAIL == "on" || BG_LOGIN_MAIL == "on" || $num_min > 0) {
            $_num_mailMin = 1;
        } else {
            $_num_mailMin = 0;
        }

        $_arr_userMail = validateStr($str_mail, $_num_mailMin, 300, "str", "email");

        switch ($_arr_userMail["status"]) {
            case "too_short":
                return array(
                    "alert" => "x010206",
                );
            break;

            case "too_long":
                return array(
                    "alert" => "x010207",
                );
            break;

            case "format_err":
                return array(
                    "alert" => "x010208",
                );
            break;

            case "ok":
                $_str_userMail = $_arr_userMail["str"];

                if (defined("BG_ACC_MAIL") && strlen(BG_ACC_MAIL) && $_str_userMail) {
                    if (!fn_regChk($_str_userMail, BG_ACC_MAIL)) {
                        return array(
                            "alert" => "x010209",
                        );
                    }
                } else if (defined("BG_BAD_MAIL") && strlen(BG_BAD_MAIL) && $_str_userMail) {
                    if (fn_regChk($_str_userMail, BG_BAD_MAIL)) {
                        return array(
                            "alert" => "x010210",
                        );
                    }
                }
            break;
        }

        return array(
            "user_mail"  => $_str_userMail,
            "alert"      => "ok",
        );
    }


    /** 验证密码
     * chk_user_pass function.
     *
     * @access public
     * @param mixed $str_pass
     * @return void
     */
    private function chk_user_pass($str_pass) {
        $_arr_userPass = validateStr($str_pass, 1, 0);
        switch ($_arr_userPass["status"]) {
            case "too_short":
                return array(
                    "alert" => "x010212",
                );
            break;

            case "ok":
                $_str_userPass = $_arr_userPass["str"];
            break;
        }

        return array(
            "user_pass"  => $_str_userPass,
            "alert"      => "ok",
        );
    }


    /** 验证昵称
     * chk_user_nick function.
     *
     * @access public
     * @param mixed $str_nick
     * @return void
     */
    private function chk_user_nick($str_nick) {
        $_arr_userNick = validateStr($str_nick, 0, 30);
        switch ($_arr_userNick["status"]) {
            case "too_long":
                return array(
                    "alert" => "x010214",
                );
            break;

            case "ok":
                $_str_userNick = $_arr_userNick["str"];
            break;

        }

        return array(
            "user_nick"  => $_str_userNick,
            "alert"      => "ok",
        );
    }


    /** 验证备注
     * chk_user_note function.
     *
     * @access public
     * @param mixed $str_note
     * @return void
     */
    private function chk_user_note($str_note) {
        $_arr_userNote = validateStr($str_note, 0, 30);
        switch ($_arr_userNote["status"]) {
            case "too_long":
                return array(
                    "alert" => "x010215",
                );
            break;

            case "ok":
                $_str_userNote = $_arr_userNote["str"];
            break;

        }

        return array(
            "user_note"  => $_str_userNote,
            "alert"      => "ok",
        );
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
            $_str_sqlWhere .= " AND (user_name LIKE '%" . $arr_search["key"] . "%' OR user_name LIKE '%" . $arr_search["key"] . "%' OR user_mail LIKE '%" . $arr_search["key"] . "%' OR user_note LIKE '%" . $arr_search["key"] . "%')";
        }

        if (isset($arr_search["key_name"]) && $arr_search["key_name"]) {
            $_str_sqlWhere .= " AND user_name LIKE '%" . $arr_search["key_name"] . "%'";
        }

        if (isset($arr_search["key_mail"]) && $arr_search["key_mail"]) {
            $_str_sqlWhere .= " AND user_mail LIKE '%" . $arr_search["key_mail"] . "%'";
        }

        if (isset($arr_search["begin_id"]) && $arr_search["begin_id"] > 0) {
            $_str_sqlWhere .= " AND user_id>=" . $arr_search["begin_id"];
        }

        if (isset($arr_search["end_id"]) && $arr_search["end_id"] > 0) {
            $_str_sqlWhere .= " AND user_id<=" . $arr_search["end_id"];
        }

        if (isset($arr_search["begin_time"]) && $arr_search["begin_time"] > 0) {
            $_str_sqlWhere .= " AND user_time>=" . $arr_search["begin_time"];
        }

        if (isset($arr_search["end_time"]) && $arr_search["end_time"] > 0) {
            $_str_sqlWhere .= " AND user_time<=" . $arr_search["end_time"];
        }

        if (isset($arr_search["begin_login"]) && $arr_search["begin_login"] > 0) {
            $_str_sqlWhere .= " AND user_time_login>=" . $arr_search["begin_login"];
        }

        if (isset($arr_search["end_login"]) && $arr_search["end_login"] > 0) {
            $_str_sqlWhere .= " AND user_time_login<=" . $arr_search["end_login"];
        }

        if (isset($arr_search["status"]) && $arr_search["status"]) {
            $_str_sqlWhere .= " AND user_status='" . $arr_search["status"] . "'";
        }

        if (isset($arr_search["user_names"]) && $arr_search["user_names"]) {
            $_str_userNames    = implode("','", $arr_search["user_names"]);
            $_str_sqlWhere .= " AND user_name IN ('" . $_str_userNames . "')";

        }

        return $_str_sqlWhere;
    }
}
