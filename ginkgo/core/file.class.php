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

// 文件操作类
class File {

  public $error; // 错误
  public $mod  = 0755; // 目录权限

  protected static $instance; // 当前实例

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


  /** 文件夹是否存在
   * dirHas function.
   *
   * @access public
   * @static
   * @param string $path 路径
   * @return bool
   */
  public static function dirHas($path) {
    return is_dir(strtolower($path));
  }


  /** 文件是否存在
   * fileHas function.
   *
   * @access public
   * @static
   * @param string $path 路径
   * @return bool
   */
  public static function fileHas($path) {
    return is_file(strtolower($path));
  }


  /** 列出目录结构
   * dirList function.
   *
   * @access public
   * @param string $path 路径
   * @param string $ext (default: '') 指定扩展名
   * @return 目录结构
   */
  public function dirList($path, $ext = '') {
    $path         = strtolower($path);
    $_arr_return  = array();
    $_arr_dir     = array();

    if (self::dirHas($path)) { // 判断是否问文件夹
      $_arr_dir = scandir($path);
    } else {
      $this->errRecord('File::dirList(), Not a directory: ' . $path); // 定义错误消息
      return array();
    }

    if (Func::notEmpty($_arr_dir)) {
      foreach ($_arr_dir as $_key=>$_value) {
        if ($_value != '.' && $_value != '..') {
          $_str_pathFull  = Func::fixDs($path) . $_value; // 补全路径

          $_str_type = filetype($_str_pathFull); // 判断路径类型

          $_arr_returnTmp = array(
            'name' => $_value,
            'path' => $_str_pathFull,
            'type' => $_str_type,
          );

          if ($_str_type == 'dir') { // 如果是文件夹, 则递归列出
            $_arr_returnTmp['sub'] = $this->dirList($_str_pathFull, $ext);

            $_arr_return[] = $_arr_returnTmp; // 拼接数组
          } else {
            if (Func::isEmpty($ext)) { // 如果没有指定扩展名, 则全部列出
              $_arr_return[] = $_arr_returnTmp;
            } else {
              $_str_ext = pathinfo($_value, PATHINFO_EXTENSION); // 比较扩展名, 符合的拼合数组

              if ($_str_ext == $ext) {
                $_arr_return[] = $_arr_returnTmp;
              }
            }
          }
        }
      }
    }

    return $_arr_return;
  }

  /** 创建目录
   * dirMk function.
   *
   * @access public
   * @param string $path 路径
   * @return 创建结果 (bool)
   */
  public function dirMk($path, $mod = false) {
    $path      = strtolower($path);
    $old_umask = umask(0);

    if (self::dirHas($path)) { // 已存在
      $_bool_status = true;
    } else {
      if ($mod === false) {
        $mod = $this->mod;
      }

      if (mkdir($path, $mod, true)) { // 创建成功
        $_bool_status = true;
      } else {
        $this->error  = 'Failed to create directory'; // 定义错误消息
        $_bool_status = false; // 失败
      }
    }

    //print_r($old_umask);
    umask($old_umask);

    return $_bool_status;
  }


  /** 拷贝整个目录
   * dirCopy function.
   *
   * @access public
   * @param string $src 源路径
   * @param string $dst 目标路径
   * @return 拷贝结果 (bool)
   */
  public function dirCopy($src, $dst) {
    $src = strtolower($src);
    $dst = strtolower($dst);

    if (!$this->dirMk($dst)) {
      return false;
    }

    $_arr_dir = $this->dirList($src); //逐级列出

    foreach ($_arr_dir as $_key=>$_value) {
      if (substr($dst, -1) == DS) {
        $_str_pathFull  = $dst . $_value['name'];
      } else {
        $_str_pathFull  = $dst . DS . $_value['name'];
      }

      if ($_value['type'] == 'file' && self::fileHas($_value['path'])) { // 假如为文件且路径存在, 则直接拷贝
        copy($_value['path'], $_str_pathFull);
      } else if (self::dirHas($_value['path'])) { // 假如为目录且路径存在, 则递归拷贝
        $this->dirCopy($_value['path'], $_str_pathFull);
      }
    }

    return true;
  }

  /** 递归删除整个目录
   * dirDelete function.
   *
   * @access public
   * @param string $path 路径
   * @return 删除结果 (bool)
   */
  public function dirDelete($path) {
    $path = strtolower($path);

    if (!self::dirHas($path)) { // 路径不存在则返回 false
      $this->errRecord('File::dirDelete(), Directory not found: ' . $path); // 定义错误消息
      return false;
    }

    // 删除目录及目录里所有的目录和文件
    $_arr_dir = $this->dirList($path); // 逐级列出

    foreach ($_arr_dir as $_key=>$_value) {
      if (substr($path, -1) == DS) {
        $_str_pathFull  = $path . $_value['name'];
      } else {
        $_str_pathFull  = $path . DS . $_value['name'];
      }

      if ($_value['type'] == 'file' && self::fileHas($_str_pathFull)) { // 假如为文件且路径存在, 则直接删除文件
        $this->fileDelete($_str_pathFull);
      } else if (self::dirHas($_str_pathFull)) { // 假如为目录且路径存在, 则递归删除
        $this->dirDelete($_str_pathFull);
      }
    }

    return rmdir($path); // 最后删除目录
  }


  /** 读取文件
   * fileRead function.
   *
   * @access public
   * @param string $path 路径
   * @return 文件内容
   */
  public function fileRead($path) {
    $path = strtolower($path);

    if (!self::fileHas($path)) {
      $this->errRecord('File::fileRead(), File not found: '. $path); // 定义错误消息
      return false;
    }

    return file_get_contents($path);
  }


  /** 移动文件 (更名)
   * fileMove function.
   *
   * @access public
   * @param string $src 源路径
   * @param string $dst 目标路径
   * @return 移动结果 (bool)
   */
  public function fileMove($src, $dst) {
    $src = strtolower($src);
    $dst = strtolower($dst);

    if (!self::fileHas($src)) {
      $this->errRecord('File::fileMove(), Source file not found: ' . $src);
      return false;
    }

    if (!$this->dirMk(dirname($dst))) {
      return false;
    }

    return rename($src, $dst);
  }


  /** 写入文件
   * fileWrite function.
   *
   * @access public
   * @param string $path 路径
   * @param string $content 内容
   * @param bool $append (default: false) 是否为追加
   * @return 写入字节数
   */
  public function fileWrite($path, $content, $append = false) {
    $path = strtolower($path);

    if (!$this->dirMk(dirname($path))) { // 假如目录不能存在则创建
      return false;
    }

    if ($append) {
      $append = FILE_APPEND;
    }

    return file_put_contents($path, $content, $append);
  }


  /** 复制文件
   * fileCopy function.
   *
   * @access public
   * @param string $src 源路径
   * @param string $dst 目标路径
   * @return 复制结果 (bool)
   */
  public function fileCopy($src, $dst) {
    $src = strtolower($src);
    $dst = strtolower($dst);

    if (!$this->dirMk($dst)) {
      return false;
    }

    if (!self::fileHas($src)) {
      $this->errRecord('File::fileCopy(), Source file not found: ' . $src);
      return false;
    }

    return copy($src, $dst);
  }


  /** 删除文件
   * fileDelete function.
   *
   * @access public
   * @param string $path 路径
   * @return 删除结果 (bool)
   */
  public function fileDelete($path) {
    $path = strtolower($path);

    if (!self::fileHas($path)) { // 文件不能存在则返回 false
      $this->errRecord('File::fileDelete(), File not found: ' . $path);
      return false;
    }

    return unlink($path);
  }


  // 设置目录权限
  public function mod($mod = '') {
    if (Func::isEmpty($mod)) {
      return $this->mod;
    } else {
      $this->mod = $mod;
    }
  }


  // 获取错误
  public function getError() {
    return $this->error;
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
