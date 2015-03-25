<?php
/*-----------------------------------------------------------------

！！！！警告！！！！
以下为系统文件，请勿修改

-----------------------------------------------------------------*/

//不能非法包含或直接执行
if(!defined("IN_BAIGO")) {
	exit("Access Denied");
}

if (file_exists(BG_PATH_CONFIG . "is_install.php")) { //验证是否已经安装
	include_once(BG_PATH_CONFIG . "is_install.php");
	if (!defined("BG_INSTALL_PUB") || PRD_SSO_PUB > BG_INSTALL_PUB) {
		header("Location: " . BG_URL_INSTALL . "ctl.php?mod=upgrade");
		exit;
	}
} else {
	header("Location: " . BG_URL_INSTALL . "ctl.php");
	exit;
}
