<?php
/*-----------------------------------------------------------------
！！！！警告！！！！
以下为系统文件，请勿修改
-----------------------------------------------------------------*/

// 不能非法包含或直接执行
defined('IN_GINKGO') or exit('Access denied');

/*-------------------------通用-------------------------*/
return array(
    'Registration is prohibited'    => '禁止注册',
    'Username'                      => '用户名',
    'Password'                      => '密码',
    'Email'                         => '邮箱地址',
    'Nickname'                      => '昵称',
    'Note'                          => '备注',
    'Contact'                       => '联系方式',
    'Extend'                        => '扩展',
    'Registration successfully'     => '注册成功',
    'Registration failed'           => '注册失败',
    'Username can be registered'    => '用户名可以注册',
    'User already exists'           => '用户已存在',
    'Mailbox can be used'           => '邮箱地址可以使用',
    'Mailbox already exists'        => '邮箱地址已存在',
    '{:attr} require'               => '{:attr} 是必需的',
    'Permission denied'             => '没有权限',
    'Illegal content in username'   => '用户名中含有非法内容',
    'Email is not allowed'          => '邮箱不被允许',
    'Illegal content in mailbox'    => '邮箱中含有非法内容',
    'Send confirmation email failed'                    => '发送确认邮件失败',
    'Size of {:attr} must be {:rule}'                   => '{:attr} 的长度必须在 {:rule} 之间',
    'Max size of {:attr} must be {:rule}'               => '{:attr} 最长 {:rule}',
    '{:attr} must be alpha-numeric, dash, underscore'   => '{:attr} 必须为字母、数字、连接符和下划线',
    '{:attr} must be integer'                           => '{:attr} 必须为数字',
    '{:attr} not a valid email address'                 => '{:attr} 不是一个合法的 Email 地址',
    '{:attr} not a valid ip'                            => '{:attr} 不是一个合法的 IP 地址',
);
