<?php
/*-----------------------------------------------------------------
！！！！警告！！！！
以下为系统文件，请勿修改
-----------------------------------------------------------------*/

namespace ginkgo;

// 不能非法包含或直接执行
defined('IN_GINKGO') or exit('Access denied');

// 加载管理类
class Loader {

    protected static $instance = array(); // 用静态属性保存实例

    /** 加载 (替代优化系统的几个加载函数)
     * load function.
     *
     * @access public
     * @static
     * @param mixed $path
     * @param string $type (default: 'require')
     * @return void
     */
    static function load($path, $type = 'require') {
        /*print_r($path);
        print_r(' ::: ');
        print_r(Func::isFile(strtolower($path)));
        print_r('<br>');
        print_r(PHP_EOL);*/

        if (!empty($path) && is_file(strtolower($path))) {
            $path = strtolower($path);

            switch ($type) {
                case 'include':
                    return include($path);
                break;

                case 'include_once':
                    return include_once($path);
                break;

                case 'require_once':
                    return require_once($path);
                break;

                default:
                    return require($path);
                break;
            }
        }

        return '';
    }


    /** 注册自动加载函数
     * register function.
     *
     * @access public
     * @static
     * @return void
     */
    static function register() {
        // 自动加载
        spl_autoload_register(array(__CLASS__, 'autoload'), true, true);

        if (Func::isFile(GK_PATH_VENDOR . 'autoload.php')) {
            self::load(GK_PATH_VENDOR . 'autoload.php'); // 加载 composer
        }
    }


    /** 自动加载函数
     * autoload function.
     *
     * @access private
     * @static
     * @param mixed $class_name
     * @return void
     */
    private static function autoload($class_name) {
        $_str_path = self::getPath($class_name); // 根据类名分析路径

        /*print_r($_str_path);
        print_r('<br>');
        print_r(PHP_EOL);*/

        self::load($_str_path);

        return true;
    }


    /** 实例化控制器
     * ctrl function.
     *
     * @access public
     * @static
     * @param string $class 控制器名 (类名)
     * @param string $layer (default: '') 分层
     * @param mixed $mod (default: true) 模块 (true 为 当前模块, false 为 控制器根目录, 或直接指定模块名)
     * @param array $option (default: array()) 选项 (向控制器的构造函数传输参数)
     * @return 控制器实例
     */
    public static function ctrl($class, $layer = '', $mod = true, $option = array()) {
        $_str_namespace = self::namespaceProcess($class, $layer, $mod, 'ctrl');
        $_str_ctrl      = $_str_namespace . Func::ucwords($class, '_');

        $_str_ctrlEmpty = $_str_namespace . 'C_Empty'; // 空控制器名

        /*print_r($_str_ctrl);
        print_r('<br>');*/

        if (class_exists($_str_ctrl)) { // 如果控制器存在, 直接实例化
            $_cid = md5($_str_ctrl);
            static::$instance[$_cid] = new $_str_ctrl($option); // 实例化控制器

            return static::$instance[$_cid];
        } else if (class_exists($_str_ctrlEmpty)) { // 如果控制器不存在, 实例化空控制器
            $_cid = md5($_str_ctrlEmpty);
            static::$instance[$_cid] = new $_str_ctrlEmpty($option); // 实例化空控制器

            return static::$instance[$_cid];
        } else { // 都不存在报错
            $_obj_excpt = new Exception('Controller not found', 404);
            $_obj_excpt->setData('err_detail', $_str_ctrl);

            throw $_obj_excpt;
        }
    }


    /** 实例化模型
     * ctrl function.
     *
     * @access public
     * @static
     * @param string $class 模型名 (类名)
     * @param string $layer (default: '') 分层
     * @param mixed $mod (default: true) 模块 (true 为 当前模块, false 为 模型根目录, 或直接指定模块名)
     * @param array $option (default: array()) 选项 (向模型的构造函数传输参数)
     * @return 模型实例
     */
    public static function model($class, $layer = '', $mod = true, $option = array()) {
        $_str_namespace = self::namespaceProcess($class, $layer, $mod, 'model');
        $_str_mdl       = $_str_namespace . Func::ucwords($class, '_');

        if (class_exists($_str_mdl)) { // 如果模型存在, 直接实例化
            $_mid = md5($_str_mdl);
            static::$instance[$_mid] = new $_str_mdl($option); //实例化模型
            return static::$instance[$_mid];
        } else { // 不存在报错
            $_obj_excpt = new Exception('Model not found', 500);
            $_obj_excpt->setData('err_detail', $_str_mdl);

            throw $_obj_excpt;
        }
    }


    /** 实例化验证器
     * validate function.
     *
     * @access public
     * @static
     * @param string $class 验证器名 (类名)
     * @param string $layer (default: '') 分层
     * @param mixed $mod (default: true) 模块 (true 为 当前模块, false 为 验证器根目录, 或直接指定模块名)
     * @param array $option (default: array()) 选项 (向验证器的构造函数传输参数)
     * @return void
     */
    public static function validate($class, $layer = '', $mod = true, $option = array()) {
        $_str_namespace = self::namespaceProcess($class, $layer, $mod, 'validate');
        $_str_vld       = $_str_namespace . Func::ucwords($class, '_');

        //print_r($_str_vld);
        //print_r('<br>');

        if (class_exists($_str_vld)) { // 如果验证器存在, 直接实例化
            $_vid = md5($_str_vld);
            static::$instance[$_vid] = new $_str_vld($option); // 实例化验证器
            return static::$instance[$_vid];
        } else { // 不存在报错
            $_obj_excpt = new Exception('Validator not found', 500);
            $_obj_excpt->setData('err_detail', $_str_vld);

            throw $_obj_excpt;
        }
    }


    /** 实例化类
     * classes function.
     *
     * @access public
     * @static
     * @param string $class 类名
     * @param string $layer (default: '') 分层
     * @param mixed $mod (default: true) 模块 (true 为 当前模块, false 为 类根目录, 或直接指定模块名)
     * @param array $option (default: array()) 选项 (向类的构造函数传输参数)
     * @return void
     */
    public static function classes($class, $layer = '', $mod = true, $option = array()) {
        $_str_namespace = self::namespaceProcess($class, $layer, $mod);
        $_str_class     = $_str_namespace . Func::ucwords($class, '_');

        //print_r('<br>');

        if (class_exists($_str_class)) {
            $_oid = md5($_str_class);
            static::$instance[$_oid] = new $_str_class($option); //实例化类
            return static::$instance[$_oid];
        } else {
            $_obj_excpt = new Exception('Class not found', 500);
            $_obj_excpt->setData('err_detail', $_str_class);

            throw $_obj_excpt;
        }
    }


    /**
     * getPath function.
     * 取得路径
     * @access public
     * @static
     * @param mixed $path
     * @return void
     */
    public static function getPath($path) {
        $path  = strtolower($path);

        //print_r($path);
        //print_r('<br>');

        $_arr_url = array();

        if (!empty($path)) {
            $_arr_url = explode('\\', $path);
        }

        if (!isset($_arr_url[1])) {
            $_arr_url[1] = '';
        }

        $_str_path = '';

        switch ($_arr_url[0]) {
            case 'ginkgo':
                $_str_path  = GK_PATH_CORE;
                $_str_path .= self::pathProcess($_arr_url);
                $_str_path .= GK_EXT_CLASS;
            break;

            case 'app':
                switch ($_arr_url[1]) {
                    case 'model':
                        $_str_path  = GK_APP_MDL;
                        $_str_path .= self::pathProcess($_arr_url);
                        $_str_path .= GK_EXT_MDL;
                    break;

                    case 'validate':
                        $_str_path  = GK_APP_VLD;
                        $_str_path .= self::pathProcess($_arr_url);
                        $_str_path .= GK_EXT_VLD;
                    break;

                    case 'classes':
                        $_str_path  = GK_APP_CLASSES;
                        $_str_path .= self::pathProcess($_arr_url);
                        $_str_path .= GK_EXT_CLASS;
                    break;

                    case 'ctrl':
                        $_str_path  = GK_APP_CTRL;
                        $_str_path .= self::pathProcess($_arr_url);
                        $_str_path .= GK_EXT_CTRL;
                    break;
                }
            break;

            case 'extend':
                switch ($_arr_url[1]) {
                    case 'plugin':
                        $_str_path  = GK_PATH_PLUGIN;
                        $_str_path .= self::pathProcess($_arr_url);
                        $_str_path .= GK_EXT_CLASS;
                    break;

                    default:
                        $_str_path  = GK_PATH_EXTEND;
                        $_str_path .= self::pathProcess($_arr_url);
                        $_str_path .= GK_EXT_CLASS;
                    break;
                }
            break;
        }

        /*print_r($_str_path);
        print_r('<br>');*/

        return $_str_path;
    }


    // 清除实例
    public static function clearInstance() {
        Plugin::listen('action_fw_end'); //运行结束时触发

        static::$instance = array();
    }


    /** 命名空间处理
     * namespaceProcess function.
     *
     * @access private
     * @static
     * @param string $class 类名
     * @param string $layer (default: '') 分层
     * @param mixed $mod (default: true) 模块 (true 为 当前模块, false 为 类根目录, 或直接指定模块名)
     * @param string $type (default: 'classes') 加载类型
     * @return void
     */
    private static function namespaceProcess($class, $layer = '', $mod = true, $type = 'classes') {
        $_arr_route = Route::get(); // 获取路由

        $_str_namespace   = 'app\\' . $type . '\\'; // 补全命名空间

        if ($mod === true) { // $mod 参数 true 为 当前模块
            $_str_namespace  .= $_arr_route['mod'] . '\\';
        } else if (!Func::isEmpty($mod)) { // $mod 参数直接指定模块名
            $_str_namespace  .= $mod . '\\';
        }

        if (!Func::isEmpty($layer)) { // 如果指定了 $layer 参数
            $_str_namespace .= $layer . '\\';
        }

        return $_str_namespace;
    }


    /**
     * pathProcess function.
     * URL 数组组合成路径
     * @access private
     * @static
     * @param mixed $_arr_url
     * @return void
     */
    private static function pathProcess($url) {
        switch ($url[0]) {
            case 'ginkgo':
            break;

            default:
                unset($url[1]);
            break;
        }

        unset($url[0]);

        return implode(DS, $url);
    }
}
