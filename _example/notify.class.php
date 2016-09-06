<?php
/*-------------NOTIFY 接口类-------------*/
class CLASS_NOTIFY {

    /** 验证 app
     * app_chk function.
     *
     * @access public
     * @param mixed $arr_appGet
     * @param mixed $arr_appRow
     * @return void
     */
    function app_chk($num_appId, $str_appKey) {

        $_arr_appId = validateStr($num_appId, 1, 0, "str", "int");
        switch ($_arr_appId["status"]) {
            case "too_short":
                return array(
                    "alert" => "x220206",
                );
            break;

            case "format_err":
                return array(
                    "alert" => "x220207",
                );
            break;

            case "ok":
                $_arr_appChk["app_id"] = $_arr_appId["str"];
            break;
        }

        if ($_arr_appChk["app_id"] != BG_SSO_APPID) {
            return array(
                "alert" => "x220208",
            );
        }

        $_arr_appKey = validateStr($str_appKey, 1, 64, "str", "alphabetDigit");
        switch ($_arr_appKey["status"]) {
            case "too_short":
                return array(
                    "alert" => "x220209",
                );
            break;

            case "too_long":
                return array(
                    "alert" => "x220210",
                );
            break;

            case "format_err":
                return array(
                    "alert" => "x220211",
                );
            break;

            case "ok":
                $_arr_appChk["app_key"] = $_arr_appKey["str"];
            break;
        }

        if ($_arr_appChk["app_key"] != BG_SSO_APPKEY) {
            return array(
                "alert" => "x220212",
            );
        }

        $_arr_appChk["alert"] = "ok";

        return $_arr_appChk;
    }


    /** 读取 app 信息
     * app_get function.
     *
     * @access public
     * @param bool $chk_token (default: false)
     * @return void
     */
    function notify_input($str_method = "get", $chk_token = false) {

        switch ($str_method) {
            case "post":
                $_str_time                      = fn_post("time");
                $_str_signature                 = fn_post("signature");
                $_str_code                      = fn_post("code");
                $this->jsonp_callback           = fn_post("callback");
                $_arr_notifyInput["act_post"]   = fn_post("act_post");
            break;

            default:
                $_str_time                      = fn_get("time");
                $_str_signature                 = fn_get("signature");
                $_str_code                      = fn_get("code");
                $this->jsonp_callback           = fn_get("callback");
                $_arr_notifyInput["act_get"]    = fn_get("act_get");
            break;
        }

        $_arr_time = validateStr($_str_time, 1, 0);
        switch ($_arr_time["status"]) {
            case "too_short":
                return array(
                    "alert" => "x220201",
                );
            break;

            case "ok":
                $_arr_notifyInput["time"] = $_arr_time["str"];
            break;
        }

        $_arr_signature = validateStr($_str_signature, 1, 0);
        switch ($_arr_signature["status"]) {
            case "too_short":
                return array(
                    "alert" => "x220203",
                );
            break;

            case "ok":
                $_arr_notifyInput["signature"] = $_arr_signature["str"];
            break;
        }

        $_arr_code = validateStr($_str_code, 1, 0);
        switch ($_arr_code["status"]) {
            case "too_short":
                return array(
                    "alert" => "x220204",
                );
            break;

            case "ok":
                $_arr_notifyInput["code"] = $_arr_code["str"];
            break;
        }

        $_arr_notifyInput["alert"] = "ok";

        return $_arr_notifyInput;
    }


    /** 返回结果
     * halt_re function.
     *
     * @access public
     * @param mixed $arr_re
     * @return void
     */
    function halt_re($arr_re, $is_encode = false, $is_jsonp = false) {
        if ($is_encode) {
            $_str_return = fn_jsonEncode($arr_re, "encode");
        } else {
            $_str_return = json_encode($arr_re);
        }
        if ($is_jsonp) {
            $_str_return = $this->jsonp_callback . "(" . $_str_return . ")";
        }
        exit($_str_return); //输出错误信息
    }
}