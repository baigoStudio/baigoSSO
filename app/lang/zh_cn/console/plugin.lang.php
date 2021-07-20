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
    'Access denied'     => '拒绝访问',
    'Back'              => '返回',
    'Edit'              => '编辑', //编辑
    'Show'              => '查看',
    'Reset'             => '清除',
    'Cancel'            => '取消',
    'Confirm'           => '确定',
    'Input error'       => '输入错误，请检查！',
    'Plugin'            => '插件',
    'Missing ID'        => '无法获取 ID',
    'Plugin not found'  => '插件不存在',
    'Installable'       => '可安装',
    'Token'             => '表单令牌',
    'Keyword'          => '关键词',
    'All'               => '全部',
    'Close'             => '关闭',
    'OK'                => '确定',
    'Save'              => '保存',
    'Server side error' => '服务器错误',
    'Bulk actions'      => '批量操作',
    'Submitting'        => '正在提交 ...',
    'Saving'            => '正在保存 ...',
    'Type'              => '类型',
    'Status'            => '状态',
    'All status'        => '所有状态',
    'Install'           => '安装',
    'Uninstall'         => '卸载',
    'Directory'         => '目录',
    'Option'            => '选项',
    'wait'              => '等待安装',
    'error'             => '错误',
    'enable'            => '启用', //生效
    'disabled'          => '禁用', //禁用
    'Detail'            => '详情',
    'Class'             => '类名',
    'Version'           => '版本',
    'Please select'     => '请选择',
    'Name'                                      => '名称',
    'Note'                                      => '备注',
    'Author'                                    => '作者',
    'Action'                                    => '操作',
    'Missing directory param'                   => '无法获取目录名',
    'Configuration file error'                  => '配置文件错误',
    'Missing required files'                    => '必要文件丢失',
    'Install plugin successfully'               => '安装插件成功',
    'Install plugin failed'                     => '安装插件失败',
    'Update plugin successfully'                => '更新插件成功',
    'Successfully updated {:count} plugins'     => '成功更新 {:count} 个插件',
    'Did not make any changes'                  => '未做任何修改',
    'Successfully uninstalled {:count} plugins' => '成功卸载 {:count} 个插件',
    'No plugin have been uninstalled'           => '未卸载任何插件',
    'Plugin already installed'                  => '插件已安装',
    'Please select'                             => '请选择',
    'Apply'                                     => '提交',
    'There are no options to set'               => '没有可供设置的选项',
    'Form token is incorrect'                          => '表单令牌错误',
    'Choose at least one item'                  => '至少选择一项',
    'Choose at least one {:attr}'               => '至少选择一项 {:attr}',
    'Are you sure to uninstall?'                => '确认卸载吗？此操作不可恢复',
    'You do not have permission'                => '您没有权限',
    '{:attr} require'                           => '{:attr} 是必需的',
    'Size of {:attr} must be {:rule}'           => '{:attr} 的长度必须在 {:rule} 之间',
    'Max size of {:attr} must be {:rule}'       => '{:attr} 最长 {:rule}',
    '{:attr} must be integer'                   => '{:attr} 必须为整数',
    '{:attr} not a valid url'                   => '{:attr} 格式不合法',
);
