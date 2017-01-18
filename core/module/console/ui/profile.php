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

$ctrl_profile = new CONTROL_CONSOLE_UI_PROFILE(); //初始化个人信息

switch ($GLOBALS["act"]) {
    case "mailbox":
        $ctrl_profile->ctrl_mailbox(); //修改邮箱
    break;

    case "qa":
        $ctrl_profile->ctrl_qa(); //修改密码
    break;

    case "pass":
        $ctrl_profile->ctrl_pass(); //修改密码
    break;

    default:
        $ctrl_profile->ctrl_info(); //修改个人信息
    break;
}
