<?php
/*-----------------------------------------------------------------
！！！！警告！！！！
以下为系统文件，请勿修改
-----------------------------------------------------------------*/


//不能非法包含或直接执行
if (!defined('IN_BAIGO')) {
    exit('Access Denied');
}

class CLASS_CRYPT {

    /** 加密
     * encrypt function.
     *
     * @access public
     * @param mixed $string
     * @param mixed $key_appKey
     * @param mixed $key_appSecret
     * @return void
     */
    function encrypt($string, $key_appKey, $key_appSecret) {
        if (strlen($key_appSecret) != 16) {
            return array(
                'rcode' => 'x030308',
            );
        }

        $_str_encrypt   = openssl_encrypt($string, 'AES-128-CBC', $key_appKey, 1, $key_appSecret);

        $_str_encrypt   = base64_encode($_str_encrypt);

        $_str_encrypt   = str_ireplace('=', '|', $_str_encrypt);
        $_str_encrypt   = str_ireplace('/', '@', $_str_encrypt);
        $_str_encrypt   = str_ireplace('+', '_', $_str_encrypt);

        return array(
            'rcode'     => 'ok',
            'encrypt'   => $_str_encrypt,
        );
    }


    /** 解密
     * decrypt function.
     *
     * @access public
     * @param mixed $string
     * @param mixed $key_appKey
     * @param mixed $key_appSecret
     * @return void
     */
    function decrypt($string, $key_appKey, $key_appSecret) {
        if (strlen($key_appSecret) != 16) {
            return array(
                'rcode' => 'x030308',
            );
        }

        $string         = str_ireplace('|', '=', $string);
        $string         = str_ireplace('@', '/', $string);
        $string         = str_ireplace('_', '+', $string);
        $string         = base64_decode($string);

        $_str_decrypt   = openssl_decrypt($string, 'AES-128-CBC', $key_appKey, 1, $key_appSecret);

        return array(
            'rcode'     => 'ok',
            'decrypt'   => $_str_decrypt,
        );
    }
}