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
    //"ssin"          => true,
    "header"        => "Content-type: application/json; charset=utf-8", //header
    "db"            => true, //连接数据库
    "type"          => "ajax", //模块类型
);
fn_init($arr_set);

include_once(BG_PATH_CONTROL . "api/pm.class.php"); //载入短信控制器

$api_pm = new API_PM(); //初始化短信

switch ($GLOBALS["act_post"]) {
    case "send":
        $api_pm->api_send(); //发送
    break;

    case "del":
        $api_pm->api_del(); //删除
    break;

    case "status":
        $api_pm->api_status(); //状态
    break;

    case "rev":
        $api_pm->api_rev(); //撤回
    break;

    default:
        switch ($GLOBALS["act_get"]) {
            case "check":
                $api_pm->api_check(); //验证是否有新短信
            break;

            case "read":
                $api_pm->api_read(); //读取
            break;

            case "list":
                $api_pm->api_list(); //列出
            break;
        }
    break;
}
