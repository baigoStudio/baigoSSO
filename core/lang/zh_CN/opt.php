<?php
/*-----------------------------------------------------------------
！！！！警告！！！！
以下为系统文件，请勿修改
-----------------------------------------------------------------*/

//不能非法包含或直接执行
if(!defined("IN_BAIGO")) {
	exit("Access Denied");
}

for ($_i = 14; $_i >= -12; $_i--) {
	if ($_i >= 0) {
		$_i_key   = "Etc/GMT+" . $_i;
		$_i_value = "Etc/GMT+" . $_i;
	} else {
		$_i_key   = "Etc/GMT" . $_i;
		$_i_value = "Etc/GMT" . $_i;
	}
	$_timezone[$_i_key] = $_i_value;
}

return array(
	"base" => array(
		"BG_SITE_NAME" => array(
			"label"      => "名称",
			"type"       => "str",
			"format"     => "text",
			"min"        => 1,
			"default"    => PRD_SSO_NAME,
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
			"note"       => "末尾请勿加 /",
		),
		"BG_SITE_PERPAGE" => array(
			"label"      => "每页显示数",
			"type"       => "str",
			"format"     => "int",
			"min"        => 1,
			"default"    => 30,
		),
		"BG_SITE_TIMEZONE" => array(
			"label"      => "时区",
			"type"       => "select",
			"min"        => 1,
			"option"     => $_timezone,
			"default"    => "Etc/GMT+8",
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
	),
	"reg" => array(
		"BG_REG_NEEDMAIL" => array(
			"label"  => "强制输入 E-mail",
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
			"label"  => "允许 E-mail 地址重复",
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
		"BG_ACC_MAIL" => array(
			"label"      => "允许注册的 E-mail",
			"type"       => "textarea",
			"format"     => "text",
			"min"        => 0,
			"default"    => "",
			"note"       => "只填域名部分，每行一个域名，如 @hotmail.com",
		),
		"BG_BAD_MAIL" => array(
			"label"      => "禁止注册的 E-mail",
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
	"mail" => array(
		"BG_MAIL_FROM" => array(
			"label"      => "邮件来源地址",
			"type"       => "str",
			"format"     => "email",
			"min"        => 1,
			"default"    => "sys@" . $_SERVER["SERVER_NAME"],
		),
		"BG_MAIL_HOST" => array(
			"label"      => "SMTP 服务器",
			"type"       => "str",
			"format"     => "text",
			"min"        => 1,
			"default"    => "smtp." . $_SERVER["SERVER_NAME"],
		),
		"BG_MAIL_PORT" => array(
			"label"      => "SMTP 服务器端口",
			"type"       => "str",
			"format"     => "int",
			"min"        => 1,
			"default"    => 25,
		),
		"BG_MAIL_AUTH" => array(
			"label"  => "SMTP 服务器要求身份验证",
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
		"BG_MAIL_USER" => array(
			"label"      => "SMTP 身份验证用户名",
			"type"       => "str",
			"format"     => "text",
			"min"        => 0,
			"default"    => "sys@" . $_SERVER["SERVER_NAME"],
		),
		"BG_MAIL_PASS" => array(
			"label"      => "SMTP 身份验证密码",
			"type"       => "str",
			"format"     => "text",
			"min"        => 0,
			"default"    => "password",
		),
	)
);
?>
