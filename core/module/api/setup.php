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
switch ($GLOBALS["act"]) {
    case "dbconfig":
        $arr_set = array(
            "base"          => true, //基本设置
            "dsp_type"      => "result",
        );
    break;

    default:
        $arr_set = array(
            "base"          => true, //基本设置
            "db"            => true, //连接数据库
            "dsp_type"      => "result",
        );
    break;
}
fn_chkPHP($arr_set);

require(BG_PATH_FUNC . "init.func.php"); //初始化
fn_init($arr_set);

$ctrl_setup = new CONTROL_API_SETUP(); //初始化安装
switch ($GLOBALS["method"]) {
    case "POST":
        switch ($GLOBALS["act"]) {
            case "dbconfig":
                $ctrl_setup->ctrl_dbconfig(); //数据库配置
            break;

            case "dbtable":
                $ctrl_setup->ctrl_dbtable(); //创建数据表
            break;

            case "admin":
                $ctrl_setup->ctrl_admin(); //创建管理员
            break;

            case "over":
                $ctrl_setup->ctrl_over(); //结束安装
            break;

            case "base":
                $ctrl_setup->ctrl_submit(); //基本配置
            break;
        }
    break;
}
