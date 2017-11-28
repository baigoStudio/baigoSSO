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


$ctrl_app = new CONTROL_CONSOLE_UI_APP(); //初始化应用

switch ($GLOBALS['route']['bg_act']) {
    case 'show':
        $ctrl_app->ctrl_show(); //显示
    break;

    case 'form':
        $ctrl_app->ctrl_form(); //创建、编辑表单
    break;

    case 'belong':
        $ctrl_app->ctrl_belong(); //用户授权
    break;

    default:
        $ctrl_app->ctrl_list(); //列出
    break;
}