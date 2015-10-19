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
	session_id($_GET["ssid"]); //将当前的SessionId设置成客户端传递回来的SessionId
}

session_start(); //开启session
$GLOBALS["ssid"] = session_id();

header("Content-type: application/json");
include_once(BG_PATH_INC . "common_global.inc.php"); //载入通用
include_once(BG_PATH_CLASS . "mysqli.class.php"); //载入数据库类
include_once(BG_PATH_CLASS . "base.class.php"); //载入基类
include_once(BG_PATH_CONTROL . "install/ajax/upgrade.class.php"); //载入栏目控制器

$GLOBALS["obj_base"]    = new CLASS_BASE(); //初始化基类
$ajax_upgrade           = new AJAX_UPGRADE(); //初始化商家

switch ($GLOBALS["act_post"]) {
	case "reg":
		$ajax_upgrade->ajax_reg();
	break;

	case "base":
		$ajax_upgrade->ajax_base();
	break;

	case "over":
		$ajax_upgrade->ajax_over();
	break;

	case "dbtable":
		$ajax_upgrade->ajax_dbtable();
	break;

	case "dbconfig":
		$ajax_upgrade->ajax_dbconfig();
	break;
}
