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
fn_init(true, true, "Content-Type: text/html; charset=utf-8", true, "ctl");

include_once(BG_PATH_INC . "is_install.inc.php"); //验证是否已登录
include_once(BG_PATH_CONTROL . "user/ctl/reg.class.php"); //载入用户控制器

$ctl_reg = new CONTROL_REG();

switch ($GLOBALS["act_get"]) {
    case "confirm":
        $arr_regRow = $ctl_reg->ctl_confirm();
        if ($arr_regRow["alert"] != "y010102") {
            header("Location: " . BG_URL_USER . "ctl.php?mod=reg&act_get=form&alert=" . $arr_regRow["alert"]);
            exit;
        }
    break;

    case "forgot":
        $arr_regRow = $ctl_reg->ctl_forgot(); //导入
        if ($arr_regRow["alert"] != "y010102") {
            header("Location: " . BG_URL_USER . "ctl.php?mod=reg&act_get=form&alert=" . $arr_regRow["alert"]);
            exit;
        }
    break;

    case "mailbox":
        $arr_regRow = $ctl_reg->ctl_mailbox();
        if ($arr_regRow["alert"] != "y010102") {
            header("Location: " . BG_URL_USER . "ctl.php?mod=reg&act_get=form&alert=" . $arr_regRow["alert"]);
            exit;
        }
    break;

    default:
        $arr_regRow = $ctl_reg->ctl_form();
    break;
}
