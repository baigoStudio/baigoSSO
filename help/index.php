<?php
/*-----------------------------------------------------------------
！！！！警告！！！！
以下为系统文件，请勿修改
-----------------------------------------------------------------*/

define('BG_PATH_CONFIG', $_SERVER['DOCUMENT_ROOT'] . dirname(dirname($_SERVER['PHP_SELF'])) . DIRECTORY_SEPARATOR . 'config' . DIRECTORY_SEPARATOR);
define('BG_APP', 'help');

if (file_exists(BG_PATH_CONFIG . 'init.inc.php')) {
    require(BG_PATH_CONFIG . 'init.inc.php'); //载入配置
} else {
    exit('{"rcode":"x","msg":"Fatal Error: Initialize file does not exist!"}');
}
