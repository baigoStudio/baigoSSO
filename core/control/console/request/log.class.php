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
class CONTROL_CONSOLE_REQUEST_LOG {

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

        $this->mdl_log          = new MODEL_LOG(); //设置管理员模型
        $this->mdl_admin        = new MODEL_ADMIN(); //设置管理员模型
        $this->mdl_user         = new MODEL_USER(); //设置管理员模型
        $this->mdl_verify       = new MODEL_VERIFY(); //设置管理员模型
        $this->mdl_app          = new MODEL_APP(); //设置管理员模型
    }

    /*============更改用户状态============
    @arr_logId 用户 ID 数组
    @str_status 状态

    返回提示信息
    */
    function ctrl_status() {
        if (!isset($this->adminLogged["admin_allow"]["log"]["edit"]) && !$this->is_super) {
            $_arr_tplData = array(
                "rcode" => "x060303",
            );
            $this->obj_tpl->tplDisplay("result", $_arr_tplData);
        }

        $_str_status = fn_getSafe($GLOBALS["act"], "txt", "");
        if (fn_isEmpty($_str_status)) {
            $_arr_tplData = array(
                "rcode" => "x060202",
            );
            $this->obj_tpl->tplDisplay("result", $_arr_tplData);
        }

        $_arr_logIds = $this->mdl_log->input_ids();
        if ($_arr_logIds["rcode"] != "ok") {
            $this->obj_tpl->tplDisplay("result", $_arr_logIds);
        }

        $_arr_logRow = $this->mdl_log->mdl_status($_str_status);

        $this->obj_tpl->tplDisplay("result", $_arr_logRow);
    }

    /*============删除用户============
    @arr_logId 用户 ID 数组

    返回提示信息
    */
    function ctrl_del() {
        if (!isset($this->adminLogged["admin_allow"]["log"]["del"]) && !$this->is_super) {
            $_arr_tplData = array(
                "rcode" => "x060304",
            );
            $this->obj_tpl->tplDisplay("result", $_arr_tplData);
        }

        $_arr_logIds = $this->mdl_log->input_ids();
        if ($_arr_logIds["rcode"] != "ok") {
            $this->obj_tpl->tplDisplay("result", $_arr_logIds);
        }

        $_arr_logRow = $this->mdl_log->mdl_del();

        if ($_arr_logRow["rcode"] == "y060104") {
            foreach ($_arr_logIds["log_ids"] as $_key=>$_value) {
                $_arr_targets[] = array(
                    "log_id" => $_value,
                );
                $_str_targets = json_encode($_arr_targets);
            }
            $_str_logRow = json_encode($_arr_logRow);

            $_arr_logData = array(
                "log_targets"        => $_str_targets,
                "log_target_type"    => "log",
                "log_title"          => $this->log["log"]["del"],
                "log_result"         => $_str_logRow,
                "log_type"           => "admin",
            );

            $this->mdl_log->mdl_submit($_arr_logData, $this->adminLogged["admin_id"]);
        }

        $this->obj_tpl->tplDisplay("result", $_arr_logRow);
    }


    /*============编辑管理员界面============
    返回提示
    */
    function ctrl_show() {
        if (!isset($this->adminLogged["admin_allow"]["log"]["browse"]) && !$this->is_super) {
            $_arr_tplData = array(
                "rcode" => "x060301",
            );
            $this->obj_tpl->tplDisplay("result", $_arr_tplData);
        }

        $_num_logId = fn_getSafe(fn_get("log_id"), "int", 0);
        if ($_num_logId < 1) {
            $_arr_tplData = array(
                "rcode" => "x060201",
            );
            $this->obj_tpl->tplDisplay("result", $_arr_tplData);
        }

        $_arr_logRow = $this->mdl_log->mdl_read($_num_logId);

        if ($_arr_logRow["rcode"] != "y060102") {
            $this->obj_tpl->tplDisplay("result", $_arr_logRow);
        }

        foreach ($_arr_logRow["log_targets"] as $_key=>$_value) {
            switch ($_arr_logRow["log_target_type"]) {
                case "admin":
                    $_arr_logRow["log_targets"][$_key]["adminRow"] = $this->mdl_admin->mdl_read($_value["admin_id"]);
                break;

                case "user":
                    $_arr_logRow["log_targets"][$_key]["userRow"] = $this->mdl_user->mdl_read($_value["user_id"]);
                break;

                case "app":
                    $_arr_logRow["log_targets"][$_key]["appRow"] = $this->mdl_app->mdl_read($_value["app_id"]);
                break;

                case "verify":
                    $_arr_logRow["log_targets"][$_key]["verifyRow"] = $this->mdl_verify->mdl_read($_value["verify_id"]);
                break;

                case "log":
                    if (isset($_value["log_id"])) {
                        $_num_logId = $_value["log_id"];
                    } else {
                        $_num_logId = 0;
                    }
                    $_arr_logRow["log_targets"][$_key]["logRow"] = array(
                        "log_id"    => $_num_logId,
                        "log_name"  => "",
                    );
                break;
            }
        }

        switch ($_arr_logRow["log_type"]) {
            case "admin":
                $_arr_logRow["adminRow"] = $this->mdl_admin->mdl_read($_arr_logRow["log_operator_id"]);
            break;

            case "app":
                $_arr_logRow["appRow"] = $this->mdl_app->mdl_read($_arr_logRow["log_operator_id"]);
            break;
        }

        //print_r($_arr_logRow);

        $this->tplData["logRow"] = $_arr_logRow; //管理员信息

        $_arr_logRow = $this->mdl_log->mdl_isRead($_num_logId);

        $this->obj_tpl->tplDisplay("log_show", $this->tplData);
    }
}
