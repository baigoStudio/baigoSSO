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


$ctrl_pm = new CONTROL_CONSOLE_UI_PM(); //初始化短信

switch ($GLOBALS['route']['bg_act']) {
    case 'send':
        $ctrl_pm->ctrl_send(); //发送
    break;

    case 'bulk':
        $ctrl_pm->ctrl_bulk(); //群发
    break;

    case 'show':
        $ctrl_pm->ctrl_show(); //显示
    break;

    default:
        $ctrl_pm->ctrl_list(); //列出
    break;
}
