<?php
/*-----------------------------------------------------------------
！！！！警告！！！！
以下为系统文件，请勿修改
-----------------------------------------------------------------*/

namespace ginkgo;

use ginkgo\except\Class_Not_Found;

// 不能非法包含或直接执行
if (!defined('IN_GINKGO')) {
  return 'Access denied';
}

// 视图类
class View {

  public $config   = array(); // 配置
  public $replace  = array(); // 输出替换

  protected static $instance; // 当前实例

  private $configThis = array(
    'type' => 'php',
  ); // 默认配置

  private $obj_driver; // 视图驱动实例
  private $data = array(); // 内容

  /** 构造函数
   * __construct function.
   *
   * @access protected
   * @param string $configView (default: '') 视图配置
   * @param array $configTpl (default: array()) 模板配置
   * @return void
   */
  protected function __construct($configView = '', $configTpl = array()) {
    $this->config($configView);
    $this->driver('', $configTpl);

    $this->obj_request  = Request::instance();
  }

  protected function __clone() { }

  /** 实例化
   * instance function.
   *
   * @access public
   * @static
   * @param string $configView (default: 'php') 视图配置
   * @param array $configTpl (default: array()) 模板配置
   * @return 当前类的实例
   */
  public static function instance($configView = 'php', $configTpl = array()) {
    if (is_null(self::$instance)) {
      self::$instance = new static($configView, $configTpl);
    }
    return self::$instance;
  }


  public function config($config = '') {
    $_arr_config   = Config::get('view'); // 取得配置

    $_arr_configDo = $this->configThis;

    if (is_array($_arr_config) && Func::notEmpty($_arr_config)) {
      $_arr_configDo = array_replace_recursive($_arr_configDo, $_arr_config); // 合并配置
    }

    if (is_array($this->config) && Func::notEmpty($this->config)) {
      $_arr_configDo = array_replace_recursive($_arr_configDo, $this->config); // 合并配置
    }

    if (is_array($config)) {
      $_arr_configDo = array_replace_recursive($_arr_configDo, $config); // 合并配置
    } else if (is_string($config) && Func::notEmpty($config)) {
      $_arr_configDo['type'] = $config;
    }

    $this->config  = $_arr_configDo;
  }


  /** 设置视图驱动并实例化
   * driver function.
   *
   * @access public
   * @param string $configView (default: 'php') 试图配置
   * @param array $configTpl (default: array()) 模板配置
   * @return 当前实例
   */
  public function driver($configView = '', $configTpl = array()) {
    if (Func::notEmpty($configView)) {
      $this->config($configView);
    }

    if (Func::isEmpty($this->config['type'])) {
      $this->config['type'] = $this->configThis['type'];
    }

    if (strpos($this->config['type'], '\\')) { // 如果驱动类型指定了命名空间, 则直接使用
      $_class = $this->config['type'];
    } else {
      $_class = 'ginkgo\\view\\driver\\' . Strings::ucwords($this->config['type'], '_'); // 补全命名空间
    }

    if (class_exists($_class)) {
      $this->obj_driver = $_class::instance($configTpl); // 实例化
    } else {
      $_obj_excpt = new Class_Not_Found('View driver not found', 500); // 报错

      $_obj_excpt->setData('err_detail', $_class);

      throw $_obj_excpt;
    }

    return $this;
  }


  /** 模板变量赋值
   * assign function.
   *
   * @access public
   * @param mixed $assign 变量
   * @param string $value (default: '') 值
   * @return void
   */
  public function assign($assign, $value = '') {
    if (is_array($assign)) {
      $this->data = array_replace_recursive($this->data, $assign);
    } else {
      $this->data[$assign] = $value;
    }
  }

  /** 渲染
   * fetch function.
   *
   * @access public
   * @param string $tpl (default: '') 模板
   * @param string $assign (default: '') 变量
   * @param string $value (default: '') 值
   * @param bool $is_display (default: false) 是否为渲染内容
   * @return void
   */
  public function fetch($tpl = '', $assign = '', $value = '', $is_display = false) {
    if (Func::notEmpty($assign)) {
      $this->assign($assign, $value); // 赋值
    }

    $_arr_data = $this->data; // 内容

    $_arr_data['path_tpl'] = $this->getPath(); // 取得模板路径

    ob_start(); // 打开缓冲
    ob_implicit_flush(0); // 关闭绝对刷送

    try {
      if ($is_display) {
        $_str_method = 'display'; // 直接以字符串作为模板
      } else {
        $_str_method = 'fetch'; // 渲染模板文件
      }
      $this->obj_driver->$_str_method($tpl, $_arr_data); // 调用驱动方法
    } catch (\Exception $e) {
      ob_end_clean();
      throw $e;
    }

    $_str_content = ob_get_clean(); // 取得输出缓冲内容并清理关闭

    $this->reset(); // 重置内容

    return $this->replaceProcess($_str_content); // 输出替换
  }

  /** 直接以字符串作为模板
   * display function.
   *
   * @access public
   * @param string $content (default: '') 模板内容
   * @param string $assign (default: '') 变量
   * @param string $value (default: '') 值
   * @return void
   */
  public function display($content, $assign = '', $value = '') {
    return $this->fetch($content, $assign = '', $value, true);
  }

  /** 模板是否存在
   * has function.
   *
   * @access public
   * @param string $tpl (default: '') 模板名
   * @return bool
   */
  public function has($tpl = '') {
    return $this->obj_driver->has($tpl);
  }

  /** 设置输出替换
   * setReplace function.
   *
   * @access public
   * @param mixed $replace 查找
   * @param string $value (default: '') 替换
   * @return void
   */
  public function setReplace($replace, $value = '') {
    if (is_array($replace)) {
      $this->replace = array_replace_recursive($this->replace, $replace);
    } else {
      $this->replace[$replace] = $value;
    }
  }

  /** 设置模板目录
   * setPath function.
   *
   * @access public
   * @param string $pathTpl (default: '')
   * @return void
   */
  public function setPath($pathTpl) {
    return $this->obj_driver->setPath($pathTpl);
  }

  /** 向模板映射对象
   * setObj function.
   *
   * @access public
   * @param mixed $name 对象名
   * @param mixed &$obj 对象映射
   * @return void
   */
  public function setObj($name, &$obj) {
    $this->obj_driver->setObj($name, $obj);
  }

  /** 取得模板目录
   * getPath function.
   *
   * @access public
   * @return void
   */
  public function getPath() {
    return $this->obj_driver->getPath();
  }

  /** 清空内容
   * reset function.
   *
   * @access public
   * @return void
   */
  public function reset() {
    $this->data = array();
  }

  /** 输出替换处理
   * replaceProcess function.
   *
   * @access private
   * @param mixed $content 内容
   * @return void
   */
  private function replaceProcess($content) {
    $replace = $this->replace;

    if (is_array($replace) && Func::notEmpty($replace)) {
      $_arr_replace = array_keys($replace);
      foreach ($_arr_replace as $_key=>&$_value) {
        $_value = '{:' . $_value . '}';
      }
      $content = str_ireplace($_arr_replace, $replace, $content);
    }

    // 路径处理
    $_str_urlBase      = $this->obj_request->baseUrl(true);
    $_str_urlRoot      = $this->obj_request->root(true);
    $_str_dirRoot      = $this->obj_request->root();
    $_str_routeRoot    = $this->obj_request->baseUrl();

    $_str_routePage    = Route::build();

    /*print_r($_str_routePage);
    print_r('<br>');*/

    // 模板中的替换处理
    $_arr_replaceSrc = array(
      '{:URL_BASE}',
      '{:URL_ROOT}',
      '{:DIR_ROOT}',
      '{:DIR_STATIC}',
      '{:ROUTE_ROOT}',
      '{:ROUTE_PAGE}',
    );

    $_arr_replaceDst = array(
      $_str_urlBase,
      $_str_urlRoot,
      $_str_dirRoot,
      $_str_dirRoot . GK_NAME_STATIC . '/',
      $_str_routeRoot,
      $_str_routePage,
    );

    $content = str_ireplace($_arr_replaceSrc, $_arr_replaceDst, $content);

    $content = Plugin::listen('filter_fw_view', $content); //模板输出时过滤

    return $content;
  }
}
