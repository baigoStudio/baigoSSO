<?php
/*-----------------------------------------------------------------
！！！！警告！！！！
以下为系统文件，请勿修改
-----------------------------------------------------------------*/

namespace ginkgo;

//不能非法包含或直接执行
defined('IN_GINKGO') or exit('Access denied');

class View {

    protected static $instance;
    private $obj_engine;
    private $data = array();
    private $replace  = array();

    protected function __construct($engine = '', $config = array()) {
        if (Func::isEmpty($engine)) {
            $_str_engine = Config::get('type', 'tpl');
        } else {
            $_str_engine = $engine;
        }

        $this->engine($_str_engine, $config);

        $this->obj_request  = Request::instance();
    }

    protected function __clone() {

    }

    public static function instance($engine = 'php', $config = array()) {
        if (is_null(static::$instance)) {
            static::$instance = new static($engine, $config);
        }
        return static::$instance;
    }

    public function engine($engine = 'php', $config = array()) {
        if (Func::isEmpty($engine)) {
            $engine = 'php';
        }

        if (strpos($engine, '\\')) {
            $_class = $engine;
        } else {
            $_class = 'ginkgo\\view\\driver\\' . Func::ucwords($engine, '_');
        }

        if (class_exists($_class)) {
            $this->obj_engine = $_class::instance($config);
        } else {
            $_obj_excpt = new Exception('View engine not found', 500);

            $_obj_excpt->setData('err_detail', $_class);

            throw $_obj_excpt;
        }

        return $this;
    }

    function assign($assign, $value = '') {
        if (is_array($assign)) {
            $this->data = array_replace_recursive($this->data, $assign);
        } else {
            $this->data[$assign] = $value;
        }
    }

    function fetch($tpl = '', $assign = '', $value = '', $is_display = false) {
        if (!Func::isEmpty($assign)) {
            $this->assign($assign, $value);
        }

        $_arr_data = $this->data;

        $_arr_data['path_tpl'] = $this->getPath();

        ob_start();
        ob_implicit_flush(0);

        try {
            if ($is_display) {
                $_str_method = 'display';
            } else {
                $_str_method = 'fetch';
            }
            $this->obj_engine->$_str_method($tpl, $_arr_data);
        } catch (\Exception $e) {
            ob_end_clean();
            throw $e;
        }

        $_str_content = ob_get_clean();

        $this->reset();

        return $this->replaceProcess($_str_content);
    }

    function display($content = '', $assign = '', $value = '') {
        return $this->fetch($content, $assign = '', $value, true);
    }

    function has($tpl = '') {
        return $this->obj_engine->has($tpl);
    }

    function setReplace($replace, $value = '') {
        if (is_array($replace)) {
            $this->replace = array_replace_recursive($this->replace, $replace);
        } else {
            $this->replace[$replace] = $value;
        }
    }

    function setPath($pathTpl = '') {
        return $this->obj_engine->setPath($pathTpl);
    }

    function setObj($name, &$obj) {
        $this->obj_engine->setObj($name, $obj);
    }

    function getPath() {
        return $this->obj_engine->getPath();
    }

    function reset() {
        $this->data = array();
    }

    private function replaceProcess($content) {
        $replace = $this->replace;

        if (is_array($replace) && !Func::isEmpty($replace)) {
            $_arr_replace = array_keys($replace);
            foreach ($_arr_replace as $_key=>&$_value) {
                $_value = '{:' . $_value . '}';
            }
            $content = str_ireplace($_arr_replace, $replace, $content);
        }

        $_str_urlBase       = Func::fixDs($this->obj_request->baseUrl(true), '/');
        $_str_urlRoot       = Func::fixDs($this->obj_request->root(true), '/');
        $_str_dirRoot       = Func::fixDs($this->obj_request->root(), '/');
        $_str_routeRoot     = Func::fixDs($this->obj_request->baseUrl(), '/');

        $_str_routePage     = Route::build();

        /*print_r($_str_routePage);
        print_r('<br>');*/

        $_arr_replaceSrc = array(
            '{:URL_BASE}',
            '{:URL_ROOT}',
            '{:DIR_ROOT}',
            '{:DIR_STATIC}',
            '{:ROUTE_ROOT}',
            '{:ROUTE_PAGE}',
        );

        $_arr_replaceDst = array(
            $_str_urlBase,
            $_str_urlRoot,
            $_str_dirRoot,
            $_str_dirRoot . GK_NAME_STATIC . '/',
            $_str_routeRoot,
            $_str_routePage,
        );

        $content = str_ireplace($_arr_replaceSrc, $_arr_replaceDst, $content);

        $_mix_result = Plugin::listen('filter_fw_view', $content); //模板输出时过滤
        $content     = Plugin::resultProcess($content, $_mix_result);

        return $content;
    }
}


