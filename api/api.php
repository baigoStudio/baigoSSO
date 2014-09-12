<?php
/*-----------------------------------------------------------------
！！！！警告！！！！
以下为系统文件，请勿修改
-----------------------------------------------------------------*/
include_once("../config/config.inc.php"); //载入配置

$arr_mod = array("user", "code", "admin");

$mod = $_GET["mod"];

if (!$mod) {
	$mod = $arr_mod[0];
}

if (!in_array($mod, $arr_mod)) {
	exit("Access Denied");
}

include_once(BG_PATH_MODULE_API . $mod . ".php");
?>