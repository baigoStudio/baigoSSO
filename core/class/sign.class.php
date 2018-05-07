<?php
/*-----------------------------------------------------------------
！！！！警告！！！！
以下为系统文件，请勿修改
-----------------------------------------------------------------*/


//不能非法包含或直接执行
if (!defined('IN_BAIGO')) {
    exit('Access Denied');
}

class CLASS_SIGN {

    //生成签名
    function sign_make($str_json, $key_appKey, $key_appSecret) {
    	if (get_magic_quotes_gpc()){
        	$str_json = stripslashes($str_json);
    	}

    	return strtoupper(md5($key_appKey . $str_json . $key_appSecret));
    }

    //验证签名
    function sign_check($str_json, $str_sign, $key_appKey, $key_appSecret) {
        $_str_signChk = $this->sign_make($str_json, $key_appKey, $key_appSecret);

        /*print_r($_str_signChk);
        print_r('<br>');
        print_r($str_sign);*/

        if ($_str_signChk == $str_sign) {
            return true;
        } else {
            return false;
        }
    }
}
