<?php
/*-----------------------------------------------------------------
！！！！警告！！！！
以下为系统文件，请勿修改
-----------------------------------------------------------------*/

//不能非法包含或直接执行
if (!defined("IN_BAIGO")) {
    exit("Access Denied");
}


/*-------------模板类-------------*/
class CLASS_TPL {

    function __construct($str_pathTpl = "", $arr_cfg = false) { //构造函数
        $this->obj_base = $GLOBALS["obj_base"];
        $this->config   = $this->obj_base->config;

        $this->pathTpl  = $str_pathTpl;

        if (isset($arr_cfg["console"])) {
            $this->consoleMod   = require(BG_PATH_LANG . $this->config["lang"] . "/consoleMod.php"); //载入后台模块配置
        }

        if (isset($arr_cfg["install"])) {
            $this->install  = require(BG_PATH_LANG . $this->config["lang"] . "/install.php"); //载入安装信息
        }

        if (isset($arr_cfg["console"]) || isset($arr_cfg["install"])) {
            $this->log      = require(BG_PATH_LANG . $this->config["lang"] . "/log.php"); //载入日志内容
        }

        if (isset($arr_cfg["console"]) || isset($arr_cfg["install"]) || isset($arr_cfg["my"])) {
            $this->common["tokenRow"]   = fn_token();
        }

        if (isset($arr_cfg["console"]) || isset($arr_cfg["install"]) || isset($arr_cfg["my"]) || isset($arr_cfg["help"])) {
            $this->lang     = require(BG_PATH_LANG . $this->config["lang"] . "/lang.php"); //载入语言文件
        }

        if (isset($arr_cfg["console"]) || isset($arr_cfg["install"]) || isset($arr_cfg["my"]) || isset($arr_cfg["help"]) || isset($arr_cfg["misc"])) {
            $this->type     = require(BG_PATH_LANG . $this->config["lang"] . "/type.php"); //载入类型文件
            $this->allow    = require(BG_PATH_LANG . $this->config["lang"] . "/allow.php"); //载入权限文件
            $this->status   = require(BG_PATH_LANG . $this->config["lang"] . "/status.php"); //载入状态文件
            $this->opt      = require(BG_PATH_LANG . $this->config["lang"] . "/opt.php"); //载入配置信息
            $this->rcode    = require(BG_PATH_LANG . $this->config["lang"] . "/rcode.php"); //载入返回代码
        }
    }


    /** 显示界面
     * tplDisplay function.
     *
     * @access public
     * @param mixed $str_tpl
     * @param string $arr_tplData (default: "")
     * @return void
     */
    function tplDisplay($str_tpl, $arr_tplData = array()) {
        $this->tplData  = $arr_tplData;

        require($this->pathTpl . "/" . $str_tpl . ".php");
        exit;
    }
}
