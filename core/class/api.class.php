<?php
/*-----------------------------------------------------------------
！！！！警告！！！！
以下为系统文件，请勿修改
-----------------------------------------------------------------*/

//不能非法包含或直接执行
if (!defined("IN_BAIGO")) {
    exit("Access Denied");
}

include_once(BG_PATH_FUNC . "http.func.php"); //载入模板类

/*-------------API 接口类-------------*/
class CLASS_API {

    private $obj_base; //配置
    private $config; //配置
    public $log; //配置

    function __construct() { //构造函数
        $this->obj_base = $GLOBALS["obj_base"]; //获取界面类型
        $this->config   = $this->obj_base->config;
        $this->log      = include_once(BG_PATH_LANG . $this->config["lang"] . "/log.php"); //载入日志内容
        $this->type     = include_once(BG_PATH_LANG . $this->config["lang"] . "/type.php"); //载入类型文件
        $this->opt      = include_once(BG_PATH_LANG . $this->config["lang"] . "/opt.php");
        $this->mail     = include_once(BG_PATH_LANG . $this->config["lang"] . "/mail.php");
        $this->status   = include_once(BG_PATH_LANG . $this->config["lang"] . "/status.php");
        $this->arr_return = array(
            "prd_sso_ver" => PRD_SSO_VER,
            "prd_sso_pub" => PRD_SSO_PUB,
        );
    }


    /** 验证 app
     * app_chk function.
     *
     * @access public
     * @param mixed $arr_appRequest
     * @param mixed $arr_appRow
     * @return void
     */
    function app_chk($arr_appRequest, $arr_appRow) {
        if ($arr_appRequest["alert"] != "ok") {
            return $arr_appRow;
        }

        if ($arr_appRow["app_status"] != "enable") {
            return array(
                "alert" => "x050402",
            );
        }

        $_str_ip = fn_getIp();

        if ($arr_appRow["app_ip_allow"]) {
            $_str_ipAllow = str_ireplace(PHP_EOL, "|", $arr_appRow["app_ip_allow"]);
            if (!fn_regChk($_str_ip, $_str_ipAllow, true)) {
                return array(
                    "alert" => "x050212",
                );
            }
        } else if ($arr_appRow["app_ip_bad"]) {
            $_str_ipBad = str_ireplace(PHP_EOL, "|", $arr_appRow["app_ip_bad"]);
            if (fn_regChk($_str_ip, $_str_ipBad)) {
                return array(
                    "alert" => "x050213",
                );
            }
        }

        if ($arr_appRow["app_key"] != $arr_appRequest["app_key"]) {
            return array(
                "alert" => "x050217",
            );
        }

        return array(
            "alert" => "ok",
        );
    }


    /** 读取 app 信息
     * app_request function.
     *
     * @access public
     * @return void
     */
    function app_request($str_method = "get", $with_sign = false) {
        if ($str_method == "post") {
            $_num_appId       = fn_post("app_id");
            $_str_appKey      = fn_post("app_key");
            $_tm_time         = fn_post("time");
            if ($with_sign) {
                $_str_sign    = fn_post("signature");
            }
        } else {
            $_num_appId       = fn_get("app_id");
            $_str_appKey      = fn_get("app_key");
            $_tm_time         = fn_get("time");
            if ($with_sign) {
                $_str_sign    = fn_get("signature");
            }
        }

        $_arr_appId = validateStr($_num_appId, 1, 0, "str", "int");
        switch ($_arr_appId["status"]) {
            case "too_short":
                return array(
                    "alert" => "x050203",
                );
            break;

            case "format_err":
                return array(
                    "alert" => "x050204",
                );
            break;

            case "ok":
                $_arr_appRequest["app_id"] = $_arr_appId["str"];
            break;
        }

        $_arr_appKey = validateStr($_str_appKey, 1, 64, "str", "alphabetDigit");
        switch ($_arr_appKey["status"]) {
            case "too_short":
                return array(
                    "alert" => "x050214",
                );
            break;

            case "too_long":
                return array(
                    "alert" => "x050215",
                );
            break;

            case "format_err":
                return array(
                    "alert" => "x050216",
                );
            break;

            case "ok":
                $_arr_appRequest["app_key"] = $_arr_appKey["str"];
            break;
        }

        $_arr_time = validateStr($_tm_time, 1, 0, "str", "int");
        switch ($_arr_time["status"]) {
            case "too_short":
                return array(
                    "alert" => "x050224",
                );
            break;

            case "format_err":
                return array(
                    "alert" => "x050225",
                );
            break;

            case "ok":
                $_arr_appRequest["time"] = $_arr_time["str"];
            break;
        }

        $_tm_diff = $_arr_appRequest["time"] - time();

        if ($_tm_diff > 1800 || $_tm_diff < -1800) {
            return array(
                "alert" => "x050227",
            );
        }

        if ($with_sign) {
            $_arr_sign = validateStr($_str_sign, 1, 0);
            switch ($_arr_appId["status"]) {
                case "too_short":
                    return array(
                        "alert" => "x050226",
                    );
                break;

                case "ok":
                    $_arr_appRequest["signature"] = $_arr_sign["str"];
                break;
            }
        }

        $_arr_appRequest["alert"] = "ok";

        return $_arr_appRequest;
    }


    /** 返回结果
     * halt_re function.
     *
     * @access public
     * @param mixed $arr_re
     * @return void
     */
    function halt_re($arr_re) {
        $arr_halt = array_merge($this->arr_return, $arr_re);

        exit(json_encode($arr_halt)); //输出错误信息
    }


    function chk_install() {
        if (file_exists(BG_PATH_CONFIG . "is_install.php")) { //验证是否已经安装
            include_once(BG_PATH_CONFIG . "is_install.php");
            if (!defined("BG_INSTALL_PUB") || PRD_SSO_PUB > BG_INSTALL_PUB) {
                $_arr_return = array(
                    "alert" => "x030411"
                );
                $this->halt_re($_arr_return);
            }
        } else {
            $_arr_return = array(
                "alert" => "x030410"
            );
            $this->halt_re($_arr_return);
        }
    }
}
