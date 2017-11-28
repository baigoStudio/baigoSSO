<?php
/*-----------------------------------------------------------------
！！！！警告！！！！
以下为系统文件，请勿编辑
-----------------------------------------------------------------*/

//不能非法包含或直接执行
if (!defined('IN_BAIGO')) {
    exit('Access Denied');
}

return array(
    'page' => array(
        'help'    => 'baigo SSO 帮助',
        'intro'     => '简介',
        'install'   => '安装 / 升级',
        'setup'     => '安装',
        'upgrade'   => '升级',
        'manual'    => '手动安装 / 升级',
        'deploy'    => '高级部署',
        'console'   => '管理后台',
        'doc'       => '开发文档',
        'tpl'       => '模板文档',
        'api'       => 'API 接口文档',
    ),

    'label' => array(
        'desc'  => '描述',
    ),

    'intro'     => array(
        'outline'   => '简介',
        'faq'       => '常见问题',
    ),

    'setup'     => array(
        'outline'     => '安装概述',
        'phplib'      => '服务器环境检查',
        'dbconfig'    => '数据库设置',
        'dbtable'     => '创建数据表',
        'base'        => '基本设置',
        'reg'         => '注册设置',
        'smtp'        => '邮件发送设置',
        'admin'       => '创建管理员',
        'over'        => '完成安装',
    ),

    'upgrade'     => array(
        'outline'     => '升级概述',
        'phplib'      => '服务器环境检查',
        'dbconfig'    => '数据库设置',
        'dbtable'     => '升级数据库',
        'base'        => '基本设置',
        'reg'         => '注册设置',
        'smtp'        => '邮件发送设置',
        'admin'       => '创建管理员',
        'over'        => '完成安装',
    ),

    'manual'     => array(
        'outline'   => '安装 / 升级概述',
        'dbconfig'  => '数据库配置',
        'base'      => '基本配置',
        'reg'       => '注册配置',
        'smtp'      => '邮件发送配置',
    ),

    'deploy'     => array(
        'outline'     => '高级部署',
    ),

    'console'     => array(
        'outline' => '后台概述',
        'user'    => '用户管理',
        'pm'      => '站内短信',
        'app'     => '应用管理',
        'admin'   => '管理员',
        'opt'     => '系统设置',
    ),

    'tpl'     => array(
        'outline'   => '模板概述',
        'common'    => '通用资源',
        'error'     => '提示信息',
        'reg'       => '注册',
        'forgot'    => '忘记密码',
        'profile'   => '个人资料',
    ),

    'api'     => array(
        'outline'       => 'API 接口概述',
        'page'          => '分页',
        'code'          => '密文',
        'signature'     => '签名',
        'user'          => '用户接口',
        'profile'       => '个人接口',
        'forgot'        => '找回密码',
        'pm'            => '站内短信',
        'notify'        => '通知接口',
        'sync'          => '同步接口',
        'sync_notify'   => '同步通知',
        'setup'         => '安装接口',
        'rcode'         => '返回代码',
    ),
);
