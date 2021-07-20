<?php
/*-----------------------------------------------------------------
！！！！警告！！！！
以下为系统文件，请勿修改
-----------------------------------------------------------------*/

namespace ginkgo;

// 不能非法包含或直接执行
if (!defined('IN_GINKGO')) {
    return 'Access denied';
}

// 错误处理
class Error {

    public static $config = array(); // 错误配置

    // 错误类型
    private static $errType = array(
        E_ERROR              => 'Error - E_ERROR',
        E_CORE_ERROR         => 'Core Error - E_CORE_ERROR',
        E_COMPILE_ERROR      => 'Compile Error - E_COMPILE_ERROR',
        E_USER_ERROR         => 'User Error - E_USER_ERROR',
        E_RECOVERABLE_ERROR  => 'Catchable Fatal Error - E_RECOVERABLE_ERROR',

        E_PARSE              => 'Parsing Error - E_PARSE',

        E_WARNING            => 'Warning - E_WARNING',
        E_CORE_WARNING       => 'Core Warning - E_CORE_WARNING',
        E_COMPILE_WARNING    => 'Compile Warning - E_COMPILE_WARNING',
        E_USER_WARNING       => 'User Warning - E_USER_WARNING',

        E_NOTICE             => 'Notice - E_NOTICE',
        E_USER_NOTICE        => 'User Notice - E_USER_NOTICE',

        //E_STRICT           => 'Runtime Notice - E_STRICT',
    );

    // 致命错误类型
    private static $errFatal = array(
        E_ERROR,
        E_PARSE,
        E_CORE_ERROR,
        E_COMPILE_ERROR,
        E_RECOVERABLE_ERROR,
        //E_STRICT,
    );

    private static $optDebugDump = false; // 调试配置
    private static $uncatchable; // 无法捕获的错误

    // 默认配置
    private static $configThis = array(
        'dump'  => false, //输出调试信息 false 关闭, trace 输出 Trace
        'tag'   => 'div', //调试信息包含在标签内
        'class' => 'container p-5', //调试信息包含标签的 css 类名
    );


    /** 注册错误处理方法
     * appError function.
     *
     * @access public
     * @static
     * @param mixed $config 配置 since 0.2.0
     * @return void
     */
    public static function register($config = false) {
        self::config($config);

        if (self::$config['dump']) { // 假如配置为输出
            self::$optDebugDump = true;
        }

        error_reporting(0); // 禁用系统报错
        libxml_use_internal_errors(true); // 禁止 html xml 解析报错
        set_error_handler(array(__CLASS__, 'appError')); // 注册错误处理方法
        set_exception_handler(array(__CLASS__, 'appException')); // 注册异常处理方法
        register_shutdown_function(array(__CLASS__, 'appShutdown')); // 注册关闭处理方法
    }


    // since 0.2.0
    public static function config($config = false) {
        $_mix_config   = Config::get('debug'); // 取得调试配置

        $_arr_configDo = self::$configThis;

        if (!Func::isEmpty($_mix_config)) {
            $_arr_config   = self::configProcess($_mix_config);

            $_arr_configDo = array_replace_recursive($_arr_configDo, $_arr_config);
        }

        if (!Func::isEmpty(self::$config)) {
            $_arr_config   = self::configProcess(self::$config);

            $_arr_configDo = array_replace_recursive($_arr_configDo, $_arr_config);
        }

        if (!Func::isEmpty($config)) {
            $_arr_config   = self::configProcess($config);

            $_arr_configDo = array_replace_recursive($_arr_configDo, $_arr_config);
        }

        self::$config  = $_arr_configDo;
    }


    /** 错误处理
     * appError function.
     *
     * @access public
     * @static
     * @param int $err_no 错误号
     * @param string $err_msg 错误消息
     * @param string $err_file 出错文件
     * @param int $err_line 出错行号
     * @return void
     */
    public static function appError($err_no, $err_msg, $err_file, $err_line) {
        $_str_errType   = 'Unknown error'; // 默认消息

        //print_r($err_msg);

        if (isset(self::$errType[$err_no])) { // 设置错误类型
            $_str_errType = self::$errType[$err_no];
        }

        $_arr_error = array(
            'type'          => $err_no,
            'err_type'      => $_str_errType,
            'err_message'   => $err_msg,
        );

        if (self::$optDebugDump === true) { // 假如配置为输出
            $_arr_error['err_file'] = $err_file;
            $_arr_error['err_line'] = $err_line;
        }

        $_str_key = md5($err_no . $err_msg . $err_file . $err_line); // 生成错误号 (避免冲突)

        if (self::isFatal($err_no)) { // 如果是致命错误, 则直接报错
            self::sendErr($_arr_error);
        } else { // 否则只记录错误
            if (class_exists('ginkgo\Debug')) {
                Debug::record($_str_key, $_arr_error);
            } else {
                self::$uncatchable[] = $_arr_error;
            }

            if (class_exists('ginkgo\Log')) {
                $_arr_error['err_file'] = $err_file;
                $_arr_error['err_line'] = $err_line;
                Log::record($_arr_error, 'error');
            }
        }
    }


    /** 异常处理
     * appException function.
     *
     * @access public
     * @static
     * @param object $excpt 异常实例
     * @return void
     */
    public static function appException($excpt) {
        $_str_type      = $excpt->getCode(); // 取得错误号
        $_str_errType   = 'Unknown error';

        if (isset(self::$errType[$_str_type])) { // 设置错误类型
            $_str_errType = self::$errType[$_str_type];
        }

        $_arr_error['err_level']    = 'Framework error';
        $_arr_error['err_type']     = $_str_errType;
        $_arr_error['status_code']  = 500;

        if (method_exists($excpt, 'getStatusCode')) {
            $_arr_error['status_code']  = $excpt->getStatusCode(); // 取得 http 状态码
        }

        if (method_exists($excpt, 'getData')) { // 取得错误详情
            $err_detail = $excpt->getData('err_detail');
        } else {
            $err_detail = '';
        }

        $err_message    = $excpt->getMessage(); // 错误消息
        $err_file       = $excpt->getFile(); // 出错文件
        $err_line       = $excpt->getLine(); // 出错行号

        if (self::$optDebugDump === true) {
            $_arr_error['err_message']  = $err_message;
            $_arr_error['err_file']     = $err_file;
            $_arr_error['err_line']     = $err_line;
            $_arr_error['err_detail']   = $err_detail;
        }

        //print_r(self::$optDebugDump);

        unset($excpt); //销毁异常实例

        // 记录日志
        if (class_exists('ginkgo\Log')) {
            $_arr_errorRecord = $_arr_error;

            $_arr_errorRecord['err_message']  = $err_message;
            $_arr_errorRecord['err_file']     = $err_file;
            $_arr_errorRecord['err_line']     = $err_line;
            $_arr_errorRecord['err_detail']   = $err_detail;

            Log::record($_arr_errorRecord, 'excpt'); // 记录日志
        }

        self::sendErr($_arr_error); // 输出报错信息
    }


    /** 程序关闭处理
     * appShutdown function.
     *
     * @access public
     * @static
     * @return void
     */
    public static function appShutdown() {
        $_error_last = error_get_last(); // 取得最后一个错误

        //print_r($_error_last);

        if (!Func::isEmpty($_error_last)) { // 假如有错误, 则处理
            if (self::isFatal($_error_last['type'])) { // 仅处理致命错误
                $_obj_except = new Exception($_error_last['message'], 500, $_error_last['type'], $_error_last['file'], $_error_last['line']);

                self::appException($_obj_except);
            }

            Log::save(); // 写入日志
        }

        if (!Func::isEmpty(self::$uncatchable)) {
            echo self::dump(self::$uncatchable);
        }

        //print_r((memory_get_usage() - GK_START_MEM) / 1024 / 1024);
    }


    /** 渲染错误模板
     * fetch function.
     *
     * @access public
     * @static
     * @param string $tpl (default: '') 模板名称
     * @param array $data (default: array()) 渲染内容
     * @return void
     */
    public static function fetch($tpl = '', $data = array()) {
        $_obj_request  = Request::instance();
        $_obj_lang     = Lang::instance();
        $_obj_lang->range('__ginkgo__');  //设置语言作用域

        $_arr_obj['lang']    = $_obj_lang;
        $_arr_obj['request'] = $_obj_request;

        $_str_tpl = self::pathProcess($tpl);

        if (self::$optDebugDump === true) {
            if (!File::fileHas($_str_tpl)) {
                return '<pre>' . var_export($data, true) . '</pre>';
            }

            if (!Func::isEmpty($data)) {
                extract($data, EXTR_OVERWRITE); // 将内容数组转换为模板变量
            }
        } else {
            return '<div>' . $data['http_status'] . '</div>';
        }

        $_str_content = '';

        ob_start(); // 打开缓冲
        ob_implicit_flush(0); // 关闭绝对刷送

        if (!Func::isEmpty($_arr_obj)) {
            extract($_arr_obj, EXTR_OVERWRITE); // 将对象数组转换为模板变量
        }

        require($_str_tpl); // 载入模板文件

        $_str_content = ob_get_clean(); // 取得输出缓冲内容并清理关闭

        // 路径处理
        $_str_urlBase       = $_obj_request->baseUrl(true);
        $_str_urlRoot       = $_obj_request->root(true);
        $_str_dirRoot       = $_obj_request->root();
        $_str_routeRoot     = $_obj_request->baseUrl();

        // 模板中的替换处理
        $_arr_replaceSrc = array(
            '{:URL_BASE}',
            '{:URL_ROOT}',
            '{:DIR_ROOT}',
            '{:DIR_STATIC}',
            '{:ROUTE_ROOT}',
            '{:PHP_VERSION}',
        );

        $_arr_replaceDst = array(
            $_str_urlBase,
            $_str_urlRoot,
            $_str_dirRoot,
            $_str_dirRoot . GK_NAME_STATIC . '/',
            $_str_routeRoot,
            PHP_VERSION,
        );

        $_str_content = str_ireplace($_arr_replaceSrc, $_arr_replaceDst, $_str_content);

        return $_str_content; // 返回渲染后的内容
    }


    /** 输出错误
     * dump function.
     *
     * @access public
     * @static
     * @param string $error 详细错误
     * @return void
     */
    public static function dump($error) {
        $_str_html = '';

        if (self::$optDebugDump === true && !Func::isEmpty($error)) {
            $_str_tag   = 'div';
            $_str_class = 'container p-5';

            if (is_array(self::$config)) {
                if (isset(self::$config['tag']) && !Func::isEmpty(self::$config['tag'])) { // 获取调试信息容器
                    $_str_tag = self::$config['tag'];
                }

                if (isset(self::$config['class']) && !Func::isEmpty(self::$config['class'])) { // 获取调试信息的样式
                    $_str_class = self::$config['class'];
                }
            }

            $_arr_tag  = array(
                'begin' => '',
                'end'   => '',
            );

            // 补全 html 代码
            if (!Func::isEmpty($_str_tag)) {
                $_str_tagStart = '<' . $_str_tag;

                if (!Func::isEmpty($_str_class)) {
                    $_str_tagStart .= ' class="' . $_str_class . '"';
                }

                $_arr_tag['begin']  = $_str_tagStart . '><h5>Debug information</h5>';
                $_str_tag['end']    = '</' . $_str_tag . '>';
            }

            $_str_html = $_arr_tag['begin'];

            // 填充错误信息
            foreach ($error as $_key=>$_value) {
                $_str_html .= '<hr><dl>';
                    if (isset($_value['err_message'])) {
                        $_str_html .= '<dt>message</dt>';
                        $_str_html .= '<dd>' . $_value['err_message'] . '</dd>';
                    }

                    if (isset($_value['err_file'])) {
                        $_str_html .= '<dt>file</dt>';
                        $_str_html .= '<dd>' . $_value['err_file'] . '</dd>';
                    }

                    if (isset($_value['err_line'])) {
                        $_str_html .= '<dt>line</dt>';
                        $_str_html .= '<dd>' . $_value['err_line'] . '</dd>';
                    }

                    if (isset($_value['err_type'])) {
                        $_str_html .= '<dt>type</dt>';
                        $_str_html .= '<dd>' . $_value['err_type'] . '</dd>';
                    }
                $_str_html .= '</dl>';
            }

            $_str_html .= $_arr_tag['end'];
        }

        return $_str_html; // 返回友好的 html 代码
    }

    /** 判断是否为致命错误
     * isFatal function.
     *
     * @access private
     * @static
     * @param mixed $type
     * @return 是否为致命错误 (bool)
     */
    private static function isFatal($type) {
        return in_array($type, self::$errFatal);
    }


    /** 输出报错信息
     * sendErr function.
     *
     * @access private
     * @static
     * @param array $error 错误数组
     * @return void
     */
    private static function sendErr($error) {
        if (!isset($error['status_code'])) { // 假如未设置 http 状态码, 则设为 500
            $error['status_code'] = 500;
        }

        //print_r($error);

        $_obj_response  = Response::create('', '', $error['status_code']); // 实例化响应类
        $_obj_request   = Request::instance();

        $error['http_status'] = $_obj_response->getStatus(); // 设置响应状态

        $_arr_configDefault   = Config::get('var_default'); // 读取默认配置

        // 处理请求类型
        if ($_obj_request->isAjax()) {
            if (Func::isEmpty($_arr_configDefault['return_type_ajax'])) {
                $_str_type = $_arr_configDefault['return_type'];
            } else {
                $_str_type = $_arr_configDefault['return_type_ajax'];
            }
        } else {
            $_str_type = $_obj_request->type();
        }

        if ($_str_type == 'json') {
            $_str_content = Arrays::toJson($error);
        } else {
            // 用模板渲染错误
            $_str_content = self::fetch($error['status_code'], $error);
        }

        // 设置响应内容
        $_obj_response->setContent($_str_content);

        // 输出响应内容
        $_obj_response->send('error');
    }


    private static function pathProcess($tpl = '') {
        $_arr_configTplSys      = Config::get('tpl_sys'); // 取得系统模板目录
        $_arr_configExceptPage  = Config::get('exception_page'); // 取得异常页配置

        $_str_pathTpl   = GK_PATH_TPL;
        $_str_tpl       = $_str_pathTpl . 'exception' . GK_EXT_TPL;

        if (!Func::isEmpty($_arr_configTplSys['path'])) { // 如果定义了模板路径, 则替换默认路径
            if (strpos($_arr_configTplSys['path'], DS) !== false) {
                $_str_pathTpl = Func::fixDs($_arr_configTplSys['path']);
            } else {
                $_str_pathTpl .= Func::fixDs($_arr_configTplSys['path']);
            }
        }

        if (!Func::isEmpty($tpl) && isset($_arr_configExceptPage[$tpl])) { // 假如定义了模板参数, 且异常页配置中有匹配的元素
            $_str_tplName = $_arr_configExceptPage[$tpl]; // 取出异常页面的定义

            if (strpos($_str_tplName, DS) !== false) { // 如果模板名中有目录分隔符, 则认为是定义了完整路径
                $_str_tpl = $_str_tplName;
            } else { // 否则用系统默认的路径和后缀补全
                $_str_tpl = $_str_pathTpl . $_str_tplName . GK_EXT_TPL;
            }
        } else if (!Func::isEmpty($tpl)) { // 假如只定义了模板参数, 则直接用该模板
            $_str_tpl = $_str_pathTpl . $tpl . GK_EXT_TPL; // 用系统默认的路径和后缀补全
        }

        //print_r($_str_tpl);

        $_str_ext = pathinfo($_str_tpl, PATHINFO_EXTENSION); // 取得模板路径的扩展名

        if (Func::isEmpty($_str_ext)) { // 如有没有扩展名, 用系统默认的后缀补全
            $_str_tpl .= GK_EXT_TPL;
        }

        return $_str_tpl;
    }

    // since 0.2.0
    private static function configProcess($config = false) {
        $_arr_return = self::$configThis;

        if (is_array($config)) {
            if (isset($config['dump'])) { // 假如配置为输出
                $_arr_return['dump'] = $config['dump'];
            }
        } else if (is_scalar($config)) {
            $_arr_return['dump'] = $config;
        }

        return $_arr_return;
    }
}
