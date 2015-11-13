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
fn_include(true, true, "Content-Type: text/html; charset=utf-8", true, "ctl", true);

include_once(BG_PATH_INC . "is_install.inc.php"); //验证是否已登录
include_once(BG_PATH_INC . "is_admin.inc.php"); //验证是否已登录
include_once(BG_PATH_CONTROL . "admin/ctl/log.class.php"); //载入日志控制器

$ctl_log = new CONTROL_LOG(); //初始化日志

switch ($GLOBALS["act_get"]) {
	case "show": //显示
		$arr_logRow = $ctl_log->ctl_show();
		if ($arr_logRow["alert"] != "y060102") {
			header("Location: " . BG_URL_ADMIN . "ctl.php?mod=alert&act_get=show&alert=" . $arr_logRow["alert"] . $_url_attach);
			exit;
		}
	break;

	default: //列出
		$arr_logRow = $ctl_log->ctl_list();
		if ($arr_logRow["alert"] != "y060302") {
			header("Location: " . BG_URL_ADMIN . "ctl.php?mod=alert&act_get=show&alert=" . $arr_logRow["alert"]);
			exit;
		}
	break;
}
