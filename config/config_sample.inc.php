<?php
/*-----------------------------------------------------------------
！！！！警告！！！！
以下为系统文件，请勿修改
-----------------------------------------------------------------*/
define("IN_BAIGO", true); //程序是否合法被包含
define("BG_SYS_DEBUG", false); //数据库调试模式
define("BG_DB_DEBUG", false); //数据库调试模式

/*-------------------------开关-------------------------*/
define("BG_SWITCH_LANG", false); //语言选择开关 true 允许选择
define("BG_SWITCH_UI", false); //界面选择开关 true 允许选择
define("BG_SWITCH_TOKEN", true); //表单提交令牌开关 true 验证令牌
define("BG_SWITCH_SMARTY_DEBUG", false); //表单提交令牌开关 true 验证令牌

/*-------------------------默认-------------------------*/
define("BG_DEFAULT_LANG", "zh_CN"); //默认语言
define("BG_DEFAULT_UI", "default"); //默认界面
define("BG_DEFAULT_SESSION", 1200); //默认会话过期时间，秒
define("BG_DEFAULT_PERPAGE", 30); //默认会话过期时间，秒
define("BG_DEFAULT_TOKEN", 604800); //默认口令过期时间，秒

/*-------------------------目录名称-------------------------*/
define("BG_NAME_TPL", "tpl"); //模板
define("BG_NAME_COMPILE", "compile"); //模板

define("BG_NAME_HELP", "help"); //生成文件目录
define("BG_NAME_CONFIG", "config");

define("BG_NAME_CORE", "core"); //源代码存放目录
define("BG_NAME_MODULE", "module"); //模块文件
define("BG_NAME_MODEL", "model"); //数据库模型
define("BG_NAME_CONTROL", "control"); //控制

define("BG_NAME_INC", "inc"); //共用程序
define("BG_NAME_LANG", "lang"); //语言
define("BG_NAME_CLASS", "class"); //类目录
define("BG_NAME_FUNC", "func"); //函数目录
define("BG_NAME_FONT", "font"); //字体
define("BG_NAME_SMARTY", "smarty"); //Smarty 目录

define("BG_NAME_ADMIN", "admin"); //后台
define("BG_NAME_INSTALL", "install"); //后台
define("BG_NAME_API", "api"); //后台

define("BG_NAME_STATIC", "static"); //静态文件(图片、CSS、JS 等)
define("BG_NAME_CSS", "css"); //CSS
define("BG_NAME_JS", "js"); //JS
define("BG_NAME_IMAGE", "image"); //图片

/*-------------------------路径-------------------------*/
define("BG_PATH_ROOT", str_replace("\\", "/", substr(dirname(__FILE__), 0, strrpos(dirname(__FILE__), BG_NAME_CONFIG)))); //根目录
define("BG_PATH_CONFIG", BG_PATH_ROOT . BG_NAME_CONFIG . "/"); //共用

define("BG_PATH_TPL", BG_PATH_ROOT . BG_NAME_TPL . "/"); //模板
define("BG_PATH_TPL_ADMIN", BG_PATH_TPL . BG_NAME_ADMIN . "/"); //模板
define("BG_PATH_TPL_INSTALL", BG_PATH_TPL . BG_NAME_INSTALL . "/"); //模板
define("BG_PATH_TPL_HELP", BG_PATH_TPL . BG_NAME_HELP . "/"); //模板
define("BG_PATH_TPL_COMPILE", BG_PATH_TPL . BG_NAME_COMPILE . "/"); //模板

define("BG_PATH_CORE", BG_PATH_ROOT . BG_NAME_CORE . "/"); //源代码目录

define("BG_PATH_MODULE", BG_PATH_CORE . BG_NAME_MODULE . "/"); //模块文件
define("BG_PATH_MODULE_ADMIN", BG_PATH_MODULE . BG_NAME_ADMIN . "/"); //模块文件
define("BG_PATH_MODULE_INSTALL", BG_PATH_MODULE . BG_NAME_INSTALL . "/"); //模块文件
define("BG_PATH_MODULE_API", BG_PATH_MODULE . BG_NAME_API . "/"); //模块文件
define("BG_PATH_MODULE_HELP", BG_PATH_MODULE . BG_NAME_HELP . "/"); //模块文件

define("BG_PATH_CONTROL", BG_PATH_CORE . BG_NAME_CONTROL . "/"); //控制
define("BG_PATH_CONTROL_ADMIN", BG_PATH_CONTROL . BG_NAME_ADMIN . "/"); //控制
define("BG_PATH_CONTROL_INSTALL", BG_PATH_CONTROL . BG_NAME_INSTALL . "/"); //控制
define("BG_PATH_CONTROL_API", BG_PATH_CONTROL . BG_NAME_API . "/"); //控制
define("BG_PATH_CONTROL_HELP", BG_PATH_CONTROL . BG_NAME_HELP . "/"); //控制

define("BG_PATH_MODEL", BG_PATH_CORE . BG_NAME_MODEL . "/"); //数据库模型
define("BG_PATH_FONT", BG_PATH_CORE . BG_NAME_FONT . "/"); //字体
define("BG_PATH_INC", BG_PATH_CORE . BG_NAME_INC . "/"); //共用
define("BG_PATH_LANG", BG_PATH_CORE . BG_NAME_LANG . "/"); //语言
define("BG_PATH_CLASS", BG_PATH_CORE . BG_NAME_CLASS . "/"); //类目录
define("BG_PATH_FUNC", BG_PATH_CORE . BG_NAME_FUNC . "/"); //函数目录
define("BG_PATH_SMARTY", BG_PATH_CORE . BG_NAME_SMARTY . "/"); //Smarty 目录


/*-------------------------URL-------------------------*/
define("BG_URL_ROOT", str_ireplace(str_ireplace("\\", "/", $_SERVER["DOCUMENT_ROOT"]), "", str_ireplace("\\", "/", BG_PATH_ROOT))); //根目录

define("BG_URL_HELP", BG_URL_ROOT . BG_NAME_HELP . "/"); //静态模式时文章存放目录
define("BG_URL_API", BG_URL_ROOT . BG_NAME_API . "/"); //静态文件目录
define("BG_URL_ADMIN", BG_URL_ROOT . BG_NAME_ADMIN . "/"); //管理目录
define("BG_URL_INSTALL", BG_URL_ROOT . BG_NAME_INSTALL . "/"); //管理目录
define("BG_URL_STATIC", BG_URL_ROOT . BG_NAME_STATIC . "/"); //静态文件目录
define("BG_URL_JS", BG_URL_STATIC . BG_NAME_JS . "/"); //JS
define("BG_URL_IMAGE", BG_URL_STATIC . BG_NAME_IMAGE . "/"); //JS

define("BG_URL_STATIC_ADMIN", BG_URL_STATIC . BG_NAME_ADMIN . "/"); //静态文件目录
define("BG_URL_STATIC_INSTALL", BG_URL_STATIC . BG_NAME_INSTALL . "/"); //静态文件目录
define("BG_URL_STATIC_HELP", BG_URL_STATIC . BG_NAME_HELP . "/"); //静态文件目录

/*-------------------------载入其他配置-------------------------*/
include_once(BG_PATH_INC . "version.inc.php"); //版本信息
include_once(BG_PATH_CONFIG . "config_db.inc.php"); //载入数据库配置
include_once(BG_PATH_CONFIG . "opt_base.inc.php"); //载入基本设置
include_once(BG_PATH_CONFIG . "opt_reg.inc.php"); //载入基本设置
//include_once(BG_PATH_CONFIG . "opt_mail.inc.php"); //载入基本设置

