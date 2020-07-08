<?php
/*-----------------------------------------------------------------
！！！！警告！！！！
以下为系统文件，请勿修改
-----------------------------------------------------------------*/

namespace ginkgo;

// 不能非法包含或直接执行
defined('IN_GINKGO') or exit('Access denied');

// 缓存
class Cache {

    protected static $instance; // 当前实例
    public $obj_driver; // 驱动实例

    private $config; // 配置

    // 默认配置
    private $this_config = array(
        'type'      => 'file',
        'prefix'    => '',
    );

    protected function __construct($type = 'file', $config = array()) {
        $type = (string)$type;
        $_arr_config  = Config::get('cache'); // 获取缓存配置
        $this->config = array_replace_recursive($this->this_config, $config, $_arr_config); // 合并配置

        $this->driver($type, $this->config); // 设置驱动
    }

    protected function __clone() {

    }

    /** 实例化
     * instance function.
     *
     * @access public
     * @static
     * @param string $type (default: 'file') 驱动类型
     * @param array $config (default: array()) 配置
     * @return 当前类的实例
     */
    public static function instance($type = 'file', $config = array()) {
        if (Func::isEmpty(static::$instance)) {
            static::$instance = new static($type, $config);
        }
        return static::$instance;
    }

    /** 设置, 取得前缀
     * prefix function.
     *
     * @access public
     * @param string $prefix (default: '') 前缀
     * @return 如果参数为空则返回当前前缀, 否则无返回
     */
    public function prefix($prefix = '') {
        return $this->obj_driver->prefix($prefix);
    }

    /** 设置驱动
     * driver function.
     *
     * @access public
     * @param string $type (default: 'file') 驱动类型
     * @param array $config (default: array()) 配置
     * @return 当前实例
     */
    public function driver($type = 'file', $config = array()) {
        // 未指定驱动, 则使用默认
        if (Func::isEmpty($type)) {
            $type = 'file';
        }

        if (strpos($type, '\\')) {
            $_class = $type;
        } else {
            $_class = 'ginkgo\\cache\\driver\\' . Func::ucwords($type, '_');
        }

        // 初始化驱动类
        if (class_exists($_class)) {
            $this->obj_driver = $_class::instance($config);
        } else {
            $_obj_excpt = new Exception('Cache driver not found', 500);

            $_obj_excpt->setData('err_detail', $_class);

            throw $_obj_excpt;
        }

        return $this;
    }

    /** 检查缓存是否存在
     * check function.
     *
     * @access public
     * @param mixed $name 缓存名称
     * @param bool $check_expire (default: false) 是否检查过期时间 (默认不检查)
     * @return 检查结果 (bool)
     */
    function check($name, $check_expire = false) {
        return $this->obj_driver->check($name, $check_expire);
    }

    /** 读取
     * read function.
     *
     * @access public
     * @param mixed $name 缓存名称
     * @return 缓存内容
     */
    function read($name) {
        return $this->obj_driver->read($name);
    }

    /** 写入
     * write function.
     *
     * @access public
     * @param mixed $name 缓存名称
     * @param mixed $content 缓存内容
     * @param mixed $life_time 有效时间
     * @return 写入字节数
     */
    function write($name, $content, $life_time = 0) {
        return $this->obj_driver->write($name, $content, $life_time);
    }

    /** 删除
     * delete function.
     *
     * @access public
     * @param mixed $name 缓存名称
     * @return 删除结果 (bool)
     */
    function delete($name) {
        return $this->obj_driver->delete();
    }
}