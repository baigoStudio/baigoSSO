<?php
/*+++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
！！！！警告！！！！
以下为系统文件，请勿修改
+++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++*/

//不能非法包含或直接执行
if (!defined('IN_BAIGO')) {
    exit('Access Denied');
}

/*++++++提示信息++++++
x开头为错误
y开头为成功
++++++++++++++++++*/
return array(
    'page' => array(
        'setup'         => '安装程序',
        'phplib'        => '服务器环境检查',
        'dbtable'       => '创建数据表',
        'admin'         => '创建管理员',
        'auth'          => '授权为管理员', //授权
        'over'          => '完成安装',
    ),

    'href' => array(
        'help'          => '帮助',
        'adminAdd'      => '创建管理员', //授权
        'adminAuth'     => '授权为管理员', //授权
        'jumping'       => '正在跳转',
    ),

    'label' => array(
        'tpl'           => '模板',
        'timezone'      => '时区',
        'dbHost'        => '数据库服务器',
        'dbPort'        => '服务器端口',
        'dbName'        => '数据库名称',
        'dbUser'        => '用户名',
        'dbPass'        => '密码',
        'dbCharset'     => '字符编码',
        'dbtable'       => '数据表前缀',
        'nick'          => '昵称',

        'username'      => '用户名',
        'password'      => '密码',
        'passConfirm'   => '确认密码',

        'over'          => '还差最后一步，完成安装！',
    ),

    'phplib' => array(
        'installed'     => '已安装',
        'notinstalled'  => '未安装',
    ),

    'btn' => array(
        'jump'      => '跳转至',
        'skip'      => '跳过',
        'prev'  => '上一步',
        'next'  => '下一步',
        'save'      => '保存',
        'complete'  => '完成',
    ),

    'text' => array(
        'phplibErr'  => '服务器环境检查未通过，请检查上述扩展库是否已经正确安装。',
        'phplibOk'   => '服务器环境检查通过，可以继续安装。',
        'admin'      => '本操作将向 baigo SSO 注册新用户，并自动将新注册的用户授权为超级管理员，拥有所有的管理权限。如果您不想注册新用户，只希望使用原有的 baigo SSO 用户作为管理员，请点击 <mark>授权为管理员</mark>。',
        'auth'       => '本操作将用您输入的 baigo SSO 用户作为管理员，拥有所有的管理权限。如果您要创建新的管理员请点击 <mark>创建管理员</mark>。',
    ),
);
