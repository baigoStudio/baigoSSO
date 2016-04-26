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
    "base"      => true, //基本设置
    "ssin"      => true, //启用会话
    "header"    => "Content-type: application/json; charset=utf-8", //header
    "db"        => true, //连接数据库
    "type"      => "ajax", //模块类型
);
fn_init($arr_set);

include_once(BG_PATH_CONTROL . "user/ajax/reg.class.php"); //载入注册控制器

$ajax_reg = new AJAX_REG(); //初始化用户

switch ($GLOBALS["act_post"]) {
    case "confirm":
        $ajax_reg->ajax_confirm(); //激活
    break;

    case "forgot":
        $ajax_reg->ajax_forgot(); //忘记密码
    break;

    case "mailbox":
        $ajax_reg->ajax_mailbox(); //更换邮箱
    break;
}
