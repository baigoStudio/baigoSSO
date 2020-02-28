<?php
/*-----------------------------------------------------------------
！！！！警告！！！！
以下为系统文件，请勿修改
-----------------------------------------------------------------*/

namespace ginkgo;

//不能非法包含或直接执行
defined('IN_GINKGO') or exit('Access denied');

class Config {

    protected static $instance;
    private static $range  = '';
    private static $config = array();
    private static $init;
    private static $count  = 1;

    protected function __construct() {
    }

    protected function __clone() {

    }

    public static function init() {
        self::loadSys();

        self::$init = true;
    }

    public static function range($range = '') {
        if (Func::isEmpty(self::$init)) {
            self::init();
        }

        if (Func::isEmpty($range)) {
            return self::$range;
        } else {
            self::$range = $range;
        }
    }


    public static function add($name, $value = '', $range = '') {
        $name = (string)$name;

        if (Func::isEmpty(self::$init)) {
            self::init();
        }

        $_mix_range = self::rangeProcess($range);

        if (Func::isEmpty($_mix_range)) {
            if (!isset(self::$config[$name])) {
                self::$config[$name] = $value;
            }
        } else if (is_array($_mix_range)) {
            if (isset($_mix_range[1])) {
                if (!isset(self::$config[$_mix_range[0]][$_mix_range[1]][$name])) {
                    self::$config[$_mix_range[0]][$_mix_range[1]][$name] = $value;
                }
            } else if (isset($_mix_range[0])) {
                if (!isset(self::$config[$_mix_range[0]][$name])) {
                    self::$config[$_mix_range[0]][$name] = $value;
                }
            }
        } else if (is_string($_mix_range)) {
            if (!isset(self::$config[$_mix_range][$name])) {
                self::$config[$_mix_range][$name] = $value;
            }
        }
    }


    public static function set($name, $value = '', $range = '') {
        if (Func::isEmpty(self::$init)) {
            self::init();
        }

        $_mix_range = self::rangeProcess($range);

        if (Func::isEmpty($_mix_range)) {
            if (is_array($name)) {
                self::$config = array_replace_recursive(self::$config, $name);
            } else if (is_string($name)) {
                if (isset(self::$config[$name]) && is_array(self::$config[$name]) && is_array($value)) {
                    self::$config[$name] = array_replace_recursive(self::$config[$name], $value);
                } else {
                    self::$config[$name] = $value;
                }
            }
        } else if (is_array($_mix_range)) {
            if (is_array($name)) {
                if (isset($_mix_range[1])) {
                    if (isset(self::$config[$_mix_range[0]][$_mix_range[1]]) && is_array(self::$config[$_mix_range[0]][$_mix_range[1]])) {
                        self::$config[$_mix_range[0]][$_mix_range[1]] = array_replace_recursive(self::$config[$_mix_range[0]][$_mix_range[1]], $name);
                    } else {
                        self::$config[$_mix_range[0]][$_mix_range[1]] = $name;
                    }
                } else if (isset($_mix_range[0])) {
                    if (isset(self::$config[$_mix_range[0]]) && is_array(self::$config[$_mix_range[0]])) {
                        self::$config[$_mix_range[0]] = array_replace_recursive(self::$config[$_mix_range[0]], $name);
                    } else {
                        self::$config[$_mix_range[0]] = $name;
                    }
                }
            } else if (is_string($name)) {
                if (isset($_mix_range[1])) {
                    if (isset(self::$config[$_mix_range[0]][$_mix_range[1]][$name]) && is_array(self::$config[$_mix_range[0]][$_mix_range[1]][$name]) && is_array($value)) {
                        self::$config[$_mix_range[0]][$_mix_range[1]][$name] = array_replace_recursive(self::$config[$_mix_range[0]][$_mix_range[1]][$name], $value);
                    } else {
                        self::$config[$_mix_range[0]][$_mix_range[1]][$name] = $value;
                    }
                } else if (isset($_mix_range[0])) {
                    if (isset(self::$config[$_mix_range[0]][$name]) && is_array(self::$config[$_mix_range[0]][$name]) && is_array($value)) {
                        self::$config[$_mix_range[0]][$name] = array_replace_recursive(self::$config[$_mix_range[0]][$name], $value);
                    } else {
                        self::$config[$_mix_range[0]][$name] = $value;
                    }
                }
            }
        } else if (is_string($_mix_range)) {
            if (is_array($name)) {
                if (isset(self::$config[$_mix_range]) && is_array(self::$config[$_mix_range])) {
                    self::$config[$_mix_range] = array_replace_recursive(self::$config[$_mix_range], $name);
                } else {
                    self::$config[$_mix_range] = $name;
                }
            } else {
                if (isset(self::$config[$_mix_range][$name]) && is_array(self::$config[$_mix_range][$name]) && is_array($value)) {
                    self::$config[$_mix_range][$name] = array_replace_recursive(self::$config[$_mix_range][$name], $value);
                } else {
                    self::$config[$_mix_range][$name] = $value;
                }
            }
        }
    }


    public static function get($name = '', $range = '') {
        $name = (string)$name;

        if (Func::isEmpty(self::$init)) {
            self::init();
        }

        $_mix_range  = self::rangeProcess($range);

        $_mix_return = '';


        if (Func::isEmpty($_mix_range)) {
            if (Func::isEmpty($name)) {
                $_mix_return = self::$config;
            } else {
                if (isset(self::$config[$name])) {
                    $_mix_return = self::$config[$name];
                }
            }
        } else if (is_array($_mix_range)) {
            if (Func::isEmpty($name)) {
                if (isset($_mix_range[1])) {
                    if (isset(self::$config[$_mix_range[0]][$_mix_range[1]])) {
                        $_mix_return = self::$config[$_mix_range[0]][$_mix_range[1]];
                    }
                } else if (isset($_mix_range[0])) {
                    if (isset(self::$config[$_mix_range[0]])) {
                        $_mix_return = self::$config[$_mix_range[0]];
                    }
                }
            } else {
                if (isset($_mix_range[1])) {
                    if (isset(self::$config[$_mix_range[0]][$_mix_range[1]][$name])) {
                        $_mix_return = self::$config[$_mix_range[0]][$_mix_range[1]][$name];
                    }
                } else if (isset($_mix_range[0])) {
                    if (isset(self::$config[$_mix_range[0]][$name])) {
                        $_mix_return = self::$config[$_mix_range[0]][$name];
                    }
                }
            }
        } else if (is_string($_mix_range)) {
            if (Func::isEmpty($name)) {
                if (isset(self::$config[$_mix_range])) {
                    $_mix_return = self::$config[$_mix_range];
                }
            } else {
                if (isset(self::$config[$_mix_range][$name])) {
                    $_mix_return = self::$config[$_mix_range][$name];
                }
            }
        }

        //print_r(self::$config);

        return $_mix_return;
    }


    public static function delete($name = '', $range = '') {
        $name = (string)$name;

        if (Func::isEmpty(self::$init)) {
            self::init();
        }

        $_mix_range  = self::rangeProcess($range);

        if (Func::isEmpty($_mix_range)) {
            if (Func::isEmpty($name)) {
                unset(self::$config);
            } else {
                if (isset(self::$config[$name])) {
                    unset(self::$config[$name]);
                }
            }
        } else if (is_array($_mix_range)) {
            if (Func::isEmpty($name)) {
                if (isset($_mix_range[1])) {
                    if (isset(self::$config[$_mix_range[0]][$_mix_range[1]])) {
                        unset(self::$config[$_mix_range[0]][$_mix_range[1]]);
                    }
                } else if (isset($_mix_range[0])) {
                    if (isset(self::$config[$_mix_range[0]])) {
                        unset(self::$config[$_mix_range[0]]);
                    }
                }
            } else {
                if (isset($_mix_range[1])) {
                    if (isset(self::$config[$_mix_range[0]][$_mix_range[1]][$name])) {
                        unset(self::$config[$_mix_range[0]][$_mix_range[1]][$name]);
                    }
                } else if (isset($_mix_range[0])) {
                    if (isset(self::$config[$_mix_range[0]][$name])) {
                        unset(self::$config[$_mix_range[0]][$name]);
                    }
                }
            }
        } else if (is_string($_mix_range)) {
            if (Func::isEmpty($name)) {
                if (isset(self::$config[$_mix_range])) {
                    unset(self::$config[$_mix_range]);
                }
            } else {
                if (isset(self::$config[$_mix_range][$name])) {
                    unset(self::$config[$_mix_range][$name]);
                }
            }
        }
    }

    public static function count() {
        return self::$count;
    }

    public static function load($path, $name = '', $range = '') {
        if (Func::isEmpty(self::$init)) {
            self::init();
        }

        $_arr_config = array();

        if (Func::isFile($path)) {
            $_arr_config = Loader::load($path);
            ++self::$count;
        } else {
            $_obj_excpt = new Exception('Config file not found', 500);
            $_obj_excpt->setData('err_detail', $path);

            throw $_obj_excpt;
        }

        if (is_array($_arr_config) && !Func::isEmpty($_arr_config)) {
            $_arr_config = array_change_key_case($_arr_config);

            self::set($name, $_arr_config, $range);
        }

        return $_arr_config;
    }

    public static function write($path, $value = '') {
        if (is_array($value)) {
            $_str_outPut = '<?php return ' . var_export($value, true) . ';';
        } else if (is_numeric($value)) {
            $_str_outPut = '<?php return ' . $value . ';';
        } else if (is_string($value)) {
            $_str_outPut = '<?php return \'' . $value . '\';';
        }

        return File::instance()->fileWrite($path, $_str_outPut);
    }

    private static function loadSys() {
        $_arr_convention    = Loader::load(GK_PATH_FW . 'convention' . GK_EXT); //配置规范

        $_str_pathBase      = GK_APP_CONFIG . 'config' . GK_EXT_INC;
        $_str_pathDbconfig  = GK_APP_CONFIG . 'dbconfig' . GK_EXT_INC;

        $_arr_config = array();

        if (Func::isFile($_str_pathBase)) {
            $_arr_config = Loader::load($_str_pathBase);
        }

        if (Func::isFile($_str_pathDbconfig)) {
            $_arr_config['dbconfig'] = Loader::load($_str_pathDbconfig);
            ++self::$count;
        }

        if (!Func::isEmpty($_arr_convention)) {
            $_arr_convention = array_change_key_case($_arr_convention);
        }

        if (!Func::isEmpty($_arr_config)) {
            $_arr_config = array_change_key_case($_arr_config);
        }

        self::$config = array_replace_recursive(self::$config, $_arr_convention, $_arr_config);
    }


    private static function rangeProcess($range = '') {
        if (Func::isEmpty($range)) {
            $_str_range = self::$range;
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


