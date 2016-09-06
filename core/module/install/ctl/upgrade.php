<?php
/*-----------------------------------------------------------------
！！！！警告！！！！
以下为系统文件，请勿修改
-----------------------------------------------------------------*/

//不能非法包含或直接执行
if (!defined("IN_BAIGO")) {
    exit("Access Denied");
}

if (!file_exists(BG_PATH_CONFIG . "is_install.php")) { //如果已安装文件不存在
    header("Location: " . BG_URL_INSTALL . "ctl.php?mod=install"); //跳转至安装界面
} else {
    include_once(BG_PATH_CONFIG . "is_install.php"); //载入
    if (defined("BG_INSTALL_PUB") && PRD_SSO_PUB <= BG_INSTALL_PUB) {
        header("Location: " . BG_URL_INSTALL . "ctl.php?mod=alert&act_get=show&alert=x030403"); //如果已安装文件中的版本大于等于当前版本则抛出错误信息
    }
}

include_once(BG_PATH_FUNC . "init.func.php"); //初始化
switch ($GLOBALS["act_get"]) {
    case "dbtable":
    case "reg":
    case "base":
    case "smtp":
    case "over":
        $arr_set = array(
            "base"      => true, //基本设置
            "ssin"      => true, //启用会话
            "header"    => "Content-Type: text/html; charset=utf-8", //header
            "db"        => true, //连接数据库
            "type"      => "install",  //模块类型
        );
    break;

    default:
        $arr_set = array(
            "base"      => true, //基本设置
            "ssin"      => true, //启用会话
            "header"    => "Content-Type: text/html; charset=utf-8", //header
            "ssin_file" => true, //由于升级时，session 数据表表可能尚未创建，故临时采用文件形式的 session
        );
    break;
}
fn_init($arr_set);

include_once(BG_PATH_CLASS . "mysqli.class.php"); //载入数据库类
include_once(BG_PATH_CONTROL . "install/ctl/upgrade.class.php"); //载入升级控制器

$ctl_upgrade = new CONTROL_UPGRADE(); //初始化升级

switch ($GLOBALS["act_get"]) {
    case "dbconfig":
        $arr_upgradeRow = $ctl_upgrade->ctl_dbconfig(); //数据库
        if ($arr_upgradeRow["alert"] != "y030404") {
            header("Location: " . BG_URL_INSTALL . "ctl.php?mod=alert&act_get=show&alert=" . $arr_upgradeRow["alert"]);
            exit;
        }
    break;

    case "dbtable":
        $arr_upgradeRow = $ctl_upgrade->ctl_dbtable(); //升级数据表
        if ($arr_upgradeRow["alert"] != "y030404") {
            header("Location: " . BG_URL_INSTALL . "ctl.php?mod=alert&act_get=show&alert=" . $arr_upgradeRow["alert"]);
            exit;
        }
    break;

    case "reg":
    case "base":
    case "smtp":
        $arr_upgradeRow = $ctl_upgrade->ctl_form(); //其他
        if ($arr_upgradeRow["alert"] != "y030405") {
            header("Location: " . BG_URL_INSTALL . "ctl.php?mod=alert&act_get=show&alert=" . $arr_upgradeRow["alert"]);
            exit;
        }
    break;

    case "over":
        $arr_upgradeRow = $ctl_upgrade->ctl_over(); //升级结束
        if ($arr_upgradeRow["alert"] != "y030405") {
            header("Location: " . BG_URL_INSTALL . "ctl.php?mod=alert&act_get=show&alert=" . $arr_upgradeRow["alert"]);
            exit;
        }
    break;

    default:
        $arr_upgradeRow = $ctl_upgrade->ctl_ext(); //php 扩展是否安装
        if ($arr_upgradeRow["alert"] != "y030403") {
            header("Location: " . BG_URL_INSTALL . "ctl.php?mod=alert&act_get=show&alert=" . $arr_upgradeRow["alert"]);
            exit;
        }
    break;
}
