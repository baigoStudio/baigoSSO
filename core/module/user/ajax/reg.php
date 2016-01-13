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
fn_init(true, true, "Content-type: application/json", true, "ajax", true);

include_once(BG_PATH_CONTROL . "user/ajax/reg.class.php"); //载入用户控制器

$ajax_reg = new AJAX_REG(); //初始化用户

switch ($GLOBALS["act_post"]) {
    case "confirm":
        $ajax_reg->ajax_confirm(); //导入
    break;

    case "forgot":
        $ajax_reg->ajax_forgot(); //导入
    break;

    case "mailbox":
        $ajax_reg->ajax_mailbox();
    break;
}
