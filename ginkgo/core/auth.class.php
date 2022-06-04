<?php
/*-----------------------------------------------------------------
！！！！警告！！！！
以下为系统文件，请勿修改
-----------------------------------------------------------------*/

namespace ginkgo;

// 不能非法包含或直接执行
if (!defined('IN_GINKGO')) {
  return 'Access denied';
}

// 认证处理类
class Auth {

  public $prefix = 'user';
  public $options = array(
    'cookie'    => true,
    'remember'  => false,
  );

  public $config = array();
  public $error;

  protected static $instance; // 当前实例

  private $session;
  private $cookie;
  private $remember;

  // 默认配置
  private $configThis = array(
    'session_expire'    => 1200,
    'remember_expire'   => 2592000,
  );

  protected function __construct($config = array(), $prefix = '') {
    $this->config($config);
    $this->prefix($prefix);
  }

  protected function __clone() { }

  /** 实例化
   * instance function.
   *
   * @access public
   * @static
   * @return 当前类的实例
   */
  public static function instance($config = array(), $prefix = '') {
    if (Func::isEmpty(self::$instance)) {
      self::$instance = new static($config, $prefix);
    }

    return self::$instance;
  }


  /** 读取认证信息
   * read function.
   *
   * @access public
   * @return 认证信息
   */
  public function read() {
    $_sessionTime    = Session::get($this->prefix . '_time');

    $_arr_session = array(
      $this->prefix . '_id'     => Session::get($this->prefix . '_id'),
      $this->prefix . '_name'   => Session::get($this->prefix . '_name'),
      $this->prefix . '_hash'   => Session::get($this->prefix . '_hash'),
      $this->prefix . '_time'   => $_sessionTime,
      $this->prefix . '_expire' => $_sessionTime + $this->config['session_expire'],
    );

    $_cookieTime    = (int)Cookie::get($this->prefix . '_time');

    $_arr_cookie = array(
      $this->prefix . '_id'     => Cookie::get($this->prefix . '_id'),
      $this->prefix . '_name'   => Cookie::get($this->prefix . '_name'),
      $this->prefix . '_hash'   => Cookie::get($this->prefix . '_hash'),
      $this->prefix . '_time'   => $_cookieTime,
      $this->prefix . '_expire' => $_cookieTime + $this->config['session_expire'],
    );

    $_rememberTime    = (int)Cookie::get('remember_' . $this->prefix . '_time');

    $_arr_remember = array(
      $this->prefix . '_id'     => Cookie::get('remember_' . $this->prefix . '_id'),
      $this->prefix . '_name'   => Cookie::get('remember_' . $this->prefix . '_name'),
      $this->prefix . '_hash'   => Cookie::get('remember_' . $this->prefix . '_hash'),
      $this->prefix . '_time'   => $_rememberTime,
      $this->prefix . '_expire' => $_rememberTime + $this->config['remember_expire'],
    );

    $this->session  = $_arr_session;
    $this->cookie   = $_arr_cookie;
    $this->remember = $_arr_remember;

    return array(
      'session'   => $_arr_session,
      'cookie'    => $_arr_cookie,
      'remember'  => $_arr_remember,
    );
  }


  /** 写入认证信息
   * write function.
   *
   * @access public
   * @param mixed $authRow 认证信息
   * @param bool $regen (default: false) 是否使用新生成的会话 ID
   * @param string $loginType (default: 'form') 登录类型
   * @param string $remember (default: '') 是否记住密码
   * @param string $pathCookie (default: '/') Cookie 保存路径
   * @return void
   */
  public function write($authRow, $regen = false, $loginType = 'form', $remember = '', $pathCookie = '/') {
    if (!$this->checkParam($authRow)) {
      return false;
    }

    if ($regen) {
      session_regenerate_id(true); // 使用新生成的会话 ID 更新现有会话 ID
    }

    $_arr_options = $this->options;

    $_arr_optCookie = array(
      'expire'    => GK_NOW + 2592000, // Cookie 过期时间
      'path'      => $pathCookie,
    );

    //print_r($authRow);

    Session::set($this->prefix . '_id', $authRow[$this->prefix . '_id']);
    Session::set($this->prefix . '_name', $authRow[$this->prefix . '_name']);
    Session::set($this->prefix . '_time', GK_NOW);
    Session::set($this->prefix . '_hash', $this->hashProcess($authRow));
    Session::set($this->prefix . '_login_type', $loginType);

    if ($_arr_options['cookie'] === true || $_arr_options['cookie'] === 'true') {
      Cookie::set($this->prefix . '_id', $authRow[$this->prefix . '_id'], $_arr_optCookie);
      Cookie::set($this->prefix . '_name', $authRow[$this->prefix . '_name'], $_arr_optCookie);
      Cookie::set($this->prefix . '_time', GK_NOW, $_arr_optCookie);
      Cookie::set($this->prefix . '_hash', $this->hashProcess($authRow), $_arr_optCookie);
      Cookie::set($this->prefix . '_login_type', $loginType, $_arr_optCookie);
    }

    if ($remember === 'remember' || $remember === true || $remember === 'true') {
      $_arr_options['remember'] = true;
    }

    if ($_arr_options['remember'] === true || $_arr_options['remember'] === 'true') {
      $_arr_optCookie['expire'] = $this->config['remember_expire']; // 保存密码过期时间

      Cookie::set('remember_' . $this->prefix . '_id', $authRow[$this->prefix . '_id'], $_arr_optCookie);
      Cookie::set('remember_' . $this->prefix . '_name', $authRow[$this->prefix . '_name'], $_arr_optCookie);
      Cookie::set('remember_' . $this->prefix . '_hash', $this->hashProcess($authRow), $_arr_optCookie);
      Cookie::set('remember_' . $this->prefix . '_time', GK_NOW, $_arr_optCookie);
    }

    $this->options = $_arr_options;

    return true;
  }


  /** 结束登录
   * end function.
   *
   * @access public
   * @param int $id (default: 0)
   * @return void
   */
  public function end($regen = false, $pathCookie = '/') {
    $_arr_optCookie = array(
      'path'      => $pathCookie,
    );

    Session::delete($this->prefix . '_id');
    Session::delete($this->prefix . '_name');
    Session::delete($this->prefix . '_time');
    Session::delete($this->prefix . '_hash');
    Cookie::delete($this->prefix . '_id', $_arr_optCookie);
    Cookie::delete($this->prefix . '_name', $_arr_optCookie);
    Cookie::delete($this->prefix . '_time', $_arr_optCookie);
    Cookie::delete($this->prefix . '_hash', $_arr_optCookie);
    Cookie::delete('remember_' . $this->prefix . '_id', $_arr_optCookie);
    Cookie::delete('remember_' . $this->prefix . '_name', $_arr_optCookie);
    Cookie::delete('remember_' . $this->prefix . '_hash', $_arr_optCookie);
    Cookie::delete('remember_' . $this->prefix . '_time', $_arr_optCookie);

    if ($regen) {
      session_regenerate_id(true); // 使用新生成的会话 ID 更新现有会话 ID
    }
  }


  /** 验证认证信息
   * check function.
   *
   * @access public
   * @param mixed $authRow
   * @param string $pathCookie (default: '/')
   * @return bool
   */
  public function check($authRow, $pathCookie = '/') {
    $_arr_session   = $this->session;
    $_arr_cookie    = $this->cookie;
    $_arr_remember  = $this->remember;

    $_arr_options = $this->options;

    if (!$this->checkParam($authRow)) {
      $this->end();
      return false;
    }

    if ($this->haveSession()) {
      if ($authRow[$this->prefix . '_id'] != $_arr_session[$this->prefix . '_id']) {
        $this->end();
        $this->errRecord('Auth::check(), Session ID is incorrect: ' . $this->prefix . '_id');

        return false;
      }

      if ($authRow[$this->prefix . '_name'] != $_arr_session[$this->prefix . '_name']) {
        $this->end();
        $this->errRecord('Auth::check(), Session name is incorrect: ' . $this->prefix . '_name');
        return false;
      }

      if ($this->hashProcess($authRow) != $_arr_session[$this->prefix . '_hash']) {
        $this->end();
        $this->errRecord('Auth::check(), Session hash is incorrect: ' . $this->prefix . '_hash');
        return false;
      }

      if ($_arr_options['cookie'] === true || $_arr_options['cookie'] === 'true') {
        if ($authRow[$this->prefix . '_id'] != $_arr_cookie[$this->prefix . '_id']) {
          $this->end();
          $this->errRecord('Auth::check(), Cookie ID is incorrect: ' . $this->prefix . '_id');
          return false;
        }

        if ($authRow[$this->prefix . '_name'] != $_arr_cookie[$this->prefix . '_name']) {
          $this->end();
          $this->errRecord('Auth::check(), Cookie name is incorrect: ' . $this->prefix . '_name');
          return false;
        }

        if ($this->hashProcess($authRow) != $_arr_cookie[$this->prefix . '_hash']){
          $this->end();
          $this->errRecord('Auth::check(), Cookie hash is incorrect: ' . $this->prefix . '_hash');
          return false;
        }
      }

      $this->write($authRow, false, 'form', '', $pathCookie);
    } else if ($this->haveRemenber()) {
      if ($authRow[$this->prefix . '_id'] != $_arr_remember[$this->prefix . '_id']) {
        $this->end();
        $this->errRecord('Auth::check(), Remember ID is incorrect: ' . $this->prefix . '_id');
        return false;
      }

      if ($authRow[$this->prefix . '_name'] != $_arr_remember[$this->prefix . '_name']) {
        $this->end();
        $this->errRecord('Auth::check(), Remember name is incorrect: ' . $this->prefix . '_name');
        return false;
      }

      if ($this->hashProcess($authRow) != $_arr_remember[$this->prefix . '_hash']){
        $this->end();
        $this->errRecord('Auth::check(), Remember hash is incorrect: ' . $this->prefix . '_hash');
        return false;
      }

      $this->write($authRow, false, 'auto', '', $pathCookie);
    } else {
      $this->end();
      return false;
    }

    return true;
  }


  /** 配置
   * prefix function.
   * since 0.2.0
   * @access public
   * @param string $config (default: array()) 配置
   * @return
   */
  public function config($config = array()) {
    $_arr_config    = Config::get('auth');

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

    $this->config   = $_arr_configDo;
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
      return $this->prefix;
    } else if (is_string($prefix)) {
      $this->prefix = $prefix;
    }
  }


  public function setOptions($name, $value = '') {
    if (is_array($name)) {
      $this->options = array_replace_recursive($this->options, $name);
    } else if (is_string($name)) {
      $this->options[$name] = $value;
    }
  }

  public function getOptions($name = '') {
    $_return = '';

    if (Func::isEmpty($name)) {
      $_return = $this->options;
    } else {
      if (isset($this->options[$name])) {
        $_return = $this->options[$name];
      }
    }

    return $_return;
  }


  /** 取得错误消息
   * getError function.
   *
   * @access public
   * @return 错误消息
   */
  public function getError() {
    return $this->error;
  }


  /** 验证 authRow 结构
   * checkParam function.
   *
   * @access private
   * @return void
   */
  private function checkParam($authRow) {
    if (!isset($authRow[$this->prefix . '_id'])) {
      $this->errRecord('Auth::checkParam(), Missing auth ID: ' . $this->prefix . '_id');
      return false;
    }
    if (!isset($authRow[$this->prefix . '_name'])) {
      $this->errRecord('Auth::checkParam(), Missing auth name: ' . $this->prefix . '_name');
      return false;
    }
    if (!isset($authRow[$this->prefix . '_time_login'])) {
      $this->errRecord('Auth::checkParam(), Missing auth login time: ' . $this->prefix . '_time_login');
      return false;
    }
    if (!isset($authRow[$this->prefix . '_ip'])) {
      $this->errRecord('Auth::checkParam(), Missing auth IP: ' . $this->prefix . '_ip');
      return false;
    }

    return true;
  }


  /** 是否有会话
   * haveSession function.
   *
   * @access private
   * @return bool
   */
  private function haveSession() {
    $_arr_options   = $this->options;
    $_arr_session   = $this->session;

    if (Func::isEmpty($_arr_session[$this->prefix . '_id'])) {
      $this->errRecord('Auth::haveSession(), Missing session ID: ' . $this->prefix . '_id');
      return false;
    }
    if (Func::isEmpty($_arr_session[$this->prefix . '_name'])) {
      $this->errRecord('Auth::haveSession(), Missing session name: ' . $this->prefix . '_name');
      return false;
    }
    if (Func::isEmpty($_arr_session[$this->prefix . '_time'])) {
        $this->errRecord('Auth::haveSession(), Missing session time: ' . $this->prefix . '_time');
        return false;
    }
    if (Func::isEmpty($_arr_session[$this->prefix . '_hash'])) {
      $this->errRecord('Auth::haveSession(), Missing session hash: ' . $this->prefix . '_hash');
      return false;
    }

    if ($_arr_session[$this->prefix . '_expire'] < GK_NOW) {
      $this->errRecord('Auth::haveSession(), Session expired');
      return false;
    }

    if ($_arr_options['cookie'] === true || $_arr_options['cookie'] === 'true') {
      $_arr_cookie    = $this->cookie;

      if (Func::isEmpty($_arr_cookie[$this->prefix . '_id'])) {
        $this->errRecord('Auth::haveSession(), Missing cookie ID: ' . $this->prefix . '_id');
        return false;
      }
      if (Func::isEmpty($_arr_cookie[$this->prefix . '_name'])) {
        $this->errRecord('Auth::haveSession(), Missing cookie name: ' . $this->prefix . '_name');
        return false;
      }
      if (Func::isEmpty($_arr_cookie[$this->prefix . '_time'])) {
        $this->errRecord('Auth::haveSession(), Missing cookie time: ' . $this->prefix . '_time');
        return false;
      }
      if (Func::isEmpty($_arr_cookie[$this->prefix . '_hash'])) {
        $this->errRecord('Auth::haveSession(), Missing cookie hash: ' . $this->prefix . '_hash');
        return false;
      }

      if ($_arr_cookie[$this->prefix . '_expire'] < GK_NOW) {
        $this->errRecord('Auth::haveSession(), Cookie expired');
        return false;
      }
    }

    return true;
  }


  /** 是否有记住登录状态
   * haveRemenber function.
   *
   * @access private
   * @return void
   */
  private function haveRemenber() {
    $_arr_options   = $this->options;

    if ($_arr_options['remember'] !== true || $_arr_options['remember'] !== 'true') {
      $this->errRecord('Auth::haveRemenber(), Remember off');
      return false;
    }

    $_arr_remember  = $this->remember;

    if (Func::isEmpty($_arr_remember[$this->prefix . '_id'])) {
      $this->errRecord('Auth::haveRemenber(), Missing remember ID: ' . $this->prefix . '_id');
      return false;
    }
    if (Func::isEmpty($_arr_remember[$this->prefix . '_name'])) {
      $this->errRecord('Auth::haveRemenber(), Missing remember name: ' . $this->prefix . '_name');
      return false;
    }
    if (Func::isEmpty($_arr_remember[$this->prefix . '_time'])) {
      $this->errRecord('Auth::haveRemenber(), Missing remember time: ' . $this->prefix . '_time');
      return false;
    }
    if (Func::isEmpty($_arr_remember[$this->prefix . '_hash'])) {
      $this->errRecord('Auth::haveRemenber(), Missing remember hash: ' . $this->prefix . '_hash');
      return false;
    }

    if ($_arr_remember[$this->prefix . '_expire'] < GK_NOW) {
      $this->errRecord('Auth::haveRemenber(), Remember expired');
      return false;
    }

    return true;
  }


  /** 哈希处理
   * hashProcess function.
   *
   * @access private
   * @param mixed $authRow
   * @return 哈希值
   */
  private function hashProcess($authRow) {
    $_str_return = '';

    if (isset($authRow[$this->prefix . '_id']) && isset($authRow[$this->prefix . '_name']) && isset($authRow[$this->prefix . '_time_login']) && isset($authRow[$this->prefix . '_ip'])) {
      $_str_return = Crypt::crypt($authRow[$this->prefix . '_id'] . $authRow[$this->prefix . '_name'] . $authRow[$this->prefix . '_time_login'], $authRow[$this->prefix . '_ip']);
    }

    return $_str_return;
  }

  private function errRecord($msg) { // since 0.2.4
    $this->error      = $msg;
    $_bool_debugDump  = false;
    $_mix_configDebug = Config::get('debug'); // 取得调试配置

    if (is_array($_mix_configDebug)) {
      if ($_mix_configDebug['dump'] === true || $_mix_configDebug['dump'] === 'true' || $_mix_configDebug['dump'] === 'trace') { // 假如配置为输出
        $_bool_debugDump = true;
      }
    } else if (is_scalar($_mix_configDebug)) {
      if ($_mix_configDebug === true || $_mix_configDebug === 'true' || $_mix_configDebug === 'trace') { // 假如配置为输出
        $_bool_debugDump = true;
      }
    }

    if ($_bool_debugDump) {
      Log::record('type: ginkgo\Auth, msg: ' . $msg, 'log');
    }
  }
}
