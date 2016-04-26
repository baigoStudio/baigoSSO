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
    "header"        => "Content-type: application/json; charset=utf-8", //header
);
fn_init($arr_set);

include_once(BG_PATH_CLASS . "mysqli.class.php"); //载入数据库类
include_once(BG_PATH_CONTROL . "api/install.class.php"); //载入安装控制器

$api_install = new API_INSTALL(); //初始化安装

switch ($GLOBALS["act_post"]) {
    case "dbconfig":
        $api_install->api_dbconfig(); //数据库配置
    break;

    case "dbtable":
        $api_install->api_dbtable(); //创建数据表
    break;

    case "admin":
        $api_install->api_admin(); //创建管理员
    break;

    case "over":
        $api_install->api_over(); //结束安装
    break;

    case "base":
        $api_install->api_submit(); //基本配置
    break;
}
