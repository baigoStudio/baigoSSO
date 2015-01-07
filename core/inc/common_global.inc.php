<?php
/*-----------------------------------------------------------------

！！！！警告！！！！
以下为系统文件，请勿修改

-----------------------------------------------------------------*/

//不能非法包含或直接执行
if(!defined("IN_BAIGO")) {
	exit("Access Denied");
}

$ssid = $_GET["ssid"];

if ($ssid) {
	session_id($ssid); //将当前的SessionId设置成客户端传递回来的SessionId
}
session_start(); //开启session
$GLOBALS["ssid"] = session_id();

header("Content-Type: text/html; charset=utf-8");

include_once(BG_PATH_FUNC . "common.func.php"); //载入通用函数
include_once(BG_PATH_FUNC . "validate.func.php"); //载入表单验证函数

$act_post           = fn_getSafe($_POST["act_post"], "txt", ""); //表单动作
$act_get            = fn_getSafe($_GET["act_get"], "txt", ""); //查询串动作
$GLOBALS["view"]    = fn_getSafe($_GET["view"], "txt", ""); //查询串动作
?>