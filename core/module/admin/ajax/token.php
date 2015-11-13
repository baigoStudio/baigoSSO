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

include_once(BG_PATH_CONTROL . "admin/ajax/token.class.php"); //载入令牌 ajax 控制器

$ajax_token = new AJAX_TOKEN();

switch ($GLOBALS["act_get"]) {
	case "make":
		$ajax_token->ajax_make(); //滚动令牌
	break;
}
