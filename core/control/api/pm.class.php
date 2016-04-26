<?php
/*-----------------------------------------------------------------
！！！！警告！！！！
以下为系统文件，请勿修改
-----------------------------------------------------------------*/

//不能非法包含或直接执行
if(!defined("IN_BAIGO")) {
    exit("Access Denied");
}

include_once(BG_PATH_FUNC . "baigocode.func.php"); //载入开放平台类
include_once(BG_PATH_CLASS . "api.class.php"); //载入模板类
include_once(BG_PATH_MODEL . "app.class.php"); //载入后台用户类
include_once(BG_PATH_MODEL . "pm.class.php"); //载入后台用户类
include_once(BG_PATH_MODEL . "log.class.php"); //载入管理帐号模型
include_once(BG_PATH_MODEL . "user.class.php"); //载入后台用户类

/*-------------用户类-------------*/
class API_PM {

    private $obj_api;
    private $log;
    private $mdl_pm;
    private $appAllow;
    private $appRequest;

    function __construct() { //构造函数
        $this->obj_api  = new CLASS_API();
        $this->obj_api->chk_install();
        $this->log      = $this->obj_api->log;
        $this->mdl_pm   = new MODEL_PM();
        $this->mdl_app  = new MODEL_APP();
        $this->mdl_log  = new MODEL_LOG();
        $this->mdl_user = new MODEL_USER();
    }

    /**
     * api_reg function.
     *
     * @access public
     * @return void
     */
    function api_send() {
        $this->app_check("post");

        if (!isset($this->appAllow["pm"]["send"])) { //无权限并记录日志
            $_arr_return = array(
                "alert" => "x050320",
            );
            $_arr_logType = array("pm", "send");
            $_arr_logTarget[] = array(
                "app_id" => $this->appRequest["app_id"],
            );
            $this->log_do($_arr_logTarget, "app", $_arr_return, $_arr_logType);
            $this->obj_api->halt_re($_arr_return);
        }

        $_arr_userRow   = $this->user_check("post");
        $_arr_pmSend    = $this->mdl_pm->input_send();

        if ($_arr_pmSend["alert"] != "ok") {
            $this->obj_ajax->halt_alert($_arr_pmSend["alert"]);
        }

        if (stristr($_arr_pmSend["pm_to"], "|")) {
            $_arr_pmTo = explode("|", $_arr_pmSend["pm_to"]);
        } else {
            $_arr_pmTo = array($_arr_pmSend["pm_to"]);
        }

        $_arr_pmTo = array_unique($_arr_pmTo);

        $_arr_pmRows = array();

        foreach ($_arr_pmTo as $_key=>$_value) {
            $_arr_toUser = $this->mdl_user->mdl_read($_value, "user_name");
            if ($_arr_toUser["alert"] == "y010102") {
                $_arr_pmRows[$_key] = $this->mdl_pm->mdl_submit($_arr_toUser["user_id"], $_arr_userRow["user_id"]);
                $_arr_pmRows[$_key]["pm_to"] = $_arr_toUser["user_id"];
            }
        }

        $_str_key   = fn_rand(6);
        $_str_code  = $this->obj_api->api_encode($_arr_pmRows, $_str_key);  //生成结果

        $_arr_return = array(
            "code"   => $_str_code,
            "key"    => $_str_key,
        );

        $_arr_return["alert"]   = $_arr_pmRows[$_key]["alert"];

        $this->obj_api->halt_re($_arr_return);
    }


    function api_rev() {
        $this->app_check("post");

        if (!isset($this->appAllow["pm"]["rev"])) {
            $_arr_return = array(
                "alert" => "x050321",
            );
            $_arr_logTarget[] = array(
                "app_id" => $this->appRequest["app_id"],
            );
            $_arr_logType = array("pm", "rev");
            $this->log_do($_arr_logTarget, "app", $_arr_return, $_arr_logType);
            $this->obj_api->halt_re($_arr_return);
        }

        $_arr_userRow = $this->user_check("post");

        $_arr_pmIds = $this->mdl_pm->input_ids();
        if ($_arr_pmIds["alert"] != "ok") {
            $this->obj_api->halt_re($_arr_pmIds);
        }

        $_arr_pmDel = $this->mdl_pm->mdl_del($_arr_userRow["user_id"], true);

        $this->obj_api->halt_re($_arr_pmDel);
    }


    function api_status() {
        $this->app_check("post");

        if (!isset($this->appAllow["pm"]["status"])) {
            $_arr_return = array(
                "alert" => "x050321",
            );
            $_arr_logTarget[] = array(
                "app_id" => $this->appRequest["app_id"],
            );
            $_arr_logType = array("pm", "status");
            $this->log_do($_arr_logTarget, "app", $_arr_return, $_arr_logType);
            $this->obj_api->halt_re($_arr_return);
        }

        $_arr_userRow = $this->user_check("post");

        $_arr_pmIds = $this->mdl_pm->input_ids();
        if ($_arr_pmIds["alert"] != "ok") {
            $this->obj_api->halt_re($_arr_pmIds);
        }

        $_str_status = fn_getSafe(fn_post("pm_status"), "txt", "");
        if (!$_str_status) {
            $_arr_return = array(
                "alert" => "x110219",
            );
            $this->obj_api->halt_re($_arr_return);
        }
        $_arr_pmStatus = $this->mdl_pm->mdl_status($_str_status, $_arr_userRow["user_id"]);

        $this->obj_api->halt_re($_arr_pmStatus);
    }

    /**
     * api_del function.
     *
     * @access public
     * @return void
     */
    function api_del() {
        $this->app_check("post");

        if (!isset($this->appAllow["pm"]["del"])) {
            $_arr_return = array(
                "alert" => "x050309",
            );
            $_arr_logTarget[] = array(
                "app_id" => $this->appRequest["app_id"],
            );
            $_arr_logType = array("pm", "del");
            $this->log_do($_arr_logTarget, "app", $_arr_return, $_arr_logType);
            $this->obj_api->halt_re($_arr_return);
        }

        $_arr_userRow = $this->user_check("post");

        $_arr_pmIds = $this->mdl_pm->input_ids();
        if ($_arr_pmIds["alert"] != "ok") {
            $this->obj_api->halt_re($_arr_pmIds);
        }

        $_arr_pmDel = $this->mdl_pm->mdl_del($_arr_userRow["user_id"]);

        $this->obj_api->halt_re($_arr_pmDel);
    }


    /**
     * api_read function.
     *
     * @access public
     * @return void
     */
    function api_read() {
        $this->app_check("get");

        if (!isset($this->appAllow["pm"]["read"])) {
            $_arr_return = array(
                "alert" => "x050319",
            );
            $_arr_logTarget[] = array(
                "app_id" => $this->appRequest["app_id"],
            );
            $_arr_logType = array("pm", "read");
            $this->log_do($_arr_logTarget, "app", $_arr_return, $_arr_logType);
            $this->obj_api->halt_re($_arr_return);
        }

        $_arr_userRow = $this->user_check("get");

        $_num_pmId = fn_getSafe(fn_get("pm_id"), "int", 0);
        if ($_num_pmId < 1) {
            $_arr_return = array(
                "alert" => "x110211",
            );
            $this->obj_api->halt_re($_arr_return);
        }

        $_arr_pmRow = $this->mdl_pm->mdl_read($_num_pmId);
        if ($_arr_pmRow["alert"] != "y110102") {
            $this->obj_api->halt_re($_arr_pmRow);
        }

        if ($_arr_pmRow["pm_from"] != $_arr_userRow["user_id"] && $_arr_pmRow["pm_to"] != $_arr_userRow["user_id"]) {
            $_arr_return = array(
                "alert" => "x110403",
            );
            $this->obj_api->halt_re($_arr_return);
        }

        $_arr_pmRow["fromUser"] = $this->mdl_user->mdl_read_api($_arr_pmRow["pm_from"]);
        $_arr_pmRow["toUser"]   = $this->mdl_user->mdl_read_api($_arr_pmRow["pm_to"]);

        $_str_key   = fn_rand(6);
        $_str_code  = $this->obj_api->api_encode($_arr_pmRow, $_str_key);

        $_arr_return = array(
            "code"   => $_str_code,
            "key"    => $_str_key,
            "alert"  => $_arr_pmRow["alert"],
        );

        $this->obj_api->halt_re($_arr_return);
    }


    function api_check() {
        $this->app_check("get");

        if (!isset($this->appAllow["pm"]["check"])) {
            $_arr_return = array(
                "alert" => "x050319",
            );
            $_arr_logTarget[] = array(
                "app_id" => $this->appRequest["app_id"],
            );
            $_arr_logType = array("pm", "check");
            $this->log_do($_arr_logTarget, "app", $_arr_return, $_arr_logType);
            $this->obj_api->halt_re($_arr_return);
        }

        $_arr_userRow = $this->user_check("get");

        $_arr_search = array(
            "type"      => "in",
            "pm_to"     => $_arr_userRow["user_id"],
            "status"    => fn_getSafe(fn_get("status"), "txt", "wait"),
        );

        $_num_pmCount   = $this->mdl_pm->mdl_count($_arr_search);

        $_arr_return = array(
            "pm_count"  => $_num_pmCount,
            "alert"     => "y110402",
        );

        $this->obj_api->halt_re($_arr_return);
    }


    /**
     * api_chkname function.
     *
     * @access public
     * @return void
     */
    function api_list() {
        $this->app_check("get");

        if (!isset($this->appAllow["pm"]["list"])) {
            $_arr_return = array(
                "alert" => "x050319",
            );
            $_arr_logTarget[] = array(
                "app_id" => $this->appRequest["app_id"],
            );
            $_arr_logType = array("pm", "list");
            $this->log_do($_arr_logTarget, "app", $_arr_return, $_arr_logType);
            $this->obj_api->halt_re($_arr_return);
        }

        $_arr_userRow = $this->user_check("get");

        $_num_perPage   = fn_getSafe(fn_get("per_page"), "int", BG_SITE_PERPAGE);
        $_str_type      = fn_getSafe(fn_get("pm_type"), "txt", "");
        $_str_pmIds     = fn_getSafe(fn_get("pm_ids"), "txt", "");

        $_arr_pmIds = array();

        if ($_str_pmIds) {
            if (stristr($_str_pmIds, "|")) {
                $_arr_pmIds = explode("|", $_str_pmIds);
            } else {
                $_arr_pmIds = array($_str_pmIds);
            }
        }

        if (!$_str_type) {
            $_arr_return = array(
                "alert" => "x110218",
            );
            $this->obj_api->halt_re($_arr_return);
        }

        $_arr_search = array(
            "type"      => $_str_type,
            "status"    => fn_getSafe(fn_get("pm_status"), "txt", ""),
            "key"       => fn_getSafe(fn_get("key"), "txt", ""),
            "pm_ids"    => $_arr_pmIds,
        );

        switch ($_str_type) {
            case "in":
                $_arr_search["pm_to"] = $_arr_userRow["user_id"];
            break;

            case "out":
                $_arr_search["pm_from"] = $_arr_userRow["user_id"];
            break;
        }

        $_num_pmCount   = $this->mdl_pm->mdl_count($_arr_search);
        $_arr_page      = fn_page($_num_pmCount);
        $_arr_pmRows    = $this->mdl_pm->mdl_list($_num_perPage, $_arr_page["except"], $_arr_search);

        foreach ($_arr_pmRows as $_key=>$_value) {
            $_arr_pmRows[$_key]["fromUser"] = $this->mdl_user->mdl_read_api($_value["pm_from"]);
            $_arr_pmRows[$_key]["toUser"]   = $this->mdl_user->mdl_read_api($_value["pm_to"]);
        }

        $_arr_return = array(
            "pmRows"    => $_arr_pmRows,
            "pageRow"   => $_arr_page,
        );

        $_str_key   = fn_rand(6);
        $_str_code  = $this->obj_api->api_encode($_arr_return, $_str_key);

        $_arr_return = array(
            "code"   => $_str_code,
            "key"    => $_str_key,
            "alert"  => "y110402",
        );

        $this->obj_api->halt_re($_arr_return);
    }


    private function user_check($str_method = "get") {
        $_arr_userRequest = $this->mdl_user->input_token_api($str_method);
        if ($_arr_userRequest["alert"] != "ok") {
            $this->obj_api->halt_re($_arr_userRequest);
        }

        $_arr_userRow = $this->mdl_user->mdl_read($_arr_userRequest["user_str"], $_arr_userRequest["user_by"]);
        if ($_arr_userRow["alert"] != "y010102") {
            $this->obj_api->halt_re($_arr_userRow);
        }

        if ($_arr_userRow["user_status"] == "disable") {
            $_arr_return = array(
                "alert" => "x010401",
            );
            $this->obj_api->halt_re($_arr_return);
        }

        if ($_arr_userRow["user_access_expire"] < time()) {
            $_arr_return = array(
                "alert" => "x010231",
            );
            $this->obj_api->halt_re($_arr_return);
        }

        if ($_arr_userRequest["user_access_token"] != $_arr_userRow["user_access_token"]) {
            $_arr_return = array(
                "alert" => "x010230",
            );
            $this->obj_api->halt_re($_arr_return);
        }

        return $_arr_userRow;
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
        $this->appRequest = $this->obj_api->app_request($str_method);

        if ($this->appRequest["alert"] != "ok") {
            $this->obj_api->halt_re($this->appRequest);
        }

        $_arr_logTarget[] = array(
            "app_id" => $this->appRequest["app_id"]
        );

        $_arr_appRow = $this->mdl_app->mdl_read($this->appRequest["app_id"]);
        if ($_arr_appRow["alert"] != "y050102") {
            $_arr_logType = array("app", "read");
            $this->log_do($_arr_logTarget, "app", $_arr_appRow, $_arr_logType);
            $this->obj_api->halt_re($_arr_appRow);
        }
        $this->appAllow = $_arr_appRow["app_allow"];

        $_arr_appChk = $this->obj_api->app_chk($this->appRequest, $_arr_appRow);
        if ($_arr_appChk["alert"] != "ok") {
            $_arr_logType = array("app", "check");
            $this->log_do($_arr_logTarget, "app", $_arr_appChk, $_arr_logType);
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
    private function log_do($arr_logTarget, $str_targetType, $arr_logResult, $arr_logType) {
        $_str_targets = json_encode($arr_logTarget);
        $_str_result  = json_encode($arr_logResult);
        $this->mdl_log->mdl_submit($_str_targets, $str_targetType, $this->log[$arr_logType[0]][$arr_logType[1]], $_str_result, "app", $this->appRequest["app_id"]);
    }
}
