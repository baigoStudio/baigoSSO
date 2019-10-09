<?php
/*-----------------------------------------------------------------
！！！！警告！！！！
以下为系统文件，请勿修改
-----------------------------------------------------------------*/

//不能非法包含或直接执行
defined('IN_GINKGO') or exit('Access denied');

/*-------------------------通用-------------------------*/
return array(
    'Access token'                              => '访问口令',
    'read'                                      => '已读',
    'wait'                                      => '未读',
    'out'                                       => '已发送',
    'in'                                        => '收件箱',
    'Sender'                                    => '发件人',
    'Recipient'                                 => '收件人',
    'Content'                                   => '内容',
    'Message ID'                                => '短信 ID',
    'Message not found'                         => '短信不存在',
    'User ID, Username or Email'                => '用户 ID, 用户名 或 Email',
    'User not found'                            => '用户不存在',
    'User is disabled'                          => '用户被禁用',
    'Access token expired'                      => '访问口令过期',
    'Access token is incorrect'                 => '访问口令错误',
    'Message type'                              => '消息类型',
    'Message status'                            => '消息状态',
    '{:attr} require'                           => '{:attr} 是必需的',
    '{:attr} must be integer'                   => '{:attr} 是必须是数字',
    'Size of {:attr} must be {:rule}'           => '{:attr} 的长度必须在 {:rule} 之间',
    'Max size of {:attr} must be {:rule}'       => '{:attr} 最长 {:rule}',
    'Did not make any changes'                  => '未做任何修改',
    'Message does not belong to you'            => '短信不属于您',
    'No message have been revoked'              => '未撤回任何短信',
    'No message have been deleted'              => '未删除任何短信',
    'Successfully revoked {:count} messages'    => '成功撤回 {:count} 条短信',
    'Successfully deleted {:count} messages'    => '成功删除 {:count} 条短信',
    'Successfully updated {:count} messages'    => '成功更新 {:count} 条短信',
    'Successfully sent {:count} messages'       => '成功发送 {:count} 条短信',
    'Send message failed'                       => '发送短信失败',
);
