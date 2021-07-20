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
    'Access denied'                                     => '拒绝访问',
    'Upgrading'                                         => '正在升级',
    'To'                                                => '至',
    'Administrator'                                     => '管理员',
    'User not found, please use add administrator' => '用户不存在，请使用添加管理员',
    'User already exists, please use authorization as administrator' => '用户已存在，请使用授权为管理员',
    'Administrator already exists'                      => '管理员已存在',
    'Add'                                               => '添加',
    'Add administrator'                                 => '添加管理员',
    'Authorization'                                     => '授权',
    'Authorize as administrator'                        => '授权为管理员',

    'Add administrator successfully'                    => '添加管理员成功',
    'Add administrator failed'                          => '添加管理员失败',

    'Status'                            => '状态',
    'Submitting'                        => '正在提交',
    'Updating'                          => '正在更新',
    'Update data'                       => '升级数据',
    'Create table'                      => '创建数据表',
    'Update table'                      => '升级数据表',
    'Create view'                       => '创建视图',

    'Create table successfully'         => '创建成功',
    'Create table failed'               => '创建失败',

    'Update table successfully'         => '升级成功',
    'Update table failed'               => '升级失败',
    'No need to update table'           => '无须升级',

    'Create view successfully'                     => '创建成功',
    'Create view failed'                           => '创建失败',

    'Close'                                             => '关闭',
    'OK'                                                => '确定',
    'Complete'                                          => '完成',
    'Complete upgrade'                                  => '完成升级',
    'Confirm password'                                  => '确认密码',
    'Nickname'                                          => '昵称',
    'Email'                                             => '邮箱地址',
    'Form token is incorrect'                                  => '表单令牌错误',
    'Installed'                                         => '已安装',
    'Not installed'                                     => '未安装',
    'Input error'                                       => '输入错误，请检查！',
    'Next'                                              => '下一步',
    'On'                                                => '开启',
    'Off'                                               => '关闭',
    'Prefix'                                            => '数据表前缀',
    'Previous'                                          => '上一步',
    'Username'                                          => '用户名',
    'Password'                                          => '密码',
    'Submitting'                                        => '正在提交 ...',
    'PHP Extensions'                                    => '服务器环境检查',
    'Redirecting'                                       => '正在跳转 ...',
    'Save'                                              => '保存',
    'Upgrader'                                          => '升级程序',
    'Skip'                                              => '跳过',
    'Token'                                             => '表单令牌',

    'Installation successful'                           => '升级成功',
    'Installation failed'                               => '升级失败',

    'Do not add a slash <kbd>/</kbd> at the end'        => '末尾请勿加斜杠 <kbd>/</kbd>',

    '{:attr} require'                                   => '{:attr} 是必需的',
    '{:attr} must be numeric'                           => '{:attr} 必须为数字',
    '{:attr} must be alpha-numeric, dash, underscore'   => '{:attr} 必须为字母、数字、连接符和下划线',
    '{:attr} not a valid email address'                 => '{:attr} 不是一个合法的 Email 地址',
    'Size of {:attr} must be {:rule}'                   => '{:attr} 的长度必须在 {:rule} 之间',
    'Max size of {:attr} must be {:rule}'               => '{:attr} 最长 {:rule}',
    '{:attr} out of accord with {:confirm}'             => '{:attr} 和 {:confirm} 不一致',

    'Warning! Please backup the data before upgrading.' => '警告！请在升级之前备份数据。',
    'This step will add a new user as a super administrator with all permissions.' => '此步骤将注册新用户作为超级管理员，拥有所有权限。',
    'This step will authorizes an existing user as a super administrator with all permissions.' => '此步骤将已存在的用户授权为超级管理员，拥有所有权限。',

    'Last step, complete the upgrade'  => '还差最后一步，完成升级',
);
