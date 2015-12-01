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
fn_include(true, true, "Content-type: application/json");

include_once(BG_PATH_CLASS . "mysqli.class.php"); //载入数据库类
include_once(BG_PATH_CONTROL . "install/ajax/install.class.php"); //载入栏目控制器

$ajax_install = new AJAX_INSTALL(); //初始化商家

switch ($GLOBALS["act_post"]) {
	case "dbconfig":
		$ajax_install->ajax_dbconfig();
	break;

	case "admin":
		$ajax_install->ajax_admin();
	break;

	case "over":
		$ajax_install->ajax_over();
	break;

	case "reg":
	case "base":
		$ajax_install->ajax_submit();
	break;
}
