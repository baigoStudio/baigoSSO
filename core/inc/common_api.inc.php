<?php
/*-----------------------------------------------------------------
！！！！警告！！！！
以下为系统文件，请勿修改
-----------------------------------------------------------------*/

//不能非法包含或直接执行
if(!defined("IN_BAIGO")) {
	exit("Access Denied");
}

include_once(BG_PATH_INC . "common_global.inc.php"); //载入全局通用
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
	"debug"     => BG_DEBUG_DB,
);

$GLOBALS["obj_db"]      = new CLASS_MYSQLI($_cfg_host); //设置数据库对象

if (!$GLOBALS["obj_db"]->connect()) {
	$arr_re    = array(
		"alert" => "x030111",
	);
	exit(json_encode($arr_re));
}

if (!$GLOBALS["obj_db"]->select_db()) {
	$arr_re    = array(
		"alert" => "x030112",
	);
	exit(json_encode($arr_re));
}

$GLOBALS["obj_base"]    = new CLASS_BASE(); //初始化基类
