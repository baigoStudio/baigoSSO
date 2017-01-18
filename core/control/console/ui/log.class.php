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
class CONTROL_CONSOLE_UI_LOG {

    private $is_super = false;

    function __construct() { //构造函数
        $this->obj_console      = new CLASS_CONSOLE();
        $this->obj_console->chk_install();

        $this->adminLogged      = $this->obj_console->ssin_begin(); //获取已登录信息
        $this->obj_console->is_admin($this->adminLogged);

        $this->obj_tpl          = $this->obj_console->obj_tpl;

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


    /*============编辑管理员界面============
    返回提示
    */
    function ctrl_show() {
        if (!isset($this->adminLogged["admin_allow"]["log"]["browse"]) && !$this->is_super) {
            $this->tplData["rcode"] = "x060301";
            $this->obj_tpl->tplDisplay("error", $this->tplData);
        }

        $_num_logId = fn_getSafe(fn_get("log_id"), "int", 0);
        if ($_num_logId < 1) {
            $this->tplData["rcode"] = "x060201";
            $this->obj_tpl->tplDisplay("error", $this->tplData);
        }

        $_arr_logRow = $this->mdl_log->mdl_read($_num_logId);

        if ($_arr_logRow["rcode"] != "y060102") {
            $this->tplData["rcode"] = $_arr_logRow["rcode"];
            $this->obj_tpl->tplDisplay("error", $this->tplData);
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


    /**
     * ctrl_list function.
     *
     * @access public
     */
    function ctrl_list() {
        if (!isset($this->adminLogged["admin_allow"]["log"]["browse"]) && !$this->is_super) {
            $this->tplData["rcode"] = "x060301";
            $this->obj_tpl->tplDisplay("error", $this->tplData);
        }

        $_arr_search = array(
            "key"            => fn_getSafe(fn_get("key"), "txt", ""),
            "type"           => fn_getSafe(fn_get("type"), "txt", ""),
            "status"         => fn_getSafe(fn_get("status"), "txt", ""),
            "level"          => fn_getSafe(fn_get("level"), "txt", ""),
            "operator_id"    => fn_getSafe(fn_get("operator_id"), "int", 0),
        );

        $_num_logCount    = $this->mdl_log->mdl_count($_arr_search);
        $_arr_page        = fn_page($_num_logCount); //取得分页数据
        $_str_query       = http_build_query($_arr_search);
        $_arr_logRows     = $this->mdl_log->mdl_list(BG_DEFAULT_PERPAGE, $_arr_page["except"], $_arr_search);

        foreach ($_arr_logRows as $_key=>$_value) {
            switch ($_value["log_type"]) {
                case "admin":
                    $_arr_logRows[$_key]["adminRow"] = $this->mdl_admin->mdl_read($_value["log_operator_id"]);
                break;
                case "app":
                    $_arr_logRows[$_key]["appRow"] = $this->mdl_app->mdl_read($_value["log_operator_id"]);
                break;
            }
        }

        $_arr_tpl = array(
            "query"      => $_str_query,
            "pageRow"    => $_arr_page,
            "search"     => $_arr_search,
            "logRows"    => $_arr_logRows,
        );

        $_arr_tplData = array_merge($this->tplData, $_arr_tpl);

        $this->obj_tpl->tplDisplay("log_list", $_arr_tplData);
    }
}
