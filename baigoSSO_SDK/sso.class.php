<?php
define(BG_SSO_URL, "http://www.domain.com/api/api.php"); //SSO 地址
define(BG_SSO_APPID, 1); //APP ID
define(BG_SSO_APPKEY, ""); //APP KEY


/*-------------单点登录类-------------*/
class CLASS_SSO {

    private $arr_data;

    function __construct() { //构造函数
        $this->arr_data = array(
            "app_id"     => BG_SSO_APPID, //APP ID
            "app_key"    => BG_SSO_APPKEY, //APP KEY
        );
    }


    /** 编码
     * sso_encode function.
     *
     * @access public
     * @param mixed $_str_json
     * @return void
     */
    function sso_encode($arr_data) {
        $_arr_json    = array_merge($this->arr_data, $arr_data); //合并数组
        $_str_json    = fn_jsonEncode($_arr_json, "encode");

        $_arr_sso = array(
            "act_post"   => "encode", //方法
            "data"       => $_str_json,
        );

        $_arr_ssoData = array_merge($this->arr_data, $_arr_sso);
        $_arr_get     = fn_http(BG_SSO_URL . "?mod=code", $_arr_ssoData, "post"); //提交

        return fn_jsonDecode($_arr_get["ret"], "no");
    }


    /** 解码
     * sso_decode function.
     *
     * @access public
     * @return void
     */
    function sso_decode($str_code, $str_key) {
        $_arr_sso = array(
            "act_post"   => "decode", //方法
            "code"       => $str_code, //加密串
            "key"        => $str_key, //解码秘钥
        );

        if (isset($this->appInstall)) { //仅在安装时使用
            $_arr_ssoData     = array_merge($this->appInstall, $_arr_sso); //合并数组
            $_arr_get         = fn_http($this->appInstall["sso_url"] . "?mod=code", $_arr_ssoData, "post"); //提交
        } else {
            $_arr_ssoData     = array_merge($this->arr_data, $_arr_sso); //合并数组
            $_arr_get         = fn_http(BG_SSO_URL . "?mod=code", $_arr_ssoData, "post"); //提交
        }

        return fn_jsonDecode($_arr_get["ret"], "decode");
    }


    /** 签名
     * sso_signature function.
     *
     * @access public
     * @param mixed $tm_time
     * @param mixed $str_rand
     * @return void
     */
    function sso_signature($tm_time, $str_rand) {
        $_arr_sso = array(
            "act_get"    => "signature", //方法
            "time"       => $tm_time,
            "random"     => $str_rand,
        );

        $_arr_ssoData     = array_merge($this->arr_data, $_arr_sso); //合并数组
        $_arr_get         = fn_http(BG_SSO_URL . "?mod=signature", $_arr_ssoData, "get"); //提交

        return fn_jsonDecode($_arr_get["ret"], "no");
    }


    /** 验证签名
     * sso_verify function.
     *
     * @access public
     * @param mixed $tm_time
     * @param mixed $str_rand
     * @param mixed $str_sign
     * @return void
     */
    function sso_verify($tm_time, $str_rand, $str_sign) {
        $_arr_sso = array(
            "act_get"    => "verify", //方法
            "time"       => $tm_time,
            "random"     => $str_rand,
            "signature"  => $str_sign,
        );

        $_arr_ssoData     = array_merge($this->arr_data, $_arr_sso); //合并数组
        $_arr_get         = fn_http(BG_SSO_URL . "?mod=signature", $_arr_ssoData, "get"); //提交

        return fn_jsonDecode($_arr_get["ret"], "no");
    }


    /** 注册
     * sso_reg function.
     *
     * @access public
     * @param mixed $str_userName 用户名
     * @param mixed $str_userPass 密码
     * @param string $str_userMail (default: "") 邮箱
     * @param string $str_userNick (default: "") 昵称
     * @return 解码后数组 注册结果
     */
    function sso_reg($str_userName, $str_userPass, $str_userMail = "", $str_userNick = "") {
        $_arr_sso = array(
            "act_post"   => "reg",
            "user_name"  => $str_userName,
            "user_pass"  => md5($str_userPass),
            "user_mail"  => $str_userMail,
            "user_nick"  => $str_userNick,
        );

        if (isset($this->appInstall)) { //仅在安装时使用
            $_arr_ssoData = array_merge($this->appInstall, $_arr_sso); //合并数组
            $_arr_get     = fn_http($this->appInstall["sso_url"] . "?mod=user", $_arr_ssoData, "post"); //提交
        } else {
            $_arr_ssoData = array_merge($this->arr_data, $_arr_sso); //合并数组
            $_arr_get     = fn_http(BG_SSO_URL . "?mod=user", $_arr_ssoData, "post"); //提交
        }
        $_arr_result  = $this->result_process($_arr_get);

        if ($_arr_result["alert"] != "y010101" && $_arr_result["alert"] != "y010410") {
            return $_arr_result; //返回错误信息
        }

        $_arr_decode          = $this->sso_decode($_arr_result["code"], $_arr_result["key"]); //解码
        $_arr_decode["alert"] = $_arr_result["alert"];

        return $_arr_decode;
    }


    /** 登录
     * sso_login function.
     *
     * @access public
     * @param mixed $str_userName 用户名
     * @param mixed $str_userPass 密码
     * @return 解码后数组 登录结果
     */
    function sso_login($str_user, $str_userPass, $str_userBy = "user_name") {
        $_arr_sso = array(
            "act_post"   => "login",
            $str_userBy  => $str_user,
            "user_pass"  => md5($str_userPass),
        );

        $_arr_ssoData = array_merge($this->arr_data, $_arr_sso);
        $_arr_get     = fn_http(BG_SSO_URL . "?mod=user", $_arr_ssoData, "post"); //提交
        $_arr_result  = $this->result_process($_arr_get);

        if ($_arr_result["alert"] != "y010401") {
            return $_arr_result; //返回错误信息
        }

        $_arr_decode          = $this->sso_decode($_arr_result["code"], $_arr_result["key"]); //解码
        $_arr_decode["alert"] = $_arr_result["alert"];

        return $_arr_decode;
    }


    /** 同步登录
     * sso_sync_login function.
     *
     * @access public
     * @param mixed $num_userId
     * @return void
     */
    function sso_sync_login($num_userId) {
        $_str_key             = fn_rand(6);
        $_tm_time             = time();
        $_str_rand            = fn_rand();
        $_arr_signature       = $this->sso_signature($_tm_time, $_str_rand);

        if ($_arr_signature["alert"] != "y050404") {
            return $_arr_signature; //返回错误信息
        }

        $_arr_sso = array(
            "act_post"  => "login",
            "user_id"   => $num_userId,
            "time"      => $_tm_time,
            "random"    => $_str_rand,
            "signature" => $_arr_signature["signature"],
        );

        $_arr_ssoData   = array_merge($this->arr_data, $_arr_sso);
        $_arr_get       = fn_http(BG_SSO_URL . "?mod=sync", $_arr_ssoData, "post"); //提交
        $_arr_result    = $this->result_process($_arr_get);
        if ($_arr_result["urlRows"]) {
            foreach ($_arr_result["urlRows"] as $_key=>$_value) {
                $_arr_result["urlRows"][$_key]["url"]   = urldecode($_value["url"]);
                $_arr_result["urlRows"][$_key]["data"]  = urldecode($_value["data"]);
            }
        }

        return $_arr_result;
    }


    /** 读取用户信息
     * sso_read function.
     *
     * @access public
     * @param mixed $str_user ID（或用户名）
     * @param string $str_userBy (default: "user_id") 用何种方式读取（默认用ID）
     * @return 解码后数组 用户信息
     */
    function sso_read($str_user, $str_userBy = "user_id") {
        $_arr_sso = array(
            "act_get"    => "read",
            $str_userBy  => $str_user,
        );

        $_arr_ssoData = array_merge($this->arr_data, $_arr_sso);
        $_arr_get     = fn_http(BG_SSO_URL . "?mod=user", $_arr_ssoData, "get"); //提交
        $_arr_result  = $this->result_process($_arr_get);

        if ($_arr_result["alert"] != "y010102") {
            return $_arr_result; //返回错误信息
        }

        $_arr_decode          = $this->sso_decode($_arr_result["code"], $_arr_result["key"]); //解码
        $_arr_decode["alert"] = $_arr_result["alert"];

        return $_arr_decode;
    }


    /** 编辑用户
     * sso_edit function.
     *
     * @access public
     * @param mixed $str_userName 用户名
     * @param string $str_userPass (default: "") 密码
     * @param string $str_userPassNew (default: "") 新密码
     * @param string $str_userMailNew (default: "") 新邮箱
     * @param string $str_userNick (default: "") 昵称
     * @param string $str_userBy (default: "user_name") 用何种方式编辑（默认用用户名）
     * @param string $str_checkPass (default: "off") 是否验证密码（默认不验证）
     * @return 解码后数组 编辑结果
     */
    function sso_edit($str_userName, $str_userPass = "", $str_userPassNew = "", $str_userMailNew = "", $str_userNick = "", $str_userBy = "user_name", $str_checkPass = false) {
        if ($str_userPassNew) {
            $_str_userPassNew = md5($str_userPassNew);
        } else {
            $_str_userPassNew = "";
        }

        $_arr_sso = array(
            "act_post"           => "edit",
            $str_userBy          => $str_userName,
            "user_check_pass"    => $str_checkPass,
            "user_pass"          => md5($str_userPass),
            "user_pass_new"      => $_str_userPassNew,
            "user_mail_new"      => $str_userMailNew,
            "user_nick"          => $str_userNick,
        );

        $_arr_ssoData = array_merge($this->arr_data, $_arr_sso);
        $_arr_get     = fn_http(BG_SSO_URL . "?mod=user", $_arr_ssoData, "post"); //提交
        $_arr_result  = $this->result_process($_arr_get);

        if ($_arr_result["alert"] != "y010103") {
            return $_arr_result; //返回错误信息
        }

        $_arr_decode          = $this->sso_decode($_arr_result["code"], $_arr_result["key"]); //解码
        $_arr_decode["alert"] = $_arr_result["alert"];

        return $_arr_decode;
    }


    function sso_mailbox($str_userName, $str_userPass = "", $str_userMailNew = "", $str_userBy = "user_name", $str_checkPass = false) {
        $_arr_sso = array(
            "act_post"           => "mailbox",
            $str_userBy          => $str_userName,
            "user_check_pass"    => $str_checkPass,
            "user_pass"          => md5($str_userPass),
            "user_mail_new"      => $str_userMailNew,
        );

        $_arr_ssoData = array_merge($this->arr_data, $_arr_sso);
        $_arr_get     = fn_http(BG_SSO_URL . "?mod=user", $_arr_ssoData, "post"); //提交
        $_arr_result  = $this->result_process($_arr_get);

        if ($_arr_result["alert"] != "y010406") {
            return $_arr_result; //返回错误信息
        }

        $_arr_decode          = $this->sso_decode($_arr_result["code"], $_arr_result["key"]); //解码
        $_arr_decode["alert"] = $_arr_result["alert"];

        return $_arr_decode;
    }


    function sso_forgot($str_user, $str_userBy = "user_id") {
        $_arr_sso = array(
            "act_post"  => "forgot",
            $str_userBy => $str_user,
        );

        $_arr_ssoData = array_merge($this->arr_data, $_arr_sso);
        $_arr_get     = fn_http(BG_SSO_URL . "?mod=user", $_arr_ssoData, "post"); //提交
        $_arr_result  = $this->result_process($_arr_get);

        if ($_arr_result["alert"] != "y010408") {
            return $_arr_result; //返回错误信息
        }

        $_arr_decode          = $this->sso_decode($_arr_result["code"], $_arr_result["key"]); //解码
        $_arr_decode["alert"] = $_arr_result["alert"];

        return $_arr_decode;
    }


    function sso_nomail($str_user, $str_userBy = "user_id") {
        $_arr_sso = array(
            "act_post"  => "nomail",
            $str_userBy => $str_user,
        );

        $_arr_ssoData = array_merge($this->arr_data, $_arr_sso);
        $_arr_get     = fn_http(BG_SSO_URL . "?mod=user", $_arr_ssoData, "post"); //提交
        $_arr_result  = $this->result_process($_arr_get);

        if ($_arr_result["alert"] != "y010408") {
            return $_arr_result; //返回错误信息
        }

        $_arr_decode          = $this->sso_decode($_arr_result["code"], $_arr_result["key"]); //解码
        $_arr_decode["alert"] = $_arr_result["alert"];

        return $_arr_decode;
    }


    /** 检查用户名
     * sso_chkname function.
     *
     * @access public
     * @param mixed $str_userName 用户名
     * @return 解码后数组 检查结果
     */
    function sso_chkname($str_userName) {
        $_arr_sso = array(
            "act_get"    => "chkname",
            "user_name"  => $str_userName,
        );

        $_arr_ssoData = array_merge($this->arr_data, $_arr_sso);
        $_arr_get     = fn_http(BG_SSO_URL . "?mod=user", $_arr_ssoData, "get"); //提交
        $_arr_result  = $this->result_process($_arr_get);

        if ($_arr_result["alert"] != "y010205") {
            return $_arr_result; //返回错误信息
        }

        //$this->sso_decode();
        $_arr_decode["alert"] = $_arr_result["alert"];

        return $_arr_decode;
    }


    /** 检查 邮箱
     * sso_chkmail function.
     *
     * @access public
     * @param mixed $str_userMail 邮箱
     * @param int $num_userId (default: 0) 当前用户ID（默认为0，忽略）
     * @return 解码后数组 检查结果
     */
    function sso_chkmail($str_userMail, $num_userId = 0) {
        $_arr_sso = array(
            "act_get"    => "chkmail",
            "user_mail"  => $str_userMail,
            "user_id"    => $num_userId,
        );

        $_arr_ssoData = array_merge($this->arr_data, $_arr_sso);
        $_arr_get     = fn_http(BG_SSO_URL . "?mod=user", $_arr_ssoData, "get"); //提交
        $_arr_result  = $this->result_process($_arr_get);

        if ($_arr_result["alert"] != "y010211") {
            return $_arr_result; //返回错误信息
        }

        //$this->sso_decode();
        $_arr_decode["alert"] = $_arr_result["alert"];

        return $_arr_decode;
    }


    function sso_install() {
        $_arr_ssoData = array(
            "act_post"   => "dbconfig",
            "db_host"    => BG_DB_HOST,
            "db_port"    => BG_DB_PORT,
            "db_name"    => BG_DB_NAME,
            "db_user"    => BG_DB_USER,
            "db_pass"    => BG_DB_PASS,
            "db_charset" => BG_DB_CHARSET,
            "db_table"   => "sso_",
        );
        $_arr_get     = fn_http(BG_SITE_URL . BG_URL_SSO . "api/api.php?mod=install", $_arr_ssoData, "post"); //提交
        $_arr_result  = $this->result_process($_arr_get);
        if ($_arr_result["alert"] != "y030404") {
            return $_arr_result;
        }


        $_arr_ssoData = array(
            "act_post"   => "dbtable",
        );
        $_arr_get     = fn_http(BG_SITE_URL . BG_URL_SSO . "api/api.php?mod=install", $_arr_ssoData, "post"); //提交
        $_arr_result  = $this->result_process($_arr_get);
        if ($_arr_result["alert"] != "y030108") {
            return $_arr_result;
        }

        return $_arr_result;
    }


    /** 管理员
     * sso_admin function.
     *
     * @access public
     * @param mixed $str_adminName
     * @param mixed $str_adminPass
     * @return void
     */
    function sso_admin($str_adminName, $str_adminPass) {
        $_arr_sso = array(
            "act_post"   => "admin",
            "admin_name" => $str_adminName,
            "admin_pass" => md5($str_adminPass),
        );

        $_arr_ssoData = array_merge($this->arr_data, $_arr_sso); //合并数组
        $_arr_get     = fn_http(BG_SITE_URL . BG_URL_SSO . "api/api.php?mod=install", $_arr_ssoData, "post"); //提交
        $_arr_result  = $this->result_process($_arr_get);
        if ($_arr_result["alert"] != "y020101") {
            return $_arr_result;
        }

        $_arr_ssoData = array(
            "act_post"          => "over",
            "app_name"          => "baigo CMS",
            "app_url_notice"    => BG_SITE_URL . BG_URL_API . "api.php?mod=notice",
            "app_url_sync"      => BG_SITE_URL . BG_URL_API . "api.php?mod=sync",
        );
        $_arr_get     = fn_http(BG_SITE_URL . BG_URL_SSO . "api/api.php?mod=install", $_arr_ssoData, "post"); //提交
        $_arr_result  = $this->result_process($_arr_get);
        if ($_arr_result["alert"] != "y030408") {
            return $_arr_result;
        }

        $this->appInstall = array(
            "sso_url"    => $_arr_result["sso_url"],
            "app_id"     => $_arr_result["app_id"],
            "app_key"    => $_arr_result["app_key"],
        );

        $_str_content = "<?php" . PHP_EOL;
        $_str_content .= "define(\"BG_SSO_URL\", \"" . $_arr_result["sso_url"] . "\");" . PHP_EOL;
        $_str_content .= "define(\"BG_SSO_APPID\", " . $_arr_result["app_id"] . ");" . PHP_EOL;
        $_str_content .= "define(\"BG_SSO_APPKEY\", \"" . $_arr_result["app_key"] . "\");" . PHP_EOL;
        $_str_content .= "define(\"BG_SSO_SYNC\", \"on\");" . PHP_EOL;

        $_num_size = file_put_contents(BG_PATH_CONFIG . "opt_sso.inc.php", $_str_content);

        if ($_num_size > 0) {
            $_str_alert = "y060101";
        } else {
            $_str_alert = "x060101";
        }

        $_arr_return = array(
            "alert" => $_str_alert,
        );
        return $_arr_result;
    }

    /**
     * result_process function.
     *
     * @access private
     * @return void
     */
    private function result_process($arr_get) {
        if (!isset($arr_get["ret"])) {
            $_arr_result = array(
                "alert" => "x030114"
            );
            return $_arr_result;
        }

        $_arr_result = json_decode($arr_get["ret"], true);
        if (!isset($_arr_result["alert"])) {
            $_arr_result = array(
                "alert" => "x030114"
            );
            return $_arr_result;
        }

        if (!isset($_arr_result["prd_sso_pub"]) || $_arr_result["prd_sso_pub"] < 20151116) {
            $_arr_result = array(
                "alert" => "x030114"
            );
            return $_arr_result;
        }

        return $_arr_result;
    }
}
