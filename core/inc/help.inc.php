<?php
/*-----------------------------------------------------------------
！！！！警告！！！！
以下为系统文件，请勿编辑
-----------------------------------------------------------------*/

//不能非法包含或直接执行
if (!defined('IN_BAIGO')) {
    exit('Access Denied');
}

return array(
    'nav' => array(
        'intro' => array(
            'title' => 'Introduction',
        ),
        'install' => array(
            'title' => 'Setup / Upgrade',
            'sub'   => array(
                'setup'     => 'Setup',
                'upgrade'   => 'Upgrade',
                'manual'    => 'Manual Setup / Upgrade',
                'deploy'    => 'Advanced deployment',
            ),
        ),
        'console' => array(
            'title' => 'The Console',
        ),
        'doc' => array(
            'title' => 'Documentation',
            'sub'   => array(
                'tpl'   => 'Template Documentation',
                'api'   => 'API Documentation',
            ),
        ),
    ),
    'mod' => array(
        'intro' => array(
            'active'    => 'intro',
            'menu'      => array(
                'outline'   => 'Introduction',
                'faq'       => 'FAQ',
            ),
        ),
        'setup' => array(
            'active'   => 'install',
            'menu'     => array(
                'outline'   => 'Setup Outline',
                'phplib'    => 'PHP Extension Check',
                'dbconfig'  => 'Database Settings',
                'dbtable'   => 'Create Database',
                'base'      => 'Base Settings',
                'reg'       => 'Register Settings',
                'smtp'      => 'SMTP Settings',
                'admin'     => 'Create Administrator',
                'over'      => 'Complete Setup',
            ),
        ),
        'upgrade' => array(
            'active'   => 'install',
            'menu'     => array(
                'outline'   => 'Upgrade Outline',
                'phplib'    => 'PHP Extension Check',
                'dbconfig'  => 'Database Settings',
                'dbtable'   => 'Upgrade Database',
                'base'      => 'Base Settings',
                'reg'       => 'Register Settings',
                'smtp'      => 'SMTP Settings',
                'admin'     => 'Create Administrator',
                'over'      => 'Complete Upgrade',
            ),
        ),
        'manual' => array(
            'active'   => 'install',
            'menu'     => array(
                'outline'   => 'Manual Outline',
                'dbconfig'  => 'Database Settings',
                'base'      => 'Base Settings',
                'reg'       => 'Register Settings',
                'smtp'      => 'SMTP Settings',
            ),
        ),
        'deploy' => array(
            'active'   => 'install',
            'menu'     => array(
                'outline'   => 'Advanced Deployment',
            ),
        ),
        'console' => array(
            'active'   => 'console',
            'menu'     => array(
                'outline'   => 'The Console Outline',
                'user'      => 'User Management',
                'pm'        => 'Private Message',
                'app'       => 'API Settings',
                'admin'     => 'Administrator',
                'opt'       => 'Settings',
            ),
        ),
        'tpl' => array(
            'active'   => 'doc',
            'menu'     => array(
                'outline'   => 'Template Outline',
                'common'    => 'Common Resources',
                'error'     => 'Alert Message',
                'reg'       => 'Register',
                'forgot'    => 'Forget Password',
                'profile'   => 'Profile',
            ),
        ),
        'api' => array(
            'active'   => 'doc',
            'menu'     => array(
                'outline'       => 'API Outline',
                'page'          => 'Paging Parameters',
                'code'          => 'Encryption Code',
                'signature'     => 'Signature',
                'user'          => 'User',
                'profile'       => 'Profile',
                'forgot'        => 'Forget Password',
                'pm'            => 'Private Message',
                'notify'        => 'Notify',
                'sync'          => 'Sync',
                'sync_notify'   => 'Sync Notify',
                'setup'         => 'Setup',
                'rcode'         => 'Return Code',
            ),
        ),
    ),
);
