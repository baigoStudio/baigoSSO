<?php
/*-----------------------------------------------------------------
！！！！警告！！！！
以下为系统文件，请勿修改
-----------------------------------------------------------------*/

namespace ginkgo;

// 不能非法包含或直接执行
defined('IN_GINKGO') or exit('Access Denied');

// FTP操作类
class Ftp {

    protected static $instance; // 当前实例
    private $config; // 配置
    protected $error; // 错误
    public $obj_conn; // ftp 连接实例

    protected function __construct($config = array()) {
        $this->config = Config::get('ftp', 'var_extra');

        if (!Func::isEmpty($config)) {
            $this->config = array_replace_recursive($this->config, $config);
        }
    }

    protected function __clone() {

    }

    /** 实例化
     * instance function.
     *
     * @access public
     * @static
     * @param array $config (default: array()) 配置
     * @return 当前类的实例
     */
    public static function instance($config = array()) {
        if (Func::isEmpty(static::$instance)) {
            static::$instance = new static($config);
        }
        return static::$instance;
    }


    /** 初始化
     * init function.
     *
     * @access public
     * @return 连接与登录结果 (bool)
     */
    function init() {
        if (!$this->connect()) {
            return false;
        }

        if (!$this->login()) {
            return false;
        }

        return true;
    }


    /** 连接 ftp 服务器
     * connect function.
     *
     * @access public
     * @return 连接结果 (bool)
     */
    function connect() {
        if (!$this->initConfig()) { // 初始化配置
            return false;
        }

        $this->obj_conn = @ftp_connect($this->config['host'], $this->config['port']); //连接

        if (!$this->obj_conn) {
            $this->error = 'FTP Error: Cannot conect to {:host}';
            return false;
        }

        return true;
    }


    /** 登录
     * login function.
     *
     * @access public
     * @return 登录结果 (bool)
     */
    function login() {
        if (!@ftp_login($this->obj_conn, $this->config['user'], $this->config['pass'])) {
            $this->error = 'FTP Error: Cannot login to  {:host}';
            return false;
        }

        if (isset($this->config['pasv']) && ($this->config['pasv'] === true || $this->config['pasv'] === 'true' || $this->config['pasv'] === 'on')) {
            // 打开被动模式
            if (!@ftp_pasv($this->obj_conn, true)) {
                $this->error = 'FTP Error: Turn on passive mode failed';
                return false;
            }
        }

        return true;
    }


    /** 列出远程目录结构
     * dirList function.
     *
     * @access public
     * @param string $path_remote 路径
     * @param bool $is_abs (default: false) 是否为绝对路径
     * @return 目录结构
     */
    function dirList($path_remote, $is_abs = false) {
        if (!$is_abs) { // 假如为绝对路径, 则补全路径
            $path_remote = $this->config['path'] . $path_remote;
        }

        if (!@ftp_chdir($path_remote)) { // 如路径不是目录, 则读取上一级
            $path_remote = dirname($path_remote);
        }

        $_arr_return  = array();
        $_arr_dir     = @ftp_nlist($this->obj_conn, $path_remote);// 列出目录

        //print_r($_arr_dir);

        if (!Func::isEmpty($_arr_dir)) {
            foreach ($_arr_dir as $_key=>$_value) {
                if (@ftp_chdir($_value)) {
                    $_arr_return[$_key]['type'] = 'dir'; // 文件夹
                } else {
                    $_arr_return[$_key]['type'] = 'file'; // 文件
                }

                $_arr_return[$_key]['name'] = $_value;
            }
        }

        return $_arr_return;
    }

    /** 创建目录
     * dirMk function.
     *
     * @param string $path_remote 路径
     * @param bool $is_abs (default: false) 是否为绝对路径
     * @return 创建结果 (bool)
     */
    function dirMk($path_remote, $is_abs = false) {
        if (!$is_abs) { // 假如为绝对路径, 则补全路径
            $path_remote = $this->config['path'] . $path_remote;
        }

        if (!@ftp_chdir($path_remote)) { // 如路径不是目录, 则读取上一级
            $path_remote = dirname($path_remote);
        }

        if (ftp_nlist($this->obj_conn, $path_remote)) { // 已存在
            @ftp_chdir($this->obj_conn, $path_remote); // 切换到当前目录
            $_mkdir_status = true;
        } else {
            if ($this->dirMk(dirname($path_remote), true)) { // 创建上一级目录
                $_mkdir_status = @ftp_mkdir($this->obj_conn, $path_remote); // 创建当前目录
            } else {
                $this->error = 'FTP Error: Failed to create remote directory';
                $_mkdir_status = false;
            }
        }

        return $_mkdir_status;
    }


    /** 递归删除整个目录
     * dirDelete function.
     *
     * @access public
     * @param string $path_remote 路径
     * @param bool $is_abs (default: false) 是否为绝对路径
     * @return void
     */
    function dirDelete($path_remote, $is_abs = false) {
        if (!$is_abs) { // 假如为绝对路径, 则补全路径
            $path_remote = $this->config['path'] . $path_remote;
        }

        if (!@ftp_chdir($path_remote)) { // 如路径不是目录, 则读取上一级
            $path_remote = dirname($path_remote);
        }

        $_arr_dir = $this->dirList($path_remote); // 逐级列出

        //print_r($_arr_dir);

        foreach ($_arr_dir as $_key=>$_value) {
            if ($_value['type'] == 'file') {
                $this->fileDelete($_value['name']);  // 删除文件
            } else {
                $this->dirDelete($_value['name'], false); // 递归
            }
        }

        return @ftp_rmdir($this->obj_conn, $path_remote); // 最后删除目录
    }


    /** 上传文件
     * fileUpload function.
     *
     * @access public
     * @param string $path_local 本地路径
     * @param string $path_remote 远程路径
     * @param bool $is_abs (default: false) 是否为绝对路径
     * @param bool $mod (default: FTP_ASCII) 上次模式
     * @return 上传结果 (bool)
     */
    function fileUpload($path_local, $path_remote, $is_abs = false, $mod = FTP_ASCII) {
        if (!$is_abs) { // 假如为绝对路径, 则补全路径
            $path_remote = $this->config['path'] . $path_remote;
        }

        if (!Func::isFile($path_local)) { // 检查本地文件是否存在
            $this->error = 'FTP Error: Local file not found';
            return false;
        }

        if (!$this->dirMk($path_remote, true)) { // 创建远程目录
            return false;
        }

        if (!@ftp_put($this->obj_conn, $path_remote, $path_local, $mod)) { // 上传
            $this->error = 'FTP Error: Upload to remote directory failed';
            return false;
        }

        return true;
    }

    /** 删除文件
     * fileDelete function.
     *
     * @access public
     * @param string $path_remote 远程路径
     * @param bool $is_abs (default: false) 是否为绝对路径
     * @return void
     */
    function fileDelete($path_remote, $is_abs = false) {
        if (!$is_abs) { // 假如为绝对路径, 则补全路径
            $path_remote = $this->config['path'] . $path_remote;
        }

        //print_r($path_remote);

        return @ftp_delete($this->obj_conn, $path_remote);
    }

    // 关闭连接
    function close() {
        ftp_close($this->obj_conn);
    }


    /** 获取错误
     * getError function.
     *
     * @access public
     * @return 错误信息
     */
    function getError() {
        return $this->error;
    }


    /** 配置初始化
     * initConfig function.
     *
     * @access private
     * @return 初始化结果 (bool)
     */
    private function initConfig() {
        if (!isset($this->config['host']) || Func::isEmpty($this->config['host'])) {
            return false;
        }
        if (!isset($this->config['user']) || Func::isEmpty($this->config['user'])) {
            return false;
        }
        if (!isset($this->config['pass']) || Func::isEmpty($this->config['pass'])) {
            return false;
        }

        isset($this->config['path']) or $this->config['path'] = '/';
        isset($this->config['port']) or $this->config['port'] = 21;
        isset($this->config['pasv']) or $this->config['pasv'] = 'off';

        //Func::fixDs($this->config['path']);
        Func::fixDs($this->config['path'], '/');
        str_replace('\\', '/', $this->config['path']);
        str_replace('//', '/', $this->config['path']);

        return true;
    }
}