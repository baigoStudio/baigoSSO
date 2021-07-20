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
    'Access denied'                                                     => '拒绝访问',
    'Add'                                                               => '添加', //添加
    'Back'                                                              => '返回',
    'Edit'                                                              => '编辑', //编辑
    'Show'                                                              => '查看',
    'Reset'                                                             => '清除',
    'Username'                                                          => '用户名', //用户名
    'Password'                                                          => '密码', //密码
    'Cancel'                                                            => '取消',
    'Confirm'                                                           => '确定',
    'Input error'                                                       => '输入错误，请检查！',
    'Administrator'                                                     => '管理员',
    'Authorization'                                                     => '授权',
    'Missing ID'                                                        => '无法获取 ID',
    'Administrator not found'                                           => '管理员不存在',
    'Token'                                                             => '表单令牌',
    'Keyword'                                                          => '关键词',
    'All'                                                               => '全部',
    'Close'                                                             => '关闭',
    'OK'                                                                => '确定',
    'Status'                                                            => '状态',
    'Type'                                                              => '类型',
    'Save'                                                              => '保存',
    'Server side error'                                                 => '服务器错误',
    'Permission'                                                        => '权限',
    'Personal permission'                                               => '个人权限',
    'Submitting'                                                        => '正在提交 ...',
    'Saving'                                                            => '正在保存 ...',
    'All status'                                                        => '所有状态',
    'All types'                                                         => '所有类型',
    'Nickname'                                                          => '昵称',
    'Note'                                                              => '备注',
    'Action'                                                            => '操作', //生效
    'enable'                                                            => '启用', //生效
    'disabled'                                                          => '禁用', //禁用
    'normal'                                                            => '普通管理员',
    'super'                                                             => '超级管理员',
    'Add administrator successfully'                                    => '添加管理员成功',
    'Add administrator failed'                                          => '添加管理员失败',
    'Update administrator successfully'                                 => '更新管理员成功',
    'Successfully updated {:count} administrators'                      => '成功更新 {:count} 个管理员',
    'Prohibit editing yourself'                                         => '不能编辑自己',
    'Did not make any changes'                                          => '未做任何修改',
    'Delete'                                                            => '删除',
    'Successfully deleted {:count} administrators'                      => '成功删除 {:count} 个管理员',
    'No administrator have been deleted'                                => '未删除任何管理员',
    'Not allowed to edit'                                               => '禁止修改',
    'Apply'                                                             => '提交',
    'Bulk actions'                                                      => '批量操作',
    'Form token is incorrect'                                           => '表单令牌错误',
    'Choose at least one item'                                          => '至少选择一项',
    'Choose at least one {:attr}'                                       => '至少选择一项 {:attr}',
    'Are you sure to delete?'                                           => '确认删除吗？此操作不可恢复',
    'Administrator already exists'                                      => '管理员已存在',
    'You do not have permission'                                        => '您没有权限',
    'User already exists, please use authorization as administrator'    => '用户已存在，请使用授权为管理员',
    'User not found, please use add administrator'                      => '用户不存在，请使用添加管理员',
    'User not found'                                                    => '用户不存在',
    'Enter only when you need to modify'                                => '仅在需要修改时输入',
    '{:attr} require'                                                   => '{:attr} 是必需的',
    'Size of {:attr} must be {:rule}'                                   => '{:attr} 的长度必须在 {:rule} 之间',
    '{:attr} must be alpha-numeric, dash, underscore'                   => '{:attr} 必须为字母、数字、连接符和下划线',
    'Max size of {:attr} must be {:rule}'                               => '{:attr} 最长 {:rule}',
    '{:attr} must be integer'                                           => '{:attr} 必须为整数',
);
