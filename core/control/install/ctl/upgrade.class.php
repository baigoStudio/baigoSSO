<?php
/*-----------------------------------------------------------------
！！！！警告！！！！
以下为系统文件，请勿修改
-----------------------------------------------------------------*/

//不能非法包含或直接执行
if(!defined("IN_BAIGO")) {
    exit("Access Denied");
}

include_once(BG_PATH_CLASS . "tpl.class.php"); //载入模板类

class CONTROL_UPGRADE {

    private $obj_tpl;

    function __construct() { //构造函数
        $this->obj_base     = $GLOBALS["obj_base"];
        $this->config       = $this->obj_base->config;
        $_arr_cfg["admin"]  = true;
        $this->obj_tpl      = new CLASS_TPL(BG_PATH_TPLSYS . "install/" . $this->config["ui"], $_arr_cfg);
        $this->obj_dir      = new CLASS_DIR();
        $this->obj_dir->mk_dir(BG_PATH_CACHE . "ssin");
        $this->upgrade_init();
    }


    function ctl_ext() {
        $this->obj_tpl->tplDisplay("upgrade_ext.tpl", $this->tplData);

        return array(
            "alert" => "y030403",
        );
    }


    function ctl_dbconfig() {
        if ($this->errCount > 0) {
            return array(
                "alert" => "x030414",
            );
        }

        $this->obj_tpl->tplDisplay("upgrade_dbconfig.tpl", $this->tplData);

        return array(
            "alert" => "y030404",
        );
    }


    function ctl_form() {
        if ($this->errCount > 0) {
            return array(
                "alert" => "x030414",
            );
        }

        if (!$this->check_db()) {
            return array(
                "alert" => "x030412",
            );
        }

        $this->obj_tpl->tplDisplay("upgrade_form.tpl", $this->tplData);

        return array(
            "alert" => "y030405",
        );
    }


    /**
     * upgrade_2 function.
     *
     * @access public
     * @return void
     */
    function ctl_dbtable() {
        if ($this->errCount > 0) {
            return array(
                "alert" => "x030414",
            );
        }

        if (!$this->check_db()) {
            return array(
                "alert" => "x030412",
            );
        }

        $this->table_admin();
        $this->table_user();
        $this->table_app();
        $this->table_belong();
        $this->table_log();
        $this->table_session();
        $this->table_verify();
        $this->table_pm();
        $this->view_user();

        $this->obj_tpl->tplDisplay("upgrade_dbtable.tpl", $this->tplData);

        return array(
            "alert" => "y030404",
        );
    }


    function ctl_over() {
        if ($this->errCount > 0) {
            return array(
                "alert" => "x030414",
            );
        }

        if (!$this->check_db()) {
            return array(
                "alert" => "x030412",
            );
        }

        $this->obj_tpl->tplDisplay("upgrade_over.tpl", $this->tplData);

        return array(
            "alert" => "y030405",
        );
    }


    private function check_db() {
        if (strlen(BG_DB_HOST) < 1 || strlen(BG_DB_NAME) < 1 || strlen(BG_DB_USER) < 1 || strlen(BG_DB_PASS) < 1 || strlen(BG_DB_CHARSET) < 1) {
            return false;
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
                return false;
            }

            if (!$this->obj_db->select_db()) {
                return false;
            }

            return true;
        }
    }


    private function upgrade_init() {
        $_arr_extRow      = get_loaded_extensions();
        $this->errCount   = 0;

        foreach ($this->obj_tpl->type["ext"] as $_key=>$_value) {
            if (!in_array($_key, $_arr_extRow)) {
                $this->errCount++;
            }
        }

        $this->act_get = fn_getSafe($GLOBALS["act_get"], "txt", "ext");

        $this->tplData = array(
            "errCount"   => $this->errCount,
            "extRow"     => $_arr_extRow,
            "act_get"    => $this->act_get,
            "act_next"   => $this->upgrade_next($this->act_get),
        );
    }

    private function upgrade_next($act_get) {
        $_arr_optKeys = array_keys($this->obj_tpl->opt);
        $_index       = array_search($act_get, $_arr_optKeys);
        $_arr_opt     = array_slice($this->obj_tpl->opt, $_index + 1, 1);
        if ($_arr_opt) {
            $_key = key($_arr_opt);
        } else {
            $_key = "over";
        }

        return $_key;
    }


    private function table_admin() {
        include_once(BG_PATH_MODEL . "admin.class.php"); //载入管理帐号模型
        $_mdl_admin                 = new MODEL_ADMIN();
        $_mdl_admin->adminStatus    = $this->obj_tpl->status["admin"];
        $_arr_adminTable            = $_mdl_admin->mdl_alert_table();

        $this->tplData["db_alert"]["admin_table"] = array(
            "alert"   => $_arr_adminTable["alert"],
            "status"  => substr($_arr_adminTable["alert"], 0, 1),
        );
    }


    private function table_user() {
        include_once(BG_PATH_MODEL . "user.class.php"); //载入管理帐号模型
        $_mdl_user              = new MODEL_USER();
        $_mdl_user->userStatus  = $this->obj_tpl->status["user"];
        $_arr_userTable         = $_mdl_user->mdl_alert_table();

        $this->tplData["db_alert"]["user_table"] = array(
            "alert"   => $_arr_userTable["alert"],
            "status"  => substr($_arr_userTable["alert"], 0, 1),
        );
    }


    private function table_app() {
        include_once(BG_PATH_MODEL . "app.class.php"); //载入管理帐号模型
        $_mdl_app               = new MODEL_APP();
        $_mdl_app->appStatus    = $this->obj_tpl->status["app"];
        $_mdl_app->appSyncs     = $this->obj_tpl->status["appSync"];
        $_arr_appTable          = $_mdl_app->mdl_alert_table();

        $this->tplData["db_alert"]["app_table"] = array(
            "alert"   => $_arr_appTable["alert"],
            "status"  => substr($_arr_appTable["alert"], 0, 1),
        );
    }


    private function table_belong() {
        include_once(BG_PATH_MODEL . "belong.class.php"); //载入管理帐号模型
        $_mdl_belong        = new MODEL_BELONG();
        $_arr_belongCreate  = $_mdl_belong->mdl_create_table();
        $_arr_belongAlert   = $_mdl_belong->mdl_alert_table();

        $this->tplData["db_alert"]["belong_table_create"] = array(
            "alert"   => $_arr_belongCreate["alert"],
            "status"  => substr($_arr_belongCreate["alert"], 0, 1),
        );
        $this->tplData["db_alert"]["belong_table_alert"] = array(
            "alert"   => $_arr_belongAlert["alert"],
            "status"  => substr($_arr_belongAlert["alert"], 0, 1),
        );
    }


    private function table_log() {
        include_once(BG_PATH_MODEL . "log.class.php"); //载入管理帐号模型
        $_mdl_log               = new MODEL_LOG();
        $_mdl_log->logStatus    = $this->obj_tpl->status["log"];
        $_mdl_log->logTypes     = $this->obj_tpl->type["log"];
        $_mdl_log->logTargets   = $this->obj_tpl->type["logTarget"];
        $_arr_logTable          = $_mdl_log->mdl_alert_table();

        $this->tplData["db_alert"]["log_table"] = array(
            "alert"   => $_arr_logTable["alert"],
            "status"  => substr($_arr_logTable["alert"], 0, 1),
        );
    }


    private function table_session() {
        include_once(BG_PATH_MODEL . "session.class.php"); //载入管理帐号模型
        $_mdl_session         = new MODEL_SESSION();
        $_arr_sessionTable    = $_mdl_session->mdl_create_table();

        $this->tplData["db_alert"]["session_table"] = array(
            "alert"   => $_arr_sessionTable["alert"],
            "status"  => substr($_arr_sessionTable["alert"], 0, 1),
        );
    }


    private function table_verify() {
        include_once(BG_PATH_MODEL . "verify.class.php"); //载入管理帐号模型
        $_mdl_verify                = new MODEL_VERIFY();
        $_mdl_verify->verifyStatus  = $this->obj_tpl->status["verify"];
        $_arr_verifyTable           = $_mdl_verify->mdl_create_table();

        $this->tplData["db_alert"]["verify_table"] = array(
            "alert"   => $_arr_verifyTable["alert"],
            "status"  => substr($_arr_verifyTable["alert"], 0, 1),
        );
    }


    private function table_pm() {
        include_once(BG_PATH_MODEL . "pm.class.php"); //载入管理帐号模型
        $_mdl_pm            = new MODEL_PM();
        $_mdl_pm->pmStatus  = $this->obj_tpl->status["pm"];
        $_mdl_pm->pmTypes   = $this->obj_tpl->type["pm"];
        $_arr_pmTable       = $_mdl_pm->mdl_create_table();

        $this->tplData["db_alert"]["pm_table"] = array(
            "alert"   => $_arr_pmTable["alert"],
            "status"  => substr($_arr_pmTable["alert"], 0, 1),
        );
    }


    private function view_user() {
        include_once(BG_PATH_MODEL . "user.class.php"); //载入管理帐号模型
        $_mdl_user        = new MODEL_USER();
        $_arr_userView    = $_mdl_user->mdl_create_view();

        $this->tplData["db_alert"]["user_view"] = array(
            "alert"   => $_arr_userView["alert"],
            "status"  => substr($_arr_userView["alert"], 0, 1),
        );
    }
}
