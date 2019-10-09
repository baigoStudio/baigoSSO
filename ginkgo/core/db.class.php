<?php
/*-----------------------------------------------------------------
！！！！警告！！！！
以下为系统文件，请勿修改
-----------------------------------------------------------------*/

namespace ginkgo;

//不能非法包含或直接执行
defined('IN_GINKGO') or exit('Access denied');

/*-------------数据库类-------------*/
class Db {

    protected static $instance;
    private static $isConfig;

    public static $dbconfig;

    protected function __construct($dbconfig = array()) {
    }

    protected function __clone() {

    }

    public static function connect($dbconfig = array()) {
        if (Func::isEmpty(static::$instance)) {
            if (Func::isEmpty(self::$isConfig)) {
                self::config($dbconfig);
            }

            if (Func::isEmpty(self::$dbconfig['type'])) {
                self::$dbconfig['type'] = 'mysql';
            }

            if (strpos(self::$dbconfig['type'], '\\')) {
                $_class = self::$dbconfig['type'];
            } else {
                $_class = 'ginkgo\\db\\connector\\' . Func::ucwords(self::$dbconfig['type'], '_');
            }

            //print_r($_class);

            if (class_exists($_class)) {
                static::$instance = $_class::instance(self::$dbconfig);
            } else {
                $_obj_excpt = new Exception('Unsupported database type', 500);

                $_obj_excpt->setData('err_detail', $_class);

                throw $_obj_excpt;
            }
        }

        return static::$instance;
    }


    public static function config($dbconfig = array()) {
        $_arr_dbconfig = Config::get('dbconfig');

        if (!Func::isEmpty($dbconfig)) {
            self::$dbconfig = $dbconfig;
        } else {
            self::$dbconfig = $_arr_dbconfig;
        }

        self::configProcess();

        self::$isConfig = true;
    }


    private static function configProcess() {
        isset(self::$dbconfig['type']) or self::$dbconfig['type'] = 'mysql';
        isset(self::$dbconfig['host']) or self::$dbconfig['host'] = 'localhost';
        isset(self::$dbconfig['name']) or self::$dbconfig['name'] = '';
        isset(self::$dbconfig['user']) or self::$dbconfig['user'] = '';
        isset(self::$dbconfig['pass']) or self::$dbconfig['pass'] = '';
        isset(self::$dbconfig['charset']) or self::$dbconfig['charset'] = 'utf8';
        isset(self::$dbconfig['prefix']) or self::$dbconfig['prefix'] = '';
        isset(self::$dbconfig['debug']) or self::$dbconfig['debug'] = false;
        isset(self::$dbconfig['port']) or self::$dbconfig['port'] = 3306;
    }


    public static function __callStatic($method, $params) {
        $_class = self::connect();

        if (method_exists($_class, $method)) {
            return call_user_func_array(array($_class, $method), $params);
        } else {
            $_obj_excpt = new Exception('Method not found: ' . __CLASS__ . '->' . $method, 500);
            $_obj_excpt->setData('err_detail', $method);

            throw $_obj_excpt;
        }
    }
}
