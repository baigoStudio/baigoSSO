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

include_once(BG_PATH_CONTROL . "admin/ajax/verify.class.php"); //载入验证 ajax 控制器

$ajax_verify = new AJAX_VERIFY(); //初始化日验证对象

switch ($GLOBALS["act_post"]) {
    case "enable":
    case "disable":
        $ajax_verify->ajax_status(); //状态
    break;

    case "del":
        $ajax_verify->ajax_del();  //删除
    break;
}
