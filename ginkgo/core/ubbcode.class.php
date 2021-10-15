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

// Ubb code
class Ubbcode {

  // 成对规则
  public static $pairRules    = array('strong', 'code', 'del', 'kbd', 'u', 'i', 'blockquote', 'h1', 'h2', 'h3', 'h4', 'h5', 'h6');

  // 单独规则
  public static $singleRules  = array('hr', 'br');

  // 替换规则
  public static $replaceRules = array(
    'quote' => 'blockquote',
    'b'     => 'strong',
    'em'    => 'i',
    's'     => 'del',
  );

  // 是否将换行符替换为 br 标签
  public static $nl2br = true;

  // 正则规则
  public static $regexRules = array(
    '/\[url=([\-A-Za-z0-9+&@#\/%?\=~\_|!:,\.;]+[\-A-Za-z0-9+&@#\/%\=~\_|])\](.+)\[\/url\]/i' => '<a href="$1" target="_blank" title="$1">$2</a>',
    '/\[url\]([\-A-Za-z0-9+&@#\/%?\=~\_|!:,\.;]+[\-A-Za-z0-9+&@#\/%\=~\_|])\[\/url\]/i'      => '<a href="$1" target="_blank">$1</a>',
    '/\[img=([\-A-Za-z0-9+&@#\/%?\=~\_|!:,\.;]+[\-A-Za-z0-9+&@#\/%\=~\_|])\](.+)\[\/img\]/i' => '<img src="$1" alt="$2" title="$2">',
    '/\[img\]([\-A-Za-z0-9+&@#\/%?\=~\_|!:,\.;]+[\-A-Za-z0-9+&@#\/%\=~\_|])\[\/img\]/i'      => '<img src="$1">',
    '/\[color=(\w+)\](.+)\[\/color\]/i'      => '<span style="color:$1">$2</span>',
    '/\[bgcolor=(\w+)\](.+)\[\/bgcolor\]/i'  => '<span style="background-color:$1">$2</span>',
    '/\[size=(\d+)\](\d+)\[\/size\]/i'       => '<span style="font-size:$1">$2</span>',
  );


  // 添加成对规则
  public static function addPair($pair) {
    if (is_array($pair)) {
      self::$pairRules = array_merge(self::$pairRules, $pair);
    } else if (is_string($pair)) {
      self::$pairRules[] = $pair;
    }
  }


  // 添加单独规则
  public static function addSingle($single) {
    if (is_array($single)) {
      self::$singleRules = array_merge(self::$singleRules, $single);
    } else if (is_string($single)) {
      self::$singleRules[] = $single;
    }
  }


  // 添加替换规则
  public static function addReplace($replace, $dst = '') {
    if (is_array($replace)) {
      self::$replaceRules = array_replace_recursive(self::$replaceRules, $replace);
    } else if (is_string($replace)) {
      self::$replaceRules[$replace] = $dst;
    }
  }

  // 添加正则规则
  public static function addRegex($regex, $dst = '') {
    if (is_array($regex)) {
      self::$regexRules = array_replace_recursive(self::$regexRules, $regex);
    } else if (is_string($regex)) {
      self::$regexRules[$regex] = $dst;
    }
  }


  // 去除标签
  public static function stripCode($string) {
    $_arr_regs = array(
      '/\[img=(.+)\](.+)\[\/img\]/i',
      '/\[img\](.+)\[\/img\]/i',
      '/\[(.+)\]/i',
      '/\[(.+)=(.+)\]/i',
      '/\[\/(.+)\]/i',
    );

    $string = preg_replace($_arr_regs, '', $string);

    return $string;
  }

  // 转换 ubbcode
  public static function convert($string) {
    $_arr_src = array();
    $_arr_dst = array();

    // 转换成对规则
    foreach (self::$pairRules as $_key=>$_value) {
      $_arr_src[] = '[' . $_value . ']';
      $_arr_src[] = '[/' . $_value . ']';
      $_arr_dst[] = '<' . $_value . '>';
      $_arr_dst[] = '</' . $_value . '>';
    }

    $string = str_ireplace($_arr_src, $_arr_dst, $string);

    $_arr_src = array();
    $_arr_dst = array();

    // 转换替换规则
    foreach (self::$replaceRules as $_key=>$_value) {
      $_arr_src[] = '[' . $_key . ']';
      $_arr_src[] = '[/' . $_key . ']';
      $_arr_dst[] = '<' . $_value . '>';
      $_arr_dst[] = '</' . $_value . '>';
    }

    $string = str_ireplace($_arr_src, $_arr_dst, $string);

    $_arr_src = array();
    $_arr_dst = array();

    // 转换单独规则
    foreach (self::$singleRules as $_key=>$_value) {
      $_arr_src[] = '[' . $_value . ']';
      $_arr_dst[] = '<' . $_value . '>';
    }

    $string = str_ireplace($_arr_src, $_arr_dst, $string);

    $_arr_regs = array();
    $_arr_dsts = array();

    // 转换正则规则
    foreach (self::$regexRules as $_key=>$_value) {
      if (strpos($_key, '/') !== 0 && !preg_match('/\/[imsU]{0,4}$/', $_key)) {
        // 不是正则表达式则两端补上/
        $_key = '/^' . $_key . '$/';
      }

      $_arr_regs[] = $_key;
      $_arr_dsts[] = $_value;
    }

    $string = preg_replace($_arr_regs, $_arr_dsts, $string);

    if (self::$nl2br === true) {
      $string = nl2br($string, false);
    }

    return $string;
  }

  // 获取图片
  public static function getImages($string = '', $options = '', $filter = '', $include = '') {
    $_arr_data      = array();
    $_arr_return    = array();

    if (Func::notEmpty($string)) {
      preg_match_all('/\[img\]([\-A-Za-z0-9+&@#\/%?\=~\_|!:,\.;]+[\-A-Za-z0-9+&@#\/%\=~\_|])\[\/img\]/i', $string, $_arr_matches_1); // 正则匹配
      preg_match_all('/\[img=([\-A-Za-z0-9+&@#\/%?\=~\_|!:,\.;]+[\-A-Za-z0-9+&@#\/%\=~\_|])\](.+)\[\/img\]/i', $string, $_arr_matches_2); // 正则匹配

      if (isset($_arr_matches_1[1]) && Func::notEmpty($_arr_matches_1[1])) {
        $_arr_data = array_merge($_arr_data, $_arr_matches_1[1]);
      }

      if (isset($_arr_matches_2[1]) && Func::notEmpty($_arr_matches_2[1])) {
        $_arr_data = array_merge($_arr_data, $_arr_matches_2[1]);
      }
    }

    if (Func::notEmpty($_arr_data) && Func::notEmpty($include)) {
      foreach ($_arr_data as $_key=>$_value) {
        if (is_array($include)) {
          foreach ($include as $_key_filter=>$_value_filter) {
            if (!stristr($_value, $_value_filter)) {
              unset($_arr_data[$_key]);
            }
          }
        } else if (is_string($include)) {
          if (!stristr($_value, $include)) {
            unset($_arr_data[$_key]);
          }
        }
      }
    }

    if (Func::notEmpty($_arr_data) && Func::notEmpty($filter)) {
      foreach ($_arr_data as $_key=>$_value) {
        if (is_array($filter)) {
          foreach ($filter as $_key_filter=>$_value_filter) {
            if (stristr($_value, $_value_filter)) {
              unset($_arr_data[$_key]);
            }
          }
        } else if (is_string($filter)) {
          if (stristr($_value, $filter)) {
            unset($_arr_data[$_key]);
          }
        }
      }
    }

    if (Func::notEmpty($_arr_data)) {
      foreach ($_arr_data as $_key=>$_value) {
        $_arr_return[] = pathinfo(Html::decode($_value, 'url'), $options);
      }
    }

    return $_arr_return;
  }
}
