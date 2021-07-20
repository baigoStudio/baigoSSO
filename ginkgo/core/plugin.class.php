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

// 插件管理类
class Plugin {

    protected static $instance = array(); // 用静态属性保存实例

    private static $listeners = array(); // 监听已注册的插件
    private static $obj_file; // 文件操作实例
    private static $init; // 是否初始化标志


    /** 向钩子注册动作
     * add function.
     *
     * @access public
     * @static
     * @param string $hook 钩子名称
     * @param object &$object 插件实例
     * @param mixed $method 插件方法
     * @return void
     */
    public static function add($hook, &$object, $method) {
        if (Func::isEmpty(self::$init)) {
            self::init();
        }

        if (is_object($object)) { // 只有实例参数为实例是才触发
            $_class = get_class($object); // 获取类名

            if (is_array($method)) { // 如果方法为数组
                foreach ($method as $_key=>$_value) { // 遍历方法
                    // 注册动作
                    self::$listeners[$hook][$_class . '->' . $_value] = array(
                        'object'    => $object, //对象
                        'method'    => $_value
                    );
                }
            } else if (is_string($method)) {
                // 注册动作
                self::$listeners[$hook][$_class . '->' . $method] = array(
                    'object'    => $object, //对象
                    'method'    => $method
                );
            }
        }

        //print_r(self::$listeners);
    }


    /** 埋钩子并触发已注册的动作
     * listen function.
     *
     * @access public
     * @static
     * @param string $hook 钩子名称
     * @param mixed $data (default: '') 数据
     * @return void
     */
    public static function listen($hook, $data = '') {
        if (Func::isEmpty(self::$init)) {
            self::init();
        }

        //$_result = false;

        // 查看要实现的钩子, 是否在监听数组之中
        if (isset(self::$listeners[$hook]) && is_array(self::$listeners[$hook]) && count(self::$listeners[$hook]) > 0) {
            // 循环调用开始
            foreach (self::$listeners[$hook] as $_key=>$_value) {
                // 取出插件对象的引用和方法
                if (method_exists($_value['object'], $_value['method'])) {
                    // 动态调用插件的方法
                    $data = call_user_func(array($_value['object'], $_value['method']), $data);
                }
            }
        }

        return $data;
    }


    /** 初始化
     * init function.
     *
     * @access private
     * @static
     * @return void
     */
    private static function init() {
        self::$init = true; // 标识为已初始化

        $_str_pathPlugin    = GK_APP_CONFIG . 'plugin' . GK_EXT_INC;
        $_arr_plugins       = Config::load($_str_pathPlugin, 'plugin'); // 加载注册文件

        if (Func::isEmpty($_arr_plugins)) { // 如果注册数据为空
            $_arr_plugins = Config::get('plugin'); // 取得插件注册数据
        }

        if (!Func::isEmpty($_arr_plugins)) { // 遍历插件
            self::$obj_file = File::instance(); // 初始化文件对象

            foreach($_arr_plugins as $_key=>$_value) {
                $_str_dir           = self::dirProcess($_key, $_value);
                $_arr_pluginConfig  = self::configProcess($_str_dir);
                $_str_plugin        = self::namespaceProcess($_str_dir, $_arr_pluginConfig);

                if (class_exists($_str_plugin)) {
                    // 初始化所有插件
                    $_pid = md5($_str_plugin);
                    self::$instance[$_pid] = new $_str_plugin(); // 实例化

                    if (!Func::isEmpty($_arr_pluginConfig)) {
                        self::$instance[$_pid]->config = $_arr_pluginConfig;
                    }

                    $_str_optsPath   = GK_PATH_PLUGIN . $_str_dir . DS . 'opts_var.json'; // 用户设置文件

                    if (File::fileHas($_str_optsPath)) {
                        $_str_pluginOpts             = self::$obj_file->fileRead($_str_optsPath); // 读取用户设置
                        self::$instance[$_pid]->opts = Arrays::fromJson($_str_pluginOpts); // 解码用户设置
                    }
                }
            }
        }
    }


    /** 配置处理
     * configProcess function.
     *
     * @access private
     * @static
     * @param string $dir 目录名
     * @return void
     */
    private static function configProcess($dir) {
        $dir = (string)$dir;

        $_str_configPath = GK_PATH_PLUGIN . $dir . DS . 'config.json'; // 插件配置文件

        if (File::fileHas($_str_configPath)) {
            $_str_pluginConfig  = self::$obj_file->fileRead($_str_configPath); // 读取插件配置
            $_arr_pluginConfig  = Arrays::fromJson($_str_pluginConfig); // 解码配置
        } else {
            $_arr_pluginConfig = array();
        }

        return $_arr_pluginConfig;
    }


    /** 目录名处理
     * dirProcess function.
     *
     * @access private
     * @static
     * @param mixed $key 键名
     * @param mixed $value 键值
     * @return void
     */
    private static function dirProcess($key, $value) {
        if (is_numeric($key)) { // 如果键名为数字, 则以键值为准
            if (is_array($value)) { // 如果键值为数组, 则查找 dir 元素是否存在
                if (isset($value['dir'])) { // dir 元素存在才视为有效路径
                    $_str_dir = $value['dir'];
                }
            } else if (is_string($value)) { // 如果是字符串, 直接作为路径
                $_str_dir = $value;
            }
        } else {
            $_str_dir = $key; // 如果键名是字符串, 直接作为路径
        }

        return $_str_dir;
    }


    /** 命名空间处理
     * namespaceProcess function.
     *
     * @access private
     * @static
     * @param string $dir 目录名
     * @param mixed $config 配置
     * @return 类名
     */
    private static function namespaceProcess($dir, $config) {
        if (isset($config['class']) && !Func::isEmpty($config['class'])) { // 如果定义了类名
            $_str_class = $config['class']; // 以定义的为准
        } else {
            $_str_class = $dir; // 未定义类名, 直接以文件夹名为准
        }

        if (strpos($_str_class, '\\') === false) { // 如果未定义命名空间
            $_str_class = 'extend\\plugin\\' . $dir . '\\' . Strings::ucwords($_str_class, '_'); // 补全命名空间
        }

        return $_str_class;
    }
}
