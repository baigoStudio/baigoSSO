<?php
/*-----------------------------------------------------------------
！！！！警告！！！！
以下为系统文件，请勿修改
-----------------------------------------------------------------*/

//不能非法包含或直接执行
if(!defined("IN_BAIGO")) {
	exit("Access Denied");
}

if (isset($_GET["ssid"])) {
	session_id($_GET["ssid"]); //将当前的 SessionId 设置成客户端传递回来的 SessionId
}

session_start(); //开启session
$GLOBALS["ssid"] = session_id();

include_once(BG_PATH_INC . "is_install.inc.php"); //验证是否已安装
include_once(BG_PATH_INC . "common_global.inc.php"); //载入通用
include_once(BG_PATH_FUNC . "session.func.php"); //载入 session 函数
include_once(BG_PATH_CLASS . "mysqli.class.php"); //载入数据库类
include_once(BG_PATH_CLASS . "base.class.php"); //载入基类
include_once(BG_PATH_CONTROL_ADMIN . "ctl/logon.class.php"); //载入登录控制器

header("Content-Type: text/html; charset=utf-8");

if (!defined("BG_DB_PORT")) {
	define("BG_DB_PORT", "3306");
}

$_cfg_host = array(
	"host"      => BG_DB_HOST,
	"name"      => BG_DB_NAME,
	"user"      => BG_DB_USER,
	"pass"      => BG_DB_PASS,
	"charset"   => BG_DB_CHARSET,
	"debug"     => BG_DB_DEBUG,
);

$GLOBALS["obj_db"]      = new CLASS_MYSQLI($_cfg_host); //设置数据库对象

if (!$GLOBALS["obj_db"]->connect()) {
	header("Location: " . BG_URL_ROOT . "db_conn_err.html");
	exit;
}

if (!$GLOBALS["obj_db"]->select_db()) {
	header("Location: " . BG_URL_ROOT . "db_select_err.html");
	exit;
}

$GLOBALS["obj_base"]    = new CLASS_BASE(); //初始化基类
$ctl_logon              = new CONTROL_LOGON(); //初始化登录

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
