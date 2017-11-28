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
    'db'            => true, //连接数据库
);
$obj_runtime->run($arr_set);


$ctrl_forgot = new CONTROL_API_API_FORGOT(); //初始化用户
switch ($GLOBALS['method']) {
    case 'post':
        switch ($GLOBALS['route']['bg_act']) {
            case 'bymail':
                $ctrl_forgot->ctrl_bymail(); //忘记密码
            break;

            case 'byqa':
                $ctrl_forgot->ctrl_byqa(); //删除
            break;
        }
    break;
}