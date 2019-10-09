<?php
/*-----------------------------------------------------------------
！！！！警告！！！！
以下为系统文件，请勿修改
-----------------------------------------------------------------*/

namespace ginkgo;

//不能非法包含或直接执行
defined('IN_GINKGO') or exit('Access denied');

class Log {

    private static $config;
    private static $log;
    private static $init;

    protected function __construct() {
    }

    protected function __clone() {

    }

    private static function init() {
        self::$config   = Config::get('log');

        $_str_timezone  = Config::get('timezone', 'var_default');

        App::setTimezone($_str_timezone);

        self::$init     = true;
    }

    static function get($type = '') {
        $_value = '';

        if (Func::isEmpty($type)) {
            $_value = self::$log;
        } else if (isset(self::$log[$type])) {
            $_value = self::$log[$type];
        }

        //print_r($_value);

        return $_value;
    }

    static function record($value = '', $type = '') {
        if (Func::isEmpty(self::$init)) {
            self::init();
        }

        if (is_array($value)) {
            $value = Json::encode($value);
        }

        self::$log[$type][] = '[' . date('c') . '] ' . $value;
    }

    static function save() {
        if (Func::isEmpty(self::$init)) {
            self::init();
        }

        if (!Func::isEmpty(self::$log)) {
            $_obj_file = File::instance();

            foreach (self::$log as $_key=>$_value) {
                $_str_logPath = GK_PATH_LOG . $_key . GK_EXT_LOG;

                if (Func::isFile($_str_logPath) && filesize($_str_logPath) >= floor(self::$config['file_size'])) {
                    try {
                        $_obj_file->fileMove($_str_logPath, dirname($_str_logPath) . DS . date('Y-m-d') . '_' . basename($_str_logPath));
                    } catch (\Exception $e) {
                    }
                }

                $_str_content = '';

                foreach ($_value as $_key_row=>$_value_row) {
                    $_obj_file->fileWrite($_str_logPath, $_value_row . PHP_EOL, true);
                }
            }
        }
    }
}


