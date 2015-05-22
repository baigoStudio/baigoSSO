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

include_once(BG_PATH_INC . "common_global.inc.php"); //载入通用
include_once(BG_PATH_CONTROL_ADMIN . "ctl/seccode.class.php"); //载入验证码类

$ctl_seccode = new CONTROL_SECCODE(); //初始化验证对象

switch ($GLOBALS["act_get"]) {
	case "make":
		$ctl_seccode->ctl_make(); //生成验证码
	break;
}
