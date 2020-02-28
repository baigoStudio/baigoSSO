<?php
/*-----------------------------------------------------------------
！！！！警告！！！！
以下为系统文件，请勿修改
-----------------------------------------------------------------*/

namespace ginkgo;

//不能非法包含或直接执行
defined('IN_GINKGO') or exit('Access denied');

class Crypt {

    private static $init;
    private static $keyPub;
    private static $error;

    static function init() {
        $_str_pathKey = GK_PATH_DATA . 'key_pub' . GK_EXT_INC;

        if (!Func::isFile($_str_pathKey)) {
            $_num_size   = Config::write($_str_pathKey, Func::rand());
        }

        self::$keyPub   = Loader::load($_str_pathKey);
        self::$init     = true;
    }


    /** 非对称加密
     * encrypt function.
     *
     * @access public
     * @param mixed $string
     * @param mixed $key
     * @param mixed $iv
     * @return void
     */
    static function crypt($str, $salt, $is_md5 = false, $crypt_type = 2) {
        if (Func::isEmpty(self::$init)) {
            self::init();
        }

        if ($is_md5) {
            $_str = $str;
        } else {
            $_str = md5($str);
        }

        $_salt      = md5($salt); //用 md5 加密盐
        $_key_pub   = md5(self::$keyPub); //用 md5 加密公钥

        switch ($crypt_type) {
            case 0:
                $_str_return = md5($_str . $salt); //保留历史加密方式
            break;

            case 1:
                $_str_return = md5($_str . $salt . self::$keyPub); //保留历史加密方式
            break;

            default:
                $_str_return = sha1($_key_pub . $_salt . sha1(md5($_str)) . $_salt . $_key_pub); //初步加密
                $_str_return = crypt($_str_return, $_salt); //php 内置加密
                $_str_return = md5($_str_return); //最终加密
            break;
        }

        return $_str_return;
    }


    /** 对称加密
     * encrypt function.
     *
     * @access public
     * @param mixed $string
     * @param mixed $key
     * @param mixed $iv
     * @return void
     */
    static function encrypt($string, $key, $iv) {
        if (strlen($iv) != 16) {
            static::$error = 'Size of Secret code must be 16';
            return false;
        }

        $_str_encrypt = openssl_encrypt($string, 'AES-128-CBC', $key, 1, $iv);

        return Base64::encode($_str_encrypt);
    }


    /** 对称解密
     * decrypt function.
     *
     * @access public
     * @param mixed $string
     * @param mixed $key
     * @param mixed $iv
     * @return void
     */
    static function decrypt($string, $key, $iv) {
        if (strlen($iv) != 16) {
            static::$error = 'Size of Secret code must be 16';
            return false;
        }

        $string = Base64::decode($string);

        return openssl_decrypt($string, 'AES-128-CBC', $key, 1, $iv);
    }


    static function getError() {
        return static::$error;
    }
}