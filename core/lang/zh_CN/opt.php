<?php
/*-----------------------------------------------------------------
！！！！警告！！！！
以下为系统文件，请勿修改
-----------------------------------------------------------------*/

//不能非法包含或直接执行
if (!defined('IN_BAIGO')) {
    exit('Access Denied');
}

return array(
    'base' => array(
        'title'   => '基本设置',
        'list'    => array(
            'BG_SITE_NAME' => array(
                'label'      => '站点名称',
            ),
            'BG_SITE_DOMAIN' => array(
                'label'      => '域名',
            ),
            'BG_SITE_URL' => array(
                'label'      => '首页 URL ',
                'note'       => '末尾请勿加 <kdb>/</kbd>，仅需 http:// 和域名部分，如：http://' . $_SERVER['SERVER_NAME'],
            ),
            'BG_SITE_PERPAGE' => array(
                'label'      => '每页显示数',
            ),
            'BG_SITE_DATE' => array(
                'label'  => '日期格式',
            ),
            'BG_SITE_DATESHORT' => array(
                'label'  => '短日期格式',
            ),
            'BG_SITE_TIME' => array(
                'label'  => '时间格式',
            ),
            'BG_SITE_TIMESHORT' => array(
                'label'  => '短时间格式',
            ),
            'BG_ACCESS_EXPIRE' => array(
                'label'     => '访问口令存活期',
                'option' => array(
                    10    => '10 分钟',
                    20    => '20 分钟',
                    30    => '30 分钟',
                    40    => '40 分钟',
                    50    => '50 分钟',
                    60    => '60 分钟',
                    70    => '70 分钟',
                    80    => '80 分钟',
                    90    => '90 分钟',
                ),
            ),
            'BG_REFRESH_EXPIRE' => array(
                'label'     => '刷新口令存活期',
                'option' => array(
                    10    => '10 天',
                    20    => '20 天',
                    30    => '30 天',
                    40    => '40 天',
                    50    => '50 天',
                    60    => '60 天',
                ),
            ),
            'BG_VERIFY_EXPIRE' => array(
                'label'     => '验证链接有效期',
                'option' => array(
                    10    => '10 分钟',
                    20    => '20 分钟',
                    30    => '30 分钟',
                    40    => '40 分钟',
                    50    => '50 分钟',
                    60    => '60 分钟',
                    70    => '70 分钟',
                    80    => '80 分钟',
                    90    => '90 分钟',
                ),
                'note'      => '验证邮箱、找回密码时的链接有效时间',
            ),
        ),
    ),
    'reg' => array(
        'title'   => '注册设置',
        'list'    => array(
            'BG_REG_ACC' => array(
                'label'  => '允许注册',
                'option' => array(
                    'enable'    => array(
                        'value'    => '允许'
                    ),
                    'disable'   => array(
                        'value'    => '禁止'
                    ),
                ),
            ),
            'BG_REG_NEEDMAIL' => array(
                'label'  => '强制输入邮箱',
                'option' => array(
                    'on'    => array(
                        'value'    => '开启'
                    ),
                    'off'   => array(
                        'value'    => '关闭'
                    ),
                ),
            ),
            'BG_REG_ONEMAIL' => array(
                'label'  => '允许邮箱重复',
                'option' => array(
                    'true'    => array(
                        'value'    => '是'
                    ),
                    'false'   => array(
                        'value'    => '否'
                    ),
                ),
            ),
            'BG_LOGIN_MAIL' => array(
                'label'  => '使用邮箱登录、读取',
                'option' => array(
                    'on'    => array(
                        'value'    => '开启'
                    ),
                    'off'   => array(
                        'value'    => '关闭'
                    ),
                ),
                'note'      => '此设置将强制输入邮箱并且邮箱不能重复',
            ),
            'BG_REG_CONFIRM' => array(
                'label'  => '验证邮箱激活用户',
                'option' => array(
                    'on'    => array(
                        'value'    => '开启'
                    ),
                    'off'   => array(
                        'value'    => '关闭'
                    ),
                ),
                'note'      => '注册或更换邮箱均需要验证',
            ),
            'BG_ACC_MAIL' => array(
                'label'      => '允许注册的邮箱',
                'note'       => '只填域名部分，每行一个域名，如 @hotmail.com',
            ),
            'BG_BAD_MAIL' => array(
                'label'      => '禁止注册的邮箱',
                'note'       => '只填域名部分，每行一个域名，如 @hotmail.com',
            ),
            'BG_BAD_NAME' => array(
                'label'      => '禁止注册的用户名',
                'note'       => '每行一个用户名，可使用通配符 * 如 *版主*',
            ),
        ),
    ),
    'smtp' => array(
        'title'   => '邮件发送设置',
        'list'    => array(
            'BG_SMTP_HOST' => array(
                'label'      => 'SMTP 服务器',
            ),
            'BG_SMTP_TYPE' => array(
                'label'      => '邮件发送方式',
                'option' => array(
                    'smtp'      => '使用 SMTP 发送（推荐）',
                    'phpmail'   => '使用 PHP 的 Mail 函数发送',
                    'sendmail'  => '使用 Sendmail 发送',
                    'qmail'     => '使用 Qmail 发送',
                ),
            ),
            'BG_SMTP_SEC' => array(
                'label'      => '启用加密',
                'option' => array(
                    'off'   => array(
                        'value' => '不启用',
                    ),
                    'tls'   => array(
                        'value' => 'TLS 协议',
                        'note' => '需启用相关 TLS 协议。'
                    ),
                    'ssl'   => array(
                        'value' => 'SSL 协议',
                        'note' => '需启用相关 SSL 协议，如：OpenSSL 等。'
                    ),
                ),
            ),
            'BG_SMTP_PORT' => array(
                'label'      => '服务器端口',
            ),
            'BG_SMTP_AUTH' => array(
                'label'  => '服务器是否需要验证',
                'option' => array(
                    'true'    => array(
                        'value'    => '是'
                    ),
                    'false'   => array(
                        'value'    => '否'
                    ),
                ),
            ),

            'BG_SMTP_AUTHTYPE' => array(
                'label'  => '验证方式',
                'option' => array(
                    'login'   => array(
                        'value'    => 'LOGIN'
                    ),
                    'plain'   => array(
                        'value'    => 'PLAIN'
                    ),
                    'cram-md5'    => array(
                        'value'    => 'CRAM-MD5'
                    ),
                    'xoauth2'   => array(
                        'value'    => 'XOAUTH2'
                    ),
                ),
            ),

            'BG_SMTP_USER' => array(
                'label'      => '用户名',
            ),
            'BG_SMTP_PASS' => array(
                'label'      => '密码',
            ),
            'BG_SMTP_FROM' => array(
                'label'      => '发送邮箱',
            ),
            'BG_SMTP_REPLY' => array(
                'label'      => '回复邮箱',
            ),
        ),
    ),
);

