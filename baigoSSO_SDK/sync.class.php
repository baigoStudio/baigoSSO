<?php
define(BG_SSO_URL, "http://www.domain.com/api/api.php"); //SSO 地址
define(BG_SSO_APPID, 1); //APP ID
define(BG_SSO_APPKEY, ""); //APP KEY

include_once("func.php"); //载入 http
include_once(BG_PATH_CLASS . "notice.class.php");
include_once(BG_PATH_CLASS . "sso.class.php");

/*-------------文章类-------------*/
class API_SYNC {

    function __construct() { //构造函数
        $this->obj_notice = new CLASS_NOTICE();
        $this->obj_sso    = new CLASS_SSO();
    }


    /**
     * api_list function.
     *
     * @access public
     * @return void
     */
    function api_login() {
        $_arr_noticeInput = $this->obj_notice->notice_input("get");

        if ($_arr_noticeInput["alert"] != "ok") {
            $this->obj_notice->halt_re($_arr_noticeInput);
        }

        $_tm_now = time();

        if (($_arr_noticeInput["time"] - $_tm_now) > 300) {
            $_arr_return = array(
                "alert"     => "x220213",
            );
            $this->obj_notice->halt_re($_arr_return);
        }

        $_arr_signature = $this->obj_sso->sso_verify($_arr_noticeInput["time"], $_arr_noticeInput["random"], $_arr_noticeInput["signature"]);
        if ($_arr_signature["alert"] != "y050403") {
            $this->obj_notice->halt_re($_arr_signature);
        }

        $_arr_decode  = $this->obj_sso->sso_decode($_arr_noticeInput["code"], $_arr_noticeInput["key"]);

        if ($_arr_decode["app_id"] != BG_SSO_APPID) {
            $_arr_return = array(
                "alert"     => "x220208",
            );
            $this->obj_notice->halt_re($_arr_return);
        }

        if ($_arr_decode["app_key"] != BG_SSO_APPKEY) {
            $_arr_return = array(
                "alert"     => "x220212",
            );
            $this->obj_notice->halt_re($_arr_return);
        }


        /* 开始会话等操作  */


        $_arr_return = array(
            "alert" => "y020405",
        );
        $this->obj_notice->halt_re($_arr_return, false, true);
    }


    function api_logout() {
        $_arr_noticeInput = $this->obj_notice->notice_input("get");

        if ($_arr_noticeInput["alert"] != "ok") {
            $this->obj_notice->halt_re($_arr_noticeInput);
        }

        $_tm_now = time();

        if (($_arr_noticeInput["time"] - $_tm_now) > 300) {
            $_arr_return = array(
                "alert"     => "x220213",
            );
            $this->obj_notice->halt_re($_arr_return);
        }

        $_arr_signature = $this->obj_sso->sso_verify($_arr_noticeInput["time"], $_arr_noticeInput["random"], $_arr_noticeInput["signature"]);
        if ($_arr_signature["alert"] != "y050403") {
            $this->obj_notice->halt_re($_arr_signature);
        }

        $_arr_decode  = $this->obj_sso->sso_decode($_arr_noticeInput["code"], $_arr_noticeInput["key"]);

        if ($_arr_decode["app_id"] != BG_SSO_APPID) {
            $_arr_return = array(
                "alert"     => "x220208",
            );
            $this->obj_notice->halt_re($_arr_return);
        }

        if ($_arr_decode["app_key"] != BG_SSO_APPKEY) {
            $_arr_return = array(
                "alert"     => "x220212",
            );
            $this->obj_notice->halt_re($_arr_return);
        }


        /* 结束会话等操作  */


        $_arr_return = array(
            "alert" => "y020406",
        );
        $this->obj_notice->halt_re($_arr_return, false, true);
    }
}
