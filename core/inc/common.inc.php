<?php
/*-----------------------------------------------------------------
！！！！警告！！！！
以下为系统文件，请勿修改
-----------------------------------------------------------------*/

//不能非法包含或直接执行
if (!defined("IN_BAIGO")) {
    exit("Access Denied");
}

if (defined("BG_DEBUG_SYS") && BG_DEBUG_SYS == 1) {
    error_reporting(E_ALL);
} else {
    error_reporting(0);
}

require(BG_PATH_FUNC . "common.func.php"); //载入通用函数
require(BG_PATH_FUNC . "validate.func.php"); //载入表单验证函数

$GLOBALS["method"]  = fn_getSafe(fn_server("REQUEST_METHOD"), "txt", "");
$GLOBALS["act"]     = fn_getSafe(fn_request("act"), "txt", ""); //动作
$GLOBALS["view"]    = fn_getSafe(fn_request("view"), "txt", ""); //界面 (是否 iframe)

function fn_chkPHP($arr_set) {
    if (version_compare(PHP_VERSION, "5.3.0", "<")) { //php 版本 5.3.0 或以上
        if (!isset($arr_set["dsp_type"])) {
            switch ($GLOBALS["method"]) {
                case "POST":
                    $arr_set["dsp_type"] = "post";
                break;

                default:
                    $arr_set["dsp_type"] = "html";
                break;
            }
        }

        switch ($arr_set["dsp_type"]) {
            case "result":
            case "post":
                $_arr_return = array(
                    "rcode" => "x030113",
                    "msg"   => "err_php_ver",
                );
                exit(json_encode($_arr_return));
            break;

            default:
                header("Location: " . BG_URL_ROOT . "err_php_ver.html");
                exit;
            break;
        }
    }
}
