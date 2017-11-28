<?php
/*-----------------------------------------------------------------
！！！！警告！！！！
以下为系统文件，请勿修改
-----------------------------------------------------------------*/

//不能非法包含或直接执行
if (!defined('IN_BAIGO')) {
    exit('Access Denied');
}

$arr_set = array(
    'base'          => true, //基本设置
    'db'            => true, //连接数据库
);
$obj_runtime->run($arr_set);


$ctrl_user = new CONTROL_API_API_USER(); //初始化用户
switch ($GLOBALS['method']) {
    case 'post':
        switch ($GLOBALS['route']['bg_act']) {
            case 'reg':
                $ctrl_user->ctrl_reg(); //注册
            break;

            case 'nomail':
                $ctrl_user->ctrl_nomail(); //没有收到激活邮件
            break;

            case 'login':
                $ctrl_user->ctrl_login(); //登录
            break;

            case 'edit':
                $ctrl_user->ctrl_edit(); //编辑
            break;

            case 'del':
                $ctrl_user->ctrl_del(); //删除
            break;
        }
    break;

    default:
        switch ($GLOBALS['route']['bg_act']) {
            case 'get':
            case 'read':
                $ctrl_user->ctrl_read(); //读取
            break;

            case 'chkname':
            case 'check_name':
                $ctrl_user->ctrl_chkname(); //验证用户名是否可以注册
            break;

            case 'chkmail':
            case 'check_mail':
                $ctrl_user->ctrl_chkmail(); //验证邮箱是否可以注册
            break;
        }
    break;
}