<?php
/*-----------------------------------------------------------------
！！！！警告！！！！
以下为系统文件，请勿修改
-----------------------------------------------------------------*/

define('BG_PATH_CONFIG', $_SERVER['DOCUMENT_ROOT'] . dirname($_SERVER['PHP_SELF']) . DIRECTORY_SEPARATOR . 'config' . DIRECTORY_SEPARATOR);

if (file_exists(BG_PATH_CONFIG . 'config.class.php')) {
    require(BG_PATH_CONFIG . 'config.class.php'); //配置生成类
} else {
    exit('{"rcode":"x","msg":"Fatal Error: Config class does not exist!"}');
}
