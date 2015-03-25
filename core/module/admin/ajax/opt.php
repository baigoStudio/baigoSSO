<?php
/*-----------------------------------------------------------------
！！！！警告！！！！
以下为系统文件，请勿修改
-----------------------------------------------------------------*/

//不能非法包含或直接执行
if(!defined("IN_BAIGO")) {
	exit("Access Denied");
}

include_once(BG_PATH_INC . "common_admin_ajax.inc.php"); //管理员通用
include_once(BG_PATH_CONTROL_ADMIN . "ajax/opt.class.php"); //载入设置 ajax 控制器

$ajax_opt = new AJAX_OPT(); //初始化设置对象

switch ($GLOBALS["act_post"]) {
	case "reg":
		$ajax_opt->ajax_reg(); //注册
	break;

	case "mail":
		$ajax_opt->ajax_mail(); //邮件发送设置
	break;

	case "base":
		$ajax_opt->ajax_base(); //基本
	break;

	case "db":
		$ajax_opt->ajax_db(); //数据库
	break;
}
