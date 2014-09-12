<?php
/*-----------------------------------------------------------------

！！！！警告！！！！
以下为系统文件，请勿修改

-----------------------------------------------------------------*/

//不能非法包含或直接执行
if(!defined("IN_BAIGO")) {
	exit("Access Denied");
}

if ($GLOBALS["adminLogged"]["str_alert"] != "y020102") {
	if ($GLOBALS["view"]) {
		$_str_location = "Location: " . BG_URL_ADMIN . "ctl.php?mod=alert&act_get=display&alert=" . $GLOBALS["adminLogged"]["str_alert"] . "&view=" . $GLOBALS["view"];
	} else {
		$_str_location = "Location: " . BG_URL_ADMIN . "ctl.php?mod=logon&forward=" . base64_encode($_SERVER["REQUEST_URI"]);
	}
	header($_str_location);  //未登录就跳转至登录界面
	exit;
}
?>