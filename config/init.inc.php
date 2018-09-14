<?php
define('IN_BAIGO', true);
define('DS', DIRECTORY_SEPARATOR);
define('BG_NOW', $_SERVER['REQUEST_TIME']);

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

if (file_exists(BG_PATH_CONFIG . 'const.inc.php')) {
    require(BG_PATH_CONFIG . 'const.inc.php'); //载入配置
} else {
    exit('{"rcode":"x","msg":"Fatal Error: Const file does not exist!"}');
}

if (file_exists(BG_PATH_CORE . 'runtime.php')) {
    require(BG_PATH_CORE . 'runtime.php');
} else {
    exit('{"rcode":"x","msg":"Fatal Error: Runtime does not exist!"}');
}
