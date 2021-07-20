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
    'Back'                                                  => '返回',
    'Username'                                              => '用户名', //用户名
    'Password'                                              => '密码', //密码
    'Input error'                                           => '输入错误，请检查！',
    'Close'                                                 => '关闭',
    'OK'                                                    => '确定',
    'Cancel'                                                => '取消',
    'Confirm'                                               => '确定',
    'Nickname'                                              => '昵称',
    'Email'                                                 => '邮箱地址',
    'Note'                                                  => '备注',
    'Action'                                                => '操作', //生效
    'Coding'                                                => '编码',
    'More'                                                  => '更多',
    'Submitting'                                            => '正在提交 ...',
    'Loading'                                               => '载入中 ...',
    'Preview'                                               => '预览',
    'Description'                                           => '描述',
    'Token'                                                 => '表单令牌',
    'Server side error'                                     => '服务器错误',
    'Upload CSV file'                                       => '上传 CSV 文件 ...',
    'Delete CSV file'                                       => '删除 CSV 文件',
    'MD5 Encryption tool'                                   => 'MD5 加密文件',
    'CSV file charset encoding'                             => 'CSV 文件编码',
    'Common charset'                                        => '常用字符集',
    'Charset encoding'                                      => '字符编码',
    'Charset encoding help'                                 => '编码帮助',
    'Encryption'                                            => '加密',
    'Encryption result'                                     => '加密结果',
    'Upload CSV files only'                                 => '只能上传 CSV 文件',
    'CSV file uploaded successfully'                        => 'CSV 文件上传成功',
    'Delete CSV file successfully'                          => '删除 CSV 文件成功',
    'No file have been deleted'                             => '未删除任何文件',
    'Apply'                                                 => '提交',
    'Source'                                                => '原始数据',
    'Import'                                                => '导入',
    'Import as'                                             => '导入为',
    'Successfully import {:count} users'                    => '成功导入 {:count} 个用户',
    'No data imported'                                      => '未导入任何数据',
    'Ignore'                                                => '忽略',
    'Form token is incorrect'                               => '表单令牌错误',
    'Are you sure to delete?'                               => '确认删除吗？此操作不可恢复',
    'You do not have permission'                            => '您没有权限',
    'Username is a required item'                           => '用户名为必选项目',
    'Password is a required item'                           => '密码为必选项目',
    '{:attr} require'                                       => '{:attr} 是必需的',
    'Size of {:attr} must be {:rule}'                       => '{:attr} 的长度必须在 {:rule} 之间',
    '{:attr} must be alpha-numeric, dash, underscore'       => '{:attr} 必须为字母、数字、连接符和下划线',
    'Uploading requires HTML5 support, please upgrade your browser' => '上传需要 HTML5 支持，请升级您的浏览器',
    'The first line of the CSV file must be a field name. It is recommended to use three columns. The password column must be encrypted with MD5. For the encryption tool, please see the next item. After uploading the CSV file, please refresh this page to preview, click here <a href="javascript:location.reload();" class="alert-link">Refresh</a>. After the import is successful, it is highly recommended to delete the CSV file.' => 'CSV 文件第一行必须为字段名，建议使用三列，其中密码列必须使用 MD5 加密，加密工具请看下方表单。上传 CSV 文件后，请刷新本页查看预览，点此 <a href="javascript:location.reload();" class="alert-link">刷新</a>。导入成功以后，强烈建议删除 CSV 文件。',
);
