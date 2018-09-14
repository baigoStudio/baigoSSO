<?php
/*-----------------------------------------------------------------
！！！！警告！！！！
以下为系统文件，请勿修改
-----------------------------------------------------------------*/

//不能非法包含或直接执行
if (!defined('IN_BAIGO')) {
    exit('Access Denied');
}

//常量设置验证，防止出现常量未定义错误

//基本设置
!defined('BG_SITE_NAME') && define('BG_SITE_NAME', 'baigo SSO');
!defined('BG_SITE_DOMAIN') && define('BG_SITE_DOMAIN', $_SERVER['SERVER_NAME']);
!defined('BG_SITE_URL') && define('BG_SITE_URL', 'http://' . $_SERVER['SERVER_NAME']);
!defined('BG_SITE_PERPAGE') && define('BG_SITE_PERPAGE', 30);
!defined('BG_SITE_DATE') && define('BG_SITE_DATE', 'Y-m-d');
!defined('BG_SITE_DATESHORT') && define('BG_SITE_DATESHORT', 'Y-m-d');
!defined('BG_SITE_TIME') && define('BG_SITE_TIME', 'H:i:s');
!defined('BG_SITE_TIMESHORT') && define('BG_SITE_TIMESHORT', 'H:i');
!defined('BG_ACCESS_EXPIRE') && define('BG_ACCESS_EXPIRE', 60);
!defined('BG_REFRESH_EXPIRE') && define('BG_REFRESH_EXPIRE', 60);
!defined('BG_VERIFY_EXPIRE') && define('BG_VERIFY_EXPIRE', 30);
!defined('BG_SITE_SSIN') && define('BG_SITE_SSIN', 'QhBAkX');

//数据库设置
!defined('BG_DB_HOST') && define('BG_DB_HOST', 'localhost');
!defined('BG_DB_NAME') && define('BG_DB_NAME', 'baigo_sso');
!defined('BG_DB_USER') && define('BG_DB_USER', 'baigo_sso');
!defined('BG_DB_PASS') && define('BG_DB_PASS', '');
!defined('BG_DB_CHARSET') && define('BG_DB_CHARSET', 'utf8');
!defined('BG_DB_TABLE') && define('BG_DB_TABLE', 'sso_');
!defined('BG_DB_PORT') && define('BG_DB_PORT', 3306);

//注册设置
!defined('BG_REG_ACC') && define('BG_REG_ACC', 'enable');
!defined('BG_REG_NEEDMAIL') && define('BG_REG_NEEDMAIL', 'on');
!defined('BG_REG_ONEMAIL') && define('BG_REG_ONEMAIL', 'true');
!defined('BG_LOGIN_MAIL') && define('BG_LOGIN_MAIL', 'off');
!defined('BG_REG_CONFIRM') && define('BG_REG_CONFIRM', 'on');
!defined('BG_ACC_MAIL') && define('BG_ACC_MAIL', '');
!defined('BG_BAD_MAIL') && define('BG_BAD_MAIL', '');
!defined('BG_BAD_NAME') && define('BG_BAD_NAME', '');

//邮件发送设置
!defined('BG_SMTP_HOST') && define('BG_SMTP_HOST', $_SERVER['SERVER_NAME']);
!defined('BG_SMTP_TYPE') && define('BG_SMTP_TYPE', 'smtp');
!defined('BG_SMTP_SEC') && define('BG_SMTP_SEC', 'ssl');
!defined('BG_SMTP_PORT') && define('BG_SMTP_PORT', 465);
!defined('BG_SMTP_AUTH') && define('BG_SMTP_AUTH', 'true');
!defined('BG_SMTP_AUTHTYPE') && define('BG_SMTP_AUTHTYPE', 'login');
!defined('BG_SMTP_USER') && define('BG_SMTP_USER', '');
!defined('BG_SMTP_PASS') && define('BG_SMTP_PASS', '');
!defined('BG_SMTP_FROM') && define('BG_SMTP_FROM', '');
!defined('BG_SMTP_REPLY') && define('BG_SMTP_REPLY', '');
