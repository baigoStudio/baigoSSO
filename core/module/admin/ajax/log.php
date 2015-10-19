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
include_once(BG_PATH_CONTROL . "admin/ajax/log.class.php"); //载入日志 ajax 控制器

$ajax_log = new AJAX_LOG(); //初始化日志对象

switch ($GLOBALS["act_post"]) {
	case "wait":
	case "read":
		$ajax_log->ajax_status(); //状态
	break;

	case "del":
		$ajax_log->ajax_del();  //删除
	break;
}
