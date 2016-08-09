<?php
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


/** JSON 编码（内容可编码成 base64）
 * fn_jsonEncode function.
 *
 * @access public
 * @param string $arr_json (default: "")
 * @param string $method (default: "")
 * @return void
 */
function fn_jsonEncode($arr_json = "", $method = "") {
    if ($arr_json) {
        $arr_json = fn_eachArray($arr_json, $method);
        //print_r($method);
        $str_json = json_encode($arr_json); //json编码
    } else {
        $str_json = "";
    }
    return $str_json;
}


/** JSON 解码 (内容可解码自 base64)
 * jsonDecode function.
 *
 * @access public
 * @param string $str_json (default: "")
 * @param string $method (default: "")
 * @return void
 */
function fn_jsonDecode($str_json = "", $method = "") {
    if (isset($str_json)) {
        $arr_json = json_decode($str_json, true); //json解码
        $arr_json = fn_eachArray($arr_json, $method);
    } else {
        $arr_json = array();
    }
    return $arr_json;
}



/** 遍历数组，并进行 base64 解码编码
 * fn_eachArray function.
 *
 * @access public
 * @param mixed $arr
 * @param string $method (default: "encode")
 * @return void
 */
function fn_eachArray($arr, $method = "encode") {
    $_is_magic = get_magic_quotes_gpc();
    if (is_array($arr)) {
        foreach ($arr as $_key=>$_value) {
            if (is_array($_value)) {
                $arr[$_key] = fn_eachArray($_value, $method);
            } else {
                switch ($method) {
                    case "encode":
                        if (!$_is_magic) {
                            $_str = addslashes($_value);
                        } else {
                            $_str = $_value;
                        }
                        $arr[$_key] = base64_encode($_str);
                    break;

                    case "decode":
                        $_str = base64_decode($_value);
                        //if (!$_is_magic) {
                            $arr[$_key] = stripslashes($_str);
                        //} else {
                            //$arr[$_key] = $_str;
                        //}
                    break;

                    default:
                        if (!$_is_magic) {
                            $_str = addslashes($_value);
                        } else {
                            $_str = $_value;
                        }
                        $arr[$_key] = $_str;
                    break;
                }
            }
        }
    } else {
        $arr = array();
    }
    return $arr;
}


function fn_rand($num_rand = 32) {
    $_str_char = "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789";
    $_str_rnd = "";
    while (strlen($_str_rnd) < $num_rand) {
        $_str_rnd .= substr($_str_char, (rand(0, strlen($_str_char))), 1);
    }
    return $_str_rnd;
}


/**
 * fn_get function.
 *
 * @access public
 * @param mixed $key
 * @return void
 */
function fn_get($key) {
    if (isset($_GET[$key])) {
        return $_GET[$key];
    } else {
        return null;
    }
}


/**
 * fn_post function.
 *
 * @access public
 * @param mixed $key
 * @return void
 */
function fn_post($key) {
    if (isset($_POST[$key])) {
        return $_POST[$key];
    } else {
        return null;
    }
}

