<?php
/*-----------------------------------------------------------------
！！！！警告！！！！
以下为系统文件，请勿修改
-----------------------------------------------------------------*/


//不能非法包含或直接执行
if(!defined("IN_BAIGO")) {
    exit("Access Denied");
}


/** http 函数
 * fn_http function.
 *
 * @access public
 * @param mixed $str_url
 * @param mixed $arr_data
 * @param string $str_method (default: "get")
 * @return void
 */
function fn_http($str_url, $arr_data, $str_method = "get") {

    $_obj_http = curl_init();
    $_str_data = http_build_query($arr_data);

    $_arr_headers = array(
        "Content-Type: application/x-www-form-urlencoded",
        //"Content-length: " . strlen($_str_data),
    );

    if ($_arr_headers) {
        curl_setopt($_obj_http, CURLOPT_HTTPHEADER, $_arr_headers);
    }

    if ($str_method == "post") {
        curl_setopt($_obj_http, CURLOPT_POST, true);
        curl_setopt($_obj_http, CURLOPT_POSTFIELDS, $_str_data);
        curl_setopt($_obj_http, CURLOPT_URL, $str_url);
    } else {
        if (stristr($str_url, "?")) {
            $_str_conn = "&";
        } else {
            $_str_conn = "?";
        }
        curl_setopt($_obj_http, CURLOPT_URL, $str_url . $_str_conn . $_str_data);
    }

    curl_setopt($_obj_http, CURLOPT_RETURNTRANSFER, true);

    $_obj_ret = curl_exec($_obj_http);

    $_arr_return = array(
        "ret"     => $_obj_ret,
        "err"     => curl_error($_obj_http),
        "errno"   => curl_errno($_obj_http),
    );

    //print_r(curl_error($_obj_http));
    //print_r(curl_errno($_obj_http));
    //print_r($_obj_ret);

    curl_close($_obj_http);

    return $_arr_return;
}
