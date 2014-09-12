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
include_once(BG_PATH_CONTROL_ADMIN . "ajax/admin.class.php"); //载入栏目控制器

$ajax_admin = new AJAX_ADMIN(); //初始化设置对象

switch ($act_post) {
	case "submit":
		$ajax_admin->ajax_submit();
	break;

	case "del":
		$ajax_admin->ajax_del();
	break;

	case "enable":
	case "disable":
		$ajax_admin->ajax_status();
	break;

	default:
		switch ($act_get) {
			case "chkname":
				$ajax_admin->ajax_chkname();
			break;
		}
	break;
}
?>