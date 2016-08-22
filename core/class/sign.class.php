<?php
/*-----------------------------------------------------------------
！！！！警告！！！！
以下为系统文件，请勿修改
-----------------------------------------------------------------*/


//不能非法包含或直接执行
if (!defined("IN_BAIGO")) {
    exit("Access Denied");
}

class CLASS_SIGN {
    //生成签名
    function sign_make($tm_time, $str_rand, $num_appId, $str_appKey) {
        $_num_time  = intval($tm_time);
        $_num_appId = intval($num_appId);
        $_arr_temp  = array($_num_time, $str_rand, $num_appId, $str_appKey, BG_SITE_SSIN);
        sort($_arr_temp);
        $_str_temp  = implode($_arr_temp);
        $_str_temp  = sha1($_str_temp);

        return $_str_temp;
    }

    //验证签名
    function sign_check($tm_time, $str_rand, $num_appId, $str_appKey, $str_sign) {
        $_str_temp = $this->sign_make($tm_time, $str_rand, $num_appId, $str_appKey);

        if ($_str_temp == $str_sign) {
            return true;
        } else {
            return false;
        }
    }
}
