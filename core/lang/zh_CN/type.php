<?php
/*-----------------------------------------------------------------
！！！！警告！！！！
以下为系统文件，请勿修改
-----------------------------------------------------------------*/

//不能非法包含或直接执行
if (!defined("IN_BAIGO")) {
    exit("Access Denied");
}

/*-------------------------类型-------------------------*/
return array(
    "ui" => array(
        "default" => "标准", //标准
        "mobile"  => "移动设备",
    ),

    "admin" => array(
        "normal"    => "普通管理员",
        "super"     => "超级管理员",
    ),

    "forgot" => array(
        "bymail"    => "通过邮件找回",
        "byqa"      => "回答密保问题找回",
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

    "verify" => array(
        "mailbox"   => "更换邮箱",
        "confirm"   => "用户激活",
        "forgot"    => "找回密码",
    ),

    "profile" => array(
        "info"      => array(
            "icon"  => "user",
            "title" => "个人信息",
        ),
        "pass"      => array(
            "icon"  => "lock",
            "title" => "密码",
        ),
        "qa"        => array(
            "icon"  => "question-sign",
            "title" => "密保问题",
        ),
        "mailbox"   => array(
            "icon"  => "inbox",
            "title" => "更换邮箱",
        ),
    ),

    "quesOften" => array(
        "您祖母叫什么名字",
        "您祖父叫什么名字",
        "您的生日是什么时候",
        "您母亲的名字",
        "您父亲的名字",
        "您宠物的名字叫什么",
        "您的车号是什么",
        "您的家乡是哪里",
        "您小学叫什么名字",
        "您最喜欢的颜色",
        "您女儿/儿子的小名叫什么",
        "谁是您儿时最好的伙伴",
        "您最尊敬的老师的名字",
    ),

    "ext" => array(
        "mysqli"    => "Mysqli 扩展库",
        "gd"        => "GD 扩展库",
        "mbstring"  => "mbstring 扩展库",
        "curl"      => "cURL 扩展库",
    ),
);
