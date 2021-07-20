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
    'Captcha'                               => '验证码',
    'Captcha is incorrect'                  => '验证码错误',
    'Close'                                 => '关闭',
    'Refresh'                               => '换一个',
    'Apply'                                 => '提交',
    'Missing ID'                            => '无法获取 ID',
    'Unable to get token'                   => '无法获取口令',
    'Token not found'                       => '口令不存在',
    'Token is no longer valid'              => '口令已失效',
    'Token type error'                      => '口令类型错误',
    'Token expired'                         => '口令已过期',
    'Token is incorrect'                    => '口令错误',
    'User is disabled'                      => '用户被禁用',
    'No need to activate again'             => '无需重复激活',
    'Change mailbox'                        => '更换邮箱',
    'Activate user'                         => '激活用户',
    'Activate user successful'              => '用户激活成功',
    'Activate user failed'                  => '用户激活失败',
    'Update password successfully'          => '更新密码成功',
    'Did not make any changes'              => '未做任何修改',
    'Reset password'                        => '重置密码',
    'New password'                          => '新密码',
    'Confirm password'                      => '确认密码',
    'Not allowed to edit'                   => '禁止修改',
    'Form token is incorrect'               => '表单令牌错误',
    'Password is incorrect'                 => '密码错误',
    'Change mailbox successfully'           => '更换邮箱成功',
    'Mailbox'                               => '邮箱',
    'Old mailbox'                           => '原邮箱',
    'New mailbox'                           => '新邮箱',
    '{:attr} require'                       => '{:attr} 是必需的',
    'Size of {:attr} must be {:rule}'       => '{:attr} 的长度必须在 {:rule} 之间',
    '{:attr} must be alpha-numeric'         => '{:attr} 必须为字母和数字',
    '{:attr} out of accord with {:confirm}' => '{:attr} 和 {:confirm} 不一致',
);
