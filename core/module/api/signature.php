<?php
/*-----------------------------------------------------------------
！！！！警告！！！！
以下为系统文件，请勿修改
-----------------------------------------------------------------*/


//不能非法包含或直接执行
if (!defined("IN_BAIGO")) {
    exit("Access Denied");
}

include_once(BG_PATH_FUNC . "init.func.php"); //初始化
$arr_set = array(
    "base"          => true, //基本设置
    //"ssin"          => true,
    "header"        => "Content-type: application/json; charset=utf-8", //header
    "db"            => true, //连接数据库
    "type"          => "ajax", //模块类型
);
fn_init($arr_set);

include_once(BG_PATH_CONTROL . "api/signature.class.php"); //载入签名控制器

$api_signature = new API_SIGNATURE(); //初始化签名

switch ($GLOBALS["act_post"]) {
    case "signature":
        $api_signature->api_signature(); //生成签名
    break;

    case "verify":
        $api_signature->api_verify(); //验证签名
    break;
}
