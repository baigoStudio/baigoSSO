<?php
/*-----------------------------------------------------------------
！！！！警告！！！！
以下为系统文件，请勿修改
-----------------------------------------------------------------*/

define('BG_PATH_CONFIG', $_SERVER['DOCUMENT_ROOT'] . dirname(dirname($_SERVER['PHP_SELF'])) . DIRECTORY_SEPARATOR . 'config' . DIRECTORY_SEPARATOR);
define('BG_APP', 'help');

if (file_exists(BG_PATH_CONFIG . 'config.class.php')) {
    require(BG_PATH_CONFIG . 'config.class.php'); //配置生成类
} else {
    exit('{"rcode":"x","msg":"Fatal Error: Config class does not exist!"}');
}

if (file_exists(BG_PATH_CONFIG . 'config.inc.php')) {
    require(BG_PATH_CONFIG . 'config.inc.php'); //载入配置
} else {
    exit('{"rcode":"x","msg":"Fatal Error: Config file does not exist!"}');
}

if (file_exists(BG_PATH_CORE . 'runtime.php')) {
    require(BG_PATH_CORE . 'runtime.php');
} else {
    exit('{"rcode":"x","msg":"Fatal Error: Runtime does not exist!"}');
}
