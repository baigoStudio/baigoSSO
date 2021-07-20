<?php
/*-----------------------------------------------------------------
！！！！警告！！！！
以下为系统文件，请勿修改
-----------------------------------------------------------------*/

namespace ginkgo\db;

use ginkgo\Func;

// 不能非法包含或直接执行
if (!defined('IN_GINKGO')) {
    return 'Access denied';
}

/*-------------SQL 语句构造抽象类-------------*/
abstract class Builder {

    public $config = array(); // 数据库配置

    protected static $instance; // 当前实例


    /** 配置参数
     * configThis
     *
     * @var mixed
     * @access private
     */
    private $configThis = array(
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


    /** 构造函数
     * __construct function.
     *
     * @access protected
     * @param array $config (default: array()) 配置
     * @return void
     */
    protected function __construct($config = array()) {
        $this->config($config);
    }

    protected function __clone() { }

    /** 实例化
     * instance function.
     *
     * @access public
     * @static
     * @param array $config (default: array()) 配置
     * @return 当前类的实例
     */
    public static function instance($config = array()) {
        if (Func::isEmpty(self::$instance)) {
            self::$instance = new static($config);
        }
        return self::$instance;
    }


    /** 设定配置
     * config function.
     *
     * @access public
     * @param array $config (default: array())
     * @return void
     */
    public function config($config = array()) {
        $this->config = array_replace_recursive($this->configThis, $this->config, $config); // 合并配置
    }


    /** 处理表名
     * table function.
     *
     * @access public
     * @param mixed $table 表名
     * @return 完整表名
     */
    public function table($table) {
        return $table;
    }
}
