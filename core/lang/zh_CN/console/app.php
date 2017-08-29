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
        'edit'              => '编辑',
        'add'               => '创建',
        'show'              => '查看',
        'belong'            => '授权用户',
    ),

    /*------链接文字------*/
    'href'             => array(
        'back'            => '返回',
        'add'             => '创建',
        'edit'            => '编辑',
        'belong'          => '授权用户',
        'help'            => '帮助',
        'show'            => '查看',
        'notifyTest'      => '通知接口测试',
    ),

    /*------说明文字------*/
    'label'            => array(
        'id'                => 'ID',
        'add'               => '创建',
        'all'               => '全部',
        'key'               => '关键词',
        'status'            => '状态',
        'note'              => '备注',
        'allow'             => '权限',
        'sync'              => '同步通知',
        'user'              => '用户',
        'notifyTest'        => '通知接口测试',

        'appName'           => '应用名称',
        'appId'             => 'APP ID',
        'appKey'            => 'APP KEY 通信密钥',
        'appKeyNote'        => '如果 APP KEY 泄露，可以通过重置更换，原 APP KEY 将作废。',
        'appUrlNotify'      => '通知接口 URL',
        'appUrlSync'        => '同步接口 URL',
        'apiUrl'            => 'API 接口 URL',

        'belongUser'        => '已授权用户',
        'selectUser'        => '待授权用户',

        'ipAllow'           => '允许通信 IP',
        'ipBad'             => '禁止通信 IP',
        'ipNote'            => '每行一个 IP，可使用通配符 <strong>*</strong> （如 192.168.1.*）',
    ),

    'status' => array(
        'enable'  => '启用', //生效
        'disable' => '禁用', //禁用
    ),

    'user' => array(
        'enable'  => '激活', //生效
        'wait'    => '待激活', //待审
        'disable' => '禁用', //禁用
    ),

    'sync' => array(
        'on'  => '打开', //生效
        'off' => '关闭', //禁用
    ),

    /*------选择项------*/
    'option'           => array(
        'allStatus' => '所有状态',
        'batch'     => '批量操作',
        'del'       => '永久删除',
    ),

    /*------按钮------*/
    'btn' => array(
        'ok'        => '确定',
        'submit'    => '提交',
        'save'      => '保存',
        'resetKey'  => '重置 APP KEY',
        'auth'      => '授权',
        'deauth'    => '取消授权',
    ),

    /*------确认框------*/
    'confirm'          => array(
        'del'       => '确认永久删除吗？此操作不可恢复！',
        'deauth'          => '取消授权将使此应用失去对这些用户的编辑权限，确认取消吗？',
        'resetKey'  => '确认重置吗？此操作不可恢复！',
    ),
);
