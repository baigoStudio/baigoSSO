<?php
/*-----------------------------------------------------------------
！！！！警告！！！！
以下为系统文件，请勿修改
-----------------------------------------------------------------*/

// 不能非法包含或直接执行
if (!defined('IN_GINKGO')) {
  return 'Access denied';
}

/*-------------------------通用-------------------------*/
return array(
  'Access token'                  => '访问口令',
  'User ID'                       => '用户 ID',
  'User not found'                => '用户不存在',
  'User is disabled'              => '用户被禁用',
  'Access token expired'          => '访问口令过期',
  'Access token is incorrect'     => '访问口令错误',
  '{:attr} require'               => '{:attr} 是必需的',
);
