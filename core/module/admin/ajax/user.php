<?php
/*-----------------------------------------------------------------
！！！！警告！！！！
以下为系统文件，请勿修改
-----------------------------------------------------------------*/

//不能非法包含或直接执行
if(!defined("IN_BAIGO")) {
	exit("Access Denied");
}

include_once(BG_PATH_FUNC . "include.func.php"); //管理员通用
fn_include(true, true, "Content-type: application/json", true, "ajax", true);

include_once(BG_PATH_CONTROL . "admin/ajax/user.class.php"); //载入用户控制器

$ajax_user = new AJAX_USER(); //初始化用户

switch ($GLOBALS["act_post"]) {
	case "convert":
		$ajax_user->ajax_convert(); //导入
	break;

	case "import":
		$ajax_user->ajax_import(); //导入
	break;

	case "csvDel":
		$ajax_user->ajax_csvDel(); //导入
	break;

	case "submit":
		$ajax_user->ajax_submit(); //创建、编辑
	break;

	case "enable":
	case "wait":
	case "disable":
		$ajax_user->ajax_status(); //状态
	break;

	case "del":
		$ajax_user->ajax_del(); //删除
	break;

	default:
		switch ($GLOBALS["act_get"]) {
			case "chkname":
				$ajax_user->ajax_chkname(); //验证用户名
			break;

			case "chkmail":
				$ajax_user->ajax_chkmail(); //验证 email
			break;
		}
	break;
}
