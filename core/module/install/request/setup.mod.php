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
    case 'dbconfig':
        $arr_set['ssin_file'] = true; //可能 session 数据表表尚未创建，故临时采用文件形式的 session
    break;

    default:
        $arr_set['db'] = true;
    break;
}
$obj_runtime->run($arr_set);


$ctrl_setup = new CONTROL_INSTALL_REQUEST_SETUP(); //初始化安装

switch ($GLOBALS['method']) {
    case 'post':
        switch ($GLOBALS['route']['bg_act']) {
            case 'dbconfig':
                $ctrl_setup->ctrl_dbconfig(); //数据库
            break;

            case 'auth':
                $ctrl_setup->ctrl_auth(); //创建管理员
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
                $ctrl_setup->ctrl_submit(); //其他
            break;
        }
    break;

    default:
        switch ($GLOBALS['route']['bg_act']) {
            case 'chkauth':
                $ctrl_setup->ctrl_chkauth(); //其他
            break;

            case 'chkname':
                $ctrl_setup->ctrl_chkname(); //其他
            break;
        }
    break;
}
