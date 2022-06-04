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

// 上传类
class Upload {

  public $config    = array(); // 配置
  public $limitSize = 0; // 允许上传大小
  public $error; // 错误
  public $rule = 'md5'; // 生成文件名规则 (函数名)
  public $mimeRows = array(); // mime 池

  public $fileInfo = array(
    'name'     => '',
    'tmp_name' => '',
    'ext'      => '',
    'mime'     => '',
    'size'     => 0,
  );

  protected static $instance; // 当前实例

  private $configThis     = array(
    'limit_size'    => 200, //上传尺寸
    'limit_unit'    => 'kb', //尺寸单位
  ); // 默认配置

  private $obj_file; // 文件对象


  /** 构造函数
   * __construct function.
   *
   * @access protected
   * @param string $config (default: '') 配置
   * @return void
   */
  protected function __construct($config = array()) {
    $this->config($config); // 初始化
    $this->obj_file = File::instance();
  }


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
    $_arr_config   = Config::get('upload', 'var_extra');

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

    $_arr_configDo['limit_unit'] = strtolower($_arr_configDo['limit_unit']);

    switch ($_arr_configDo['limit_unit']) { // 初始化单位
      case 'tb':
        $_num_sizeUnit = GK_TB;
      break;

      case 'gb':
        $_num_sizeUnit = GK_GB;
      break;

      case 'mb':
        $_num_sizeUnit = GK_MB;
      break;

      case 'kb':
        $_num_sizeUnit = GK_KB;
      break;

      default:
        $_num_sizeUnit = 1;
      break;
    }

    if ($this->limitSize < 1) { // 如果大小限制未定义
      $this->limitSize = $_arr_configDo['limit_size'] * $_num_sizeUnit;
    }

    $this->config  = $_arr_configDo;
  }


  /** 创建上传对象
   * create function.
   *
   * @access public
   * @param mixed $name
   * @return void
   */
  public function create($name) {
    if (!isset($_FILES) || !isset($_FILES[$name])) {
      $this->errRecord('Upload::create(), No files uploaded'); // 没有上传数据

      return false;
    }

    $_arr_fileInfo = $_FILES[$name];

    // 上传文件校验
    if (!isset($_arr_fileInfo['tmp_name']) || !is_uploaded_file($_arr_fileInfo['tmp_name'])) {
      $this->errRecord('Upload::create(), No files uploaded');

      return false;
    }

    // 错误处理
    if (isset($_arr_fileInfo['error']) && $_arr_fileInfo['error'] > 0) {
      $this->errorProcess($_arr_fileInfo['error']);

      return false;
    }

    // 取得 mime
    $_str_mime  = $this->getMime($_arr_fileInfo['tmp_name'], $_arr_fileInfo['type']);

    // 取得扩展名
    $_str_ext   = $this->getExt($_arr_fileInfo['name'], $_str_mime);

    // 验证是否为允许的文件
    if (!$this->verifyFile($_str_ext, $_str_mime)) {
      return false;
    }

    if ($_arr_fileInfo['size'] > $this->limitSize) { //是否超过尺寸
      $this->errRecord('Upload::create(), Upload file size exceeds the settings');

      return false;
    }

    $_arr_fileInfo['name']  = Func::safe($_arr_fileInfo['name']);
    $_arr_fileInfo['ext']   = $_str_ext;
    $_arr_fileInfo['mime']  = $_str_mime;

    $_arr_fileInfoDo = array_replace_recursive($this->fileInfo, $_arr_fileInfo);
    $this->fileInfo  = $_arr_fileInfoDo;

    return $_arr_fileInfoDo;
  }


  /** 设置、获取大小限制
   * setLimit function.
   *
   * @access public
   * @param mixed $size
   * @return void
   */
  public function limit($size = false) {
    if ($size === false) {
      return $this->limitSize;
    } else {
      $this->limitSize = (float)$size;
    }
  }


  /** 移动文件
   * move function.
   *
   * @access public
   * @param string $dir 目的地路径
   * @param mixed $name (default: true) 文件名, 参数为 true 时, 按规则生成文件名, false 时, 使用原始文件名, 字符串直接使用
   * @param bool $replace (default: true) 是否替换
   * @return void
   */
  public function move($dir, $name = true, $replace = true) {
    if (!$this->obj_file->dirMk($dir)) { // 建目录
      $this->errRecord('Upload::move(), Failed to create directory: ' . $dir);

      return false;
    }

    $name = $this->genFilename($name); // 生成文件名

    if (Func::isEmpty($name)) {
      $this->errRecord('Upload::move(), Missing filename');

      return false;
    }

    $_str_path = Func::fixDs($dir) . $name; // 补全路径

    if (!$replace && File::fileHas($_str_path)) { // 如果为不替换, 冲突时报错
      $this->errRecord('Upload::move(), Has the same filename: ' . $_str_path);

      return false;
    }

    if (!move_uploaded_file($this->fileInfo['tmp_name'], $_str_path)) { // 移动至指定目录
      $this->errRecord('Upload::move(), Failed to move uploaded file'); // 移动失败

      return false;
    }

    if (File::fileHas($this->fileInfo['tmp_name'])) { // 如果临时文件仍然存在
      $this->obj_file->fileDelete($this->fileInfo['tmp_name']); // 删除临时文件
    }

    return $_str_path;
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


  // 兼容用
  public function __call($method, $params) {
    return $this->getInfo($method);
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
    if ($strict === true || $strict === 'true') {
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


  // 获取错误
  public function getError() {
    return $this->error;
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


  /** 错误处理
   * errorProcess function.
   *
   * @access private
   * @param mixed $error_no 错误号
   * @return void
   */
  private function errorProcess($error_no) {
    switch ($error_no) {
      case UPLOAD_ERR_INI_SIZE:
        $this->errRecord('Upload::errorProcess(), Upload file size exceeds the php.ini settings');
      break;

      case UPLOAD_ERR_FORM_SIZE:
        $this->errRecord('Upload::errorProcess(), Upload file size exceeds the form settings');
      break;

      case UPLOAD_ERR_PARTIAL:
        $this->errRecord('Upload::errorProcess(), Only the portion of file is uploaded');
      break;

      case UPLOAD_ERR_NO_FILE:
        $this->errRecord('Upload::errorProcess(), No files uploaded');
      break;

      case UPLOAD_ERR_NO_TMP_DIR:
        $this->errRecord('Upload::errorProcess(), Upload temp dir not found');
      break;

      case UPLOAD_ERR_CANT_WRITE:
        $this->errRecord('Upload::errorProcess(), File write error');
      break;

      default:
        $this->errRecord('Upload::errorProcess(), Unknown upload error');
      break;
    }
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
        $this->errRecord('Upload::verifyFile(), MIME check failed: ' . $ext);

        return false;
      }

      if (!in_array($mime, $this->mimeRows[$ext])) { //是否允许
        $this->errRecord('Upload::verifyFile(), MIME not allowed: ' . $mime);

        return false;
      }
    }

    return true;
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
      Log::record('type: ginkgo\Upload, msg: ' . $msg, 'log');
    }
  }
}
