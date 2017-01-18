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
    "db"            => true, //连接数据库
    "dsp_type"      => "result",
);
fn_chkPHP($arr_set);

require(BG_PATH_FUNC . "init.func.php"); //初始化
fn_init($arr_set);

$ctrl_profile = new CONTROL_API_PROFILE(); //初始化用户
switch ($GLOBALS["method"]) {
    case "POST":
        switch ($GLOBALS["act"]) {
            case "qa":
                $ctrl_profile->ctrl_qa();
            break;

            case "info":
                $ctrl_profile->ctrl_info(); //编辑
            break;

            case "pass":
                $ctrl_profile->ctrl_pass(); //忘记密码
            break;

            case "token":
                $ctrl_profile->ctrl_token(); //删除
            break;

            case "mailbox":
                $ctrl_profile->ctrl_mailbox(); //删除
            break;
        }
    break;
}