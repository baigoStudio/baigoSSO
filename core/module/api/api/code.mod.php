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

$ctrl_code = new CONTROL_API_API_CODE(); //初始化密文

switch ($GLOBALS['method']) {
    case 'post':
        switch ($GLOBALS['route']['bg_act']) {
            case 'encode':
                $ctrl_code->ctrl_encode(); //加密
            break;

            case 'decode':
                $ctrl_code->ctrl_decode(); //解密
            break;
        }
    break;
}