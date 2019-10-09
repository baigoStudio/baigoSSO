<?php
/*-----------------------------------------------------------------
！！！！警告！！！！
以下为系统文件，请勿修改
-----------------------------------------------------------------*/

namespace ginkgo;

//不能非法包含或直接执行
defined('IN_GINKGO') or exit('Access denied');

class Loader {

    protected static $instance; //用静态属性保存实例

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


    /**
     * register function.
     * 注册自动载入函数
     * @access public
     * @static
     * @return void
     */
    static function register() {
        //自动载入
        spl_autoload_register(array(__CLASS__, 'autoload'), true, true);

        if (Func::isFile(GK_PATH_VENDOR . 'autoload.php')) {
            self::load(GK_PATH_VENDOR . 'autoload.php');
        }
    }


    /**
     * autoload function.
     * 自动载入类文件
     * @access private
     * @static
     * @param mixed $class_name
     * @return void
     */
    private static function autoload($class_name) {
        $_str_path = self::getPath($class_name);

        /*print_r($_str_path);
        print_r('<br>');
        print_r(PHP_EOL);*/

        self::load($_str_path);

        return true;
    }


    /**
     * ctrl function.
     * 实例化控制器
     * @access public
     * @static
     * @param mixed $class
     * @param string $layer (default: '')
     * @param array $option (default: array())
     * @return void
     */
    public static function ctrl($class, $layer = '', $mod = true, $option = array()) {
        $_arr_route = Route::get();

        $_str_ctrl  = 'app\\ctrl\\';
        $_str_route = '';

        if ($mod === true) {
            $_str_ctrl  .= $_arr_route['mod'] . '\\';
            $_str_route .= $_arr_route['mod'] . '->';
        } else if (!Func::isEmpty($mod)) {
            $_str_ctrl  .= $mod . '\\';
            $_str_route .= $mod . '->';
        }

        if (!Func::isEmpty($layer)) {
            $_str_ctrl  .= $layer . '\\';
            $_str_route .= $layer . '->';
        }

        $_str_ctrlError  = $_str_ctrl . 'C_Empty';
        $_str_ctrl      .= Func::ucwords($class, '_');
        $_str_route     .= strtolower($class);

        /*print_r($_str_ctrl);
        print_r('<br>');*/

        if (class_exists($_str_ctrl)) {
            $_cid = md5($_str_ctrl);
            static::$instance[$_cid] = new $_str_ctrl($option); //实例化控制器

            return static::$instance[$_cid];
        } else if (class_exists($_str_ctrlError)) {
            $_cid = md5($_str_ctrlError);
            static::$instance[$_cid] = new $_str_ctrlError($option); //实例化空控制器

            return static::$instance[$_cid];
        } else {
            $_obj_excpt = new Exception('Controller not found', 404);

            $_obj_excpt->setData('err_detail', $_str_route);

            throw $_obj_excpt;
        }
    }


    /**
     * model function.
     * 实例化模型
     * @access public
     * @static
     * @param mixed $class
     * @param string $layer (default: '')
     * @param array $option (default: array())
     * @return void
     */
    public static function model($class, $layer = '', $mod = true, $option = array()) {
        $_arr_route = Route::get();

        $_str_mdl   = 'app\\model\\';

        if ($mod === true) {
            $_str_mdl  .= $_arr_route['mod'] . '\\';
        } else if (!Func::isEmpty($mod)) {
            $_str_mdl  .= $mod . '\\';
        }

        if (!Func::isEmpty($layer)) {
            $_str_mdl .= $layer . '\\';
        }

        $_str_mdl .= Func::ucwords($class, '_');


        if (class_exists($_str_mdl)) {
            $_mid = md5($_str_mdl);
            static::$instance[$_mid] = new $_str_mdl($option); //实例化模型
            return static::$instance[$_mid];
        } else {
            $_obj_excpt = new Exception('Model not found', 500);
            $_obj_excpt->setData('err_detail', $_str_mdl);

            throw $_obj_excpt;
        }
    }


    public static function validate($class, $layer = '', $mod = true, $option = array()) {
        $_arr_route = Route::get();

        $_str_vld   = 'app\\validate\\';

        if ($mod === true) {
            $_str_vld  .= $_arr_route['mod'] . '\\';
        } else if (!Func::isEmpty($mod)) {
            $_str_vld  .= $mod . '\\';
        }

        if (!Func::isEmpty($layer)) {
            $_str_vld .= $layer . '\\';
        }

        $_str_vld .= Func::ucwords($class, '_');

        //print_r($_str_vld);
        //print_r('<br>');

        if (class_exists($_str_vld)) {
            $_vid = md5($_str_vld);
            static::$instance[$_vid] = new $_str_vld($option); //实例化模型
            return static::$instance[$_vid];
        } else {
            $_obj_excpt = new Exception('Validator not found', 500);
            $_obj_excpt->setData('err_detail', $_str_vld);

            throw $_obj_excpt;
        }
    }


    public static function classes($class, $layer = '', $mod = true, $option = array()) {
        $_arr_route = Route::get();

        $_str_class   = 'app\\classes\\';

        if ($mod === true) {
            $_str_class  .= $_arr_route['mod'] . '\\';
        } else if (!Func::isEmpty($mod)) {
            $_str_class  .= $mod . '\\';
        }

        if (!Func::isEmpty($layer)) {
            $_str_class .= $layer . '\\';
        }

        $_str_class .= Func::ucwords($class, '_');

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


    public static function clearInstance() {
        Plugin::listen('action_fw_end'); //运行结束时触发

        static::$instance = array();
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
