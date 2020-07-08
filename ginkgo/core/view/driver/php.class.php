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

// 不能非法包含或直接执行
defined('IN_GINKGO') or exit('Access denied');

// 视图驱动类 (php 原生模板引擎)
class Php {

    protected static $instance; // 当前实例
    private $suffix = GK_EXT_TPL; // 默认模板文件后缀
    private $config; // 配置
    private $route; // 路由
    private $param; // 参数

    protected $obj_request; // 请求实例

    private $pathTpl; // 模板路径

    public $lang; // 语言实例
    public $obj; // 对象

    /** 构造函数
     * __construct function.
     *
     * @access protected
     * @param array $config (default: array()) 配置
     * @return void
     */
    protected function __construct($config = array()) {
        $this->obj_request      = Request::instance();

        $this->obj['lang']      = Lang::instance();
        $this->obj['request']   = $this->obj_request;

        $this->route            = $this->obj_request->route;
        $this->routeOrig        = $this->obj_request->routeOrig;
        $this->param            = $this->obj_request->param;

        $this->config           = Config::get('tpl');

        $this->setPath(); // 设置路径
    }

    /**
     * instance function.
     *
     * @access public
     * @static
     * @param array $config (default: array()) 配置
     * @return void
     */
    public static function instance($config = array()) {
        if (Func::isEmpty(static::$instance)) {
            static::$instance = new static($config);
        }
        return static::$instance;
    }


    /** 渲染文件模板
     * fetch function.
     *
     * @access public
     * @param string $tpl (default: '') 模板名
     * @param string $data (default: '') 内容
     * @return 渲染结果
     */
    function fetch($tpl = '', $data = '') {
        $_str_tpl = $this->pathProcess($tpl); // 路径处理

        if (!Func::isFile($_str_tpl)) {
            $_obj_excpt = new Exception('Template not found', 500); // 报错
            $_obj_excpt->setData('err_detail', $_str_tpl);

            throw $_obj_excpt;
        }

        //$this->data = $data;

        if (!Func::isEmpty($data)) {
            extract($data, EXTR_OVERWRITE); // 拆分为模板变量
        }

        if (!Func::isEmpty($this->obj)) {
            extract($this->obj, EXTR_OVERWRITE); // 拆分为模板变量
        }

        require($_str_tpl);
    }


    /** 渲染内容模板
     * display function.
     *
     * @access public
     * @param string $content (default: '')
     * @param string $data (default: '')
     * @return void
     */
    function display($content = '', $data = '') {

        if (!Func::isEmpty($data)) {
            extract($data, EXTR_OVERWRITE);
        }

        eval('?>' . $content);
    }

    /** 模板是否存在
     * has function.
     *
     * @access public
     * @param string $tpl (default: '') 模板名
     * @return bool
     */
    function has($tpl) {
        $_str_tpl = $this->pathProcess($tpl);

        //print_r($_str_tpl);

        return Func::isFile($_str_tpl);
    }


    /** 设置模板目录路径
     * setPath function.
     *
     * @access public
     * @param string $pathTpl (default: '') 路径
     * @return void
     */
    function setPath($pathTpl = '') {
        if (Func::isEmpty($pathTpl)) { // 如果参数为空, 则自动定位
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
            $_str_pathTpl   = Func::fixDs($pathTpl); // 否则直接使用
        }

        $this->pathTpl  = $_str_pathTpl;

        return $_str_pathTpl;
    }


    /** 设置对象
     * setObj function.
     *
     * @access public
     * @param mixed $name
     * @param mixed &$obj
     * @return void
     */
    function setObj($name, &$obj) {
        $this->obj[$name] = $obj;
    }

    /** 取得模板路径
     * getPath function.
     *
     * @access public
     * @return void
     */
    function getPath() {
        return $this->pathTpl;
    }


    /** 路径处理
     * pathProcess function.
     *
     * @access private
     * @param string $tpl (default: '')
     * @return void
     */
    private function pathProcess($tpl = '') {
        $_str_tpl = $this->pathTpl;

        if (!Func::isEmpty($this->config['suffix'])) { // 如果定义了后缀名
            $this->suffix = $this->config['suffix']; // 替换默认后缀名
        }

        $_str_act = Func::toLine($this->route['act']); // 转为文件名

        if (Func::isEmpty($tpl)) {
            $_str_tpl .= $this->route['ctrl'] . DS . $_str_act; // 如果未定义模板参数, 则自动定位
        } else {
            $tpl = str_replace(array('/', '\\'), DS, $tpl); // 分拆模板参数

            if (stristr($tpl, $this->suffix)) { // 如果定义了后缀, 则认为是完整路径, 直接使用
                $_str_tpl = $tpl;
            } else if (strpos($tpl, DS) !== false) { // 如果定义了目录分隔符, 则认为是 控制器/模板 形式
                $_str_tpl .= $tpl;
            } else { // 否则补全为 当前控制器/模板
                $_str_tpl .= $this->route['ctrl'] . DS . $tpl;
            }
        }

        $_str_tplPath = $_str_tpl . $this->suffix;
        $_str_tplPath = str_replace(array('/', '\\'), DS, $_str_tplPath);

        return str_replace('-', '_', $_str_tplPath);
    }
}


