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
    function sign_make($arr_params) {
        unset($arr_params["signature"], $arr_params["alert"]);

        $_arr_params = array();

    	foreach ($arr_params as $_key=>$_value) {
        	if (!fn_isEmpty($_value)) {
            	$_arr_params[$_key] = $_value;
        	}
    	}

        ksort($_arr_params);
        reset($_arr_params);

    	$_str_signSrc = http_build_query($_arr_params);

    	//如果存在转义字符，那么去掉转义
    	if (get_magic_quotes_gpc()){
        	$_str_signSrc = stripslashes($_str_signSrc);
    	}

    	return md5($_str_signSrc);
    }

    //验证签名
    function sign_check($arr_params, $str_sign) {
        $_str_signChk = $this->sign_make($arr_params);

        if ($_str_signChk == $str_sign) {
            return true;
        } else {
            return false;
        }
    }
}
