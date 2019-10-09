<?php
/*-----------------------------------------------------------------
！！！！警告！！！！
以下为系统文件，请勿编辑
-----------------------------------------------------------------*/

//不能非法包含或直接执行
defined('IN_GINKGO') or exit('Access denied');

return array(
    'pdo' => array(
        'title'     => 'PDO (The PHP Data Objects)',
        'installed' => false,
    ),
    'gd' => array(
        'title'     => 'GD Library (Image Processing and GD)',
        'installed' => false,
    ),
    'mbstring' => array(
        'title'     => 'MBString (Multibyte String)',
        'installed' => false,
    ),
    'curl' => array(
        'title'     => 'cURL (Client URL Library)',
        'installed' => false,
    ),
    'openssl' => array(
        'title'     => 'OpenSSL',
        'installed' => false,
    ),
);
