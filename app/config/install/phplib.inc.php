<?php
/*-----------------------------------------------------------------
！！！！警告！！！！
以下为系统文件，请勿编辑
-----------------------------------------------------------------*/

// 不能非法包含或直接执行
if (!defined('IN_GINKGO')) {
  return 'Access denied';
}

return array(
  'pdo_mysql' => array(
    'title'     => 'PDO (The PHP Data Objects) for MySQL',
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
