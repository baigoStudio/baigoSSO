<?php
/*-----------------------------------------------------------------
！！！！警告！！！！
以下为系统文件，请勿修改
-----------------------------------------------------------------*/

//不能非法包含或直接执行
if(!defined("IN_BAIGO")) {
    exit("Access Denied");
}

if (file_exists(BG_PATH_CONFIG . "is_install.php")) { //如果已安装文件存在
    include_once(BG_PATH_CONFIG . "is_install.php");  //载入
    if (defined("BG_INSTALL_PUB") && PRD_SSO_PUB > BG_INSTALL_PUB) {
        header("Location: " . BG_URL_INSTALL . "ctl.php?mod=upgrade"); //如果已安装文件中的版本小于当前版本则升级
    } else {
        header("Location: " . BG_URL_INSTALL . "ctl.php?mod=alert&act_get=show&alert=x030403"); //否则抛出已安装错误
    }
    exit;
}

include_once(BG_PATH_FUNC . "init.func.php"); //初始化
switch ($GLOBALS["act_get"]) {
    case "dbconfig":
    case "ext":
        $arr_set = array(
            "base"      => true, //基本设置
            "ssin"      => true, //启用会话
            "header"    => "Content-Type: text/html; charset=utf-8", //header
            "ssin_file" => true, //由于安装时，session 数据表表尚未创建，故临时采用文件形式的 session
        );
    break;

    default:
        $arr_set = array(
            "base"      => true, //基本设置
            "ssin"      => true, //启用会话
            "header"    => "Content-Type: text/html; charset=utf-8", //header
            "db"        => true, //连接数据库
            "type"      => "ctl", //模块类型
        );
    break;
}
fn_init($arr_set);

include_once(BG_PATH_CLASS . "mysqli.class.php"); //载入数据库类
include_once(BG_PATH_CONTROL . "install/ctl/install.class.php"); //载入安装控制器

$ctl_install            = new CONTROL_INSTALL(); //初始化安装

switch ($GLOBALS["act_get"]) {
    case "dbconfig":
        $arr_installRow = $ctl_install->ctl_dbconfig(); //数据库
        if ($arr_installRow["alert"] != "y030404") {
            header("Location: " . BG_URL_INSTALL . "ctl.php?mod=alert&act_get=show&alert=" . $arr_installRow["alert"]);
            exit;
        }
    break;

    case "dbtable":
        $arr_installRow = $ctl_install->ctl_dbtable(); //创建数据表
        if ($arr_installRow["alert"] != "y030404") {
            header("Location: " . BG_URL_INSTALL . "ctl.php?mod=alert&act_get=show&alert=" . $arr_installRow["alert"]);
            exit;
        }
    break;

    case "admin":
        $arr_installRow = $ctl_install->ctl_admin(); //创建管理员
        if ($arr_installRow["alert"] != "y030405") {
            header("Location: " . BG_URL_INSTALL . "ctl.php?mod=alert&act_get=show&alert=" . $arr_installRow["alert"]);
            exit;
        }
    break;

    case "over":
        $arr_installRow = $ctl_install->ctl_over(); //安装结束
        if ($arr_installRow["alert"] != "y030405") {
            header("Location: " . BG_URL_INSTALL . "ctl.php?mod=alert&act_get=show&alert=" . $arr_installRow["alert"]);
            exit;
        }
    break;

    case "reg":
    case "base":
    case "smtp":
        $arr_installRow = $ctl_install->ctl_form(); //其他
        if ($arr_installRow["alert"] != "y030405") {
            header("Location: " . BG_URL_INSTALL . "ctl.php?mod=alert&act_get=show&alert=" . $arr_installRow["alert"]);
            exit;
        }
    break;

    default:
        $arr_installRow = $ctl_install->ctl_ext();  //php 扩展是否安装
        if ($arr_installRow["alert"] != "y030403") {
            header("Location: " . BG_URL_INSTALL . "ctl.php?mod=alert&act_get=show&alert=" . $arr_installRow["alert"]);
            exit;
        }
    break;
}
