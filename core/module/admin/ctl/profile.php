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
include_once(BG_PATH_CONTROL_ADMIN . "ctl/profile.class.php"); //载入个人信息控制器

$ctl_profile = new CONTROL_PROFILE(); //初始化个人信息

switch ($GLOBALS["act_get"]) {
	case "pass": //修改密码
		$arr_profileRow = $ctl_profile->ctl_pass();
		if ($arr_profileRow["alert"] != "y020109") {
			header("Location: " . BG_URL_ADMIN . "ctl.php?mod=alert&act_get=display&alert=" . $arr_profileRow["alert"]);
			exit;
		}
	break;

	default: //修改个人信息
		$arr_profileRow = $ctl_profile->ctl_info();
		if ($arr_profileRow["alert"] != "y020108") {
			header("Location: " . BG_URL_ADMIN . "ctl.php?mod=alert&act_get=display&alert=" . $arr_profileRow["alert"]);
			exit;
		}
	break;
}
