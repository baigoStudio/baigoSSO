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
    "header"        => "Content-type: application/json; charset=utf-8", //header
    "db"            => true, //连接数据库
    "type"          => "ajax", //模块类型
    "ssin_begin"    => true, //开始管理员会话
);
fn_init($arr_set);

include_once(BG_PATH_CONTROL . "admin/ajax/user.class.php"); //载入用户控制器

$ajax_user = new AJAX_USER(); //初始化用户

switch ($GLOBALS["act_post"]) {
    case "convert":
        $ajax_user->ajax_convert(); //导入
    break;

    case "import":
        $ajax_user->ajax_import(); //导入
    break;

    case "csvDel":
        $ajax_user->ajax_csvDel(); //导入
    break;

    case "submit":
        $ajax_user->ajax_submit(); //创建、编辑
    break;

    case "enable":
    case "wait":
    case "disable":
        $ajax_user->ajax_status(); //状态
    break;

    case "del":
        $ajax_user->ajax_del(); //删除
    break;

    default:
        switch ($GLOBALS["act_get"]) {
            case "getname":
                $ajax_user->ajax_getname(); //验证用户名
            break;

            case "chkname":
                $ajax_user->ajax_chkname(); //验证用户名
            break;

            case "chkmail":
                $ajax_user->ajax_chkmail(); //验证 email
            break;
        }
    break;
}
