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
class CONTROL_API_SETUP {

    function __construct() { //构造函数
        $this->obj_api      = new CLASS_API(true);

        //本接口只在安装状态下起作用
        if (file_exists(BG_PATH_CONFIG . "installed.php")) { //如果新文件存在
            $_arr_return = array(
                "rcode" => "x030403"
            );
            $this->obj_api->show_result($_arr_return);
        } else if (file_exists(BG_PATH_CONFIG . "is_install.php")) { //如果旧文件存在
            $this->obj_dir->copy_file(BG_PATH_CONFIG . "is_install.php", BG_PATH_CONFIG . "installed.php"); //拷贝
            $_arr_return = array(
                "rcode" => "x030403"
            );
            $this->obj_api->show_result($_arr_return);
        }

        $this->mdl_opt      = new MODEL_OPT();

        $this->setup_init();
    }


    function ctrl_dbconfig() {
        $_arr_dbconfigInput = $this->mdl_opt->input_dbconfig(false);
        if ($_arr_dbconfigInput["rcode"] != "ok") {
            $this->obj_api->show_result($_arr_dbconfigInput);
        }

        $_arr_return = $this->mdl_opt->mdl_dbconfig();

        $this->obj_api->show_result($_arr_return);
    }


    function ctrl_submit() {
        $_act = fn_getSafe($GLOBALS["act"], "txt", "base");

        $this->check_db();

        $_num_countSrc = 0;

        foreach ($this->obj_api->opt[$_act]["list"] as $_key=>$_value) {
            if ($_value["min"] > 0) {
                $_num_countSrc++;
            }
        }

        $_arr_const = $this->mdl_opt->input_const($_act, false);

        $_num_countInput = count(array_filter($_arr_const));

        if ($_num_countInput < $_num_countSrc) {
            $_arr_return = array(
                "rcode" => "x030204"
            );
            $this->obj_api->show_result($_arr_return);
        }

        $_arr_return = $this->mdl_opt->mdl_const($_act);

        $this->obj_api->show_result($_arr_return);
    }


    function ctrl_dbtable() {
        $this->check_db();

        $this->table_admin();
        $this->table_user();
        $this->table_app();
        $this->table_belong();
        $this->table_log();
        $this->table_session();
        $this->table_verify();
        $this->table_pm();
        $this->view_user();

        $_arr_return = array(
            "rcode" => "y030108"
        );
        $this->obj_api->show_result($_arr_return);
    }


    function ctrl_admin() {
        $this->check_db();

        $_mdl_admin_install = new MODEL_ADMIN_INSTALL();
        $_mdl_user_api      = new MODEL_USER_API();

        $_arr_adminInput  = $_mdl_admin_install->input_install_api();
        if ($_arr_adminInput["rcode"] != "ok") {
            $this->obj_api->show_result($_arr_adminInput);
        }

        //检验用户名是否重复
        $_arr_userRow = $_mdl_user_api->mdl_read($_arr_adminInput["admin_name"], "user_name");

        if ($_arr_userRow["rcode"] == "y010102") {
            $_arr_adminRow = $_mdl_admin_install->mdl_read($_arr_userRow["user_id"]);
            if ($_arr_adminRow["rcode"] == "y020102") {
                $_arr_tplData = array(
                    "rcode" => "x020205",
                );
                $this->obj_api->show_result($_arr_tplData);
            } else {
                $_arr_tplData = array(
                    "rcode" => "x020206",
                );
                $this->obj_api->show_result($_arr_tplData);
            }
        }

        $_arr_userSubmit = array(
            "user_name"     => $_arr_adminInput["admin_name"],
            "user_pass"     => fn_baigoCrypt($_arr_adminInput["admin_pass"], $_arr_adminInput["admin_name"], true),
            "user_status"   => $_arr_adminInput["admin_status"],
            "user_nick"     => $_arr_adminInput["admin_nick"],
            "user_note"     => $_arr_adminInput["admin_note"],
        );

        $_arr_userRow     = $_mdl_user_api->mdl_reg($_arr_userSubmit);

        if ($_arr_userRow["rcode"] != "y010101") {
            $this->obj_api->show_result($_arr_userRow);
        }

        $_arr_adminInput["admin_id"]    = $_arr_userRow["user_id"];

        $_arr_adminReturn               = $_mdl_admin_install->mdl_submit($_arr_adminInput);

        $_arr_adminReturn["user_id"]    = $_arr_userRow["user_id"];
        $_arr_adminReturn["rcode"]      = $_arr_userRow["rcode"];

        $this->obj_api->show_result($_arr_adminReturn);
    }


    function ctrl_over() {
        $this->check_db();

        $this->record_app();

        $_arr_return = $this->mdl_opt->mdl_over();

        if ($_arr_return["rcode"] != "y030405") {
            $this->obj_api->show_result($_arr_return);
        }

        $this->appRecord["sso_url"]   = BG_SITE_URL . BG_URL_API . "api.php";
        $this->appRecord["rcode"]     = "y030408";
        $this->obj_api->show_result($this->appRecord);
    }


    private function table_admin() {
        $_mdl_admin                 = new MODEL_ADMIN();
        $_mdl_admin->adminStatus    = $this->obj_api->status["admin"];
        $_mdl_admin->adminTypes     = $this->obj_api->type["admin"];
        $_arr_adminTable            = $_mdl_admin->mdl_create_table();

        if ($_arr_adminTable["rcode"] != "y020105") {
            $this->obj_api->show_result($_arr_adminTable);
        }
    }


    private function table_user() {
        $_mdl_user              = new MODEL_USER();
        $_mdl_user->userStatus  = $this->obj_api->status["user"];
        $_arr_userTable         = $_mdl_user->mdl_create_table();

        if ($_arr_userTable["rcode"] != "y010105") {
            $this->obj_api->show_result($_arr_userTable);
        }
    }


    private function table_app() {
        $_mdl_app               = new MODEL_APP();
        $_mdl_app->appStatus    = $this->obj_api->status["app"];
        $_mdl_app->appSyncs     = $this->obj_api->status["appSync"];
        $_arr_appTable          = $_mdl_app->mdl_create_table();

        if ($_arr_appTable["rcode"] != "y050105") {
            $this->obj_api->show_result($_arr_appTable);
        }
    }


    private function table_belong() {
        $_mdl_belong       = new MODEL_BELONG();
        $_arr_belongTable  = $_mdl_belong->mdl_create_table();

        if ($_arr_belongTable["rcode"] != "y070105") {
            $this->obj_api->show_result($_arr_belongTable);
        }
    }


    private function table_log() {
        $_mdl_log               = new MODEL_LOG();
        $_mdl_log->logStatus    = $this->obj_api->status["log"];
        $_mdl_log->logTypes     = $this->obj_api->type["log"];
        $_mdl_log->logTargets   = $this->obj_api->type["logTarget"];
        $_arr_logTable          = $_mdl_log->mdl_create_table();

        if ($_arr_logTable["rcode"] != "y060105") {
            $this->obj_api->show_result($_arr_logTable);
        }
    }


    private function table_session() {
        $_mdl_session         = new MODEL_SESSION();
        $_arr_sessionTable    = $_mdl_session->mdl_create_table();

        $this->tplData["db_rcode"]["session_table"] = array(
            "rcode"   => $_arr_sessionTable["rcode"],
            "status"  => substr($_arr_sessionTable["rcode"], 0, 1),
        );
    }


    private function table_verify() {
        $_mdl_verify                = new MODEL_VERIFY();
        $_mdl_verify->verifyStatus  = $this->obj_api->status["verify"];
        $_mdl_verify->verifyTypes   = $this->obj_api->type["verify"];
        $_arr_verifyTable           = $_mdl_verify->mdl_create_table();

        $this->tplData["db_rcode"]["verify_table"] = array(
            "rcode"   => $_arr_verifyTable["rcode"],
            "status"  => substr($_arr_verifyTable["rcode"], 0, 1),
        );
    }


    private function table_pm() {
        $_mdl_pm            = new MODEL_PM();
        $_mdl_pm->pmStatus  = $this->obj_api->status["pm"];
        $_mdl_pm->pmTypes   = $this->obj_api->type["pm"];
        $_arr_pmTable       = $_mdl_pm->mdl_create_table();

        $this->tplData["db_rcode"]["pm_table"] = array(
            "rcode"   => $_arr_pmTable["rcode"],
            "status"  => substr($_arr_pmTable["rcode"], 0, 1),
        );
    }


    private function view_user() {
        $_mdl_user        = new MODEL_USER();
        $_arr_userView    = $_mdl_user->mdl_create_view();

        if ($_arr_userView["rcode"] != "y010108") {
            $this->obj_api->show_result($_arr_userView);
        }
    }

    private function record_app() {
        $_mdl_app           = new MODEL_APP();
        $_arr_appInputApi   = $_mdl_app->input_setup();

        if ($_arr_appInputApi["rcode"] != "ok") {
            $this->obj_api->show_result($_arr_appInputApi);
        }

        foreach ($this->obj_api->allow as $_key=>$_value) {
            foreach ($_value as $_key_sub=>$_value_sub) {
                $_arr_appAllow[$_key][$_key_sub] = 1;
            }
        }

        $this->appRecord = $_mdl_app->mdl_submit($_arr_appAllow);

        if ($this->appRecord["rcode"] != "y050101") {
            $this->obj_api->show_result($this->appRecord);
        }
    }


    private function check_db() {
        if (fn_isEmpty(BG_DB_HOST) || fn_isEmpty(BG_DB_NAME) || fn_isEmpty(BG_DB_USER) || fn_isEmpty(BG_DB_PASS) || fn_isEmpty(BG_DB_CHARSET)) {
            $_arr_return = array(
                "rcode" => "x030404"
            );
            $this->obj_api->show_result($_arr_return);
        }
    }


    private function setup_init() {
        $_str_rcode = "";

        if (file_exists(BG_PATH_CONFIG . "installed.php")) { //如果新文件存在
            require(BG_PATH_CONFIG . "installed.php");  //载入
            $_str_rcode = "x030403";
        } else if (file_exists(BG_PATH_CONFIG . "is_install.php")) { //如果旧文件存在
            $this->obj_dir->copy_file(BG_PATH_CONFIG . "is_install.php", BG_PATH_CONFIG . "installed.php"); //拷贝
            require(BG_PATH_CONFIG . "installed.php");  //载入
            $_str_rcode = "x030403";
        }

        if (defined("BG_INSTALL_PUB") && PRD_SSO_PUB > BG_INSTALL_PUB) {
            $_str_rcode = "x030411";
        }

        if (!fn_isEmpty($_str_rcode)) {
            $_arr_tplData = array(
                "rcode" => $_str_rcode,
            );
            $this->obj_api->show_result($_arr_tplData);
        }

        $_arr_extRow     = get_loaded_extensions();
        $_num_errCount   = 0;

        foreach ($this->obj_api->type["ext"] as $_key=>$_value) {
            if (!in_array($_key, $_arr_extRow)) {
                $_num_errCount++;
            }
        }

        if ($_num_errCount > 0) {
            $_arr_return = array(
                "rcode" => "x030417"
            );
            $this->obj_api->show_result($_arr_return);
        }
    }
}
