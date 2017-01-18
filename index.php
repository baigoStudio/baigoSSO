<?php
/*-----------------------------------------------------------------
！！！！警告！！！！
以下为系统文件，请勿修改
-----------------------------------------------------------------*/
$base = dirname(__FILE__) . DIRECTORY_SEPARATOR;

require($base . "config/config.class.php");

$obj_config = new CLASS_CONFIG();

$obj_config->config_gen();
