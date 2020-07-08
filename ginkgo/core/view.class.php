<?php
/*-----------------------------------------------------------------
！！！！警告！！！！
以下为系统文件，请勿修改
-----------------------------------------------------------------*/

namespace ginkgo;

// 不能非法包含或直接执行
defined('IN_GINKGO') or exit('Access denied');

// 视图类
class View {

    protected static $instance; // 当前实例
    private $obj_engine; // 引擎实例
    private $data = array(); // 内容
    private $replace  = array(); // 输出替换

    /** 构造函数
     * __construct function.
     *
     * @access protected
     * @param string $engine (default: '') 模板引擎名
     * @param array $config (default: array()) 配置
     * @return void
     */
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

    /** 实例化
     * instance function.
     *
     * @access public
     * @static
     * @param string $engine (default: 'php') 模板引擎名
     * @param array $config (default: array()) 配置
     * @return 当前类的实例
     */
    public static function instance($engine = 'php', $config = array()) {
        if (is_null(static::$instance)) {
            static::$instance = new static($engine, $config);
        }
        return static::$instance;
    }


    /** 设置模板引擎并实例化
     * engine function.
     *
     * @access public
     * @param string $engine (default: 'php') 引擎类型
     * @param array $config (default: array()) 配置
     * @return 当前实例
     */
    public function engine($engine = 'php', $config = array()) {
        if (Func::isEmpty($engine)) {
            $engine = 'php'; // 默认引擎
        }

        if (strpos($engine, '\\')) { // 如果引擎类型指定了命名空间, 则直接使用
            $_class = $engine;
        } else {
            $_class = 'ginkgo\\view\\driver\\' . Func::ucwords($engine, '_'); // 补全命名空间
        }

        if (class_exists($_class)) {
            $this->obj_engine = $_class::instance($config); // 实例化
        } else {
            $_obj_excpt = new Exception('View engine not found', 500); // 报错

            $_obj_excpt->setData('err_detail', $_class);

            throw $_obj_excpt;
        }

        return $this;
    }


    /** 模板变量赋值
     * assign function.
     *
     * @access public
     * @param mixed $assign 变量
     * @param string $value (default: '') 值
     * @return void
     */
    function assign($assign, $value = '') {
        if (is_array($assign)) {
            $this->data = array_replace_recursive($this->data, $assign);
        } else {
            $this->data[$assign] = $value;
        }
    }

    /** 渲染
     * fetch function.
     *
     * @access public
     * @param string $tpl (default: '') 模板
     * @param string $assign (default: '') 变量
     * @param string $value (default: '') 值
     * @param bool $is_display (default: false) 是否为渲染内容
     * @return void
     */
    function fetch($tpl = '', $assign = '', $value = '', $is_display = false) {
        if (!Func::isEmpty($assign)) {
            $this->assign($assign, $value); // 赋值
        }

        $_arr_data = $this->data; // 内容

        $_arr_data['path_tpl'] = $this->getPath(); // 取得模板路径

        ob_start(); // 打开缓冲
        ob_implicit_flush(0); // 关闭绝对刷送

        try {
            if ($is_display) {
                $_str_method = 'display'; // 直接以字符串作为模板
            } else {
                $_str_method = 'fetch'; // 渲染模板文件
            }
            $this->obj_engine->$_str_method($tpl, $_arr_data); // 调用引擎方法
        } catch (\Exception $e) {
            ob_end_clean();
            throw $e;
        }

        $_str_content = ob_get_clean(); // 取得输出缓冲内容并清理关闭

        $this->reset(); // 重置内容

        return $this->replaceProcess($_str_content); // 输出替换
    }

    /** 直接以字符串作为模板
     * display function.
     *
     * @access public
     * @param string $content (default: '') 模板内容
     * @param string $assign (default: '') 变量
     * @param string $value (default: '') 值
     * @return void
     */
    function display($content = '', $assign = '', $value = '') {
        return $this->fetch($content, $assign = '', $value, true);
    }

    /** 模板是否存在
     * has function.
     *
     * @access public
     * @param string $tpl (default: '') 模板名
     * @return bool
     */
    function has($tpl = '') {
        return $this->obj_engine->has($tpl);
    }

    /** 设置输出替换
     * setReplace function.
     *
     * @access public
     * @param mixed $replace 查找
     * @param string $value (default: '') 替换
     * @return void
     */
    function setReplace($replace, $value = '') {
        if (is_array($replace)) {
            $this->replace = array_replace_recursive($this->replace, $replace);
        } else {
            $this->replace[$replace] = $value;
        }
    }

    /** 设置模板目录
     * setPath function.
     *
     * @access public
     * @param string $pathTpl (default: '')
     * @return void
     */
    function setPath($pathTpl = '') {
        return $this->obj_engine->setPath($pathTpl);
    }

    /** 设置对象
     * setObj function.
     *
     * @access public
     * @param mixed $name 对象名
     * @param mixed &$obj 对象映射
     * @return void
     */
    function setObj($name, &$obj) {
        $this->obj_engine->setObj($name, $obj);
    }

    /** 取得模板目录
     * getPath function.
     *
     * @access public
     * @return void
     */
    function getPath() {
        return $this->obj_engine->getPath();
    }

    /** 清空内容
     * reset function.
     *
     * @access public
     * @return void
     */
    function reset() {
        $this->data = array();
    }

    /** 输出替换处理
     * replaceProcess function.
     *
     * @access private
     * @param mixed $content 内容
     * @return void
     */
    private function replaceProcess($content) {
        $replace = $this->replace;

        if (is_array($replace) && !Func::isEmpty($replace)) {
            $_arr_replace = array_keys($replace);
            foreach ($_arr_replace as $_key=>&$_value) {
                $_value = '{:' . $_value . '}';
            }
            $content = str_ireplace($_arr_replace, $replace, $content);
        }

        // 路径处理
        $_str_urlBase       = $this->obj_request->baseUrl(true);
        $_str_urlRoot       = $this->obj_request->root(true);
        $_str_dirRoot       = $this->obj_request->root();
        $_str_routeRoot     = $this->obj_request->baseUrl();

        $_str_routePage     = Route::build();

        /*print_r($_str_routePage);
        print_r('<br>');*/

        // 模板中的替换处理
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


