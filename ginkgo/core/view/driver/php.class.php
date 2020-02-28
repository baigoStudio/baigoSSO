<?php
/*-----------------------------------------------------------------
！！！！警告！！！！
以下为系统文件，请勿修改
-----------------------------------------------------------------*/

namespace ginkgo\view\driver;

use ginkgo\Response;
use ginkgo\Request;
use ginkgo\Config;
use ginkgo\Func;
use ginkgo\Exception;
use ginkgo\Lang;
use ginkgo\Plugin;

//不能非法包含或直接执行
defined('IN_GINKGO') or exit('Access denied');

class Php {

    protected static $instance;
    private $suffix = GK_EXT_TPL; // 默认模板文件后缀
    private $config;
    private $route;
    private $param;

    protected $obj_request;

    private $pathTpl;

    public $lang;
    public $plugin;
    public $obj;

    protected function __construct($config = array()) {
        $this->obj_request      = Request::instance();

        $this->obj['lang']      = Lang::instance();

        $this->route            = $this->obj_request->route;
        $this->routeOrig        = $this->obj_request->routeOrig;
        $this->param            = $this->obj_request->param;

        $this->config           = Config::get('tpl');

        $this->setPath();
    }

    public static function instance($config = array()) {
        if (Func::isEmpty(static::$instance)) {
            static::$instance = new static($config);
        }
        return static::$instance;
    }


    function fetch($tpl = '', $data = '') {
        $_str_tpl = $this->pathProcess($tpl);

        if (!Func::isFile($_str_tpl)) {
            $_obj_excpt = new Exception('Template not found', 500);
            $_obj_excpt->setData('err_detail', $_str_tpl);

            throw $_obj_excpt;
        }

        //$this->data = $data;

        if (!Func::isEmpty($data)) {
            extract($data, EXTR_OVERWRITE);
        }

        if (!Func::isEmpty($this->obj)) {
            extract($this->obj, EXTR_OVERWRITE);
        }

        require($_str_tpl);
    }


    function display($content = '', $data = '') {

        if (!Func::isEmpty($data)) {
            extract($data, EXTR_OVERWRITE);
        }

        eval('?>' . $content);
    }

    function has($tpl) {
        $_str_tpl = $this->pathProcess($tpl);

        //print_r($_str_tpl);

        return Func::isFile($_str_tpl);
    }

    function setPath($pathTpl = '') {
        if (Func::isEmpty($pathTpl)) {
            $_str_pathTpl   = GK_APP_TPL . $this->route['mod'] . DS;

            if (!Func::isEmpty($this->config['path'])) {
                $_str_path = str_replace(array('/', '\\'), DS, $this->config['path']);

                if (strpos($_str_path, DS) !== false) {
                    $_str_pathTpl = Func::fixDs($_str_path);
                } else {
                    $_str_pathTpl .= Func::fixDs($_str_path);
                }
            }
        } else {
            $_str_pathTpl   = Func::fixDs($pathTpl);
        }

        $this->pathTpl  = $_str_pathTpl;

        return $_str_pathTpl;
    }


    function setObj($name, &$obj) {
        $this->obj[$name] = $obj;
    }

    function getPath() {
        return $this->pathTpl;
    }


    private function pathProcess($tpl = '') {
        $_str_tpl = $this->pathTpl;

        if (!Func::isEmpty($this->config['suffix'])) {
            $this->suffix = $this->config['suffix'];
        }

        $_str_act = Func::toLine($this->route['act']);

        if (Func::isEmpty($tpl)) {
            $_str_tpl .= $this->route['ctrl'] . DS . $_str_act;
        } else {
            $tpl = str_replace(array('/', '\\'), DS, $tpl);

            if (stristr($tpl, $this->suffix)) {
                $_str_tpl = $tpl;
            } else if (strpos($tpl, DS) !== false) {
                $_str_tpl .= $tpl;
            } else {
                $_str_tpl .= $this->route['ctrl'] . DS . $tpl;
            }
        }

        $_str_tplPath = $_str_tpl . $this->suffix;
        $_str_tplPath = str_replace(array('/', '\\'), DS, $_str_tplPath);

        return str_replace('-', '_', $_str_tplPath);
    }
}


