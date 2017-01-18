<?php
/*-----------------------------------------------------------------
！！！！警告！！！！
以下为系统文件，请勿修改
-----------------------------------------------------------------*/

//不能非法包含或直接执行
if (!defined("IN_BAIGO")) {
    exit("Access Denied");
}


/*-------------管理员控制器-------------*/
class CONTROL_CONSOLE_REQUEST_USER {

    private $is_super = false;

    function __construct() { //构造函数
        $this->obj_base         = $GLOBALS["obj_base"]; //获取界面类型
        $this->config           = $this->obj_base->config;

        $this->obj_dir          = new CLASS_DIR();
        $this->obj_console      = new CLASS_CONSOLE();
        $this->obj_console->dspType = "result";
        $this->obj_console->chk_install();

        $this->adminLogged      = $this->obj_console->ssin_begin(); //获取已登录信息
        $this->obj_console->is_admin($this->adminLogged);

        $this->obj_tpl          = $this->obj_console->obj_tpl;

        $this->log              = $this->obj_tpl->log;

        $this->tplData = array(
            "adminLogged" => $this->adminLogged
        );

        if ($this->adminLogged["admin_type"] == "super") {
            $this->is_super = true;
        }

        $this->mdl_user         = new MODEL_USER(); //设置管理员模型
        $this->mdl_log          = new MODEL_LOG(); //设置管理员模型

        $this->charsetRows              = require(BG_PATH_LANG . $this->config["lang"] . "/charset.php");
        $this->charsetOften             = array_keys($this->charsetRows["often"]["list"]);
        $this->charsetList              = array_keys($this->charsetRows["list"]["list"]);
        $this->charsetKeys              = array_unique(array_merge($this->charsetOften, $this->charsetList));
        $this->mdl_user->charsetKeys    = $this->charsetKeys;
    }


    function ctrl_convert() {
        if (!isset($this->adminLogged["admin_allow"]["user"]["import"]) && !$this->is_super) {
            $_arr_tplData = array(
                "rcode"     => "x010305",
            );
            $this->obj_tpl->tplDisplay("result", $_arr_tplData);
        }

        $_arr_userInput = $this->mdl_user->input_convert();
        if ($_arr_userInput["rcode"] != "ok") {
            $this->obj_tpl->tplDisplay("result", $_arr_userInput);
        }

        $_arr_userRow = $this->mdl_user->mdl_convert();

        $this->obj_tpl->tplDisplay("result", $_arr_userRow);
    }


    function ctrl_csvDel() {
        if (!isset($this->adminLogged["admin_allow"]["user"]["import"]) && !$this->is_super) {
            $_arr_tplData = array(
                "rcode"     => "x010305",
            );
            $this->obj_tpl->tplDisplay("result", $_arr_tplData);
        }

        if (!fn_token("chk")) { //令牌
            $_arr_tplData = array(
                "rcode"     => "x030206",
            );
            $this->obj_tpl->tplDisplay("result", $_arr_tplData);
        }

        $_bool = false;

        $_bool = $this->obj_dir->del_file(BG_PATH_CACHE . "sys/user_import.csv");

        if ($_bool) {
            $_str_rcode = "y010404";
        } else {
            $_str_rcode = "x010404";
        }

        $_arr_tplData = array(
            "rcode"     => $_str_rcode,
        );
        $this->obj_tpl->tplDisplay("result", $_arr_tplData);
    }


    function ctrl_import() {
        if (!isset($this->adminLogged["admin_allow"]["user"]["import"]) && !$this->is_super) {
            $_arr_tplData = array(
                "rcode"     => "x010305",
            );
            $this->obj_tpl->tplDisplay("result", $_arr_tplData);
        }

        $_arr_userImport = $this->validate_import();
        if ($_arr_userImport["rcode"] != "ok") {
            $_arr_userImport["file_name"] = $this->csvFiles["name"];
            $this->obj_tpl->tplDisplay("result", $_arr_userImport);
        }

        move_uploaded_file($this->userImport["file_temp"], BG_PATH_CACHE . "sys/user_import.csv");

        $_arr_tplData = array(
            "rcode"     => "y010403",
            "file_name" => $this->csvFiles["name"],
        );
        $this->obj_tpl->tplDisplay("result", $_arr_tplData);
    }


    /*============提交用户============
    返回数组
        user_id ID
        str_rcode 提示信息
    */
    function ctrl_submit() {
        $_arr_userInput  = $this->mdl_user->input_submit();

        $_arr_userSubmit = array();

        if ($_arr_userInput["rcode"] != "ok") {
            $this->obj_tpl->tplDisplay("result", $_arr_userInput);
        }

        if ($_arr_userInput["user_id"] > 0) {
            if (!isset($this->adminLogged["admin_allow"]["user"]["edit"]) && !$this->is_super) {
                $_arr_tplData = array(
                    "rcode" => "x010303",
                );
                $this->obj_tpl->tplDisplay("result", $_arr_tplData);
            }
            $_str_userPass = fn_getSafe(fn_post("user_pass"), "txt", "");
            if (!fn_isEmpty($_str_userPass)) {
                $_arr_userSubmit = array(
                    "user_pass" => fn_baigoCrypt($_str_userPass, $_arr_userInput["user_name"]),
                );
            }
        } else {
            if (!isset($this->adminLogged["admin_allow"]["user"]["add"]) && !$this->is_super) {
                $_arr_tplData = array(
                    "rcode" => "x010302",
                );
                $this->obj_tpl->tplDisplay("result", $_arr_tplData);
            }
            $_arr_userPass = validateStr(fn_post("user_pass"), 1, 0);
            switch ($_arr_userPass["status"]) {
                case "too_short":
                    $_arr_tplData = array(
                        "rcode" => "x010212",
                    );
                    $this->obj_tpl->tplDisplay("result", $_arr_tplData);
                break;

                case "ok":
                    $_str_userPass = $_arr_userPass["str"];
                break;
            }

            $_arr_userSubmit = array(
                "user_pass" => fn_baigoCrypt($_str_userPass, $_arr_userInput["user_name"]),
            );
        }

        $_arr_userRow = $this->mdl_user->mdl_submit($_arr_userSubmit);

        $this->obj_tpl->tplDisplay("result", $_arr_userRow);
    }

    /*============更改用户状态============
    @arr_userId 用户 ID 数组
    @str_status 状态

    返回提示信息
    */
    function ctrl_status() {
        if (!isset($this->adminLogged["admin_allow"]["user"]["edit"]) && !$this->is_super) {
            $_arr_tplData = array(
                "rcode" => "x010303",
            );
            $this->obj_tpl->tplDisplay("result", $_arr_tplData);
        }

        $_str_status = fn_getSafe($GLOBALS["act"], "txt", "");
        if (fn_isEmpty($_str_status)) {
            $_arr_tplData = array(
                "rcode" => "x010216",
            );
            $this->obj_tpl->tplDisplay("result", $_arr_tplData);
        }

        $_arr_userIds = $this->mdl_user->input_ids();
        if ($_arr_userIds["rcode"] != "ok") {
            $this->obj_tpl->tplDisplay("result", $_arr_userIds);
        }

        $_arr_userRow = $this->mdl_user->mdl_status($_str_status);

        $this->obj_tpl->tplDisplay("result", $_arr_userRow);
    }

    /*============删除用户============
    @arr_userId 用户 ID 数组

    返回提示信息
    */
    function ctrl_del() {
        if (!isset($this->adminLogged["admin_allow"]["user"]["del"]) && !$this->is_super) {
            $_arr_tplData = array(
                "rcode" => "x010304",
            );
            $this->obj_tpl->tplDisplay("result", $_arr_tplData);
        }

        $_arr_userIds = $this->mdl_user->input_ids();
        if ($_arr_userIds["rcode"] != "ok") {
            $this->obj_tpl->tplDisplay("result", $_arr_userIds);
        }

        $_arr_userRow = $this->mdl_user->mdl_del($_arr_userIds["user_ids"]);

        if ($_arr_userRow["rcode"] == "y010104") {
            foreach ($_arr_userIds["user_ids"] as $_key=>$_value) {
                $_arr_targets[] = array(
                    "user_id" => $_value,
                );
                $_str_targets = json_encode($_arr_targets);
            }
            $_str_userRow = json_encode($_arr_userRow);

            $_arr_logData = array(
                "log_targets"        => $_str_targets,
                "log_target_type"    => "log",
                "log_title"          => $this->log["user"]["del"],
                "log_result"         => $_str_userRow,
                "log_type"           => "admin",
            );

            $this->mdl_log->mdl_submit($_arr_logData, $this->adminLogged["admin_id"]);
        }

        $this->obj_tpl->tplDisplay("result", $_arr_userRow);
    }


    function ctrl_getname() {
        $_arr_userName = $this->mdl_user->input_name();

        if ($_arr_userName["rcode"] != "ok") {
            $this->obj_tpl->tplDisplay("result", $_arr_userName);
        }

        $_arr_userRow = $this->mdl_user->mdl_read($_arr_userName["user_name"], "user_name");

        if ($_arr_userRow["rcode"] != "y010102") {
            $this->obj_tpl->tplDisplay("result", $_arr_userRow);
        }

        $_arr_tplData = array(
            "msg" => "ok",
        );
        $this->obj_tpl->tplDisplay("result", $_arr_tplData);
    }

    /**
     * ctrl_chkname function.
     *
     * @access public
     * @return void
     */
    function ctrl_chkname() {
        $_arr_userName = $this->mdl_user->input_name();
        if ($_arr_userName["rcode"] != "ok") {
            $this->obj_tpl->tplDisplay("result", $_arr_userName);
        }

        $_num_notId = fn_getSafe(fn_get("user_id"), "int", 0);

        $_arr_userRow = $this->mdl_user->mdl_read($_arr_userName["user_name"], "user_name", $_num_notId);

        if ($_arr_userRow["rcode"] == "y010102") {
            $_arr_tplData = array(
                "rcode" => "x010205",
            );
            $this->obj_tpl->tplDisplay("result", $_arr_tplData);
        }

        $_arr_tplData = array(
            "msg" => "ok",
        );
        $this->obj_tpl->tplDisplay("result", $_arr_tplData);
    }

    /**
     * ctrl_chkmail function.
     *
     * @access public
     * @return void
     */
    function ctrl_chkmail() {
        $_arr_userMail = $this->mdl_user->input_mail();

        if ($_arr_userMail["rcode"] != "ok") {
            $this->obj_tpl->tplDisplay("result", $_arr_userMail);
        }

        if (!fn_isEmpty($_arr_userMail["user_mail"])) {
            $_arr_userRow = $this->mdl_user->mdl_read($_arr_userMail["user_mail"], "user_mail", $_arr_userMail["not_id"]);
            if ($_arr_userRow["rcode"] == "y010102") {
                $_arr_tplData = array(
                    "rcode" => "x010211",
                );
                $this->obj_tpl->tplDisplay("result", $_arr_tplData);
            }
        }

        $_arr_tplData = array(
            "msg" => "ok",
        );
        $this->obj_tpl->tplDisplay("result", $_arr_tplData);
    }


    private function validate_import() {
        if (!fn_token("chk")) { //令牌
            return array(
                "rcode" => "x030206",
            );
        }

        $this->csvFiles = $_FILES["csv_files"];

        $_str_rcode = $this->upload_init($this->csvFiles["error"]);
        if ($_str_rcode != "ok") {
            return array(
                "rcode" => $_str_rcode,
            );
        }

        $this->userImport["file_ext"] = pathinfo($this->csvFiles["name"], PATHINFO_EXTENSION); //取得扩展名
        $this->userImport["file_ext"] = strtolower($this->userImport["file_ext"]);

        if ($this->userImport["file_ext"] != "csv") {
            return array(
                "rcode" => "x010219",
            );
        }

        $this->userImport["file_temp"]    = $this->csvFiles["tmp_name"];
        $this->userImport["rcode"]        = "ok";

        return $this->userImport;
    }


    private function upload_init($num_error) {
        switch ($num_error) { //返回错误
            case 1:
                $_str_rcode = "x030301";
            break;
            case 2:
                $_str_rcode = "x030302";
            break;
            case 3:
                $_str_rcode = "x030303";
            break;
            case 4:
                $_str_rcode = "x030304";
            break;
            case 6:
                $_str_rcode = "x030306";
            break;
            case 7:
                $_str_rcode = "x030307";
            break;
            default:
                $_str_rcode = "ok";
            break;
        }
        return $_str_rcode;
    }
}
