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
include_once(BG_PATH_CLASS . "base.class.php"); //载入基类
include_once(BG_PATH_CONTROL_ADMIN . "ctl/alert.class.php"); //载入消息类

$GLOBALS["obj_base"]    = new CLASS_BASE(); //初始化基类
$ctl_alert              = new CONTROL_ALERT(); //初始化消息对象

switch ($act_get) {
	case "display":
		$ctl_alert->ctl_display(); //显示消息
	break;
}
?>