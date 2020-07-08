<?php
/*-----------------------------------------------------------------
！！！！警告！！！！
以下为系统文件，请勿修改
-----------------------------------------------------------------*/

define('IN_GINKGO', true); //合法包含验证
define('DS', DIRECTORY_SEPARATOR); //目录分离符

require(__DIR__ . DS . 'const.php'); //常量

require(GK_PATH_CORE . 'loader' . GK_EXT_CLASS); //载入器

// 注册自动加载
ginkgo\Loader::register();

// 注册错误和异常处理机制
ginkgo\Error::register();

