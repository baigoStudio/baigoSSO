<?php
/*-----------------------------------------------------------------
！！！！警告！！！！
以下为系统文件，请勿修改
-----------------------------------------------------------------*/


//不能非法包含或直接执行
if(!defined("IN_BAIGO")) {
    exit("Access Denied");
}

//加密
function fn_baigoEncode($txt, $key) {
    srand((double)microtime() * 1000000);
    $encrypt_key   = md5(rand(0, 32000));
    $ctr           = 0;
    $tmp           = "";
    for($i = 0; $i < strlen($txt); $i++) {
        $ctr  = $ctr == strlen($encrypt_key) ? 0 : $ctr;
        $tmp .= $encrypt_key[$ctr] . ($txt[$i] ^ $encrypt_key[$ctr++]);
    }
    return urlencode(base64_encode(fn_baigoKey($tmp, $key)));
}

//解密
function fn_baigoDecode($txt, $key) {
    $txt   = fn_baigoKey(base64_decode(urldecode($txt)), $key);
    $tmp   = "";
    for($i = 0; $i < strlen($txt); $i++) {
        $md5  = $txt[$i];
        $tmp .= $txt[++$i] ^ $md5;
    }
    return $tmp;
}

function fn_baigoKey($txt, $encrypt_key) {
    $encrypt_key   = md5($encrypt_key);
    $ctr           = 0;
    $tmp           = "";
    for($i = 0; $i < strlen($txt); $i++) {
        $ctr  = $ctr == strlen($encrypt_key) ? 0 : $ctr;
        $tmp .= $txt[$i] ^ $encrypt_key[$ctr++];
    }
    return $tmp;
}

//生成签名
function fn_baigoSignMk($tm_time, $str_rand, $num_appId, $str_appKey) {
    $_num_time  = intval($tm_time);
    $_num_appId = intval($num_appId);
    $_arr_temp  = array($_num_time, $str_rand, $num_appId, $str_appKey);
    sort($_arr_temp);
    $_str_temp  = implode($_arr_temp);
    $_str_temp  = sha1($_str_temp);

    return $_str_temp;
}

//验证签名
function fn_baigoSignChk($tm_time, $str_rand, $num_appId, $str_appKey, $str_sign) {
    $_str_temp = fn_baigoSignMk($tm_time, $str_rand, $num_appId, $str_appKey);

    if ($_str_temp == $str_sign) {
        return true;
    } else {
        return false;
    }
}
