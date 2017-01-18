<?php
class CLASS_CONFIG {
    public $str_nameConfig  = "config";

    function __construct() {
        $this->str_pathRoot = realpath(dirname(__FILE__) . "/../") . DIRECTORY_SEPARATOR;

        $this->arr_config = array(
            "IN_BAIGO"               => array(1, "num"),
            "BG_DEBUG_SYS"           => array(0, "num"),
            "BG_DEBUG_DB"            => array(0, "num"),
            "BG_SWITCH_LANG"         => array(0, "num"),
            "BG_SWITCH_UI"           => array(0, "num"),
            "BG_SWITCH_TOKEN"        => array(1, "num"),
            "BG_SWITCH_SMARTY_DEBUG" => array(0, "num"),
            "BG_DEFAULT_SESSION"     => array(1200, "num"),
            "BG_DEFAULT_PERPAGE"     => array(30, "num"),
            "BG_DEFAULT_LANG"        => array("zh_CN", "str"),
            "BG_DEFAULT_UI"          => array("default", "str"),
            "BG_NAME_CONFIG"         => array($this->str_nameConfig, "str"),
            "BG_NAME_TPL"            => array("tpl", "str"),
            "BG_NAME_CACHE"          => array("cache", "str"),
            "BG_NAME_HELP"           => array("help", "str"),
            "BG_NAME_CORE"           => array("core", "str"),
            "BG_NAME_MODULE"         => array("module", "str"),
            "BG_NAME_MODEL"          => array("model", "str"),
            "BG_NAME_CONTROL"        => array("control", "str"),
            "BG_NAME_INC"            => array("inc", "str"),
            "BG_NAME_LANG"           => array("lang", "str"),
            "BG_NAME_CLASS"          => array("class", "str"),
            "BG_NAME_FUNC"           => array("func", "str"),
            "BG_NAME_FONT"           => array("font", "str"),
            "BG_NAME_LIB"            => array("lib", "str"),
            "BG_NAME_CONSOLE"        => array("console", "str"),
            "BG_NAME_MY"             => array("my", "str"),
            "BG_NAME_MISC"           => array("misc", "str"),
            "BG_NAME_INSTALL"        => array("install", "str"),
            "BG_NAME_API"            => array("api", "str"),
            "BG_NAME_STATIC"         => array("static", "str"),
            "DS"                     => array("DIRECTORY_SEPARATOR", "const"),
            "BG_PATH_ROOT"           => array("realpath(dirname(__FILE__) . \"/../\") . DS", "const"),
            "BG_PATH_HELP"           => array("BG_PATH_ROOT . BG_NAME_HELP . DS", "const"),
            "BG_PATH_CONFIG"         => array("BG_PATH_ROOT . BG_NAME_CONFIG . DS", "const"),
            "BG_PATH_TPL"            => array("BG_PATH_ROOT . BG_NAME_TPL . DS", "const"),
            "BG_PATH_CACHE"          => array("BG_PATH_ROOT . BG_NAME_CACHE . DS", "const"),
            "BG_PATH_STATIC"         => array("BG_PATH_ROOT . BG_NAME_STATIC . DS", "const"),
            "BG_PATH_CORE"           => array("BG_PATH_ROOT . BG_NAME_CORE . DS", "const"),
            "BG_PATH_MODULE"         => array("BG_PATH_CORE . BG_NAME_MODULE . DS", "const"),
            "BG_PATH_CONTROL"        => array("BG_PATH_CORE . BG_NAME_CONTROL . DS", "const"),
            "BG_PATH_MODEL"          => array("BG_PATH_CORE . BG_NAME_MODEL . DS", "const"),
            "BG_PATH_FONT"           => array("BG_PATH_CORE . BG_NAME_FONT . DS", "const"),
            "BG_PATH_INC"            => array("BG_PATH_CORE . BG_NAME_INC . DS", "const"),
            "BG_PATH_LANG"           => array("BG_PATH_CORE . BG_NAME_LANG . DS", "const"),
            "BG_PATH_CLASS"          => array("BG_PATH_CORE . BG_NAME_CLASS . DS", "const"),
            "BG_PATH_FUNC"           => array("BG_PATH_CORE . BG_NAME_FUNC . DS", "const"),
            "BG_PATH_LIB"            => array("BG_PATH_CORE . BG_NAME_LIB . DS", "const"),
            "BG_PATH_TPLSYS"         => array("BG_PATH_CORE . BG_NAME_TPL . DS", "const"),
            "BG_URL_ROOT"            => array("str_ireplace(DS, \"/\", str_ireplace(\$_SERVER[\"DOCUMENT_ROOT\"], \"\", BG_PATH_ROOT))", "const"),
            "BG_URL_HELP"            => array("BG_URL_ROOT . BG_NAME_HELP . \"/\"", "const"),
            "BG_URL_CONSOLE"         => array("BG_URL_ROOT . BG_NAME_CONSOLE . \"/\"", "const"),
            "BG_URL_MY"              => array("BG_URL_ROOT . BG_NAME_MY . \"/\"", "const"),
            "BG_URL_MISC"            => array("BG_URL_ROOT . BG_NAME_MISC . \"/\"", "const"),
            "BG_URL_INSTALL"         => array("BG_URL_ROOT . BG_NAME_INSTALL . \"/\"", "const"),
            "BG_URL_API"             => array("BG_URL_ROOT . BG_NAME_API . \"/\"", "const"),
            "BG_URL_STATIC"          => array("BG_URL_ROOT . BG_NAME_STATIC . \"/\"", "const"),
        );

        $this->arr_dbconfig = array(
            "BG_DB_HOST"     => array("localhost", "str"),
            "BG_DB_PORT"     => array(3306, "num"),
            "BG_DB_NAME"     => array("baigo_sso", "str"),
            "BG_DB_USER"     => array("baigo_sso", "str"),
            "BG_DB_PASS"     => array("baigo_sso", "str"),
            "BG_DB_CHARSET"  => array("utf8", "str"),
            "BG_DB_TABLE"    => array("sso_", "str"),
        );

        $this->arr_base = array(
            "BG_SITE_NAME"      => array("baigo SSO", "str"),
            "BG_SITE_DOMAIN"    => array("\$_SERVER[\"SERVER_NAME\"]", "const"),
            "BG_SITE_URL"       => array("\"http://\" . \$_SERVER[\"SERVER_NAME\"]", "const"),
            "BG_SITE_PERPAGE"   => array(30, "num"),
            "BG_SITE_TIMEZONE"  => array("Asia/Shanghai", "str"),
            "BG_SITE_DATE"      => array("Y-m-d", "str"),
            "BG_SITE_DATESHORT" => array("m-d", "str"),
            "BG_SITE_TIME"      => array("H:i:s", "str"),
            "BG_SITE_TIMESHORT" => array("H:i", "str"),
            "BG_SITE_SSIN"      => array($this->rand(6), "str"),
            "BG_SITE_TPL"       => array("default", "str"),
            "BG_ACCESS_EXPIRE"  => array(60, "num"),
            "BG_REFRESH_EXPIRE" => array(30, "num"),
            "BG_VERIFY_EXPIRE"  => array(30, "num"),
        );

        $this->arr_reg = array(
            "BG_REG_ACC"         => array("enable", "str"),
            "BG_REG_NEEDMAIL"    => array("off", "str"),
            "BG_REG_ONEMAIL"     => array("false", "str"),
            "BG_REG_CONFIRM"     => array("off", "str"),
            "BG_LOGIN_MAIL"      => array("off", "str"),
            "BG_ACC_MAIL"        => array("", "str"),
            "BG_BAD_MAIL"        => array("", "str"),
            "BG_BAD_NAME"        => array("", "str"),
        );

        $this->arr_smtp = array(
            "BG_SMTP_HOST"       => array("\"smtp.\" . \$_SERVER[\"SERVER_NAME\"]", "const"),
            "BG_SMTP_PORT"       => array(25, "num"),
            "BG_SMTP_AUTH"       => array("true", "str"),
            "BG_SMTP_USER"       => array("\"user@\" . \$_SERVER[\"SERVER_NAME\"]", "const"),
            "BG_SMTP_PASS"       => array("password", "str"),
            "BG_SMTP_FROM"       => array("\"noreplay@\" . \$_SERVER[\"SERVER_NAME\"]", "const"),
            "BG_SMTP_REPLY"      => array("\"replay@\" . \$_SERVER[\"SERVER_NAME\"]", "const"),
        );
    }


    /** 生成配置
     * config_gen function.
     *
     * @access public
     * @param bool $is_install (default: false)
     * @return void
     */
    function config_gen($is_install = false) {
        $this->file_gen($this->arr_dbconfig, "opt_dbconfig", $is_install); //数据库配置
        $this->file_gen($this->arr_config, "config", $is_install); //全局配置
        $this->file_gen($this->arr_base, "opt_base", $is_install); //基本配置
        $this->file_gen($this->arr_reg, "opt_reg", $is_install); //注册配置
        $this->file_gen($this->arr_smtp, "opt_smtp", $is_install); //注册配置
    }


    /** 生成文件
     * file_gen function.
     *
     * @access private
     * @param mixed $arr_configSrc
     * @param mixed $str_file
     * @param bool $is_install (default: false)
     * @return void
     */
    private function file_gen($arr_configSrc, $str_file, $is_install = false) {
        $_str_config        = "";
        $_str_constConfig   = "";
        if (file_exists($this->str_pathRoot . "config/" . $str_file . ".inc.php")) { //如果文件存在
            if ($is_install) { //如果是安装状态，一一对比
                $_str_configChk = file_get_contents($this->str_pathRoot . "config/" . $str_file . ".inc.php"); //将配置文件转换为变量
                $_arr_config    = file($this->str_pathRoot . "config/" . $str_file . ".inc.php"); //将配置文件转换为数组
                $_arr_config    = array_unique($_arr_config);

                foreach ($arr_configSrc as $_key_src=>$_value_src) {
                    if (!stristr($_str_configChk, $_key_src)) { //如不存在则加上
                        if ($_value_src[1] == "str") {
                            $_str_constConfig = "define(\"" . $_key_src . "\", \"" . $_value_src[0] . "\");" . PHP_EOL;
                        } else {
                            $_str_constConfig = "define(\"" . $_key_src . "\", " . $_value_src[0] . ");" . PHP_EOL;
                        }

                        array_push($_arr_config, $_str_constConfig);
                    }
                }

                foreach ($_arr_config as $_key_src=>$_value_src) { //拼接
                    $_str_config .= $_value_src;
                }

                $_str_config = preg_replace("/require\(\S+\s\.\s\"\S+\"\);\n?/i", "", $_str_config);

                if ($str_file == "config") { //如果为全局配置，则增加 8 行
                    $_str_config = $this->end_process($_str_config);
                }

                $_str_config = str_ireplace("?>", "", $_str_config); //去除旧版本配置文件的 php 结尾

                file_put_contents($this->str_pathRoot . "config/" . $str_file . ".inc.php", $_str_config);
            }
        } else { //如果文件不存在则生成默认
            $_str_config = "<?php" . PHP_EOL;
            foreach ($arr_configSrc as $_key_src=>$_value_src) {
                if ($_value_src[1] == "str") {
                    $_str_config .= "define(\"" . $_key_src . "\", \"" . $_value_src[0] . "\");" . PHP_EOL;
                } else {
                    $_str_config .= "define(\"" . $_key_src . "\", " . $_value_src[0] . ");" . PHP_EOL;
                }
            }

            if ($str_file == "config") { //如果为全局配置，则增加 8 行
                $_str_config = $this->end_process($_str_config);
            }

            file_put_contents($this->str_pathRoot . "config/" . $str_file . ".inc.php", $_str_config);
        }
    }


    /** 随机数
     * rand function.
     *
     * @access private
     * @param int $num_rand (default: 32)
     * @return void
     */
    private function rand($num_rand = 32) {
        $_str_char = "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789";
        $_str_rnd = "";
        while (strlen($_str_rnd) < $num_rand) {
            $_str_rnd .= substr($_str_char, (rand(0, strlen($_str_char))), 1);
        }
        return $_str_rnd;
    }


    private function end_process($str_config) {
        $str_config .= "require(BG_PATH_INC . \"version.inc.php\");" . PHP_EOL;
        $str_config .= "require(BG_PATH_CONFIG . \"opt_dbconfig.inc.php\");" . PHP_EOL;
        $str_config .= "require(BG_PATH_CONFIG . \"opt_base.inc.php\");" . PHP_EOL;
        $str_config .= "require(BG_PATH_CONFIG . \"opt_reg.inc.php\");" . PHP_EOL;
        $str_config .= "require(BG_PATH_CONFIG . \"opt_smtp.inc.php\");" . PHP_EOL;

        return $str_config;
    }
}