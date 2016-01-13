<?php
/*-----------------------------------------------------------------
！！！！警告！！！！
以下为系统文件，请勿修改
-----------------------------------------------------------------*/


//不能非法包含或直接执行
if(!defined("IN_BAIGO")) {
    exit("Access Denied");
}

include_once(BG_PATH_FUNC . "init.func.php"); //管理员通用
fn_init(false, true, "Content-type: image/png", true, "ajax");

include_once(BG_PATH_CONTROL . "misc/ctl/seccode.class.php"); //载入验证码类

$ctl_seccode = new CONTROL_SECCODE(); //初始化验证对象

switch ($GLOBALS["act_get"]) {
    case "make":
        $ctl_seccode->ctl_make(); //生成验证码
    break;
}
