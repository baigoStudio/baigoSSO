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
    'base' => true, //基本设置
);
$obj_runtime->run($arr_set);

$ctrl_help = new CONTROL_HELP_UI_HELP();

$ctrl_help->ctrl_show(); //显示帮助
