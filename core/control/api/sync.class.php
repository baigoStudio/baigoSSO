<?php
/*-----------------------------------------------------------------
！！！！警告！！！！
以下为系统文件，请勿修改
-----------------------------------------------------------------*/

//不能非法包含或直接执行
if(!defined("IN_BAIGO")) {
    exit("Access Denied");
}

include_once(BG_PATH_FUNC . "http.func.php"); //载入开放平台类
include_once(BG_PATH_FUNC . "baigocode.func.php"); //载入开放平台类
include_once(BG_PATH_CLASS . "sync.class.php"); //载入模板类
include_once(BG_PATH_MODEL . "app.class.php"); //载入后台用户类
include_once(BG_PATH_MODEL . "belong.class.php");
include_once(BG_PATH_MODEL . "user.class.php"); //载入后台用户类
include_once(BG_PATH_MODEL . "log.class.php"); //载入管理帐号模型

/*-------------用户类-------------*/
class API_SYNC {

    private $obj_sync;
    private $log;
    private $mdl_user;
    private $appAllow;
    private $appRows;
    private $appRequest;

    function __construct() { //构造函数
        $this->obj_sync     = new CLASS_SYNC();
        $this->obj_sync->chk_install();
        $this->log          = $this->obj_sync->log; //初始化 AJAX 基对象
        $this->mdl_user     = new MODEL_USER(); //设置管理组模型
        $this->mdl_app      = new MODEL_APP(); //设置管理组模型
        $this->mdl_belong   = new MODEL_BELONG();
        $this->mdl_log      = new MODEL_LOG(); //设置管理员模型
    }


    function api_login() {
        $this->app_check("get");

        $_arr_userId = validateStr($this->appRequest["user_id"], 1, 0, "str", "int");
        switch ($_arr_userId["status"]) {
            case "too_short":
                $_arr_return = array(
                    "alert" => "x010217",
                );
                $this->obj_sync->halt_re($_arr_return);
            break;

            case "format_err":
                $_arr_return = array(
                    "alert" => "x010218",
                );
                $this->obj_sync->halt_re($_arr_return);
            break;

            case "ok":
                $_num_userId = $_arr_userId["str"];
            break;
        }

        $_arr_userRow = $this->mdl_user->mdl_read($_num_userId);

        if ($_arr_userRow["alert"] != "y010102") {
            $this->obj_sync->halt_re($_arr_userRow);
        }

        if ($_arr_userRow["user_status"] == "disable") {
            $_arr_return = array(
                "alert" => "x010401",
            );
            $this->obj_api->halt_re($_arr_return);
        }

        unset($_arr_userRow["user_pass"], $_arr_userRow["user_mail"], $_arr_userRow["user_nick"], $_arr_userRow["user_note"], $_arr_userRow["user_rand"], $_arr_userRow["user_status"], $_arr_userRow["user_time"], $_arr_userRow["user_time_login"], $_arr_userRow["user_ip"]);

        $_arr_urlRows = array();

        foreach ($this->appRows as $_key=>$_value) {
            $_arr_userRow["app_id"]   = $_value["app_id"];
            $_arr_userRow["app_key"]  = $_value["app_key"];

            $_str_key     = fn_rand(6);
            $_str_code    = $this->obj_sync->sync_encode($_arr_userRow, $_str_key);

            $_tm_time    = time();
            $_str_rand   = fn_rand();
            $_str_sign   = fn_baigoSignMk($_tm_time, $_str_rand, $_value["app_id"], $_value["app_key"]);

            if (stristr($_value["app_url_sync"], "?")) {
                $_str_conn = "&";
            } else {
                $_str_conn = "?";
            }
            $_str_url = $_value["app_url_sync"] . $_str_conn . "mod=sync";

            $_arr_data = array(
                "act_get"    => "login",
                "time"       => $_tm_time,
                "random"     => $_str_rand,
                "signature"  => $_str_sign,
                "code"       => $_str_code,
                "key"        => $_str_key,
            );

            $_arr_urlRows[] = urlencode($_str_url . "&" . http_build_query($_arr_data));
        }

        $_arr_return = array(
            "alert"      => "y100401",
            "urlRows"    => $_arr_urlRows,
        );

        $this->obj_sync->halt_re($_arr_return);
    }


    function api_logout() {
        $this->app_check("get");

        $_arr_userId = validateStr($this->appRequest["user_id"], 1, 0, "str", "int");
        switch ($_arr_userId["status"]) {
            case "too_short":
                $_arr_return = array(
                    "alert" => "x010217",
                );
                $this->obj_sync->halt_re($_arr_return);
            break;

            case "format_err":
                $_arr_return = array(
                    "alert" => "x010218",
                );
                $this->obj_sync->halt_re($_arr_return);
            break;

            case "ok":
                $_num_userId = $_arr_userId["str"];
            break;
        }

        $_arr_userRow = $this->mdl_user->mdl_read($_num_userId);

        if ($_arr_userRow["alert"] != "y010102") {
            $this->obj_sync->halt_re($_arr_userRow);
        }

        if ($_arr_userRow["user_status"] == "disable") {
            $_arr_return = array(
                "alert" => "x010401",
            );
            $this->obj_api->halt_re($_arr_return);
        }

        unset($_arr_userRow["user_pass"], $_arr_userRow["user_mail"], $_arr_userRow["user_nick"], $_arr_userRow["user_note"], $_arr_userRow["user_rand"], $_arr_userRow["user_status"], $_arr_userRow["user_time"], $_arr_userRow["user_time_login"], $_arr_userRow["user_ip"]);

        $_str_key     = fn_rand(6);
        $_arr_code    = $_arr_userRow;
        $_arr_urlRows = array();

        foreach ($this->appRows as $_key=>$_value) {
            $_tm_time                = time();
            $_str_rand               = fn_rand();
            $_str_sign               = fn_baigoSignMk($_tm_time, $_str_rand, $_value["app_id"], $_value["app_key"]);
            $_arr_code["app_id"]     = $_value["app_id"];
            $_arr_code["app_key"]    = $_value["app_key"];
            $_str_code               = $this->obj_sync->sync_encode($_arr_code, $_str_key);

            if (stristr($_value["app_url_sync"], "?")) {
                $_str_conn = "&";
            } else {
                $_str_conn = "?";
            }
            $_str_url = $_value["app_url_sync"] . $_str_conn . "mod=sync";

            $_arr_data = array(
                "act_get"    => "logout",
                "time"       => $_tm_time,
                "random"     => $_str_rand,
                "signature"  => $_str_sign,
                "code"       => $_str_code,
                "key"        => $_str_key,
            );

            $_arr_urlRows[] = urlencode($_str_url . "&" . http_build_query($_arr_data));
        }

        $_arr_return = array(
            "alert"      => "y100402",
            "urlRows"    => $_arr_urlRows,
        );

        $this->obj_sync->halt_re($_arr_return);
    }


    /**
     * app_check function.
     *
     * @access private
     * @param mixed $num_appId
     * @param string $str_method (default: "get")
     * @return void
     */
    private function app_check($str_method = "get") {
        $this->appRequest = $this->obj_sync->sync_fetch();

        if ($this->appRequest["alert"] != "ok") {
            $this->obj_sync->halt_re($this->appRequest);
        }

        $_arr_logTarget[] = array(
            "app_id" => $this->appRequest["app_id"]
        );

        $this->appRow = $this->mdl_app->mdl_read($this->appRequest["app_id"]);
        if ($this->appRow["alert"] != "y050102") {
            $_arr_logType = array("app", "read");
            $this->log_do($_arr_logTarget, "app", $this->appRow, $_arr_logType);
            $this->obj_sync->halt_re($this->appRow);
        }

        $_arr_appChk = $this->obj_sync->app_chk($this->appRequest, $this->appRow);
        if ($_arr_appChk["alert"] != "ok") {
            $_arr_logType = array("app", "check");
            $this->log_do($_arr_logTarget, "app", $_arr_appChk, $_arr_logType);
            $this->obj_sync->halt_re($_arr_appChk);
        }

        $_arr_search = array(
            "status"        => "enable",
            "sync"          => "on",
            "has_notice"    => true,
            "not_ids"       => array($this->appRequest["app_id"]),
        );
        $this->appRows = $this->mdl_app->mdl_list(100, 0, $_arr_search);
    }


    /**
     * log_do function.
     *
     * @access private
     * @param mixed $arr_logResult
     * @param mixed $str_logType
     * @return void
     */
    private function log_do($arr_logTarget, $str_targetType, $arr_logResult, $arr_logType) {
        $_str_targets = json_encode($arr_logTarget);
        $_str_result  = json_encode($arr_logResult);
        $this->mdl_log->mdl_submit($_str_targets, $str_targetType, $this->log[$arr_logType[0]][$arr_logType[1]], $_str_result, "app", $this->appRequest["app_id"]);
    }

}
