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
fn_include(true, true, "Content-type: application/json", true, "ajax");

include_once(BG_PATH_CONTROL . "api/code.class.php"); //载入商家控制器
$api_code = new API_CODE(); //初始化商家

switch ($GLOBALS["act_post"]) {
	case "encode":
		$api_code->api_encode();
	break;

	case "decode":
		$api_code->api_decode();
	break;
}