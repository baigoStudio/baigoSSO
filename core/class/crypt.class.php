<?php
/*-----------------------------------------------------------------
！！！！警告！！！！
以下为系统文件，请勿修改
-----------------------------------------------------------------*/


//不能非法包含或直接执行
if (!defined("IN_BAIGO")) {
    exit("Access Denied");
}

class CLASS_CRYPT {

    function __construct() { //构造函数
        $this->obj_dir = new CLASS_DIR();
        if (!file_exists(BG_PATH_CACHE . "sys/crypt_key_pub.txt")) {
            $this->obj_dir->put_file(BG_PATH_CACHE . "sys/crypt_key_pub.txt", fn_rand());
        }

        $this->key_pub = file_get_contents(BG_PATH_CACHE . "sys/crypt_key_pub.txt");
    }


    //加密
    function encrypt($string, $key_priv) {
        srand((double)microtime() * 1000000);
        $_str_encrypt   = md5(rand(0, 32000));
        $_ctr           = 0;
        $_str_tmp       = "";

        for ($_iii = 0; $_iii < strlen($string); $_iii++) {
            $_ctr       = $_ctr == strlen($_str_encrypt) ? 0 : $_ctr;
            $_str_tmp  .= $_str_encrypt[$_ctr] . ($string[$_iii] ^ $_str_encrypt[$_ctr++]);
        }

        $_str_return = $this->get_key($_str_tmp, $key_priv);
        $_str_return = base64_encode($_str_return);
        $_str_return = urlencode($_str_return);

        return $_str_return;
    }

    //解密
    function decrypt($string, $key_priv) {
        //$string        = preg_replace("/\s+/i", "", $string);
        $string        = fn_htmlcode($string, "decode", "crypt");
        $string        = urldecode($string);
        $string        = base64_decode($string);
        $string        = $this->get_key($string, $key_priv);
        $_str_return   = "";

        for ($_iii = 0; $_iii < strlen($string); $_iii++) {
            $_str_md5       = $string[$_iii];
            $_str_return   .= $string[++$_iii] ^ $_str_md5;
        }

        return $_str_return;
    }

    private function get_key($string, $key_priv) {
        $_str_key       = md5($this->key_pub . $key_priv);
        $_ctr           = 0;
        $_str_return    = "";

        for ($_iii = 0; $_iii < strlen($string); $_iii++) {
            $_ctr           = $_ctr == strlen($_str_key) ? 0 : $_ctr;
            $_str_return   .= $string[$_iii] ^ $_str_key[$_ctr++];
        }

        return $_str_return;
    }
}