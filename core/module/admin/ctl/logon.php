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
);
fn_init($arr_set);

include_once(BG_PATH_INC . "is_install.inc.php"); //验证是否已安装
include_once(BG_PATH_FUNC . "session.func.php"); //载入 session 函数
include_once(BG_PATH_CONTROL . "admin/ctl/logon.class.php"); //载入登录控制器

$ctl_logon = new CONTROL_LOGON(); //初始化登录

switch ($GLOBALS["act_post"]) {
    case "login":
        $arr_logonRow = $ctl_logon->ctl_login(); //登录
        if ($arr_logonRow["alert"] != "y020201") {
            header("Location: " . BG_URL_ADMIN . "ctl.php?mod=logon&act_get=logon&forward=" . $arr_logonRow["forward"] . "&alert=" . $arr_logonRow["alert"]);
        } else {
            $_str_forward = base64_decode($arr_logonRow["forward"]);
            if (stristr($_str_forward, "logon")) {
                $_str_forward = BG_URL_ADMIN . "ctl.php";
            }
            header("Location: " . $_str_forward);
        }
        exit;
    break;

    default:
        switch ($GLOBALS["act_get"]) {
            case "logout":
                $arr_logonRow = $ctl_logon->ctl_logout(); //登出
                header("Location: " . base64_decode($arr_logonRow["forward"]));
                exit;
            break;

            default:
                $arr_logonRow = $ctl_logon->ctl_logon(); //登录界面
            break;
        }
    break;
}
