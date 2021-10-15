<?php
/*-----------------------------------------------------------------
！！！！警告！！！！
以下为系统文件，请勿修改
-----------------------------------------------------------------*/

namespace ginkgo\view;

use ginkgo\Request;
use ginkgo\Config;
use ginkgo\Func;
use ginkgo\Strings;
use ginkgo\File;
use ginkgo\Lang;
use ginkgo\except\Tpl_Not_Found;

// 不能非法包含或直接执行
if (!defined('IN_GINKGO')) {
  return 'Access denied';
}

// 视图驱动基类
abstract class Driver {

  public $config = array(); // 配置

  protected static $instance; // 当前实例
  protected $obj_request; // 请求实例
  protected $obj; // 对象

  protected $route; // 路由
  protected $param; // 参数
  protected $pathTpl; // 模板路径

  protected $configThis = array(
    'path' => '',
  ); // 默认配置

  /** 构造函数
   * __construct function.
   *
   * @access protected
   * @param array $config (default: array()) 配置
   * @return void
   */
  protected function __construct($config = array()) {
    $this->obj_request      = Request::instance();

    $this->obj['lang']      = Lang::instance();
    $this->obj['request']   = $this->obj_request;

    $this->route            = $this->obj_request->route;
    $this->routeOrig        = $this->obj_request->routeOrig;
    $this->param            = $this->obj_request->param;

    $this->config($config);

    $this->setPath(); // 设置路径
  }

  /**
   * instance function.
   *
   * @access public
   * @static
   * @param array $config (default: array()) 配置
   * @return void
   */
  public static function instance($config = array()) {
    if (Func::isEmpty(self::$instance)) {
      self::$instance = new static($config);
    }
    return self::$instance;
  }


  // 配置 since 0.2.0
  public function config($config = array()) {
    $_arr_config   = Config::get('tpl'); // 取得配置

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

    $this->config  = $_arr_configDo;
  }


  /** 渲染文件模板
   * fetch function.
   *
   * @access public
   * @param string $tpl (default: '') 模板名
   * @param string $data (default: '') 内容
   * @return 渲染结果
   */
  public function fetch($tpl = '', $data = '') {
    $_str_tpl = $this->pathProcess($tpl); // 路径处理

    if (!File::fileHas($_str_tpl)) {
      $_obj_excpt = new Tpl_Not_Found('Template not found', 500); // 报错
      $_obj_excpt->setData('err_detail', $_str_tpl);

      throw $_obj_excpt;
    }

    require($_str_tpl);
  }


  /** 渲染内容模板
   * display function.
   *
   * @access public
   * @param string $content (default: '')
   * @param string $data (default: '')
   * @return void
   */
  public function display($content = '', $data = '') {

    return $content;
  }

  /** 模板是否存在
   * has function.
   *
   * @access public
   * @param string $tpl (default: '') 模板名
   * @return bool
   */
  public function has($tpl) {
    $_str_tpl = $this->pathProcess($tpl);

    //print_r($_str_tpl);

    return File::fileHas($_str_tpl);
  }


  /** 设置模板目录路径
   * setPath function.
   *
   * @access public
   * @param string $pathTpl (default: '') 路径
   * @return void
   */
  public function setPath($pathTpl = '') {
    if (Func::isEmpty($pathTpl)) { // 如果参数为空, 则自动定位
      $_str_pathTpl   = GK_APP_TPL . $this->route['mod'] . DS;

      if (Func::notEmpty($this->config['path'])) {
        $_str_path = str_replace(array('/', '\\'), DS, $this->config['path']);

        if (strpos($_str_path, DS) !== false) {
          $_str_pathTpl = Func::fixDs($_str_path);
        } else {
          $_str_pathTpl .= Func::fixDs($_str_path);
        }
      }
    } else {
      $_str_pathTpl   = Func::fixDs($pathTpl); // 否则直接使用
    }

    $this->pathTpl  = $_str_pathTpl;

    return $_str_pathTpl;
  }


  /** 映射对象
   * setObj function.
   *
   * @access public
   * @param mixed $name
   * @param mixed &$obj
   * @return void
   */
  public function setObj($name, &$obj) {
    $this->obj[$name] = $obj;
  }

  /** 取得模板路径
   * getPath function.
   *
   * @access public
   * @return void
   */
  public function getPath() {
    return $this->pathTpl;
  }


  /** 路径处理
   * pathProcess function.
   *
   * @access protected
   * @param string $tpl (default: '')
   * @return void
   */
  protected function pathProcess($tpl = '') {
    $_str_tpl = $this->pathTpl;

    $_str_act = Strings::toLine($this->route['act']); // 转为文件名

    if (Func::isEmpty($tpl)) {
      $_str_tpl .= $this->route['ctrl'] . DS . $_str_act; // 如果未定义模板参数, 则自动定位
    } else {
      $tpl = str_replace(array('/', '\\'), DS, $tpl); // 分拆模板参数

      if (strpos($tpl, GK_EXT_TPL) !== false) { // 如果定义了后缀, 则认为是完整路径, 直接使用
        $_str_tpl = $tpl;
      } else if (strpos($tpl, DS) !== false) { // 如果定义了目录分隔符, 则认为是 控制器/模板 形式
        $_str_tpl .= $tpl;
      } else { // 否则补全为 当前控制器/模板
        $_str_tpl .= $this->route['ctrl'] . DS . $tpl;
      }
    }

    $_str_pathTpl = str_replace(array('/', '\\'), DS, $_str_tpl);

    if (strpos($_str_pathTpl, GK_EXT_TPL) === false) { // 如果后缀空缺, 则补全
      $_str_pathTpl .= GK_EXT_TPL;
    }

    return str_replace('-', '_', $_str_pathTpl);
  }
}
