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
include_once(BG_PATH_CONTROL . "admin/ctl/user.class.php"); //载入用户控制器

$ctl_user = new CONTROL_USER(); //初始化用户

switch ($GLOBALS["act_get"]) {
	default:
		$arr_userRow = $ctl_user->ctl_list(); //列出
		if ($arr_userRow["alert"] != "y010302") {
			header("Location: " . BG_URL_ADMIN . "ctl.php?mod=alert&act_get=show&alert=" . $arr_userRow["alert"]);
			exit;
		}
	break;
}
