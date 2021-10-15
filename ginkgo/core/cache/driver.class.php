<?php
/*-----------------------------------------------------------------
！！！！警告！！！！
以下为系统文件，请勿修改
-----------------------------------------------------------------*/

namespace ginkgo\cache;

use ginkgo\Func;
use ginkgo\Config;

// 不能非法包含或直接执行
if (!defined('IN_GINKGO')) {
  return 'Access denied';
}

// 驱动抽象类
abstract class Driver {

  public $config = array();

  protected static $instance; // 当前实例

  // 默认配置
  private $configThis = array(
    'prefix'    => '',
    'life_time' => 1200,
  );

  /** 构造函数
   * __construct function.
   *
   * @access protected
   * @param array $config (default: array()) 配置
   * @return void
   */
  protected function __construct($config = array()) {
    $this->config($config); // 合并配置
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

  // since 0.2.0
  public function config($config = array()) {
    $_arr_config   = Config::get('cache'); // 获取缓存配置

    $_arr_configDo = $this->configThis;

    if (is_array($_arr_config) && Func::notEmpty($_arr_config)) {
      $_arr_configDo = array_replace_recursive($_arr_configDo, $_arr_config); // 合并配置
    }

    if (is_array($this->config) && Func::notEmpty($this->config)) {
      $_arr_configDo = array_replace_recursive($_arr_configDo, $this->config); // 合并配置
    }

    if (is_array($config) && Func::notEmpty($config)) {
      $_arr_configDo = array_replace_recursive($_arr_configDo, $config); // 合并配置
    }

    $this->config = $_arr_configDo;
  }

  /** 设置, 取得前缀
   * prefix function.
   *
   * @access public
   * @param string $prefix (default: '') 前缀
   * @return 如果参数为空则返回当前前缀, 否则无返回
   */
  public function prefix($prefix = '') {
    if (Func::isEmpty($prefix)) {
      return $this->config['prefix'];
    } else {
      $this->config['prefix'] = $prefix;
    }
  }

  /** 检查缓存是否存在
   * check function.
   *
   * @access public
   * @param mixed $name 缓存名称
   * @param bool $check_expire (default: false) 是否检查过期时间 (默认不检查)
   * @return 检查结果 (bool)
   */
  public function check($name, $check_expire = false) {
    return true;
  }

  /** 读取
   * read function.
   *
   * @access public
   * @param mixed $name 缓存名称
   * @return 缓存内容
   */
  public function read($name) {
   return '';
  }

  /** 写入
   * write function.
   *
   * @access public
   * @param mixed $name 缓存名称
   * @param mixed $content 缓存内容
   * @return 写入字节数
   */
  public function write($name, $content, $life_time = 0) {
    return 0;
  }

  /** 删除
   * delete function.
   *
   * @access public
   * @param mixed $name 缓存名称
   * @return 删除结果 (bool)
   */
  public function delete($name) {
    return true;
  }
}
