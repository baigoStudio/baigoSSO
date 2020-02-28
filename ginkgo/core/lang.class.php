<?php
/*-----------------------------------------------------------------
！！！！警告！！！！
以下为系统文件，请勿修改
-----------------------------------------------------------------*/

namespace ginkgo;

//不能非法包含或直接执行
defined('IN_GINKGO') or exit('Access denied');

class Lang {

    protected static $instance;
    private $lang;
    private $config;
    private $current; //默认语言
    private $clientLang; //客户端语言
    private $route;
    private $range = '';

    protected function __construct() {
        $this->config = Config::get('lang');

        $this->getCurrent();
        $this->init();
    }

    protected function __clone() {

    }

    public static function instance() {
        if (Func::isEmpty(static::$instance)) {
            static::$instance = new static();
        }
        return static::$instance;
    }

    public function range($range = '') {
        if (Func::isEmpty($range)) {
            return $this->range;
        } else {
            $this->range = $range;
        }
    }

    //获取当前语言
    function getCurrent($lower = false, $separator = '', $client = false) {
        if ($client) {
            $_str_current = $this->clientLang;
        } else {
            $_str_current = $this->current;
        }

        if ($lower === true) {
            $_str_current = strtolower($_str_current);
        }

        if ((!Func::isEmpty($separator) && is_string($separator)) || $separator == '-') {
            $_str_current = str_replace('_', $separator, $_str_current);
        }

        return $_str_current;
    }


    function setCurrent($lang = '') {
        $this->current = $lang;
    }


    function add($name, $value = '', $range = '') {
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


    function set($name, $value = '', $range = '') { //设置语言字段
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

    function get($name, $range = '', $replace = array(), $show_src = true) { //获取语言字段
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

        if (is_array($replace) && !Func::isEmpty($replace)) {
            $_arr_replace = array_keys($replace);
            foreach ($_arr_replace as $_key=>&$_value) {
                $_value = '{:' . $_value . '}';
            }
            $_str_return = str_ireplace($_arr_replace, $replace, $_str_return);
        }

        return $_str_return;
    }


    function load($path, $range = '') {
        $_arr_lang = array();

        if (Func::isFile($path)) {
            $_arr_lang = Loader::load($path, 'include');
        }

        /*print_r($range);
        print_r('<br>');*/

        $this->set($_arr_lang, '', $range);

        return $_arr_lang;
    }


    private function init() {
        $_str_current = $this->current;

        if (!Func::isEmpty($this->config['switch'])) { //语言开关为开
            if (isset($_SERVER['HTTP_ACCEPT_LANGUAGE'])) {
                $this->clientLang = $_SERVER['HTTP_ACCEPT_LANGUAGE'];
            }
        }

        if (Func::isEmpty($_str_current)) {
            $_str_current = $this->config['default'];
        }

        if (function_exists('mb_internal_encoding')) {
            mb_internal_encoding('UTF-8'); //设置内部字符编码
        }

        setlocale(LC_ALL, $_str_current . '.UTF-8'); //设置区域格式,主要针对 csv 处理

        $this->current = $_str_current;

        $_str_pathSys = GK_PATH_LANG . $_str_current . GK_EXT_LANG;

        if (Func::isFile($_str_pathSys)) {
            $this->lang['__ginkgo__'] = Loader::load($_str_pathSys, 'include');
        }
    }


    private function rangeProcess($range = '') {
        if (Func::isEmpty($range)) {
            $_str_range = $this->range;
        } else {
            $_str_range = $range;
        }

        if (!is_string($_str_range)) {
            $_str_range = '';
        }

        $_mix_return    = '';

        if (strpos($_str_range, '.')) {
            $_mix_return = explode('.', $_str_range);
        } else {
            $_mix_return = $_str_range;
        }

        return $_mix_return;
    }
}


