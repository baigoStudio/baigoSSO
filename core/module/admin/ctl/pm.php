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
include_once(BG_PATH_CONTROL . "admin/ctl/pm.class.php"); //载入短信控制器

$ctl_pm = new CONTROL_PM(); //初始化短信

switch ($GLOBALS["act_get"]) {
    case "send":
        $arr_pmRow = $ctl_pm->ctl_send(); //发送
        if ($arr_pmRow["alert"] != "y110303") {
            header("Location: " . BG_URL_ADMIN . "ctl.php?mod=alert&act_get=show&alert=" . $arr_pmRow["alert"]);
            exit;
        }
    break;

    case "show":
        $arr_pmRow = $ctl_pm->ctl_show(); //显示
        if ($arr_pmRow["alert"] != "y110102") {
            header("Location: " . BG_URL_ADMIN . "ctl.php?mod=alert&act_get=show&alert=" . $arr_pmRow["alert"]);
            exit;
        }
    break;

    case "bulk":
        $arr_pmRow = $ctl_pm->ctl_bulk(); //群发
        if ($arr_pmRow["alert"] != "y110102") {
            header("Location: " . BG_URL_ADMIN . "ctl.php?mod=alert&act_get=show&alert=" . $arr_pmRow["alert"]);
            exit;
        }
    break;

    default:
        $arr_pmRow = $ctl_pm->ctl_list(); //列出
        if ($arr_pmRow["alert"] != "y110302") {
            header("Location: " . BG_URL_ADMIN . "ctl.php?mod=alert&act_get=show&alert=" . $arr_pmRow["alert"]);
            exit;
        }
    break;
}
