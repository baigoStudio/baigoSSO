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
    'base'      => true, //基本设置
    'ssin'      => true, //启用会话
    'db'        => true, //连接数据库
);
$obj_runtime->run($arr_set);

$ctrl_reg = new CONTROL_PERSONAL_UI_REG();

switch ($GLOBALS['route']['bg_act']) {
    case 'confirm':
        $ctrl_reg->ctrl_confirm(); //激活
    break;
}
