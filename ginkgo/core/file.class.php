<?php
/*-----------------------------------------------------------------
！！！！警告！！！！
以下为系统文件，请勿修改
-----------------------------------------------------------------*/

namespace ginkgo;

//不能非法包含或直接执行
defined('IN_GINKGO') or exit('Access denied');

/*-------------文件操作类-------------*/
class File {

    protected static $instance;
    protected $error;
    protected $rule = 'md5';
    protected $mimeRows = array();
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

    public static function instance() {
        if (Func::isEmpty(static::$instance)) {
            static::$instance = new static();
        }

        return static::$instance;
    }

    /**
     * dirList function.
     *
     * @access public
     * @param mixed $path
     * @return void
     */
    function dirList($path, $ext = '') {
        $_arr_return  = array();
        $_arr_dir     = array();

        if (is_dir($path)) {
            $_arr_dir = scandir($path);
        } else {
            $this->error = 'Not a directory';
            return array();
        }

        if (!Func::isEmpty($_arr_dir)) {
            foreach ($_arr_dir as $_key=>$_value) {
                if ($_value != '.' && $_value != '..') {
                    $_str_pathFull  = Func::fixDs($path) . $_value;

                    $_str_type = filetype($_str_pathFull);

                    $_arr_returnTmp = array(
                        'name' => $_value,
                        'path' => $_str_pathFull,
                        'type' => $_str_type,
                    );

                    if ($_str_type == 'dir') {
                        $_arr_returnTmp['sub'] = $this->dirList($_str_pathFull, $ext);

                        $_arr_return[] = $_arr_returnTmp;
                    } else {
                        if (Func::isEmpty($ext)) {
                            $_arr_return[] = $_arr_returnTmp;
                        } else {
                            $_str_ext = pathinfo($_value, PATHINFO_EXTENSION);

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
     * @param mixed $path
     * @return void
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
                    $this->error  = 'Failed to create directory';
                    $_bool_status = false; //失败
                }
            } else {
                $this->error  = 'Failed to create directory';
                $_bool_status = false;
            }
        }

        //print_r($old_umask);
        umask($old_umask);

        return $_bool_status;
    }


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

            if ($_value['type'] == 'file' && Func::isFile($_value['path'])) {
                copy($_value['path'], $_str_pathFull);
            } else if (is_dir($_value['path'])) {
                $this->dirCopy($_value['path'], $_str_pathFull);
            }
        }

        return true;
    }

    /**
     * dirDelete function.
     *
     * @access public
     * @param mixed $path
     * @return void
     */
    function dirDelete($path) {
        if (!is_dir($path)) {
            $this->error = 'Directory not found';
            return false;
        }

        //删除目录及目录里所有的目录和文件
        $_arr_dir = $this->dirList($path); //逐级列出

        foreach ($_arr_dir as $_key=>$_value) {
            if (substr($path, -1) == DS) {
                $_str_pathFull  = $path . $_value['name'];
            } else {
                $_str_pathFull  = $path . DS . $_value['name'];
            }

            if ($_value['type'] == 'file' && Func::isFile($_str_pathFull)) {
                $this->fileDelete($_str_pathFull);  //删除
            } else if (is_dir($_str_pathFull)) {
                $this->dirDelete($_str_pathFull); //递归
            }
        }

        return rmdir($path);
    }

    function fileRead($path) {
        if (!Func::isFile($path)) {
            $this->error = 'File not found';
            return false;
        }

        return file_get_contents($path);
    }

    function fileMove($path_src, $path_dst) {
        if (!Func::isFile($path_src)) {
            $this->error = 'Source file not found';
            return false;
        }

        if (!$this->dirMk(dirname($path_dst))) {
            return false;
        }
        return rename($path_src, $path_dst);
    }

    function fileWrite($path, $content, $append = false) {
        if (!$this->dirMk(dirname($path))) {
            return false;
        }

        if ($append) {
            $append = FILE_APPEND;
        }

        return file_put_contents($path, $content, $append);
    }

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

    function fileDelete($path) {
        if (!Func::isFile($path)) {
            $this->error = 'File not found';
            return false;
        }

        return unlink($path);
    }


    function getMime($path, $type = false) {
        $_obj_finfo = new \finfo();

        $_str_mime  = $_obj_finfo->file($path, FILEINFO_MIME_TYPE);

        if ($_str_mime == "CDF V2 Document, corrupt: Can't expand summary_info") { //如果无法识别则以浏览器报告的 mime 为准
            if (!Func::isEmpty($type) && is_string($type)) {
                $_str_mime = $type;
            }
        }

        return $_str_mime;
    }


    function getExt($path, $mime = false) {
        $_str_ext = strtolower(pathinfo($path, PATHINFO_EXTENSION)); //取得扩展名

        //扩展名与 MIME 不符的情况下, 反向查找, 如果存在, 则更改扩展名
        if ($mime) {
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


    function setMime($mime, $value = array()) {
        if (is_array($mime)) {
            $this->mimeRows = array_replace_recursive($this->mimeRows, $mime);
        } else {
            $this->mimeRows[$mime] = $value;
        }
    }


    function rule($rule) {
        $this->rule = $rule;

        return $this;
    }

    protected function genFilename($name = true) {
        if ($name === true) {
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
        } else if ($name === false) {
            $name = $this->fileInfo['name'];
        }

        return $name;
    }


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