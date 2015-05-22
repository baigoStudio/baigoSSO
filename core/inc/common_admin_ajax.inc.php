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

include_once(BG_PATH_INC . "common_global.inc.php"); //载入全局通用
include_once(BG_PATH_FUNC . "session.func.php"); //载入 session 函数
include_once(BG_PATH_CLASS . "mysqli.class.php"); //载入数据库类
include_once(BG_PATH_CLASS . "base.class.php"); //载入基类

header("Content-type: application/json");

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
$GLOBALS["obj_base"]    = new CLASS_BASE(); //初始化基类

if (!$GLOBALS["obj_db"]->connect()) {
	$arr_alert = include_once(BG_PATH_LANG . $GLOBALS["obj_base"]->config["lang"] . "/alert.php"); //载入提示信息
	$str_alert = "x030111";
	$arr_re    = array(
		"msg"    => $arr_alert[$str_alert],
		"alert"  => $str_alert,
	);
	exit(json_encode($arr_re));
}

if (!$GLOBALS["obj_db"]->select_db()) {
	$arr_alert = include_once(BG_PATH_LANG . $GLOBALS["obj_base"]->config["lang"] . "/alert.php"); //载入提示信息
	$str_alert = "x030112";
	$arr_re    = array(
		"msg"    => $arr_alert[$str_alert],
		"alert"  => $str_alert,
	);
	exit(json_encode($arr_re));
}

$GLOBALS["adminLogged"] = fn_ssin_begin(); //验证 session, 并获取管理员信息
