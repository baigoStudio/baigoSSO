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
    /*------链接文字------*/
    'href' => array(
        'help'          => '帮助',
    ),

    'label' => array(
        'tpl'           => '模板',
        'timezone'      => '时区',
        'dbHost'            => '数据库服务器',
        'dbPort'            => '服务器端口',
        'dbName'            => '数据库名称',
        'dbUser'            => '用户名',
        'dbPass'            => '密码',
        'dbCharset'         => '字符编码',
        'dbtable'           => '数据表前缀',
        'installVer'        => '当前安装版本',
        'installTime'       => '安装（升级）时间',
        'pubTime'           => '发布时间',
        'latestVer'         => '最新版本',
        'announcement'      => '公告',
        'downloadUrl'       => '下载地址',
        'description'       => '描述',
    ),

    'btn' => array(
        'save'      => '保存',
        'chkver'    => '再次检查更新',
    ),

    'text' => array(
        'haveNewVer'      => '您的版本不是最新的，下面是最新版本的发布和更新帮助链接。',
        'isNewVer'        => '恭喜！您的版本是最新的！',
    ),
);
