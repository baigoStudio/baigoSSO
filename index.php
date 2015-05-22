<?php
/*-----------------------------------------------------------------
！！！！警告！！！！
以下为系统文件，请勿修改
-----------------------------------------------------------------*/
$base = str_replace("\\", "/", str_replace("//", "/", $_SERVER["DOCUMENT_ROOT"] . "/" . basename(dirname($_SERVER["PHP_SELF"])) . "/"));
include_once($base . "config/config.inc.php"); //载入配置
