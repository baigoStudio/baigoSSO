<?php
/*-----------------------------------------------------------------
！！！！警告！！！！
以下为系统文件，请勿编辑
-----------------------------------------------------------------*/

// 不能非法包含或直接执行
if (!defined('IN_GINKGO')) {
    return 'Access denied';
}

define('PRD_SSO_NAME', 'baigo SSO');
define('PRD_SSO_URL', 'http://www.baigo.net/sso/');
define('PRD_SSO_VER', '4.0-alpha-2');
define('PRD_SSO_PUB', 20210720);
define('PRD_SSO_HELP', 'http://doc.baigo.net/sso/');
define('PRD_VER_CHECK', 'http://www.baigo.net/ver_check/check.php');

defined('BG_TPL_PERSONAL') || define('BG_TPL_PERSONAL', GK_APP_TPL . 'personal' . DS); //前台模板
defined('BG_PATH_CONFIG') || define('BG_PATH_CONFIG', GK_PATH_APP . GK_NAME_CONFIG . DS); //配置文件

//error_reporting(E_ALL);
