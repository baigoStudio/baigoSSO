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


$ctrl_user = new CONTROL_CONSOLE_UI_USER(); //初始化用户

switch ($GLOBALS['route']['bg_act']) {
    case 'import':
        $ctrl_user->ctrl_import(); //导入
    break;

    case 'show':
        $ctrl_user->ctrl_show();
    break;

    case 'form':
        $ctrl_user->ctrl_form(); //表单
    break;

    default:
        $ctrl_user->ctrl_list(); //列出
    break;
}
