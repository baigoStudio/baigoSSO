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
include_once(BG_PATH_CONTROL_ADMIN . "ajax/log.class.php"); //载入商家控制器

$ajax_log = new AJAX_LOG(); //初始化商家

switch ($GLOBALS["act_post"]) {
	case "wait":
	case "read":
		$ajax_log->ajax_status();
	break;

	case "del":
		$ajax_log->ajax_del();
	break;
}
?>