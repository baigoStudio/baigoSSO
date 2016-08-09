<?php
/*-----------------------------------------------------------------
！！！！警告！！！！
以下为系统文件，请勿修改
-----------------------------------------------------------------*/

//不能非法包含或直接执行
if (!defined("IN_BAIGO")) {
    exit("Access Denied");
}

return array(
    "base" => array(
        "title"   => "基本设置",
        "list"    => array(
            "BG_SITE_NAME" => array(
                "label"      => "站点名称",
                "type"       => "str",
                "format"     => "text",
                "min"        => 1,
                "default"    => BG_SITE_NAME,
            ),
            "BG_SITE_DOMAIN" => array(
                "label"      => "域名",
                "type"       => "str",
                "format"     => "text",
                "min"        => 1,
                "default"    => $_SERVER["SERVER_NAME"],
            ),
            "BG_SITE_URL" => array(
                "label"      => "首页 URL ",
                "type"       => "str",
                "format"     => "url",
                "min"        => 1,
                "default"    => "http://" . $_SERVER["SERVER_NAME"],
                "note"       => "末尾请勿加 <kdb>/</kbd>，仅需 http:// 和域名部分，如：http://" . $_SERVER["SERVER_NAME"],
            ),
            "BG_SITE_PERPAGE" => array(
                "label"      => "每页显示数",
                "type"       => "str",
                "format"     => "int",
                "min"        => 1,
                "default"    => 30,
            ),
            "BG_SITE_DATE" => array(
                "label"  => "日期格式",
                "type"   => "select",
                "min"    => 1,
                "option" => array(
                    "Y-m-d"     => date("Y-m-d"),
                    "y-m-d"     => date("y-m-d"),
                    "M. d, Y"   => date("M. d, Y"),
                ),
                "default" => "Y-m-d",
            ),
            "BG_SITE_DATESHORT" => array(
                "label"  => "短日期格式",
                "type"   => "select",
                "min"    => 1,
                "option" => array(
                    "m-d"   => date("m-d"),
                    "m-d"   => date("m-d"),
                    "M. d"  => date("M. d"),
                ),
                "default" => "Y-m-d",
            ),
            "BG_SITE_TIME" => array(
                "label"  => "时间格式",
                "type"   => "select",
                "min"    => 1,
                "option" => array(
                    "H:i"       => date("H:i"),
                    "h:i A"     => date("h:i A"),
                    "H:i:s"     => date("H:i:s"),
                    "h:i:s A"   => date("h:i:s A"),
                ),
                "default" => "H:i:s",
            ),
            "BG_SITE_TIMESHORT" => array(
                "label"  => "短时间格式",
                "type"   => "select",
                "min"    => 1,
                "option" => array(
                    "H:i"   => date("H:i"),
                    "h:i A" => date("h:i A"),
                ),
                "default" => "H:i",
            ),
            "BG_ACCESS_EXPIRE" => array(
                "label"     => "访问口令存活期",
                "type"      => "select",
                "min"       => 1,
                "option" => array(
                    "10"    => "10 分钟",
                    "20"    => "20 分钟",
                    "30"    => "30 分钟",
                    "40"    => "40 分钟",
                    "50"    => "50 分钟",
                    "60"    => "60 分钟",
                    "70"    => "70 分钟",
                    "80"    => "80 分钟",
                    "90"    => "90 分钟",
                ),
                "default"   => 60,
            ),
            "BG_REFRESH_EXPIRE" => array(
                "label"     => "刷新口令存活期",
                "type"      => "select",
                "min"       => 1,
                "option" => array(
                    "10"    => "10 天",
                    "20"    => "20 天",
                    "30"    => "30 天",
                    "40"    => "40 天",
                    "50"    => "50 天",
                    "60"    => "60 天",
                ),
                "default"   => 60,
            ),
            "BG_VERIFY_EXPIRE" => array(
                "label"     => "验证链接有效期",
                "type"      => "select",
                "min"       => 1,
                "option" => array(
                    "10"    => "10 分钟",
                    "20"    => "20 分钟",
                    "30"    => "30 分钟",
                    "40"    => "40 分钟",
                    "50"    => "50 分钟",
                    "60"    => "60 分钟",
                    "70"    => "70 分钟",
                    "80"    => "80 分钟",
                    "90"    => "90 分钟",
                ),
                "default"   => 30,
                "note"      => "验证邮箱、找回密码时的链接有效时间",
            ),
        ),
    ),
    "reg" => array(
        "title"   => "注册设置",
        "list"    => array(
            "BG_REG_ACC" => array(
                "label"  => "允许注册",
                "type"   => "radio",
                "min"    => 1,
                "option" => array(
                    "enable"    => array(
                        "value"    => "允许"
                    ),
                    "disable"   => array(
                        "value"    => "禁止"
                    ),
                ),
                "default" => "enable",
            ),
            "BG_REG_NEEDMAIL" => array(
                "label"  => "强制输入邮箱",
                "type"   => "radio",
                "min"    => 1,
                "option" => array(
                    "on"    => array(
                        "value"    => "开启"
                    ),
                    "off"   => array(
                        "value"    => "关闭"
                    ),
                ),
                "default" => "off",
            ),
            "BG_REG_ONEMAIL" => array(
                "label"  => "允许邮箱重复",
                "type"   => "radio",
                "min"    => 1,
                "option" => array(
                    "true"    => array(
                        "value"    => "是"
                    ),
                    "false"   => array(
                        "value"    => "否"
                    ),
                ),
                "default" => "false",
            ),
            "BG_LOGIN_MAIL" => array(
                "label"  => "使用邮箱登录、读取",
                "type"   => "radio",
                "min"    => 1,
                "option" => array(
                    "on"    => array(
                        "value"    => "开启"
                    ),
                    "off"   => array(
                        "value"    => "关闭"
                    ),
                ),
                "default"   => "off",
                "note"      => "此设置将强制输入邮箱并且邮箱不能重复",
            ),
            "BG_REG_CONFIRM" => array(
                "label"  => "验证邮箱激活用户",
                "type"   => "radio",
                "min"    => 1,
                "option" => array(
                    "on"    => array(
                        "value"    => "开启"
                    ),
                    "off"   => array(
                        "value"    => "关闭"
                    ),
                ),
                "default"   => "off",
                "note"      => "注册或更换邮箱均需要验证",
            ),
            "BG_ACC_MAIL" => array(
                "label"      => "允许注册的邮箱",
                "type"       => "textarea",
                "format"     => "text",
                "min"        => 0,
                "default"    => "",
                "note"       => "只填域名部分，每行一个域名，如 @hotmail.com",
            ),
            "BG_BAD_MAIL" => array(
                "label"      => "禁止注册的邮箱",
                "type"       => "textarea",
                "format"     => "text",
                "min"        => 0,
                "default"    => "",
                "note"       => "只填域名部分，每行一个域名，如 @hotmail.com",
            ),
            "BG_BAD_NAME" => array(
                "label"      => "禁止注册的用户名",
                "type"       => "textarea",
                "format"     => "text",
                "min"        => 0,
                "default"    => "",
                "note"       => "每行一个用户名，可使用通配符 * 如 *版主*",
            ),
        ),
    ),
    "smtp" => array(
        "title"   => "邮件发送设置",
        "list"    => array(
            "BG_SMTP_HOST" => array(
                "label"      => "SMTP 服务器",
                "type"       => "str",
                "format"     => "text",
                "min"        => 1,
                "default"    => "smtp." . $_SERVER["SERVER_NAME"],
            ),
            "BG_SMTP_PORT" => array(
                "label"      => "服务器端口",
                "type"       => "str",
                "format"     => "int",
                "min"        => 1,
                "default"    => 25,
            ),
            "BG_SMTP_AUTH" => array(
                "label"  => "服务器是否需要验证",
                "type"   => "radio",
                "min"    => 1,
                "option" => array(
                    "true"    => array(
                        "value"    => "是"
                    ),
                    "false"   => array(
                        "value"    => "否"
                    ),
                ),
                "default" => "true",
            ),
            "BG_SMTP_USER" => array(
                "label"      => "用户名",
                "type"       => "str",
                "format"     => "text",
                "min"        => 1,
                "default"    => "user@" . $_SERVER["SERVER_NAME"],
            ),
            "BG_SMTP_PASS" => array(
                "label"      => "密码",
                "type"       => "str",
                "format"     => "text",
                "min"        => 1,
                "default"    => "password",
            ),
            "BG_SMTP_FROM" => array(
                "label"      => "发送邮箱",
                "type"       => "str",
                "format"     => "text",
                "min"        => 1,
                "default"    => "noreply@" . $_SERVER["SERVER_NAME"],
            ),
            "BG_SMTP_REPLY" => array(
                "label"      => "回复邮箱",
                "type"       => "str",
                "format"     => "text",
                "min"        => 1,
                "default"    => "reply@" . $_SERVER["SERVER_NAME"],
            ),
        ),
    ),
);

