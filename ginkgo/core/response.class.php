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

// 响应类
class Response {

    public $config = array(); // 配置
    public $statusCode; // http 状态码
    public $replace  = array(); // 输出替换

    public $header   = array(
        'Content-Type'  => 'text/html; Charset=UTF-8',
    ); // 头数据

    protected $data; // 数据

    // 常用 http 状态码及含义
    protected $statusRow = array(
        100 => 'Continue',
        101 => 'Switching Protocols',

        200 => 'OK',
        201 => 'Created',
        202 => 'Accepted',
        203 => 'Non-Authoritative Information',
        204 => 'No Content',
        205 => 'Reset Content',
        206 => 'Partial Content',

        300 => 'Multiple Choices',
        301 => 'Moved Permanently',
        302 => 'Found',
        303 => 'See Other',
        304 => 'Not Modified',
        305 => 'Use Proxy',
        307 => 'Temporary Redirect',

        400 => 'Bad Request',
        401 => 'Unauthorized',
        402 => 'Payment Required',
        403 => 'Forbidden',
        404 => 'Not Found',
        405 => 'Method Not Allowed',
        406 => 'Not Acceptable',
        407 => 'Proxy Authentication Required',
        408 => 'Request Time-out',
        409 => 'Conflict',
        410 => 'Gone',
        411 => 'Length Required',
        412 => 'Precondition Failed',
        413 => 'Request Entity Too Large',
        414 => 'Request-URI Too Large',
        415 => 'Unsupported Media Type',
        416 => 'Requested range not satisfiable',
        417 => 'Expectation Failed',

        500 => 'Internal Server Error',
        501 => 'Not Implemented',
        502 => 'Bad Gateway',
        503 => 'Service Unavailable',
        504 => 'Gateway Time-out',
        505 => 'HTTP Version not supported',
    );

    protected $configThis = array(
        'charset'              => 'UTF-8', // 字符编码
        'jsonp_callback'       => '', // 回调函数
        'jsonp_callback_param' => '', // 请求回调函数的参数
    );


    /** 构造函数
     * __construct function.
     *
     * @access public
     * @param mixed $data (default: '') 数据
     * @param int $statusCode (default: 200) http 状态码
     * @param array $header (default: array()) 头数据
     * @return void
     */
    public function __construct($data, $statusCode = 200, $header = array()) {
        $this->config();
        $this->setStatusCode($statusCode); // 设置 http 状态码
        $this->setContent($data); // 设置数据
        $this->setHeader($header); // 设置头数据

        $this->obj_request  = Request::instance();
    }

    protected function __clone() { }

    /** 创建响应实例
     * create function.
     *
     * @access public
     * @static
     * @param string $data (default: '') 数据
     * @param string $type (default: '') 响应类型
     * @param int $statusCode (default: 200)  http 状态码
     * @param array $header (default: array()) 头数据
     * @return void
     */
    public static function create($data, $type = '', $statusCode = 200, $header = array()) {
        $_class = '';

        if (!Func::isEmpty($type)) { // 指定类型
            if (strpos($type, '\\')) { // 如指定类型包含命名空间, 则直接使用
                $_class = $type;
            } else { // 否则补全命名空间
                $_class = 'ginkgo\\response\\' . Strings::ucwords($type, '_');
            }
        }

        if (class_exists($_class)) { // 如指定的类存在
            return new $_class($data, $statusCode, $header); // 实例化响应并返回
        } else { // 如不存在, 则用本类实例化 (html)
            return new static($data, $statusCode, $header);
        }
    }


    // 配置 since 0.2.0
    public function config($config = array()) {
        $_arr_config   = Config::get('var_default'); // 取得配置

        $_arr_configDo = $this->configThis;

        if (is_array($_arr_config) && !Func::isEmpty($_arr_config)) {
            $_arr_configDo = array_replace_recursive($_arr_configDo, $_arr_config); // 合并配置
        }

        if (is_array($this->config) && !Func::isEmpty($this->config)) {
            $_arr_configDo = array_replace_recursive($_arr_configDo, $this->config); // 合并配置
        }

        if (is_array($config) && !Func::isEmpty($config)) {
            $_arr_configDo = array_replace_recursive($_arr_configDo, $config); // 合并配置
        }

        $this->config  = $_arr_configDo;
    }


    /** 设置 http 状态码
     * setStatusCode function.
     *
     * @access public
     * @param int $statusCode (default: 200) http 状态码
     * @return void
     */
    public function setStatusCode($statusCode = 200) {
        $this->statusCode = $statusCode;
        $this->header['HTTP/1.0 ' . $this->getStatusCode()] = '';
    }


    /** 设置头数据
     * setHeader function.
     *
     * @access public
     * @param mixed $header
     * @param string $value (default: '') 头数据
     * @return void
     */
    public function setHeader($header, $value = '') {
        if (is_array($header)) {
            $this->header = array_replace_recursive($this->header, $header);
        } else {
            $this->header[$header] = $value;
        }
    }

    /** 设置数据
     * setContent function.
     *
     * @access public
     * @param mixed $data 数据
     * @return void
     */
    public function setContent($data) {
        $this->data = $data;

        //print_r($this->data);
        //print_r('<br>');
    }

    /** 设置输出替换
     * setReplace function.
     *
     * @access public
     * @param mixed $replace 查找内容
     * @param string $value (default: '') 替换的值
     * @return void
     */
    public function setReplace($replace, $value = '') {
        if (is_array($replace)) {
            $this->replace = array_replace_recursive($this->replace, $replace);
        } else {
            $this->replace[$replace] = $value;
        }
    }

    /** 取得 http 状态信息
     * getStatus function.
     *
     * @access public
     * @return 状态信息
     */
    public function getStatus() {
        if (isset($this->statusRow[$this->statusCode])) {
            $_str_status = $this->statusRow[$this->statusCode];
        } else {
            $_str_status = $this->statusRow[200];
        }

        return $_str_status;
    }

    /** 取得 http 状态码
     * getStatusCode function.
     *
     * @access public
     * @return 状态码
     */
    public function getStatusCode() {
        return $this->statusCode;
    }

    /** 取得头数据
     * getHeader function.
     *
     * @access public
     * @return 头数据
     */
    public function getHeader($header = '') {
        $_return = '';

        if (Func::isEmpty($header)) {
            $_return = $this->header;
        } else if (isset($this->header[$header])) {
            $_return = $this->header[$header];
        }

        return $_return;
    }

    /** 取得数据
     * getContent function.
     *
     * @access public
     * @return 数据
     */
    public function getContent() {
        return $this->output($this->data);
    }

    /** 设置过期时间
     * expires function.
     *
     * @access public
     * @param mixed $time
     * @return void
     */
    public function expires($time) {
        $this->header['Expires'] = $time;
    }


    /** 设置缓存控制
     * cacheControl function.
     *
     * @access public
     * @param mixed $cacheControl
     * @return void
     */
    public function cacheControl($cacheControl) {
        $this->header['Cache-Control'] = $cacheControl;
    }

    /** 设置输出类型
     * contentType function.
     *
     * @access public
     * @param mixed $contentType
     * @param string $charset (default: 'UTF-8')
     * @return void
     */
    public function contentType($contentType, $charset = '') {
        if (!Func::isEmpty($charset)) {
            $this->config['charset'] = $charset;
        }

        $this->header['Content-Type'] = $contentType . '; Charset=' . $this->config['charset'];
    }


    /** 向浏览器发送内容
     * send function.
     *
     * @access public
     * @param int $id (default: 0) ID
     * @return void
     */
    public function send($id = 0) {
        Plugin::listen('action_fw_response_send');

        $_str_content = $this->getContent(); // 取得内容

        $_str_content = $this->replaceProcess($_str_content); // 输出替换

        if (!headers_sent() && !Func::isEmpty($this->header)) {
            if (function_exists('http_response_code')) {
                // 发送状态码
                http_response_code($this->statusCode);
            }
            // 发送头数据
            foreach ($this->header as $_key => $_value) {
                if (Func::isEmpty($_value)) {
                    header($_key);
                } else {
                    header($_key . ': ' . $_value);
                }
            }
        }

        Plugin::listen('action_fw_response_end');

        exit($_str_content); // 输出并关闭
    }


    /** 输出并注入调试信息
     * output function.
     *
     * @access protected
     * @param mixed $data
     * @return void
     */
    protected function output($data) {
        if (is_array($data)) { // 如果是数组
            $data = Debug::inject($data, 'arr'); // 注入调试信息 (是否注入具体内容, 由 ginkgo/Debug 类决定)
        } else if (is_string($data)) {
            $data = Debug::inject($data, 'str');
        }

        return $data;
    }


    /** 输出替换处理
     * replaceProcess function.
     *
     * @access protected
     * @param mixed $content 内容
     * @return void
     */
    protected function replaceProcess($content) {
        $_arr_replace = $this->replace;

        if (is_array($_arr_replace) && !Func::isEmpty($_arr_replace)) {
            $_arr_keys   = array_keys($_arr_replace);
            $_arr_values = array_values($_arr_replace);

            $content = str_ireplace($_arr_keys, $_arr_values, $content);
        }

        return $content;
    }
}
