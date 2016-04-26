<?php
/*-----------------------------------------------------------------
！！！！警告！！！！
以下为系统文件，请勿修改
-----------------------------------------------------------------*/

//不能非法包含或直接执行
if(!defined("IN_BAIGO")) {
    exit("Access Denied");
}

include_once(BG_PATH_FUNC . "init.func.php"); //初始化
$arr_set = array(
    "base"          => true, //基本设置
    "header"        => "Content-Type: text/html; charset=utf-8", //header
);
fn_init($arr_set);

include_once(BG_PATH_CONTROL . "help/help.class.php"); //载入帮助类

$ctl_help = new CONTROL_HELP();

$ctl_help->ctl_show(); //显示帮助
