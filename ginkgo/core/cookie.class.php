<?php
/*-----------------------------------------------------------------
！！！！警告！！！！
以下为系统文件，请勿修改
-----------------------------------------------------------------*/

namespace ginkgo;

//不能非法包含或直接执行
defined('IN_GINKGO') or exit('Access denied');

class Cookie {

    protected static $instance;

    private static $this_config = array(
        'prefix'    => '', // cookie 名称前缀
        'expire'    => 0, // cookie 保存时间
        'path'      => '/', // cookie 保存路径
        'path_arr'  => array(), // cookie 保存路径 数组
        'domain'    => '', // cookie 有效域名
        'secure'    => false, //  cookie 启用安全传输
        'httponly'  => true, // httponly 设置
        'setcookie' => true, // 是否使用 setcookie
    );

    private static $config;

    private static $init;

    protected function __construct() {
    }


    protected function __clone() {

    }


    public static function prefix($prefix = '') {
        if (Func::isEmpty(self::$init)) {
            self::init();
        }

        if (Func::isEmpty($prefix)) {
            return self::$config['prefix'];
        } else {
            self::$config['prefix'] = $prefix;
        }
    }


    public static function init($config = array()) {
        $_arr_config  = Config::get('cookie');

        self::$config = array_replace_recursive(self::$this_config, $_arr_config);

        if (!Func::isEmpty($config)) {
            self::$config = array_replace_recursive(self::$config, $config);
        }

        if (!Func::isEmpty(self::$config['httponly'])) {
            ini_set('session.cookie_httponly', 1);
        }

        self::$init = true;
    }


    public static function set($name, $value, $option = array()) {
        if (Func::isEmpty(self::$init)) {
            self::init();
        }

        $_arr_config = self::$config;

        $name = $_arr_config['prefix'] . $name;

        $_tm_expire = 0;

        if (!Func::isEmpty($option)) {
            $_arr_config = array_replace_recursive($_arr_config, $option);
        }

        if ($_arr_config['expire'] > 0) {
            $_tm_expire = GK_NOW + intval($_arr_config['expire']);
        }

        if ($_arr_config['setcookie']) {
            if (Func::isEmpty($_arr_config['path_arr'])) {
                setcookie($name, $value, $_tm_expire, $_arr_config['path'], $_arr_config['domain'], $_arr_config['secure'], $_arr_config['httponly']);
            } else {
                foreach ($_arr_config['path_arr'] as $_key=>$_value) {
                    setcookie($name, $value, $_tm_expire, $_value, $_arr_config['domain'], $_arr_config['secure'], $_arr_config['httponly']);
                }
            }
        }

        $_COOKIE[$name] = $value;
    }

    public static function get($name, $prefix = '') {
        $_value = null;

        if (Func::isEmpty(self::$init)) {
            self::init();
        }

        $_arr_config = self::$config;

        if (Func::isEmpty($prefix)) {
            $prefix = $_arr_config['prefix'];
        }

        $name = $prefix . $name;

        /*print_r($name);
        print_r('<br>');*/

        if (isset($_COOKIE[$name])) {
            $_value = $_COOKIE[$name];
        }

        return Func::safe($_value);
    }


    public static function delete($name, $prefix = '') {
        if (Func::isEmpty(self::$init)) {
            self::init();
        }

        $_arr_config = self::$config;

        if (Func::isEmpty($prefix)) {
            $prefix = $_arr_config['prefix'];
        }

        $name = $prefix . $name;

        if (Func::isEmpty($_arr_config['path_arr'])) {
            setcookie($name, '', GK_NOW - GK_HOUR, $_arr_config['path'], $_arr_config['domain'], $_arr_config['secure'], $_arr_config['httponly']);
        } else {
            foreach ($_arr_config['path_arr'] as $_key=>$_value) {
                setcookie($name, '', GK_NOW - GK_HOUR, $_value, $_arr_config['domain'], $_arr_config['secure'], $_arr_config['httponly']);
            }
        }

        // 删除指定 cookie
        unset($_COOKIE[$name]);
    }
}