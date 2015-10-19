<?php
/*-----------------------------------------------------------------
！！！！警告！！！！
以下为系统文件，请勿修改
-----------------------------------------------------------------*/
$base = $_SERVER["DOCUMENT_ROOT"] . str_replace(basename(dirname($_SERVER["PHP_SELF"])), "", dirname($_SERVER["PHP_SELF"]));

include_once($base . "config/init.class.php");

$obj_init = new CLASS_INIT();

$obj_init->config_gen();

include_once($obj_init->str_pathRoot . "config/config.inc.php"); //载入配置

include_once(BG_PATH_MODULE . "help/help.php");
