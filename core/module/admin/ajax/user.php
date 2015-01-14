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
include_once(BG_PATH_CONTROL_ADMIN . "ajax/user.class.php"); //载入商家控制器

$ajax_user = new AJAX_USER(); //初始化商家

switch ($GLOBALS["act_post"]) {
	case "submit":
		$ajax_user->ajax_submit();
	break;

	case "enable":
	case "wait":
	case "disable":
		$ajax_user->ajax_status();
	break;

	case "del":
		$ajax_user->ajax_del();
	break;

	default:
		switch ($GLOBALS["act_get"]) {
			case "chkname":
				$ajax_user->ajax_chkname();
			break;

			case "chkmail":
				$ajax_user->ajax_chkmail();
			break;
		}
	break;
}
?>