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
            'ctrl'   => 'user',
            'icon'   => 'users',
        ),
        'lists' => array(
            'index' => array(
                'title' => 'User list',
                'ctrl'  => 'user',
                'act'   => 'index',
            ),
            'form' => array(
                'title' => 'Add user',
                'ctrl'  => 'user',
                'act'   => 'form',
            ),
            'import' => array(
                'title' => 'Import',
                'ctrl'  => 'import',
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
            'ctrl'   => 'pm',
            'icon'   => 'envelope',
        ),
        'lists' => array(
            'index' => array(
                'title' => 'Message list',
                'ctrl'  => 'pm',
                'act'   => 'index',
            ),
            'bulk' => array(
                'title' => 'Bulk send',
                'ctrl'  => 'pm',
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
            'title'  => 'App',
            'ctrl'   => 'app',
            'icon'   => 'mobile-alt',
        ),
        'lists' => array(
            'index' => array(
                'title' => 'App list',
                'ctrl'  => 'app',
                'act'   => 'index',
            ),
            'form' => array(
                'title' => 'Add App',
                'ctrl'  => 'app',
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
            'ctrl'   => 'verify',
            'icon'   => 'file-alt',
        ),
        'lists' => array(
            'index' => array(
                'title' => 'Token list',
                'ctrl'  => 'verify',
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
            'ctrl'   => 'admin',
            'icon'   => 'user-lock',
        ),
        'lists' => array(
            'index' => array(
                'title' => 'Administrator list',
                'ctrl'  => 'admin',
                'act'   => 'index',
            ),
            'form' => array(
                'title' => 'Add',
                'ctrl'  => 'admin',
                'act'   => 'form',
            ),
            'auth' => array(
                'title' => 'Authorization',
                'ctrl'  => 'auth',
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
            'ctrl'  => 'plugin',
            'icon'  => 'puzzle-piece',
        ),
        'lists' => array(
            'index' => array(
                'title' => 'Plugin management',
                'ctrl'  => 'plugin',
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
