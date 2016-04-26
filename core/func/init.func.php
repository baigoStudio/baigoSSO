<?php
/*-----------------------------------------------------------------
！！！！警告！！！！
以下为系统文件，请勿修改
-----------------------------------------------------------------*/

//不能非法包含或直接执行
if(!defined("IN_BAIGO")) {
    exit("Access Denied");
}

if (defined("BG_DEBUG_SYS") && BG_DEBUG_SYS == 1) {
    error_reporting(E_ALL);
} else {
    error_reporting(E_ALL & ~E_DEPRECATED & ~E_STRICT);
}

include_once(BG_PATH_FUNC . "common.func.php"); //载入通用函数
include_once(BG_PATH_FUNC . "validate.func.php"); //载入表单验证函数
include_once(BG_PATH_CLASS . "dir.class.php"); //载入模板类

$GLOBALS["act_post"]    = fn_getSafe(fn_post("act_post"), "txt", ""); //表单动作
$GLOBALS["act_get"]     = fn_getSafe(fn_get("act_get"), "txt", ""); //查询串动作
$GLOBALS["view"]        = fn_getSafe(fn_request("view"), "txt", ""); //界面 (是否 iframe)

if ($GLOBALS["view"]) {
    $_url_attach = "&view=" . $GLOBALS["view"];
}

function fn_init($arr_set = array()) {
    
    //$base = false, $ssin = false, $header = "Content-Type: text/html; charset=utf-8", $db = false, $ajax = "", $admin = false, $is_ssin_db = true
    
    if (isset($arr_set["db"])) { //连接数据库
        include_once(BG_PATH_CLASS . "mysqli.class.php"); //载入数据库类

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
        );

        $GLOBALS["obj_db"]      = new CLASS_MYSQLI($_cfg_host); //设置数据库对象
    }

    if (isset($arr_set["type"])) { //模块类型
        switch ($arr_set["type"]) {
            case "ajax":
                if (!$GLOBALS["obj_db"]->connect()) {
                    $arr_alert = include_once(BG_PATH_LANG . $GLOBALS["obj_base"]->config["lang"] . "/alert.php"); //载入提示信息
                    $str_alert = "x030111";
                    $arr_re    = array(
                        "msg"    => $arr_alert[$str_alert],
                        "alert"  => $str_alert,
                    );
                    exit(json_encode($arr_re));
                }
        
                if (!$GLOBALS["obj_db"]->select_db()) {
                    $arr_alert = include_once(BG_PATH_LANG . $GLOBALS["obj_base"]->config["lang"] . "/alert.php"); //载入提示信息
                    $str_alert = "x030112";
                    $arr_re    = array(
                        "msg"    => $arr_alert[$str_alert],
                        "alert"  => $str_alert,
                    );
                    exit(json_encode($arr_re));
                }
            break;
            
            case "ctl":
                if (!$GLOBALS["obj_db"]->connect()) {
                    header("Location: " . BG_URL_ROOT . "db_conn_err.html");
                    exit;
                }
        
                if (!$GLOBALS["obj_db"]->select_db()) {
                    header("Location: " . BG_URL_ROOT . "db_select_err.html");
                    exit;
                }
            break;
        }
    }

    if (isset($arr_set["ssin"])) {  //启动会话
        if (isset($arr_set["ssin_file"])) {
            $_str_iniSsin = ini_get("session.save_path");
            if (!$_str_iniSsin) {
                ini_set("session.save_path", BG_PATH_CACHE . "ssin");
            }
        } else {
            include_once(BG_PATH_MODEL . "session.class.php"); //载入基类
            $_mdl_session = new MODEL_SESSION();
            session_set_save_handler(array(&$_mdl_session, "mdl_open"), array(&$_mdl_session, "mdl_close"), array(&$_mdl_session, "mdl_read"), array(&$_mdl_session, "mdl_write"), array(&$_mdl_session, "mdl_destroy"), array(&$_mdl_session, "mdl_gc"));
        }

        session_start(); //开启session
    }

    if (isset($arr_set["header"]) && $arr_set["header"]) {
        header($arr_set["header"]);
    }

    if (isset($arr_set["base"])) {
        include_once(BG_PATH_CLASS . "base.class.php"); //载入基类
        $GLOBALS["obj_base"] = new CLASS_BASE(); //初始化基类
    }

    if (isset($arr_set["ssin_begin"])) {
        include_once(BG_PATH_FUNC . "session.func.php"); //载入 session 函数
        $GLOBALS["adminLogged"] = fn_ssin_begin(); //验证 session, 并获取管理员信息
    }
}
