<?php
/*-----------------------------------------------------------------
！！！！警告！！！！
以下为系统文件，请勿修改
-----------------------------------------------------------------*/

namespace ginkgo;

// 不能非法包含或直接执行
if (!defined('IN_GINKGO')) {
    return 'Access denied';
}

// 加密解密 需要 openssl 支持
class Crypt {

    public static $error; // 错误

    private static $init; // 是否初始化标志
    private static $keyPub; // 公钥

    public static function init() {
        $_str_pathKey = GK_PATH_DATA . 'key_pub' . GK_EXT_INC;

        if (!File::fileHas($_str_pathKey)) { // 如果没有公钥, 则生成一个
            $_num_size   = Config::write($_str_pathKey, Func::rand());
        }

        self::$keyPub   = Loader::load($_str_pathKey); // 载入公钥
        self::$init     = true; // 标识为已初始化
    }


    /** 非对称加密 (不可逆)
     * crypt function.
     *
     * @access public
     * @static
     * @param string $str 待加密
     * @param string $salt 盐
     * @param bool $is_md5 (default: false) 是否为 md5
     * @param int $crypt_type (default: 2) 加密类型 (历史技术债务, 向下兼容)
     * @return 加密后字符串
     */
    public static function crypt($str, $salt, $is_md5 = false, $crypt_type = 2) {
        $str = (string)$str;

        if (Func::isEmpty(self::$init)) {
            self::init();
        }

        if ($is_md5) {
            $_str = $str;
        } else {
            $_str = md5($str);
        }

        $_salt      = md5($salt); // 用 md5 加密盐
        $_key_pub   = md5(self::$keyPub); // 用 md5 加密公钥

        switch ($crypt_type) {
            case 0:
                $_str_return = md5($_str . $salt); // 保留历史加密方式
            break;

            case 1:
                $_str_return = md5($_str . $salt . self::$keyPub); // 保留历史加密方式
            break;

            default:
                $_str_return = sha1($_key_pub . $_salt . sha1(md5($_str)) . $_salt . $_key_pub); // 初步加密
                $_str_return = crypt($_str_return, $_salt); // php 内置加密
                $_str_return = md5($_str_return); // 最终加密
            break;
        }

        return $_str_return;
    }


    /** 对称加密 (可解密)
     * encrypt function.
     *
     * @access public
     * @param string $string 待加密
     * @param string $key 加密码
     * @param string $iv 初始化向量
     * @return 加密后字符串
     */
    public static function encrypt($string, $key, $iv) {
        $string = (string)$string;

        if (strlen($iv) != 16) {
            self::$error = 'Size of Secret code must be 16';
            return false;
        }

        $_str_encrypt = openssl_encrypt($string, 'AES-128-CBC', $key, 1, $iv); // 加密

        return Strings::toBase64($_str_encrypt); // base64 编码
    }


    /** 对称解密
     * decrypt function.
     *
     * @access public
     * @param string $string 待解密
     * @param string $key 解密码
     * @param string $iv 初始化向量
     * @return 解密后字符串
     */
    public static function decrypt($string, $key, $iv) {
        if (strlen($iv) != 16) {
            self::$error = 'Size of Secret code must be 16';
            return false;
        }

        $string = Strings::fromBase64($string); // base64 解码

        return openssl_decrypt($string, 'AES-128-CBC', $key, 1, $iv); // 解密
    }

    /** 取得错误
     * getError function.
     *
     * @access public
     * @static
     * @return 错误内容
     */
    public static function getError() {
        return self::$error;
    }
}
