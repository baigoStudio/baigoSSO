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
    'page' => array(
        'add'   => '创建',
        'edit'  => '编辑',
    ),

    /*------链接文字------*/
    'href' => array(
        'back'      => '返回',
        'add'       => '创建',
        'edit'      => '编辑',
        'help'      => '帮助',
        'import'    => '批量导入',
    ),

    /*------说明文字------*/
    'label' => array(
        'id'            => 'ID',
        'all'           => '全部',
        'key'           => '关键词',
        'user'          => '用户',
        'status'        => '状态',
        'note'          => '备注',
        'username'      => '用户名',
        'password'      => '密码',
        'nick'          => '昵称',
        'mail'          => '邮箱',
        'source'        => '原始数据',
        'convert'       => '转换为',
        'preview'       => '预览',
        'name'          => '名称',
        'needH5'        => '上传插件需要 HTML5，请升级您的浏览器！',
        'onlyModi'          => '需要修改时输入', //需要修改时输入

        'uploading'     => '正在上传',
        'uploadSucc'    => '上传成功',
        'returnErr'     => '返回错误，非 JSON',
        'md5tool'       => 'MD5 加密工具',
        'md5result'     => '加密结果',
        'charset'       => '字符编码',
        'charsetSrc'    => '原始字符编码',
    ),

    'status' => array(
        'enable'    => '激活', //生效
        'wait'      => '待激活', //待审
        'disable'   => '禁用', //禁用
    ),

    'option' => array(
        'allStatus' => '所有状态',
        'batch'     => '批量操作',
        'del'       => '永久删除',
        'abort'     => '忽略',
    ),

    'btn' => array(
        'submit'    => '提交',
        'save'      => '保存',
        'convert'   => '导入',
        'uploadCsv' => '上传 CSV 文件 ...',
        'delCsv'    => '删除 CSV 文件',
        'md5gen'    => '生成加密结果',
        'charset'   => '查看编码帮助',
    ),

    'text' => array(
        'refreshImport' => 'CSV 文件第一行必须为字段名，建议使用三列，其中密码列必须使用 MD5 加密，加密工具请看下方表单。上传 CSV 文件后，请刷新本页查看预览，点此 <a href=\'javascript:location.reload();\' class=\'alert-link\'>刷新</a>。导入成功以后，强烈建议删除 CSV 文件。',
    ),

    'confirm' => array(
        'del'       => '确认永久删除吗？此操作不可恢复！',
    ),
);
