<?php
class CLASS_INIT {
	public $str_nameConfig = "config";
	public $arr_config     = array();

	function __construct() {
		$this->str_pathRoot = str_replace("\\", "/", substr(dirname(__FILE__), 0, strrpos(dirname(__FILE__), $this->str_nameConfig)));

		$this->arr_config = array(
			"IN_BAIGO"                => array(1, "num"),
			"BG_DEBUG_SYS"            => array(0, "num"),
			"BG_DEBUG_DB"             => array(0, "num"),
			"BG_SWITCH_LANG"          => array(0, "num"),
			"BG_SWITCH_UI"            => array(0, "num"),
			"BG_SWITCH_TOKEN"         => array(1, "num"),
			"BG_SWITCH_SMARTY_DEBUG"  => array(0, "num"),
			"BG_DEFAULT_SESSION"      => array(1200, "num"),
			"BG_DEFAULT_PERPAGE"      => array(30, "num"),
			"BG_DEFAULT_LANG"         => array("zh_CN", "str"),
			"BG_DEFAULT_UI"           => array("default", "str"),
			"BG_NAME_CONFIG"          => array($this->str_nameConfig, "str"),
			"BG_NAME_TPL"             => array("tpl", "str"),
			"BG_NAME_HELP"            => array("help", "str"),
			"BG_NAME_CORE"            => array("core", "str"),
			"BG_NAME_MODULE"          => array("module", "str"),
			"BG_NAME_MODEL"           => array("model", "str"),
			"BG_NAME_CONTROL"         => array("control", "str"),
			"BG_NAME_INC"             => array("inc", "str"),
			"BG_NAME_LANG"            => array("lang", "str"),
			"BG_NAME_CLASS"           => array("class", "str"),
			"BG_NAME_FUNC"            => array("func", "str"),
			"BG_NAME_FONT"            => array("font", "str"),
			"BG_NAME_SMARTY"          => array("smarty", "str"),
			"BG_NAME_ADMIN"           => array("admin", "str"),
			"BG_NAME_INSTALL"         => array("install", "str"),
			"BG_NAME_API"             => array("api", "str"),
			"BG_NAME_STATIC"          => array("static", "str"),
			"BG_PATH_ROOT"            => array("str_replace(\"\\\\\", \"/\", substr(dirname(__FILE__), 0, strrpos(dirname(__FILE__), BG_NAME_CONFIG)))", "const"),
			"BG_PATH_CONFIG"          => array("BG_PATH_ROOT . BG_NAME_CONFIG . \"/\"", "const"),
			"BG_PATH_TPL"             => array("BG_PATH_ROOT . BG_NAME_TPL . \"/\"", "const"),
			"BG_PATH_STATIC"          => array("BG_PATH_ROOT . BG_NAME_STATIC . \"/\"", "const"),
			"BG_PATH_CORE"            => array("BG_PATH_ROOT . BG_NAME_CORE . \"/\"", "const"),
			"BG_PATH_MODULE"          => array("BG_PATH_CORE . BG_NAME_MODULE . \"/\"", "const"),
			"BG_PATH_CONTROL"         => array("BG_PATH_CORE . BG_NAME_CONTROL . \"/\"", "const"),
			"BG_PATH_MODEL"           => array("BG_PATH_CORE . BG_NAME_MODEL . \"/\"", "const"),
			"BG_PATH_FONT"            => array("BG_PATH_CORE . BG_NAME_FONT . \"/\"", "const"),
			"BG_PATH_INC"             => array("BG_PATH_CORE . BG_NAME_INC . \"/\"", "const"),
			"BG_PATH_LANG"            => array("BG_PATH_CORE . BG_NAME_LANG . \"/\"", "const"),
			"BG_PATH_CLASS"           => array("BG_PATH_CORE . BG_NAME_CLASS . \"/\"", "const"),
			"BG_PATH_FUNC"            => array("BG_PATH_CORE . BG_NAME_FUNC . \"/\"", "const"),
			"BG_PATH_SMARTY"          => array("BG_PATH_CORE . BG_NAME_SMARTY . \"/\"", "const"),
			"BG_URL_ROOT"             => array("str_ireplace(str_ireplace(\"\\\\\", \"/\", \$_SERVER[\"DOCUMENT_ROOT\"]), \"\", str_ireplace(\"\\\\\", \"/\", BG_PATH_ROOT))", "const"),
			"BG_URL_HELP"             => array("BG_URL_ROOT . BG_NAME_HELP . \"/\"", "const"),
			"BG_URL_ADMIN"            => array("BG_URL_ROOT . BG_NAME_ADMIN . \"/\"", "const"),
			"BG_URL_INSTALL"          => array("BG_URL_ROOT . BG_NAME_INSTALL . \"/\"", "const"),
			"BG_URL_API"              => array("BG_URL_ROOT . BG_NAME_API . \"/\"", "const"),
			"BG_URL_STATIC"           => array("BG_URL_ROOT . BG_NAME_STATIC . \"/\"", "const"),
		);
	}

	function config_gen($is_install = false) {
		if (file_exists($this->str_pathRoot . "config/config.inc.php")) {
			if ($is_install) {
				include_once($this->str_pathRoot . "config/config.inc.php"); //载入配置
				$_arr_config = file($this->str_pathRoot . "config/config.inc.php");
				foreach ($this->arr_config as $_key_m=>$_value_m) {
					if (!defined($_key_m)) {
						if ($_value_m[1] == "str") {
							$_str_const = "define(\"" . $_key_m . "\", \"" . $_value_m[0] . "\");" . PHP_EOL;
						} else {
							$_str_const = "define(\"" . $_key_m . "\", " . $_value_m[0] . ");" . PHP_EOL;
						}

						array_splice($_arr_config, -5, 0, $_str_const);
					}
				}
				$_str_config = "";
				foreach ($_arr_config as $_key_m=>$_value_m) {
					$_str_config .= $_value_m;
				}

				//print_r($_str_config);
				file_put_contents($this->str_pathRoot . "config/config.inc.php", $_str_config);
			}
		} else {
			$_str_config = "<?php" . PHP_EOL;
			foreach ($this->arr_config as $_key_m=>$_value_m) {
				if ($_value_m[1] == "str") {
					$_str_config .= "define(\"" . $_key_m . "\", \"" . $_value_m[0] . "\");" . PHP_EOL;
				} else {
					$_str_config .= "define(\"" . $_key_m . "\", " . $_value_m[0] . ");" . PHP_EOL;
				}
			}

			$_str_config .= "include_once(BG_PATH_INC . \"version.inc.php\");" . PHP_EOL;
			$_str_config .= "include_once(BG_PATH_CONFIG . \"config_db.inc.php\");" . PHP_EOL;
			$_str_config .= "include_once(BG_PATH_CONFIG . \"opt_base.inc.php\");" . PHP_EOL;
			$_str_config .= "include_once(BG_PATH_CONFIG . \"opt_reg.inc.php\");" . PHP_EOL;

			file_put_contents($this->str_pathRoot . "config/config.inc.php", $_str_config);
		}
	}
}