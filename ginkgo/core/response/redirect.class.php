<?php
/*-----------------------------------------------------------------
！！！！警告！！！！
以下为系统文件，请勿修改
-----------------------------------------------------------------*/

namespace ginkgo\response;

use ginkgo\Response;
use ginkgo\Session;
use ginkgo\Route;
use ginkgo\Func;
use ginkgo\Html;

//不能非法包含或直接执行
defined('IN_GINKGO') or exit('Access denied');

class Redirect extends Response {

    protected $param;
    protected $exclude;

    function remember($url = '') {
        if (Func::isEmpty($url)) {
            $_str_url = $this->obj_request->server('REQUEST_URI');
        } else {
            $_str_url = $url;
        }

        Session::set('__redirect__', rawurlencode($this->obj_request->server('REQUEST_URI')));
    }

    function restore() {
        $_str_url = Session::get('__redirect__');
        $_str_url = Html::decode($_str_url, 'url');
        $_str_url = rawurldecode($_str_url);

        Session::delete('__redirect__');

        return $_str_url;
    }

    function param($param, $value = '') {
        if (is_array($param)) {
            $this->param = array_param_recursive($this->param, $param);
        } else {
            $this->param[$param] = $value;
        }
    }

    function exclude($exclude = array()) {
        if (!Func::isEmpty($exclude)) {
            if (is_array($exclude)) {
                if (Func::isEmpty($this->exclude)) {
                    $this->exclude = $exclude;
                } else {
                    $this->exclude = array_merge($this->exclude, $exclude);
                }
            } else if (is_string($exclude)) {
                array_push($this->exclude, $exclude);
            }
        }
    }

    protected function output($data) {
        $_str_jump = $this->getUrl();

        if (!Func::isEmpty($_str_jump)) {
            //$this->cacheControl('no-cache, must-revalidate, no-store, max-age=0');
            $this->setHeader('Location', $_str_jump);
            $this->setStatusCode(302);
        }
    }

    private function getUrl() {
        $_return = '';

        if (is_scalar($this->data)) {
            if (strpos($this->data, '://') || strpos($this->data, '/') === 0) {
                $_return = $this->data;
            } else {
                $_return = Route::build($this->data, $this->param, $this->exclude);
            }
        }

        return $_return;
    }
}


