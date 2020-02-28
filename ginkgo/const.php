<?php
/*-----------------------------------------------------------------
！！！！警告！！！！
以下为系统文件，请勿修改
-----------------------------------------------------------------*/

//不能非法包含或直接执行
defined('IN_GINKGO') or exit('Access denied');

define('DS', DIRECTORY_SEPARATOR); //目录分离符
define('GK_VERSION', '0.1.1'); //框架版本
define('GK_PUBLISH', 20200228); //框架发布时间
define('GK_NOW', $_SERVER['REQUEST_TIME']); //当前时间
define('GK_START_TIME', microtime(true));
define('GK_START_MEM', memory_get_usage());

//用于时间计算
define('GK_MINUTE', 60);
define('GK_HOUR', 3600);
define('GK_DAY', 86400);
define('GK_WEEK', 604800);
define('GK_MONTH', 2592000);
define('GK_YEAR', 31536000);

//扩展名-------
defined('GK_EXT') or define('GK_EXT', '.php'); //扩展名
defined('GK_EXT_CLASS') or define('GK_EXT_CLASS', '.class' . GK_EXT); //类文件
defined('GK_EXT_CTRL') or define('GK_EXT_CTRL', '.ctrl' . GK_EXT); //控制器
defined('GK_EXT_MDL') or define('GK_EXT_MDL', '.mdl' . GK_EXT); //数据模型
defined('GK_EXT_VLD') or define('GK_EXT_VLD', '.vld' . GK_EXT); //验证器
defined('GK_EXT_INC') or define('GK_EXT_INC', '.inc' . GK_EXT); //包含
defined('GK_EXT_TPL') or define('GK_EXT_TPL', '.tpl' . GK_EXT); //模板
defined('GK_EXT_LANG') or define('GK_EXT_LANG', '.lang' . GK_EXT); //语言
defined('GK_EXT_LOG') or define('GK_EXT_LOG', '.log'); //日志

defined('GK_NAME_APP') or define('GK_NAME_APP', 'app'); //应用目录名称
defined('GK_NAME_ATTACH') or define('GK_NAME_ATTACH', 'attach'); //附件目录名称
defined('GK_NAME_STATIC') or define('GK_NAME_STATIC', 'static'); //静态文件目录名称
defined('GK_NAME_RUNTIME') or define('GK_NAME_RUNTIME', 'runtime'); //运行时目录名称
defined('GK_NAME_SESSION') or define('GK_NAME_SESSION', 'session'); //会话目录名称
defined('GK_NAME_CACHE') or define('GK_NAME_CACHE', 'cache'); //缓存目录名称
defined('GK_NAME_DATA') or define('GK_NAME_DATA', 'data'); //数据目录名称
defined('GK_NAME_TEMP') or define('GK_NAME_TEMP', 'temp'); //临时目录名称
defined('GK_NAME_LOG') or define('GK_NAME_LOG', 'log'); //日志目录名称
defined('GK_NAME_CORE') or define('GK_NAME_CORE', 'core'); //内核目录名称
defined('GK_NAME_TPL') or define('GK_NAME_TPL', 'tpl'); //系统模板目录名称
defined('GK_NAME_LANG') or define('GK_NAME_LANG', 'lang'); //系统语言目录名称
defined('GK_NAME_EXTEND') or define('GK_NAME_EXTEND', 'extend'); //扩展目录名称
defined('GK_NAME_PLUGIN') or define('GK_NAME_PLUGIN', 'plugin'); //插件目录名称
defined('GK_NAME_VENDOR') or define('GK_NAME_VENDOR', 'vendor'); //composer 目录名称

//WEB 目录-------
defined('GK_PATH_PUBLIC') or define('GK_PATH_PUBLIC', dirname($_SERVER['SCRIPT_FILENAME']) . DS); //WEB 根路径
defined('GK_PATH_ATTACH') or define('GK_PATH_ATTACH', rtrim(GK_PATH_PUBLIC, DS) . DS . GK_NAME_ATTACH . DS); //附件目录

defined('GK_PATH_ROOT') or define('GK_PATH_ROOT', dirname(realpath(GK_PATH_PUBLIC)) . DS); //根目录
defined('GK_PATH_APP') or define('GK_PATH_APP', rtrim(GK_PATH_ROOT, DS) . DS . GK_NAME_APP . DS); //应用目录

defined('GK_APP_HASH') or define('GK_APP_HASH', md5(GK_PATH_APP)); //应用哈希

//运行时目录-------
defined('GK_PATH_RUNTIME') or define('GK_PATH_RUNTIME', rtrim(GK_PATH_ROOT, DS) . DS . GK_NAME_RUNTIME . DS); //运行时目录
defined('GK_PATH_SESSION') or define('GK_PATH_SESSION', rtrim(GK_PATH_RUNTIME, DS) . DS . GK_NAME_SESSION . DS . GK_APP_HASH . DS); //会话
defined('GK_PATH_CACHE') or define('GK_PATH_CACHE', rtrim(GK_PATH_RUNTIME, DS) . DS . GK_NAME_CACHE . DS . GK_APP_HASH . DS); //缓存
defined('GK_PATH_DATA') or define('GK_PATH_DATA', rtrim(GK_PATH_RUNTIME, DS) . DS . GK_NAME_DATA . DS . GK_APP_HASH . DS); //数据
defined('GK_PATH_TEMP') or define('GK_PATH_TEMP', rtrim(GK_PATH_RUNTIME, DS) . DS . GK_NAME_TEMP . DS . GK_APP_HASH . DS); //临时文件
defined('GK_PATH_LOG') or define('GK_PATH_LOG', rtrim(GK_PATH_RUNTIME, DS) . DS . GK_NAME_LOG . DS . GK_APP_HASH . DS); //日志文件

//扩展目录-------
defined('GK_PATH_EXTEND') or define('GK_PATH_EXTEND', rtrim(GK_PATH_ROOT, DS) . DS . GK_NAME_EXTEND . DS); //扩展
defined('GK_PATH_PLUGIN') or define('GK_PATH_PLUGIN', rtrim(GK_PATH_EXTEND, DS) . DS . GK_NAME_PLUGIN . DS); //插件
defined('GK_PATH_VENDOR') or define('GK_PATH_VENDOR', rtrim(GK_PATH_ROOT, DS) . DS . GK_NAME_VENDOR . DS); //composer

//框架目录-------
defined('GK_PATH_FW') or define('GK_PATH_FW', __DIR__ . DS); //框架所在目录
defined('GK_PATH_TPL') or define('GK_PATH_TPL', rtrim(GK_PATH_FW, DS) . DS . GK_NAME_TPL . DS); //系统模板
defined('GK_PATH_LANG') or define('GK_PATH_LANG', rtrim(GK_PATH_FW, DS) . DS . GK_NAME_LANG . DS); //系统语言
defined('GK_PATH_CORE') or define('GK_PATH_CORE', rtrim(GK_PATH_FW, DS) . DS . GK_NAME_CORE . DS); //内核

defined('GK_NAME_CLASSES') or define('GK_NAME_CLASSES', 'classes'); //类目录名称
defined('GK_NAME_CTRL') or define('GK_NAME_CTRL', 'ctrl'); //控制器目录名称
defined('GK_NAME_MDL') or define('GK_NAME_MDL', 'model'); //模型目录名称
defined('GK_NAME_VLD') or define('GK_NAME_VLD', 'validate'); //验证器目录名称
defined('GK_NAME_CONFIG') or define('GK_NAME_CONFIG', 'config'); //配置目录名称
defined('GK_NAME_APP_TPL') or define('GK_NAME_APP_TPL', 'tpl'); //应用模板目录名称
defined('GK_NAME_APP_LANG') or define('GK_NAME_APP_LANG', 'lang'); //应用语言目录名称

//应用目录-------
defined('GK_APP_CLASSES') or define('GK_APP_CLASSES', rtrim(GK_PATH_APP, DS) . DS . GK_NAME_CLASSES . DS); //应用控制器
defined('GK_APP_CTRL') or define('GK_APP_CTRL', rtrim(GK_PATH_APP, DS) . DS . GK_NAME_CTRL . DS); //应用控制器
defined('GK_APP_MDL') or define('GK_APP_MDL', rtrim(GK_PATH_APP, DS) . DS . GK_NAME_MDL . DS); //应用模型
defined('GK_APP_VLD') or define('GK_APP_VLD', rtrim(GK_PATH_APP, DS) . DS . GK_NAME_VLD . DS); //应用验证器
defined('GK_APP_CONFIG') or define('GK_APP_CONFIG', rtrim(GK_PATH_APP, DS) . DS . GK_NAME_CONFIG . DS); //应用配置
defined('GK_APP_TPL') or define('GK_APP_TPL', rtrim(GK_PATH_APP, DS) . DS . GK_NAME_APP_TPL . DS); //应用模板
defined('GK_APP_LANG') or define('GK_APP_LANG', rtrim(GK_PATH_APP, DS) . DS . GK_NAME_APP_LANG . DS); //应用语言
