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
include_once(BG_PATH_CONTROL . "admin/ajax/profile.class.php"); //载入个人信息 ajax 控制器

$ajax_profile = new AJAX_PROFILE(); //初始化个人信息对象

switch ($GLOBALS["act_post"]) {
	case "pass":
		$ajax_profile->ajax_pass(); //修改密码
	break;

	case "info":
		$ajax_profile->ajax_info(); //修改个人信息
	break;
}
