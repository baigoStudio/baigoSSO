<?php
/*-----------------------------------------------------------------
！！！！警告！！！！
以下为系统文件，请勿修改
-----------------------------------------------------------------*/

//不能非法包含或直接执行
if(!defined("IN_BAIGO")) {
	exit("Access Denied");
}

include_once(BG_PATH_INC . "common_help.inc.php"); //验证是否已登录
include_once(BG_PATH_CONTROL_HELP . "install.class.php"); //载入文章类

$ctl_install = new CONTROL_INSTALL();

$ctl_install->ctl_show($GLOBALS["act_get"]);
