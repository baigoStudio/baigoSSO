<?php
/*-----------------------------------------------------------------
！！！！警告！！！！
以下为系统文件，请勿修改
-----------------------------------------------------------------*/

// 不能非法包含或直接执行
defined('IN_GINKGO') or exit('Access denied');

/*-------------------------通用-------------------------*/
return array(
    'User ID, Username or Email'    => '用户 ID, 用户名 或 Email',
    'Username'                      => '用户名',
    'Password'                      => '密码',
    'Email'                         => '邮箱地址',
    'Nickname'                      => '昵称',
    'IP Address'                    => 'IP 地址',
    'Note'                          => '备注',
    'Contact'                       => '联系方式',
    'Extend'                        => '扩展',
    'Login successful'              => '登录成功',
    'Password is incorrect'         => '密码错误',
    'User not found'                => '用户不存在',
    'Update user successfully'      => '更新用户成功',
    'Did not make any changes'      => '未做任何修改',
    'User is disabled'              => '用户被禁用',
    '{:attr} require'               => '{:attr} 是必需的',
    'Size of {:attr} must be {:rule}'                   => '{:attr} 的长度必须在 {:rule} 之间',
    'Max size of {:attr} must be {:rule}'               => '{:attr} 最长 {:rule}',
    '{:attr} must be alpha-numeric, dash, underscore'   => '{:attr} 必须为字母、数字、连接符和下划线',
    '{:attr} must be integer'                           => '{:attr} 必须为数字',
    '{:attr} not a valid email address'                 => '{:attr} 不是一个合法的 Email 地址',
    '{:attr} not a valid ip'                            => '{:attr} 不是一个合法的 IP 地址',
);
