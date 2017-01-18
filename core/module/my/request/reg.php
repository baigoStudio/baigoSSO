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

require(BG_PATH_FUNC . "init.func.php"); //初始化
$arr_set = array(
    "base"      => true, //基本设置
    "ssin"      => true, //启用会话
    "db"        => true, //连接数据库
);
fn_init($arr_set);

$ctrl_reg = new CONTROL_MY_REQUEST_REG();

switch ($GLOBALS["method"]) {
    case "POST":
        switch ($GLOBALS["act"]) {
            case "confirm":
                $ctrl_reg->ctrl_confirm(); //激活
            break;
        }
    break;
}