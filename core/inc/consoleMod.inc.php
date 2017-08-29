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
            'title'  => 'User Management',
            'mod'    => 'user',
        ),
        'sub' => array(
            'list' => array(
                'title' => 'User List',
                'mod'   => 'user',
                'act'   => 'list',
            ),
            'form' => array(
                'title' => 'Create',
                'mod'   => 'user',
                'act'   => 'form',
            ),
            'import' => array(
                'title' => 'Import',
                'mod'   => 'user',
                'act'   => 'import',
            ),
        ),
        'allow' => array(
            'browse' => 'Browse',
            'add'    => 'Create',
            'edit'   => 'Edit',
            'del'    => 'Delete',
            'import' => 'Import',
        ),
    ),
    'pm' => array(
        'main' => array(
            'title'  => 'Private Message',
            'mod'    => 'pm',
        ),
        'sub' => array(
            'list' => array(
                'title' => 'Message List',
                'mod'   => 'pm',
                'act'   => 'list',
            ),
            'send' => array(
                'title' => 'Send Message',
                'mod'   => 'pm',
                'act'   => 'send',
            ),
            'bulk' => array(
                'title' => 'Bulk Send',
                'mod'   => 'pm',
                'act'   => 'bulk',
            ),
        ),
        'allow' => array(
            'browse'    => 'Browse',
            'send'      => 'Send',
            'bulk'      => 'Bulk Send',
            'del'       => 'Delete',
        ),
    ),
    'app' => array(
        'main' => array(
            'title'  => 'Application',
            'mod'    => 'app',
        ),
        'sub' => array(
            'list' => array(
                'title' => 'Application List',
                'mod'   => 'app',
                'act'   => 'list',
            ),
            'form' => array(
                'title' => 'Create',
                'mod'   => 'app',
                'act'   => 'form',
            ),
        ),
        'allow' => array(
            'browse' => 'Browse',
            'add'    => 'Create',
            'edit'   => 'Edit',
            'del'    => 'Delete',
        ),
    ),
    'verify' => array(
        'main' => array(
            'title'  => 'Validation Log',
            'mod'    => 'verify',
        ),
        'sub' => array(
            'list' => array(
                'title' => 'Validation Log',
                'mod'   => 'verify',
                'act'   => 'list',
            ),
        ),
        'allow' => array(
            'verify'    => 'Validation Log',
        ),
    ),
    'admin' => array(
        'main' => array(
            'title'  => 'Administrator',
            'mod'    => 'admin',
        ),
        'sub' => array(
            'list' => array(
                'title' => 'Administrator List',
                'mod'   => 'admin',
                'act'   => 'list',
            ),
            'form' => array(
                'title' => 'Create',
                'mod'   => 'admin',
                'act'   => 'form',
            ),
            'auth' => array(
                'title' => 'Authorization',
                'mod'   => 'admin',
                'act'   => 'auth',
            ),
        ),
        'allow' => array(
            'browse' => 'Browse',
            'add'    => 'Create',
            'edit'   => 'Edit',
            'del'    => 'Delete',
        ),
    ),
);
