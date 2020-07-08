<?php
/*-----------------------------------------------------------------
！！！！警告！！！！
以下为系统文件，请勿修改
-----------------------------------------------------------------*/

namespace ginkgo;

// 不能非法包含或直接执行
defined('IN_GINKGO') or exit('Access denied');

// 会话处理
class Session {

    private static $init; // 是否初始化标志

    // 默认配置
    private static $this_config = array(
        'type'      => 'file',
        'autostart' => false,
        'name'      => '',
        'path'      => '',
    );

    private static $prefix = ''; // 前缀

    protected function __construct() {
    }


    protected function __clone() {

    }

    /** 初始化
     * init function.
     *
     * @access public
     * @static
     * @param array $config (default: array()) 会话配置
     * @return void
     */
    public static function init($config = array()) {
        $_do_start = false;

        $_arr_config    = Config::get('session');

        if (!Func::isEmpty($config)) {
            $_arr_config = array_replace_recursive(self::$this_config, $_arr_config, $config); // 合并配置
        }

        if (isset($_arr_config['type']) && !Func::isEmpty($_arr_config['type']) && $_arr_config['type'] != 'file') { // 如果指定了驱动类型, 且不是文件类型
            if (strpos($_arr_config['type'], '\\')) { // 如果类型包含命名空间则直接使用
                $_class = $_arr_config['type'];
            } else { // 否则补全命名空间
                $_class = 'ginkgo\\session\\driver\\' . Func::ucwords($_arr_config['type'], '_');
            }

            // 检查驱动类
            if (class_exists($_class)) {
                $_obj_session = $_class::instance(); // 实例化驱动

                $_arr_return = session_set_save_handler(array($_obj_session, 'open'), array($_obj_session, 'close'), array($_obj_session, 'read'), array($_obj_session, 'write'), array($_obj_session, 'destroy'), array($_obj_session, 'gc')); // 定义处理函数
            } else { // 报错
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

        if ($_arr_config['autostart'] === true && Func::isEmpty(session_id())) {
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

    /** 引导
     * boot function.
     *
     * @access public
     * @static
     * @param array $config (default: array()) 会话配置
     * @return void
     */
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

    /** 设置, 获取前缀
     * prefix function.
     *
     * @access public
     * @static
     * @param string $prefix (default: '')
     * @return 如果参数为空则返回前缀, 否则无返回
     */
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


    /** 设置会话变量
     * set function.
     *
     * @access public
     * @static
     * @param mixed $name 名称
     * @param mixed $value 值
     * @param string $prefix (default: '') 前缀
     * @return void
     */
    static function set($name, $value, $prefix = '') {
        if (Func::isEmpty(self::$init)) {
            //print_r('boot');
            self::boot();
        }

        $_arr_prefix = self::prefixProcess($prefix); // 前缀处理

        if (!Func::isEmpty($_arr_prefix[0]) && isset($_arr_prefix[1]) && !Func::isEmpty($_arr_prefix[1])) {
            $_SESSION[$_arr_prefix[0]][$_arr_prefix[1]][$name] = $value;
        } else if (!Func::isEmpty($_arr_prefix[0])) {
            $_SESSION[$_arr_prefix[0]][$name] = $value;
        } else {
            $_SESSION[$name] = $value;
        }

        //print_r($_SESSION);
    }


    /** 读取会话变量
     * get function.
     *
     * @access public
     * @static
     * @param mixed $name 名称
     * @param string $prefix (default: '') 前缀
     * @return 变量
     */
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


    /** 删除会话变量
     * delete function.
     *
     * @access public
     * @static
     * @param mixed $name 名称
     * @param string $prefix (default: '') 前缀
     * @return void
     */
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


    /** 前缀处理
     * prefixProcess function.
     *
     * @access private
     * @static
     * @param string $prefix (default: '') 前缀
     * @return 前缀
     */
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