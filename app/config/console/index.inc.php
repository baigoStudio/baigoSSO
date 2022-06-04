<?php
/*-----------------------------------------------------------------
！！！！警告！！！！
以下为系统文件，请勿修改
-----------------------------------------------------------------*/

//不能非法包含或直接执行
if (!defined('IN_GINKGO')) {
  return 'Access denied';
}

return array(
  'count_lists' => array(
    'user'     => array(
      'title'  => 'User',
      'lists'  => array(
        'total'  => true,
        'status' => true,
      ),
    ),
    'app' => array(
      'title'  => 'App',
      'lists'  => array(
        'total'  => true,
        'status' => true,
      ),
    ),
    'pm'  => array(
      'title'  => 'Private message',
      'lists'  => array(
        'total'  => true,
        'status' => true,
        'type'   => true,
      ),
    ),
    'admin'   => array(
      'title'  => 'Administrator',
      'lists'  => array(
        'total'  => true,
        'status' => true,
        'type'   => true,
      ),
    ),
  ),
);
