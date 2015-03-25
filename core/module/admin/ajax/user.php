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
include_once(BG_PATH_CONTROL_ADMIN . "ajax/user.class.php"); //载入用户控制器

$ajax_user = new AJAX_USER(); //初始化用户

switch ($GLOBALS["act_post"]) {
	case "submit":
		$ajax_user->ajax_submit(); //创建、编辑
	break;

	case "enable":
	case "wait":
	case "disable":
		$ajax_user->ajax_status(); //状态
	break;

	case "del":
		$ajax_user->ajax_del(); //删除
	break;

	default:
		switch ($GLOBALS["act_get"]) {
			case "chkname":
				$ajax_user->ajax_chkname(); //验证用户名
			break;

			case "chkmail":
				$ajax_user->ajax_chkmail(); //验证 email
			break;
		}
	break;
}
