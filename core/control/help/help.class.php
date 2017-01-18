<?php
/*-----------------------------------------------------------------
！！！！警告！！！！
以下为系统文件，请勿修改
-----------------------------------------------------------------*/

//不能非法包含或直接执行
if (!defined("IN_BAIGO")) {
    exit("Access Denied");
}


/*-------------文章类-------------*/
class CONTROL_HELP_HELP {

    private $obj_tpl;

    function __construct() { //构造函数
        $this->obj_base       = $GLOBALS["obj_base"];
        $this->config         = $this->obj_base->config;
        $_arr_cfg["help"]     = true;
        $this->obj_tpl        = new CLASS_TPL(BG_PATH_TPLSYS . "help/" . BG_DEFAULT_UI, $_arr_cfg); //初始化视图对象

        if (file_exists(BG_PATH_LANG . $this->config["lang"] . "/help/config.php")) {
            $this->helpConfig = require(BG_PATH_LANG . $this->config["lang"] . "/help/config.php");
        } else {
            $this->helpConfig = array();
        }
    }

    function ctrl_show() {
        $this->mod  = fn_getSafe(fn_get("mod"), "txt", "intro");
        $this->act  = fn_getSafe($GLOBALS["act"], "txt", "outline");

        if (file_exists(BG_PATH_LANG . $this->config["lang"] . "/help/" . $this->mod . ".php")) {
            $_arr_config = require(BG_PATH_LANG . $this->config["lang"] . "/help/" . $this->mod . ".php");
        } else {
            $_arr_config = array();
        }

        if (BG_DEFAULT_UI != "default") {
            $_arr_config = str_ireplace("baigo", BG_DEFAULT_UI, $_arr_config);
        }

        $this->tplData = array(
            "helpConfig"    => $this->helpConfig,
            "config"        => $_arr_config,
            "mod"           => $this->mod,
            "act"           => $this->act,
            "content"       => $this->str_process(),
        );

        $this->obj_tpl->tplDisplay("help", $this->tplData);
    }

    private function str_process() {
        $_str_content = "";

        if (file_exists(BG_PATH_HELP . $this->config["lang"] . "/" . $this->mod . "/" . $this->act . ".html")) {
            ob_start();
            require(BG_PATH_HELP . $this->config["lang"] . "/" . $this->mod . "/" . $this->act . ".html");
            $_str_content = ob_get_contents();
            ob_end_clean();
        }

        $_str_content = str_ireplace("{images}", BG_URL_STATIC . "help/" . BG_DEFAULT_UI . "/" . $this->mod . "/", $_str_content);
        $_str_content = str_ireplace("{BG_URL_HELP}", BG_URL_HELP, $_str_content);
        $_str_content = str_ireplace("{BG_SITE_URL}", BG_SITE_URL, $_str_content);
        $_str_content = str_ireplace("{BG_URL_API}", BG_URL_API, $_str_content);
        if (BG_DEFAULT_UI != "default") {
            $_str_content = str_ireplace("baigo", BG_DEFAULT_UI, $_str_content);
        }
        return $_str_content;
    }
}
