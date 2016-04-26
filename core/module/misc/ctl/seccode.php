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
    "ssin"      => true, //启用会话
    "header"    => "Content-type: image/png", //header
    "db"        => true, //连接数据库
    "type"      => "ajax", //模块类型
);
fn_init($arr_set);

include_once(BG_PATH_CONTROL . "misc/ctl/seccode.class.php"); //载入验证码类

$ctl_seccode = new CONTROL_SECCODE(); //初始化验证对象

switch ($GLOBALS["act_get"]) {
    case "make":
        $ctl_seccode->ctl_make(); //生成验证码
    break;
}
