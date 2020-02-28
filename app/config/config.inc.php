<?php return array(
    'var_default'   => array(
        'count_secqa'    => 3,
    ),
    'tpl' => array(
        'path'      => 'default',
    ),
    'session' => array(
        'autostart'     => true,
        'name'          => 'baigoSSOssinID',
        'prefix'        => 'baigo.sso',
    ),
    'cookie' => array(
        'prefix'    => 'baigo_sso_', // cookie 名称前缀
    ),
    'route' => array(
        'route_rule'    => array(
            'console/opt/base'              => 'console/opt/form',
            'console/opt/reg'               => 'console/opt/form',
        ),
    ),
    'config_extra' => array(
        'base'      => true,
        'reg'       => true,
        'mailtpl'   => true,
    ),
    'var_extra' => array(
        'base' => array( //设置默认值
            'site_name'         => 'baigo SSO',
            'site_perpage'      => 30,
            'site_date'         => 'Y-m-d',
            'site_date_short'   => 'm-d',
            'site_time'         => 'H:i:s',
            'site_time_short'   => 'H:i',
            'site_timezone'     => 'Asia/Shanghai',
            'site_tpl'          => 'default',
            'access_expire'     => 60,
            'refresh_expire'    => 60,
            'verify_expire'     => 30,
        ),
        'reg' => array(
            'reg_acc'        => 'on',
            'reg_needmail'   => 'off',
            'reg_confirm'    => 'off',
            'login_mail'     => 'off',
            'acc_mail'       => '',
            'bad_mail'       => '',
            'bad_name'       => '',
        ),
        'mailtpl' => array( //邮件模板默认
            'reg_subject'       => '',
            'reg_content'       => '',
            'mailbox_subject'   => '',
            'mailbox_content'   => '',
            'forgot_subject'    => '',
            'forgot_content'    => '',
        ),
    ),
    'version' => array(
        'prd_sso_name'      => 'baigo SSO',
        'prd_sso_url'       => 'http://www.baigo.net/sso/',
        'prd_sso_ver'       => '4.0 beta-2',
        'prd_sso_pub'       => 20200208,
        'prd_sso_help'      => 'http://doc.baigo.net/sso/',
        'prd_ver_check'     => 'http://www.baigo.net/ver_check/check.php',
    ),
    'ui_ctrl' => array(
        'copyright'             => 'on',
        'update_check'          => 'on',
        'logo_install'          => '',
        'logo_personal'         => '',
        'logo_console_login'    => '',
        'logo_console_head'     => '',
        'logo_console_foot'     => '',
    ),
);
