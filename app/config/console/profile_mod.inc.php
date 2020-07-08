<?php
/*-----------------------------------------------------------------
！！！！警告！！！！
以下为系统文件，请勿编辑
-----------------------------------------------------------------*/

// 不能非法包含或直接执行
defined('IN_GINKGO') or exit('Access denied');

/*----------后台管理模块----------*/
return array(
    'info'      => array(
        'title' => 'Profile info',
        'icon'  => 'user',
    ),
    /*'prefer'    => array(
        'title' => 'Preferences',
    ),*/
    'pass'      => array(
        'title' => 'Password',
        'icon'  => 'key',
    ),
    'secqa'       => array(
        'title' => 'Security question',
        'icon'  => 'question-circle',
    ),
    'mailbox'   => array(
        'title' => 'Mailbox',
        'icon'  => 'at',
    ),
);
