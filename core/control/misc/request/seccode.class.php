<?php
/*-----------------------------------------------------------------
！！！！警告！！！！
以下为系统文件，请勿修改
-----------------------------------------------------------------*/


//不能非法包含或直接执行
if (!defined("IN_BAIGO")) {
    exit("Access Denied");
}

class CONTROL_MISC_REQUEST_SECCODE {

    function __construct() { //构造函数
        $this->obj_base     = $GLOBALS["obj_base"];
        $_arr_cfg["misc"]   = true;
        $this->obj_tpl      = new CLASS_TPL(BG_PATH_TPLSYS . "misc/", $_arr_cfg); //初始化视图对象
    }

    function ctrl_chk() {
        $_str_seccode = strtolower(fn_get("seccode"));
        if ($_str_seccode != fn_session("seccode")) {
            $_arr_tplData = array(
                "rcode" => "x030205",
            );
            $this->obj_tpl->tplDisplay("result", $_arr_tplData);
        }

        $_arr_tplData = array(
            "msg"   => "ok",
        );
        $this->obj_tpl->tplDisplay("result", $_arr_tplData);
    }
}
