<?php
/*-----------------------------------------------------------------
！！！！警告！！！！
以下为系统文件，请勿修改
-----------------------------------------------------------------*/

namespace ginkgo;

// 不能非法包含或直接执行
defined('IN_GINKGO') or exit('Access denied');

// 控制器抽象类
abstract class Ctrl {

    protected $obj_request; // 请求实例
    protected $obj_lang; // 语言实例
    protected $obj_view; // 视图实例
    protected $route        = array(); // 路由
    protected $routeOrig    = array(); // 原始路由
    protected $param        = array(); // 路由参数

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


    /** 向视图赋值
     * assign function.
     *
     * @access protected
     * @param mixed $assign 变量名或值
     * @param string $value (default: '') 变量值
     * @return void
     */
    protected function assign($assign, $value = '') {
        $this->obj_view->assign($assign, $value); // 用视图实例赋值
    }

    /** 渲染模板输出
     * fetch function.
     *
     * @access protected
     * @param string $tpl (default: '') 模板名
     * @param string $assign (default: '') 待赋值的变量名或值
     * @param string $value (default: '') 待赋值的变量值
     * @param int $code (default: 200) http 状态码
     * @return 响应实例
     */
    protected function fetch($tpl = '', $assign = '', $value = '', $code = 200) {
        $_str_return = $this->obj_view->fetch($tpl, $assign, $value); // 用视图实例渲染

        return Response::create($_str_return, 'html', $code = 200); // 用渲染得到的内容实例化响应类
    }

    /** 显示模板输出
     * display function.
     *
     * @access protected
     * @param string $content (default: '') 模板内容 (非模板文件)
     * @param string $assign (default: '') 待赋值的变量名或值
     * @param string $value (default: '') 待赋值的变量值
     * @param int $code (default: 200) http 状态码
     * @return 响应实例
     */
    protected function display($content = '', $assign = '', $value = '', $code = 200) {
        $_str_return = $this->obj_view->display($content, $assign, $value);

        return Response::create($_str_return, 'html', $code = 200);
    }

    /** 重定向输出
     * redirect function.
     *
     * @access protected
     * @param string $url (default: '') 重定向地址
     * @return 响应实例
     */
    protected function redirect($url = '') {
        return Response::create($url, 'redirect', 302); // 用重定向地址实例化响应类
    }

    /** json 输出
     * json function.
     *
     * @access protected
     * @param mixed $content json 内容
     * @param int $code (default: 200) http 状态码
     * @return 响应实例
     */
    protected function json($content, $code = 200) {
        return Response::create($content, 'json', $code = 200); // 用 json 内容实例化响应类
    }

    /** jsonp 输出
     * json function.
     *
     * @access protected
     * @param mixed $content jsonp 内容
     * @param int $code (default: 200) http 状态码
     * @return 响应实例
     */
    protected function jsonp($content, $code = 200) {
        return Response::create($content, 'jsonp', $code = 200); // 用 jsonp 内容实例化响应类
    }

    /** 初始化视图驱动
     * engine function.
     *
     * @access protected
     * @param string $engine 驱动
     * @return void
     */
    protected function engine($engine) {
        $this->obj_view->engine($engine);
    }

    // 清空变量
    protected function reset() {
        $this->obj_view->reset();
    }

    // 设置模板对象
    protected function setObj($name, &$obj) {
        $this->obj_view->setObj($name, $obj);
    }


    /** 调用验证器
     * validate function.
     *
     * @access protected
     * @param mixed $data 待验证数据
     * @param string $validate (default: '') 验证器名称
     * @param string $scene (default: '') 验证场景
     * @param array $only (default: array()) 仅验证指定规则
     * @param array $remove (default: array()) 移除指定规则
     * @param array $append (default: array()) 追加验证规则
     * @return 验证结果
     */
    protected function validate($data, $validate = '', $scene = '', $only = array(), $remove = array(), $append = array()) {
        if (Func::isEmpty($validate)) { // 假如验证规则未定义, 则依据控制器名指定
            $validate   = $this->route['ctrl']; // 取得当前控制器名
            $_vld       = Loader::validate($validate); // 载入控制器

            if ($scene !== false) { // 只有场景定义为 false 时才忽略场景
                if (Func::isEmpty($scene)) { // 如果未指定场景, 则依据动作名指定
                    $_str_scene = $this->route['act'];
                } else {
                    $_str_scene = $scene;
                }
                $_vld->scene($_str_scene); // 指定场景
            }
        } else {
            if (is_array($validate)) { // 假如验证规则为数组, 则用该规则初始化验证类
                $_vld = Validate::instance();
                $_vld->rule($validate); // 设置验证规则
            } else if (is_string($validate)) { // 假如验证规则为字符串, 则载入对应验证器
                $_vld = Loader::validate($validate);

                if ($scene !== false) { // 只有场景定义为 false 时才忽略场景
                    if (Func::isEmpty($scene)) {
                        $_str_scene = $this->route['act']; // 如果未指定场景, 则依据动作名指定
                    } else {
                        $_str_scene = $scene;
                    }
                    $_vld->scene($_str_scene); // 指定场景
                }
            }
        }

        if (!Func::isEmpty($only)) {
            $_vld->only($only); // 仅验证指定规则
        }

        if (!Func::isEmpty($remove)) {
            $_vld->remove($remove); // 移除指定规则
        }

        if (!Func::isEmpty($append)) {
            $_vld->append($append); // 追加验证规则
        }

        if ($_vld->verify($data)) {
            $_mix_return = true; // 验证成功
        } else {
            $_mix_return = $_vld->getMessage(); // 验证失败, 取得消息
        }

        return $_mix_return;
    }
}
