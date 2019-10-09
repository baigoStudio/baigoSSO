<?php
/*-----------------------------------------------------------------
！！！！警告！！！！
以下为系统文件，请勿修改
-----------------------------------------------------------------*/

namespace ginkgo;

//不能非法包含或直接执行
defined('IN_GINKGO') or exit('Access Denied');

/*-------------插件管理类-------------*/
class Plugin {

    public static $instance = array();

    private static $listeners   = array(); //监听已注册的插件
    private static $init;

    protected function __construct() {

    }

    protected function __clone() {

    }


    /**
    * 注册需要监听的插件方法（钩子）
    *
    * @param string $hook
    * @param object $object
    * @param string $method
    */
    static function add($hook, &$object, $method) {
        if (Func::isEmpty(self::$init)) {
            self::init();
        }

        if (is_object($object)) {
            $_class = get_class($object);

            if (is_array($method)) {
                foreach ($method as $_key=>$_value) {
                    self::$listeners[$hook][$_class . '->' . $_value] = array(
                        'object'    => $object, //对象
                        'method'    => $_value
                    );
                }
            } else {
                self::$listeners[$hook][$_class . '->' . $method] = array(
                    'object'    => $object, //对象
                    'method'    => $method
                );
            }
        }

        //print_r(self::$listeners);
    }

    /**
    * 触发一个钩子
    *
    * @param string $hook 钩子的名称
    * @param mixed $data 钩子的入参
    * @return mixed
    */
    static function listen($hook, $data = '') {
        if (Func::isEmpty(self::$init)) {
            self::init();
        }

        $_result = false;

        //查看要实现的钩子，是否在监听数组之中
        if (isset(self::$listeners[$hook]) && is_array(self::$listeners[$hook]) && count(self::$listeners[$hook]) > 0) {
            //循环调用开始
            foreach (self::$listeners[$hook] as $_key=>$_value) {
                //取出插件对象的引用和方法
                if (method_exists($_value['object'], $_value['method'])) {
                    //动态调用插件的方法
                    $_result[$_key] = call_user_func(array($_value['object'], $_value['method']), $data);
                }
            }
        }

        return $_result;
    }


    static function resultProcess($data, $result) {
        if (!Func::isEmpty($result)) {
            foreach ($result as $_key=>$_value) {
                if (!Func::isEmpty($_value)) {
                    if (is_array($_value)) {
                        $data = array_replace_recursive($data, $_value);
                    } else if (is_scalar($_value)) {
                        $data = $_value;
                    }
                }
            }
        }

        return $data;
    }


    private static function init() {
        self::$init = true;

        $_arr_plugins = Config::get('plugin');

        if (Func::isEmpty($_arr_plugins)) {
            $_str_pathPlugin = GK_APP_CONFIG . 'plugin' . GK_EXT_INC;

            if (Func::isFile($_str_pathPlugin)) {
                $_arr_plugins = Config::load($_str_pathPlugin, 'plugin');
            }
        }

        if (!Func::isEmpty($_arr_plugins)) {
            $_obj_file = File::instance(); //初始化文件对象

            foreach($_arr_plugins as $_key=>$_value) {
                if (is_numeric($_key)) {
                    if (is_array($_value)) {
                        if (isset($_value['dir'])) {
                            $_str_dir = $_value['dir'];
                        }
                    } else if (is_string($_value)) {
                        $_str_dir = $_value;
                    }
                } else {
                    $_str_dir = $_key;
                }


                $_str_configPath = GK_PATH_PLUGIN . $_str_dir . DS . 'config.json';

                if (Func::isFile($_str_configPath)) {
                    $_str_pluginConfig  = $_obj_file->fileRead($_str_configPath);
                    $_arr_pluginConfig  = Json::decode($_str_pluginConfig);
                } else {
                    $_arr_pluginConfig = array();
                }

                if (isset($_arr_pluginConfig['class']) && !Func::isEmpty($_arr_pluginConfig['class'])) {
                    $_str_class = $_arr_pluginConfig['class'];
                } else {
                    $_str_class = $_str_dir;
                }

                if (strpos($_str_class, '\\')) {
                    $_str_plugin = $_str_class;
                } else {
                    $_str_plugin = 'extend\\plugin\\' . $_str_dir . '\\' . Func::ucwords($_str_class, '_');
                }

                if (class_exists($_str_plugin)) {
                    //初始化所有插件
                    $_pid = md5($_str_plugin);
                    static::$instance[$_pid] = new $_str_plugin(); //实例化

                    if (!Func::isEmpty($_arr_pluginConfig)) {
                        static::$instance[$_pid]->config = $_arr_pluginConfig;
                    }

                    $_str_optsPath   = GK_PATH_PLUGIN . $_str_dir . DS . 'opts_var.json';

                    if (Func::isFile($_str_optsPath)) {
                        $_str_pluginOpts  = $_obj_file->fileRead($_str_optsPath);
                        static::$instance[$_pid]->opts = Json::decode($_str_pluginOpts); //定义选项
                    }
                }
            }
        }
    }
}