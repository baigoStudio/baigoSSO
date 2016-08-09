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
include_once(BG_PATH_CONTROL . "admin/ctl/log.class.php"); //载入日志控制器

$ctl_log = new CONTROL_LOG(); //初始化日志

switch ($GLOBALS["act_get"]) {
    case "show":
        $arr_logRow = $ctl_log->ctl_show(); //显示
        if ($arr_logRow["alert"] != "y060102") {
            header("Location: " . BG_URL_ADMIN . "ctl.php?mod=alert&act_get=show&alert=" . $arr_logRow["alert"] . $_url_attach);
            exit;
        }
    break;

    default:
        $arr_logRow = $ctl_log->ctl_list(); //列出
        if ($arr_logRow["alert"] != "y060302") {
            header("Location: " . BG_URL_ADMIN . "ctl.php?mod=alert&act_get=show&alert=" . $arr_logRow["alert"]);
            exit;
        }
    break;
}
