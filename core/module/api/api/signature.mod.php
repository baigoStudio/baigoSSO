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


$ctrl_signature = new CONTROL_API_API_SIGNATURE(); //初始化签名

switch ($GLOBALS['method']) {
    case 'post':
        switch ($GLOBALS['route']['bg_act']) {
            case 'signature':
                $ctrl_signature->ctrl_signature(); //生成签名
            break;

            case 'verify':
                $ctrl_signature->ctrl_verify(); //验证签名
            break;
        }
    break;
}
