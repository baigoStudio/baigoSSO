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

// 字符串处理
class Strings {

  /** 将字符串中每个单词的首字母转换为大写
   * ucwords function.
   *
   * @access public
   * @static
   * @param string $string 字符串
   * @param string $delimiter (default: "\t\r\n\f\v") 分隔符
   * @return 转换后的字符串
   */
  public static function ucwords($string, $delimiter = "\t\r\n\f\v") {
    $string         = (string)$string;
    $string         = strtolower($string);

    $_str_return    = '';

    //print_r(version_compare(PHP_VERSION, '5.4.32', '>'));

    if (version_compare(PHP_VERSION, '5.4.32', '>')) { // 大于 5.4.32, 直接使用 php 函数
      $_str_return = ucwords($string, $delimiter);
    } else { // 否则, 另行处理
      $_arr_string = explode($delimiter, $string); // 用分隔符转换成数组
      //print_r($_arr_string);
      foreach ($_arr_string as $_key=>$_value) { // 遍历
        $_str_return .= $delimiter . ucfirst(strtolower($_value)); // 首字母大写并拼合
      }

      $_str_return = trim($_str_return, $delimiter); // 去除首尾的分隔符
    }

    return $_str_return;
  }


  /** 强化版 strtotime, 可以自动过滤转义过的符号
   * strtotime function.
   *
   * @access public
   * @static
   * @param string $time 日期时间字符串
   * @return 时间戳
   */
  public static function toTime($time) {
    $_arr_src   = array('&#45;', '&#58;');
    $_arr_dst   = array('-', ':');
    $time       = str_replace($_arr_src, $_arr_dst, $time);
    $_tm_return = strtotime($time);

    return $_tm_return;
  }


  /** 转换为驼峰命名
   * toHump function.
   *
   * @access public
   * @static
   * @param string $string 字符串
   * @param string $delimiter (default: '_') 分隔符
   * @param bool $lcfirst (default: false) 首字母是否小写
   * @return 转换后的字符串
   */
  public static function toHump($string, $delimiter = '_', $lcfirst = false) {
    $delimiter  = (string)$delimiter; // 转换为字符串类型
    $string     = (string)$string; // 转换为字符串类型
    $string     = self::ucwords($string, $delimiter); // 将字符串中每个单词的首字母转换为大写
    $string     = str_replace($delimiter, '', $string); // 去除分隔符

    if ($lcfirst === true) {
      $string = lcfirst($string); // 首字母小写
    }

    return $string;
  }


  /** 转换为分隔符命名
   * toLine function.
   *
   * @access public
   * @static
   * @param string $string 字符串
   * @param string $delimiter (default: '_') 分隔符
   * @return void
   */
  public static function toLine($string, $delimiter = '_') {
    $string = (string)$string; // 转换为字符串类型

    $_bool_result = preg_match_all('/([A-Z]{1})/', $string, $_arr_matches); // 正则匹配

    //print_r($_arr_matches);

    if (isset($_arr_matches[0]) && Func::notEmpty($_arr_matches[0])) {
      foreach ($_arr_matches[0] as $_key=>$_value) { // 便利匹配结果
        $string = str_replace($_value, strtolower($delimiter . $_value), $string); // 全部转换为小写, 并拼合
      }
    }

    return trim($string, $delimiter); // 去除首尾的分隔符
  }

  /** 容量格式化
   * sizeFormat function.
   *
   * @access public
   * @static
   * @param int $size (default: 0) 容量
   * @param int $float (default: 2) 小数点
   * @return 格式化后的容量字符串
   */
  public static function sizeFormat($size = 0, $float = 2) {
    $_return = 0;

    if ($size > 0) {
      $_arr_unit = array('B', 'KB', 'MB', 'GB', 'TB', 'PB');

      if (!is_numeric($size)) {
        $size = floatval($size);
      }

      if (!is_numeric($size)) {
        $size = 0;
      }

      $_return = number_format($size / pow(1024, ($_iii = floor(log($size, 1024)))), $float) . ' ' . $_arr_unit[$_iii];
    }

    return $_return;
  }


  /** 数字格式化
   * numFormat function.
   *
   * @access public
   * @static
   * @param int $num 数字
   * @param int $float (default: 2) 小数点
   * @return 格式化后的数字
   */
  public static function numFormat($num, $float = 2) {
    if (!is_numeric($num)) {
      $num = floatval($num);
    }

    if (!is_numeric($num)) {
      $num = 0;
    }

    return number_format($num, $float);
  }


  /** 隐私字符串
   * strSecret function.
   *
   * @access public
   * @static
   * @param string $string 字符串
   * @param int $left (default: 5) 保留左边字符数
   * @param int $right (default: 5) 保留右边字符数
   * @param string $hide (default: '*') 替换字符
   * @return 隐私处理后的字符串
   */
  public static function secrecy($string, $left = 5, $right = 5, $hide = '*'){
    $string     = (string)$string;
    $_str_mid   = ''; // 中间字符串
    if (function_exists('mb_strlen')) {
      $_num_len   = mb_strlen($string); // 计算长度
    } else {
      $_num_len   = strlen($string);
    }

    if ($_num_len <= $left + $right) { // 如果左右截取加起来大于等于长度, 则左右个保留一个字符
      $left   = 1;
      $right  = 1;
    }

    $_num_mid = $_num_len - $left - $right;

    if (function_exists('mb_substr')) {
      $_str_left      = mb_substr($string, 0, $left); // 取左边字符
    } else {
      $_str_left      = substr($string, 0, $left);
    }
    if (function_exists('mb_substr')) {
      $_str_right     = mb_substr($string, 0 - $_num_len, $right); // 取右边字符
    } else {
      $_str_right     = substr($string, 0 - $_num_len, $right);
    }

    for ($_iii = 0; $_iii < $_num_mid; ++$_iii) { // 隐藏
      $_str_mid .= $hide;
    }

    return $_str_left . $_str_mid . $_str_right; // 拼合返回
  }


  /** Base64 解码
   * decode function.
   *
   * @access public
   * @static
   * @param string $string 待解码
   * @param bool $url_safe (default: true) 是否用 url 安全的形式解码
   * @return 解码后字符串
   */
  public static function fromBase64($string, $url_safe = true) {
    $string = (string)$string; //转换为字符串类型

    if ($url_safe) {
      $string = str_replace(array('-', '_'), array('+', '/'), $string); // 替换 URL 传输时容易出错的字符
    }

    $_num_mod4 = strlen($string) % 4;

    if ($_num_mod4) {
      $string .= substr('====', $_num_mod4);
    }

    return base64_decode($string);
  }


  /** Base64 编码
   * encode function.
   *
   * @access public
   * @static
   * @param string $string 待编码
   * @param bool $url_safe (default: true) 是否用 url 安全的形式编码
   * @return 编码后字符串
   */
  public static function toBase64($string, $url_safe = true) {
    $string = (string)$string; //转换为字符串类型

    $string = base64_encode($string);

    if ($url_safe) {
      $string = str_replace(array('+', '/', '='), array('-', '_', ''), $string); // 替换 URL 传输时容易出错的字符
    }

    return $string;
  }


  public static function isJson($string) {
    $_return = true;

    json_decode($string);

    $_last_err = json_last_error();

    if ($_last_err) {
      $_return = false;
    }

    return $_return;
  }
}
