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

// 不能非法包含或直接执行
if (!defined('IN_GINKGO')) {
    return 'Access denied';
}

// 重定向响应类
class Redirect extends Response {

    public $param; // 参数
    public $exclude; // 排除参数

    /** 记住 url
     * remember function.
     *
     * @access public
     * @param string $url (default: '')
     * @return void
     */
    public function remember($url = '') {
        if (Func::isEmpty($url)) {
            $url = $this->obj_request->server('REQUEST_URI'); // 未指定参数则取服务器变量
        }

        Session::set('__redirect__', rawurlencode($url)); // 设置会话
    }

    /** 读取 url
     * restore function.
     *
     * @access public
     * @return void
     */
    public function restore() {
        $_str_url = Session::get('__redirect__'); // 从会话读取
        $_str_url = Html::decode($_str_url, 'url');
        $_str_url = rawurldecode($_str_url);

        Session::delete('__redirect__'); // 删除会话

        return $_str_url; // 返回
    }

    /** 设置参数
     * param function.
     *
     * @access public
     * @param mixed $param
     * @param string $value (default: '')
     * @return void
     */
    public function param($param, $value = '') {
        if (is_array($param)) {
            $this->param = array_param_recursive($this->param, $param);
        } else {
            $this->param[$param] = $value;
        }
    }

    /** 排除参数
     * exclude function.
     *
     * @access public
     * @param array $exclude (default: array())
     * @return void
     */
    public function exclude($exclude) {
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

    /** 输出处理
     * output function.
     *
     * @access protected
     * @param mixed $data
     * @return void
     */
    protected function output($data) {
        $_str_jump = $this->getUrl(); // 取得 url

        if (!Func::isEmpty($_str_jump)) {
            //$this->cacheControl('no-cache, must-revalidate, no-store, max-age=0');
            $this->setHeader('Location', $_str_jump);
            $this->setStatusCode(302);
        }
    }

    /** 取得 url
     * getUrl function.
     *
     * @access private
     * @return void
     */
    private function getUrl() {
        $_return = '';

        if (is_scalar($this->data)) {
            if (strpos($this->data, '://') || strpos($this->data, '/') === 0) {
                $_return = $this->data; // 如果响应内容存在, 切符合跳转 url 规则, 则直接使用
            } else { // 否则构建 url
                $_return = Route::build($this->data, $this->param, $this->exclude);
            }
        }

        return $_return;
    }
}
