<?php
/*-----------------------------------------------------------------

！！！！警告！！！！
以下为系统文件，请勿修改

-----------------------------------------------------------------*/

//不能非法包含或直接执行
if(!defined("IN_BAIGO")) {
	exit("Access Denied");
}

include_once(BG_PATH_INC . "common_global.inc.php"); //载入通用

include_once(BG_PATH_CONTROL_ADMIN . "ctl/seccode.class.php"); //载入消息类
$ctl_seccode = new CONTROL_SECCODE(); //初始化消息对象

switch ($GLOBALS["act_get"]) {
	case "make":
		$ctl_seccode->ctl_make(); //显示消息
	break;
}
?>