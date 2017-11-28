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
    'ssin'          => true, //启用会话
    'db'            => true, //连接数据库
);
$obj_runtime->run($arr_set);


$ctrl_admin = new CONTROL_CONSOLE_REQUEST_ADMIN(); //初始化管理员对象

switch ($GLOBALS['method']) {
    case 'post':
        switch ($GLOBALS['route']['bg_act']) {
            case 'submit':
                $ctrl_admin->ctrl_submit(); //创建、编辑
            break;

            case 'auth':
                $ctrl_admin->ctrl_auth(); //授权
            break;

            case 'del':
                $ctrl_admin->ctrl_del(); //删除
            break;

            case 'enable':
            case 'disable':
                $ctrl_admin->ctrl_status(); //状态
            break;
        }
    break;

    default:
        switch ($GLOBALS['route']['bg_act']) {
            case 'chkauth':
                $ctrl_admin->ctrl_chkauth(); //验证授权
            break;

            case 'chkname':
                $ctrl_admin->ctrl_chkname(); //验证用户名
            break;
        }
    break;
}