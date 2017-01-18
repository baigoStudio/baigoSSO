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
class CONTROL_CONSOLE_REQUEST_VERIFY {

    private $is_super = false;

    function __construct() { //构造函数
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

        $this->mdl_verify       = new MODEL_VERIFY();
        $this->mdl_user         = new MODEL_USER();
        $this->mdl_log          = new MODEL_LOG();
    }


    /*============更改用户状态============
    @arr_verifyId 用户 ID 数组
    @str_status 状态

    返回提示信息
    */
    function ctrl_status() {
        if (!isset($this->adminLogged["admin_allow"]["log"]["verify"]) && !$this->is_super) {
            $_arr_tplData = array(
                "rcode" => "x120301",
            );
            $this->obj_tpl->tplDisplay("result", $_arr_tplData);
        }

        $_str_status = fn_getSafe($GLOBALS["act"], "txt", "");
        if (fn_isEmpty($_str_status)) {
            $_arr_tplData = array(
                "rcode" => "x120206",
            );
            $this->obj_tpl->tplDisplay("result", $_arr_tplData);
        }

        $_arr_verifyIds = $this->mdl_verify->input_ids();
        if ($_arr_verifyIds["rcode"] != "ok") {
            $this->obj_tpl->tplDisplay("result", $_arr_verifyIds);
        }

        $_arr_verifyRow = $this->mdl_verify->mdl_status($_str_status);

        $this->obj_tpl->tplDisplay("result", $_arr_verifyRow);
    }

    /*============删除用户============
    @arr_verifyId 用户 ID 数组

    返回提示信息
    */
    function ctrl_del() {
        if (!isset($this->adminLogged["admin_allow"]["log"]["verify"]) && !$this->is_super) {
            $_arr_tplData = array(
                "rcode" => "x120301",
            );
            $this->obj_tpl->tplDisplay("result", $_arr_tplData);
        }

        $_arr_verifyIds = $this->mdl_verify->input_ids();
        if ($_arr_verifyIds["rcode"] != "ok") {
            $this->obj_tpl->tplDisplay("result", $_arr_verifyIds);
        }

        $_arr_verifyRow = $this->mdl_verify->mdl_del();

        if ($_arr_verifyRow["rcode"] == "y120104") {
            foreach ($_arr_verifyIds["verify_ids"] as $_key=>$_value) {
                $_arr_targets[] = array(
                    "verify_id" => $_value,
                );
                $_str_targets = json_encode($_arr_targets);
            }
            $_str_verifyRow = json_encode($_arr_verifyRow);

            $_arr_logData = array(
                "log_targets"        => $_str_targets,
                "log_target_type"    => "verify",
                "log_title"          => $this->log["verify"]["del"],
                "log_result"         => $_str_verifyRow,
                "log_type"           => "admin",
            );

            $this->mdl_log->mdl_submit($_arr_logData, "admin", $this->adminLogged["admin_id"]);
        }

        $this->obj_tpl->tplDisplay("result", $_arr_verifyRow);
    }
}
