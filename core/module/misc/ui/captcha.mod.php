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
    'base'      => true,
    'ssin'      => true, //启用会话
    'db'        => true, //连接数据库
);
$obj_runtime->run($arr_set);


$ctrl_captcha = new CONTROL_MISC_UI_CAPTCHA(); //初始化验证对象

$ctrl_captcha->ctrl_make(); //生成验证码
