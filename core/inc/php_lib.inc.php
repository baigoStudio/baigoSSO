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
    'mysqli'      => 'MySQL Improved Extension (MySQLi)',
    'gd'          => 'Image Processing and GD (GD)',
    'mbstring'    => 'Multibyte String (MBString)',
    'curl'        => 'Client URL Library (cURL)',
);
