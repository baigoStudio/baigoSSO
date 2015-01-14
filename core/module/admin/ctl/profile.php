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
include_once(BG_PATH_INC . "is_admin.inc.php"); //验证是否已登录
include_once(BG_PATH_CONTROL_ADMIN . "ctl/profile.class.php"); //载入栏目控制器

$ctl_profile = new CONTROL_PROFILE(); //初始化设置对象

switch ($GLOBALS["act_get"]) {
	case "pass":
		$arr_profileRow = $ctl_profile->ctl_pass();
		if ($arr_profileRow["str_alert"] != "y020109") {
			header("Location: " . BG_URL_ADMIN . "ctl.php?mod=alert&act_get=display&alert=" . $arr_profileRow["str_alert"]);
			exit;
		}
	break;

	default:
		$arr_profileRow = $ctl_profile->ctl_info();
		if ($arr_profileRow["str_alert"] != "y020108") {
			header("Location: " . BG_URL_ADMIN . "ctl.php?mod=alert&act_get=display&alert=" . $arr_profileRow["str_alert"]);
			exit;
		}
	break;
}
?>