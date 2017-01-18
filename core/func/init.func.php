<?php
/*-----------------------------------------------------------------
！！！！警告！！！！
以下为系统文件，请勿修改
-----------------------------------------------------------------*/

//不能非法包含或直接执行
if (!defined("IN_BAIGO")) {
    exit("Access Denied");
}

spl_autoload_register("sso_autoload", true, true);

function sso_autoload($str_className) {
    $_arr_class = explode("_", strtolower($str_className));
    switch ($_arr_class[0]) {
        case "class":
            require(BG_PATH_CLASS . $_arr_class[1] . ".class.php"); //载入类
        break;
        case "model":
            if (isset($_arr_class[2]) && !fn_isEmpty($_arr_class[2])) {
                require(BG_PATH_MODEL . $_arr_class[1] . "_" . $_arr_class[2] . ".class.php"); //载入数据模型
            } else {
                require(BG_PATH_MODEL . $_arr_class[1] . ".class.php"); //载入数据模型
            }
        break;
        case "control":
            if (isset($_arr_class[3]) && !fn_isEmpty($_arr_class[3])) {
                require(BG_PATH_CONTROL . $_arr_class[1] . DS . $_arr_class[2] . DS . $_arr_class[3] . ".class.php"); //载入数据模型
            } else {
                require(BG_PATH_CONTROL . $_arr_class[1] . DS . $_arr_class[2] . ".class.php"); //载入数据模型
            }
        break;
    }
}

function fn_init($arr_set = array()) {
    if (!isset($arr_set["dsp_type"])) {
        switch ($GLOBALS["method"]) {
            case "POST":
                $arr_set["dsp_type"] = "post";
            break;

            default:
                $arr_set["dsp_type"] = "html";
            break;
        }
    }

    if (isset($arr_set["db"])) { //连接数据库
        CLASS_INIT::setDatabase($arr_set);
    }

    if (isset($arr_set["ssin"])) {  //启动会话
        CLASS_INIT::setSession($arr_set);
    }

    if (isset($arr_set["base"])) {
        $GLOBALS["obj_base"] = new CLASS_BASE(); //初始化基类
    }
}

class CLASS_INIT {
    static function setDatabase($arr_set) {
        $_str_rcode = "";
        $_str_jump  = "";

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

        $GLOBALS["obj_db"] = new CLASS_DATABASE($_cfg_host); //设置数据库对象

        if ($GLOBALS["obj_db"]->status != "sucess") {
            switch ($GLOBALS["obj_db"]->status) {
                case "err_db_conn":
                    $_str_rcode = "x030111";
                break;

                case "err_db_select":
                    $_str_rcode = "x030112";
                break;
            }

            if (!fn_isEmpty($_str_rcode)) {
                switch ($arr_set["dsp_type"]) {
                    case "result":
                    case "post":
                        $_arr_return = array(
                            "rcode" => "x030111",
                            "msg"   => $GLOBALS["obj_db"]->status,
                        );
                        exit(json_encode($_arr_return));
                    break;

                    default:
                        if (isset($arr_set["is_install"])) {
                            header("Location: " . BG_URL_INSTALL . $GLOBALS["obj_db"]->status . ".html");
                            exit;
                        } else {
                            header("Location: " . BG_URL_ROOT . $GLOBALS["obj_db"]->status . ".html");
                            exit;
                        }
                    break;
                }
            }
        }
    }

    static function setSession($arr_set) {
        if (isset($arr_set["ssin_file"])) {
            if (!ini_get("session.save_path")) {
                ini_set("session.save_path", BG_PATH_CACHE . "ssin");
            }
        } else {
            $_mdl_session = new MODEL_SESSION();
            session_set_save_handler(array(&$_mdl_session, "mdl_open"), array(&$_mdl_session, "mdl_close"), array(&$_mdl_session, "mdl_read"), array(&$_mdl_session, "mdl_write"), array(&$_mdl_session, "mdl_destroy"), array(&$_mdl_session, "mdl_gc"));
        }

        session_start(); //开启session
    }
}
