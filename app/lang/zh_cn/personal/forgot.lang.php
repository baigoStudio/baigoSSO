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
  'Access denied'                         => '拒绝访问',
  'Username'                              => '用户名', //用户名
  'Input error'                           => '输入错误，请检查！',
  'Token'                                 => '表单令牌',
  'Answer'                                => '回答',
  'Captcha'                               => '验证码',
  'Captcha is incorrect'                  => '验证码错误',
  'Refresh'                               => '换一个',
  'Apply'                                 => '提交',
  'Next'                                  => '下一步',
  'Unable to get username'                => '无法获取用户名',
  'By email'                              => '通过邮件找回',
  'By security question'                  => '回答密保问题找回',
  'User not found'                        => '用户不存在',
  'User is disabled'                      => '用户被禁用',
  'Forgot password'                       => '忘记密码',
  'New password'                          => '新密码',
  'Confirm password'                      => '确认密码',
  'Did not make any changes'              => '未做任何修改',
  'Not allowed to edit'                   => '禁止修改',
  'Form token is incorrect'               => '表单令牌错误',
  'Password is incorrect'                 => '密码错误',
  'Change mailbox successfully'           => '更换邮箱成功',
  'Update password successfully'          => '更新密码成功',
  'Did not make any changes'              => '未做任何修改',
  'Security question'                     => '密保问题',
  'Security answer'                       => '回答',
  'Security answer is incorrect'          => '密保答案错误',
  '{:attr} require'                       => '{:attr} 是必需的',
  'Size of {:attr} must be {:rule}'       => '{:attr} 的长度必须在 {:rule} 之间',
  '{:attr} out of accord with {:confirm}' => '{:attr} 与 {:confirm} 不一致',
  '{:attr} must be alpha-numeric'         => '{:attr} 必须为字母和数字',
  'You have not set a mailbox!'           => '您没有设置邮箱！',
  'You have not set a secret question!'   => '您没有设置密保问题！',
  'Send verification email failed'        => '发送确认邮件失败',
  'A verification email has been sent to your mailbox, please verify.' => '已将验证邮件发送至您的邮箱，请验证。',
  'System will send a confirmation email to the mailbox you reserved.' => '选择此方式找回密码，系统将向您预留的邮箱发送确认邮件。',
  'You have not set a mailbox and security question, cannot reset your password. Please contact your system administrator!' => '您没有设置邮箱和密保问题，无法找回密码，请联系系统管理员！'
);
