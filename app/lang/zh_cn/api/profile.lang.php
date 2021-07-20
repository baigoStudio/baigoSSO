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
    'Access denied'                         => '禁止访问',
    'User ID'                               => '用户 ID',
    'Password'                              => '密码',
    'Old password'                          => '原密码',
    'New password'                          => '新密码',
    'Nickname'                              => '昵称',
    'Refresh token'                         => '刷新口令',
    'Refresh token expired'                 => '刷新口令过期',
    'Refresh token is incorrect'            => '刷新口令错误',
    'Refresh access token successfully'     => '刷新访问口令成功',
    'Refresh access token failed'           => '刷新访问口令失败',
    'Security question'                     => '密保问题',
    'Security answer'                       => '密保答案',
    'User not found'                        => '用户不存在',
    'User is disabled'                      => '用户被禁用',
    'Password is incorrect'                 => '密码错误',
    'The old password is incorrect'         => '原密码错误',
    'Update password successfully'          => '修改密码成功',
    'Update user successfully'              => '更新用户成功',
    'Update security question successfully' => '修改密保问题成功',
    'Change mailbox successfully'           => '更换邮箱成功',
    'Did not make any changes'              => '未做任何修改',
    '{:attr} require'                       => '{:attr} 是必需的',
    'Max size of {:attr} must be {:rule}'   => '{:attr} 最长 {:rule}',
    '{:attr} not a valid email address'     => '{:attr} 必须为 Email 地址',
    'Size of {:attr} must be {:rule}'       => '{:attr} 的长度必须在 {:rule} 之间',
    'Send verification email failed'        => '发送确认邮件失败',
    'A verification email has been sent to your new mailbox, please verify.' => '已将验证邮件发送至您的新邮箱，请验证。',
);
