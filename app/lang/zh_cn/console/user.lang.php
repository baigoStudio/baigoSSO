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
  'Access denied'                                         => '拒绝访问',
  'Add'                                                   => '添加', //添加
  'Back'                                                  => '返回',
  'Edit'                                                  => '编辑', //编辑
  'Show'                                                  => '查看',
  'Reset'                                                 => '清除',
  'Server side error'                                     => '服务器错误',
  'Contact'                                               => '联系方式',
  'Extend'                                                => '扩展字段',
  'Name'                                                  => '名称',
  'Content'                                               => '内容',
  'Add'                                                   => '增加',
  'Username'                                              => '用户名', //用户名
  'Password'                                              => '密码', //密码
  'Input error'                                           => '输入错误，请检查！',
  'User'                                                  => '用户',
  'Missing ID'                                            => '无法获取 ID',
  'User not found'                                        => '用户不存在',
  'Keyword'                                               => '关键词',
  'Token'                                                 => '表单令牌',
  'Status'                                                => '状态',
  'Type'                                                  => '类型',
  'Save'                                                  => '保存',
  'All status'                                            => '所有状态',
  'Nickname'                                              => '昵称',
  'Email'                                                 => '邮箱地址',
  'Note'                                                  => '备注',
  'Action'                                                => '操作',
  'enable'                                                => '启用', //生效
  'disabled'                                              => '禁用', //禁用
  'wait'                                                  => '待激活', //待激活
  'Coding'                                                => '编码',
  'Preview'                                               => '预览',
  'Description'                                           => '描述',
  'Unknown'                                               => '未知',
  'Submitting'                                            => '正在提交 ...',
  'Saving'                                                => '正在保存 ...',
  'App'                                           => '应用',
  'Authorized App'                                => '已授权应用',
  'Registered from App'                           => '注册自应用',
  'Add user successfully'                                 => '添加用户成功',
  'Add user failed'                                       => '添加用户失败',
  'Update user successfully'                              => '更新用户成功',
  'Successfully updated {:count} users'                   => '成功更新 {:count} 个用户',
  'Did not make any changes'                              => '未做任何修改',
  'Delete'                                                => '删除',
  'Successfully deleted {:count} users'                   => '成功删除 {:count} 个用户',
  'No user have been deleted'                             => '未删除任何用户',
  'Apply'                                                 => '提交',
  'Bulk actions'                                          => '批量操作',
  'Form token is incorrect'                               => '表单令牌错误',
  'Choose at least one item'                              => '至少选择一项',
  'Choose at least one {:attr}'                           => '至少选择一项 {:attr}',
  'Are you sure to delete?'                               => '确认删除吗？此操作不可恢复',
  'User already exists'                                   => '用户已存在',
  'You do not have permission'                            => '您没有权限',
  'Enter only when you need to modify'                    => '仅在需要修改时输入',
  '{:attr} require'                                       => '{:attr} 是必需的',
  'Size of {:attr} must be {:rule}'                       => '{:attr} 的长度必须在 {:rule} 之间',
  '{:attr} must be alpha-numeric, dash, underscore'       => '{:attr} 必须为字母、数字、连接符和下划线',
  'Max size of {:attr} must be {:rule}'                   => '{:attr} 最长 {:rule}',
  '{:attr} must be integer'                               => '{:attr} 必须为整数',
  '{:attr} not a valid email address'                     => '{:attr} 必须为 Email 地址',
);
