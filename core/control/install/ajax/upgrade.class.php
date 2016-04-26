<?php
/*-----------------------------------------------------------------
！！！！警告！！！！
以下为系统文件，请勿修改
-----------------------------------------------------------------*/

//不能非法包含或直接执行
if(!defined("IN_BAIGO")) {
    exit("Access Denied");
}

include_once(BG_PATH_CLASS . "ajax.class.php"); //载入 AJAX 基类
include_once(BG_PATH_MODEL . "opt.class.php"); //载入管理帐号模型

class AJAX_UPGRADE {

    private $obj_ajax;
    private $obj_db;

    function __construct() { //构造函数
        $this->obj_ajax       = new CLASS_AJAX(); //初始化 AJAX 基对象

        if (file_exists(BG_PATH_CONFIG . "is_install.php")) {
            include_once(BG_PATH_CONFIG . "is_install.php"); //载入栏目控制器
            if (defined("BG_INSTALL_PUB") && PRD_SSO_PUB <= BG_INSTALL_PUB) {
                $this->obj_ajax->halt_alert("x030403");
            }
        }
        $this->upgrade_init();
        $this->mdl_opt = new MODEL_OPT();
    }


    function ajax_dbconfig() {
        $_arr_dbconfigSubmit = $this->mdl_opt->input_dbconfig(false);

        if ($_arr_dbconfigSubmit["alert"] != "ok") {
            $this->obj_ajax->halt_alert($_arr_dbconfigSubmit["alert"]);
        }

        $_arr_return = $this->mdl_opt->mdl_dbconfig();

        $this->obj_ajax->halt_alert($_arr_return["alert"]);
    }


    function ajax_submit() {
        $_act_post = fn_getSafe($GLOBALS["act_post"], "txt", "base");

        $this->check_db();

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

        $this->obj_ajax->halt_alert("y030405");
    }



    function ajax_over() {
        $this->check_db();

        $_arr_return = $this->mdl_opt->mdl_over();

        if ($_arr_return["alert"] != "y040101") {
            $this->obj_ajax->halt_alert($_arr_return["alert"]);
        }

        $this->obj_ajax->halt_alert("y030409");
    }


    private function check_db() {
        if (!fn_token("chk")) { //令牌
            $this->obj_ajax->halt_alert("x030206");
        }

        if (strlen(BG_DB_HOST) < 1 || strlen(BG_DB_NAME) < 1 || strlen(BG_DB_USER) < 1 || strlen(BG_DB_PASS) < 1 || strlen(BG_DB_CHARSET) < 1) {
            $this->obj_ajax->halt_alert("x030412");
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
                $this->obj_ajax->halt_alert("x030111");
            }

            if (!$this->obj_db->select_db()) {
                $this->obj_ajax->halt_alert("x030112");
            }
        }
    }


    private function upgrade_init() {
        $_arr_extRow      = get_loaded_extensions();
        $_num_errCount    = 0;

        foreach ($this->obj_ajax->type["ext"] as $_key=>$_value) {
            if (!in_array($_key, $_arr_extRow)) {
                $_num_errCount++;
            }
        }

        if ($_num_errCount > 0) {
            $this->obj_ajax->halt_alert("x030418");
        }
    }
}