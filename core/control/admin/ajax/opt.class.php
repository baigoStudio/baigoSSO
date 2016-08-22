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
include_once(BG_PATH_CLASS . "ajax.class.php"); //载入 AJAX 基类
include_once(BG_PATH_MODEL . "opt.class.php"); //载入管理帐号模型
include_once(BG_PATH_MODEL . "log.class.php"); //载入管理帐号模型

/*-------------管理员控制器-------------*/
class AJAX_OPT {

    private $adminLogged;
    private $obj_ajax;
    private $mdl_opt;
    private $mdl_log;
    private $is_super = false;

    function __construct() { //构造函数
        $this->obj_base     = $GLOBALS["obj_base"]; //获取界面类型
        $this->adminLogged  = $GLOBALS["adminLogged"]; //已登录商家信息
        $this->obj_ajax     = new CLASS_AJAX(); //初始化 AJAX 基对象
        $this->obj_ajax->chk_install();
        $this->log          = $this->obj_ajax->log; //初始化 AJAX 基对象
        $this->mdl_opt      = new MODEL_OPT(); //设置管理组模型
        $this->mdl_log      = new MODEL_LOG(); //设置管理员模型

        if ($this->adminLogged["alert"] != "y020102") { //未登录，抛出错误信息
            $this->obj_ajax->halt_alert($this->adminLogged["alert"]);
        }

        if ($this->adminLogged["admin_type"] == "super") {
            $this->is_super = true;
        }
    }


    function ajax_chkver() {
        if (!isset($this->adminLogged["admin_allow"]["opt"]["chkver"]) && !$this->is_super) {
            $this->obj_ajax->halt_alert("x040301");
        }

        $this->mdl_opt->chk_ver(true, "manual");

        $this->obj_ajax->halt_alert("y040402");
    }


    function ajax_dbconfig() {
        if (!isset($this->adminLogged["admin_allow"]["opt"]["dbconfig"]) && !$this->is_super) {
            $this->obj_ajax->halt_alert("x040301");
        }

        $_arr_dbconfigSubmit = $this->mdl_opt->input_dbconfig();

        if ($_arr_dbconfigSubmit["alert"] != "ok") {
            $this->obj_ajax->halt_alert($_arr_dbconfigSubmit["alert"]);
        }

        $_arr_return = $this->mdl_opt->mdl_dbconfig();

        $_arr_targets[]   = "dbconfig";
        $_str_targets     = json_encode($_arr_targets);
        $_str_return      = json_encode($_arr_return);

        $_arr_logData = array(
            "log_targets"        => $_str_targets,
            "log_target_type"    => "log",
            "log_title"          => $this->log["opt"]["edit"],
            "log_result"         => $_str_return,
            "log_type"           => "admin",
        );

        $this->mdl_log->mdl_submit($_arr_logData, $this->adminLogged["admin_id"]);

        $this->obj_ajax->halt_alert($_arr_return["alert"]);
    }


    function ajax_submit() {
        $_act_post = fn_getSafe($GLOBALS["act_post"], "txt", "base");

        if (!isset($this->adminLogged["admin_allow"]["opt"][$_act_post]) && !$this->is_super) {
            $this->obj_ajax->halt_alert("x040301");
        }

        $_num_countSrc = 0;

        foreach ($this->obj_ajax->opt[$_act_post]["list"] as $_key=>$_value) {
            if ($_value["min"] > 0) {
                $_num_countSrc++;
            }
        }

        $_arr_const = $this->mdl_opt->input_const($_act_post);

        $_num_countInput = count(array_filter($_arr_const));

        if ($_num_countInput < $_num_countSrc) {
            $this->obj_ajax->halt_alert("x030204");
        }

        $_arr_return = $this->mdl_opt->mdl_const($_act_post);

        if ($_arr_return["alert"] != "y040101") {
            $this->obj_ajax->halt_alert($_arr_return["alert"]);
        }

        $_arr_targets[]   = $_act_post;
        $_str_targets     = json_encode($_arr_targets);
        $_str_return      = json_encode($_arr_return);

        $_arr_logData = array(
            "log_targets"        => $_str_targets,
            "log_target_type"    => "log",
            "log_title"          => $this->log["opt"]["edit"],
            "log_result"         => $_str_return,
            "log_type"           => "admin",
        );

        $this->mdl_log->mdl_submit($_arr_logData, $this->adminLogged["admin_id"]);

        $this->obj_ajax->halt_alert("y040401");
    }
}