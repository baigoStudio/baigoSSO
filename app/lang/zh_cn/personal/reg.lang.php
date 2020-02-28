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
    'Register'                              => '注册',
    'Username'                              => '用户名', //用户名
    'Password'                              => '密码', //密码
    'Confirm password'                      => '确认密码',
    'Mailbox'                               => '邮箱',
    'Input error'                           => '输入错误，请检查！',
    'Token'                                 => '表单令牌',
    'Captcha'                               => '验证码',
    'Captcha is incorrect'                  => '验证码错误',
    'Close'                                 => '关闭',
    'Refresh'                               => '换一个',
    'Apply'                                 => '提交',
    'User not found'                        => '用户不存在',
    'User is disabled'                      => '用户被禁用',
    'Forgot password'                       => '忘记密码',
    'Form token is incorrect'               => '表单令牌错误',
    'User already exists'                   => '用户已存在',
    'Mailbox already exists'                => '邮箱地址已存在',
    '{:attr} require'                       => '{:attr} 是必需的',
    'Size of {:attr} must be {:rule}'       => '{:attr} 的长度必须在 {:rule} 之间',
    '{:attr} out of accord with {:confirm}' => '{:attr} 与 {:confirm} 不一致',
    '{:attr} must be alpha-numeric'         => '{:attr} 必须为字母和数字',
    '{:attr} not a valid email address'     => '{:attr} 必须为 Email 地址',
    '{:attr} must be alpha-numeric, dash, underscore'       => '{:attr} 必须为字母、数字、连接符和下划线',

    'Registration successfully'     => '注册成功',
    'Registration failed'           => '注册失败',

    'Send confirmation email failed'        => '发送确认邮件失败',
    'Not receiving confirmation emails'     => '没有收到确认邮件',
    'Repeat activation is unnecessary'      => '无需重复激活',
    'A verification email has been sent to your mailbox, please verify.' => '已将验证邮件发送至您的邮箱，请验证。',
);
