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


$ctrl_verify = new CONTROL_CONSOLE_REQUEST_VERIFY(); //初始化验证

switch ($GLOBALS['method']) {
    case 'post':
        switch ($GLOBALS['route']['bg_act']) {
            case 'enable':
            case 'disable':
                $ctrl_verify->ctrl_status(); //状态
            break;

            case 'del':
                $ctrl_verify->ctrl_del();  //删除
            break;
        }
    break;
}