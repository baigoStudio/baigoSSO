<?php
/*-----------------------------------------------------------------
！！！！警告！！！！
以下为系统文件，请勿修改
-----------------------------------------------------------------*/

namespace ginkgo;

//不能非法包含或直接执行
defined('IN_GINKGO') or exit('Access Denied');

/**
* FTP操作类
*/
class Ftp {

    protected static $instance;
    private $config;
    protected $error;
    public $obj_conn; //FTP连接

    protected function __construct($config = array()) {
        $this->config = Config::get('ftp', 'var_extra');

        if (!Func::isEmpty($config)) {
            $this->config = array_replace_recursive($this->config, $config);
        }
    }

    protected function __clone() {

    }

    public static function instance($config = array()) {
        if (Func::isEmpty(static::$instance)) {
            static::$instance = new static($config);
        }
        return static::$instance;
    }


    function init() {
        if (!$this->connect()) {
            return false;
        }

        if (!$this->login()) {
            return false;
        }

        return true;
    }


    /**v连接FTP服务器
     * obj_conn function.
     *
     * @access public
     * @param mixed $ftp_host 服务器地址
     * @param int $ftp_port (default: 21) 端口
     * @return void
     */
    function connect() {
        if (!$this->initConfig()) {
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
     * ftp_login function.
     *
     * @access public
     * @param mixed $ftp_user 用户名
     * @param mixed $ftp_pass 密码
     * @return void
     */
    function login() {
        if (!@ftp_login($this->obj_conn, $this->config['user'], $this->config['pass'])) {
            $this->error = 'FTP Error: Cannot login to  {:host}';
            return false;
        }

        if (isset($this->config['pasv']) && ($this->config['pasv'] === true || $this->config['pasv'] == 'true' || $this->config['pasv'] == 'on')) {
            // 打开被动模式
            if (!@ftp_pasv($this->obj_conn, true)) {
                $this->error = 'FTP Error: Turn on passive mode failed';
                return false;
            }
        }

        return true;
    }


    function dirList($path_remote, $is_abs = false) {
        if (!$is_abs) {
            $path_remote = $this->config['path'] . $path_remote;
        }

        if (strpos($path_remote, '.')) {
            $path_remote = dirname($path_remote);
        }
        $_arr_return  = array();
        $_arr_dir     = @ftp_nlist($this->obj_conn, $path_remote);

        //print_r($_arr_dir);

        if (!Func::isEmpty($_arr_dir)) {
            foreach ($_arr_dir as $_key=>$_value) {
                if (strpos($_value, '.')) {
                    $_arr_return[$_key]['type'] = 'file';
                } else {
                    $_arr_return[$_key]['type'] = 'dir';
                }

                $_arr_return[$_key]['name'] = $_value;
            }
        }

        return $_arr_return;
    }

    /** 创建目录
     * dirMk function.
     *
     * @access public
     * @param mixed $path_remote 远程路径
     * @return void
     */
    function dirMk($path_remote, $is_abs = false) {
        if (!$is_abs) {
            $path_remote = $this->config['path'] . $path_remote;
        }

        if (strpos($path_remote, '.')) {
            $path_remote = dirname($path_remote);
        }


        if (ftp_nlist($this->obj_conn, $path_remote)) { //已存在
            @ftp_chdir($this->obj_conn, $path_remote);
            $_mkdir_status = true;
        } else {
            //创建目录
            if ($this->dirMk(dirname($path_remote), true)) {
                $_mkdir_status = @ftp_mkdir($this->obj_conn, $path_remote);
            } else {
                $this->error = 'FTP Error: Failed to create remote directory';
                $_mkdir_status = false;
            }
        }

        return $_mkdir_status;
    }


    function dirDelete($path_remote, $is_abs = false) {
        if (!$is_abs) {
            $path_remote = $this->config['path'] . $path_remote;
        }

        if (strpos($path_remote, '.')) {
            $path_remote = dirname($path_remote);
        }
        $_arr_dir = $this->dirList($path_remote); //逐级列出

        //print_r($_arr_dir);

        foreach ($_arr_dir as $_key=>$_value) {
            if ($_value['type'] == 'file') {
                $this->fileDelete($_value['name']);  //删除
            } else {
                $this->dirDelete($_value['name'], false); //递归
            }
        }

        return @ftp_rmdir($this->obj_conn, $path_remote);
    }


    /** 上传文件
     * fileUpload function.
     *
     * @access public
     * @param mixed $path_local 本地路径
     * @param mixed $path_remote 远程路径
     * @return void
     */
    function fileUpload($path_local, $path_remote, $is_abs = false, $mod = FTP_ASCII) {
        if (!$is_abs) {
            $path_remote = $this->config['path'] . $path_remote;
        }

        if (!Func::isFile($path_local)) {
            $this->error = 'FTP Error: Local file not found';
            return false;
        }

        if (!$this->dirMk($path_remote, true)) {
            return false;
        }

        if (!@ftp_put($this->obj_conn, $path_remote, $path_local, $mod)) {
            $this->error = 'FTP Error: Upload to remote directory failed';
            return false;
        }

        return true;
    }

    /** 删除文件
     * fileDelete function.
     *
     * @access public
     * @param mixed $path_remote 远程路径
     * @return void
     */
    function fileDelete($path_remote, $is_abs = false) {
        if (!$is_abs) {
            $path_remote = $this->config['path'] . $path_remote;
        }

        //print_r($path_remote);

        return @ftp_delete($this->obj_conn, $path_remote);
    }

    /** 关闭连接
     * close function.
     *
     * @access public
     * @return void
     */
    function close() {
        ftp_close($this->obj_conn);
    }

    function getError() {
        return $this->error;
    }


    private function initConfig() {
        if (!isset($this->config['host'])) {
            return false;
        }
        if (!isset($this->config['user'])) {
            return false;
        }
        if (!isset($this->config['pass'])) {
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