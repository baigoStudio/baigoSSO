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

// 数组处理
class Arrays {

  public static $error = false; // 错误信息

  // 错误类型
  private static $errType = array(
    JSON_ERROR_DEPTH            => 'Maximum stack depth exceeded',
    JSON_ERROR_STATE_MISMATCH   => 'State mismatch (invalid or malformed JSON)',
    JSON_ERROR_CTRL_CHAR        => 'Control character error, possibly incorrectly encoded',
    JSON_ERROR_SYNTAX           => 'Syntax error',
  );

  /** JSON 编码
   * encode function.
   *
   * @access public
   * @param string $array (default: '') 数组
   * @param string $encode (default: '') 指定编码形式
   * @param mixed $option (default: false) 编码选项
   * @return 编码后的字符串
   */
  public static function toJson($array = array(), $encode = 'json_safe', $option = false) {
    $_str_json   = '[]';

    if (version_compare(PHP_VERSION, '5.4.0', '>') && $option === false) {
      $option = JSON_UNESCAPED_UNICODE;
    }

    if (is_array($array) && Func::notEmpty($array)) {
      $array     = self::map($array, $encode);
      $_str_json = json_encode($array, $option); //json编码

      self::backtrace();
    }

    return $_str_json;
  }


  /** JSON 解码
   * decode function.
   *
   * @access public
   * @param string $string (default: '') json 字符串
   * @param string $decode (default: '') 指定解码形式
   * @param mixed $option (default: false) 解码选项
   * @return 解码后的数组
   */
  public static function fromJson($string = '', $decode = false, $assoc = true) {
    $_arr_json = array();
    $string    = (string)$string;

    if (Func::notEmpty($string)) {
      //$string    = Html::decode($string, 'json');
      $_arr_json = json_decode($string, $assoc); //json解码
      $_arr_json = self::map($_arr_json, $decode); //json解码

      self::backtrace();
    }

    if (!is_array($_arr_json)) {
      $_arr_json = array();
    }

    return $_arr_json;
  }


  public static function each($arr, $func = '', $left = 5, $right = 5, $hide = '*') {
    return self::map($arr, $func, $left, $right, $hide);
  }


  /** 遍历数组并用指定函数处理
   * map function.
   *
   * @access public
   * @static
   * @param array $arr 数组
   * @param string $func (default: '') 处理函数
   * @param int $left (default: 5) 保留左边字符数, 仅在处理函数为 secrecy 时有效
   * @param int $right (default: 5) 保留右边字符数, 仅在处理函数为 secrecy 时有效
   * @param string $hide (default: '*') 替换字符, 仅在处理函数为 secrecy 时有效
   * @return 处理后的数组
   */
  public static function map($arr, $func = '', $left = 5, $right = 5, $hide = '*') {
    if (is_array($arr) && Func::notEmpty($arr)) {
      foreach ($arr as $_key=>$_value) {
        if (is_array($_value) && Func::notEmpty($_value)) {
          $arr[$_key] = self::map($_value, $func, $left, $right, $hide);
        } else if (is_scalar($_value) && Func::notEmpty($_value)) {
          $_value = Func::safe($_value, true);

          switch ($func) {
            case 'json_safe':
            case 'jsonsafe':
              $_value = Html::decode($_value, true);
            break;

            case 'html_decode':
            case 'htmldecode':
              $_value = Html::decode($_value);
            break;

            case 'html_encode':
            case 'htmlencode':
              $_value = Html::encode($_value);
            break;

            case 'url_encode':
            case 'urlencode':
              $_value = rawurlencode($_value);
            break;

            case 'url_decode':
            case 'urldecode':
              $_value = rawurldecode($_value);
            break;

            case 'base64_encode':
            case 'base64encode':
              $_value = Strings::toBase64($_value);
            break;

            case 'base64_decode':
            case 'base64decode':
              $_value = Strings::fromBase64($_value);
            break;

            /*case 'utf8encode':
              $_value = utf8_encode($_value);
            break;

            case 'utf8decode':
              $_value = utf8_decode($_value);
            break;*/

            case 'md5':
              $_value = md5($_value);
            break;

            case 'secrecy':
            case 'secret': // 兼容
              $_value = Strings::secrecy($_value, $left, $right, $hide);
            break;
          }

          $arr[$_key] = $_value;
        } else {
          $arr[$_key] = '';
        }
      }
    } else {
      $arr = array();
    }

    return $arr;
  }


  /** 兼容用
   * filter function.
   *
   * @access public
   * @static
   * @param array $arr 数组
   * @param bool $pop_false (default: true) 是否剔除 false 元素
   * @return void
   */
  public static function filter($arr, $pop_false = true) {
    return self::unique($arr, $pop_false);
  }

  /** 过滤数组重复项目
   * unique function.
   *
   * @access public
   * @static
   * @param array $arr 数组
   * @param bool $pop_false (default: true) 是否剔除 false 元素
   * @return void
   */
  public static function unique($arr, $pop_false = true) {
    if (Func::notEmpty($arr)) {
      $arr = array_unique($arr);

      if ($pop_false === true) {
        $arr = array_filter($arr); // 剔除 false 元素
      }
    }
    return $arr;
  }


  /** 取得错误
   * getError function.
   *
   * @access public
   * @static
   * @return 错误数组
   */
  public static function getError() {
    return self::$error;
  }


  /** 错误追踪
   * backtrace function.
   *
   * @access protected
   * @static
   * @return void
   */
  protected static function backtrace() {
    $_err_no  = json_last_error();

    if ($_err_no) {
      if (isset(self::$errType[$_err_no])) {
        $_err_msg = self::$errType[$_err_no];
      } else {
        $_err_msg = 'Unknown';
      }

      self::$error = array(
        'err_type'      => $_err_no,
        'err_message'   => $_err_msg,
      );

      $_optDebugDump = false;

      $_mix_configDebug  = Config::get('debug'); // 取得调试配置

      if (is_array($_mix_configDebug)) {
        if ($_mix_configDebug['dump'] === 'trace') { // 假如配置为输出
          $_optDebugDump = 'trace';
        }
      } else if (is_scalar($_mix_configDebug)) {
        if ($_mix_configDebug === 'trace') { // 假如配置为输出
          $_optDebugDump = 'trace';
        }
      }

      if ($_optDebugDump === 'trace') { // 追溯详细错误
        $_arr_trace = debug_backtrace(false);

        if (isset($_arr_trace[1])) {
          $_err_file = '';
          $_err_line = 0;

          if (isset($_arr_trace[1]['file'])) {
            $_err_file = $_arr_trace[1]['file'];
          }

          if (isset($_arr_trace[1]['line'])) {
            $_err_line = $_arr_trace[1]['line'];
          }

          self::$error['err_file'] = $_err_file;
          self::$error['err_line'] = $_err_line;
        }
      }
    }
  }
}
