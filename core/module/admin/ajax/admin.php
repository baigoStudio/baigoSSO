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

include_once(BG_PATH_CONTROL . "admin/ajax/admin.class.php"); //载入管理员 ajax 控制器

$ajax_admin = new AJAX_ADMIN(); //初始化管理员对象

switch ($GLOBALS["act_post"]) {
    case "submit":
        $ajax_admin->ajax_submit(); //创建、编辑
    break;

    case "del":
        $ajax_admin->ajax_del(); //删除
    break;

    case "enable":
    case "disable":
        $ajax_admin->ajax_status(); //状态
    break;

    default:
        switch ($GLOBALS["act_get"]) {
            case "chkname":
                $ajax_admin->ajax_chkname(); //验证用户名
            break;
        }
    break;
}
