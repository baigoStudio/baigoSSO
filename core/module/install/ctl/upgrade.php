<?php
/*-----------------------------------------------------------------
！！！！警告！！！！
以下为系统文件，请勿修改
-----------------------------------------------------------------*/

//不能非法包含或直接执行
if(!defined("IN_BAIGO")) {
	exit("Access Denied");
}

if (!file_exists(BG_PATH_CONFIG . "is_install.php")) {
	header("Location: " . BG_URL_INSTALL . "ctl.php?mod=install");
} else {
    include_once(BG_PATH_CONFIG . "is_install.php");
    if (defined("BG_INSTALL_PUB") && PRD_SSO_PUB <= BG_INSTALL_PUB) {
        header("Location: " . BG_URL_INSTALL . "ctl.php?mod=alert&act_get=show&alert=x030403");
    }
}

include_once(BG_PATH_FUNC . "include.func.php"); //管理员通用
fn_include(true, true);

include_once(BG_PATH_CLASS . "mysqli.class.php"); //载入数据库类
include_once(BG_PATH_CONTROL . "install/ctl/upgrade.class.php"); //载入栏目控制器

$ctl_upgrade = new CONTROL_UPGRADE(); //初始化商家

switch ($GLOBALS["act_get"]) {
	case "dbconfig":
		$arr_upgradeRow = $ctl_upgrade->ctl_dbconfig();
		if ($arr_upgradeRow["alert"] != "y030404") {
			header("Location: " . BG_URL_INSTALL . "ctl.php?mod=alert&act_get=show&alert=" . $arr_upgradeRow["alert"]);
			exit;
		}
	break;

	case "dbtable":
		$arr_upgradeRow = $ctl_upgrade->ctl_dbtable();
		if ($arr_upgradeRow["alert"] != "y030404") {
			header("Location: " . BG_URL_INSTALL . "ctl.php?mod=alert&act_get=show&alert=" . $arr_upgradeRow["alert"]);
			exit;
		}
	break;

	case "over":
		$arr_upgradeRow = $ctl_upgrade->ctl_over();
		if ($arr_upgradeRow["alert"] != "y030405") {
			header("Location: " . BG_URL_INSTALL . "ctl.php?mod=alert&act_get=show&alert=" . $arr_upgradeRow["alert"]);
			exit;
		}
	break;

	case "reg":
	case "base":
		$arr_upgradeRow = $ctl_upgrade->ctl_form();
		if ($arr_upgradeRow["alert"] != "y030405") {
			header("Location: " . BG_URL_INSTALL . "ctl.php?mod=alert&act_get=show&alert=" . $arr_upgradeRow["alert"]);
			exit;
		}
	break;

	default:
		$arr_upgradeRow = $ctl_upgrade->ctl_ext();
		if ($arr_upgradeRow["alert"] != "y030403") {
			header("Location: " . BG_URL_INSTALL . "ctl.php?mod=alert&act_get=show&alert=" . $arr_upgradeRow["alert"]);
			exit;
		}
	break;
}
