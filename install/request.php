<?php
/*-----------------------------------------------------------------
！！！！警告！！！！
以下为系统文件，请勿修改
-----------------------------------------------------------------*/
$arr_mod = array("setup", "upgrade"); //允许的模块

if (isset($_GET["mod"])) {
    $mod = $_GET["mod"];
} else {
    $mod = $arr_mod[0];
}

if (!in_array($mod, $arr_mod)) { //非法调用
    exit("Access Denied");
}

$base = $_SERVER["DOCUMENT_ROOT"] . str_ireplace(basename(dirname($_SERVER["PHP_SELF"])), "", dirname($_SERVER["PHP_SELF"]));

require($base . "config/config.class.php"); //载入初始化类

$obj_config = new CLASS_CONFIG(); //配置初始化

$obj_config->config_gen(); //生成配置文件

require($obj_config->str_pathRoot . "config/config.inc.php"); //载入配置

require(BG_PATH_MODULE . "install/request/" . $mod . ".php"); //调用模块