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

// 会话处理
class Session {

  public static $config = array(); // 配置

  // 默认配置
  private static $configThis = array(
    'autostart'     => false, //自动开始
    'name'          => '', //Session ID 名称
    'type'          => 'file', //类型 (可选 db,file)
    'path'          => '', //保存路径 (默认为 /runtime/session)
    'prefix'        => 'ginkgo_', //前缀
    'cookie_domain' => '', //cookie 域名
    'life_time'     => 1200, // session 生存时间
  );

  private static $init; // 是否初始化标志

  /** 初始化
   * init function.
   *
   * @access public
   * @static
   * @param array $config (default: array()) 会话配置
   * @return void
   */
  public static function init($config = array()) {
    $_do_start = false;

    self::config($config);

    if (isset(self::$config['type']) && Func::notEmpty(self::$config['type']) && self::$config['type'] != 'file') { // 如果指定了驱动类型, 且不是文件类型
      if (strpos(self::$config['type'], '\\')) { // 如果类型包含命名空间则直接使用
        $_class = self::$config['type'];
      } else { // 否则补全命名空间
        $_class = 'ginkgo\\session\\driver\\' . Strings::ucwords(self::$config['type'], '_');
      }

      // 检查驱动类
      if (class_exists($_class)) {
        $_obj_session = $_class::instance(self::$config); // 实例化驱动

        $_arr_return = session_set_save_handler(array($_obj_session, 'open'), array($_obj_session, 'close'), array($_obj_session, 'read'), array($_obj_session, 'write'), array($_obj_session, 'destroy'), array($_obj_session, 'gc')); // 定义处理函数
      } else { // 报错
        $_obj_excpt = new Class_Not_Found('Session driver not found', 500);

        $_obj_excpt->setData('err_detail', $_class);

        throw $_obj_excpt;
      }
    } else {
      if (Func::isEmpty(self::$config['path'])) {
        $_str_sessionPath = GK_PATH_SESSION;
      } else {
        $_str_sessionPath = self::$config['path'];
      }

      File::instance()->dirMk($_str_sessionPath);

      session_save_path($_str_sessionPath);
    }

    if (Func::notEmpty(self::$config['cookie_domain'])) {
      ini_set('session.cookie_domain', self::$config['cookie_domain']);
    }

    if (isset(self::$config['name']) && Func::notEmpty(self::$config['name'])) {
      session_name(self::$config['name']);
    }

    //print_r(session_id());

    if (self::$config['autostart'] === true && Func::isEmpty(session_id())) {
      //print_r('auto_start');
      ini_set('session.auto_start', 0);
      $_do_start = true;
    }

    if ($_do_start) {
      //print_r('auto_start');
      session_start();
      self::$init = true;
    } else {
      self::$init = false;
    }
  }


  // 配置 since 0.2.0
  public static function config($config = array()) {
    $_arr_config   = Config::get('session'); // 取得配置

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
  }


  /** 设置, 获取前缀
   * prefix function.
   *
   * @access public
   * @static
   * @param string $prefix (default: '')
   * @return 如果参数为空则返回前缀, 否则无返回
   */
  public static function prefix($prefix = '') {
    if (Func::isEmpty(self::$init)) {
      self::init();
    }

    if (Func::isEmpty($prefix)) {
      return self::$config['prefix'];
    } else {
      self::$config['prefix'] = $prefix;
    }
  }


  /** 设置会话变量
   * set function.
   *
   * @access public
   * @static
   * @param mixed $name 名称
   * @param mixed $value 值
   * @param string $prefix (default: '') 前缀
   * @return void
   */
  public static function set($name, $value, $prefix = '') {
    if (Func::isEmpty(self::$init)) {
      //print_r('boot');
      self::init();
    }

    $_arr_prefix = self::prefixProcess($prefix); // 前缀处理

    if (Func::notEmpty($_arr_prefix[0]) && isset($_arr_prefix[1]) && Func::notEmpty($_arr_prefix[1])) {
      $_SESSION[$_arr_prefix[0]][$_arr_prefix[1]][$name] = $value;
    } else if (Func::notEmpty($_arr_prefix[0])) {
      $_SESSION[$_arr_prefix[0]][$name] = $value;
    } else {
      $_SESSION[$name] = $value;
    }

    //print_r($_SESSION);
  }


  /** 读取会话变量
   * get function.
   *
   * @access public
   * @static
   * @param mixed $name 名称
   * @param string $prefix (default: '') 前缀
   * @return 变量
   */
  public static function get($name, $prefix = '') {
    $_value = null;

    if (Func::isEmpty(self::$init)) {
      //print_r('boot');
      self::init();
    }

    $_arr_prefix = self::prefixProcess($prefix);

    /*print_r($_arr_prefix);
    print_r('<br>');*/

    if (Func::notEmpty($_arr_prefix[0]) && isset($_arr_prefix[1]) && Func::notEmpty($_arr_prefix[1]) && isset($_SESSION[$_arr_prefix[0]][$_arr_prefix[1]][$name])) {
      $_value = $_SESSION[$_arr_prefix[0]][$_arr_prefix[1]][$name];
    } else if (Func::notEmpty($_arr_prefix[0]) && isset($_SESSION[$_arr_prefix[0]][$name])) {
      $_value = $_SESSION[$_arr_prefix[0]][$name];
    } else if (isset($_SESSION[$name])) {
      $_value = $_SESSION[$name];
    }

    //print_r($_value);

    return $_value;
  }


  /** 删除会话变量
   * delete function.
   *
   * @access public
   * @static
   * @param mixed $name 名称
   * @param string $prefix (default: '') 前缀
   * @return void
   */
  public static function delete($name, $prefix = '') {
    if (Func::isEmpty(self::$init)) {
      self::init();
    }

    $_arr_prefix = self::prefixProcess($prefix);

    if (Func::notEmpty($_arr_prefix[0]) && isset($_arr_prefix[1]) && Func::notEmpty($_arr_prefix[1]) && isset($_SESSION[$_arr_prefix[0]][$_arr_prefix[1]][$name])) {
      unset($_SESSION[$_arr_prefix[0]][$_arr_prefix[1]][$name]);
    } else if (Func::notEmpty($_arr_prefix[0]) && isset($_SESSION[$_arr_prefix[0]][$name])) {
      unset($_SESSION[$_arr_prefix[0]][$name]);
    } else if (isset($_SESSION[$name])) {
      unset($_SESSION[$name]);
    }
  }


  /** 前缀处理
   * prefixProcess function.
   *
   * @access private
   * @static
   * @param string $prefix (default: '') 前缀
   * @return 前缀
   */
  private static function prefixProcess($prefix) {
    if (Func::isEmpty($prefix)) {
      $_str_prefix = self::$config['prefix'];
    } else {
      $_str_prefix = $prefix;
    }

    $_arr_prefix = explode('.', $_str_prefix);

    return $_arr_prefix;
  }
}
