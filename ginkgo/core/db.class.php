<?php
/*-----------------------------------------------------------------
！！！！警告！！！！
以下为系统文件，请勿修改
-----------------------------------------------------------------*/

namespace ginkgo;

// 不能非法包含或直接执行
defined('IN_GINKGO') or exit('Access denied');

// 数据库类
class Db {

    protected static $instance; // 当前实例
    private static $isConfig; // 是否已配置标志

    public static $dbconfig = array();

    private static $this_config = array(
        'type'      => 'mysql',
        'host'      => '',
        'name'      => '',
        'user'      => '',
        'pass'      => '',
        'charset'   => 'utf8',
        'prefix'    => 'ginkgo_',
        'debug'     => false,
        'port'      => 3306,
    );

    protected function __construct($dbconfig = array()) {
    }

    protected function __clone() {

    }

    /** 连接数据库
     * connect function.
     *
     * @access public
     * @static
     * @param array $dbconfig (default: array()) 数据库配置
     * @return 数据库实例
     */
    public static function connect($dbconfig = array()) {
        if (Func::isEmpty(static::$instance)) {

            if (Func::isEmpty(self::$isConfig)) {
                self::config($dbconfig); // 数据库配置
            }

            if (Func::isEmpty(self::$dbconfig['type'])) { // 假如未指定类型, 则默认为 mysql
                self::$dbconfig['type'] = 'mysql';
            }

            if (strpos(self::$dbconfig['type'], '\\')) {
                $_class = self::$dbconfig['type'];
            } else {
                $_class = 'ginkgo\\db\\connector\\' . Func::ucwords(self::$dbconfig['type'], '_');
            }

            //print_r($_class);

            if (class_exists($_class)) {
                static::$instance = $_class::instance(self::$dbconfig); // 实例化数据库驱动
            } else {
                $_obj_excpt = new Exception('Unsupported database type', 500);

                $_obj_excpt->setData('err_detail', $_class);

                throw $_obj_excpt;
            }
        }

        return static::$instance;
    }


    /** 数据库配置
     * config function.
     *
     * @access public
     * @static
     * @param array $dbconfig (default: array()) 数据库配置
     * @return void
     */
    public static function config($dbconfig = array()) {
        $_arr_dbconfig = Config::get('dbconfig'); // 获取数据库配置

        if (!Func::isEmpty($dbconfig)) { // 假如配置参数不为空, 则采用
            $_arr_dbconfig = $dbconfig;
        }

        self::$dbconfig = array_replace_recursive(self::$this_config, $_arr_dbconfig); // 合并配置

        self::$isConfig = true; // 标识为已配置
    }


    /** 魔术静态调用
     * __callStatic function.
     *
     * @access public
     * @static
     * @param string $method 数据库方法
     * @param string $params 参数
     * @return 数据库实例
     */
    public static function __callStatic($method, $params) {
        $_obj_connect = self::connect(); // 连接数据库

        if (method_exists($_obj_connect, $method)) {
            return call_user_func_array(array($_obj_connect, $method), $params); // 调用数据库驱动方法
        } else {
            $_obj_excpt = new Exception('Method not found: ' . __CLASS__ . '->' . $method, 500);
            $_obj_excpt->setData('err_detail', $method);

            throw $_obj_excpt;
        }
    }
}
