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
fn_include(true, true, "Content-type: application/json", true, "ajax", true);

include_once(BG_PATH_CONTROL . "admin/ajax/opt.class.php"); //载入设置 ajax 控制器

$ajax_opt = new AJAX_OPT(); //初始化设置对象

switch ($GLOBALS["act_post"]) {
	case "dbconfig":
		$ajax_opt->ajax_dbconfig(); //数据库
	break;

	default:
		$ajax_opt->ajax_submit(); //数据库
	break;
}
