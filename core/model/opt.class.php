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
			$_str_alert = "y040101";
		} else {
			$_str_alert = "x040101";
		}

		return array(
			"alert" => $_str_alert,
		);
	}


	function mdl_dbconfig() {
		if (!fn_token("chk")) { //令牌
			return array(
				"alert" => "x030102",
			);
			exit;
		}

		$_str_content     = "<?php" . PHP_EOL;
		$_str_content    .= "define(\"BG_DB_HOST\", \"" . $this->dbconfig["db_host"] . "\");" . PHP_EOL;
		$_str_content    .= "define(\"BG_DB_PORT\", \"" . $this->dbconfig["db_port"] . "\");" . PHP_EOL;
		$_str_content    .= "define(\"BG_DB_NAME\", \"" . $this->dbconfig["db_name"] . "\");" . PHP_EOL;
		$_str_content    .= "define(\"BG_DB_USER\", \"" . $this->dbconfig["db_user"] . "\");" . PHP_EOL;
		$_str_content    .= "define(\"BG_DB_PASS\", \"" . $this->dbconfig["db_pass"] . "\");" . PHP_EOL;
		$_str_content    .= "define(\"BG_DB_CHARSET\", \"" . $this->dbconfig["db_charset"] . "\");" . PHP_EOL;
		$_str_content    .= "define(\"BG_DB_TABLE\", \"" . $this->dbconfig["db_table"] . "\");" . PHP_EOL;

		$_num_size        = file_put_contents(BG_PATH_CONFIG . "config_db.inc.php", $_str_content);

		if ($_num_size > 0) {
			$_str_alert = "y040101";
		} else {
			$_str_alert = "x040101";
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
			$_str_alert = "y040101";
		} else {
			$_str_alert = "x040101";
		}

		return array(
			"alert" => $_str_alert,
		);
	}


	function input_dbconfig() {
		$_arr_dbHost = validateStr(fn_post("db_host"), 1, 0);
		switch ($_arr_dbHost["status"]) {
			case "too_short":
				return array(
					"alert" => "x030204",
				);
				exit;
			break;

			case "ok":
				$this->dbconfig["db_host"] = $_arr_dbHost["str"];
			break;
		}

		$_arr_dbPort = validateStr(fn_post("db_port"), 1, 0);
		switch ($_arr_dbPort["status"]) {
			case "too_short":
				return array(
					"alert" => "x030211",
				);
				exit;
			break;

			case "ok":
				$this->dbconfig["db_port"] = $_arr_dbPort["str"];
			break;
		}

		$_arr_dbName = validateStr(fn_post("db_name"), 1, 0);
		switch ($_arr_dbName["status"]) {
			case "too_short":
				return array(
					"alert" => "x030205",
				);
				exit;
			break;

			case "ok":
				$this->dbconfig["db_name"] = $_arr_dbName["str"];
			break;
		}

		$_arr_dbUser = validateStr(fn_post("db_user"), 1, 0);
		switch ($_arr_dbUser["status"]) {
			case "too_short":
				return array(
					"alert" => "x030206",
				);
				exit;
			break;

			case "ok":
				$this->dbconfig["db_user"] = $_arr_dbUser["str"];
			break;
		}

		$_arr_dbPass = validateStr(fn_post("db_pass"), 1, 0);
		switch ($_arr_dbPass["status"]) {
			case "too_short":
				return array(
					"alert" => "x030207",
				);
				exit;
			break;

			case "ok":
				$this->dbconfig["db_pass"] = $_arr_dbPass["str"];
			break;
		}

		$_arr_dbCharset = validateStr(fn_post("db_charset"), 1, 0);
		switch ($_arr_dbCharset["status"]) {
			case "too_short":
				return array(
					"alert" => "x030208",
				);
				exit;
			break;

			case "ok":
				$this->dbconfig["db_charset"] = $_arr_dbCharset["str"];
			break;
		}

		$_arr_dbTable = validateStr(fn_post("db_table"), 1, 0);
		switch ($_arr_dbTable["status"]) {
			case "too_short":
				return array(
					"alert" => "x030209",
				);
				exit;
			break;

			case "ok":
				$this->dbconfig["db_table"] = $_arr_dbTable["str"];
			break;
		}

		$this->dbconfig["alert"] = "ok";

		return $this->dbconfig;
	}


	function input_const($str_type) {
		$this->arr_const = fn_post("opt");

		return $this->arr_const[$str_type];
	}
}
