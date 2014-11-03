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

include_once(BG_PATH_CONTROL_ADMIN . "ctl/app.class.php"); //载入商家控制器
$ctl_app = new CONTROL_APP(); //初始化商家

switch ($act_get) {
	case "show":
		$arr_appRow = $ctl_app->ctl_show();
		if ($arr_appRow["str_alert"] != "y050102") {
			header("Location: " . BG_URL_ADMIN . "ctl.php?mod=alert&act_get=display&alert=" . $arr_appRow["str_alert"]);
			exit;
		}
	break;

	case "form":
		$arr_appRow = $ctl_app->ctl_form();
		if ($arr_appRow["str_alert"] != "y050102") {
			header("Location: " . BG_URL_ADMIN . "ctl.php?mod=alert&act_get=display&alert=" . $arr_appRow["str_alert"]);
			exit;
		}
	break;

	case "belong":
		$arr_appRow = $ctl_app->ctl_belong();
		if ($arr_appRow["str_alert"] != "y050302") {
			header("Location: " . BG_URL_ADMIN . "ctl.php?mod=alert&act_get=display&alert=" . $arr_appRow["str_alert"]);
			exit;
		}
	break;

	default:
		$arr_appRow = $ctl_app->ctl_list();
		if ($arr_appRow["str_alert"] != "y050302") {
			header("Location: " . BG_URL_ADMIN . "ctl.php?mod=alert&act_get=display&alert=" . $arr_appRow["str_alert"]);
			exit;
		}
	break;
}
?>