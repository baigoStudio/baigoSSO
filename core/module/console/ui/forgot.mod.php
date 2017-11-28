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


$ctrl_forgot = new CONTROL_CONSOLE_UI_FORGOT(); //初始化登录

switch ($GLOBALS['route']['bg_act']) {
    case 'step_2':
        $ctrl_forgot->ctrl_step_2(); //登出
    break;

    default:
        $ctrl_forgot->ctrl_step_1(); //登出
    break;
}
