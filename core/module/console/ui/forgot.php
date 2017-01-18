<?php
/*-----------------------------------------------------------------
！！！！警告！！！！
以下为系统文件，请勿修改
-----------------------------------------------------------------*/

//不能非法包含或直接执行
if (!defined("IN_BAIGO")) {
    exit("Access Denied");
}

require(BG_PATH_INC . "common.inc.php"); //通用
$arr_set = array(
    "base"          => true, //基本设置
    "ssin"          => true, //启用会话
    "db"            => true, //连接数据库
);
fn_chkPHP($arr_set);

require(BG_PATH_FUNC . "init.func.php"); //初始化
fn_init($arr_set);

$ctrl_forgot = new CONTROL_CONSOLE_UI_FORGOT(); //初始化登录

switch ($GLOBALS["act"]) {
    case "step_2":
        $ctrl_forgot->ctrl_step_2(); //登出
    break;

    default:
        $ctrl_forgot->ctrl_step_1(); //登出
    break;
}
