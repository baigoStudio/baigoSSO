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
  'var_extra' => array(
    'base' => array( //设置默认值
      'site_name'         => 'baigo SSO',
      'site_perpage'      => 30,
      'site_date'         => 'Y-m-d',
      'site_date_short'   => 'm-d',
      'site_time'         => 'H:i:s',
      'site_time_short'   => 'H:i',
      'site_timezone'     => 'Asia/Shanghai',
      'site_tpl'          => 'default',
      'access_expire'     => 60,
      'refresh_expire'    => 60,
      'verify_expire'     => 30,
    ),
    'reg' => array(
      'reg_acc'        => 'on',
      'reg_needmail'   => 'off',
      'reg_confirm'    => 'off',
      'login_mail'     => 'off',
      'acc_mail'       => '',
      'bad_mail'       => '',
      'bad_name'       => '',
    ),
    'mailtpl' => array( //邮件模板默认
      'reg_subject'       => '',
      'reg_content'       => '',
      'mailbox_subject'   => '',
      'mailbox_content'   => '',
      'forgot_subject'    => '',
      'forgot_content'    => '',
    ),
  ),
);
