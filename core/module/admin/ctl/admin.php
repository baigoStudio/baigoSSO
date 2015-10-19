<?php
/*-----------------------------------------------------------------
！！！！警告！！！！
以下为系统文件，请勿修改
-----------------------------------------------------------------*/

//不能非法包含或直接执行
if(!defined("IN_BAIGO")) {
	exit("Access Denied");
}

include_once(BG_PATH_INC . "common_admin_ctl.inc.php"); //管理员通用
include_once(BG_PATH_INC . "is_install.inc.php"); //验证是否已登录
include_once(BG_PATH_INC . "is_admin.inc.php"); //验证是否已登录
include_once(BG_PATH_CONTROL . "admin/ctl/admin.class.php"); //载入管理员控制器

$ctl_admin = new CONTROL_ADMIN(); //初始化管理员对象

switch ($GLOBALS["act_get"]) {
	case "show": //显示
		$arr_adminRow = $ctl_admin->ctl_show();
		if ($arr_adminRow["alert"] != "y020102") {
			header("Location: " . BG_URL_ADMIN . "ctl.php?mod=alert&act_get=show&alert=" . $arr_adminRow["alert"]);
			exit;
		}
	break;

	case "form": //创建、编辑表单
		$arr_adminRow = $ctl_admin->ctl_form();
		if ($arr_adminRow["alert"] != "y020102") {
			header("Location: " . BG_URL_ADMIN . "ctl.php?mod=alert&act_get=show&alert=" . $arr_adminRow["alert"]);
			exit;
		}
	break;

	default: //列出
		$arr_adminRow = $ctl_admin->ctl_list();
		if ($arr_adminRow["alert"] != "y020302") {
			header("Location: " . BG_URL_ADMIN . "ctl.php?mod=alert&act_get=show&alert=" . $arr_adminRow["alert"]);
			exit;
		}
	break;
}
