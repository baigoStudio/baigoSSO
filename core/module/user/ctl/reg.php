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
    "header"    => "Content-Type: text/html; charset=utf-8", //header
    "db"        => true, //连接数据库
    "type"      => "ctl", //模块类型
);
fn_init($arr_set);

include_once(BG_PATH_INC . "is_install.inc.php"); //验证是否已安装
include_once(BG_PATH_CONTROL . "user/ctl/reg.class.php"); //载入注册控制器

$ctl_reg = new CONTROL_REG();

switch ($GLOBALS["act_get"]) {
    case "confirm":
        $arr_regRow = $ctl_reg->ctl_confirm(); //激活
        if ($arr_regRow["alert"] != "y010102") {
            header("Location: " . BG_URL_USER . "ctl.php?mod=reg&act_get=form&alert=" . $arr_regRow["alert"]);
            exit;
        }
    break;

    case "forgot":
        $arr_regRow = $ctl_reg->ctl_forgot(); //忘记密码
        if ($arr_regRow["alert"] != "y010102") {
            header("Location: " . BG_URL_USER . "ctl.php?mod=reg&act_get=form&alert=" . $arr_regRow["alert"]);
            exit;
        }
    break;

    case "mailbox":
        $arr_regRow = $ctl_reg->ctl_mailbox(); //更换邮箱
        if ($arr_regRow["alert"] != "y010102") {
            header("Location: " . BG_URL_USER . "ctl.php?mod=reg&act_get=form&alert=" . $arr_regRow["alert"]);
            exit;
        }
    break;

    /*default:
        $arr_regRow = $ctl_reg->ctl_form(); //注册
    break;*/
}
