<?php
/*-----------------------------------------------------------------
！！！！警告！！！！
以下为系统文件，请勿修改
-----------------------------------------------------------------*/

namespace ginkgo;

// 不能非法包含或直接执行
defined('IN_GINKGO') or exit('Access denied');

// 发起 http 请求, 基于 curl
class Http extends File {

    protected static $instance; // 当前实例
    public $header = false; // 头
    public $verifyPeer = false; // 验证对等证书
    public $verifyHost = false; // 验证主机
    public $caInfo; // 一个保存着1个或多个用来让服务端验证的证书的文件名, $verifyPeer 属性为 true 时有效
    public $connectTimeout = 30; // 连接超时
    public $returnTransfer = true; // 是否转换返回, true 返回原生的（Raw）输出

    // 默认 http 头, 模仿表单提交
    private $httpHeader = array(
        'Content-Type'  => 'application/x-www-form-urlencoded; charset=UTF-8',
        'Accept'        => 'application/json',
    );

    private $contentType; // http 头的内容类型 (默认为表单形式)
    private $charset; // http 头的字符编码
    private $port; // url 端口

    private $accept = 'application/json'; // 请求返回的类型
    private $result; // 返回结果
    private $errno; // 错误号
    private $statusCode; // http 状态码


    /** 构造函数
     * __construct function.
     *
     * @access protected
     * @param string $port (default: '') 端口
     * @return void
     */
    protected function __construct($port = '') {
        $this->curl  = curl_init(); // curl 初始化
        $this->port  = $port;
    }

    protected function __clone() {

    }


    /** 初始化实例
     * instance function.
     *
     * @access public
     * @static
     * @return 当前类的实例
     */
    public static function instance() {
        if (Func::isEmpty(static::$instance)) {
            static::$instance = new static();
        }

        return static::$instance;
    }


    /** 发起 http 请求
     * request function.
     *
     * @access public
     * @param string $url 地址
     * @param mixed $data (default: false) 发送的数据
     * @param string $method (default: 'get') 发起方法
     * @return url 返回结果
     */
    function request($url, $data = false, $method = 'get') {
        $method         = strtolower($method);

        if (Func::isEmpty($url)) { // url 错误
            $this->error = 'Missing URL';
            return false;
        }

        $this->portProcess($url); // 端口处理

        $_str_data          = $this->dataProcess($data); // 发送数据处理
        $_arr_httpHeader    = $this->httpHeaderProcess(); // http 头处理

        switch ($method) {
            case 'post': // post 方法
                $_arr_opt = array(
                    CURLOPT_POST        => true, // 设置 post 方法为 true
                    CURLOPT_POSTFIELDS  => $_str_data, // 设置发送的数据
                );

                curl_setopt_array($this->curl, $_arr_opt);
            break;

            case 'get':
                if (strpos($url, '?')) {
                    $_str_conn = '&';
                } else {
                    $_str_conn = '?';
                }

                curl_setopt($this->curl, CURLOPT_HTTPGET, true);  // 设置 get 方法为 true

                $url = $url . $_str_conn . $_str_data; // 将附带数据, 连接符拼合为完整 url
            break;
        }

        $_arr_opt = array(
            CURLOPT_URL             => $url, // 请求 url
            CURLOPT_CONNECTTIMEOUT  => $this->connectTimeout, // 超时
            CURLOPT_RETURNTRANSFER  => $this->returnTransfer, // 是否转换返回, true 返回原生的（Raw）输出
            CURLOPT_SSL_VERIFYPEER  => $this->verifyPeer, // 验证对等证书
            CURLOPT_SSL_VERIFYHOST  => $this->verifyHost, // 验证主机
            CURLOPT_HEADER          => $this->header,  // 将头文件的信息作为数据流输出
        );

        curl_setopt_array($this->curl, $_arr_opt);

        if (!Func::isEmpty($_arr_httpHeader)) {
            curl_setopt($this->curl, CURLOPT_HTTPHEADER, $_arr_httpHeader); // 设置 http 头
        }

        if (!Func::isEmpty($this->caInfo)) {
            curl_setopt($this->curl, CURLOPT_CAINFO, $this->caInfo); // 设置证书名
        }

        if (!Func::isEmpty($this->port)) {
            curl_setopt($this->curl, CURLOPT_PORT, $this->port); // 设置端口
        }

        $_result = curl_exec($this->curl); // 执行请求

        $this->result = $_result;

        $_result = $this->resultProcess($_result); // 返回结果处理

        $this->statusCode   = curl_getinfo($this->curl, CURLINFO_HTTP_CODE); // 取得 http 状态码

        $this->error        = curl_error($this->curl); // 取得错误信息
        $this->errno        = curl_errno($this->curl); // 取得错误号

        return $_result;
    }


    /** 设置 http 头 (替换)
     * setHeader function.
     *
     * @access public
     * @param string $name 名称
     * @param string $value (default: '') 值
     * @return void
     */
    function setHeader($name, $value = '') {
        $this->httpHeader[$name] = $value;
    }

    /** 设置 url 端口
     * setPort function.
     *
     * @access public
     * @param string $port (default: '')
     * @return void
     */
    function setPort($port = '') {
        $this->port = $port;
    }

    /** 设置请求返回的类型
     * setAccept function.
     *
     * @access public
     * @param string $type (default: 'application/json') 类型
     * @return void
     */
    function setAccept($type = 'application/json') {
        $this->httpHeader['Accept'] = $type;

        $this->accept = $type;
    }

    /** 设置请求内容的类型 (默认为表单形式)
     * contentType function.
     *
     * @access public
     * @param string $contentType  请求内容的类型
     * @param string $charset (default: 'UTF-8') 字符编码
     * @return void
     */
    function contentType($contentType, $charset = 'UTF-8') {
        $this->contentType  = $contentType;
        $this->charset      = $charset;
        $this->httpHeader['Content-Type'] = $contentType . '; Charset=' . $charset;
    }

    /** 取得错误消息
     * getError function.
     *
     * @access public
     * @return 错误消息
     */
    function getError() {
        return $this->error;
    }

    /** 取得错误号
     * getErrno function.
     *
     * @access public
     * @return 错误号
     */
    function getErrno() {
        return $this->errno;
    }

    /** 取得 http 状态码
     * getStatusCode function.
     *
     * @access public
     * @return void
     */
    function getStatusCode() {
        return $this->statusCode;
    }

    /** 取得返回结果
     * getResult function.
     *
     * @access public
     * @return void
     */
    function getResult() {
        return $this->result;
    }

    /** 抓取远程内容并保存在临时文件中
     * getRemote function.
     *
     * @access public
     * @param string $url 远程地址
     * @param bool $data (default: false) 附带数据
     * @param string $method (default: 'get') 请求方法
     * @return 临时文件信息
     */
    function getRemote($url, $data = false, $method = 'get') {
        $_result    = $this->request($url, $data, $method); // 用 request 方法取得结果

        /*print_r($url . ' -> ' . $this->statusCode);
        print_r(PHP_EOL);*/

        if ($this->statusCode != '200' || $this->errno > 0) { // 返回失败
            return false;
        }

        $_tmp_path  = GK_PATH_TEMP . md5($url); // 生成临时文件名
        $_num_size  = 0;

        $_num_size  = $this->fileWrite($_tmp_path, $this->result); // 写入临时文件

        if (!$_num_size) { // 写入失败
            return false;
        }

        $_str_mime  = $this->getMime($_tmp_path); // 取得 mime 类型
        $_str_ext   = $this->getExt($url, $_str_mime); // 取得扩展名

        if (!$this->checkFile($_str_ext, $_str_mime)) { // 验证是否为允许的类型
            $this->fileDelete($_tmp_path);
            return false;
        }

        $_str_name  = basename($url);

        $_arr_fileInfo = array(
            'name'      => Func::safe($_str_name),
            'tmp_name'  => $_tmp_path,
            'ext'       => $_str_ext,
            'mime'      => $_str_mime,
            'size'      => $_num_size,
        );

        $this->fileInfo = $_arr_fileInfo;

        return $_arr_fileInfo;
    }

    /** 移动远程抓取到的文件到指定文件夹
     * move function.
     *
     * @access public
     * @param string $path_dir 指定文件夹
     * @param mixed $name (default: true) 指定文件名, true 为自动生成, false 为原始文件名, 字符串为指定文件名
     * @param mixed $replace (default: true) 是否替换
     * @return void
     */
    function move($path_dir, $name = true, $replace = true) {
        $name = $this->genFilename($name);

        if (Func::isEmpty($name)) {
            $this->error = 'Missing filename';

            return false;
        }

        $_str_path = Func::fixDs($path_dir) . $name; // 补全路径

        if (!$replace && Func::isFile($_str_path)) { // 文件名冲突
            $this->error = 'Has the same filename: ' . $_str_path;

            return false;
        }

        return $this->fileMove($this->fileInfo['tmp_name'], $_str_path); // 移动文件
    }


    /** 返回结果处理
     * resultProcess function.
     *
     * @access private
     * @param mixed $result 返回结果
     * @return 处理后的结果
     */
    private function resultProcess($result) {
        switch ($this->accept) {
            case 'application/json':
                $result = Json::decode($result);
            break;
        }

        return $result;
    }

    /** 端口处理
     * portProcess function.
     *
     * @access private
     * @param string $url 请求 url
     * @return void
     */
    private function portProcess($url) {
        $_arr_urlParsed = parse_url($url); // 解析 url

        if (Func::isEmpty($this->port) && isset($_arr_urlParsed['port']) && !Func::isEmpty($_arr_urlParsed['port'])) {
            $this->port = $_arr_urlParsed['port']; // 根据解析结果指定端口
        }
    }

    /** 发送数据处理
     * dataProcess function.
     *
     * @access private
     * @param array $data 附带数据
     * @return 拼合后的数据字符串
     */
    private function dataProcess($data) {
        if ($data) { // 拼接数据
            $_str_data  = http_build_query($data);
        } else {
            $_str_data  = '';
        }

        return $_str_data;
    }

    /** http 头处理
     * httpHeaderProcess function.
     *
     * @access private
     * @return http 头数组
     */
    private function httpHeaderProcess() {
        $_arr_httpHeader    = array();

        if (!Func::isEmpty($this->httpHeader)) {
            // 发送头部信息
            foreach ($this->httpHeader as $_key=>$_value) {
                if (Func::isEmpty($_value)) {
                    $_arr_httpHeader[] = $_key;
                } else {
                    $_arr_httpHeader[] = $_key . ': ' . $_value;
                }
            }

            //print_r($_arr_httpHeader);
        }

        return $_arr_httpHeader;
    }

    function __destruct() {
        if ($this->curl != null) {
            curl_close($this->curl);
            $this->curl = null;
        }
    }
}