<?php
/*-----------------------------------------------------------------
！！！！警告！！！！
以下为系统文件，请勿修改
-----------------------------------------------------------------*/

//不能非法包含或直接执行
if(!defined("IN_BAIGO")) {
	exit("Access Denied");
}

include_once(BG_PATH_INC . "is_install.inc.php"); //验证是否已登录
include_once(BG_PATH_INC . "common_global.inc.php"); //载入通用
include_once(BG_PATH_FUNC . "session.func.php"); //载入商家控制器
include_once(BG_PATH_CLASS . "mysql.class.php"); //载入数据库类
include_once(BG_PATH_CLASS . "base.class.php"); //载入基类
include_once(BG_PATH_CONTROL_ADMIN . "ctl/logon.class.php"); //载入登录控制器

$GLOBALS["obj_db"]      = new CLASS_MYSQL(); //设置数据库对象
$GLOBALS["obj_base"]    = new CLASS_BASE(); //初始化基类
$ctl_logon              = new CONTROL_LOGON();

switch ($GLOBALS["act_post"]) {
	case "login":
		$arr_logonRow = $ctl_logon->ctl_login();
		if ($arr_logonRow["str_alert"] != "y020201") {
			header("Location: " . BG_URL_ADMIN . "ctl.php?mod=logon&act_get=logon&forward=" . $arr_logonRow["forward"] . "&alert=" . $arr_logonRow["str_alert"]);
		} else {
			header("Location: " . base64_decode($arr_logonRow["forward"]));
		}
		exit;
	break;

	default:
		switch ($GLOBALS["act_get"]) {
			case "logout":
				$arr_logonRow = $ctl_logon->ctl_logout();
				header("Location: " . base64_decode($arr_logonRow["forward"]));
				exit;
			break;

			default:
				$arr_logonRow = $ctl_logon->ctl_logon();
			break;
		}
	break;
}
?>