<?php
/*-----------------------------------------------------------------
！！！！警告！！！！
以下为系统文件，请勿修改
-----------------------------------------------------------------*/

//不能非法包含或直接执行
if (!defined("IN_BAIGO")) {
    exit("Access Denied");
}

require(BG_PATH_INC . "common.inc.php"); //通用
$arr_set = array(
    "base"          => true, //基本设置
    "ssin"          => true, //启用会话
    "db"            => true, //连接数据库
    "dsp_type"      => "result",
);
fn_chkPHP($arr_set);

require(BG_PATH_FUNC . "init.func.php"); //初始化
fn_init($arr_set);

$ctrl_user = new CONTROL_CONSOLE_REQUEST_USER(); //初始化用户

switch ($GLOBALS["method"]) {
    case "POST":
        switch ($GLOBALS["act"]) {
            case "convert":
                $ctrl_user->ctrl_convert(); //导入
            break;

            case "import":
                $ctrl_user->ctrl_import(); //导入
            break;

            case "csvDel":
                $ctrl_user->ctrl_csvDel(); //导入
            break;

            case "submit":
                $ctrl_user->ctrl_submit(); //创建、编辑
            break;

            case "enable":
            case "wait":
            case "disable":
                $ctrl_user->ctrl_status(); //状态
            break;

            case "del":
                $ctrl_user->ctrl_del(); //删除
            break;
        }
    break;

    default:
        switch ($GLOBALS["act"]) {
            case "getname":
                $ctrl_user->ctrl_getname(); //验证用户名
            break;

            case "chkname":
                $ctrl_user->ctrl_chkname(); //验证用户名
            break;

            case "chkmail":
                $ctrl_user->ctrl_chkmail(); //验证 email
            break;
        }
    break;
}