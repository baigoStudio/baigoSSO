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
    'user' => array(
        'main' => array(
            'title'  => '用户管理',
            'icon'   => 'people',
        ),
        'sub' => array(
            'list'      => '所有用户',
            'form'      => '创建用户',
            'import'    => '批量导入',
        ),
        'allow' => array(
            'browse' => '浏览',
            'add'    => '创建',
            'edit'   => '编辑',
            'del'    => '删除',
            'import' => '批量导入',
        ),
    ),
    'pm' => array(
        'main' => array(
            'title'  => '站内短信',
            'icon'   => 'envelope-closed',
        ),
        'sub' => array(
            'list' => '所有短信',
            'send' => '发送短信',
            'bulk' => '群发短信',
        ),
        'allow' => array(
            'browse'    => '浏览',
            'send'      => '发送',
            'bulk'      => '群发',
            'del'       => '删除',
        ),
    ),
    'app' => array(
        'main' => array(
            'title'  => '应用管理',
            'icon'   => 'terminal',
        ),
        'sub' => array(
            'list' => '所有应用',
            'form' => '创建应用',
        ),
        'allow' => array(
            'browse' => '浏览',
            'add'    => '创建',
            'edit'   => '编辑',
            'del'    => '删除',
        ),
    ),
    'verify' => array(
        'main' => array(
            'title'  => '验证日志',
            'icon'   => 'timer',
        ),
        'sub' => array(
            'list'      => '所有日志',
        ),
        'allow' => array(
            'verify'    => '验证日志',
        ),
    ),
    'admin' => array(
        'main' => array(
            'title'  => '管理员',
            'icon'   => 'person',
        ),
        'sub' => array(
            'list' => '所有管理员',
            'form' => '创建管理员',
            'auth' => '授权为管理员',
        ),
        'allow' => array(
            'browse' => '浏览',
            'add'    => '创建',
            'edit'   => '编辑',
            'del'    => '删除',
        ),
    ),
);
