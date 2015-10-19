<?php
/*-----------------------------------------------------------------
！！！！警告！！！！
以下为系统文件，请勿编辑
-----------------------------------------------------------------*/

//不能非法包含或直接执行
if(!defined("IN_BAIGO")) {
	exit("Access Denied");
}

/*----------后台管理模块----------*/
return array(
	"user" => array(
		"main" => array(
			"title"  => "用户管理",
			"mod"    => "user",
			"icon"   => "user",
		),
		"sub" => array(
			"list" => array(
				"title"     => "所有用户",
				"mod"       => "user",
				"act_get"   => "list",
			),
			"form" => array(
				"title"     => "创建用户",
				"mod"       => "user",
				"act_get"   => "form",
			),
		),
		"allow" => array(
			"browse" => "浏览",
			"add"    => "创建",
			"edit"   => "编辑",
			"del"    => "删除",
		),
	),
	"app" => array(
		"main" => array(
			"title"  => "应用管理",
			"mod"    => "app",
			"icon"   => "transfer",
		),
		"sub" => array(
			"list" => array(
				"title"     => "所有应用",
				"mod"       => "app",
				"act_get"   => "list",
			),
			"form" => array(
				"title"     => "创建应用",
				"mod"       => "app",
				"act_get"   => "form",
			),
		),
		"allow" => array(
			"browse" => "浏览",
			"add"    => "创建",
			"edit"   => "编辑",
			"del"    => "删除",
		),
	),
	"log" => array(
		"main" => array(
			"title"  => "日志管理",
			"mod"    => "log",
			"icon"   => "time",
		),
		"sub" => array(
			"list" => array(
				"title"     => "所有日志",
				"mod"       => "log",
				"act_get"   => "list",
			),
		),
		"allow" => array(
			"browse" => "浏览",
			"edit"   => "编辑",
			"del"    => "删除",
		),
	),
	"admin" => array(
		"main" => array(
			"title"  => "管理员",
			"mod"    => "admin",
			"icon"   => "lock",
		),
		"sub" => array(
			"list" => array(
				"title"     => "所有管理员",
				"mod"       => "admin",
				"act_get"   => "list",
			),
			"form" => array(
				"title"     => "创建管理员",
				"mod"       => "admin",
				"act_get"   => "form",
			),
		),
		"allow" => array(
			"browse" => "浏览",
			"add"    => "创建",
			"edit"   => "编辑",
			"del"    => "删除",
		),
	),
	"opt" => array(
		"main" => array(
			"title"  => "系统设置",
			"mod"    => "opt",
			"icon"   => "cog",
		),
		"sub" => array(
			"base" => array(
				"title"     => "基本设置",
				"mod"       => "opt",
				"act_get"   => "base",
			),
			"db" => array(
				"title"     => "数据库设置",
				"mod"       => "opt",
				"act_get"   => "db",
			),
			"reg" => array(
				"title"     => "注册设置",
				"mod"       => "opt",
				"act_get"   => "reg",
			),
			/*"mail" => array(
				"title"     => "邮件设置",
				"mod"       => "opt",
				"act_get"   => "mail",
			),*/
		),
		"allow" => array(
			"base"   => "基本设置",
			"db"     => "数据库设置",
			"reg"    => "注册设置",
			//"mail" => "邮件设置",
		),
	),
);
