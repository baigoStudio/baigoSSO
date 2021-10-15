<?php
/*-----------------------------------------------------------------
！！！！警告！！！！
以下为系统文件，请勿修改
-----------------------------------------------------------------*/

namespace ginkgo;

use ginkgo\except\Db_Except;

// 不能非法包含或直接执行
if (!defined('IN_GINKGO')) {
  return 'Access denied';
}

// 数据库类
class Db {

  public static $config = array();

  protected static $instance; // 当前实例

  private static $init; // 是否已初始化标志
  private static $configThis = array(
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


  /** 连接数据库
   * connect function.
   *
   * @access public
   * @static
   * @param array $config (default: array()) 数据库配置
   * @return 数据库实例
   */
  public static function connect($config = array()) {
    if (Func::isEmpty(self::$instance)) {
      if (Func::isEmpty(self::$init)) {
        self::config($config); // 数据库配置
      }

      if (Func::isEmpty(self::$config['type'])) {
        self::$config['type'] = Strings::ucwords(self::$configThis['type']);
      }

      if (strpos(self::$config['type'], '\\')) {
        $_class = self::$config['type'];
      } else {
        $_class = 'ginkgo\\db\\connector\\' . Strings::ucwords(self::$config['type'], '_');
      }

      if (class_exists($_class)) {
          self::$instance = $_class::instance(self::$config); // 实例化数据库驱动
      } else {
        $_obj_excpt = new Db_Except('Unsupported database type', 500);

        $_obj_excpt->setData('err_detail', $_class);

        throw $_obj_excpt;
      }
    }

    return self::$instance;
  }


  /** 数据库配置
   * config function.
   *
   * @access public
   * @static
   * @param array $config (default: array()) 数据库配置
   * @return void
   */
  public static function config($config = array()) {
    $_arr_config   = Config::get('dbconfig'); // 获取数据库配置

    $_arr_configDo = self::$configThis;

    if (is_array($_arr_config) && Func::notEmpty($_arr_config)) {
      $_arr_configDo = array_replace_recursive($_arr_configDo, $_arr_config); // 合并配置
    }

    if (is_array(self::$config) && Func::notEmpty(self::$config)) {
      $_arr_configDo = array_replace_recursive($_arr_configDo, self::$config); // 合并配置
    }

    if (is_array($config) && Func::notEmpty($config)) {
      $_arr_configDo = array_replace_recursive($_arr_configDo, $config); // 合并配置
    }

    self::$config  = $_arr_configDo;

    self::$init    = true; // 标识为已初始化
  }


  /** 魔术静态调用
   * __callStatic function.
   *
   * @access public
   * @static
   * @param string $method 数据库方法
   * @param string $params 参数
   * @return 数据库实例
   */
  public static function __callStatic($method, $params) {
    $_obj_connect = self::connect(); // 连接数据库

    if (method_exists($_obj_connect, $method)) {
      return call_user_func_array(array($_obj_connect, $method), $params); // 调用数据库驱动方法
    } else {
      $_obj_excpt = new Db_Except('Method not found: ' . __CLASS__ . '::' . $method, 500);
      $_obj_excpt->setData('err_detail', $method);

      throw $_obj_excpt;
    }
  }
}
