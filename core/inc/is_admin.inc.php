<?php
/*-----------------------------------------------------------------

！！！！警告！！！！
以下为系统文件，请勿修改

-----------------------------------------------------------------*/

//不能非法包含或直接执行
if(!defined("IN_BAIGO")) {
	exit("Access Denied");
}

if ($GLOBALS["adminLogged"]["alert"] != "y020102") {
	if ($GLOBALS["view"]) {
		$_str_location = "Location: " . BG_URL_ADMIN . "ctl.php?mod=alert&act_get=display&alert=" . $GLOBALS["adminLogged"]["alert"] . "&view=" . $GLOBALS["view"];
	} else {
		if (fn_server("REQUEST_URI")) {
			$_str_attach = base64_encode(fn_server("REQUEST_URI"));
		}
		$_str_location = "Location: " . BG_URL_ADMIN . "ctl.php?mod=logon&forward=" . $_str_attach;
	}
	header($_str_location);  //未登录就跳转至登录界面
	exit;
}
