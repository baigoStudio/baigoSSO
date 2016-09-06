<?php
/*-----------------------------------------------------------------
！！！！警告！！！！
以下为系统文件，请勿修改
-----------------------------------------------------------------*/

//不能非法包含或直接执行
if (!defined("IN_BAIGO")) {
    exit("Access Denied");
}

include_once(BG_PATH_CLASS . "api.class.php"); //载入模板类
include_once(BG_PATH_CLASS . "sign.class.php"); //载入模板类
include_once(BG_PATH_MODEL . "app.class.php"); //载入后台用户类
include_once(BG_PATH_MODEL . "log.class.php"); //载入管理帐号模型

/*-------------用户类-------------*/
class API_SIGNATURE {

    private $obj_api;
    private $log;
    private $mdl_app;
    private $appAllow;
    private $appRequest;

    function __construct() { //构造函数
        $this->obj_api  = new CLASS_API();
        $this->obj_api->chk_install();
        $this->log      = $this->obj_api->log; //初始化 AJAX 基对象
        $this->obj_sign = new CLASS_SIGN();
        $this->mdl_app  = new MODEL_APP(); //设置管理组模型
        $this->mdl_log  = new MODEL_LOG(); //设置管理员模型
    }


    /**
     * api_signature function.
     *
     * @access public
     * @return void
     */
    function api_signature() {
        $this->app_check("post");

        $_arr_params = fn_post("params");

        if ($_arr_params) {
            foreach ($_arr_params as $_key=>$_value) {
                if (!fn_isEmpty($_value)) {
                    $_arr_paramsSrc[$_key] = fn_getSafe($_value, "txt", "");
                }
            }
        }

        $_str_sign = $this->obj_sign->sign_make($_arr_paramsSrc);

        $_arr_return = array(
            "signature"  => $_str_sign,
            "alert"      => "y050404",
        );

        $this->obj_api->halt_re($_arr_return);
    }


    /**
     * api_verify function.
     *
     * @access public
     * @return void
     */
    function api_verify() {
        $this->app_check("post");

        $_arr_params = fn_post("params");

        $_arr_signature = validateStr(fn_post("signature"), 1, 0);
        switch ($_arr_signature["status"]) {
            case "too_short":
                $_arr_return = array(
                    "alert" => "x050226",
                );
                $this->obj_api->halt_re($_arr_return);
            break;

            case "ok":
                $_str_sign = $_arr_signature["str"];
            break;
        }

        if ($this->obj_sign->sign_check($_arr_params, $_str_sign)) {
            $_str_alert = "y050403";
        } else {
            $_str_alert = "x050403";
        }

        $_arr_return = array(
            "alert" => $_str_alert,
        );

        $this->obj_api->halt_re($_arr_return);
    }


    /**
     * app_check function.
     *
     * @access private
     * @return void
     */
    private function app_check($str_method = "get") {
        $this->appRequest = $this->obj_api->app_request($str_method);

        if ($this->appRequest["alert"] != "ok") {
            $this->obj_api->halt_re($this->appRequest);
        }

        $_arr_appRow = $this->mdl_app->mdl_read($this->appRequest["app_id"]);
        if ($_arr_appRow["alert"] != "y050102") {
            $this->log_do($_arr_appRow, "read");
            $this->obj_api->halt_re($_arr_appRow);
        }

        $_arr_appChk = $this->obj_api->app_chk($this->appRequest, $_arr_appRow);
        if ($_arr_appChk["alert"] != "ok") {
            $this->log_do($_arr_appChk, "check");
            $this->obj_api->halt_re($_arr_appChk);
        }
    }


    /**
     * log_do function.
     *
     * @access private
     * @param mixed $arr_logResult
     * @param mixed $str_logType
     * @return void
     */
    private function log_do($arr_logResult, $str_logType) {
        $_arr_targets[] = array(
            "app_id" => $this->appRequest["app_id"],
        );
        $_str_targets     = json_encode($_arr_targets);
        $_str_logResult   = json_encode($arr_logResult);

        $_arr_logData = array(
            "log_targets"        => $_str_targets,
            "log_target_type"    => "app",
            "log_title"          => $this->log["app"][$str_logType],
            "log_result"         => $_str_logResult,
            "log_type"           => "app",
        );

        $this->mdl_log->mdl_submit($_arr_logData, $this->appRequest["app_id"]);
    }
}
