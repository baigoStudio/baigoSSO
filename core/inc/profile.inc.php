<?php
/*-----------------------------------------------------------------
！！！！警告！！！！
以下为系统文件，请勿编辑
-----------------------------------------------------------------*/

//不能非法包含或直接执行
if (!defined('IN_BAIGO')) {
    exit('Access Denied');
}

/*----------后台管理模块----------*/
return array(
    'info'      => array(
        'title' => 'Profile',
    ),
    /*'prefer'    => array(
        'title' => 'Preferences',
    ),*/
    'pass'      => array(
        'title' => 'Password',
    ),
    'qa'        => array(
        'title' => 'Security Question',
    ),
    'mailbox'   => array(
        'title' => 'Mailbox',
    ),
);
