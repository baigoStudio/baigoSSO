<?php
/*-----------------------------------------------------------------
！！！！警告！！！！
以下为系统文件，请勿修改
-----------------------------------------------------------------*/

//不能非法包含或直接执行
if(!defined("IN_BAIGO")) {
	exit("Access Denied");
}

include_once(BG_PATH_INC . "common_admin_ajax.inc.php"); //管理员通用
include_once(BG_PATH_CONTROL_ADMIN . "ajax/app.class.php"); //载入应用 ajax 控制器

$ajax_app = new AJAX_APP(); //初始化应用对象

switch ($GLOBALS["act_post"]) {
	case "auth":
		$ajax_app->ajax_auth(); //授权用户
	break;

	case "deauth":
		$ajax_app->ajax_deauth(); //取消授权用户
	break;

	case "reset":
		$ajax_app->ajax_reset(); //重置 APP KEY
	break;

	case "submit":
		$ajax_app->ajax_submit(); //创建、编辑
	break;

	case "enable":
	case "disable":
		$ajax_app->ajax_status(); //状态
	break;

	case "del":
		$ajax_app->ajax_del(); //删除
	break;

	case "notice":
		$ajax_app->ajax_notice(); //通知测试
	break;
}
