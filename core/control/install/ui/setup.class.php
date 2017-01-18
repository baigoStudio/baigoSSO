<?php
/*-----------------------------------------------------------------
！！！！警告！！！！
以下为系统文件，请勿修改
-----------------------------------------------------------------*/

//不能非法包含或直接执行
if (!defined("IN_BAIGO")) {
    exit("Access Denied");
}


class CONTROL_INSTALL_UI_SETUP {

    function __construct() { //构造函数
        $this->obj_base         = $GLOBALS["obj_base"];
        $this->config           = $this->obj_base->config;

        $_arr_cfg["install"]    = true;
        $this->obj_tpl          = new CLASS_TPL(BG_PATH_TPLSYS . "install/" . BG_DEFAULT_UI, $_arr_cfg);
        $this->obj_dir          = new CLASS_DIR();
        $this->obj_dir->mk_dir(BG_PATH_CACHE . "ssin");

        $this->setup_init();
    }

    function ctrl_ext() {
        $this->obj_tpl->tplDisplay("setup_ext", $this->tplData);
    }


    function ctrl_dbconfig() {
        $this->obj_tpl->tplDisplay("setup_dbconfig", $this->tplData);
    }


    function ctrl_form() {
        $this->check_db();

        if ($this->act == "base") {
            $this->tplData["tplRows"]     = $this->obj_dir->list_dir(BG_PATH_TPL . "my/");

            $_arr_timezoneRows  = require(BG_PATH_LANG . $this->config["lang"] . "/timezone.php");

            $_arr_timezone[] = "";

            if (stristr(BG_SITE_TIMEZONE, "/")) {
                $_arr_timezone = explode("/", BG_SITE_TIMEZONE);
            }

            $this->tplData["timezoneRows"]  = $_arr_timezoneRows;
            $this->tplData["timezoneJson"]  = json_encode($_arr_timezoneRows);
            $this->tplData["timezoneType"]  = $_arr_timezone[0];
        }

        $this->obj_tpl->tplDisplay("setup_form", $this->tplData);
    }


    /**
     * install_2 function.
     *
     * @access public
     * @return void
     */
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

        $this->obj_tpl->tplDisplay("setup_dbtable", $this->tplData);
    }


    /**
     * ctrl_admin function.
     *
     * @access public
     * @return void
     */
    function ctrl_auth() {
        $this->check_db();

        $this->obj_tpl->tplDisplay("setup_auth", $this->tplData);
    }


    function ctrl_admin() {
        $this->check_db();

        $this->obj_tpl->tplDisplay("setup_admin", $this->tplData);
    }


    function ctrl_over() {
        $this->check_db();

        $this->obj_tpl->tplDisplay("setup_over", $this->tplData);
    }


    private function check_db() {
        if (!defined("BG_DB_HOST") || fn_isEmpty(BG_DB_HOST) || !defined("BG_DB_NAME") || fn_isEmpty(BG_DB_NAME) || !defined("BG_DB_PASS") || fn_isEmpty(BG_DB_PASS) || !defined("BG_DB_CHARSET") || fn_isEmpty(BG_DB_CHARSET)) {
            $_arr_tplData = array(
                "rcode" => "x030404",
            );

            $this->obj_tpl->tplDisplay("error", $_arr_tplData);
        }
    }


    private function setup_init() {
        $_str_rcode = "";
        $_str_jump  = "";

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
            $_str_jump  = BG_URL_INSTALL . "index.php?mod=upgrade";
        }

        if (!fn_isEmpty($_str_rcode)) {
            if (!fn_isEmpty($_str_jump)) {
                header("Location: " . $_str_jump);
            }

            $_arr_tplData = array(
                "rcode" => $_str_rcode,
            );
            $this->obj_tpl->tplDisplay("error", $_arr_tplData);
        }

        $_arr_extRow      = get_loaded_extensions();
        $this->errCount   = 0;

        foreach ($this->obj_tpl->type["ext"] as $_key=>$_value) {
            if (!in_array($_key, $_arr_extRow)) {
                $this->errCount++;
            }
        }

        if ($this->errCount > 0) {
            $_arr_tplData = array(
                "rcode" => "x030413",
            );
            $this->obj_tpl->tplDisplay("error", $_arr_tplData);
        }

        $this->act = fn_getSafe($GLOBALS["act"], "txt", "ext");

        $this->tplData = array(
            "errCount"      => $this->errCount,
            "extRow"        => $_arr_extRow,
            "act"           => $this->act,
            "setup_step"    => $this->setup_step($this->act),
        );
    }


    private function setup_step() {
        $_arr_optKeys = array_keys($this->obj_tpl->opt);
        $_index       = array_search($this->act, $_arr_optKeys);

        $_arr_prev     = array_slice($this->obj_tpl->opt, $_index - 1, -1);
        if (fn_isEmpty($_arr_prev)) {
            $_key_prev = "dbtable";
        } else {
            $_key_prev = key($_arr_prev);
        }

        $_arr_next     = array_slice($this->obj_tpl->opt, $_index + 1, 1);
        if (fn_isEmpty($_arr_next)) {
            $_key_next = "admin";
        } else {
            $_key_next = key($_arr_next);
        }

        return array(
            "prev" => $_key_prev,
            "next" => $_key_next,
        );
    }


    private function table_admin() {
        $_mdl_admin                 = new MODEL_ADMIN();
        $_mdl_admin->adminStatus    = $this->obj_tpl->status["admin"];
        $_mdl_admin->adminTypes     = $this->obj_tpl->type["admin"];
        $_arr_adminCreateTable      = $_mdl_admin->mdl_create_table();

        $this->tplData["db_rcode"]["admin_create_table"] = array(
            "rcode"   => $_arr_adminCreateTable["rcode"],
            "status"  => substr($_arr_adminCreateTable["rcode"], 0, 1),
        );
    }


    private function table_user() {
        $_mdl_user              = new MODEL_USER();
        $_mdl_user->userStatus  = $this->obj_tpl->status["user"];
        $_arr_userCreateTable   = $_mdl_user->mdl_create_table();

        $this->tplData["db_rcode"]["user_create_table"] = array(
            "rcode"   => $_arr_userCreateTable["rcode"],
            "status"  => substr($_arr_userCreateTable["rcode"], 0, 1),
        );
    }


    private function table_app() {
        $_mdl_app               = new MODEL_APP();
        $_mdl_app->appStatus    = $this->obj_tpl->status["app"];
        $_mdl_app->appSyncs     = $this->obj_tpl->status["appSync"];
        $_arr_appCreateTable    = $_mdl_app->mdl_create_table();

        $this->tplData["db_rcode"]["app_create_table"] = array(
            "rcode"   => $_arr_appCreateTable["rcode"],
            "status"  => substr($_arr_appCreateTable["rcode"], 0, 1),
        );
    }


    private function table_belong() {
        $_mdl_belong            = new MODEL_BELONG();
        $_arr_belongCreateTable = $_mdl_belong->mdl_create_table();

        $this->tplData["db_rcode"]["belong_create_table"] = array(
            "rcode"   => $_arr_belongCreateTable["rcode"],
            "status"  => substr($_arr_belongCreateTable["rcode"], 0, 1),
        );
    }


    private function table_log() {
        $_mdl_log               = new MODEL_LOG();
        $_mdl_log->logStatus    = $this->obj_tpl->status["log"];
        $_mdl_log->logTypes     = $this->obj_tpl->type["log"];
        $_mdl_log->logTargets   = $this->obj_tpl->type["logTarget"];
        $_arr_logCreateTable    = $_mdl_log->mdl_create_table();

        $this->tplData["db_rcode"]["log_create_table"] = array(
            "rcode"   => $_arr_logCreateTable["rcode"],
            "status"  => substr($_arr_logCreateTable["rcode"], 0, 1),
        );
    }


    private function table_session() {
        $_mdl_session               = new MODEL_SESSION();
        $_arr_sessionCreateTable    = $_mdl_session->mdl_create_table();

        $this->tplData["db_rcode"]["session_create_table"] = array(
            "rcode"   => $_arr_sessionCreateTable["rcode"],
            "status"  => substr($_arr_sessionCreateTable["rcode"], 0, 1),
        );
    }


    private function table_verify() {
        $_mdl_verify                = new MODEL_VERIFY();
        $_mdl_verify->verifyStatus  = $this->obj_tpl->status["verify"];
        $_mdl_verify->verifyTypes   = $this->obj_tpl->type["verify"];
        $_arr_verifyCreateTable     = $_mdl_verify->mdl_create_table();

        $this->tplData["db_rcode"]["verify_create_table"] = array(
            "rcode"   => $_arr_verifyCreateTable["rcode"],
            "status"  => substr($_arr_verifyCreateTable["rcode"], 0, 1),
        );
    }


    private function table_pm() {
        $_mdl_pm            = new MODEL_PM();
        $_mdl_pm->pmStatus  = $this->obj_tpl->status["pm"];
        $_mdl_pm->pmTypes   = $this->obj_tpl->type["pm"];
        $_arr_pmCreateTable = $_mdl_pm->mdl_create_table();

        $this->tplData["db_rcode"]["pm_create_table"] = array(
            "rcode"   => $_arr_pmCreateTable["rcode"],
            "status"  => substr($_arr_pmCreateTable["rcode"], 0, 1),
        );
    }


    private function view_user() {
        $_mdl_user              = new MODEL_USER();
        $_arr_userCreateView    = $_mdl_user->mdl_create_view();

        $this->tplData["db_rcode"]["user_create_view"] = array(
            "rcode"   => $_arr_userCreateView["rcode"],
            "status"  => substr($_arr_userCreateView["rcode"], 0, 1),
        );
    }
}
