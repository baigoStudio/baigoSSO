<?php
/*-----------------------------------------------------------------
！！！！警告！！！！
以下为系统文件，请勿修改
-----------------------------------------------------------------*/

//不能非法包含或直接执行
if (!defined("IN_BAIGO")) {
    exit("Access Denied");
}

include_once(BG_PATH_CLASS . "tpl.class.php"); //载入模板类

/*-------------文章类-------------*/
class CONTROL_HELP {

    private $obj_tpl;

    function __construct() { //构造函数
        $this->obj_base       = $GLOBALS["obj_base"];
        $this->config         = $this->obj_base->config;
        $this->obj_tpl        = new CLASS_TPL(BG_PATH_TPLSYS . "help/" . BG_DEFAULT_UI); //初始化视图对象

        if (file_exists(BG_PATH_LANG . $this->config["lang"] . "/help/config.php")) {
            $this->helpConfig = include_once(BG_PATH_LANG . $this->config["lang"] . "/help/config.php");
        } else {
            $this->helpConfig = array();
        }
    }

    function ctl_show() {
        $this->mod        = fn_getSafe(fn_get("mod"), "txt", "intro");
        $this->act_get    = fn_getSafe(fn_get("act_get"), "txt", "outline");

        if (file_exists(BG_PATH_LANG . $this->config["lang"] . "/help/" . $this->mod . "/config.php")) {
            $_arr_config = include_once(BG_PATH_LANG . $this->config["lang"] . "/help/" . $this->mod . "/config.php");
        } else {
            $_arr_config = array();
        }

        if (BG_DEFAULT_UI != "default") {
            $_arr_config = str_ireplace("baigo", BG_DEFAULT_UI, $_arr_config);
        }

        $this->tplData = array(
            "helpConfig" => $this->helpConfig,
            "config"     => $_arr_config,
            "mod"        => $this->mod,
            "act_get"    => $this->act_get,
            "content"    => $this->str_process(),
        );

        $this->obj_tpl->tplDisplay("help.tpl", $this->tplData);
    }

    private function str_process() {
        if (file_exists(BG_PATH_LANG . $this->config["lang"] . "/help/" . $this->mod . "/" . $this->act_get . ".php")) {
            $_str_content = include_once(BG_PATH_LANG . $this->config["lang"] . "/help/" . $this->mod . "/" . $this->act_get . ".php");
        } else {
            $_str_content = "";
        }

        $_str_content = str_ireplace("{images}", BG_URL_STATIC . "help/" . BG_DEFAULT_UI . "/" . $this->mod . "/", $_str_content);
        $_str_content = str_ireplace("{BG_URL_HELP}", BG_URL_HELP, $_str_content);
        if (BG_DEFAULT_UI != "default") {
            $_str_content = str_ireplace("baigo", BG_DEFAULT_UI, $_str_content);
        }
        return $_str_content;
    }
}
