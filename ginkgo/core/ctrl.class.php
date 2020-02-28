<?php
/*-----------------------------------------------------------------
！！！！警告！！！！
以下为系统文件，请勿修改
-----------------------------------------------------------------*/

namespace ginkgo;

//不能非法包含或直接执行
defined('IN_GINKGO') or exit('Access denied');

abstract class Ctrl {

    protected $obj_request;
    protected $obj_lang;
    protected $obj_view;
    protected $route        = array();
    protected $routeOrig    = array();
    protected $param        = array();

    function __construct($param = array()) {
        $this->obj_request  = Request::instance();
        $this->obj_lang     = Lang::instance();
        $this->obj_view     = View::instance();
        $this->route        = $this->obj_request->route;
        $this->routeOrig    = $this->obj_request->routeOrig;
        $this->param        = $param;

        // 控制器初始化
        $this->c_init($param);
    }

    protected function c_init($param = array()) { }

    protected function assign($assign, $value = '') {
        $this->obj_view->assign($assign, $value);
    }

    protected function fetch($tpl = '', $assign = '', $value = '', $code = 200) {
        $_str_return = $this->obj_view->fetch($tpl, $assign, $value);

        return Response::create($_str_return, 'html', $code = 200);
    }

    protected function display($content = '', $assign = '', $value = '', $code = 200) {
        $_str_return = $this->obj_view->display($content, $assign, $value);

        return Response::create($_str_return, 'html', $code = 200);
    }

    protected function redirect($url = '') {
        return Response::create($url, 'redirect', 302);
    }

    protected function json($content, $code = 200) {
        return Response::create($content, 'json', $code = 200);
    }

    protected function jsonp($content, $code = 200) {
        return Response::create($content, 'jsonp', $code = 200);
    }

    protected function engine($engine) {
        $this->obj_view->engine($engine);
    }

    protected function reset() {
        $this->obj_view->reset();
    }

    protected function setObj($name, &$obj) {
        $this->obj_view->setObj($name, $obj);
    }

    protected function validate($data, $validate = '', $scene = '', $only = array(), $remove = array(), $append = array()) {
        if (Func::isEmpty($validate)) {
            $validate   = $this->route['ctrl'];
            $_vld       = Loader::validate($validate);

            if ($scene !== false) {
                if (Func::isEmpty($scene)) {
                    $_str_scene = $this->route['act'];
                } else {
                    $_str_scene = $scene;
                }
                $_vld->scene($_str_scene);
            }
        } else {
            if (is_array($validate)) {
                $_vld = Validate::instance();
                $_vld->rule($validate);
            } else if (is_string($validate)) {
                $_vld = Loader::validate($validate);

                if ($scene !== false) {
                    if (Func::isEmpty($scene)) {
                        $_str_scene = $this->route['act'];
                    } else {
                        $_str_scene = $scene;
                    }
                    $_vld->scene($_str_scene);
                }
            }
        }

        if (!Func::isEmpty($only)) {
            $_vld->only($only);
        }

        if (!Func::isEmpty($remove)) {
            $_vld->remove($remove);
        }

        if (!Func::isEmpty($append)) {
            $_vld->append($append);
        }

        if ($_vld->verify($data)) {
            $_mix_return = true;
        } else {
            $_mix_return = $_vld->getMessage();
        }

        return $_mix_return;
    }
}
