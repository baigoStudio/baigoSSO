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


$ctrl_opt = new CONTROL_CONSOLE_UI_OPT(); //初始化设置对象

switch ($GLOBALS['route']['bg_act']) {
    case 'chkver':
        $ctrl_opt->ctrl_chkver(); //数据库
    break;

    case 'dbconfig':
        $ctrl_opt->ctrl_dbconfig(); //数据库
    break;

    default:
        $ctrl_opt->ctrl_form(); //其他
    break;
}
