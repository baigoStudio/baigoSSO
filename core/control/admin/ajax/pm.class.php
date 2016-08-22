<?php
/*-----------------------------------------------------------------
！！！！警告！！！！
以下为系统文件，请勿修改
-----------------------------------------------------------------*/

//不能非法包含或直接执行
if (!defined("IN_BAIGO")) {
    exit("Access Denied");
}

include_once(BG_PATH_CLASS . "ajax.class.php"); //载入 AJAX 基类
include_once(BG_PATH_MODEL . "pm.class.php"); //载入管理帐号模型
include_once(BG_PATH_MODEL . "user.class.php"); //载入管理帐号模型

/*-------------管理员控制器-------------*/
class AJAX_PM {

    private $adminLogged;
    private $obj_ajax;
    private $log;
    private $mdl_pm;
    private $mdl_log;
    private $is_super = false;

    function __construct() { //构造函数
        $this->adminLogged = $GLOBALS["adminLogged"]; //已登录商家信息
        $this->obj_ajax = new CLASS_AJAX(); //初始化 AJAX 基对象
        $this->obj_ajax->chk_install();
        $this->mdl_pm   = new MODEL_PM();
        $this->mdl_user = new MODEL_USER();

        if ($this->adminLogged["alert"] != "y020102") { //未登录，抛出错误信息
            $this->obj_ajax->halt_alert($this->adminLogged["alert"]);
        }

        if ($this->adminLogged["admin_type"] == "super") {
            $this->is_super = true;
        }
    }


    /**
     * ajax_submit function.
     *
     * @access public
     * @return void
     */
    function ajax_send() {
        if (!isset($this->adminLogged["admin_allow"]["pm"]["send"]) && !$this->is_super) {
            $this->obj_ajax->halt_alert("x110303");
        }

        $_arr_pmSend = $this->mdl_pm->input_send();

        if ($_arr_pmSend["alert"] != "ok") {
            $this->obj_ajax->halt_alert($_arr_pmSend["alert"]);
        }

        $_arr_userRow = $this->mdl_user->mdl_read($_arr_pmSend["pm_to"], "user_name");
        if ($_arr_userRow["alert"] != "y010102") {
            $this->obj_ajax->halt_alert($_arr_userRow["alert"]);
        }

        $_arr_pmRow = $this->mdl_pm->mdl_submit($_arr_userRow["user_id"], -1);

        $this->obj_ajax->halt_alert($_arr_pmRow["alert"]);
    }


    function ajax_bulk() {
        if (!isset($this->adminLogged["admin_allow"]["pm"]["bulk"]) && !$this->is_super) {
            $this->obj_ajax->halt_alert("x110303");
        }

        $_arr_pmBulk = $this->mdl_pm->input_bulk();

        if ($_arr_pmBulk["alert"] != "ok") {
            $this->obj_ajax->halt_alert($_arr_pmBulk["alert"]);
        }

        switch ($_arr_pmBulk["pm_bulk_type"]) {
            case "bulkUsers":
                if (stristr($_arr_pmBulk["pm_to_users"], "|")) {
                    $_arr_toUsers = explode("|", $_arr_pmBulk["pm_to_users"]);
                } else {
                    $_arr_toUsers = array($_arr_pmBulk["pm_to_users"]);
                }
                $_arr_search = array(
                    "user_names" => $_arr_toUsers,
                );
                $_arr_userRows = $this->mdl_user->mdl_list(1000, 0, $_arr_search);
            break;

            case "bulkKeyName":
                $_arr_search = array(
                    "key_name" => $_arr_pmBulk["pm_to_key_name"],
                );
                $_arr_userRows = $this->mdl_user->mdl_list(1000, 0, $_arr_search);
            break;

            case "bulkKeyMail":
                $_arr_search = array(
                    "key_mail" => $_arr_pmBulk["pm_to_key_mail"],
                );
                $_arr_userRows = $this->mdl_user->mdl_list(1000, 0, $_arr_search);
            break;

            case "bulkRangeId":
                $_arr_search = array(
                    "min_id"  => $_arr_pmBulk["pm_to_min_id"],
                    "max_id"    => $_arr_pmBulk["pm_to_max_id"],
                );
                $_arr_userRows = $this->mdl_user->mdl_list(1000, 0, $_arr_search);
            break;

            case "bulkRangeTime":
                $_arr_search = array(
                    "begin_time"  => $_arr_pmBulk["pm_to_begin_time"],
                    "end_time"    => $_arr_pmBulk["pm_to_end_time"],
                );
                $_arr_userRows = $this->mdl_user->mdl_list(1000, 0, $_arr_search);
            break;

            case "bulkRangeLogin":
                $_arr_search = array(
                    "begin_login"  => $_arr_pmBulk["pm_to_begin_login"],
                    "end_login"    => $_arr_pmBulk["pm_to_end_login"],
                );
                $_arr_userRows = $this->mdl_user->mdl_list(1000, 0, $_arr_search);
            break;
        }

        foreach ($_arr_userRows as $_key=>$value) {
            $_arr_pmRow = $this->mdl_pm->mdl_submit($value["user_id"], -1);
        }

        $this->obj_ajax->halt_alert($_arr_pmRow["alert"]);
    }


    /**
     * ajax_status function.
     *
     * @access public
     * @return void
     */
    function ajax_status() {
        if (!isset($this->adminLogged["admin_allow"]["pm"]["edit"]) && !$this->is_super) {
            $this->obj_ajax->halt_alert("x110305");
        }

        $_str_status = fn_getSafe($GLOBALS["act_post"], "txt", "");

        $_arr_pmIds = $this->mdl_pm->input_ids();
        if ($_arr_pmIds["alert"] != "ok") {
            $this->obj_ajax->halt_alert($_arr_pmIds["alert"]);
        }

        $_arr_pmRow = $this->mdl_pm->mdl_status($_str_status);

        $this->obj_ajax->halt_alert($_arr_pmRow["alert"]);
    }


    /**
     * ajax_del function.
     *
     * @access public
     * @return void
     */
    function ajax_del() {
        if (!isset($this->adminLogged["admin_allow"]["pm"]["del"]) && !$this->is_super) {
            $this->obj_ajax->halt_alert("x110304");
        }

        $_arr_pmIds = $this->mdl_pm->input_ids();
        if ($_arr_pmIds["alert"] != "ok") {
            $this->obj_ajax->halt_alert($_arr_pmIds["alert"]);
        }

        $_arr_pmRow = $this->mdl_pm->mdl_del();

        $this->obj_ajax->halt_alert($_arr_pmRow["alert"]);
    }
}
