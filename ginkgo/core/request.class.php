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

// 请求处理类
class Request {

  // 默认路由, 一般由 ginkgo/Route 类定义, 避免直接使用
  public $route = array(
    'mod'   => 'index',
    'ctrl'  => 'index',
    'act'   => 'index',
  );

  // 默认原始路由, 一般由 ginkgo/Route 类定义, 避免直接使用
  public $routeOrig = array(
    'mod'   => 'index',
    'ctrl'  => 'index',
    'act'   => 'index',
  );

  // 参数, 一般由 ginkgo/Route 类定义, 避免直接使用
  public $param = array();

  protected static $instance; // 当前实例

  // 常用 mime
  protected $mimeType = array(
    'json'  => array(
      'application/json',
      'text/x-json',
      'application/jsonrequest',
      'text/json',
    ),
    'html'  => array(
      'text/html',
      'application/xhtml+xml',
      '*/*',
    ),
    'xml'   => array(
      'application/xml',
      'text/xml',
      'application/x-xml',
    ),
    'js'    => array(
      'text/javascript',
      'application/javascript',
      'application/x-javascript',
    ),
    'css'   => array(
      'text/css',
    ),
    'rss'   => array(
      'application/rss+xml',
    ),
    'yaml'  => array(
      'application/x-yaml',
      'text/yaml',
    ),
    'atom'  => array(
      'application/atom+xml',
    ),
    'pdf'   => array(
      'application/pdf',
    ),
    'text'  => array(
      'text/plain',
    ),
    'image' => array(
      'image/png',
      'image/jpg',
      'image/jpeg',
      'image/pjpeg',
      'image/gif',
      'image/webp',
      'image/*',
    ),
    'csv'   => array(
      'text/csv',
    ),
  );

  protected function __construct() { }

  protected function __clone() { }

  /** 实例化
   * instance function.
   *
   * @access public
   * @static
   * @return 当前类的实例
   */
  public static function instance() {
    if (Func::isEmpty(self::$instance)) {
      self::$instance = new static();
    }

    return self::$instance;
  }


  /** 设置路由信息
   * setRoute function.
   *
   * @access public
   * @param mixed $name 路由名称
   * @param string $value (default: '') 路由值
   * @return void
   */
  public function setRoute($name, $value = '') {
    $_arr_route = array();

    if (is_array($name)) {
      if (isset($name['mod'])) {
        $_arr_route['mod']  = $name['mod'];
      }

      if (isset($name['ctrl'])) {
        $_arr_route['ctrl'] = $name['ctrl'];
      }

      if (isset($name['act'])) {
        $_arr_route['act']  = $name['act'];
      }
    } else if (is_scalar($name)) {
      if (in_array($name, $this->route) && Func::isEmpty($value)) {
        $_arr_route[$name] = $value;
      }
    }

    $this->route = array_replace_recursive($this->route, $_arr_route);
  }


  /** 设置原始路由信息
   * setRouteOrig function.
   *
   * @access public
   * @param mixed $name 路由名称
   * @param string $value (default: '') 路由值
   * @return void
   */
  public function setRouteOrig($name, $value = '') {
    $_arr_routeOrig = array();

    if (is_array($name)) {
      if (isset($name['mod'])) {
        $_arr_routeOrig['mod']  = $name['mod'];
      }

      if (isset($name['ctrl'])) {
        $_arr_routeOrig['ctrl'] = $name['ctrl'];
      }

      if (isset($name['act'])) {
        $_arr_routeOrig['act']  = $name['act'];
      }
    } else if (is_scalar($name)) {
      if (in_array($name, $this->routeOrig) && Func::isEmpty($value)) {
        $_arr_routeOrig[$name] = $value;
      }
    }

    $this->routeOrig = array_replace_recursive($this->routeOrig, $_arr_routeOrig);
  }


  /** 设置参数信息
   * setParam function.
   *
   * @access public
   * @param mixed $name 参数名称
   * @param string $value (default: '') 参数值
   * @return void
   */
  public function setParam($name, $value = '') {
    $_arr_param = array();

    if (is_array($name)) {
      foreach ($name as $_key=>$_value) {
        $_arr_param[$_key]  = $_value;
      }
    } else if (is_scalar($name)) {
      $_arr_param[$name] = $value;
    }

    $this->param = array_replace_recursive($this->param, $_arr_param);
  }

  /** 取得路由信息
   * route function.
   *
   * @access public
   * @param mixed $name (default: false) 名称
   * @return 值
   */
  public function route($name = false) {
    $_mix_return = '';

    if ($name === false) {
      $_mix_return = $this->route;
    } else {
      if (isset($this->route[$name])) {
        $_mix_return = $this->route[$name];
      }
    }

    return $_mix_return;
  }

  /** 取得原始路由信息
   * route function.
   *
   * @access public
   * @param mixed $name (default: false) 名称
   * @return 值
   */
  public function routeOrig($name = false) {
    $_mix_return = '';

    if ($name === false) {
      $_mix_return = $this->routeOrig;
    } else {
      if (isset($this->routeOrig[$name])) {
        $_mix_return = $this->routeOrig[$name];
      }
    }

    return $_mix_return;
  }


  /** 取得参数变量
   * param function.
   *
   * @access public
   * @param mixed $name (default: true) 名称
   * @param string $type (default: 'str') 期待类型
   * @param string $default (default: '') 默认值
   * @param bool $htmldecode (default: false) html 解码
   * @return 变量
   */
  public function param($name = true, $type = 'str', $default = '', $htmldecode = false) {
    return $this->varProcessR($name, $type, $default, $htmldecode, $this->param);
  }


  /** 取得 $_GET 变量
   * get function.
   *
   * @access public
   * @param mixed $name (default: true) 变量名
   * @param string $type (default: 'str') 期待类型
   * @param string $default (default: '') 默认值
   * @param bool $htmldecode (default: false) html 解码
   * @return 变量
   */
  public function get($name = true, $type = 'str', $default = '', $htmldecode = false) {
    return $this->varProcessR($name, $type, $default, $htmldecode, $_GET);
  }


  /** 取得 $_POST 变量
   * post function.
   *
   * @access public
   * @param mixed $name (default: true) 变量名
   * @param string $type (default: 'str') 期待类型
   * @param string $default (default: '') 默认值
   * @param bool $htmldecode (default: false) html 解码
   * @return 变量
   */
  public function post($name = true, $type = 'str', $default = '', $htmldecode = false) {
    return $this->varProcessR($name, $type, $default, $htmldecode, $_POST);
  }


  /** 取得 $_REQUEST 变量
   * post function.
   *
   * @access public
   * @param mixed $name (default: true) 变量名
   * @param string $type (default: 'str') 期待类型
   * @param string $default (default: '') 默认值
   * @param bool $htmldecode (default: false) html 解码
   * @return 变量
   */
  public function request($name = true, $type = 'str', $default = '', $htmldecode = false) {
    return $this->varProcessR($name, $type, $default, $htmldecode, $_REQUEST);
  }


  /** 取得 $_SERVER 变量
   * server function.
   *
   * @access public
   * @param mixed $name (default: true) 变量名
   * @return 变量
   */
  public function server($name = '') {
    return $this->varProcessS($name, $_SERVER);
  }

  /** 取得 $_SESSION 变量
   * server function.
   *
   * @access public
   * @param mixed $name (default: true) 变量名
   * @return 变量
   */
  public function session($name = '') {
    return $this->varProcessS($name, $_SESSION);
  }

  /** 取得 $_COOKIE 变量
   * server function.
   *
   * @access public
   * @param mixed $name (default: true) 变量名
   * @return 变量
   */
  public function cookie($name = '') {
    return $this->varProcessS($name, $_COOKIE);
  }

  /** 取得期待的返回 mime
   * accept function.
   *
   * @access public
   * @return mime
   */
  public function accept() {
    return $this->server('HTTP_ACCEPT');
  }


  /** 取得期待的返回类型
   * type function.
   *
   * @access public
   * @return 返回类型
   */
  public function type() {
    $_str_type   = '';
    $_str_accept = $this->accept();

    foreach ($this->mimeType as $_key=>$_value) {
      foreach ($_value as $_key_sub=>$_value_sub) {
        if (stristr($_str_accept, $_value_sub)) {
          $_str_type = $_key;
          break;
        }
      }
    }

    return $_str_type;
  }


  // 设置 mime 类型
  public function mimeType($type, $value = '') {
    if (is_array($type)) {
      $this->mimeType = array_replace_recursive($this->mimeType, $type);
    } else if (is_string($type)) {
      if (isset($this->mimeType[$type])) {
        if (is_array($value)) {
          $this->mimeType[$type] = array_merge($this->mimeType[$type], $value);
        } else if (is_string($value)) {
          $this->mimeType[$type][] = $value;
        }
      } else {
        $this->mimeType[$type] = $value;
      }
    }
  }


  /** 取得 IP 地址
   * ip function.
   *
   * @access public
   * @return IP
   */
  public function ip() {
    if (Func::notEmpty($this->server('REMOTE_ADDR'))) {
      $_str_ip = $this->server('REMOTE_ADDR');
    } else if (Func::notEmpty(getenv('REMOTE_ADDR'))) {
      $_str_ip = getenv('REMOTE_ADDR');
    } else {
      $_str_ip = '0.0.0.0';
    }

    return $_str_ip;
  }


  /** 取得请求方法
   * method function.
   *
   * @access public
   * @return 请求方法
   */
  public function method() {
    $_str_method = 'GET';

    //print_r($this->server('HTTP_X_HTTP_METHOD_OVERRIDE'));

    if (Func::notEmpty($this->server('HTTP_X_HTTP_METHOD_OVERRIDE'))) {
      $_str_method = $this->server('HTTP_X_HTTP_METHOD_OVERRIDE');
    } else if (Func::notEmpty($this->server('REQUEST_METHOD'))) {
      $_str_method = $this->server('REQUEST_METHOD');
    }

    return strtoupper($_str_method);
  }


  /**
   * 是否为 GET 请求
   * @access public
   * @return bool
   */
  public function isGet() {
    return $this->method() == 'GET';
  }

  /**
   * 是否为 POST 请求
   * @access public
   * @return bool
   */
  public function isPost() {
    return $this->method() == 'POST';
  }


  /**
   * 是否为 AJAX 请求
   * @access public
   * @return bool
   */
  public function isAjax() {
    $_str_value  = $this->server('HTTP_X_REQUESTED_WITH');
    $_str_value  = strtolower($_str_value);

    return $_str_value == 'xmlhttprequest';
  }

  /**
   * 是否为 PJAX 请求
   * @access public
   * @return bool
   */
  public function isPjax() {
    $_str_value = $this->server('HTTP_X_PJAX');

    return Func::notEmpty($_str_value);
  }

  /**
   * 是否为 SSL 请求
   * @access public
   * @return bool
   */
  public function isSsl() {
    $_status = false;

    if ($this->server('HTTPS') == '1' || strtolower($this->server('HTTPS')) === 'on') {
      $_status = true;
    } else if ($this->server('REQUEST_SCHEME') == 'https') {
      $_status = true;
    } else if ($this->server('SERVER_PORT') == '443') {
      $_status = true;
    } else if ($this->server('HTTP_X_FORWARDED_PROTO') == 'https') {
      $_status = true;
    }

    return $_status;
  }

  /**
   * 是否为移动端
   * @access public
   * @return bool
   */
  public function isMobile() {
    $_status = false;

    if (Func::notEmpty($this->server('HTTP_VIA')) && stristr($this->server('HTTP_VIA'), 'wap')) {
      $_status = true;
    } else if (Func::notEmpty($this->server('HTTP_ACCEPT')) && strpos(strtoupper($this->server('HTTP_ACCEPT')), 'VND.WAP.WML')) {
      $_status = true;
    } else if (Func::notEmpty($this->server('HTTP_X_WAP_PROFILE')) || Func::notEmpty($this->server('HTTP_PROFILE'))) {
      $_status = true;
    } else if (Func::notEmpty($this->server('HTTP_USER_AGENT')) && preg_match('/(blackberry|configuration\/cldc|hp |hp-|htc |htc_|htc-|iemobile|kindle|midp|mmp|motorola|mobile|nokia|opera mini|opera |Googlebot-Mobile|YahooSeeker\/M1A1-R2D2|android|iphone|ipod|mobi|palm|palmos|pocket|portalmmm|ppc;|smartphone|sonyericsson|sqh|spv|symbian|treo|up.browser|up.link|vodafone|windows ce|xda |xda_)/i', $this->server('HTTP_USER_AGENT'))) {
      $_status = true;
    }

    return $_status;
  }


  /** 取得当前应用根目录 url
   * root function.
   *
   * @access public
   * @param bool $with_domain (default: false) 是否包含域名
   * @return 应用根目录
   */
  public function root($with_domain = false) {
    $_str_baseFile  = $this->baseFile();
    $_str_baseFile  = str_replace('\\', '/', dirname($_str_baseFile));
    $_str_root      = rtrim($_str_baseFile, '/\\');

    if ($with_domain) {
      $_str_root = $this->domain() . $_str_root;
    }

    return Func::fixDs(strtolower($_str_root), '/');
  }


  /** 取得当前 url
   * url function.
   *
   * @access public
   * @param bool $with_domain (default: false) 是否包含域名
   * @return 当前 url
   */
  public function url($with_domain = false) {
    $_str_url = '';

    if (Func::notEmpty($this->server('HTTP_X_REWRITE_URL'))) {
      $_str_url = $this->server('HTTP_X_REWRITE_URL');
    } else if (Func::notEmpty($this->server('REDIRECT_URL'))) {
      $_str_url = $this->server('REDIRECT_URL');
    } else if (Func::notEmpty($this->server('REQUEST_URI'))) {
      $_str_url = $this->server('REQUEST_URI');
    } else if (Func::notEmpty($this->server('ORIG_PATH_INFO'))) {
      $_str_url = $this->server('ORIG_PATH_INFO');

      if (Func::notEmpty($this->server('QUERY_STRING'))) {
        $_str_url .= '?' . $this->server('QUERY_STRING');
      }
    }

    if ($with_domain) {
      $_str_url = $this->domain() . $_str_url;
    }

    return strtolower($_str_url);
  }


  /** 取得当前域名 url
   * domain function.
   *
   * @access public
   * @return void
   */
  public function domain() {
    return strtolower($this->scheme() . '://' . $this->host());
  }

  /** 取得当前主机名 (一般为域名)
   * host function.
   *
   * @access public
   * @param bool $strict (default: false) 是否严格模式 (严格模式只包含域名部分)
   * @return void
   */
  public function host($strict = false) {
    if (Func::isEmpty($this->server('HTTP_X_REAL_HOST'))) {
      $_str_host = $this->server('HTTP_HOST');
    } else {
      $_str_host = $this->server('HTTP_X_REAL_HOST');
    }

    if ($strict && strpos($_str_host, ':')) {
      $_str_host = strstr($_str_host, ':', true);
    }

    return strtolower($_str_host);
  }

  /** 取得当前协议名
   * scheme function.
   *
   * @access public
   * @return 协议名
   */
  public function scheme() {
    return strtolower($this->isSsl() ? 'https' : 'http');
  }

  /** 取得头信息
   * header function.
   *
   * @access public
   * @param string $name (default: '') 名称
   * @param $separator $name (default: '-') 分隔符
   * @return 头信息
   */
  public function header($name = '', $separator = '-') {
    $_arr_header = array();
    $_mix_return = '';

    if (function_exists('apache_request_headers')) {
      $_arr_header = apache_request_headers();
    } else {
      $_arr_server = $this->server();
      foreach ($_arr_server as $_key => $_value) {
        if (strpos($_key, 'HTTP_') === 0) {
          $_key = str_replace('_', $separator, strtolower(substr($_key, 5)));
          $_arr_header[$_key] = $_value;
        }
      }
      if (isset($_arr_server['CONTENT_TYPE'])) {
        $_arr_header['content-type'] = $_arr_server['CONTENT_TYPE'];
      }
      if (isset($_arr_server['CONTENT_LENGTH'])) {
        $_arr_header['content-length'] = $_arr_server['CONTENT_LENGTH'];
      }
    }

    if (Func::notEmpty($_arr_header)) {
      $_arr_header = array_change_key_case($_arr_header);
    }

    if (Func::isEmpty($name)) {
      $_mix_return =  $_arr_header;
    } else {
      $name = str_replace('_', $separator, strtolower($name));

      if (isset($_arr_header[$name])) {
        $_mix_return = $_arr_header[$name];
      }
    }

    return $_mix_return;
  }


  /** 取得表单令牌
   * token function.
   *
   * @access public
   * @param string $name (default: '__token__') 令牌名称 (需要与表单名对应)
   * @param string $type (default: 'md5') 利用哪个函数生成令牌
   * @param string $renew (default: false) 验证完毕是否重新生成
   * @return 表单名称及令牌
   */
  public function token($name = '__token__', $type = 'md5', $renew = false) {
    if (!$name || Func::isEmpty($name)) {
      $_str_name = '__token__';
    } else {
      $_str_name = $name;
    }

    if (!$type || Func::isEmpty($type) || !is_callable($type)) {
      $_str_type = 'md5';
    } else {
      $_str_type = $type;
    }

    if (Func::isEmpty($this->server('REQUEST_TIME_FLOAT'))) {
      $_tm_time = GK_NOW;
    } else {
      $_tm_time = $this->server('REQUEST_TIME_FLOAT');
    }

    $_str_value  = Session::get($_str_name);

    if (Func::isEmpty($_str_value) || $renew) {
      $_str_value = call_user_func($_str_type, $_tm_time);

      Session::set($_str_name, $_str_value);
    }

    if ($this->isAjax() || $this->isPost()) {
      header($_str_name . ': ' . $_str_value);
    }

    return array(
      'name'  => $_str_name,
      'value' => $_str_value,
    );
  }


  /** 验证是否重复提交
   * checkDuplicate function.
   *
   * @access public
   * @param string $method (default: 'POST') 方法
   * @return void
   */
  public function checkDuplicate($method = 'POST') {
    $_str_input = $this->duplicateProcess($method);
    $_str_check = $this->cookie('check_duplicate');

    if (Func::isEmpty($_str_input)) {
      return false;
    }

    if (Func::isEmpty($_str_check)) {
      return false;
    }

    return $_str_check == $_str_input;
  }


  /** 设置用于防止重复提交的数据
   * setDuplicate function.
   *
   * @access public
   * @param string $method (default: 'POST') 方法
   * @return void
   */
  public function setDuplicate($method = 'POST') {
    $_str_input = $this->duplicateProcess($method);

    Cookie::set('check_duplicate', $_str_input);
  }


  /** 入口文件的路径
   * baseFile function.
   *
   * @access public
   * @return 路径
   */
  public function baseFile() {
    $_str_url = '';

    $_script_name = basename($this->server('SCRIPT_FILENAME'));

    $_str_pos = strpos($this->server('PHP_SELF'), '/' . $_script_name);

    if (basename($this->server('SCRIPT_NAME')) === $_script_name) {
      $_str_url = $this->server('SCRIPT_NAME');
    } else if (basename($this->server('PHP_SELF')) === $_script_name) {
      $_str_url = $this->server('PHP_SELF');
    } else if (Func::notEmpty($this->server('ORIG_SCRIPT_NAME')) && basename($this->server('ORIG_SCRIPT_NAME')) === $_script_name) {
      $_str_url = $this->server('ORIG_SCRIPT_NAME');
    } else if ($_str_pos !== false) {
      $_str_url = substr($this->server('SCRIPT_NAME'), 0, $_str_pos) . '/' . $_script_name;
    } else if (Func::notEmpty($this->server('DOCUMENT_ROOT')) && strpos($this->server('SCRIPT_FILENAME'), $this->server('DOCUMENT_ROOT')) === 0) {
      $_str_url = str_replace('\\', '/', str_ireplace($this->server('DOCUMENT_ROOT'), '', $this->server('SCRIPT_FILENAME')));
    }

    return strtolower($_str_url);
  }


  /** 入口文件的 URL
   * baseUrl function.
   *
   * @access public
   * @param bool $with_domain (default: false) 是否包含域名
   * @param bool $route_type (default: false) 路由类型
   * @return URL
   */
  public function baseUrl($with_domain = false, $route_type = '') {
    $_arr_configRoute = Config::get('route');

    $_str_baseFile  = $this->baseFile();
    $_str_url       = $this->url();

    $_str_baseRoute = $_str_baseFile;

    /*print_r($_arr_configRoute['route_type']);
    print_r('<br>');*/

    if ($_arr_configRoute['route_type'] == 'noBaseFile' || $route_type == 'noBaseFile' || strpos($_str_url, $_str_baseFile) === false) {
      $_str_baseRoute = $this->root();
    }

    if ($with_domain) {
      $_str_baseRoute = $this->domain() . $_str_baseRoute;
    }

    return Func::fixDs(strtolower($_str_baseRoute), '/');
  }


  /** 补全参数并作安全过滤
   * fillParam function.
   *
   * @access public
   * @param mixed $data 数据
   * @param mixed $param 参数 (如参数中不存在该元素, 则用空值填充)
   * @return 补全后的数据
   */
  public function fillParam($data, $param) {
    $_arr_return = array();
    $data        = (array)$data;

    if (is_array($param) && Func::notEmpty($param)) {
      foreach ($param as $_key=>$_value) { // 遍历参数
        $_type       = 'str'; // 未指定期待类型, 则认为是字符串
        $_default    = ''; // 未指定默认值, 则用空值
        $_htmldecode = false; // 未指定 html 解码, 则为否
        $_data       = ''; // 如果不存在则用空值填充
        $_key_name   = ''; // 如果不存在则用空值填充

        if (is_string($_key)) { // 如果指定了键名
          $_key_name = $_key;

          if (isset($data[$_key])) { // 如果存在则用值处理
            $_data = $data[$_key];
          }

          if (is_array($_value)) {
            if (isset($_value[0])) {
              $_type = $_value[0]; // 指定期待类型
            }

            if (isset($_value[1])) {
              $_default = $_value[1]; // 指定默认值
            }

            if (isset($_value[2])) {
              $_htmldecode = $_value[2]; // 指定 html 解码
            }
          } else if (is_scalar($_value)) {
            $_type = $_value; // 仅指定类型
          }
        } else { // 如果未指定键名
          $_value    = (string)$_value;
          $_key_name = $_value;

          if (isset($data[$_value])) { // 如果存在则用值处理
            $_data = $data[$_value];
          }
        }

        $_arr_return[$_key_name] = $this->input($_data, $_type, $_default, $_htmldecode); // 过滤
      }
    }

    return $_arr_return;
  }


  /** 取得分页参数(即将废弃, 兼容用)
   * pagination function.
   *
   * @access public
   * @param int $count (default: 0) 记录数
   * @param int $perpage (default: 0) 每页数
   * @param string $current (default: 'get') 方法
   * @param string $param (default: 'page') 参数名
   * @param int $pergroup (default: 0) 每个分组的页数
   * @return 分页参数
   */
  public function pagination($count, $perpage = 0, $current = 'get', $pageparam = 'page', $pergroup = 0) {
    $_obj_paginator = Paginator::instance();

    $_obj_paginator->count($count);
    $_obj_paginator->perpage($perpage);
    $_obj_paginator->pergroup($pergroup);
    $_obj_paginator->pageparam($pageparam);

    return $_obj_paginator->make($current);
  }


  /** 过滤输入数据
   * input function.
   *
   * @access public
   * @param string $input (default: '') 数据
   * @param string $type (default: 'str') 期待类型
   * @param string $default (default: '') 默认值
   * @param bool $htmldecode (default: false) html 解码
   * @return void
   */
  public function input($input = '', $type = 'str', $default = '', $htmldecode = false) {
    //print_r($input);
    //print_r(PHP_EOL);

    if (Func::isEmpty($input)) { // 数据为空, 则用默认值代替
      $input = $default;
    }

    switch ($type) {
      case 'int': // 整数型
        $input = trim($input);

        if (is_numeric($input)) {
          $_return = intval($input); // 如果是数值型则转换
        } else {
          $_return = 0; // 否则赋值为 0
        }
      break;

      case 'float':
      case 'num': // 数值型
        $input = trim($input);

        if (is_numeric($input)) {
          $_return = floatval($input); // 如果是数值型则转换
        } else {
          $_return = 0; // 否则赋值为 0
        }
      break;

      case 'arr': //数组
        if (!is_array($input)) {
          $input = array();
        }

        $_return = Arrays::map($input); // 遍历数组
      break;

      default: // 默认
        $_return = Func::safe($input, $htmldecode); // 安全过滤
      break;

    }

    return $_return;
  }


  /** 重复提交数据处理
   * duplicateProcess function.
   *
   * @access private
   * @param string $method (default: 'POST') 提交方法
   * @return 处理后的数据
   */
  private function duplicateProcess($method = 'POST') {
    $method = strtoupper($method);

    $_str_input = '';

    switch ($method) {
      case 'GET':
        $_str_input = md5(serialize($_GET));
      break;

      case 'REQUEST':
        $_str_input = md5(serialize($_REQUEST));
      break;

      default:
        $_str_input = md5(serialize($_POST));
      break;
    }

    return $_str_input;
  }


  /** 请求变量处理
   * varProcessR function.
   *
   * @access private
   * @param mixed $name (default: true) 变量名
   * @param string $type (default: 'str') 期待类型
   * @param string $default (default: '') 默认值
   * @param bool $htmldecode (default: false) html 解码
   * @param array $var (default: array()) 数据
   * @return void
   */
  private function varProcessR($name = true, $type = 'str', $default = '', $htmldecode = false, $var = array()) {
    $_return    = '';

    if ($name === false) { // 如果为 false, 返回全部原始参数
      $_return = $var;
    } else if ($name === true) { // 如果是 true, 返回全部参数, 并作安全过滤
      $_return = Arrays::map($var);
    } else if (is_array($name)) { // 如果为数组, 则补全
      $_return = $this->fillParam($var, $name);
    } else if (is_scalar($name)) { // 如果为标量, 则返回该元素
      if (isset($var[$name])) {
        $_return = $var[$name];
      }

      $_return = $this->input($_return, $type, $default, $htmldecode); // 安全过滤
    }

    return $_return;
  }


  /** 服务器变量处理
   * varProcessS function.
   *
   * @access private
   * @param mixed $name (default: true) 变量名
   * @param array $var (default: array()) 数据
   * @return void
   */
  private function varProcessS($name = true, $var = array()) {
    $name = strtoupper($name);

    $_mix_return = '';

    if (Func::isEmpty($name)) {
      $_mix_return = $var;
    } else {
      if (isset($var[$name])) {
        $_mix_return = $var[$name];
      }
    }

    return $_mix_return;
  }
}
