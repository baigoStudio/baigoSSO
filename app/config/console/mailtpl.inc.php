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
  /*------ 注册------*/
  'reg' => array(
    'title' => 'Confirm registration',
    'lists' => array(
      'subject'   => array(
        'title'      => 'Subject',
        'type'       => 'str',
        'format'     => 'text',
        'require'    => 'true',
      ),
      'content'   => array(
        'title'      => 'Content',
        'type'       => 'textarea',
        'format'     => 'text',
        'require'    => 'true',
      ),
    ),
  ),

  /*------ 更换邮箱------*/
  'mailbox' => array(
    'title' => 'Update mailbox',
    'lists' => array(
      'subject'   => array(
        'title'      => 'Subject',
        'type'       => 'str',
        'format'     => 'text',
        'require'    => 'true',
      ),
      'content'   => array(
        'title'      => 'Content',
        'type'       => 'textarea',
        'format'     => 'text',
        'require'    => 'true',
      ),
    ),
  ),

  /*------ 找回密码------*/
  'forgot' => array(
    'title' => 'Forgot password',
    'lists' => array(
      'subject'   => array(
        'title'      => 'Subject',
        'type'       => 'str',
        'format'     => 'text',
        'require'    => 'true',
      ),
      'content'   => array(
        'title'      => 'Content',
        'type'       => 'textarea',
        'format'     => 'text',
        'require'    => 'true',
      ),
    ),
  ),
);
