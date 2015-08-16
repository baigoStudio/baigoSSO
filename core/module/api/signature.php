<?php
/*-----------------------------------------------------------------

！！！！警告！！！！
以下为系统文件，请勿修改

-----------------------------------------------------------------*/

//不能非法包含或直接执行
if(!defined("IN_BAIGO")) {
	exit("Access Denied");
}

include_once(BG_PATH_INC . "common_api.inc.php"); //验证是否已登录
include_once(BG_PATH_CONTROL_API . "signature.class.php"); //载入商家控制器
$api_signature = new API_SIGNATURE(); //初始化商家

switch ($GLOBALS["act_get"]) {
	case "signature":
		$api_signature->api_signature();
	break;

	case "verify":
		$api_signature->api_verify();
	break;
}
