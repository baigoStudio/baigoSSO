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


$ctrl_app = new CONTROL_CONSOLE_REQUEST_APP(); //初始化应用

switch ($GLOBALS['method']) {
    case 'post':
        switch ($GLOBALS['route']['bg_act']) {
            case 'auth':
                $ctrl_app->ctrl_auth(); //授权用户
            break;

            case 'deauth':
                $ctrl_app->ctrl_deauth(); //取消授权用户
            break;

            case 'reset':
                $ctrl_app->ctrl_reset(); //重置 APP KEY
            break;

            case 'submit':
                $ctrl_app->ctrl_submit(); //创建、编辑
            break;

            case 'enable':
            case 'disable':
                $ctrl_app->ctrl_status(); //状态
            break;

            case 'del':
                $ctrl_app->ctrl_del(); //删除
            break;

            case 'notify':
                $ctrl_app->ctrl_notify(); //通知测试
            break;
        }
    break;
}