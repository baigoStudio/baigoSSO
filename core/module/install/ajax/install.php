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
switch ($GLOBALS["act_post"]) {
    case "dbconfig": //数据库配置时
        $arr_set = array(
            "base"      => true, //基本设置
            "ssin"      => true, //启用会话
            "header"    => "Content-type: application/json; charset=utf-8", //header
            "ssin_file" => true, //由于安装时，session 数据表表尚未创建，故临时采用文件形式的 session
        );
    break;

    default:
        $arr_set = array(
            "base"      => true, //基本设置
            "ssin"      => true, //启用会话
            "header"    => "Content-type: application/json; charset=utf-8", //header
            "db"        => true, //连接数据库
            "type"      => "ajax", //模块类型
        );
    break;
}
fn_init($arr_set);

include_once(BG_PATH_CLASS . "mysqli.class.php"); //载入数据库类
include_once(BG_PATH_CONTROL . "install/ajax/install.class.php"); //载入安装控制器

$ajax_install = new AJAX_INSTALL(); //初始化安装

switch ($GLOBALS["act_post"]) {
    case "dbconfig":
        $ajax_install->ajax_dbconfig(); //数据库
    break;

    case "admin":
        $ajax_install->ajax_admin(); //创建管理员
    break;

    case "over":
        $ajax_install->ajax_over(); //安装结束
    break;

    case "reg":
    case "base":
    case "smtp":
        $ajax_install->ajax_submit(); //其他
    break;
}
