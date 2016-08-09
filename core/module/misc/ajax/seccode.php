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
    "base"      => true, //基本设置
    "ssin"      => true, //启用会话
    "header"    => "Content-type: application/json; charset=utf-8", //header
    "db"        => true, //连接数据库
    "type"      => "ajax", //模块类型
);
fn_init($arr_set);

include_once(BG_PATH_CONTROL . "misc/ajax/seccode.class.php"); //载入验证码控制器

$ajax_seccode           = new AJAX_SECCODE(); //初始化验证码

switch ($GLOBALS["act_get"]) {
    case "chk":
        $ajax_seccode->ajax_chk(); //验证
    break;
}
