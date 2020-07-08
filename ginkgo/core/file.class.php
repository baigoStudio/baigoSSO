<?php
/*-----------------------------------------------------------------
！！！！警告！！！！
以下为系统文件，请勿修改
-----------------------------------------------------------------*/

namespace ginkgo;

// 不能非法包含或直接执行
defined('IN_GINKGO') or exit('Access denied');

// 文件操作类
class File {

    protected static $instance; // 当前实例
    protected $error; // 错误
    protected $rule = 'md5'; // 生成文件名规则 (函数名)
    protected $mimeRows = array(); // mime 池

    // 默认 $_FILES 变量结构
    protected $fileInfo = array(
        'name'      => '',
        'tmp_name'  => '',
        'ext'       => '',
        'mime'      => '',
        'size'      => 0,
    );

    protected function __construct() {

    }

    protected function __clone() {

    }

    /** 实例化
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



    /** 列出目录结构
     * dirList function.
     *
     * @access public
     * @param string $path 路径
     * @param string $ext (default: '') 指定扩展名
     * @return 目录结构
     */
    function dirList($path, $ext = '') {
        $_arr_return  = array();
        $_arr_dir     = array();

        if (is_dir($path)) { // 判断是否问文件夹
            $_arr_dir = scandir($path);
        } else {
            $this->error = 'Not a directory'; // 定义错误消息
            return array();
        }

        if (!Func::isEmpty($_arr_dir)) {
            foreach ($_arr_dir as $_key=>$_value) {
                if ($_value != '.' && $_value != '..') {
                    $_str_pathFull  = Func::fixDs($path) . $_value; // 补全路径

                    $_str_type = filetype($_str_pathFull); // 判断路径类型

                    $_arr_returnTmp = array(
                        'name' => $_value,
                        'path' => $_str_pathFull,
                        'type' => $_str_type,
                    );

                    if ($_str_type == 'dir') { // 如果是文件夹, 则递归列出
                        $_arr_returnTmp['sub'] = $this->dirList($_str_pathFull, $ext);

                        $_arr_return[] = $_arr_returnTmp; // 拼接数组
                    } else {
                        if (Func::isEmpty($ext)) { // 如果没有指定扩展名, 则全部列出
                            $_arr_return[] = $_arr_returnTmp;
                        } else {
                            $_str_ext = pathinfo($_value, PATHINFO_EXTENSION); // 比较扩展名, 符合的拼合数组

                            if ($_str_ext == $ext) {
                                $_arr_return[] = $_arr_returnTmp;
                            }
                        }
                    }
                }
            }
        }

        return $_arr_return;
    }

    /**
     * dirMk function.
     *
     * @access public
     * @param string $path 路径
     * @return 创建结果 (bool)
     */
    function dirMk($path) {
        $old_umask = umask(0);

        if (is_dir($path)) { //已存在
            $_bool_status = true;
        } else {
            //创建目录
            if ($this->dirMk(dirname($path))) { //递归
                if (mkdir($path, 0755, true)) { //创建成功
                    $_bool_status = true;
                } else {
                    $this->error  = 'Failed to create directory'; // 定义错误消息
                    $_bool_status = false; //失败
                }
            } else {
                $this->error  = 'Failed to create directory'; // 定义错误消息
                $_bool_status = false;
            }
        }

        //print_r($old_umask);
        umask($old_umask);

        return $_bool_status;
    }


    /** 拷贝整个目录
     * dirCopy function.
     *
     * @access public
     * @param string $src 源路径
     * @param string $dst 目标路径
     * @return 拷贝结果 (bool)
     */
    function dirCopy($src, $dst) {
        if (!$this->dirMk($dst)) {
            return false;
        }

        $_arr_dir = $this->dirList($src); //逐级列出

        foreach ($_arr_dir as $_key=>$_value) {
            if (substr($dst, -1) == DS) {
                $_str_pathFull  = $dst . $_value['name'];
            } else {
                $_str_pathFull  = $dst . DS . $_value['name'];
            }

            if ($_value['type'] == 'file' && Func::isFile($_value['path'])) { // 假如为文件且路径存在, 则直接拷贝
                copy($_value['path'], $_str_pathFull);
            } else if (is_dir($_value['path'])) { // 假如为目录且路径存在, 则递归拷贝
                $this->dirCopy($_value['path'], $_str_pathFull);
            }
        }

        return true;
    }

    /** 递归删除整个目录
     * dirDelete function.
     *
     * @access public
     * @param string $path 路径
     * @return 删除结果 (bool)
     */
    function dirDelete($path) {
        if (!is_dir($path)) { // 路径不存在则返回 false
            $this->error = 'Directory not found'; // 定义错误消息
            return false;
        }

        // 删除目录及目录里所有的目录和文件
        $_arr_dir = $this->dirList($path); // 逐级列出

        foreach ($_arr_dir as $_key=>$_value) {
            if (substr($path, -1) == DS) {
                $_str_pathFull  = $path . $_value['name'];
            } else {
                $_str_pathFull  = $path . DS . $_value['name'];
            }

            if ($_value['type'] == 'file' && Func::isFile($_str_pathFull)) { // 假如为文件且路径存在, 则直接删除文件
                $this->fileDelete($_str_pathFull);
            } else if (is_dir($_str_pathFull)) { // 假如为目录且路径存在, 则递归删除
                $this->dirDelete($_str_pathFull);
            }
        }

        return rmdir($path); // 最后删除目录
    }


    /** 读取文件
     * fileRead function.
     *
     * @access public
     * @param string $path 路径
     * @return 文件内容
     */
    function fileRead($path) {
        if (!Func::isFile($path)) {
            $this->error = 'File not found'; // 定义错误消息
            return false;
        }

        return file_get_contents($path);
    }


    /** 移动文件 (更名)
     * fileMove function.
     *
     * @access public
     * @param string $src 源路径
     * @param string $dst 目标路径
     * @return 移动结果 (bool)
     */
    function fileMove($src, $dst) {
        if (!Func::isFile($src)) {
            $this->error = 'Source file not found';
            return false;
        }

        if (!$this->dirMk(dirname($dst))) {
            return false;
        }
        return rename($src, $dst);
    }


    /** 写入文件
     * fileWrite function.
     *
     * @access public
     * @param string $path 路径
     * @param string $content 内容
     * @param bool $append (default: false) 是否为追加
     * @return 写入字节数
     */
    function fileWrite($path, $content, $append = false) {
        if (!$this->dirMk(dirname($path))) { // 假如目录不能存在则创建
            return false;
        }

        if ($append) {
            $append = FILE_APPEND;
        }

        return file_put_contents($path, $content, $append);
    }


    /** 复制文件
     * fileCopy function.
     *
     * @access public
     * @param string $src 源路径
     * @param string $dst 目标路径
     * @return 复制结果 (bool)
     */
    function fileCopy($src, $dst) {
        if (!$this->dirMk($dst)) {
            return false;
        }

        if (!Func::isFile($src)) {
            $this->error = 'Source file not found';
            return false;
        }

        return copy($src, $dst);
    }


    /** 删除文件
     * fileDelete function.
     *
     * @access public
     * @param string $path 路径
     * @return 删除结果 (bool)
     */
    function fileDelete($path) {
        if (!Func::isFile($path)) { // 文件不能存在则返回 false
            $this->error = 'File not found';
            return false;
        }

        return unlink($path);
    }


    /** 获取文件的 mime 类型
     * getMime function.
     *
     * @access public
     * @param string $path 路径
     * @param bool $mime (default: false) 手动报告类型
     * @return mime 类型
     */
    function getMime($path, $mime = false) {
        $_obj_finfo = new \finfo();

        $_str_mime  = $_obj_finfo->file($path, FILEINFO_MIME_TYPE);

        if ($_str_mime === false) { //如果无法识别则以手动报告的 mime 为准
            if (!Func::isEmpty($mime) && is_string($mime)) {
                $_str_mime = $mime;
            }
        }

        return $_str_mime;
    }


    /** 获取扩展名
     * getExt function.
     *
     * @access public
     * @param string $path 路径
     * @param mixed $mime (default: false) mime 类型
     * @return 扩展名
     */
    function getExt($path, $mime = false) {
        $_str_ext = strtolower(pathinfo($path, PATHINFO_EXTENSION)); //取得扩展名


        if ($mime) {
            // 扩展名与 mime 不符的情况下, 反向查找, 如果存在, 则更改扩展名
            if (!isset($this->mimeRows[$_str_ext]) || !in_array($mime, $this->mimeRows[$_str_ext])) {
                foreach ($this->mimeRows as $_key_allow=>$_value_allow) {
                    if (in_array($mime, $_value_allow)) {
                        return $_key_allow;
                    }
                }
            }
        }

        return $_str_ext;
    }


    /** 设置 mime
     * setMime function.
     *
     * @access public
     * @param mixed $mime
     * @param array $value (default: array())
     * @return void
     */
    function setMime($mime, $value = array()) {
        if (is_array($mime)) {
            $this->mimeRows = array_replace_recursive($this->mimeRows, $mime);
        } else {
            $this->mimeRows[$mime] = $value;
        }
    }


    /** 设置生成文件名规则 (函数名)
     * rule function.
     *
     * @access public
     * @param mixed $rule
     * @return 当前实例
     */
    function rule($rule) {
        $this->rule = $rule;

        return $this;
    }

    /** 生成文件名
     * genFilename function.
     *
     * @access protected
     * @param mixed $name (default: true) 文件名
     * @return 文件名
     */
    protected function genFilename($name = true) {
        if ($name === true) { // 参数为 true 时, 按规则生成文件名
            if (is_callable($this->rule)) {
                $_str_type = $this->rule;
            } else {
                $_str_type = 'md5';
            }

            if (isset($_SERVER['REQUEST_TIME_FLOAT'])) {
                $_tm_time = $_SERVER['REQUEST_TIME_FLOAT'];
            } else {
                $_tm_time = GK_NOW;
            }

            $name = call_user_func($_str_type, $_tm_time) . '.' . $this->fileInfo['ext'];
        } else if ($name === false) { // 参数为 false 时, 使用原始文件名
            $name = $this->fileInfo['name'];
        }

        // 指定为字符串时, 直接使用

        return $name;
    }


    /** 验证是否为允许的文件
     * checkFile function.
     *
     * @access protected
     * @param mixed $ext
     * @param mixed $mime
     * @return 验证结果 (bool)
     */
    protected function checkFile($ext, $mime) {
        if (!Func::isEmpty($this->mimeRows)) {
            if (!isset($this->mimeRows[$ext])) { //该扩展名的 mime 数组是否存在
                $this->error = 'MIME check failed';

                return false;
            }

            if (!in_array($mime, $this->mimeRows[$ext])) { //是否允许
                $this->error = 'MIME not allowed';

                return false;
            }
        }

        return true;
    }
}