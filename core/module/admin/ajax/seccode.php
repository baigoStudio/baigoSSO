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
fn_include(true, true, "Content-type: application/json", false, "ajax");

include_once(BG_PATH_CONTROL . "admin/ajax/seccode.class.php"); //载入验证码控制器

$ajax_seccode           = new AJAX_SECCODE(); //初始化验证码

switch ($GLOBALS["act_get"]) {
	case "chk":
		$ajax_seccode->ajax_chk(); //验证
	break;
}
