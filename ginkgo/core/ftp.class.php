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

// FTP操作类
class Ftp {

  public $config = array(); // 配置
  public $caInfo; // 一个保存着1个或多个用来让服务端验证的证书的文件名, $config['verify_peer'] 属性为 true 时有效
  public $result; // 返回结果
  public $error; // 错误
  public $errno; // 错误号
  public $statusCode; // http 状态码

  protected static $instance; // 当前实例

  private $allowScheme = array('ftp', 'ftps', 'sftp'); // 允许的协议

  private $configThis = array(
    'scheme'          => '', // 协议
    'host'            => '', // 主机
    'port'            => '',
    'user'            => '',
    'pass'            => '',
    'path'            => '',
    'pasv'            => false,
    'verify_peer'     => false, // 验证对等证书
    'verify_host'     => false, // 验证主机
    'return_transfer' => true, // 是否转换返回, true 返回, false 输出
    'timeout'         => 30, // 连接超时
  );

  private $res_curl; // cURL 资源
  private $userPass; // 账号密码
  private $hostUrl; // url
  private $hostPath; // path

  protected function __construct($config = array()) {
    $this->config($config);
    $this->init();
    $this->res_curl  = curl_init(); // curl 初始化
  }

  protected function __clone() { }

  /** 实例化
   * instance function.
   *
   * @access public
   * @static
   * @param array $config (default: array()) 配置
   * @return 当前类的实例
   */
  public static function instance($config = array()) {
    if (Func::isEmpty(self::$instance)) {
      self::$instance = new static($config);
    }
    return self::$instance;
  }


  /** 初始化, 兼容
   * init function.
   *
   * @access private
   * @return 初始化结果 (bool)
   */
  public function init() {
    Func::fixDs($this->config['path'], '/');
    str_replace('\\', '/', $this->config['path']);
    str_replace('//', '/', $this->config['path']);

    return true;
  }


  public function config($config = array()) {
    $_arr_config   = Config::get('ftp', 'var_extra');

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


  /** 上传文件
   * fileUpload function.
   *
   * @access public
   * @param string $path_local 本地路径
   * @param string $path_remote 远程路径
   * @param bool $is_abs (default: false) 是否为绝对路径
   * @param bool $mod (default: FTP_ASCII) 上传模式
   * @return 上传结果 (bool)
   */
  public function fileUpload($path_local, $path_remote, $ascii = true) {
    if (!File::fileHas($path_local)) { // 检查本地文件是否存在
      $this->errRecord('Ftp::fileUpload(), Local file not found: ' . $path_local);
      return false;
    }

    if (!$this->urlProcess()) { // url 处理
      return false;
    }

    $_res_file = fopen($path_local, 'r');

    $_arr_opt = array(
      CURLOPT_URL                     => $this->hostUrl . $this->hostPath . $path_remote, // 请求 url
      CURLOPT_INFILE                  => $_res_file,
      CURLOPT_INFILESIZE              => filesize($path_local),
      CURLOPT_UPLOAD                  => true, // 准备上传
      CURLOPT_FTP_CREATE_MISSING_DIRS => true, // 创建目录
      CURLOPT_TRANSFERTEXT            => $ascii,
    );

    $this->optProcess($_arr_opt);

    $_result      = curl_exec($this->res_curl); // 执行请求
    $_num_size    = curl_getinfo($this->res_curl, CURLINFO_SIZE_UPLOAD); // 取得上传字节
    $this->error  = curl_error($this->res_curl); // 取得错误信息
    $this->errno  = curl_errno($this->res_curl); // 取得错误号

    if ($this->errno < 1) {
      $_result = true;
    }

    return $_result;
  }


  /** 删除文件
   * fileDelete function.
   *
   * @access public
   * @param string $path_remote 远程路径
   * @param bool $is_abs (default: false) 是否为绝对路径
   * @return void
   */
  public function fileDelete($path_remote, $is_abs = false) {
    if (!$this->urlProcess()) { // url 处理
      return false;
    }

    $_arr_opt = array(
      CURLOPT_URL       => $this->hostUrl, // 请求 url
      CURLOPT_POSTQUOTE => array('DELE ' . $this->hostPath . $path_remote),
    );

    $this->optProcess($_arr_opt);

    $_result          = curl_exec($this->res_curl); // 执行请求
    $this->result     = $_result;
    $this->statusCode = curl_getinfo($this->res_curl, CURLINFO_HTTP_CODE); // 取得状态码
    $this->error      = curl_error($this->res_curl); // 取得错误信息
    $this->errno      = curl_errno($this->res_curl); // 取得错误号

    if ($this->errno < 1) {
      $_result = true;
    }

    return $_result;
  }


  // 设置被动模式 since 0.2.0
  public function pasv($pasv = true) {
    if ($pasv === true || $pasv === 'true') {
      $pasv = true;
    } else {
      $pasv = false;
    }

    $this->config['pasv'] = $pasv;
  }


  // 设置端口 since 0.2.0
  public function port($port = '') {
    $this->config['port'] = $port;
  }


  /** 获取错误
   * getError function.
   *
   * @access public
   * @return 错误信息
   */
  public function getError() {
    return $this->error;
  }


  /** 取得错误号
   * getErrno function.
   *
   * @access public
   * @return 错误号
   */
  public function getErrno() {
    return $this->errno;
  }

  // 设置 since 0.2.0
  private function optProcess($opts) {
    $_arr_optDo = array(
      CURLOPT_CONNECTTIMEOUT          => $this->config['timeout'], // 超时
      CURLOPT_RETURNTRANSFER          => $this->config['return_transfer'], // 是否转换返回, true 返回原生的（Raw）输出
      CURLOPT_SSL_VERIFYPEER          => $this->config['verify_peer'], // 验证对等证书
      CURLOPT_SSL_VERIFYHOST          => $this->config['verify_host'], // 验证主机
      CURLOPT_FTP_USE_EPSV            => $this->config['pasv'], // 被动模式
      CURLOPT_PORT                    => $this->config['port'], // 设置端口
    );

    $_arr_opt = array_replace_recursive($opts, $_arr_optDo);

    if (Func::notEmpty($this->userPass)) {
      $_arr_opt[CURLOPT_USERPWD]= $this->userPass; // 账号密码
    }

    if (Func::notEmpty($this->caInfo)) {
      $_arr_opt[CURLOPT_CAINFO] = $this->caInfo; // 设置证书名
    }

    //print_r($_arr_opt);

    curl_setopt_array($this->res_curl, $_arr_opt);
  }

  // url 处理 since 0.2.0
  private function urlProcess() {
    if (!stristr($this->config['host'], '://')) {
      $this->config['host'] = 'ftp://' . $this->config['host'];
    }

    $_arr_urlParsed = parse_url($this->config['host']); // 解析 url

    if (!isset($_arr_urlParsed['host']) || Func::isEmpty($_arr_urlParsed['host'])) {
      $this->errRecord('Auth::urlProcess(), Missing HOST: ' . $_arr_urlParsed['host']);
      return false;
    }

    if (isset($_arr_urlParsed['scheme']) && Func::notEmpty($_arr_urlParsed['scheme'])) {
      $_str_scheme = $_arr_urlParsed['scheme'];
    } else if (Func::notEmpty($this->config['scheme'])) {
      $_str_scheme = $this->config['scheme'];
    } else {
      $_str_scheme = 'ftp';
    }

    if (isset($_arr_urlParsed['port']) && Func::notEmpty($_arr_urlParsed['port'])) {
      $_str_port = $_arr_urlParsed['port'];
    } else if (Func::notEmpty($this->config['port'])) {
      $_str_port = $this->config['port'];
    } else {
      $_str_port = '';
    }

    if (isset($_arr_urlParsed['path']) && Func::notEmpty($_arr_urlParsed['path'])) {
      $_str_path = $_arr_urlParsed['path'];
    } else if (Func::notEmpty($this->config['path'])) {
      $_str_path = $this->config['path'];
    } else {
      $_str_path = '';
    }

    if (isset($_arr_urlParsed['user']) && Func::notEmpty($_arr_urlParsed['user'])) {
      $_str_user = $_arr_urlParsed['user'];
    } else if (Func::notEmpty($this->config['user'])) {
      $_str_user = $this->config['user'];
    } else {
      $_str_user = '';
    }

    if (isset($_arr_urlParsed['pass']) && Func::notEmpty($_arr_urlParsed['pass'])) {
      $_str_pass = $_arr_urlParsed['pass'];
    } else if (Func::notEmpty($this->config['pass'])) {
      $_str_pass = $this->config['pass'];
    } else {
      $_str_pass = '';
    }

    $this->hostUrl  = $_str_scheme . '://' . $_arr_urlParsed['host'];

    if (Func::notEmpty($_str_port)) {
      $this->hostUrl .= ':' . $_str_port;
    }

    $this->hostPath = $_str_path;

    if (Func::notEmpty($_str_user) && Func::notEmpty($_str_pass)) {
      $this->userPass = $_str_user . ':' . $_str_pass;
    }

    return true;
  }

  // 销毁 since 0.2.0
  public function __destruct() {
    if ($this->res_curl != null) {
      curl_close($this->res_curl);
      $this->res_curl = null;
    }
  }


  private function errRecord($msg) {  // since 0.2.4
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
      Log::record('type: ginkgo\Ftp, msg: ' . $msg, 'log');
    }
  }
}
