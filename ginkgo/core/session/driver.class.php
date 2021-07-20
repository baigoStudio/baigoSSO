<?php
/*-----------------------------------------------------------------
！！！！警告！！！！
以下为系统文件，请勿修改
-----------------------------------------------------------------*/

namespace ginkgo\session;

use ginkgo\Func;
use ginkgo\Config;

// 不能非法包含或直接执行
if (!defined('IN_GINKGO')) {
    return 'Access denied';
}

// 数据库类型的会话驱动
abstract class Driver {

    public $config = array(); // 配置

    // 默认配置
    private $configThis = array(
        'life_time'     => 1200, // session 生存时间
    );


    protected static $instance; // 当前实例

    /** 构造函数
     * __construct function.
     *
     * @access public
     * @return void
     */
    public function __construct($config = array()) {
        $this->config($config);
    }


    protected function __clone() { }

    /** 实例化
     * instance function.
     *
     * @access public
     * @static
     * @return 当前类的实例
     */
    public static function instance($config = array()) {
        if (Func::isEmpty(self::$instance)) {
            self::$instance = new static($config);
        }
        return self::$instance;
    }


    // 配置 since 0.2.0
    public function config($config = array()) {
        $_arr_config   = Config::get('session'); // 取得配置

        $_arr_configDo = $this->configThis;

        if (is_array($_arr_config) && !Func::isEmpty($_arr_config)) {
            $_arr_configDo = array_replace_recursive($_arr_configDo, $_arr_config); // 合并配置
        }

        if (is_array($this->config) && !Func::isEmpty($this->config)) {
            $_arr_configDo = array_replace_recursive($_arr_configDo, $this->config); // 合并配置
        }

        if (is_array($config) && !Func::isEmpty($config)) {
            $_arr_configDo = array_replace_recursive($_arr_configDo, $config); // 合并配置
        }

        $this->config  = $_arr_configDo;
    }


    /** 开启会话
     * open function.
     *
     * @access public
     * @param mixed $save_path
     * @param mixed $session_name
     * @return void
     */
    public function open($save_path, $session_name) {
        return true;
    }


    /** 关闭会话
     * close function.
     *
     * @access public
     * @return void
     */
    public function close() {
        return true;
    }


    /** 读取会话
     * read function.
     *
     * @access public
     * @param mixed $session_id
     * @return 会话数据
     */
    public function read($session_id) {
        return '';
    }


    /** 写入会话
     * write function.
     *
     * @access public
     * @param mixed $session_id
     * @param mixed $session_data
     * @return void
     */
    public function write($session_id, $session_data) {
        return true;
    }


    /** 销毁会话
     * destroy function.
     *
     * @access public
     * @param mixed $session_id
     * @return void
     */
    public function destroy($session_id) {
        return true;
    }


    /** 清理会话
     * gc function.
     *
     * @access public
     * @param mixed $ssin_max_lifetime
     * @return void
     */
    public function gc($ssin_max_lifetime) {
        return true;
    }
}
