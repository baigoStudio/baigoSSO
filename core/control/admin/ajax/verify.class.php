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
include_once(BG_PATH_MODEL . "verify.class.php"); //载入管理帐号模型

/*-------------用户控制器-------------*/
class AJAX_VERIFY {

    private $adminLogged;
    private $obj_ajax;
    private $mdl_verify;

    function __construct() { //构造函数
        $this->adminLogged  = $GLOBALS["adminLogged"]; //已登录用户信息
        $this->obj_ajax     = new CLASS_AJAX();
        $this->obj_ajax->chk_install();
        $this->log          = $this->obj_ajax->log;
        $this->mdl_verify   = new MODEL_VERIFY();

        if ($this->adminLogged["alert"] != "y020102") { //未登录，抛出错误信息
            $this->obj_ajax->halt_alert($this->adminLogged["alert"]);
        }
    }

    /*============更改用户状态============
    @arr_verifyId 用户 ID 数组
    @str_status 状态

    返回提示信息
    */
    function ajax_status() {
        if (!isset($this->adminLogged["admin_allow"]["log"]["verify"])) {
            $this->obj_ajax->halt_alert("x120301");
        }

        $_str_status = fn_getSafe($GLOBALS["act_post"], "txt", "");

        $_arr_verifyIds = $this->mdl_verify->input_ids();
        if ($_arr_verifyIds["alert"] != "ok") {
            $this->obj_ajax->halt_alert($_arr_verifyIds["alert"]);
        }

        $_arr_verifyRow = $this->mdl_verify->mdl_status($_str_status);

        $this->obj_ajax->halt_alert($_arr_verifyRow["alert"]);
    }

    /*============删除用户============
    @arr_verifyId 用户 ID 数组

    返回提示信息
    */
    function ajax_del() {
        if (!isset($this->adminLogged["admin_allow"]["log"]["verify"])) {
            $this->obj_ajax->halt_alert("x120301");
        }

        $_arr_verifyIds = $this->mdl_verify->input_ids();
        if ($_arr_verifyIds["alert"] != "ok") {
            $this->obj_ajax->halt_alert($_arr_verifyIds["alert"]);
        }

        $_arr_verifyRow = $this->mdl_verify->mdl_del();

        if ($_arr_verifyRow["alert"] == "y120104") {
            foreach ($_arr_verifyIds["verify_ids"] as $_key=>$_value) {
                $_arr_targets[] = array(
                    "verify_id" => $_value,
                );
                $_str_targets = json_encode($_arr_targets);
            }
            $_str_verifyRow = json_encode($_arr_verifyRow);
            $this->mdl_log->mdl_submit($_str_targets, "verify", $this->log["verify"]["del"], $_str_verifyRow, "admin", $this->adminLogged["admin_id"]);
        }

        $this->obj_ajax->halt_alert($_arr_verifyRow["alert"]);
    }
}
