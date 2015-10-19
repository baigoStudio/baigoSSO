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
include_once(BG_PATH_CONTROL . "admin/ajax/admin.class.php"); //载入管理员 ajax 控制器

$ajax_admin = new AJAX_ADMIN(); //初始化管理员对象

switch ($GLOBALS["act_post"]) {
	case "submit":
		$ajax_admin->ajax_submit(); //创建、编辑
	break;

	case "del":
		$ajax_admin->ajax_del(); //删除
	break;

	case "enable":
	case "disable":
		$ajax_admin->ajax_status(); //状态
	break;

	default:
		switch ($GLOBALS["act_get"]) {
			case "chkname":
				$ajax_admin->ajax_chkname(); //验证用户名
			break;
		}
	break;
}
