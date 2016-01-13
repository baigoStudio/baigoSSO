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

class AJAX_TOKEN {

    function __construct() { //构造函数
        $this->adminLogged    = $GLOBALS["adminLogged"]; //获取已登录信息
        $this->obj_ajax       = new CLASS_AJAX();
        /*if ($this->adminLogged["alert"] != "y020102") { //未登录，抛出错误信息
            $this->obj_ajax->halt_alert($this->adminLogged["alert"]);
        }*/
    }


    /**
     * ajax_check function.
     *
     * @access public
     * @return void
     */
    function ajax_make() {
        if ($this->adminLogged["alert"] == "y020102") { //未登录，抛出错误信息
            $_str_token  = fn_token(); //生成口令
            $_str_alert  = "y030102";
            $_str_msg    = "ok";
        } else {
            $_str_token  = "none";
            $_str_alert  = "x020404";
            $_str_msg    = $this->obj_ajax->alert["x020404"];
        }

        $arr_re = array(
            "token"  => $_str_token,
            "alert"  => $_str_alert,
            "msg"    => $_str_msg,
        );

        exit(json_encode($arr_re));
    }
}
