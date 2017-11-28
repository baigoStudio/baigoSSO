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
    'page' => array(
        'console'   => '管理后台',
        'gening'    => '正在生成',
        'rcode'     => '提示信息',

        'opt'       => '系统设置', //系统设置
        'dbconfig'  => '数据库设置',
        'chkver'    => '检查更新',
    ),

    /*------说明文字------*/
    'label' => array(
        'month'         => '月', //月
        'submitting'    => '正在提交 ...',
    ),

    'pm' => array(
        'in'    => '收件箱',
        'out'   => '已发送',
        'wait'  => '未读',
        'read'  => '已读',
    ),

    'profile' => array(
        'info'      => array(
            'icon'  => 'user',
            'title' => '个人资料',
        ),
        /*'prefer'    => array(
            'icon'  => 'wrench',
            'title' => '偏好设置',
        ),*/
        'pass'      => array(
            'icon'  => 'lock',
            'title' => '密码',
        ),
        'qa'        => array(
            'icon'  => 'question-sign',
            'title' => '密保问题',
        ),
        'mailbox'   => array(
            'icon'  => 'inbox',
            'title' => '更换邮箱',
        ),
    ),

    /*------链接------*/
    'href' => array(
        'logout'        => '退出', //退出
        'back'          => '返回',

        'pm'            => '消息',
        'pmNew'         => '新消息',

        'pageFirst'     => '首页', //首页
        'pagePrevList'  => '上十页', //上十页
        'pagePrev'      => '上页', //上一页
        'pageNext'      => '下页', //下一页
        'pageNextList'  => '下十页', //下十页
        'pageLast'      => '末页', //尾页
    ),

    'date' => array('日', '一', '二', '三', '四', '五', '六', '七', '八', '九', '十'),

    /*------按钮------*/
    'btn' => array(
        'ok'            => '确定', //确定
        'close'         => '关闭',
        'genOverall'    => '生成静态页面',
    ),

    'text' => array(
        'x030403' => '<h4>如需重新安装，请执行如下步骤：</h4>
            <ol>
                <li>删除 ./config/installed.php 文件</li>
                <li>重新运行 <a href="' . BG_URL_INSTALL . 'index.php">' . BG_URL_INSTALL . 'index.php</a></li>
            </ol>',

        'x030404' => '<h4>数据库未正确设置：</h4>
            <ol>
                <li><a href="' . BG_URL_INSTALL . 'index.php?mod=setup&act=dbconfig">返回重新设置</a></li>
            </ol>',

        'x030412' => '<h4>数据库未正确设置：</h4>
            <ol>
                <li><a href="' . BG_URL_INSTALL . 'index.php?mod=upgrade&act=dbconfig">返回重新设置</a></li>
            </ol>',

        'x030413' => '<h4>未通过服务器环境检查，安装无法继续：</h4>
            <ol>
                <li>重新检查环境 <a href="' . BG_URL_INSTALL . 'index.php">' . BG_URL_INSTALL . 'index.php</a></li>
                <li>根据检查结果，正确安装所必需的 PHP 扩展库。</li>
            </ol>',

        'x030414' => '<h4>未通过服务器环境检查，升级无法继续：</h4>
            <ol>
                <li>重新检查环境 <a href="' . BG_URL_INSTALL . 'index.php?mod=upgrade">' . BG_URL_INSTALL . 'index.php?mod=upgrade</a></li>
                <li>根据检查结果，正确安装所必需的 PHP 扩展库。</li>
            </ol>',
    ),
);
