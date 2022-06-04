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

// 发起 http 请求, 基于 curl
class Http {

  public $config = array(); // 配置
  public $error; // 错误
  public $errno; // 错误号
  public $caInfo; // 一个保存着1个或多个用来让服务端验证的证书的文件名, $verifyPeer 属性为 true 时有效
  public $result; // 返回结果
  public $rule = 'md5'; // 生成文件名规则 (函数名)
  public $statusCode; // http 状态码
  public $mimeRows = array(); // mime 池

  public $fileInfo = array(
    'name'     => '',
    'tmp_name' => '',
    'ext'      => '',
    'mime'     => '',
    'size'     => 0,
  );

  // 默认 http 头, 模仿表单提交
  public $httpHeader = array(
    'Content-Type'  => 'application/x-www-form-urlencoded; Charset=UTF-8',
    'Accept'        => 'application/json',
  );

  protected static $instance; // 当前实例

  private $configThis = array(
    'scheme'          => '', // 协议
    'charset'         => 'UTF-8', // http 头的字符编码
    'port'            => '',
    'accept'          => 'application/json', // 请求返回的类型
    'curlopt_header'  => false, // 是否将头文件的信息作为数据流输出
    'verify_peer'     => false, // 验证对等证书
    'verify_host'     => false, // 验证主机
    'return_transfer' => true, // 是否转换返回, true 返回, false 输出
    'timeout'         => 30, // 连接超时
  );

  private $res_curl; // cURL 资源
  private $obj_file; // 文件对象
  private $hostUrl; // url

  /** 构造函数
   * __construct function.
   *
   * @access protected
   * @param string $port (default: '') 端口
   * @return void
   */
  protected function __construct($config = array()) {
    $this->config($config);
    $this->res_curl  = curl_init(); // curl 初始化
  }


  /** 初始化实例
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
    $_arr_configDo = $this->configThis;

    if (is_array($this->config) && Func::notEmpty($this->config)) {
      $_arr_configDo = array_replace_recursive($_arr_configDo, $this->config); // 合并配置
    }

    if (is_array($config) && Func::notEmpty($config)) {
      $_arr_configDo = array_replace_recursive($_arr_configDo, $config); // 合并配置
    }

    $this->config  = $_arr_configDo;
  }


  /** 发起 http 请求
   * request function.
   *
   * @access public
   * @param string $url 地址
   * @param mixed $data (default: false) 发送的数据
   * @param string $method (default: 'get') 发起方法
   * @return url 返回结果
   */
  public function request($url, $data = array(), $method = 'get') {
    $method = strtolower($method);

    if (Func::isEmpty($url)) { // url 错误
      $this->errRecord('Http::request(), Missing URL');
      return false;
    }

    $_str_data = $this->dataProcess($data); // 发送数据处理

    if (!$this->urlProcess($url)) { // url 处理
      return false;
    }

    $_str_url = $this->hostUrl;

    switch ($method) {
      case 'post': // post 方法
        $_arr_opt = array(
          CURLOPT_POST        => true, // 设置 post 方法为 true
          CURLOPT_POSTFIELDS  => $_str_data, // 设置发送的数据
        );
      break;

      default:
        if (strpos($_str_url, '?')) {
          $_str_conn = '&';
        } else {
          $_str_conn = '?';
        }

        $_str_url .= $_str_conn . $_str_data; // 将附带数据, 连接符拼合为完整 url

        $_arr_opt = array(
          CURLOPT_HTTPGET => true, // 设置 get 方法为 true
        );
      break;
    }

    $_arr_opt[CURLOPT_URL] = $_str_url; // 请求 url

    $this->optProcess($_arr_opt);

    $_result          = curl_exec($this->res_curl); // 执行请求
    $this->result     = $_result;
    $_result          = $this->resultProcess($_result); // 返回结果处理
    $this->statusCode = curl_getinfo($this->res_curl, CURLINFO_HTTP_CODE); // 取得 http 状态码
    $this->error      = curl_error($this->res_curl); // 取得错误信息
    $this->errno      = curl_errno($this->res_curl); // 取得错误号

    return $_result;
  }


  /** 设置 http 头 (替换)
   * setHeader function.
   *
   * @access public
   * @param string $name 名称
   * @param string $value (default: '') 值
   * @return void
   */
  public function setHeader($header, $value = '') {
    if (is_array($header)) {
      $this->httpHeader = array_replace_recursive($this->httpHeader, $header);
    } else {
      $this->httpHeader[$header] = $value;
    }
  }


  /** 设置请求返回的类型
   * setAccept function.
   *
   * @access public
   * @param string $type (default: 'application/json') 类型
   * @return void
   */
  public function setAccept($type) {
    $this->setHeader('Accept', $type);

    $this->config['accept'] = $type;
  }

  /** 设置请求内容的类型 (默认为表单形式)
   * contentType function.
   *
   * @access public
   * @param string $contentType  请求内容的类型
   * @param string $charset (default: '') 字符编码
   * @return void
   */
  public function contentType($contentType, $charset = '') {
    if (Func::notEmpty($charset)) {
      $this->config['charset'] = $charset;
    }

    $this->httpHeader['Content-Type'] = $contentType . '; Charset=' . $this->config['charset'];
  }


  /** 设置生成文件名规则 (函数名)
   * rule function.
   *
   * @access public
   * @param mixed $rule
   * @return 当前实例
   */
  public function rule($rule) {
    $this->rule = $rule;

    return $this;
  }


  // 兼容用
  public function __call($method, $params) {
    return $this->getInfo($params);
  }


  /** 获取信息
   * size function.
   *
   * @access public
   * @return 0 - 图像宽度, 1 - 图像高度
   */
  public function getInfo($name = '') {
    $_mix_retrun = '';

    if (Func::isEmpty($name)) {
      $_mix_retrun = $this->fileInfo;
    } else if (isset($this->fileInfo[$name])) {
      $_mix_retrun = $this->fileInfo[$name];
    }

    return $_mix_retrun;
  }


  /** 获取文件的 mime 类型
   * getMime function.
   *
   * @access public
   * @param string $path 路径
   * @param bool $strict (default: false) 严格获取 mime, true 严格, 字符串 直接报告, false 以路径为准
   * @return mime 类型
   */
  public function getMime($path, $strict = false) {
    $_str_mime = '';

    if ($strict === true) {
      $_obj_finfo = new \finfo();

      $_str_mime  = $_obj_finfo->file($path, FILEINFO_MIME_TYPE);
    } else if (Func::notEmpty($strict) && is_string($strict)) {
      $_str_mime = $strict;
    } else {
      $_str_ext = $this->getExt($path); //取得扩展名

      if (isset($this->mimeRows[$_str_ext])) {
        $_str_mime = $this->mimeRows[$_str_ext][0];
      }
    }

    return $_str_mime;
  }


  /** 获取扩展名
   * getExt function.
   *
   * @access public
   * @param string $path 路径
   * @param mixed $mime (default: false) mime 类型
   * @return 扩展名
   */
  public function getExt($path, $mime = false) {
    $_str_ext = strtolower(pathinfo($path, PATHINFO_EXTENSION)); //取得扩展名

    if ($mime) {
      // 扩展名与 mime 不符的情况下, 反向查找, 如果存在, 则更改扩展名
      if (!isset($this->mimeRows[$_str_ext]) || !in_array($mime, $this->mimeRows[$_str_ext])) {
        foreach ($this->mimeRows as $_key_allow=>$_value_allow) {
          if (in_array($mime, $_value_allow)) {
            return $_key_allow;
          }
        }
      }
    }

    return $_str_ext;
  }


  /** 取得 http 状态码
   * getStatusCode function.
   *
   * @access public
   * @return void
   */
  public function getStatusCode() {
    return $this->statusCode;
  }

  /** 取得返回结果
   * getResult function.
   *
   * @access public
   * @return void
   */
  public function getResult() {
    return $this->result;
  }


  // 获取错误
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


  /** 抓取远程内容并保存在临时文件中
   * getRemote function.
   *
   * @access public
   * @param string $url 远程地址
   * @param bool $data (default: false) 附带数据
   * @param string $method (default: 'get') 请求方法
   * @return 临时文件信息
   */
  public function getRemote($url, $data = array(), $method = 'get') {
    $this->obj_file = File::instance();

    $_result    = $this->request($url, $data, $method); // 用 request 方法取得结果

    /*print_r($url . ' -> ' . $this->statusCode);
    print_r(PHP_EOL);*/

    if ($this->statusCode != '200' || $this->errno > 0) { // 返回失败
      return false;
    }

    $_tmp_path  = GK_PATH_TEMP . md5($url); // 生成临时文件名
    $_num_size  = 0;

    $_num_size  = $this->obj_file->fileWrite($_tmp_path, $this->result); // 写入临时文件

    if (!$_num_size) { // 写入失败
      return false;
    }

    $_str_mime  = $this->getMime($_tmp_path, true); // 取得 mime 类型

    //print_r($_str_mime);

    $_str_ext   = $this->getExt($url, $_str_mime); // 取得扩展名

    if (!$this->verifyFile($_str_ext, $_str_mime)) { // 验证是否为允许的类型
      $this->obj_file->fileDelete($_tmp_path);
      return false;
    }

    $_str_name  = basename($url);

    $_arr_fileInfo = array(
      'name'      => Func::safe($_str_name),
      'tmp_name'  => $_tmp_path,
      'ext'       => $_str_ext,
      'mime'      => $_str_mime,
      'size'      => $_num_size,
    );

    $_arr_fileInfoDo = array_replace_recursive($this->fileInfo, $_arr_fileInfo);

    $this->fileInfo  = $_arr_fileInfoDo;

    return $_arr_fileInfoDo;
  }

  /** 移动远程抓取到的文件到指定文件夹
   * move function.
   *
   * @access public
   * @param string $dir 指定文件夹
   * @param mixed $name (default: true) 指定文件名, true 为自动生成, false 为原始文件名, 字符串为指定文件名
   * @param mixed $replace (default: true) 是否替换
   * @return void
   */
  public function move($dir, $name = true, $replace = true) {
    $name = $this->genFilename($name);

    if (Func::isEmpty($name)) {
      $this->errRecord('Http::move(), Missing filename');

      return false;
    }

    $_str_path = Func::fixDs($dir) . $name; // 补全路径

    if (!$replace && File::fileHas($_str_path)) { // 文件名冲突
      $this->errRecord('Http::move(), Has the same filename: ' . $_str_path);

      return false;
    }

    return $this->obj_file->fileMove($this->fileInfo['tmp_name'], $_str_path); // 移动文件
  }

  // 设置 端口
  public function port($port = '') {
    $this->config['port'] = $port;
  }


  /** 设置 mime
   * setMime function.
   *
   * @access public
   * @param mixed $mime
   * @param array $value (default: array())
   * @return void
   */
  public function setMime($mime, $value = array()) {
    if (is_array($mime)) {
      $this->mimeRows = array_replace_recursive($this->mimeRows, $mime);
    } else {
      $this->mimeRows[$mime] = $value;
    }
  }


  /** 生成文件名
   * genFilename function.
   *
   * @access protected
   * @param mixed $name (default: true) 文件名
   * @return 文件名
   */
  private function genFilename($name = true) {
    if ($name === true) { // 参数为 true 时, 按规则生成文件名
      if (is_callable($this->rule)) {
        $_str_type = $this->rule;
      } else {
        $_str_type = 'md5';
      }

      if (isset($_SERVER['REQUEST_TIME_FLOAT'])) {
        $_tm_time = $_SERVER['REQUEST_TIME_FLOAT'];
      } else {
        $_tm_time = GK_NOW;
      }

      $name = call_user_func($_str_type, $_tm_time) . '.' . $this->fileInfo['ext'];
    } else if ($name === false) { // 参数为 false 时, 使用原始文件名
      $name = $this->fileInfo['name'];
    }

    // 指定为字符串时, 直接使用

    return $name;
  }



  /** 验证是否为允许的文件
   * verifyFile function.
   *
   * @access protected
   * @param mixed $ext
   * @param mixed $mime
   * @return 验证结果 (bool)
   */
  private function verifyFile($ext, $mime) {
    if (Func::notEmpty($this->mimeRows)) {
      if (!isset($this->mimeRows[$ext])) { //该扩展名的 mime 数组是否存在
        $this->errRecord('Http::verifyFile(), MIME check failed: ' . $ext);

        return false;
      }

      if (!in_array($mime, $this->mimeRows[$ext])) { //是否允许
        $this->errRecord('Http::verifyFile(), MIME not allowed: ' . $mime);

        return false;
      }
    }

    return true;
  }


  private function optProcess($opts) {
    $_arr_optDo = array(
      CURLOPT_CONNECTTIMEOUT  => $this->config['timeout'], // 超时
      CURLOPT_RETURNTRANSFER  => $this->config['return_transfer'], // 是否转换返回, true 返回原生的（Raw）输出
      CURLOPT_SSL_VERIFYPEER  => $this->config['verify_peer'], // 验证对等证书
      CURLOPT_SSL_VERIFYHOST  => $this->config['verify_host'], // 验证主机
      CURLOPT_HEADER          => $this->config['curlopt_header'],  // 将头文件的信息作为数据流输出
    );

    $_arr_opt = array_replace_recursive($opts, $_arr_optDo);

    $_arr_httpHeaderDo = $this->httpHeaderProcess(); // http 头处理

    if (Func::notEmpty($_arr_httpHeaderDo)) {
      $_arr_opt[CURLOPT_HTTPHEADER] = $_arr_httpHeaderDo; // 发送 http 头
    }

    if (Func::notEmpty($this->caInfo)) {
      $_arr_opt[CURLOPT_CAINFO] = $this->caInfo; // 设置证书名
    }

    if (Func::notEmpty($this->config['port'])) {
      $_arr_opt[CURLOPT_PORT]= $this->config['port']; // 设置端口
    }

    curl_setopt_array($this->res_curl, $_arr_opt);
  }

  /** 返回结果处理
   * resultProcess function.
   *
   * @access private
   * @param mixed $result 返回结果
   * @return 处理后的结果
   */
  private function resultProcess($result) {
      switch ($this->config['accept']) {
        case 'application/json':
          $result = Arrays::fromJson($result);
        break;
      }

      return $result;
  }


  // url 处理 since 0.2.0
  private function urlProcess($url) {
    $_arr_urlParsed = parse_url($url); // 解析 url

    if (!isset($_arr_urlParsed['host']) || Func::isEmpty($_arr_urlParsed['host'])) {
      $this->errRecord('Http::urlProcess(), Missing HOST');
      return false;
    }

    if (isset($_arr_urlParsed['scheme']) && Func::notEmpty($_arr_urlParsed['scheme'])) {
      $_str_scheme = $_arr_urlParsed['scheme'];
    } else if (Func::notEmpty($this->config['scheme'])) {
      $_str_scheme = $this->config['scheme'];
    } else {
      $_str_scheme = 'http';
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
    } else if (isset($this->config['path']) && Func::notEmpty($this->config['path'])) {
      $_str_path = $this->config['path'];
    } else {
      $_str_path = '';
    }

    if (isset($_arr_urlParsed['query']) && Func::notEmpty($_arr_urlParsed['query'])) {
      $_str_query = $_arr_urlParsed['query'];
    } else {
      $_str_query = '';
    }

    if (isset($_arr_urlParsed['fragment']) && Func::notEmpty($_arr_urlParsed['fragment'])) {
      $_str_fragment = $_arr_urlParsed['fragment'];
    } else {
      $_str_fragment = '';
    }

    $this->hostUrl  = $_str_scheme . '://' . $_arr_urlParsed['host'];

    if (Func::notEmpty($_str_port)) {
      $this->hostUrl .= ':' . $_str_port;
    }

    if (Func::notEmpty($_str_path)) {
      $this->hostUrl .= $_str_path;
    }

    if (Func::notEmpty($_str_query)) {
      $this->hostUrl .= '?' . $_str_query;
    }

    if (Func::notEmpty($_str_fragment)) {
      $this->hostUrl .= '#' . $_str_fragment;
    }

    return true;
  }


  /** 发送数据处理
   * dataProcess function.
   *
   * @access private
   * @param array $data 附带数据
   * @return 拼合后的数据字符串
   */
  private function dataProcess($data) {
    if (Func::notEmpty($data) && is_array($data)) { // 拼接数据
      $_str_data  = http_build_query($data);
    } else {
      $_str_data  = '';
    }

    return $_str_data;
  }

  /** http 头处理
   * httpHeaderProcess function.
   *
   * @access private
   * @return http 头数组
   */
  private function httpHeaderProcess() {
    $_arr_httpHeaderDo = array();

    if (Func::notEmpty($this->httpHeader)) {
      // 发送头部信息
      foreach ($this->httpHeader as $_key=>$_value) {
        if (Func::isEmpty($_value)) {
          $_arr_httpHeaderDo[] = $_key;
        } else {
          $_arr_httpHeaderDo[] = $_key . ': ' . $_value;
        }
      }

      //print_r($_arr_httpHeaderDo);
    }

    return $_arr_httpHeaderDo;
  }

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
      Log::record('type: ginkgo\Http, msg: ' . $msg, 'log');
    }
  }
}
