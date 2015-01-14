<?php
/*-----------------------------------------------------------------
！！！！警告！！！！
以下为系统文件，请勿修改
-----------------------------------------------------------------*/

//不能非法包含或直接执行
if(!defined("IN_BAIGO")) {
	exit("Access Denied");
}

include_once(BG_PATH_INC . "common_admin.inc.php"); //验证是否已登录
include_once(BG_PATH_CONTROL_ADMIN . "ajax/app.class.php"); //载入商家控制器

$ajax_app = new AJAX_APP(); //初始化商家

switch ($GLOBALS["act_post"]) {
	case "auth":
		$ajax_app->ajax_auth();
	break;

	case "deauth":
		$ajax_app->ajax_deauth();
	break;

	case "reset":
		$ajax_app->ajax_reset();
	break;

	case "submit":
		$ajax_app->ajax_submit();
	break;

	case "enable":
	case "disable":
		$ajax_app->ajax_status();
	break;

	case "del":
		$ajax_app->ajax_del();
	break;

	case "notice":
		$ajax_app->ajax_notice();
	break;
}
?>