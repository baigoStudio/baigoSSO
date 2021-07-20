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

// Cookie 管理类
class Cookie {

    public static $config = array(); // 配置

    private static $configThis = array( // 默认配置
        'prefix'    => '', // cookie 名称前缀
        'expire'    => 0, // cookie 保存时间
        'path'      => '/', // cookie 保存路径
        'domain'    => '', // cookie 有效域名
        'secure'    => false, //  cookie 启用安全传输
        'httponly'  => true, // httponly 设置
        'setcookie' => true, // 是否使用 setcookie
    );

    private static $init; // 是否初始化标志


    // 初始化
    public static function init($config = array()) {
        self::config($config);

        if (!Func::isEmpty(self::$config['httponly'])) { //设为 httponly
            ini_set('session.cookie_httponly', 1);
        }

        self::$init = true; // 标识为已初始化
    }


    /** 配置
     * prefix function.
     * since 0.2.0
     * @access public
     * @param string $config (default: array()) 配置
     * @return
     */
    public static function config($config = array()) {
        $_arr_config   = Config::get('cookie'); // 取得配置

        $_arr_configDo = self::$configThis;

        if (is_array($_arr_config) && !Func::isEmpty($_arr_config)) {
            $_arr_configDo = array_replace_recursive($_arr_configDo, $_arr_config); // 合并配置
        }

        if (is_array(self::$config) && !Func::isEmpty(self::$config)) {
            $_arr_configDo = array_replace_recursive($_arr_configDo, self::$config); // 合并配置
        }

        if (is_array($config) && !Func::isEmpty($config)) {
            $_arr_configDo = array_replace_recursive($_arr_configDo, $config); // 合并配置
        }

        self::$config  = $_arr_configDo;
    }


    /** 设置前缀
     * prefix function.
     *
     * @access public
     * @static
     * @param string $prefix (default: '') 前缀值
     * @return 如果参数为空则返回当前前缀, 否则无返回
     */
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


    /** 设置 cookie
     * set function.
     *
     * @access public
     * @static
     * @param string $name 名称
     * @param string $value 值
     * @param array $option (default: array()) 选项
     * @return void
     */
    public static function set($name, $value, $option = array()) {
        if (Func::isEmpty(self::$init)) {
            self::init();
        }

        $_arr_config = self::$config;

        if (!Func::isEmpty($option)) {
            $_arr_config = array_replace_recursive($_arr_config, $option); // 合并选项
        }

        $_tm_expire = 0; // 默认过期时间

        if ($_arr_config['expire'] > 0) {
            $_tm_expire = GK_NOW + intval($_arr_config['expire']); // 计算过期时间
        }

        $name = $_arr_config['prefix'] . (string)$name; // 转换名称并拼合前缀

        if ($_arr_config['setcookie']) { // 是否启用 setcookie 函数 (不启用只影响 $_COOKIE 全局变量)
            if (is_array($_arr_config['path'])) {
                foreach ($_arr_config['path'] as $_key=>$_value) {
                    setcookie($name, $value, intval($_tm_expire), $_value, $_arr_config['domain'], $_arr_config['secure'], $_arr_config['httponly']);
                }
            } else if (is_string($_arr_config['path'])) {
                setcookie($name, $value, intval($_tm_expire), $_arr_config['path'], $_arr_config['domain'], $_arr_config['secure'], $_arr_config['httponly']);
            }
        }

        $_COOKIE[$name] = $value;
    }

    /** 取得 cookie
     * get function.
     *
     * @access public
     * @static
     * @param string $name 名称
     * @param string $prefix (default: '') 前缀
     * @return cookie 值
     */
    public static function get($name, $prefix = '') {
        $_value = null; // 默认值

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

        return Func::safe($_value); // 安全过滤
    }


    /** 删除 cookie
     * delete function.
     *
     * @access public
     * @static
     * @param string $name 名称
     * @param string $prefix (default: '') 前缀
     * @return void
     */
    public static function delete($name, $option = array()) {
        if (Func::isEmpty(self::$init)) {
            self::init();
        }

        $_arr_config = self::$config;

        if (!Func::isEmpty($option)) {
            $_arr_config = array_replace_recursive($_arr_config, $option); // 合并选项
        }

        $name = $_arr_config['prefix'] . (string)$name; // 转换名称并拼合前缀

        if (is_array($_arr_config['path'])) {
            foreach ($_arr_config['path'] as $_key=>$_value) {
                setcookie($name, '', intval(GK_NOW - GK_HOUR), $_value, $_arr_config['domain'], $_arr_config['secure'], $_arr_config['httponly']);
            }
        } else if (is_string($_arr_config['path'])) {
            setcookie($name, '', intval(GK_NOW - GK_HOUR), $_arr_config['path'], $_arr_config['domain'], $_arr_config['secure'], $_arr_config['httponly']);
        }

        // 删除指定 cookie
        unset($_COOKIE[$name]);
    }
}
