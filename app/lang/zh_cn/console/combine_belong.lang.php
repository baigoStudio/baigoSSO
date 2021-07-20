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
    'All'                                   => '全部',
    'Token'                                 => '表单令牌',
    'Close'                                 => '关闭',
    'Keyword'                               => '关键词',
    'Back'                                  => '返回',
    'Show'                                  => '查看',
    'Apps'                                  => '应用',
    'Input error'                           => '输入错误，请检查！',
    'Missing ID'                            => '无法获取 ID',
    'Reset'                                 => '清除',
    'Status'                                => '状态',
    'Unknown'                               => '未知',
    'Nickname'                              => '昵称',
    'Note'                                  => '备注',
    'enable'                                => '启用',
    'disabled'                              => '禁用',
    'Cancel'                                => '取消',
    'Confirm'                               => '确定',
    'Edit'                                  => '编辑',
    'Choose'                                => '选择',
    'Choose Apps'                           => '选择应用',
    'Chosen Apps'                           => '已选择应用',
    'Waiting for choose'                    => '待选择应用',
    'Remove'                                => '移除',
    'Action'                                => '操作',
    'Submitting'                            => '正在提交 ...',
    'Bulk actions'                          => '批量操作',
    'Form token is incorrect'               => '表单令牌错误',
    'All status'                            => '所有状态',
    'Sync combine'                          => '同步组',
    'Combine name'                          => '组名称',
    'Combine not found'                     => '组不存在',
    'Choose at least one item'              => '至少选择一项',
    'Choose at least one {:attr}'           => '至少选择一项 {:attr}',
    'App'                                   => '应用',
    'Are you sure to choose?'               => '确认选择吗？',
    'Are you sure to remove?'               => '确认移除吗？',
    'You do not have permission'            => '您没有权限',
    '{:attr} require'                       => '{:attr} 是必需的',
    '{:attr} must be integer'               => '{:attr} 必须是整数',
    'Successfully chosen {:count} Apps'     => '成功授权 {:count} 个应用',
    'Successfully removed {:count} Apps'    => '成功移除 {:count} 个应用',
    'Did not make any changes'              => '未做任何修改',
);
