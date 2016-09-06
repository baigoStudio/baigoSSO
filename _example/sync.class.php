<?php
define(BG_SSO_URL, "http://www.domain.com/api/api.php"); //SSO 地址
define(BG_SSO_APPID, 1); //APP ID
define(BG_SSO_APPKEY, ""); //APP KEY

include_once("func.php"); //载入 http
include_once("notify.class.php");
include_once("sso.class.php");

/*-------------同步类-------------*/
class API_SYNC {

    function __construct() { //构造函数
        $this->obj_notify = new CLASS_NOTIFY();
        $this->obj_sso    = new CLASS_SSO();
    }


    /**
     * api_list function.
     *
     * @access public
     * @return void
     */
    function api_login() {
        $_arr_notifyInput = $this->obj_notify->notify_input("get");
        if ($_arr_notifyInput["alert"] != "ok") {
            $this->obj_notify->halt_re($_arr_notifyInput);
        }

        $_arr_signature = $this->obj_sso->sso_verify(array_merge($this->arr_data, $_arr_notifyInput), $_arr_notifyInput["signature"]);
        if ($_arr_signature["alert"] != "y050403") {
            $this->obj_notify->halt_re($_arr_signature);
        }

        $_tm_diff = $_arr_notifyInput["time"] - time();

        if ($_tm_diff > 1800 || $_tm_diff < -1800) {
            $_arr_return = array(
                "alert"     => "x350213",
            );
            $this->obj_notify->halt_re($_arr_return);
        }

        $_arr_decode  = $this->obj_sso->sso_decode($_arr_notifyInput["code"]);

        $_arr_appChk    = $this->obj_notify->app_chk($_arr_decode["app_id"], $_arr_decode["app_key"]);
        if ($_arr_appChk["alert"] != "ok") {
            $this->obj_notify->halt_re($_arr_appChk);
        }


        /* 开始会话等操作  */


        $_arr_return = array(
            "alert" => "y020405",
        );
        $this->obj_notify->halt_re($_arr_return, false, true);
    }


    function api_logout() {
        $_arr_notifyInput = $this->obj_notify->notify_input("get");
        if ($_arr_notifyInput["alert"] != "ok") {
            $this->obj_notify->halt_re($_arr_notifyInput);
        }

        $_arr_signature = $this->obj_sso->sso_verify(array_merge($this->arr_data, $_arr_notifyInput), $_arr_notifyInput["signature"]);
        if ($_arr_signature["alert"] != "y050403") {
            $this->obj_notify->halt_re($_arr_signature);
        }

        $_tm_diff = $_arr_notifyInput["time"] - time();

        if ($_tm_diff > 1800 || $_tm_diff < -1800) {
            $_arr_return = array(
                "alert"     => "x350213",
            );
            $this->obj_notify->halt_re($_arr_return);
        }

        $_arr_decode  = $this->obj_sso->sso_decode($_arr_notifyInput["code"]);

        $_arr_appChk    = $this->obj_notify->app_chk($_arr_decode["app_id"], $_arr_decode["app_key"]);
        if ($_arr_appChk["alert"] != "ok") {
            $this->obj_notify->halt_re($_arr_appChk);
        }


        /* 结束会话等操作  */


        $_arr_return = array(
            "alert" => "y020406",
        );
        $this->obj_notify->halt_re($_arr_return, false, true);
    }
}
