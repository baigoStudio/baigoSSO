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

//通用
!defined('BG_DEBUG_SYS') && define('BG_DEBUG_SYS', 0);
!defined('BG_DEBUG_DB') && define('BG_DEBUG_DB', 0);
!defined('BG_SWITCH_LANG') && define('BG_SWITCH_LANG', 0);
!defined('BG_SWITCH_UI') && define('BG_SWITCH_UI', 0);
!defined('BG_SWITCH_TOKEN') && define('BG_SWITCH_TOKEN', 1);
!defined('BG_DEFAULT_SESSION') && define('BG_DEFAULT_SESSION', 1200);
!defined('BG_DEFAULT_PERPAGE') && define('BG_DEFAULT_PERPAGE', 30);
!defined('BG_DEFAULT_LANG') && define('BG_DEFAULT_LANG', 'zh_CN');
!defined('BG_DEFAULT_UI') && define('BG_DEFAULT_UI', 'default');
!defined('BG_NAME_CONTENT') && define('BG_NAME_CONTENT', 'content');
!defined('BG_NAME_PLUGIN') && define('BG_NAME_PLUGIN', 'plugin');
!defined('BG_NAME_TPL') && define('BG_NAME_TPL', 'tpl');
!defined('BG_NAME_CACHE') && define('BG_NAME_CACHE', 'cache');
!defined('BG_NAME_HELP') && define('BG_NAME_HELP', 'help');
!defined('BG_NAME_CORE') && define('BG_NAME_CORE', 'core');
!defined('BG_NAME_MODULE') && define('BG_NAME_MODULE', 'module');
!defined('BG_NAME_MODEL') && define('BG_NAME_MODEL', 'model');
!defined('BG_NAME_CONTROL') && define('BG_NAME_CONTROL', 'control');
!defined('BG_NAME_INC') && define('BG_NAME_INC', 'inc');
!defined('BG_NAME_LANG') && define('BG_NAME_LANG', 'lang');
!defined('BG_NAME_CLASS') && define('BG_NAME_CLASS', 'class');
!defined('BG_NAME_FUNC') && define('BG_NAME_FUNC', 'func');
!defined('BG_NAME_FONT') && define('BG_NAME_FONT', 'font');
!defined('BG_NAME_LIB') && define('BG_NAME_LIB', 'lib');
!defined('BG_NAME_CONSOLE') && define('BG_NAME_CONSOLE', 'console');
!defined('BG_NAME_PERSONAL') && define('BG_NAME_PERSONAL', 'personal');
!defined('BG_NAME_MISC') && define('BG_NAME_MISC', 'misc');
!defined('BG_NAME_INSTALL') && define('BG_NAME_INSTALL', 'install');
!defined('BG_NAME_API') && define('BG_NAME_API', 'api');
!defined('BG_NAME_STATIC') && define('BG_NAME_STATIC', 'static');
!defined('BG_PATH_ROOT') && define('BG_PATH_ROOT', realpath(__DIR__ . '/../') . DS);
!defined('BG_PATH_CONTENT') && define('BG_PATH_CONTENT', BG_PATH_ROOT . BG_NAME_CONTENT . DS);
!defined('BG_PATH_TPL') && define('BG_PATH_TPL', BG_PATH_CONTENT . BG_NAME_TPL . DS);
!defined('BG_PATH_CACHE') && define('BG_PATH_CACHE', BG_PATH_CONTENT . BG_NAME_CACHE . DS);
!defined('BG_PATH_PLUGIN') && define('BG_PATH_PLUGIN', BG_PATH_CONTENT . BG_NAME_PLUGIN . DS);
!defined('BG_PATH_HELP') && define('BG_PATH_HELP', BG_PATH_ROOT . BG_NAME_HELP . DS);
!defined('BG_PATH_STATIC') && define('BG_PATH_STATIC', BG_PATH_ROOT . BG_NAME_STATIC . DS);
!defined('BG_PATH_CORE') && define('BG_PATH_CORE', BG_PATH_ROOT . BG_NAME_CORE . DS);
!defined('BG_PATH_MODULE') && define('BG_PATH_MODULE', BG_PATH_CORE . BG_NAME_MODULE . DS);
!defined('BG_PATH_CONTROL') && define('BG_PATH_CONTROL', BG_PATH_CORE . BG_NAME_CONTROL . DS);
!defined('BG_PATH_MODEL') && define('BG_PATH_MODEL', BG_PATH_CORE . BG_NAME_MODEL . DS);
!defined('BG_PATH_FONT') && define('BG_PATH_FONT', BG_PATH_CORE . BG_NAME_FONT . DS);
!defined('BG_PATH_INC') && define('BG_PATH_INC', BG_PATH_CORE . BG_NAME_INC . DS);
!defined('BG_PATH_LANG') && define('BG_PATH_LANG', BG_PATH_CORE . BG_NAME_LANG . DS);
!defined('BG_PATH_CLASS') && define('BG_PATH_CLASS', BG_PATH_CORE . BG_NAME_CLASS . DS);
!defined('BG_PATH_FUNC') && define('BG_PATH_FUNC', BG_PATH_CORE . BG_NAME_FUNC . DS);
!defined('BG_PATH_LIB') && define('BG_PATH_LIB', BG_PATH_CORE . BG_NAME_LIB . DS);
!defined('BG_PATH_TPLSYS') && define('BG_PATH_TPLSYS', BG_PATH_CORE . BG_NAME_TPL . DS);
!defined('BG_URL_ROOT') && define('BG_URL_ROOT', str_ireplace(DS, '/', str_ireplace($_SERVER['DOCUMENT_ROOT'], '', BG_PATH_ROOT)));
!defined('BG_URL_HELP') && define('BG_URL_HELP', BG_URL_ROOT . BG_NAME_HELP . '/');
!defined('BG_URL_CONSOLE') && define('BG_URL_CONSOLE', BG_URL_ROOT . BG_NAME_CONSOLE . '/');
!defined('BG_URL_PERSONAL') && define('BG_URL_PERSONAL', BG_URL_ROOT . BG_NAME_PERSONAL . '/');
!defined('BG_URL_MISC') && define('BG_URL_MISC', BG_URL_ROOT . BG_NAME_MISC . '/');
!defined('BG_URL_INSTALL') && define('BG_URL_INSTALL', BG_URL_ROOT . BG_NAME_INSTALL . '/');
!defined('BG_URL_API') && define('BG_URL_API', BG_URL_ROOT . BG_NAME_API . '/');
!defined('BG_URL_STATIC') && define('BG_URL_STATIC', BG_URL_ROOT . BG_NAME_STATIC . '/');
