<?php
/*-----------------------------------------------------------------
！！！！警告！！！！
以下为系统文件，请勿修改
-----------------------------------------------------------------*/

namespace ginkgo\cache\driver;

use ginkgo\Loader;
use ginkgo\Func;
use ginkgo\cache\Driver;
use ginkgo\File as File_Base;

// 不能非法包含或直接执行
if (!defined('IN_GINKGO')) {
  return 'Access denied';
}

// 文件型缓存驱动
class File extends Driver {

  protected function __construct($config = array()) {
    parent::__construct($config);

    $this->obj_file = File_Base::instance();
  }

  /** 检查缓存是否存在
   * check function.
   *
   * @access public
   * @param mixed $name 缓存名称
   * @param bool $check_expire (default: false) 是否检查过期时间 (默认不检查)
   * @return 检查结果 (bool)
   */
  public function check($name, $check_expire = false) {
    $_str_path    = $this->getPath($name); // 取得路径

    $_bool_return = File_Base::fileHas($_str_path); // 文件是否存在

    if ($_bool_return && $check_expire) { // 如果存在, 根据参数验证时间
      $_arr_content = Loader::load($_str_path);

      if (isset($_arr_content['expire']) && $_arr_content['expire'] > 0) {
        if ($_arr_content['expire'] < GK_NOW) { // 定义了时间, 且早于当前时间, 过期
          $_bool_return = false;
        }
      }
    }

    return $_bool_return;
  }

  /** 读取
   * read function.
   *
   * @access public
   * @param mixed $name 缓存名称
   * @return 缓存内容
   */
  public function read($name) {
    $_str_path    = $this->getPath($name); // 取得路径

    $_arr_content = Loader::load($_str_path); // 读取

    $_mix_return  = '';

    if (isset($_arr_content['expire'])) { // 如果定义了时间
      if ($_arr_content['expire'] > 0) { // 如果定义了时间
        if ($_arr_content['expire'] > GK_NOW) { // 晚于当前时间, 有效
          $_mix_return = $_arr_content['value'];
        } else { // 早于当前时间, 直接删除
          $this->delete($name);
        }
      } else { // 为 0 时, 永久有效
        $_mix_return = $_arr_content['value'];
      }
    } else { // 未定义直接删除
      $this->delete($name);
    }

    return $_mix_return;
  }

  /** 写入
   * write function.
   *
   * @access public
   * @param mixed $name 缓存名称
   * @param mixed $content 缓存内容
   * @return 写入字节数
   */
  public function write($name, $content, $life_time = 0) {
    $_str_path = $this->getPath($name); // 取得路径

    $_str_content = '';

    if (is_string($content)) {
      $_str_content = '\'' . $content . '\''; // 如果是字符串, 进行转义
    } else {
      $_str_content = $content;
    }

    $_tm_expire = 0; // 永久有效

    if ($life_time !== false && $life_time !== 'false') { // 如果参数指定了有效时间, 则直接使用
      $life_time  = (int)$life_time;
      if ($life_time > 0) {
        $_tm_expire = GK_NOW + $life_time;
      }
    } else if ($this->config['life_time'] > 0) { // 否则以配置文件为准
      $_tm_expire = GK_NOW + $this->config['life_time'];
    }

    $_arr_outPut = array(
      'expire' => $_tm_expire,
      'value'  => $content,
    );

    $_str_outPut = '<?php return ' . var_export($_arr_outPut, true) . ';'; // 转换为 php 语句

    return $this->obj_file->fileWrite($_str_path, $_str_outPut); // 写入文件
  }

  /** 删除
   * delete function.
   *
   * @access public
   * @param mixed $name 缓存名称
   * @return 删除结果 (bool)
   */
  public function delete($name) {
    $_str_path = $this->getPath($name);

    return $this->obj_file->fileDelete($_str_path);
  }


  /** 取得路径
   * getPath function.
   *
   * @access private
   * @param mixed $name 缓存名
   * @return 路径
   */
  private function getPath($name) {
    $_str_path = GK_PATH_CACHE; // 基本路径

    if (isset($this->config['prefix']) && Func::notEmpty($this->config['prefix'])) {
      $_str_path .= $this->config['prefix'] . DS; // 加上前缀
    }

    $_str_path .= $name . GK_EXT; // 补全路径

    return $_str_path;
  }
}
