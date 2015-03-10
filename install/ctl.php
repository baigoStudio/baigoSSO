<?php
/*-----------------------------------------------------------------
！！！！警告！！！！
以下为系统文件，请勿修改
-----------------------------------------------------------------*/
$base = $_SERVER["DOCUMENT_ROOT"] . str_replace(basename(dirname($_SERVER["PHP_SELF"])), "", dirname($_SERVER["PHP_SELF"]));

if (!file_exists($base . "config/config.inc.php")) {
	if (!file_exists($base . "config/config_sample.inc.php")) {
		header("Location: noconfig.html");
		exit;
	}
	copy($base . "config/config_sample.inc.php", $base . "config/config.inc.php");
}

include_once($base . "config/config.inc.php"); //载入配置

$arr_mod = array("install", "upgrade", "alert");

if (isset($_GET["mod"])) {
	$mod = $_GET["mod"];
} else {
	$mod = $arr_mod[0];
}

if (!in_array($mod, $arr_mod)) {
	exit("Access Denied");
}

include_once(BG_PATH_MODULE_INSTALL . "ctl/" . $mod . ".php");
