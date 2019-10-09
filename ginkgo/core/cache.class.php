<?php
/*-----------------------------------------------------------------
！！！！警告！！！！
以下为系统文件，请勿修改
-----------------------------------------------------------------*/

namespace ginkgo;

//不能非法包含或直接执行
defined('IN_GINKGO') or exit('Access denied');

/*-------------文件操作类-------------*/
class Cache {

    protected static $instance;
    public $obj_driver;

    private $config;

    private $this_config = array(
        'type'      => 'file',
        'prefix'    => '',
    );

    protected function __construct($type = 'file', $config = array()) {
        $_arr_config  = Config::get('cache');
        $this->config = array_replace_recursive($this->this_config, $config, $_arr_config);

        $this->driver($type, $this->config);
    }

    protected function __clone() {

    }

    public static function instance($type = 'file', $config = array()) {
        if (Func::isEmpty(static::$instance)) {
            static::$instance = new static($type, $config);
        }
        return static::$instance;
    }

    public function prefix($prefix = '') {
        return $this->obj_driver->prefix($prefix);
    }

    public function driver($type = 'file', $config = array()) {
        if (Func::isEmpty($type)) {
            $type = 'file';
        }

        if (strpos($type, '\\')) {
            $_class = $type;
        } else {
            $_class = 'ginkgo\\cache\\driver\\' . Func::ucwords($type, '_');
        }

        if (class_exists($_class)) {
            $this->obj_driver = $_class::instance($config);
        } else {
            $_obj_excpt = new Exception('Cache driver not found', 500);

            $_obj_excpt->setData('err_detail', $_class);

            throw $_obj_excpt;
        }

        return $this;
    }

    function check($name, $check_expire = false) {
        return $this->obj_driver->check($name, $check_expire);
    }

    function read($name) {
        return $this->obj_driver->read($name);
    }

    function write($name, $content) {
        return $this->obj_driver->write($name, $content);
    }

    function delete($name) {
        return $this->obj_driver->delete();
    }
}