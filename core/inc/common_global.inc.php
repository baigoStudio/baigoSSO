<?php
/*-----------------------------------------------------------------
！！！！警告！！！！
以下为系统文件，请勿修改
-----------------------------------------------------------------*/

//不能非法包含或直接执行
if(!defined("IN_BAIGO")) {
	exit("Access Denied");
}

if (isset($_GET["ssid"])) {
	session_id($_GET["ssid"]); //将当前的 SessionId 设置成客户端传递回来的 SessionId
}

session_start(); //开启session
$GLOBALS["ssid"] = session_id();

//error_reporting(E_ALL);
error_reporting(E_ALL & ~E_NOTICE); //php 错误输出

header("Content-Type: text/html; charset=utf-8");

include_once(BG_PATH_FUNC . "common.func.php"); //载入通用函数
include_once(BG_PATH_FUNC . "validate.func.php"); //载入表单验证函数

$GLOBALS["act_post"]    = fn_getSafe(fn_post("act_post"), "txt", ""); //表单动作
$GLOBALS["act_get"]     = fn_getSafe(fn_get("act_get"), "txt", ""); //查询串动作
$GLOBALS["view"]        = fn_getSafe(fn_request("view"), "txt", ""); //界面 (是否 iframe)
?>