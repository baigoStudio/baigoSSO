<?php
/*-----------------------------------------------------------------

！！！！警告！！！！
以下为系统文件，请勿修改

-----------------------------------------------------------------*/

//不能非法包含或直接执行
if(!defined("IN_BAIGO")) {
	exit("Access Denied");
}

include_once(BG_PATH_INC . "common_api.inc.php"); //验证是否已登录
include_once(BG_PATH_CONTROL_API . "sync.class.php"); //载入商家控制器

$api_sync = new API_SYNC(); //初始化商家

switch ($GLOBALS["act_get"]) {
	case "login":
		$api_sync->api_login();
	break;

	case "logout":
		$api_sync->api_logout();
	break;
}
