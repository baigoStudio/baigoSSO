<?php
/*-----------------------------------------------------------------
！！！！警告！！！！
以下为系统文件，请勿修改
-----------------------------------------------------------------*/

define('BG_PATH_CONFIG', $_SERVER['DOCUMENT_ROOT'] . dirname(dirname($_SERVER['PHP_SELF'])) . DIRECTORY_SEPARATOR . 'config' . DIRECTORY_SEPARATOR);
define('BG_APP', 'api');
define('BG_TYPE', 'api');

if (file_exists(BG_PATH_CONFIG . 'config.class.php')) {
    require(BG_PATH_CONFIG . 'config.class.php'); //配置生成类
} else {
    exit('Fatal Error: Config class not exists!');
}

if (file_exists(BG_PATH_CONFIG . 'config.inc.php')) {
    require(BG_PATH_CONFIG . 'config.inc.php'); //载入配置
} else {
    exit('Fatal Error: Config file not exists!');
}

if (file_exists(BG_PATH_CORE . 'runtime.php')) {
    require(BG_PATH_CORE . 'runtime.php');
} else {
    exit('Fatal Error: Runtime not exists!');
}
