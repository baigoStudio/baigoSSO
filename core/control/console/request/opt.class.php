<?php
/*-----------------------------------------------------------------
！！！！警告！！！！
以下为系统文件，请勿修改
-----------------------------------------------------------------*/

//不能非法包含或直接执行
if (!defined("IN_BAIGO")) {
    exit("Access Denied");
}

require(BG_PATH_FUNC . "http.func.php"); //载入模板类

/*-------------管理员控制器-------------*/
class CONTROL_CONSOLE_REQUEST_OPT {

    private $is_super = false;

    function __construct() { //构造函数
        $this->obj_base         = $GLOBALS["obj_base"]; //获取界面类型
        $this->config           = $this->obj_base->config;

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

        $this->act = fn_getSafe($GLOBALS["act"], "text", "base");

        $this->mdl_opt          = new MODEL_OPT(); //设置管理组模型
        $this->mdl_log          = new MODEL_LOG(); //设置管理员模型
    }


    function ctrl_chkver() {
        if (!isset($this->adminLogged["admin_allow"]["opt"]["chkver"]) && !$this->is_super) {
            $_arr_tplData = array(
                "rcode" => "x040301",
            );
            $this->obj_tpl->tplDisplay("result", $_arr_tplData);
        }

        $this->mdl_opt->chk_ver(true, "manual");

        $_arr_tplData = array(
            "rcode" => "y040402",
        );
        $this->obj_tpl->tplDisplay("result", $_arr_tplData);
    }


    function ctrl_dbconfig() {
        if (!isset($this->adminLogged["admin_allow"]["opt"]["dbconfig"]) && !$this->is_super) {
            $_arr_tplData = array(
                "rcode" => "x040301",
            );
            $this->obj_tpl->tplDisplay("result", $_arr_tplData);
        }

        $_arr_dbconfigInput = $this->mdl_opt->input_dbconfig();

        if ($_arr_dbconfigInput["rcode"] != "ok") {
            $this->obj_tpl->tplDisplay("result", $_arr_dbconfigInput);
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

        $this->obj_tpl->tplDisplay("result", $_arr_return);
    }


    function ctrl_submit() {
        if (!isset($this->adminLogged["admin_allow"]["opt"][$this->act]) && !$this->is_super) {
            $_arr_tplData = array(
                "rcode" => "x040301",
            );
            $this->obj_tpl->tplDisplay("result", $_arr_tplData);
        }

        $_num_countSrc = 0;

        foreach ($this->obj_tpl->opt[$this->act]["list"] as $_key=>$_value) {
            if ($_value["min"] > 0) {
                $_num_countSrc++;
            }
        }

        $_arr_const = $this->mdl_opt->input_const($this->act);

        $_num_countInput = count(array_filter($_arr_const));

        if ($_num_countInput < $_num_countSrc) {
            $_arr_tplData = array(
                "rcode" => "x030204",
            );
            $this->obj_tpl->tplDisplay("result", $_arr_tplData);
        }

        $_arr_return = $this->mdl_opt->mdl_const($this->act);

        if ($_arr_return["rcode"] != "y040101") {
            $this->obj_tpl->tplDisplay("result", $_arr_return);
        }

        $_arr_targets[]   = $this->act;
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

        $_arr_tplData = array(
            "rcode" => "y040401",
        );
        $this->obj_tpl->tplDisplay("result", $_arr_tplData);
    }
}
