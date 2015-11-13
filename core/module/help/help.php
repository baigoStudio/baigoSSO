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
fn_include(true);

include_once(BG_PATH_CONTROL . "help/help.class.php"); //载入文章类

$ctl_help = new CONTROL_HELP();

$ctl_help->ctl_show();
