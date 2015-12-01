<?php
/*-----------------------------------------------------------------
！！！！警告！！！！
以下为系统文件，请勿修改
-----------------------------------------------------------------*/

//不能非法包含或直接执行
if(!defined("IN_BAIGO")) {
	exit("Access Denied");
}

if (file_exists(BG_PATH_CONFIG . "is_install.php")) {
	include_once(BG_PATH_CONFIG . "is_install.php");
	if (defined("BG_INSTALL_PUB") && PRD_SSO_PUB > BG_INSTALL_PUB) {
		header("Location: " . BG_URL_INSTALL . "ctl.php?mod=upgrade");
	} else {
		header("Location: " . BG_URL_INSTALL . "ctl.php?mod=alert&act_get=show&alert=x030403");
	}
	exit;
}

include_once(BG_PATH_FUNC . "include.func.php"); //管理员通用
fn_include(true, true);

include_once(BG_PATH_CLASS . "mysqli.class.php"); //载入数据库类
include_once(BG_PATH_CONTROL . "install/ctl/install.class.php"); //载入栏目控制器

$ctl_install            = new CONTROL_INSTALL(); //初始化商家

switch ($GLOBALS["act_get"]) {
	case "dbconfig":
		$arr_installRow = $ctl_install->ctl_dbconfig();
		if ($arr_installRow["alert"] != "y030404") {
			header("Location: " . BG_URL_INSTALL . "ctl.php?mod=alert&act_get=show&alert=" . $arr_installRow["alert"]);
			exit;
		}
	break;

	case "dbtable":
		$arr_installRow = $ctl_install->ctl_dbtable();
		if ($arr_installRow["alert"] != "y030404") {
			header("Location: " . BG_URL_INSTALL . "ctl.php?mod=alert&act_get=show&alert=" . $arr_installRow["alert"]);
			exit;
		}
	break;

	case "admin":
		$arr_installRow = $ctl_install->ctl_admin();
		if ($arr_installRow["alert"] != "y030405") {
			header("Location: " . BG_URL_INSTALL . "ctl.php?mod=alert&act_get=show&alert=" . $arr_installRow["alert"]);
			exit;
		}
	break;

	case "over":
		$arr_installRow = $ctl_install->ctl_over();
		if ($arr_installRow["alert"] != "y030405") {
			header("Location: " . BG_URL_INSTALL . "ctl.php?mod=alert&act_get=show&alert=" . $arr_installRow["alert"]);
			exit;
		}
	break;

	case "reg":
	case "base":
		$arr_installRow = $ctl_install->ctl_form();
		if ($arr_installRow["alert"] != "y030405") {
			header("Location: " . BG_URL_INSTALL . "ctl.php?mod=alert&act_get=show&alert=" . $arr_installRow["alert"]);
			exit;
		}
	break;

	default:
		$arr_installRow = $ctl_install->ctl_ext();
		if ($arr_installRow["alert"] != "y030403") {
			header("Location: " . BG_URL_INSTALL . "ctl.php?mod=alert&act_get=show&alert=" . $arr_installRow["alert"]);
			exit;
		}
	break;
}
