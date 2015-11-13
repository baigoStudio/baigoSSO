<?php
/*-----------------------------------------------------------------
！！！！警告！！！！
以下为系统文件，请勿修改
-----------------------------------------------------------------*/

//不能非法包含或直接执行
if(!defined("IN_BAIGO")) {
	exit("Access Denied");
}

include_once(BG_PATH_FUNC . "include.func.php"); //管理员通用
fn_include(true, true, "Content-Type: text/html; charset=utf-8", true, "ctl");

include_once(BG_PATH_INC . "is_install.inc.php"); //验证是否已登录
include_once(BG_PATH_FUNC . "session.func.php"); //载入 session 函数
include_once(BG_PATH_CONTROL . "admin/ctl/logon.class.php"); //载入设置控制器

$ctl_logon = new CONTROL_LOGON(); //初始化登录

switch ($GLOBALS["act_post"]) {
	case "login": //登录
		$arr_logonRow = $ctl_logon->ctl_login();
		if ($arr_logonRow["alert"] != "y020201") {
			header("Location: " . BG_URL_ADMIN . "ctl.php?mod=logon&act_get=logon&forward=" . $arr_logonRow["forward"] . "&alert=" . $arr_logonRow["alert"]);
		} else {
			header("Location: " . base64_decode($arr_logonRow["forward"]));
		}
		exit;
	break;

	default:
		switch ($GLOBALS["act_get"]) {
			case "logout": //登出
				$arr_logonRow = $ctl_logon->ctl_logout();
				header("Location: " . base64_decode($arr_logonRow["forward"]));
				exit;
			break;

			default: //登录界面
				$arr_logonRow = $ctl_logon->ctl_logon();
			break;
		}
	break;
}
