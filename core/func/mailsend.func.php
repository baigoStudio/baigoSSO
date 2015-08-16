<?php
/*-----------------------------------------------------------------

！！！！警告！！！！
以下为系统文件，请勿修改

-----------------------------------------------------------------*/

//不能非法包含或直接执行
if(!defined("IN_BAIGO")) {
	exit("Access Denied");
}


/** 邮件发送 fsock
 * fn_mailsend function.
 *
 * @access public
 * @param mixed $arr_mailTo
 * @param mixed $str_mailSubject
 * @param mixed $str_mailContent
 * @param bool $is_send (default: true)
 * @return void
 */
function fn_mailsend($arr_mailTo, $str_mailSubject, $str_mailContent, $is_send = true) {

	//将目标邮件地址串成字符串
	$_str_mailTo = implode(",", $arr_mailTo);

	//设置端口
	if (!BG_MAIL_PORT) {
		$_str_mailPort = 25;
	} else {
		$_str_mailPort = BG_MAIL_PORT;
	}

	$_obj_fp = fsockopen(BG_MAIL_HOST, $_str_mailPort, $_num_errNo, $_str_errMsg, 30);

	//连接 SMTP 服务器
	if(!$_obj_fp) {
		return array(
			"alert" => "x030201",
		);
	}

 	stream_set_blocking($_obj_fp, true); //阻塞模式

	$_str_getMsg = fgets($_obj_fp, 512); //获取服务器信息
	if(substr($_str_getMsg, 0, 3) != "220") {
		return array(
			"alert" => "x030202",
		);
	}

	if (BG_MAIL_AUTH == "true") {
		$_str_mailAuth = "EHLO";
	} else {
		$_str_mailAuth = "HELO";
	}

	fputs($_obj_fp, $_str_mailAuth . " " . BG_SITE_NAME . PHP_EOL); //标识身份
	$_str_getMsg = fgets($_obj_fp, 512);
	if(substr($_str_getMsg, 0, 3) != 220 && substr($_str_getMsg, 0, 3) != 250) {
		return array(
			"alert" => "x030203",
		);
	}

	while(1) {
		if(substr($_str_getMsg, 3, 1) != "-" || !$_str_getMsg) {
 			break;
 		}
 		$_str_getMsg = fgets($_obj_fp, 512);
	}

	if(BG_MAIL_AUTH == "true") { //身份验证
		fputs($_obj_fp, "AUTH LOGIN" . PHP_EOL);
		$_str_getMsg = fgets($_obj_fp, 512);
		if(substr($_str_getMsg, 0, 3) != 334) {
			return array(
				"alert" => "x030204",
			);
		}

		fputs($_obj_fp, base64_encode(BG_MAIL_USER) . PHP_EOL);
		$_str_getMsg = fgets($_obj_fp, 512);
		if(substr($_str_getMsg, 0, 3) != 334) {
			return array(
				"alert" => "x030205",
			);
		}

		fputs($_obj_fp, base64_encode(BG_MAIL_PASS) . PHP_EOL);
		$_str_getMsg = fgets($_obj_fp, 512);
		if(substr($_str_getMsg, 0, 3) != 235) {
			return array(
				"alert" => "x030206",
			);
		}
	}

	//表明来源 E-mail
	fputs($_obj_fp, "MAIL FROM: <" . preg_replace("/.*\<(.+?)\>.*/", "\\1", BG_MAIL_USER) . ">" . PHP_EOL);
	$_str_getMsg = fgets($_obj_fp, 512);
	if(substr($_str_getMsg, 0, 3) != 250) {
		fputs($_obj_fp, "MAIL FROM: <" . preg_replace("/.*\<(.+?)\>.*/", "\\1", BG_MAIL_USER) . ">" . PHP_EOL);
		$_str_getMsg = fgets($_obj_fp, 512);
		if(substr($_str_getMsg, 0, 3) != 250) {
			return array(
				"alert" => "x030207",
			);
		}
	}

	//表明目标 E-mail
	foreach($arr_mailTo as $_value) {
		$_value = trim($_value);
		if($_value) {
			fputs($_obj_fp, "RCPT TO: <" . preg_replace("/.*\<(.+?)\>.*/", "\\1", $_value) . ">" . PHP_EOL);
			$_str_getMsg = fgets($_obj_fp, 512);
			if(substr($_str_getMsg, 0, 3) != 250) {
				fputs($_obj_fp, "RCPT TO: <" . preg_replace("/.*\<(.+?)\>.*/", "\\1", $_value) . ">" . PHP_EOL);
				$_str_getMsg = fgets($_obj_fp, 512);
				return array(
					"alert" => "x030208",
				);
			}
		}
	}

	if ($is_send) {

		//表明 E-mail 具体内容
		fputs($_obj_fp, "DATA" . PHP_EOL);
		$_str_getMsg = fgets($_obj_fp, 512);
		if(substr($_str_getMsg, 0, 3) != 354) {
			return array(
				"alert" => "x030209",
			);
		}

		fputs($_obj_fp, "From: <" . preg_replace("/.*\<(.+?)\>.*/", "\\1", BG_MAIL_FROM) . ">" . PHP_EOL);
		fputs($_obj_fp, "To: " . $_str_mailTo . PHP_EOL);
		fputs($_obj_fp, "Date: " . gmdate("r") . PHP_EOL);
		fputs($_obj_fp, "Subject: " . $str_mailSubject . PHP_EOL);
		fputs($_obj_fp, PHP_EOL . PHP_EOL);
		fputs($_obj_fp, $str_mailContent . PHP_EOL . "." . PHP_EOL);

		$_str_getMsg = fgets($_obj_fp, 512);
		if(substr($_str_getMsg, 0, 3) != 250) {
			return array(
				"alert" => "x030210",
			);
		}

	}

	fputs($_obj_fp, "QUIT" . PHP_EOL);

	return array(
		"alert" => "y030201",
	);
}
