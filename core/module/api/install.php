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
$GLOBALS["obj_base"]    = new CLASS_BASE(); //初始化基类

include_once(BG_PATH_CONTROL . "api/install.class.php"); //载入商家控制器

$api_install = new API_INSTALL(); //初始化商家

switch ($GLOBALS["act_post"]) {
	case "dbconfig":
		$api_install->api_dbconfig();
	break;

	case "base":
		$api_install->api_base();
	break;

	case "dbtable":
		$api_install->api_dbtable();
	break;

	case "admin":
		$api_install->api_admin();
	break;

	case "over":
		$api_install->api_over();
	break;
}
