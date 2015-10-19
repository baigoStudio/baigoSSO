<?php
/*-----------------------------------------------------------------
！！！！警告！！！！
以下为系统文件，请勿修改
-----------------------------------------------------------------*/

//不能非法包含或直接执行
if(!defined("IN_BAIGO")) {
	exit("Access Denied");
}

/*-------------------------通用-------------------------*/
return array(
	/*------站点------*/
	"site"             => array(
		"name"            => "baigo SSO",
	),

	/*------页面标题------*/
	"page"             => array(
		"admin"           => "管理后台",
		"adminLogin"      => "管理后台登录",
		"alert"           => "提示信息",
		"edit"            => "编辑",
		"add"             => "创建",
		"detail"          => "详情",
		"show"            => "查看",
		"order"           => "优先级",
		"profile"         => "管理员个人信息",
		"pass"            => "修改密码",
		"appBelong"       => "授权用户",

		"upgrade"         => "baigo SSO 升级程序",
		"upgradeDbtable"  => "升级数据库",
		"upgradeOver"     => "完成升级",

		"install"         => "baigo SSO 安装程序",
		"installExt"      => "服务器环境检查",
		"installDbconfig" => "数据库设置",
		"installDbtable"  => "创建数据表",
		"installBase"     => "基本设置",
		"installReg"      => "注册设置",
		"installAdmin"    => "创建管理员",
		"installOver"     => "完成安装",
	),

	/*------链接文字------*/
	"href"             => array(
		"all"             => "全部",
		"agree"           => "同意",
		"logout"          => "退出",
		"back"            => "返回",
		"add"             => "创建",
		"edit"            => "编辑",
		"order"           => "优先级",
		"draft"           => "草稿",
		"recycle"         => "回收站",
		"help"            => "帮助",
		"appBelong"       => "授权用户",

		"passModi"        => "修改密码",
		"show"            => "查看",
		"noticeTest"      => "通知接口测试",

		"pageFirst"       => "首页",
		"pagePrevList"    => "上十页",
		"pagePrev"        => "上一页",
		"pageNext"        => "下一页",
		"pageNextList"    => "下十页",
		"pageLast"        => "尾页",
	),

	/*------说明文字------*/
	"label"            => array(
		"id"              => "ID",
		"add"             => "创建",
		"all"             => "全部",
		"seccode"         => "验证码",
		"key"             => "关键词",
		"type"            => "类型",
		"alert"           => "返回代码",
		"hits"            => "点击数",
		"count"           => "统计",
		"noname"          => "未命名",
		"unknown"         => "未知",
		"custom"          => "自定义",
		"modOnly"         => "（需要修改时输入）", //需要修改时输入
		"status"          => "状态",
		"box"             => "保存为",
		"allow"           => "权限",
		"note"            => "备注",
		"title"           => "标题",
		"pic"             => "图片",
		"content"         => "内容",
		"draft"           => "草稿",
		"recycle"         => "回收站",
		"check"           => "选中",
		"preview"         => "预览",
		"target"          => "目标",
		"result"          => "结果",
		"sync"            => "同步通知",
		"operator"        => "操作者",
		"on"              => "开",
		"off"             => "关",
		"apiUrl"          => "API 接口 URL",
		"upgrade"         => "正在进行升级安装",
		"to"              => "至",
		"complete"        => "升级安装无法创建管理员，点完成完成安装",
		"belongUser"      => "已授权用户",
		"selectUser"      => "待授权用户",

		"submitting"      => "正在提交 ...",

		"appName"         => "应用名称",
		"appId"           => "APP ID",
		"appKey"          => "APP KEY",
		"appKeyNote"      => "如果 APP KEY 泄露，可以通过重置更换，原 APP KEY 将作废。",
		"appNotice"       => "通知接口 URL",

		"ipAllow"         => "允许通信 IP",
		"ipBad"           => "禁止通信 IP",
		"ipNote"          => "每行一个 IP，可使用通配符 <strong>*</strong> （如 192.168.1.*）",

		"profileAllow"    => "个人权限",
		"profileInfo"     => "禁止修改个人信息",
		"profilePass"     => "禁止修改密码",

		"dbHost"          => "数据库服务器",
		"dbPort"          => "服务器端口",
		"dbName"          => "数据库名称",
		"dbUser"          => "数据库用户名",
		"dbPass"          => "数据库密码",
		"dbCharset"       => "数据库字符编码",
		"dbTable"         => "数据表名前缀",

		"user"            => "用户",
		"admin"           => "管理员",

		"username"        => "用户名", //用户名
		"password"        => "密码", //密码
		"passOld"         => "旧密码", //密码
		"passNew"         => "新密码", //密码
		"passConfirm"     => "确认密码", //密码

		"upgradeDbtable"    => "即将升级数据库",
		"upgradeOver"     => "还差最后一步，完成升级",

		"installOver"     => "还差最后一步，完成安装",
		"installDbtable"  => "即将创建数据表",

		"nick"            => "昵称",
		"email"           => "E-mail 地址",
		"timeAdd"         => "创建",
		"timeLogin"       => "最后登录",
	),

	/*------选择项------*/
	"option"           => array(
		"allStatus"       => "所有状态",
		"allType"         => "所有类型",
		"allYear"         => "所有年份",
		"allMonth"        => "所有月份",
		"please"          => "请选择",
		"batch"           => "批量操作",
		"del"             => "永久删除",
		"normal"          => "正常",
		"revert"          => "放回原处",
		"draft"           => "存为草稿",
		"recycle"         => "放入回收站",
	),

	/*------按钮------*/
	"btn"              => array(
		"ok"              => "确定",
		"submit"          => "提交",
		"del"             => "永久删除",
		"complete"        => "完成",
		"search"          => "搜索",
		"filter"          => "筛选",
		"recycle"         => "移至回收站",
		"empty"           => "清空回收站",
		"login"           => "登录",
		"skip"            => "跳过",
		"save"            => "保存",
		"close"           => "关闭",
		"jump"            => "跳转至",
		"over"            => "完成",
		"auth"            => "授权",
		"deauth"          => "取消授权",
		"stepNext"        => "下一步",
		"resetKey"        => "重置 APP KEY",
	),

	/*------确认框------*/
	"confirm"          => array(
		"del"             => "确认永久删除吗？此操作不可恢复！",
		"empty"           => "确认清空回收站吗？此操作不可恢复！",
		"deauth"          => "取消授权将使此应用失去对这些用户的编辑权限，确认取消吗？",
		"resetKey"        => "确认重置吗？此操作不可恢复！",
	),

	"text" => array(
		"extErr"          => "服务器环境检查未通过，请检查上述扩展库是否已经正确安装。",
		"extOk"           => "服务器环境检查通过，可以继续安装。",
	),

	/*------图片说明------*/
	"alt"              => array(
		"seccode"         => "看不清",
	),
);
