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
include_once(BG_PATH_CLASS . "base.class.php"); //载入基类

header("Content-Type: text/html; charset=utf-8");
$GLOBALS["obj_base"]    = new CLASS_BASE(); //初始化基类
