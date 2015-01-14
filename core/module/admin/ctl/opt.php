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
include_once(BG_PATH_CONTROL_ADMIN . "ctl/opt.class.php"); //载入栏目控制器

$ctl_opt = new CONTROL_OPT(); //初始化设置对象

switch ($GLOBALS["act_get"]) {
	case "reg":
		$arr_optRow = $ctl_opt->ctl_reg();
		if ($arr_optRow["str_alert"] != "y040302") {
			header("Location: " . BG_URL_ADMIN . "ctl.php?mod=alert&act_get=display&alert=" . $arr_optRow["str_alert"]);
			exit;
		}
	break;

	case "mail":
		$arr_optRow = $ctl_opt->ctl_mail();
		if ($arr_optRow["str_alert"] != "y040303") {
			header("Location: " . BG_URL_ADMIN . "ctl.php?mod=alert&act_get=display&alert=" . $arr_optRow["str_alert"]);
			exit;
		}
	break;

	case "db":
		$arr_optRow = $ctl_opt->ctl_db();
		if ($arr_optRow["str_alert"] != "y040304") {
			header("Location: " . BG_URL_ADMIN . "ctl.php?mod=alert&act_get=display&alert=" . $arr_optRow["str_alert"]);
			exit;
		}
	break;

	default:
		$arr_optRow = $ctl_opt->ctl_base();
		if ($arr_optRow["str_alert"] != "y040301") {
			header("Location: " . BG_URL_ADMIN . "ctl.php?mod=alert&act_get=display&alert=" . $arr_optRow["str_alert"]);
			exit;
		}
	break;
}
?>