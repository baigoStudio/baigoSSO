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
include_once(BG_PATH_CONTROL . "admin/ctl/app.class.php"); //载入应用控制器

$ctl_app = new CONTROL_APP(); //初始化应用

switch ($GLOBALS["act_get"]) {
    case "show":
        $arr_appRow = $ctl_app->ctl_show(); //显示
        if ($arr_appRow["alert"] != "y050102") {
            header("Location: " . BG_URL_ADMIN . "ctl.php?mod=alert&act_get=show&alert=" . $arr_appRow["alert"]);
            exit;
        }
    break;

    case "form":
        $arr_appRow = $ctl_app->ctl_form(); //创建、编辑表单
        if ($arr_appRow["alert"] != "y050102") {
            header("Location: " . BG_URL_ADMIN . "ctl.php?mod=alert&act_get=show&alert=" . $arr_appRow["alert"]);
            exit;
        }
    break;

    case "belong":
        $arr_appRow = $ctl_app->ctl_belong(); //用户授权
        if ($arr_appRow["alert"] != "y050302") {
            header("Location: " . BG_URL_ADMIN . "ctl.php?mod=alert&act_get=show&alert=" . $arr_appRow["alert"]);
            exit;
        }
    break;

    default:
        $arr_appRow = $ctl_app->ctl_list(); //列出
        if ($arr_appRow["alert"] != "y050302") {
            header("Location: " . BG_URL_ADMIN . "ctl.php?mod=alert&act_get=show&alert=" . $arr_appRow["alert"]);
            exit;
        }
    break;
}
