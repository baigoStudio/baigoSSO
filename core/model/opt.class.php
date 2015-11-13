<?php
/*-----------------------------------------------------------------
！！！！警告！！！！
以下为系统文件，请勿修改
-----------------------------------------------------------------*/

//不能非法包含或直接执行
if(!defined("IN_BAIGO")) {
	exit("Access Denied");
}

/*-------------设置项模型-------------*/
class MODEL_OPT {

	public $arr_const;

	function mdl_const($str_type) {
		if (!fn_token("chk")) { //令牌
			return array(
				"alert" => "x030102",
			);
			exit;
		}

		$_str_content = "<?php" . PHP_EOL;
		foreach ($this->arr_const[$str_type] as $_key=>$_value) {
			if (is_numeric($_value)) {
				$_str_content .= "define(\"" . $_key . "\", " . $_value . ");" . PHP_EOL;
			} else {
				$_str_content .= "define(\"" . $_key . "\", \"" . str_replace(PHP_EOL, "|", $_value) . "\");" . PHP_EOL;
			}
		}

		if ($str_type == "base") {
			$_str_content .= "define(\"BG_SITE_SSIN\", \"" . fn_rand(6) . "\");" . PHP_EOL;
		}

		$_str_content = str_replace("||", "", $_str_content);

		$_num_size    = file_put_contents(BG_PATH_CONFIG . "opt_" . $str_type . ".inc.php", $_str_content);

		if ($_num_size > 0) {
			$_str_alert = "y030405";
		} else {
			$_str_alert = "x030405";
		}

		return array(
			"alert" => $_str_alert,
		);
	}


	function mdl_over() {
		if (!fn_token("chk")) { //令牌
			return array(
				"alert" => "x030102",
			);
			exit;
		}

		$_str_content = "<?php" . PHP_EOL;
		$_str_content .= "define(\"BG_INSTALL_VER\", \"" . PRD_SSO_VER . "\");" . PHP_EOL;
		$_str_content .= "define(\"BG_INSTALL_PUB\", " . PRD_SSO_PUB . ");" . PHP_EOL;
		$_str_content .= "define(\"BG_INSTALL_TIME\", " . time() . ");" . PHP_EOL;

		$_num_size = file_put_contents(BG_PATH_CONFIG . "is_install.php", $_str_content);
		if ($_num_size > 0) {
			$_str_alert = "y030405";
		} else {
			$_str_alert = "x030405";
		}

		return array(
			"alert" => $_str_alert,
		);
	}

	function input_const($str_type) {
		$this->arr_const = fn_post("opt");

		return $this->arr_const[$str_type];
	}
}
