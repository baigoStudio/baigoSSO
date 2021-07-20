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
    'Operator'                              => '操作者',
    'Token'                                 => '表单令牌',
    'Close'                                 => '关闭',
    'To'                                    => '至',
    'Cancel'                                => '取消',
    'Confirm'                               => '确定',
    'Back'                                  => '返回',
    'Show'                                  => '查看',
    'Input error'                           => '输入错误，请检查！',
    'Missing ID'                      => '无法获取 ID',
    'Status'                                => '状态',
    'Type'                                  => '类型',
    'Time'                                  => '时间',
    'Unknown'                                => '未知',
    'Apply'                                 => '提交',
    'Submitting'                            => '正在提交 ...',
    'Delete'                                => '删除',
    'Action'                                => '操作',
    'Expiry'                                => '过期时间',
    'Initiate time'                         => '发起时间',
    'Use time'                              => '使用时间',
    'Bulk actions'                          => '批量操作',
    'Form token is incorrect'                      => '表单令牌错误',
    'Choose at least one item'              => '至少选择一项',
    'Choose at least one {:attr}'           => '至少选择一项 {:attr}',
    'Are you sure to delete?'               => '确认删除吗？此操作不可恢复',
    'You do not have permission'            => '您没有权限',
    '{:attr} require'                       => '{:attr} 是必需的',
    'Size of {:attr} must be {:rule}'       => '{:attr} 的长度必须在 {:rule} 之间',
    '{:attr} must be integer'               => '{:attr} 必须是整数',
    '{:attr} not a valid datetime'          => '{:attr} 必须是日期时间格式',
    'Successfully updated {:count} tokens'  => '成功更新 {:count} 条口令',
    'Successfully deleted {:count} tokens'  => '成功删除 {:count} 条口令',
    'No token have been deleted'            => '未删除任何口令',
    'Did not make any changes'              => '未做任何修改',
    'enable'                                => '生效', //生效
    'disabled'                              => '禁用', //禁用
    'expired'                               => '过期',
    'mailbox'                               => '更换邮箱',
    'confirm'                               => '用户激活',
    'forgot'                                => '找回密码',
);
