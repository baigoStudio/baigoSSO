<?php
/*-----------------------------------------------------------------
！！！！警告！！！！
以下为系统文件，请勿修改
-----------------------------------------------------------------*/

//不能非法包含或直接执行
if(!defined("IN_BAIGO")) {
    exit("Access Denied");
}

/*-------------------------类型-------------------------*/
return array(
    "ui" => array(
        "default" => "标准", //标准
        "mobile"  => "移动设备", //标准
    ),

    "lang" => array(
        "zh_CN"   => "简体中文", //简体中文
        "en"      => "English", //English
    ),

    "log" => array(
        "admin"   => "后台",
        "app"     => "应用",
        "system"  => "系统",
    ),

    "logTarget" => array(
        "admin"   => "管理员",
        "app"     => "应用",
        "user"    => "用户",
        "log"     => "日志",
        "opt"     => "设置",
    ),

    "pm" => array(
        "out"   => "已发送",
        "in"    => "收件箱",
    ),

    "ext" => array(
        "mysqli"      => "Mysqli 扩展库",
        "gd"          => "GD 扩展库",
        "mbstring"    => "mbstring 扩展库",
        "curl"        => "cURL 扩展库",
    ),
);
