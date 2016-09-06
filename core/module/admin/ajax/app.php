<?php
/*-----------------------------------------------------------------
！！！！警告！！！！
以下为系统文件，请勿修改
-----------------------------------------------------------------*/

//不能非法包含或直接执行
if (!defined("IN_BAIGO")) {
    exit("Access Denied");
}

include_once(BG_PATH_FUNC . "init.func.php"); //初始化
$arr_set = array(
    "base"          => true, //基本设置
    "ssin"          => true, //启用会话
    "header"        => "Content-type: application/json; charset=utf-8", //header
    "db"            => true, //连接数据库
    "type"          => "ajax", //模块类型
    "ssin_begin"    => true, //开始管理员会话
);
fn_init($arr_set);

include_once(BG_PATH_CONTROL . "admin/ajax/app.class.php"); //载入应用 ajax 控制器

$ajax_app = new AJAX_APP(); //初始化应用对象

switch ($GLOBALS["act_post"]) {
    case "auth":
        $ajax_app->ajax_auth(); //授权用户
    break;

    case "deauth":
        $ajax_app->ajax_deauth(); //取消授权用户
    break;

    case "reset":
        $ajax_app->ajax_reset(); //重置 APP KEY
    break;

    case "submit":
        $ajax_app->ajax_submit(); //创建、编辑
    break;

    case "enable":
    case "disable":
        $ajax_app->ajax_status(); //状态
    break;

    case "del":
        $ajax_app->ajax_del(); //删除
    break;

    case "notify":
        $ajax_app->ajax_notify(); //通知测试
    break;
}
