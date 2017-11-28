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
    'personal' => array(
        'ui' => array(
            'reg',
            'profile',
            'forgot',
        ),
        'request' => array(
            'reg',
            'profile',
            'forgot',
        ),
    ),
    'console' => array(
        'ui' => array(
            'user',
            'pm',
            'app',
            'verify',
            'admin',
            'opt',
            'profile',
            'login',
            'forgot',
        ),
        'request' => array(
            'user',
            'pm',
            'app',
            'verify',
            'admin',
            'opt',
            'profile',
            'login',
            'forgot',
        ),
    ),
    'misc' => array(
        'ui' => array(
            'seccode',
        ),
        'request' => array(
            'seccode',
        ),
    ),
    'api' => array(
        'api' => array(
            'user',
            'profile',
            'forgot',
            'pm',
            'code',
            'signature',
            'sync',
            'setup',
        ),
    ),
    'install' => array(
        'ui' => array(
            'setup',
            'upgrade',
        ),
        'request' => array(
            'setup',
            'upgrade',
        ),
    ),
    'help' => array(
        'ui' => array(
            'help',
        ),
    ),
);
