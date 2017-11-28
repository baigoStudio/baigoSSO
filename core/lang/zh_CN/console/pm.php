<?php
/*-----------------------------------------------------------------
！！！！警告！！！！
以下为系统文件，请勿修改
-----------------------------------------------------------------*/

//不能非法包含或直接执行
if (!defined('IN_BAIGO')) {
    exit('Access Denied');
}

/*-------------------------通用-------------------------*/
return array(
    /*------页面标题------*/
    'page'             => array(
        'show'              => '查看',
    ),

    /*------链接文字------*/
    'href'             => array(
        'all'             => '全部',
        'help'            => '帮助',
        'pmSend'          => '发短信',
        'pmBulk'          => '群发',
        'show'            => '查看',
    ),

    /*------说明文字------*/
    'label'            => array(
        'id'            => 'ID',
        'all'           => '全部',
        'key'           => '关键词',
        'status'        => '状态',
        'title'         => '标题',
        'content'       => '内容',
        'unknow'        => '未知',
        'time'          => '时间',
        'type'          => '类型',
        'to'            => '至',

        'timeLogin'         => '登录时间',
        'timeReg'           => '注册时间',

        'pm'                => '站内短信',
        'pmFrom'            => '发件人',
        'pmTo'              => '收件人',
        'pmSys'             => '系统短信',
        'pmBulkType'        => '群发方式',

        'toNote'            => '多个收件人请用 <kbd>|</kbd> 分隔',
        'keyNameNote'       => '发送给用户名中包含该关键词的用户',
        'keyMailNote'       => '发送给邮箱中包含该关键词的用户',
        'rangeIdNote'       => '发送给 ID 范围内的用户',
        'rangeTimeNote'     => '发送给注册时间范围内的用户',
        'rangeLoginNote'    => '发送给登录时间范围内的用户',
    ),

    'status' => array(
        'wait'    => '未读', //生效
        'read'    => '已读', //禁用
    ),

    'type' => array(
        'out'   => '已发送',
        'in'    => '收件箱',
    ),

    /*------选择项------*/
    'option'           => array(
        'allStatus'         => '所有状态',
        'allType'           => '所有类型',
        'batch'             => '批量操作',
        'del'               => '永久删除',
        'bulkUsers'         => '输入用户名',
        'bulkKeyName'       => '用户名关键词',
        'bulkKeyMail'       => '邮箱关键词',
        'bulkRangeId'       => '用户 ID 范围',
        'bulkRangeTime'     => '注册时间范围',
        'bulkRangeLogin'    => '登录时间范围',
    ),

    /*------按钮------*/
    'btn' => array(
        'search'    => '搜索',
        'send'      => '发送',
        'submit'    => '提交',
    ),

    /*------确认框------*/
    'confirm'          => array(
        'del'             => '确认永久删除吗？此操作不可恢复！',
    ),
);
