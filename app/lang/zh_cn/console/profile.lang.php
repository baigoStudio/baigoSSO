<?php
/*-----------------------------------------------------------------
！！！！警告！！！！
以下为系统文件，请勿修改
-----------------------------------------------------------------*/

//不能非法包含或直接执行
defined('IN_GINKGO') or exit('Access denied');

/*-------------------------通用-------------------------*/
return array(
    'Access denied'                         => '拒绝访问',
    'Username'                              => '用户名', //用户名
    'Password'                              => '密码', //密码
    'Cancel'                                => '取消',
    'Confirm'                               => '确定',
    'Input error'                           => '输入错误，请检查！',
    'Token'                                 => '表单令牌',
    'Close'                                 => '关闭',
    'OK'                                    => '确定',
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
    'A verification email has been sent to your new mailbox, please verify.' => '已将验证邮件发送至您的新邮箱，请验证。',
);
