<?php
/*-----------------------------------------------------------------
！！！！警告！！！！
以下为系统文件，请勿修改
-----------------------------------------------------------------*/

namespace ginkgo;

// 不能非法包含或直接执行
defined('IN_GINKGO') or exit('Access denied');

// 日志管理类
class Log {

    private static $config; // 配置
    private static $log; // 日志内容
    private static $init; // 是否初始化标志

    protected function __construct() {
    }

    protected function __clone() {

    }

    /** 初始化
     * init function.
     *
     * @access private
     * @static
     * @return void
     */
    private static function init() {
        self::$config   = Config::get('log'); // 获取配置

        $_str_timezone  = Config::get('timezone', 'var_default'); // 获取时区配置

        App::setTimezone($_str_timezone); // 设置时区

        self::$init     = true; // 标识为已初始化
    }


    /** 获取日志
     * get function.
     *
     * @access public
     * @static
     * @param string $type (default: '') 日志类型
     * @return 日志
     */
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

    /** 记录日志
     * record function.
     *
     * @access public
     * @static
     * @param string $value (default: '') 日志内容
     * @param string $type (default: '') 日志类型
     * @return void
     */
    static function record($value = '', $type = '') {
        if (Func::isEmpty(self::$init)) {
            self::init();
        }

        if (is_array($value)) {
            $value = Json::encode($value);
        }

        self::$log[$type][] = '[' . date('c') . '] ' . $value;
    }


    /** 保存日志
     * save function.
     *
     * @access public
     * @static
     * @return void
     */
    static function save() {
        if (Func::isEmpty(self::$init)) {
            self::init();
        }

        if (!Func::isEmpty(self::$log)) {
            $_obj_file = File::instance();

            foreach (self::$log as $_key=>$_value) {
                $_str_logPath = GK_PATH_LOG . $_key . GK_EXT_LOG;

                if (Func::isFile($_str_logPath) && filesize($_str_logPath) >= floor(self::$config['file_size'])) { // 日志文件大于设置, 则按日期另存
                    try {
                        $_obj_file->fileMove($_str_logPath, dirname($_str_logPath) . DS . date('Y-m-d') . '_' . basename($_str_logPath));
                    } catch (\Exception $e) {
                    }
                }

                $_str_content = '';

                foreach ($_value as $_key_row=>$_value_row) {
                    $_obj_file->fileWrite($_str_logPath, $_value_row . PHP_EOL, true); // 写入
                }
            }
        }
    }
}


