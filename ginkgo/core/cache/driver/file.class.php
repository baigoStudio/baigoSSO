<?php
/*-----------------------------------------------------------------
！！！！警告！！！！
以下为系统文件，请勿修改
-----------------------------------------------------------------*/

namespace ginkgo\cache\driver;

use ginkgo\Loader;
use ginkgo\Func;
use ginkgo\Config;
use ginkgo\File as File_Base;

//不能非法包含或直接执行
defined('IN_GINKGO') or exit('Access denied');

/*-------------文件操作类-------------*/
class File extends File_Base {

    protected static $instance;

    private $this_config = array(
        'prefix'    => '',
        'life_time' => 1200,
    );

    protected function __construct($config = array()) {
        $_arr_config  = Config::get('cache');

        $this->config = array_replace_recursive($this->this_config, $_arr_config);

        if (!Func::isEmpty($config)) {
            $this->config = array_replace_recursive($this->config, $config);
        }
    }

    protected function __clone() {

    }

    public static function instance() {
        if (Func::isEmpty(static::$instance)) {
            static::$instance = new static();
        }

        return static::$instance;
    }

    public function prefix($prefix = '') {
        if (Func::isEmpty($prefix)) {
            return $this->config['prefix'];
        } else {
            $this->config['prefix'] = $prefix;
        }
    }

    function check($name, $check_expire = false) {
        $_bool_return = false;
        $_str_path    = $this->getPath($name);

        $_bool_return = Func::isFile($_str_path);

        if ($_bool_return && $check_expire) {
            $_arr_content = Loader::load($_str_path);

            if (isset($_arr_content['expire'])) {
                if ($_arr_content['expire'] > 0) {
                    if ($_arr_content['expire'] > GK_NOW) {
                        $_bool_return = true;
                    }
                } else {
                    $_bool_return = true;
                }
            } else {
                $_bool_return = true;
            }
        }

        return $_bool_return;
    }

    function read($name) {
        $_str_path    = $this->getPath($name);

        $_arr_content = Loader::load($_str_path);

        $_mix_return  = '';

        if (isset($_arr_content['expire'])) {
            if ($_arr_content['expire'] > 0) {
                if ($_arr_content['expire'] > GK_NOW) {
                    $_mix_return = $_arr_content['value'];
                } else {
                    $this->delete($name);
                }
            } else {
                $_mix_return = $_arr_content['value'];
            }
        } else {
            $this->delete($name);
        }

        return $_mix_return;
    }

    function write($name, $content, $life_time = 0) {
        $_str_path = $this->getPath($name);

        $_str_content = '';

        if (is_string($content)) {
            $_str_content = '\'' . $content . '\'';
        } else {
            $_str_content = $content;
        }

        if ($life_time > 0) {
            $_tm_expire = GK_NOW + $life_time;
        } else if ($this->config['life_time'] > 0) {
            $_tm_expire = GK_NOW + $this->config['life_time'];
        } else {
            $_tm_expire = 0;
        }

        $_arr_outPut = array(
            'expire' => $_tm_expire,
            'value'  => $content,
        );

        $_str_outPut = '<?php return ' . var_export($_arr_outPut, true) . ';';

        return $this->fileWrite($_str_path, $_str_outPut);
    }

    function delete($name) {
        $_str_path = $this->getPath($name);

        return $this->fileDelete($_str_path);
    }


    private function getPath($name) {
        $_str_path = GK_PATH_CACHE;

        if (isset($this->config['prefix']) && !Func::isEmpty($this->config['prefix'])) {
            $_str_path .= $this->config['prefix'] . DS;
        }

        $_str_path .= $name . GK_EXT;

        return $_str_path;
    }
}