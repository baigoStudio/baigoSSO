<?php
/*-----------------------------------------------------------------
！！！！警告！！！！
以下为系统文件，请勿修改
-----------------------------------------------------------------*/

//不能非法包含或直接执行
if(!defined("IN_BAIGO")) {
    exit("Access Denied");
}

include_once(BG_PATH_FUNC . "baigocode.func.php"); //载入开放平台类
include_once(BG_PATH_CLASS . "api.class.php");
include_once(BG_PATH_MODEL . "opt.class.php"); //载入后台用户类

/*-------------管理员控制器-------------*/
class API_INSTALL {

    private $obj_api;

    function __construct() { //构造函数
        $this->obj_api    = new CLASS_API();
        $this->mdl_opt    = new MODEL_OPT();

        //本接口只在安装状态下起作用
        if (file_exists(BG_PATH_CONFIG . "is_install.php")) {
            $_arr_return = array(
                "alert" => "x030403"
            );
            $this->obj_api->halt_re($_arr_return);
        }

        $this->install_init();
    }


    function api_dbconfig() {
        $_arr_dbconfigSubmit = $this->mdl_opt->input_dbconfig(false);
        if ($_arr_dbconfigSubmit["alert"] != "ok") {
            $this->obj_api->halt_re($_arr_dbconfigSubmit["alert"]);
        }

        $_arr_return = $this->mdl_opt->mdl_dbconfig();

        $this->obj_api->halt_re($_arr_return);
    }


    function api_submit() {
        $_act_post = fn_getSafe($GLOBALS["act_post"], "txt", "base");

        $this->check_db();

        $_num_countSrc = 0;

        foreach ($this->obj_api->opt[$_act_post]["list"] as $_key=>$_value) {
            if ($_value["min"] > 0) {
                $_num_countSrc++;
            }
        }

        $_arr_const = $this->mdl_opt->input_const($_act_post);

        $_num_countInput = count(array_filter($_arr_const));

        if ($_num_countInput < $_num_countSrc) {
            $_arr_return = array(
                "alert" => "x030204"
            );
            $this->obj_api->halt_re($_arr_return);
        }

        $_arr_return = $this->mdl_opt->mdl_const($_act_post);

        $this->obj_api->halt_re($_arr_return);
    }


    function api_dbtable() {
        $this->check_db();

        $this->table_admin();
        $this->table_user();
        $this->table_app();
        $this->table_belong();
        $this->table_log();
        $this->view_user();

        $_arr_return = array(
            "alert"          => "y030108"
        );
        $this->obj_api->halt_re($_arr_return);
    }


    function api_admin() {
        $this->check_db();

        include_once(BG_PATH_MODEL . "admin.class.php"); //载入管理帐号模型
        $_mdl_admin       = new MODEL_ADMIN();
        $_arr_adminAdd    = $_mdl_admin->api_add();

        if ($_arr_adminAdd["alert"] != "ok") {
            $this->obj_api->halt_re($_arr_adminAdd);
        }

        $_str_rand        = fn_rand(6);
        $_str_adminPassDo = fn_baigoEncrypt($_arr_adminAdd["admin_pass"], $_str_rand, true);
        $_arr_adminRow    = $_mdl_admin->mdl_submit($_str_adminPassDo, $_str_rand);

        $this->obj_api->halt_re($_arr_adminRow);
    }


    function api_over() {
        $this->check_db();

        $this->record_app();

        $_arr_return = $this->mdl_opt->mdl_over();

        if ($_arr_return["alert"] != "y030405") {
            $this->obj_api->halt_re($_arr_return);
        }

        $this->appRecord["sso_url"]   = BG_SITE_URL . BG_URL_API . "api.php";
        $this->appRecord["alert"]     = "y030408";
        $this->obj_api->halt_re($this->appRecord);
    }


    private function table_admin() {
        include_once(BG_PATH_MODEL . "admin.class.php"); //载入管理帐号模型
        $_mdl_admin                 = new MODEL_ADMIN();
        $_mdl_admin->adminStatus    = $this->obj_api->status["admin"];
        $_arr_adminTable            = $_mdl_admin->mdl_create_table();

        if ($_arr_adminTable["alert"] != "y020105") {
            $this->obj_api->halt_re($_arr_adminTable);
        }
    }


    private function table_user() {
        include_once(BG_PATH_MODEL . "user.class.php"); //载入管理帐号模型
        $_mdl_user              = new MODEL_USER();
        $_mdl_user->userStatus  = $this->obj_api->status["user"];
        $_arr_userTable         = $_mdl_user->mdl_create_table();

        if ($_arr_userTable["alert"] != "y010105") {
            $this->obj_api->halt_re($_arr_userTable);
        }
    }


    private function table_app() {
        include_once(BG_PATH_MODEL . "app.class.php"); //载入管理帐号模型
        $_mdl_app               = new MODEL_APP();
        $_mdl_app->appStatus    = $this->obj_api->status["app"];
        $_mdl_app->appSyncs     = $this->obj_api->status["appSync"];
        $_arr_appTable          = $_mdl_app->mdl_create_table();

        if ($_arr_appTable["alert"] != "y050105") {
            $this->obj_api->halt_re($_arr_appTable);
        }
    }


    private function table_belong() {
        include_once(BG_PATH_MODEL . "belong.class.php"); //载入管理帐号模型
        $_mdl_belong       = new MODEL_BELONG();
        $_arr_belongTable  = $_mdl_belong->mdl_create_table();

        if ($_arr_belongTable["alert"] != "y070105") {
            $this->obj_api->halt_re($_arr_belongTable);
        }
    }


    private function table_log() {
        include_once(BG_PATH_MODEL . "log.class.php"); //载入管理帐号模型
        $_mdl_log               = new MODEL_LOG();
        $_mdl_log->logStatus    = $this->obj_api->status["log"];
        $_mdl_log->logTypes     = $this->obj_api->type["log"];
        $_mdl_log->logTargets   = $this->obj_api->type["logTarget"];
        $_arr_logTable          = $_mdl_log->mdl_create_table();

        if ($_arr_logTable["alert"] != "y060105") {
            $this->obj_api->halt_re($_arr_logTable);
        }
    }


    private function view_user() {
        include_once(BG_PATH_MODEL . "user.class.php"); //载入管理帐号模型
        $_mdl_user        = new MODEL_USER();
        $_arr_userView    = $_mdl_user->mdl_create_view();

        if ($_arr_userView["alert"] != "y010108") {
            $this->obj_api->halt_re($_arr_userView);
        }
    }

    private function record_app() {
        include_once(BG_PATH_MODEL . "app.class.php"); //载入管理帐号模型
        $_mdl_app     = new MODEL_APP();
        $_arr_appRow  = $_mdl_app->api_add();

        if ($_arr_appRow["alert"] != "ok") {
            $this->obj_api->halt_re($_arr_appRow);
        }

        $this->appRecord = $_mdl_app->mdl_submit();

        if ($this->appRecord["alert"] != "y050101") {
            $this->obj_api->halt_re($this->appRecord);
        }
    }


    private function check_db() {
        if (strlen(BG_DB_HOST) < 1 || strlen(BG_DB_NAME) < 1 || strlen(BG_DB_USER) < 1 || strlen(BG_DB_PASS) < 1 || strlen(BG_DB_CHARSET) < 1) {
            $_arr_return = array(
                "alert" => "x030404"
            );
            $this->obj_api->halt_re($_arr_return);
        } else {
            if (!defined("BG_DB_PORT")) {
                define("BG_DB_PORT", "3306");
            }

            $_cfg_host = array(
                "host"      => BG_DB_HOST,
                "name"      => BG_DB_NAME,
                "user"      => BG_DB_USER,
                "pass"      => BG_DB_PASS,
                "charset"   => BG_DB_CHARSET,
                "debug"     => BG_DEBUG_DB,
                "port"      => BG_DB_PORT,
            );

            $GLOBALS["obj_db"]   = new CLASS_MYSQLI($_cfg_host); //设置数据库对象
            $this->obj_db        = $GLOBALS["obj_db"];

            if (!$this->obj_db->connect()) {
                $_arr_return = array(
                    "alert" => "x030111"
                );
                $this->obj_api->halt_re($_arr_return);
            }

            if (!$this->obj_db->select_db()) {
                $_arr_return = array(
                    "alert" => "x030112"
                );
                $this->obj_api->halt_re($_arr_return);
            }
        }
    }


    private function install_init() {
        $_arr_extRow     = get_loaded_extensions();
        $_num_errCount   = 0;

        foreach ($this->obj_api->type["ext"] as $_key=>$_value) {
            if (!in_array($_key, $_arr_extRow)) {
                $_num_errCount++;
            }
        }

        if ($_num_errCount > 0) {
            $_arr_return = array(
                "alert" => "x030417"
            );
            $this->obj_api->halt_re($_arr_return);
        }
    }
}
