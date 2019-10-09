<?php
/*-----------------------------------------------------------------
！！！！警告！！！！
以下为系统文件，请勿修改
-----------------------------------------------------------------*/

namespace ginkgo;

//不能非法包含或直接执行
defined('IN_GINKGO') or exit('Access denied');

/*------会话模型------*/
class Session {

    protected static $instance;
    private static $init;

    private static $this_config = array(
        'type'      => 'file',
        'autostart' => false,
        'name'      => '',
    );

    private static $prefix = '';

    protected function __construct() {
    }


    protected function __clone() {

    }


    public static function prefix($prefix = '') {
        if (Func::isEmpty(self::$init)) {
            self::boot();
        }

        if (Func::isEmpty($prefix)) {
            return self::$prefix;
        } else {
            self::$prefix = $prefix;
        }
    }

    public static function init($config = array()) {
        $_arr_config   = Config::get('session');

        $_do_start = false;

        $_arr_config = array_replace_recursive(self::$this_config, $_arr_config);

        if (!Func::isEmpty($config)) {
            $_arr_config = array_replace_recursive($_arr_config, $config);
        }

        if (isset($_arr_config['type']) && !Func::isEmpty($_arr_config['type']) && $_arr_config['type'] != 'file') {
            // 读取session驱动

            if (strpos($_arr_config['type'], '\\')) {
                $_class = $_arr_config['type'];
            } else {
                $_class = 'ginkgo\\session\\driver\\' . Func::ucwords($_arr_config['type'], '_');
            }

            // 检查驱动类
            if (class_exists($_class)) {
                $_obj_session = $_class::instance();

                $_arr_return = session_set_save_handler(array($_obj_session, 'open'), array($_obj_session, 'close'), array($_obj_session, 'read'), array($_obj_session, 'write'), array($_obj_session, 'destroy'), array($_obj_session, 'gc'));
            } else {
                $_obj_excpt = new Exception('Session driver not found', 500);

                $_obj_excpt->setData('err_detail', $_class);

                throw $_obj_excpt;
            }
        } else {
            if (Func::isEmpty($_arr_config['path'])) {
                $_str_sessionPath = GK_PATH_SESSION;
            } else {
                $_str_sessionPath = $_arr_config['path'];
            }

            File::instance()->dirMk($_str_sessionPath);

            session_save_path($_str_sessionPath);
        }

        if (!Func::isEmpty($_arr_config['cookie_domain'])) {
            ini_set('session.cookie_domain', $_arr_config['cookie_domain']);
        }

        if (!Func::isEmpty($_arr_config['prefix'])) {
            self::$prefix = $_arr_config['prefix'];
        }

        if (isset($_arr_config['name']) && !Func::isEmpty($_arr_config['name'])) {
            session_name($_arr_config['name']);
        }

        //print_r(session_id());

        if ($_arr_config['autostart'] == true && Func::isEmpty(session_id())) {
            //print_r('auto_start');
            ini_set('session.auto_start', 0);
            $_do_start = true;
        }

        if ($_do_start) {
            //print_r('auto_start');
            session_start();
            self::$init = true;
        } else {
            self::$init = false;
        }
    }

    public static function boot($config = array()) {
        if (Func::isEmpty(self::$init)) {
            //print_r('init');

            self::init($config);
        } else if (self::$init === false) {
            if (Func::isEmpty(session_id())) {
                session_start();
            }

            self::$init = true;
        }
    }

    static function set($name, $value, $prefix = '') {
        if (Func::isEmpty(self::$init)) {
            //print_r('boot');
            self::boot();
        }

        $_arr_prefix = self::prefixProcess($prefix);

        if (!Func::isEmpty($_arr_prefix[0]) && isset($_arr_prefix[1]) && !Func::isEmpty($_arr_prefix[1])) {
            $_SESSION[$_arr_prefix[0]][$_arr_prefix[1]][$name] = $value;
        } else if (!Func::isEmpty($_arr_prefix[0])) {
            $_SESSION[$_arr_prefix[0]][$name] = $value;
        } else {
            $_SESSION[$name] = $value;
        }

        //print_r($_SESSION);
    }

    static function get($name, $prefix = '') {
        $_value = null;

        if (Func::isEmpty(self::$init)) {
            //print_r('boot');
            self::boot();
        }

        $_arr_prefix = self::prefixProcess($prefix);

        /*print_r($_arr_prefix);
        print_r('<br>');*/

        if (!Func::isEmpty($_arr_prefix[0]) && isset($_arr_prefix[1]) && !Func::isEmpty($_arr_prefix[1]) && isset($_SESSION[$_arr_prefix[0]][$_arr_prefix[1]][$name])) {
            $_value = $_SESSION[$_arr_prefix[0]][$_arr_prefix[1]][$name];
        } else if (!Func::isEmpty($_arr_prefix[0]) && isset($_SESSION[$_arr_prefix[0]][$name])) {
            $_value = $_SESSION[$_arr_prefix[0]][$name];
        } else if (isset($_SESSION[$name])) {
            $_value = $_SESSION[$name];
        }

        //print_r($_value);

        return $_value;
    }


    static function delete($name, $prefix = '') {
        if (Func::isEmpty(self::$init)) {
            self::boot();
        }

        $_arr_prefix = self::prefixProcess($prefix);

        if (!Func::isEmpty($_arr_prefix[0]) && isset($_arr_prefix[1]) && !Func::isEmpty($_arr_prefix[1]) && isset($_SESSION[$_arr_prefix[0]][$_arr_prefix[1]][$name])) {
            unset($_SESSION[$_arr_prefix[0]][$_arr_prefix[1]][$name]);
        } else if (!Func::isEmpty($_arr_prefix[0]) && isset($_SESSION[$_arr_prefix[0]][$name])) {
            unset($_SESSION[$_arr_prefix[0]][$name]);
        } else if (isset($_SESSION[$name])) {
            unset($_SESSION[$name]);
        }
    }


    private static function prefixProcess($prefix = '') {
        if (Func::isEmpty($prefix)) {
            $_str_prefix = self::$prefix;
        } else {
            $_str_prefix = $prefix;
        }

        $_arr_prefix = explode('.', $_str_prefix);

        return $_arr_prefix;
    }
}