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
    "header"        => "Content-Type: text/html; charset=utf-8", //header
    "db"            => true, //连接数据库
    "type"          => "ctl", //模块类型
    "ssin_begin"    => true, //开始管理员会话
);
fn_init($arr_set);

include_once(BG_PATH_INC . "is_install.inc.php"); //验证是否已安装
include_once(BG_PATH_INC . "is_admin.inc.php"); //验证是否已登录
include_once(BG_PATH_CONTROL . "admin/ctl/profile.class.php"); //载入个人信息控制器

$ctl_profile = new CONTROL_PROFILE(); //初始化个人信息

switch ($GLOBALS["act_get"]) {
    case "pass":
        $arr_profileRow = $ctl_profile->ctl_pass(); //修改密码
        if ($arr_profileRow["alert"] != "y020109") {
            header("Location: " . BG_URL_ADMIN . "ctl.php?mod=alert&act_get=show&alert=" . $arr_profileRow["alert"]);
            exit;
        }
    break;

    default:
        $arr_profileRow = $ctl_profile->ctl_info(); //修改个人信息
        if ($arr_profileRow["alert"] != "y020108") {
            header("Location: " . BG_URL_ADMIN . "ctl.php?mod=alert&act_get=show&alert=" . $arr_profileRow["alert"]);
            exit;
        }
    break;
}
