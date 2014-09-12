<?php
/*-----------------------------------------------------------------
！！！！警告！！！！
以下为系统文件，请勿修改
-----------------------------------------------------------------*/

//不能非法包含或直接执行
if(!defined("IN_BAIGO")) {
	exit("Access Denied");
}

include_once(BG_PATH_MODEL . "admin.class.php"); //载入管理帐号模型


/*============验证 session, 并获取用户信息============
返回数组
	admin_id ID
	admin_open_label OPEN ID
	admin_open_site OPEN 站点
	admin_note 备注
	group_allow 权限
	str_alert 提示信息
*/
function fn_ssin_begin() {
	$_mdl_admin = new MODEL_ADMIN(); //设置管理员模型

	$_num_adminTimeDiff = $_SESSION["admin_ssintime_" . BG_SITE_SSIN] + BG_DEFAULT_SESSION; //session有效期

	if (!$_SESSION["admin_id_" . BG_SITE_SSIN] || !$_SESSION["admin_ssintime_" . BG_SITE_SSIN] || !$_SESSION["admin_hash_" . BG_SITE_SSIN] || $_num_adminTimeDiff < time()) {
		fn_ssin_end();
		$_arr_adminRow["str_alert"] = "x020401";
		return $_arr_adminRow;
		exit;
	}

	$_arr_adminRow = $_mdl_admin->mdl_read($_SESSION["admin_id_" . BG_SITE_SSIN]);

	if (fn_baigoEncrypt($_arr_adminRow["admin_time"], $_arr_adminRow["admin_rand"]) != $_SESSION["admin_hash_" . BG_SITE_SSIN]){
		fn_ssin_end();
		$_arr_adminRow["str_alert"] = "x020403";
		return $_arr_adminRow;
		exit;
	}

	$_SESSION["admin_ssintime_" . BG_SITE_SSIN]   = time();

	return $_arr_adminRow;
}


function fn_ssin_end() {
	unset($_SESSION["admin_id_" . BG_SITE_SSIN]);
	unset($_SESSION["admin_ssintime_" . BG_SITE_SSIN]);
	unset($_SESSION["admin_hash_" . BG_SITE_SSIN]);
}
?>