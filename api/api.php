<?php
/*-----------------------------------------------------------------
！！！！警告！！！！
以下为系统文件，请勿修改
-----------------------------------------------------------------*/
$arr_mod = array("user", "code", "signature", "sync", "install");

if (isset($_GET["mod"])) {
	$mod = $_GET["mod"];
} else {
	$mod = $arr_mod[0];
}

if (!in_array($mod, $arr_mod)) {
	exit("Access Denied");
}

include_once("../install/init.class.php");

$obj_init = new INSTALL_INIT();

if (!file_exists($obj_init->str_pathRoot . "config/config.inc.php")) {
	$obj_init->config_gen();
}

include_once($obj_init->str_pathRoot . "config/config.inc.php"); //载入配置

include_once(BG_PATH_MODULE . "api/" . $mod . ".php");
