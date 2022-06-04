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
  'session_expire'  => 20 * GK_MINUTE,
  'remember_expire' => 30 * GK_DAY,
  'opt_extra'       => array( ),
);
