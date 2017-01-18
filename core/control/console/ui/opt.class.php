<?php
/*-----------------------------------------------------------------
！！！！警告！！！！
以下为系统文件，请勿修改
-----------------------------------------------------------------*/

//不能非法包含或直接执行
if (!defined("IN_BAIGO")) {
    exit("Access Denied");
}

require(BG_PATH_FUNC . "http.func.php"); //载入模板类

/*-------------管理员控制器-------------*/
class CONTROL_CONSOLE_UI_OPT {

    private $is_super = false;

    function __construct() { //构造函数
        $this->obj_base         = $GLOBALS["obj_base"]; //获取界面类型
        $this->config           = $this->obj_base->config;

        $this->obj_console      = new CLASS_CONSOLE();
        $this->obj_console->chk_install();

        $this->adminLogged      = $this->obj_console->ssin_begin(); //获取已登录信息
        $this->obj_console->is_admin($this->adminLogged);

        $this->obj_tpl          = $this->obj_console->obj_tpl;

        $this->tplData = array(
            "adminLogged" => $this->adminLogged
        );

        if ($this->adminLogged["admin_type"] == "super") {
            $this->is_super = true;
        }

        $this->act = fn_getSafe($GLOBALS["act"], "text", "base");

        $this->obj_dir          = new CLASS_DIR();
        $this->mdl_opt          = new MODEL_OPT(); //设置管理组模型
    }



    function ctrl_chkver() {
        if (!isset($this->adminLogged["admin_allow"]["opt"]["chkver"]) && !$this->is_super) {
            $this->tplData["rcode"] = "x040301";
            $this->obj_tpl->tplDisplay("error", $this->tplData);
        }

        $this->tplData["latest_ver"]    = $this->mdl_opt->chk_ver();
        $this->tplData["install_pub"]   = strtotime(BG_INSTALL_PUB);

        $this->obj_tpl->tplDisplay("opt_chkver", $this->tplData);
    }


    function ctrl_dbconfig() {
        if (!isset($this->adminLogged["admin_allow"]["opt"]["dbconfig"]) && !$this->is_super) {
            $this->tplData["rcode"] = "x040301";
            $this->obj_tpl->tplDisplay("error", $this->tplData);
        }

        $this->obj_tpl->tplDisplay("opt_dbconfig", $this->tplData);
    }


    function ctrl_form() {
        if (!isset($this->adminLogged["admin_allow"]["opt"][$this->act]) && !$this->is_super) {
            $this->tplData["rcode"] = "x040301";
            $this->obj_tpl->tplDisplay("error", $this->tplData);
        }

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

        $this->tplData["act"] = $this->act;

        $this->obj_tpl->tplDisplay("opt_form", $this->tplData);
    }
}
