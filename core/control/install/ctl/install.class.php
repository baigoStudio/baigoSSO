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

class CONTROL_INSTALL {

    private $obj_tpl;

    function __construct() { //构造函数
        $this->obj_base     = $GLOBALS["obj_base"];
        $this->config       = $this->obj_base->config;
        $_arr_cfg["admin"]  = true;
        $this->obj_tpl      = new CLASS_TPL(BG_PATH_TPL . "install/" . $this->config["ui"], $_arr_cfg);
        $this->install_init();
    }


    function ctl_ext() {
        $this->obj_tpl->tplDisplay("install_ext.tpl", $this->tplData);

        return array(
            "alert" => "y030403",
        );
    }


    function ctl_dbconfig() {
        if ($this->errCount > 0) {
            return array(
                "alert" => "x030413",
            );
        }

        $this->obj_tpl->tplDisplay("install_dbconfig.tpl", $this->tplData);

        return array(
            "alert" => "y030404",
        );
    }


    function ctl_form() {
        if ($this->errCount > 0) {
            return array(
                "alert" => "x030413",
            );
        }

        if (!$this->check_db()) {
            return array(
                "alert" => "x030404",
            );
        }

        $this->obj_tpl->tplDisplay("install_form.tpl", $this->tplData);

        return array(
            "alert" => "y030405",
        );
    }


    /**
     * install_2 function.
     *
     * @access public
     * @return void
     */
    function ctl_dbtable() {
        if ($this->errCount > 0) {
            return array(
                "alert" => "x030413",
            );
        }

        if (!$this->check_db()) {
            return array(
                "alert" => "x030404",
            );
        }

        $this->table_admin();
        $this->table_user();
        $this->table_app();
        $this->table_belong();
        $this->table_log();
        $this->table_session();
        $this->table_verify();
        $this->view_user();

        $this->obj_tpl->tplDisplay("install_dbtable.tpl", $this->tplData);

        return array(
            "alert" => "y030404",
        );
    }


    /**
     * ctl_admin function.
     *
     * @access public
     * @return void
     */
    function ctl_admin() {
        if ($this->errCount > 0) {
            return array(
                "alert" => "x030413",
            );
        }

        if (!$this->check_db()) {
            return array(
                "alert" => "x030404",
            );
        }

        $this->obj_tpl->tplDisplay("install_admin.tpl", $this->tplData);

        return array(
            "alert" => "y030405",
        );
    }


    function ctl_over() {
        if ($this->errCount > 0) {
            return array(
                "alert" => "x030413",
            );
        }

        if (!$this->check_db()) {
            return array(
                "alert" => "x030404",
            );
        }

        $this->obj_tpl->tplDisplay("install_over.tpl", $this->tplData);

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


    private function install_init() {
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
            "act_next"   => $this->install_next($this->act_get),
        );
    }


    private function install_next($act_get) {
        $_arr_optKeys = array_keys($this->obj_tpl->opt);
        $_index       = array_search($act_get, $_arr_optKeys);
        $_arr_opt     = array_slice($this->obj_tpl->opt, $_index + 1, 1);
        if ($_arr_opt) {
            $_key = key($_arr_opt);
        } else {
            $_key = "admin";
        }

        return $_key;
    }


    private function table_admin() {
        include_once(BG_PATH_MODEL . "admin.class.php"); //载入管理帐号模型
        $_mdl_admin       = new MODEL_ADMIN();
        $_arr_adminTable  = $_mdl_admin->mdl_create_table();

        $this->tplData["db_alert"]["admin_table"] = array(
            "alert"   => $_arr_adminTable["alert"],
            "status"  => substr($_arr_adminTable["alert"], 0, 1),
        );
    }


    private function table_user() {
        include_once(BG_PATH_MODEL . "user.class.php"); //载入管理帐号模型
        $_mdl_user        = new MODEL_USER();
        $_arr_userTable   = $_mdl_user->mdl_create_table();

        $this->tplData["db_alert"]["user_table"] = array(
            "alert"   => $_arr_userTable["alert"],
            "status"  => substr($_arr_userTable["alert"], 0, 1),
        );
    }


    private function table_app() {
        include_once(BG_PATH_MODEL . "app.class.php"); //载入管理帐号模型
        $_mdl_app         = new MODEL_APP();
        $_arr_appTable    = $_mdl_app->mdl_create_table();

        $this->tplData["db_alert"]["app_table"] = array(
            "alert"   => $_arr_appTable["alert"],
            "status"  => substr($_arr_appTable["alert"], 0, 1),
        );
    }


    private function table_belong() {
        include_once(BG_PATH_MODEL . "belong.class.php"); //载入管理帐号模型
        $_mdl_belong       = new MODEL_BELONG();
        $_arr_belongTable  = $_mdl_belong->mdl_create_table();

        $this->tplData["db_alert"]["belong_table"] = array(
            "alert"   => $_arr_belongTable["alert"],
            "status"  => substr($_arr_belongTable["alert"], 0, 1),
        );
    }


    private function table_log() {
        include_once(BG_PATH_MODEL . "log.class.php"); //载入管理帐号模型
        $_mdl_log         = new MODEL_LOG();
        $_arr_logTable    = $_mdl_log->mdl_create_table();

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
        $_mdl_verify         = new MODEL_SESSION();
        $_arr_verifyTable    = $_mdl_verify->mdl_create_table();

        $this->tplData["db_alert"]["verify_table"] = array(
            "alert"   => $_arr_verifyTable["alert"],
            "status"  => substr($_arr_verifyTable["alert"], 0, 1),
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
