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


$ctrl_sync = new CONTROL_API_API_SYNC(); //初始化同步

switch ($GLOBALS['method']) {
    case 'post':
        switch ($GLOBALS['route']['bg_act']) {
            case 'login':
                $ctrl_sync->ctrl_login(); //同步登录
            break;

            case 'logout':
                $ctrl_sync->ctrl_logout(); //同步登出
            break;
        }
    break;
}
