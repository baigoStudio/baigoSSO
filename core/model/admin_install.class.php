<?php
/*-----------------------------------------------------------------
！！！！警告！！！！
以下为系统文件，请勿修改
-----------------------------------------------------------------*/

//不能非法包含或直接执行
if (!defined("IN_BAIGO")) {
    exit("Access Denied");
}

/*-------------管理员模型-------------*/
class MODEL_ADMIN_INSTALL extends MODEL_ADMIN {

    function __construct() { //构造函数
        $this->obj_db = $GLOBALS["obj_db"]; //设置数据库对象
    }


    /** api 创建验证
     * input_install_add_add function.
     *
     * @access public
     * @return void
     */
    function input_install_add() {
        if (!fn_token("chk")) { //令牌
            return array(
                "rcode" => "x030206",
            );
        }

        $_arr_adminName = validateStr(fn_post("admin_name"), 1, 30);
        switch ($_arr_adminName["status"]) {
            case "too_short":
                return array(
                    "rcode" => "x010201",
                );
            break;

            case "too_long":
                return array(
                    "rcode" => "x010202",
                );
            break;

            case "ok":
                $this->adminInput["admin_name"] = $_arr_adminName["str"];
            break;
        }

        $_arr_adminPass = validateStr(fn_post("admin_pass"), 1, 0);
        switch ($_arr_adminPass["status"]) {
            case "too_short":
                return array(
                    "rcode" => "x010212",
                );
            break;

            case "ok":
                $this->adminInput["admin_pass"] = $_arr_adminPass["str"];
            break;
        }

        $_arr_adminPassConfirm = validateStr(fn_post("admin_pass_confirm"), 1, 0);
        switch ($_arr_adminPassConfirm["status"]) {
            case "too_short":
                $_arr_tplData = array(
                    "rcode"     => "x010224",
                );
                $this->obj_tpl->tplDisplay("result", $_arr_tplData);
            break;

            case "ok":
                $_str_adminPassConfirm = $_arr_adminPassConfirm["str"];
            break;
        }

        if ($this->adminInput["admin_pass"] != $_str_adminPassConfirm) {
            $_arr_tplData = array(
                "rcode"     => "x010225",
            );
            $this->obj_tpl->tplDisplay("result", $_arr_tplData);
        }

        $this->adminInput["admin_nick"]    = $this->adminInput["admin_name"];
        $this->adminInput["admin_note"]    = $this->adminInput["admin_name"];
        $this->adminInput["admin_status"]  = "enable";
        $this->adminInput["admin_type"]    = "super";

        $this->adminInput["rcode"]       = "ok";

        return $this->adminInput;
    }


    function input_install_auth() {
        if (!fn_token("chk")) { //令牌
            return array(
                "rcode" => "x030206",
            );
        }

        $_arr_adminName = validateStr(fn_post("admin_name"), 1, 30);
        switch ($_arr_adminName["status"]) {
            case "too_short":
                return array(
                    "rcode" => "x010201",
                );
            break;

            case "too_long":
                return array(
                    "rcode" => "x010202",
                );
            break;

            case "ok":
                $this->adminInput["admin_name"] = $_arr_adminName["str"];
            break;
        }

        $this->adminInput["admin_nick"]    = $this->adminInput["admin_name"];
        $this->adminInput["admin_note"]    = $this->adminInput["admin_name"];
        $this->adminInput["admin_status"]  = "enable";
        $this->adminInput["admin_type"]    = "super";

        $this->adminInput["rcode"]       = "ok";

        return $this->adminInput;
    }


    function input_install_api() {
        $_arr_adminName = validateStr(fn_post("admin_name"), 1, 30);
        switch ($_arr_adminName["status"]) {
            case "too_short":
                return array(
                    "rcode" => "x010201",
                );
            break;

            case "too_long":
                return array(
                    "rcode" => "x010202",
                );
            break;

            case "ok":
                $this->adminInput["admin_name"] = $_arr_adminName["str"];
            break;
        }

        $_arr_adminPass = validateStr(fn_post("admin_pass"), 1, 0);
        switch ($_arr_adminPass["status"]) {
            case "too_short":
                return array(
                    "rcode" => "x010212",
                );
            break;

            case "ok":
                $this->adminInput["admin_pass"] = $_arr_adminPass["str"];
            break;
        }

        $this->adminInput["admin_nick"]    = $this->adminInput["admin_name"];
        $this->adminInput["admin_status"]  = "enable";
        $this->adminInput["admin_type"]    = "super";

        $this->adminInput["rcode"]       = "ok";

        return $this->adminInput;
    }
}
