<?php
/*-----------------------------------------------------------------
！！！！警告！！！！
以下为系统文件，请勿修改
-----------------------------------------------------------------*/

//不能非法包含或直接执行
if(!defined("IN_BAIGO")) {
    exit("Access Denied");
}

include_once(BG_PATH_CLASS . "tpl.class.php"); //载入模板类
include_once(BG_PATH_MODEL . "user.class.php"); //载入管理帐号模型
include_once(BG_PATH_MODEL . "verify.class.php"); //载入管理帐号模型

/*-------------管理员控制器-------------*/
class CONTROL_REG {

    private $obj_base;
    private $config; //配置
    private $obj_tpl;
    private $mdl_user;
    private $tplData;

    function __construct() { //构造函数
        $this->obj_base       = $GLOBALS["obj_base"]; //获取界面类型
        $this->config         = $this->obj_base->config;
        $this->mdl_user       = new MODEL_USER(); //设置管理员模型
        $this->mdl_verify     = new MODEL_VERIFY(); //设置管理员模型
        $_arr_cfg["user"]     = true;
        $this->obj_tpl        = new CLASS_TPL(BG_PATH_TPL . "user/" . BG_SITE_TPL, $_arr_cfg); //初始化视图对象
    }


    function ctl_confirm() {
        $_num_verifyId      = fn_getSafe(fn_get("verify_id"), "int", 0);
        $_str_verifyToken   = fn_getSafe(fn_get("verify_token"), "txt", "");

        if ($_num_verifyId < 1) {
            return array(
                "alert" => "x120201",
            );
        }

        if (!$_str_verifyToken) {
            return array(
                "alert" => "x120202",
            );
        }

        $_arr_verifyRow = $this->mdl_verify->mdl_read($_num_verifyId);
        if ($_arr_verifyRow["alert"] != "y120102") {
            return $_arr_verifyRow;
        }

        if ($_arr_verifyRow["verify_status"] != "enable") {
            return array(
                "alert" => "x120203",
            );
        }

        if ($_arr_verifyRow["verify_token_expire"] < time()) {
            return array(
                "alert" => "x120204",
            );
        }

        if (fn_baigoEncrypt($_arr_verifyRow["verify_token"], $_arr_verifyRow["verify_rand"]) != $_str_verifyToken) {
            return array(
                "alert" => "x120205",
            );
        }

        $_arr_userRow = $this->mdl_user->mdl_read($_arr_verifyRow["verify_user_id"]);
        if ($_arr_userRow["alert"] != "y010102") {
            return $_arr_userRow;
        }

        $_arr_verifyRow["verify_token"] = $_str_verifyToken;

        $_arr_tplData = array(
            "userRow"   => $_arr_userRow,
            "verifyRow" => $_arr_verifyRow,
        );

        $this->obj_tpl->tplDisplay("reg_confirm.tpl", $_arr_tplData);

        return array(
            "alert" => "y010102",
        );
    }


    function ctl_forgot() {
        $_num_verifyId      = fn_getSafe(fn_get("verify_id"), "int", 0);
        $_str_verifyToken   = fn_getSafe(fn_get("verify_token"), "txt", "");

        if ($_num_verifyId < 1) {
            return array(
                "alert" => "x120201",
            );
        }

        if (!$_str_verifyToken) {
            return array(
                "alert" => "x120202",
            );
        }

        $_arr_verifyRow = $this->mdl_verify->mdl_read($_num_verifyId);
        if ($_arr_verifyRow["alert"] != "y120102") {
            return $_arr_verifyRow;
        }

        if ($_arr_verifyRow["verify_status"] != "enable") {
            return array(
                "alert" => "x120203",
            );
        }

        if ($_arr_verifyRow["verify_token_expire"] < time()) {
            return array(
                "alert" => "x120204",
            );
        }

        if (fn_baigoEncrypt($_arr_verifyRow["verify_token"], $_arr_verifyRow["verify_rand"]) != $_str_verifyToken) {
            return array(
                "alert" => "x120205",
            );
        }

        $_arr_userRow = $this->mdl_user->mdl_read($_arr_verifyRow["verify_user_id"]);
        if ($_arr_userRow["alert"] != "y010102") {
            return $_arr_userRow;
        }

        $_arr_verifyRow["verify_token"] = $_str_verifyToken;

        $_arr_tplData = array(
            "userRow"   => $_arr_userRow,
            "verifyRow" => $_arr_verifyRow,
        );

        $this->obj_tpl->tplDisplay("reg_forgot.tpl", $_arr_tplData);

        return array(
            "alert" => "y010102",
        );
    }


    function ctl_mailbox() {
        $_num_verifyId      = fn_getSafe(fn_get("verify_id"), "int", 0);
        $_str_verifyToken   = fn_getSafe(fn_get("verify_token"), "txt", "");

        if ($_num_verifyId < 1) {
            return array(
                "alert" => "x120201",
            );
        }

        if (!$_str_verifyToken) {
            return array(
                "alert" => "x120202",
            );
        }

        $_arr_verifyRow = $this->mdl_verify->mdl_read($_num_verifyId);
        if ($_arr_verifyRow["alert"] != "y120102") {
            return $_arr_verifyRow;
        }

        if ($_arr_verifyRow["verify_status"] != "enable") {
            return array(
                "alert" => "x120203",
            );
        }

        if ($_arr_verifyRow["verify_token_expire"] < time()) {
            return array(
                "alert" => "x120204",
            );
        }

        if (fn_baigoEncrypt($_arr_verifyRow["verify_token"], $_arr_verifyRow["verify_rand"]) != $_str_verifyToken) {
            return array(
                "alert" => "x120205",
            );
        }

        $_arr_userRow = $this->mdl_user->mdl_read($_arr_verifyRow["verify_user_id"]);
        if ($_arr_userRow["alert"] != "y010102") {
            return $_arr_userRow;
        }

        $_arr_verifyRow["verify_token"] = $_str_verifyToken;

        $_arr_tplData = array(
            "userRow"   => $_arr_userRow,
            "verifyRow" => $_arr_verifyRow,
        );

        $this->obj_tpl->tplDisplay("reg_mailbox.tpl", $_arr_tplData);

        return array(
            "alert" => "y010102",
        );
    }


    function ctl_form() {
        $_str_alert = fn_getSafe(fn_get("alert"), "txt", "");

        $_arr_tplData = array(
            "alert" => $_str_alert,
        );

        $this->obj_tpl->tplDisplay("reg_form.tpl", $_arr_tplData);

        return array(
            "alert" => "y010102",
        );
    }

}
