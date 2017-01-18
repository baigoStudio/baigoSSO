<?php
/*-----------------------------------------------------------------
！！！！警告！！！！
以下为系统文件，请勿修改
-----------------------------------------------------------------*/
$arr_mod = array("user", "profile", "forgot", "pm", "code", "signature", "sync", "setup"); //允许的模块

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

if ($mod == "setup") { //如果是调用安装接口
    $is_setup = true;
} else {
    $is_setup = false;
}

$obj_config->config_gen($is_setup); //检查并生成配置文件

require($obj_config->str_pathRoot . "config/config.inc.php"); //载入配置

require(BG_PATH_MODULE . "api/" . $mod . ".php"); //调用模块
