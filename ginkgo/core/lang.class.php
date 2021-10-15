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

// 语言处理类
class Lang {

  public $lang; // 语言数据
  public $config = array(); // 语言配置
  public $current; // 当前语言
  public $clientLang; // 客户端语言
  public $range = ''; // 作用域

  protected static $instance; // 当前实例

  private $configThis = array( //语言
    'switch'    => false, //语言开关
    'default'   => 'zh_CN', //默认语言
  );

  // 构造函数
  protected function __construct($config = array()) {
    $this->config($config);

    $this->getCurrent(); // 获取当前语言
    $this->init(); // 初始化
  }

  protected function __clone() { }

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
    $_arr_config   = Config::get('lang'); // 取得图片配置

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

  /** 设置, 获取作用域
   * range function.
   *
   * @access public
   * @param string $range (default: '') 作用域
   * @return 如果参数为空则返回当前作用域, 否则无返回
   */
  public function range($range = '') {
    if (Func::isEmpty($range)) {
      return $this->range;
    } else {
      $this->range = $range;
    }
  }


  /** 获取当前语言
   * getCurrent function.
   *
   * @access public
   * @param bool $lower (default: false) 是否转换为小写
   * @param string $separator (default: '') 语言、国家分隔符
   * @param bool $client (default: false) 是否以客户端语言为准
   * @return void
   */
  public function getCurrent($lower = false, $separator = '', $client = false) {
    if ($client) {
      $_str_current = $this->clientLang;
    } else {
      $_str_current = $this->current;
    }

    if ($lower === true) {
      $_str_current = strtolower($_str_current);
    }

    if ((Func::notEmpty($separator) && is_string($separator)) || $separator == '-') {
      $_str_current = str_replace('_', $separator, $_str_current);
    }

    return $_str_current;
  }


  /** 设置当前语言
   * setCurrent function.
   *
   * @access public
   * @param string $lang (default: '') 语言代码
   * @return void
   */
  public function setCurrent($lang = '') {
    $this->current = $lang;
  }


  /** 添加变量 (冲突不覆盖)
   * add function.
   *
   * @access public
   * @param string $name 变量名
   * @param string $value (default: '') 值
   * @param string $range (default: '') 作用域
   * @return void
   */
  public function add($name, $value = '', $range = '') {
    $_mix_range = $this->rangeProcess($range);

    if (Func::isEmpty($_mix_range)) {
      if (!isset($this->lang[$name])) {
        $this->lang[$name] = $value;
      }
    } else if (is_array($_mix_range)) {
      if (isset($_mix_range[1])) {
        if (!isset($this->lang[$_mix_range[0]][$_mix_range[1]][$name])) {
          $this->lang[$_mix_range[0]][$_mix_range[1]][$name] = $value;
        }
      } else if (isset($_mix_range[0])) {
        if (!isset($this->lang[$_mix_range[0]][$name])) {
          $this->lang[$_mix_range[0]][$name] = $value;
        }
      }
    } else if (is_string($_mix_range)) {
      if (!isset($this->lang[$_mix_range][$name])) {
        $this->lang[$_mix_range][$name] = $value;
      }
    }
  }


  /** 设置变量 (冲突覆盖)
   * set function.
   *
   * @access public
   * @param mixed $name 变量名
   * @param string $value (default: '') 值
   * @param string $range (default: '') 作用域
   * @return void
   */
  public function set($name, $value = '', $range = '') { //设置语言字段
    $_mix_range = $this->rangeProcess($range);

    /*print_r($name);
    print_r('<br>');*/

    if (Func::isEmpty($_mix_range)) {
      if (is_array($name)) {
        $this->lang = array_replace_recursive($this->lang, $name);
      } else if (is_string($name)) {
        if (isset($this->lang[$name]) && is_array($this->lang[$name]) && is_array($value)) {
          $this->lang[$name] = array_replace_recursive($this->lang[$name], $value);
        } else {
          $this->lang[$name] = $value;
        }
      }
    } else if (is_array($_mix_range)) {
      if (is_array($name)) {
        if (isset($_mix_range[1])) {
          if (isset($this->lang[$_mix_range[0]][$_mix_range[1]]) && is_array($this->lang[$_mix_range[0]][$_mix_range[1]])) {
            $this->lang[$_mix_range[0]][$_mix_range[1]] = array_replace_recursive($this->lang[$_mix_range[0]][$_mix_range[1]], $name);
          } else {
            $this->lang[$_mix_range[0]][$_mix_range[1]] = $name;
          }
        } else if (isset($_mix_range[0])) {
          if (isset($this->lang[$_mix_range[0]]) && is_array($this->lang[$_mix_range[0]])) {
            $this->lang[$_mix_range[0]] = array_replace_recursive($this->lang[$_mix_range[0]], $name);
          } else {
            $this->lang[$_mix_range[0]] = $name;
          }
        }
      } else if (is_string($name)) {
        if (isset($_mix_range[1])) {
          if (isset($this->lang[$_mix_range[0]][$_mix_range[1]][$name]) && is_array($this->lang[$_mix_range[0]][$_mix_range[1]][$name]) && is_array($value)) {
            $this->lang[$_mix_range[0]][$_mix_range[1]][$name] = array_replace_recursive($this->lang[$_mix_range[0]][$_mix_range[1]][$name], $value);
          } else {
            $this->lang[$_mix_range[0]][$_mix_range[1]][$name] = $value;
          }
        } else if (isset($_mix_range[0])) {
          if (isset($this->lang[$_mix_range[0]][$name]) && is_array($this->lang[$_mix_range[0]][$name]) && is_array($value)) {
            $this->lang[$_mix_range[0]][$name] = array_replace_recursive($this->lang[$_mix_range[0]][$name], $value);
          } else {
            $this->lang[$_mix_range[0]][$name] = $value;
          }
        }
      }
    } else if (is_string($_mix_range)) {
      if (is_array($name)) {
        if (isset($this->lang[$_mix_range]) && is_array($this->lang[$_mix_range])) {
          $this->lang[$_mix_range] = array_replace_recursive($this->lang[$_mix_range], $name);
        } else {
          $this->lang[$_mix_range] = $name;
        }
      } else {
        if (isset($this->lang[$_mix_range][$name]) && is_array($this->lang[$_mix_range][$name]) && is_array($value)) {
          $this->lang[$_mix_range][$name] = array_replace_recursive($this->lang[$_mix_range][$name], $value);
        } else {
          $this->lang[$_mix_range][$name] = $value;
        }
      }
    }

    //print_r($this->lang);
  }


  /** 获取语言变量
   * get function.
   *
   * @access public
   * @param string $name 变量名
   * @param string $range (default: '') 作用域
   * @param array $replace (default: array()) 输出替换
   * @param bool $show_src (default: true) 如果变量不存在, 是否显示变量名
   * @return void
   */
  public function get($name, $range = '', $replace = array(), $show_src = true) { //获取语言字段
    $name = (string)$name;

    $_mix_range     = $this->rangeProcess($range);

    /*print_r($name);
    print_r(' ||| ');
    print_r($_mix_range);
    print_r('<br>');*/

    if ($show_src) {
      $_str_return    = $name;
    } else {
      $_str_return    = '';
    }

    if (Func::isEmpty($_mix_range)) {
      if (isset($this->lang[$name])) {
        $_str_return = $this->lang[$name];
      }
    } else if (is_array($_mix_range)) {
      if (isset($_mix_range[1])) {
        if (isset($this->lang[$_mix_range[0]][$_mix_range[1]][$name])) {
          $_str_return = $this->lang[$_mix_range[0]][$_mix_range[1]][$name];
        }
      } else if (isset($_mix_range[0])) {
        if (isset($this->lang[$_mix_range[0]][$name])) {
          $_str_return = $this->lang[$_mix_range[0]][$name];
        }
      }
    } else if (is_string($_mix_range)) {
      if (isset($this->lang[$_mix_range][$name])) {
        $_str_return = $this->lang[$_mix_range][$name];
      }
    }

    if (is_array($replace) && Func::notEmpty($replace)) {
      $_arr_replace = array_keys($replace);
      foreach ($_arr_replace as $_key=>&$_value) {
        $_value = '{:' . $_value . '}';
      }
      $_str_return = str_replace($_arr_replace, $replace, $_str_return);
    }

    return $_str_return;
  }


  /** 载入语言包
   * load function.
   *
   * @access public
   * @param string $path 路径
   * @param string $range (default: '') 作用域
   * @return void
   */
  public function load($path, $range = '') {
    $_arr_lang = array();

    if (File::fileHas($path)) {
      $_arr_lang = Loader::load($path, 'include');
    }

    /*print_r($range);
    print_r('<br>');*/

    $this->set($_arr_lang, '', $range); // 设置变量

    return $_arr_lang;
  }


  /** 初始化
   * init function.
   *
   * @access private
   * @return void
   */
  private function init() {
    $_str_current = $this->current;

    if ($this->config['switch'] === true || $this->config['switch'] === 'true') { // 语言开关为开
      if (isset($_SERVER['HTTP_ACCEPT_LANGUAGE'])) {
        $this->clientLang = $_SERVER['HTTP_ACCEPT_LANGUAGE'];
      }
    }

    if (Func::isEmpty($_str_current)) {
      $_str_current = $this->config['default'];
    }

    if (function_exists('mb_internal_encoding')) {
      mb_internal_encoding('UTF-8'); // 设置内部字符编码
    }

    setlocale(LC_ALL, $_str_current . '.UTF-8'); // 设置区域格式,主要针对 csv 处理

    $this->current = $_str_current;

    $_str_pathSys = GK_PATH_LANG . $_str_current . GK_EXT_LANG;

    if (File::fileHas($_str_pathSys)) {
      $this->lang['__ginkgo__'] = Loader::load($_str_pathSys, 'include'); // 载入框架语言包
    }
  }


  /** 作用域处理
   * rangeProcess function.
   *
   * @access private
   * @static
   * @param string $range (default: '') 作用域
   * @return 作用域数组
   */
  private function rangeProcess($range) {
    if (Func::isEmpty($range)) {
      $_str_range = $this->range;
    } else {
      $_str_range = $range;
    }

    if (!is_string($_str_range)) {
      $_str_range = '';
    }

    $_mix_return = '';

    if (strpos($_str_range, '.')) {
      $_mix_return = explode('.', $_str_range);
    } else {
      $_mix_return = $_str_range;
    }

    return $_mix_return;
  }
}
