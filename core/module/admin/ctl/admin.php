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
include_once(BG_PATH_CONTROL . "admin/ctl/admin.class.php"); //载入管理员控制器

$ctl_admin = new CONTROL_ADMIN(); //初始化管理员对象

switch ($GLOBALS["act_get"]) {
    case "show":
        $arr_adminRow = $ctl_admin->ctl_show(); //显示
        if ($arr_adminRow["alert"] != "y020102") {
            header("Location: " . BG_URL_ADMIN . "ctl.php?mod=alert&act_get=show&alert=" . $arr_adminRow["alert"]);
            exit;
        }
    break;

    case "form":
        $arr_adminRow = $ctl_admin->ctl_form(); //创建、编辑表单
        if ($arr_adminRow["alert"] != "y020102") {
            header("Location: " . BG_URL_ADMIN . "ctl.php?mod=alert&act_get=show&alert=" . $arr_adminRow["alert"]);
            exit;
        }
    break;

    default:
        $arr_adminRow = $ctl_admin->ctl_list(); //列出
        if ($arr_adminRow["alert"] != "y020302") {
            header("Location: " . BG_URL_ADMIN . "ctl.php?mod=alert&act_get=show&alert=" . $arr_adminRow["alert"]);
            exit;
        }
    break;
}
