<?php
/*-----------------------------------------------------------------
！！！！警告！！！！
以下为系统文件，请勿修改
-----------------------------------------------------------------*/

//不能非法包含或直接执行
if(!defined("IN_BAIGO")) {
    exit("Access Denied");
}

include_once(BG_PATH_CLASS . "ajax.class.php"); //载入模板类
include_once(BG_PATH_MODEL . "user.class.php"); //载入管理帐号模型
include_once(BG_PATH_MODEL . "verify.class.php"); //载入管理帐号模型

/*-------------用户控制器-------------*/
class AJAX_REG {

    private $obj_ajax;
    private $mdl_user;
    private $mdl_log;

    function __construct() { //构造函数
        $this->obj_ajax       = new CLASS_AJAX(); //获取界面类型
        $this->obj_ajax->chk_install();
        $this->mdl_user       = new MODEL_USER(); //设置用户模型
        $this->mdl_verify     = new MODEL_VERIFY(); //设置管理员模型
    }


    function ajax_mailbox() {
        $_arr_verifySubmit = $this->mdl_verify->input_verify();
        if ($_arr_verifySubmit["alert"] != "ok") {
            $this->obj_ajax->halt_alert($_arr_verifySubmit["alert"]);
        }

        $_arr_verifyRow = $this->mdl_verify->mdl_read($_arr_verifySubmit["verify_id"]);
        if ($_arr_verifyRow["alert"] != "y120102") {
            $this->obj_ajax->halt_alert($_arr_verifyRow["alert"]);
        }

        if ($_arr_verifyRow["verify_status"] != "enable") {
            $this->obj_ajax->halt_alert("x120203");
        }

        if ($_arr_verifyRow["verify_token_expire"] < time()) {
            $this->obj_ajax->halt_alert("x120204");
        }

        if (fn_baigoEncrypt($_arr_verifyRow["verify_token"], $_arr_verifyRow["verify_rand"]) != $_arr_verifySubmit["verify_token"]) {
            $this->obj_ajax->halt_alert("x120205");
        }

        $_arr_userRow = $this->mdl_user->mdl_read($_arr_verifyRow["verify_user_id"]);
        if ($_arr_userRow["alert"] != "y010102") {
            $this->obj_ajax->halt_alert($_arr_userRow["alert"]);
        }

        if ($_arr_userRow["user_status"] != "enable") {
            $this->obj_ajax->halt_alert("x010401");
        }

        $_arr_returnRow = $this->mdl_user->mdl_mail($_arr_userRow["user_id"], $_arr_verifyRow["verify_mail"]);
        if ($_arr_returnRow["alert"] == "y010103") {
            $_str_alert = "y010405";
        } else {
            $_str_alert = "x010405";
        }

        $this->mdl_verify->mdl_disable();

        $this->obj_ajax->halt_alert($_str_alert);
    }



    /*============提交用户============
    返回数组
        user_id ID
        str_alert 提示信息
    */
    function ajax_forgot() {
        $_arr_verifySubmit = $this->mdl_verify->input_verify();

        if ($_arr_verifySubmit["alert"] != "ok") {
            $this->obj_ajax->halt_alert($_arr_verifySubmit["alert"]);
        }

        $_arr_verifyRow = $this->mdl_verify->mdl_read($_arr_verifySubmit["verify_id"]);
        if ($_arr_verifyRow["alert"] != "y120102") {
            $this->obj_ajax->halt_alert($_arr_verifyRow["alert"]);
        }

        if ($_arr_verifyRow["verify_status"] != "enable") {
            $this->obj_ajax->halt_alert("x120203");
        }

        if ($_arr_verifyRow["verify_token_expire"] < time()) {
            $this->obj_ajax->halt_alert("x120204");
        }

        if (fn_baigoEncrypt($_arr_verifyRow["verify_token"], $_arr_verifyRow["verify_rand"]) != $_arr_verifySubmit["verify_token"]) {
            $this->obj_ajax->halt_alert("x120205");
        }

        $_arr_userRow = $this->mdl_user->mdl_read($_arr_verifyRow["verify_user_id"]);
        if ($_arr_userRow["alert"] != "y010102") {
            $this->obj_ajax->halt_alert($_arr_userRow["alert"]);
        }

        if ($_arr_userRow["user_status"] == "disable") {
            $this->obj_ajax->halt_alert("x010401");
        }

        $_arr_userSubmit = $this->mdl_user->input_forgot_verify();
        if ($_arr_userSubmit["alert"] != "ok") {
            $this->obj_ajax->halt_alert($_arr_userSubmit["alert"]);
        }

        $_arr_returnRow = $this->mdl_user->mdl_forgot($_arr_userRow["user_id"]);
        if ($_arr_returnRow["alert"] == "y010103") {
            $_str_alert = "y010407";
        } else {
            $_str_alert = "x010407";
        }

        $this->mdl_verify->mdl_disable();

        $this->obj_ajax->halt_alert($_str_alert);
    }


    function ajax_confirm() {
        $_arr_verifySubmit = $this->mdl_verify->input_verify();

        if ($_arr_verifySubmit["alert"] != "ok") {
            $this->obj_ajax->halt_alert($_arr_verifySubmit["alert"]);
        }

        $_arr_verifyRow = $this->mdl_verify->mdl_read($_arr_verifySubmit["verify_id"]);
        if ($_arr_verifyRow["alert"] != "y120102") {
            $this->obj_ajax->halt_alert($_arr_verifyRow["alert"]);
        }

        if ($_arr_verifyRow["verify_status"] != "enable") {
            $this->obj_ajax->halt_alert("x120203");
        }

        if ($_arr_verifyRow["verify_token_expire"] < time()) {
            $this->obj_ajax->halt_alert("x120204");
        }

        if (fn_baigoEncrypt($_arr_verifyRow["verify_token"], $_arr_verifyRow["verify_rand"]) != $_arr_verifySubmit["verify_token"]) {
            $this->obj_ajax->halt_alert("x120205");
        }

        $_arr_userRow = $this->mdl_user->mdl_read($_arr_verifyRow["verify_user_id"]);
        if ($_arr_userRow["alert"] != "y010102") {
            $this->obj_ajax->halt_alert($_arr_userRow["alert"]);
        }

        $_arr_returnRow = $this->mdl_user->mdl_confirm($_arr_userRow["user_id"]);
        if ($_arr_returnRow["alert"] == "y010103") {
            $_str_alert = "y010409";
        } else {
            $_str_alert = "x010409";
        }

        $this->mdl_verify->mdl_disable();

        $this->obj_ajax->halt_alert($_str_alert);
    }
}