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
    //"ssin"          => true,
    "header"        => "Content-type: application/json; charset=utf-8", //header
    "db"            => true, //连接数据库
    "type"          => "ajax", //模块类型
);
fn_init($arr_set);

include_once(BG_PATH_CONTROL . "api/user.class.php"); //载入用户控制器

$api_user = new API_USER(); //初始化用户

switch ($GLOBALS["act_post"]) {
    case "reg":
        $api_user->api_reg(); //注册
    break;

    case "refresh_token":
        $api_user->api_refresh_token(); //刷新访问口令
    break;

    case "login":
        $api_user->api_login(); //登录
    break;

    case "edit":
        $api_user->api_edit(); //编辑
    break;

    case "mailbox":
        $api_user->api_mailbox(); //更换邮箱
    break;

    case "nomail":
        $api_user->api_nomail(); //没有收到激活邮件
    break;

    case "forgot":
        $api_user->api_forgot(); //忘记密码
    break;

    case "del":
        $api_user->api_del(); //删除
    break;

    default:
        switch ($GLOBALS["act_get"]) {
            case "get":
            case "read":
                $api_user->api_read(); //读取
            break;

            case "chkname":
            case "check_name":
                $api_user->api_chkname(); //验证用户名是否可以注册
            break;

            case "chkmail":
            case "check_mail":
                $api_user->api_chkmail(); //验证邮箱是否可以注册
            break;
        }
    break;
}
