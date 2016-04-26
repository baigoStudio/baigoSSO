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
include_once(BG_PATH_CONTROL . "admin/ctl/user.class.php"); //载入用户控制器

$ctl_user = new CONTROL_USER(); //初始化用户

switch ($GLOBALS["act_get"]) {
    case "import":
        $arr_userRow = $ctl_user->ctl_import(); //导入
        if ($arr_userRow["alert"] != "y010305") {
            header("Location: " . BG_URL_ADMIN . "ctl.php?mod=alert&act_get=show&alert=" . $arr_userRow["alert"]);
            exit;
        }
    break;

    case "form":
        $arr_userRow = $ctl_user->ctl_form(); //表单
        if ($arr_userRow["alert"] != "y010102") {
            header("Location: " . BG_URL_ADMIN . "ctl.php?mod=alert&act_get=show&alert=" . $arr_userRow["alert"]);
            exit;
        }
    break;

    default:
        $arr_userRow = $ctl_user->ctl_list(); //列出
        if ($arr_userRow["alert"] != "y010302") {
            header("Location: " . BG_URL_ADMIN . "ctl.php?mod=alert&act_get=show&alert=" . $arr_userRow["alert"]);
            exit;
        }
    break;
}
