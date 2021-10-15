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
  'Password'                              => '密码', //密码
  'Input error'                           => '输入错误，请检查！',
  'Token'                                 => '表单令牌',
  'Status'                                => '状态',
  'Type'                                  => '类型',
  'Save'                                  => '保存',
  'Permission'                            => '权限',
  'Personal permission'                   => '个人权限',
  'Old password'                          => '原密码',
  'New password'                          => '新密码',
  'Confirm password'                      => '确认密码',
  'Saving'                                => '正在保存 ...',
  'Nickname'                              => '昵称',
  'Note'                                  => '备注',
  'enable'                                => '启用', //生效
  'disabled'                              => '禁用', //禁用
  'normal'                                => '普通管理员',
  'super'                                 => '超级管理员',
  'Update administrator successfully'     => '更新管理员成功',
  'Did not make any changes'              => '未做任何修改',
  'Not allowed to edit'                   => '禁止修改',
  'Form token is incorrect'               => '表单令牌错误',
  'You do not have permission'            => '您没有权限',
  'Password is incorrect'                 => '密码错误',
  'Update password successfully'          => '更新密码成功',
  'Change mailbox successfully'           => '更换邮箱成功',
  'Security question'                     => '密保问题',
  'Security answer'                       => '密保答案',
  'Frequently security questions'         => '常用密保问题',
  'Old mailbox'                           => '原邮箱',
  'New mailbox'                           => '新邮箱',
  'Mailbox already exists'                => '邮箱已存在',
  'Update security question successfully' => '更新密保问题成功',
  '{:attr} require'                       => '{:attr} 是必需的',
  'Max size of {:attr} must be {:rule}'   => '{:attr} 最长 {:rule}',
  '{:attr} out of accord with {:confirm}' => '{:attr} 与 {:confirm} 不一致',
  'Size of {:attr} must be {:rule}'       => '{:attr} 的长度必须在 {:rule} 之间',
  '{:attr} not a valid email address'     => '{:attr} 必须为 Email 地址',
  'Send verification email failed'        => '发送确认邮件失败',
  'A verification email has been sent to your new mailbox, please verify.' => '已将验证邮件发送至您的新邮箱，请验证。',
  'What is your grandmother&rsquo;s name?'                    => '您祖母叫什么名字？',
  'What is your grandfather&rsquo;s name?'                    => '您祖父叫什么名字？',
  'When is your birthday?'                                    => '您的生日是什么时候？',
  'What is your mother&rsquo;s name?'                         => '您母亲叫什么名字？',
  'What is your father&rsquo;s name?'                         => '您父亲叫什么名字？',
  'What is your license plate number?'                        => '您的车牌号是什么？',
  'Where is your hometown?'                                   => '您的家乡是哪里？',
  'What is your pet&rsquo;s name?'                            => '您的宠物叫什么名字？',
  'What is your primary school&rsquo;s name?'                 => '您小学的校名叫什么？',
  'What is your favorite color?'                              => '您最喜欢的颜色？',
  'What is your daughter&rsquo;s or son&rsquo;s nickname?'    => '您女儿或者儿子的小名叫什么？',
  'Who is your best friend when you were a child?'            => '谁是您儿时最好的伙伴？',
  'What is the name of your most respected teacher?'          => '您最尊敬的老师的名字？',
);
