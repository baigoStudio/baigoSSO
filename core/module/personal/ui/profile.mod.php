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

$ctrl_profil = new CONTROL_PERSONAL_UI_PROFILE();

switch ($GLOBALS['route']['bg_act']) {
    case 'mailbox':
        $ctrl_profil->ctrl_mailbox(); //更换邮箱
    break;
}
