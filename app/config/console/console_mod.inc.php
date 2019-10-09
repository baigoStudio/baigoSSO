<?php
/*-----------------------------------------------------------------
！！！！警告！！！！
以下为系统文件，请勿编辑
-----------------------------------------------------------------*/

//不能非法包含或直接执行
defined('IN_GINKGO') or exit('Access denied');

/*----------后台管理模块----------*/
return array(
    'user' => array(
        'main' => array(
            'title'  => 'User management',
            'mod'    => 'user',
            'icon'   => 'users',
        ),
        'list' => array(
            'index' => array(
                'title' => 'User list',
                'mod'   => 'user',
                'act'   => 'index',
            ),
            'form' => array(
                'title' => 'Add user',
                'mod'   => 'user',
                'act'   => 'form',
            ),
            'import' => array(
                'title' => 'Import',
                'mod'   => 'import',
                'act'   => 'index',
            ),
        ),
        'allow' => array(
            'browse'    => 'Browse',
            'add'       => 'Add',
            'edit'      => 'Edit',
            'delete'    => 'Delete',
            'import'    => 'Import',
        ),
    ),
    'pm' => array(
        'main' => array(
            'title'  => 'Private message',
            'mod'    => 'pm',
            'icon'   => 'envelope',
        ),
        'list' => array(
            'index' => array(
                'title' => 'Message list',
                'mod'   => 'pm',
                'act'   => 'index',
            ),
            'bulk' => array(
                'title' => 'Bulk send',
                'mod'   => 'pm',
                'act'   => 'bulk',
            ),
        ),
        'allow' => array(
            'browse'    => 'Browse',
            'bulk'      => 'Bulk send',
            'delete'    => 'Delete',
        ),
    ),
    'app' => array(
        'main' => array(
            'title'  => 'Application',
            'mod'    => 'app',
            'icon'   => 'mobile-alt',
        ),
        'list' => array(
            'index' => array(
                'title' => 'Application list',
                'mod'   => 'app',
                'act'   => 'index',
            ),
            'form' => array(
                'title' => 'Add application',
                'mod'   => 'app',
                'act'   => 'form',
            ),
        ),
        'allow' => array(
            'browse'    => 'Browse',
            'add'       => 'Add',
            'edit'      => 'Edit',
            'delete'    => 'Delete',
            'belong'    => 'User authorization',
        ),
    ),
    'verify' => array(
        'main' => array(
            'title'  => 'Validation token',
            'mod'    => 'verify',
            'icon'   => 'file-alt',
        ),
        'list' => array(
            'index' => array(
                'title' => 'Token list',
                'mod'   => 'verify',
                'act'   => 'index',
            ),
        ),
        'allow' => array(
            'verify'    => 'Validation token',
        ),
    ),
    'admin' => array(
        'main' => array(
            'title'  => 'Administrator',
            'mod'    => 'admin',
            'icon'   => 'user-lock',
        ),
        'list' => array(
            'index' => array(
                'title' => 'Administrator list',
                'mod'   => 'admin',
                'act'   => 'index',
            ),
            'form' => array(
                'title' => 'Add',
                'mod'   => 'admin',
                'act'   => 'form',
            ),
            'auth' => array(
                'title' => 'Authorization',
                'mod'   => 'auth',
                'act'   => 'form',
            ),
        ),
        'allow' => array(
            'browse'    => 'Browse',
            'add'       => 'Add',
            'edit'      => 'Edit',
            'delete'    => 'Delete',
        ),
    ),
    'plugin' => array(
        'main' => array(
            'title' => 'Plugin',
            'mod'   => 'plugin',
            'icon'  => 'puzzle-piece',
        ),
        'list' => array(
            'index' => array(
                'title' => 'Plugin management',
                'mod'   => 'plugin',
                'act'   => 'index',
            ),
        ),
        'allow' => array(
            'browse'    => 'Browse',
            'install'   => 'Install',
            'edit'      => 'Edit',
            'option'    => 'Option',
            'uninstall' => 'Uninstall',
        ),
    ),
);
