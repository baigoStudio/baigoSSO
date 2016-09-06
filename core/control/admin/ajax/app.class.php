<?php
/*-----------------------------------------------------------------
！！！！警告！！！！
以下为系统文件，请勿修改
-----------------------------------------------------------------*/

//不能非法包含或直接执行
if (!defined("IN_BAIGO")) {
    exit("Access Denied");
}

include_once(BG_PATH_FUNC . "http.func.php"); //载入模板类
include_once(BG_PATH_CLASS . "ajax.class.php"); //载入模板类
include_once(BG_PATH_CLASS . "sign.class.php"); //载入模板类
include_once(BG_PATH_MODEL . "app.class.php"); //载入管理帐号模型
include_once(BG_PATH_MODEL . "belong.class.php");
include_once(BG_PATH_MODEL . "user.class.php"); //载入管理帐号模型
include_once(BG_PATH_MODEL . "log.class.php"); //载入管理帐号模型

/*-------------用户控制器-------------*/
class AJAX_APP {

    private $adminLogged;
    private $obj_ajax;
    private $log;
    private $mdl_app;
    private $mdl_log;
    private $is_super = false;

    function __construct() { //构造函数
        $this->adminLogged  = $GLOBALS["adminLogged"]; //已登录用户信息
        $this->obj_ajax     = new CLASS_AJAX(); //获取界面类型
        $this->obj_ajax->chk_install();
        $this->log          = $this->obj_ajax->log; //初始化 AJAX 基对象
        $this->obj_sign     = new CLASS_SIGN();
        $this->mdl_app      = new MODEL_APP(); //设置用户模型
        $this->mdl_belong   = new MODEL_BELONG();
        $this->mdl_user     = new MODEL_USER(); //设置管理员模型
        $this->mdl_log      = new MODEL_LOG(); //设置管理员模型

        if ($this->adminLogged["alert"] != "y020102") { //未登录，抛出错误信息
            $this->obj_ajax->halt_alert($this->adminLogged["alert"]);
        }

        if ($this->adminLogged["admin_type"] == "super") {
            $this->is_super = true;
        }
    }


    function ajax_reset() {
        if (!isset($this->adminLogged["admin_allow"]["app"]["edit"]) && !$this->is_super) {
            $this->obj_ajax->halt_alert("x050303");
        }

        $_num_appId   = fn_getSafe(fn_post("app_id"), "int", 0);

        if ($_num_appId < 1) {
            return array(
                "alert" => "x050203",
            );
        }

        $_arr_appRow = $this->mdl_app->mdl_read($_num_appId);
        if ($_arr_appRow["alert"] != "y050102") {
            return $_arr_appRow;
        }

        $_arr_appRow  = $this->mdl_app->mdl_reset($_num_appId);

        $this->obj_ajax->halt_alert($_arr_appRow["alert"]);
    }


    function ajax_deauth() {
        if (!isset($this->adminLogged["admin_allow"]["app"]["edit"]) && !$this->is_super) {
            $this->obj_ajax->halt_alert("x050303");
        }

        $_arr_userIds = $this->mdl_user->input_ids();

        //print_r($_arr_userIds);

        if ($_arr_userIds["alert"] != "ok") {
            $this->obj_ajax->halt_alert($_arr_userIds["alert"]);
        }

        $_num_appId = fn_getSafe(fn_post("app_id"), "int", 0);

        if ($_num_appId < 1) {
            $this->obj_ajax->halt_alert("x050203");
        }

        $this->mdl_belong->mdl_del($_num_appId, 0, false, $_arr_userIds["user_ids"]);

        //$_arr_appRow     = $this->mdl_app->mdl_order();

        $this->obj_ajax->halt_alert("y070402");
    }


    function ajax_auth() {
        if (!isset($this->adminLogged["admin_allow"]["app"]["edit"]) && !$this->is_super) {
            $this->obj_ajax->halt_alert("x050303");
        }

        $_arr_userIds = $this->mdl_user->input_ids();

        if ($_arr_userIds["alert"] != "ok") {
            $this->obj_ajax->halt_alert($_arr_userIds["alert"]);
        }

        $_num_appId = fn_getSafe(fn_post("app_id"), "int", 0);

        if ($_num_appId < 1) {
            $this->obj_ajax->halt_alert("x050203");
        }

        foreach ($_arr_userIds["user_ids"] as $_key=>$_value) {
            $_arr_userRow = $this->mdl_user->mdl_read($_value);
            if ($_arr_userRow["alert"] == "y010102") {
                $this->mdl_belong->mdl_submit($_value, $_num_appId);
            }
        }

        //$_arr_appRow     = $this->mdl_app->mdl_order();

        $this->obj_ajax->halt_alert("y070401");
    }


    /**
     * ajax_notify function.
     *
     * @access public
     * @return void
     */
    function ajax_notify() {
        $_num_appId = fn_getSafe(fn_post("app_id_notify"), "int", 0);
        if ($_num_appId < 1) {
            $this->obj_ajax->halt_alert("x050203");
        }

        if (!isset($this->adminLogged["admin_allow"]["app"]["browse"]) && !$this->is_super) {
            $this->obj_ajax->halt_alert("x050301");
        }

        $_arr_appRow = $this->mdl_app->mdl_read($_num_appId);
        if ($_arr_appRow["alert"] != "y050102") {
            $this->obj_ajax->halt_alert($_arr_appRow["alert"]);
        }

        $_tm_time   = time();
        $_str_echo  = fn_rand();

        $_arr_data = array(
            "act_get"    => "test",
            "time"       => $_tm_time,
            "echostr"    => $_str_echo,
            "app_id"     => $_arr_appRow["app_id"],
            "app_key"    => $_arr_appRow["app_key"],
        );

        $_arr_data["signature"] = $this->obj_sign->sign_make($_arr_data);

        if (stristr($_arr_appRow["app_url_notify"], "?")) {
            $_str_conn = "&";
        } else {
            $_str_conn = "?";
        }

        $_arr_notify = fn_http($_arr_appRow["app_url_notify"] . $_str_conn . "mod=notify", $_arr_data, "get");

        if ($_arr_notify["ret"] == $_str_echo) {
            $_str_alert = "y050401";
        } else {
            $_str_alert = "x050401";

            $_arr_targets[] = array(
                "app_id" => $_num_appId,
            );
            $_str_targets    = json_encode($_arr_targets);
            $_str_notify     = fn_htmlcode($_arr_notify["ret"]);
            //exit($_str_notify);

            $_arr_logData = array(
                "log_targets"        => $_str_targets,
                "log_target_type"    => "app",
                "log_title"          => $this->log["app"]["notifyTest"],
                "log_result"         => $_str_notify,
                "log_type"           => "admin",
            );

            $this->mdl_log->mdl_submit($_arr_logData, $this->adminLogged["admin_id"]);
            //exit("test");
        }

        $this->obj_ajax->halt_alert($_str_alert);
    }


    /**
     * ajax_submit function.
     *
     * @access public
     * @return void
     */
    function ajax_submit() {
        $_arr_appSubmit = $this->mdl_app->input_submit();

        if ($_arr_appSubmit["alert"] != "ok") {
            $this->obj_ajax->halt_alert($_arr_appSubmit["alert"]);
        }

        if ($_arr_appSubmit["app_id"] > 0) {
            if (!isset($this->adminLogged["admin_allow"]["app"]["edit"]) && !$this->is_super) {
                $this->obj_ajax->halt_alert("x050303");
            }
        } else {
            if (!isset($this->adminLogged["admin_allow"]["app"]["add"]) && !$this->is_super) {
                $this->obj_ajax->halt_alert("x050302");
            }
        }

        $_arr_appRow = $this->mdl_app->mdl_submit();

        if ($_arr_appRow["alert"] == "y050101" || $_arr_appRow["alert"] == "y050103") {
            $_arr_targets[] = array(
                "app_id" => $_arr_appRow["app_id"],
            );
            $_str_targets = json_encode($_arr_targets);
            if ($_arr_appRow["alert"] == "y050101") {
                $_type = "add";
            } else {
                $_type = "edit";
            }
            $_str_appRow = json_encode($_arr_appRow);

            $_arr_logData = array(
                "log_targets"        => $_str_targets,
                "log_target_type"    => "app",
                "log_title"          => $this->log["app"][$_type],
                "log_result"         => $_str_appRow,
                "log_type"           => "admin",
            );

            $this->mdl_log->mdl_submit($_arr_logData, $this->adminLogged["admin_id"]);
        }

        $this->obj_ajax->halt_alert($_arr_appRow["alert"]);
    }


    /**
     * ajax_status function.
     *
     * @access public
     * @return void
     */
    function ajax_status() {
        if (!isset($this->adminLogged["admin_allow"]["app"]["edit"]) && !$this->is_super) {
            $this->obj_ajax->halt_alert("x050303");
        }

        $_str_status = fn_getSafe($GLOBALS["act_post"], "txt", "");

        $_arr_appIds = $this->mdl_app->input_ids();
        if ($_arr_appIds["alert"] != "ok") {
            $this->obj_ajax->halt_alert($_arr_appIds["alert"]);
        }

        $_arr_appRow = $this->mdl_app->mdl_status($_str_status);

        if ($_arr_appRow["alert"] == "y050103") {
            foreach ($_arr_appIds["app_ids"] as $_key=>$_value) {
                $_arr_targets[] = array(
                    "app_id" => $_value,
                );
                $_str_targets = json_encode($_arr_targets);
            }
            $_str_appRow = json_encode($_arr_appRow);

            $_arr_logData = array(
                "log_targets"        => $_str_targets,
                "log_target_type"    => "app",
                "log_title"          => $this->log["app"]["edit"],
                "log_result"         => $_str_appRow,
                "log_type"           => "admin",
            );

            $this->mdl_log->mdl_submit($_arr_logData, $this->adminLogged["admin_id"]);
        }

        $this->obj_ajax->halt_alert($_arr_appRow["alert"]);
    }


    /**
     * ajax_del function.
     *
     * @access public
     * @return void
     */
    function ajax_del() {
        if (!isset($this->adminLogged["admin_allow"]["app"]["del"]) && !$this->is_super) {
            $this->obj_ajax->halt_alert("x050304");
        }

        $_arr_appIds = $this->mdl_app->input_ids();
        if ($_arr_appIds["alert"] != "ok") {
            $this->obj_ajax->halt_alert($_arr_appIds["alert"]);
        }

        $_arr_appRow = $this->mdl_app->mdl_del();

        if ($_arr_appRow["alert"] == "y050104") {
            foreach ($_arr_appIds["app_ids"] as $_key=>$_value) {
                $_arr_targets[] = array(
                    "app_id" => $_value,
                );
                $_str_targets = json_encode($_arr_targets);
            }
            $_str_appRow = json_encode($_arr_appRow);

            $_arr_logData = array(
                "log_targets"        => $_str_targets,
                "log_target_type"    => "app",
                "log_title"          => $this->log["app"]["del"],
                "log_result"         => $_str_appRow,
                "log_type"           => "admin",
            );

            $this->mdl_log->mdl_submit($_arr_logData, $this->adminLogged["admin_id"]);
        }

        $this->obj_ajax->halt_alert($_arr_appRow["alert"]);
    }
}
