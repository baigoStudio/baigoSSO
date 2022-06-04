<?php
/*-----------------------------------------------------------------
！！！！警告！！！！
以下为系统文件，请勿修改
-----------------------------------------------------------------*/

// 不能非法包含或直接执行
if (!defined('IN_GINKGO')) {
  return 'Access denied';
}

define('GK_VERSION', '0.2.4'); //框架版本
define('GK_PUBLISH', 20220604); //框架发布时间
define('GK_NOW', $_SERVER['REQUEST_TIME']); //当前时间
define('GK_START_TIME', microtime(true)); //启动时间
define('GK_START_MEM', memory_get_usage()); //启动时内存

//用于时间计算的秒数
define('GK_MINUTE', 60);
define('GK_HOUR', 3600);
define('GK_DAY', 86400);
define('GK_WEEK', 604800);
define('GK_MONTH', 2592000);
define('GK_YEAR', 31536000);

//用于容量计算的字节数
define('GK_KB', 1024);
define('GK_MB', 1048576);
define('GK_GB', 1073741824);
define('GK_TB', 1099511627776);

//扩展名-------
defined('GK_EXT') || define('GK_EXT', '.php'); //扩展名
defined('GK_EXT_CLASS') || define('GK_EXT_CLASS', '.class' . GK_EXT); //类文件
defined('GK_EXT_CTRL') || define('GK_EXT_CTRL', '.ctrl' . GK_EXT); //控制器
defined('GK_EXT_MDL') || define('GK_EXT_MDL', '.mdl' . GK_EXT); //数据模型
defined('GK_EXT_VLD') || define('GK_EXT_VLD', '.vld' . GK_EXT); //验证器
defined('GK_EXT_INC') || define('GK_EXT_INC', '.inc' . GK_EXT); //包含
defined('GK_EXT_TPL') || define('GK_EXT_TPL', '.tpl' . GK_EXT); //模板
defined('GK_EXT_LANG') || define('GK_EXT_LANG', '.lang' . GK_EXT); //语言
defined('GK_EXT_LOG') || define('GK_EXT_LOG', '.log'); //日志

defined('GK_NAME_APP') || define('GK_NAME_APP', 'app'); //应用目录名称
defined('GK_NAME_ATTACH') || define('GK_NAME_ATTACH', 'attach'); //附件目录名称
defined('GK_NAME_STATIC') || define('GK_NAME_STATIC', 'static'); //静态文件目录名称
defined('GK_NAME_RUNTIME') || define('GK_NAME_RUNTIME', 'runtime'); //运行时目录名称
defined('GK_NAME_SESSION') || define('GK_NAME_SESSION', 'session'); //会话目录名称
defined('GK_NAME_CACHE') || define('GK_NAME_CACHE', 'cache'); //缓存目录名称
defined('GK_NAME_DATA') || define('GK_NAME_DATA', 'data'); //数据目录名称
defined('GK_NAME_TEMP') || define('GK_NAME_TEMP', 'temp'); //临时目录名称
defined('GK_NAME_LOG') || define('GK_NAME_LOG', 'log'); //日志目录名称
defined('GK_NAME_CORE') || define('GK_NAME_CORE', 'core'); //内核目录名称
defined('GK_NAME_TPL') || define('GK_NAME_TPL', 'tpl'); //系统模板目录名称
defined('GK_NAME_LANG') || define('GK_NAME_LANG', 'lang'); //系统语言目录名称
defined('GK_NAME_EXTEND') || define('GK_NAME_EXTEND', 'extend'); //扩展目录名称
defined('GK_NAME_PLUGIN') || define('GK_NAME_PLUGIN', 'plugin'); //插件目录名称
defined('GK_NAME_VENDOR') || define('GK_NAME_VENDOR', 'vendor'); //composer 目录名称

//WEB 目录-------
defined('GK_PATH_PUBLIC') || define('GK_PATH_PUBLIC', realpath(dirname($_SERVER['SCRIPT_FILENAME'])) . DS); //WEB 根路径
defined('GK_PATH_ATTACH') || define('GK_PATH_ATTACH', realpath(GK_PATH_PUBLIC) . DS . GK_NAME_ATTACH . DS); //附件目录

defined('GK_PATH_ROOT') || define('GK_PATH_ROOT', realpath(dirname(GK_PATH_PUBLIC)) . DS); //根目录
defined('GK_PATH_APP') || define('GK_PATH_APP', realpath(GK_PATH_ROOT) . DS . GK_NAME_APP . DS); //应用目录

defined('GK_APP_HASH') || define('GK_APP_HASH', md5(GK_PATH_APP)); //应用哈希

//运行时目录-------
defined('GK_PATH_RUNTIME') || define('GK_PATH_RUNTIME', realpath(GK_PATH_ROOT) . DS . GK_NAME_RUNTIME . DS); //运行时目录
defined('GK_PATH_SESSION') || define('GK_PATH_SESSION', realpath(GK_PATH_RUNTIME) . DS . GK_NAME_SESSION . DS . GK_APP_HASH . DS); //会话
defined('GK_PATH_CACHE') || define('GK_PATH_CACHE', realpath(GK_PATH_RUNTIME) . DS . GK_NAME_CACHE . DS . GK_APP_HASH . DS); //缓存
defined('GK_PATH_DATA') || define('GK_PATH_DATA', realpath(GK_PATH_RUNTIME) . DS . GK_NAME_DATA . DS . GK_APP_HASH . DS); //数据
defined('GK_PATH_TEMP') || define('GK_PATH_TEMP', realpath(GK_PATH_RUNTIME) . DS . GK_NAME_TEMP . DS . GK_APP_HASH . DS); //临时文件
defined('GK_PATH_LOG') || define('GK_PATH_LOG', realpath(GK_PATH_RUNTIME) . DS . GK_NAME_LOG . DS . GK_APP_HASH . DS); //日志文件

//扩展目录-------
defined('GK_PATH_EXTEND') || define('GK_PATH_EXTEND', realpath(GK_PATH_ROOT) . DS . GK_NAME_EXTEND . DS); //扩展
defined('GK_PATH_PLUGIN') || define('GK_PATH_PLUGIN', realpath(GK_PATH_EXTEND) . DS . GK_NAME_PLUGIN . DS); //插件
defined('GK_PATH_VENDOR') || define('GK_PATH_VENDOR', realpath(GK_PATH_ROOT) . DS . GK_NAME_VENDOR . DS); //composer

//框架目录-------
defined('GK_PATH_FW') || define('GK_PATH_FW', realpath(__DIR__) . DS); //框架所在目录
defined('GK_PATH_TPL') || define('GK_PATH_TPL', realpath(GK_PATH_FW) . DS . GK_NAME_TPL . DS); //系统模板
defined('GK_PATH_LANG') || define('GK_PATH_LANG', realpath(GK_PATH_FW) . DS . GK_NAME_LANG . DS); //系统语言
defined('GK_PATH_CORE') || define('GK_PATH_CORE', realpath(GK_PATH_FW) . DS . GK_NAME_CORE . DS); //内核

defined('GK_NAME_CLASSES') || define('GK_NAME_CLASSES', 'classes'); //类目录名称
defined('GK_NAME_CTRL') || define('GK_NAME_CTRL', 'ctrl'); //控制器目录名称
defined('GK_NAME_MDL') || define('GK_NAME_MDL', 'model'); //模型目录名称
defined('GK_NAME_VLD') || define('GK_NAME_VLD', 'validate'); //验证器目录名称
defined('GK_NAME_CONFIG') || define('GK_NAME_CONFIG', 'config'); //配置目录名称
defined('GK_NAME_APP_TPL') || define('GK_NAME_APP_TPL', 'tpl'); //应用模板目录名称
defined('GK_NAME_APP_LANG') || define('GK_NAME_APP_LANG', 'lang'); //应用语言目录名称

//应用目录-------
defined('GK_APP_CLASSES') || define('GK_APP_CLASSES', realpath(GK_PATH_APP) . DS . GK_NAME_CLASSES . DS); //应用控制器
defined('GK_APP_CTRL') || define('GK_APP_CTRL', realpath(GK_PATH_APP) . DS . GK_NAME_CTRL . DS); //应用控制器
defined('GK_APP_MDL') || define('GK_APP_MDL', realpath(GK_PATH_APP) . DS . GK_NAME_MDL . DS); //应用模型
defined('GK_APP_VLD') || define('GK_APP_VLD', realpath(GK_PATH_APP) . DS . GK_NAME_VLD . DS); //应用验证器
defined('GK_APP_CONFIG') || define('GK_APP_CONFIG', realpath(GK_PATH_APP) . DS . GK_NAME_CONFIG . DS); //应用配置
defined('GK_APP_TPL') || define('GK_APP_TPL', realpath(GK_PATH_APP) . DS . GK_NAME_APP_TPL . DS); //应用模板
defined('GK_APP_LANG') || define('GK_APP_LANG', realpath(GK_PATH_APP) . DS . GK_NAME_APP_LANG . DS); //应用语言
