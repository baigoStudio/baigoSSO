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
    'is_install'    => true, //告诉初始化函数，这是安装程序
);

switch ($GLOBALS['route']['bg_act']) {
    case 'dbtable':
    case 'auth':
    case 'admin':
    case 'reg':
    case 'base':
    case 'smtp':
    case 'over':
        $arr_set['db'] = true;
    break;

    default:
        $arr_set['ssin_file'] = true; //可能 session 数据表表尚未创建，故临时采用文件形式的 session
    break;
}
$obj_runtime->run($arr_set);


$ctrl_setup = new CONTROL_INSTALL_UI_SETUP(); //初始化安装

switch ($GLOBALS['route']['bg_act']) {
    case 'dbconfig':
        $ctrl_setup->ctrl_dbconfig(); //数据库
    break;

    case 'dbtable':
        $ctrl_setup->ctrl_dbtable(); //创建数据表
    break;

    case 'auth':
        $ctrl_setup->ctrl_auth(); //授权管理员
    break;

    case 'admin':
        $ctrl_setup->ctrl_admin(); //创建管理员
    break;

    case 'over':
        $ctrl_setup->ctrl_over(); //安装结束
    break;

    case 'reg':
    case 'base':
    case 'smtp':
        $ctrl_setup->ctrl_form(); //其他
    break;

    default:
        $ctrl_setup->ctrl_phplib();  //php 扩展是否安装
    break;
}
