<?php
/*-----------------------------------------------------------------
！！！！警告！！！！
以下为系统文件，请勿修改
-----------------------------------------------------------------*/

namespace ginkgo;

//不能非法包含或直接执行
defined('IN_GINKGO') or exit('Access Denied');

/*-------------上传类-------------*/
class Upload extends File {

    protected static $instance;

    private $limitSize = 0; //允许上传大小

    protected function __construct($config = '') {
        $this->init($config);
    }

    protected function __clone() {

    }

    public static function instance() {
        if (Func::isEmpty(static::$instance)) {
            static::$instance = new static();
        }

        return static::$instance;
    }

    public function create($name) {
        if (!isset($_FILES) || !isset($_FILES[$name])) {
            $this->error = 'No files uploaded';

            return false;
        }

        $_arr_fileInfo = $_FILES[$name];

        //上传文件校验
        if (!isset($_arr_fileInfo['tmp_name']) || !is_uploaded_file($_arr_fileInfo['tmp_name'])) {
            $this->error = 'No files uploaded';

            return false;
        }

        if (isset($_arr_fileInfo['error']) && $_arr_fileInfo['error'] > 0) {
            $this->errorProcess($_arr_fileInfo['error']);

            return false;
        }

        $_str_mime  = $this->getMime($_arr_fileInfo['tmp_name'], $_arr_fileInfo['type']);
        $_str_ext   = $this->getExt($_arr_fileInfo['name'], $_str_mime);

        if (!$this->checkFile($_str_ext, $_str_mime)) {
            return false;
        }

        if ($_arr_fileInfo['size'] > $this->limitSize) { //是否超过尺寸
            $this->error = 'Upload file size exceeds the settings';

            return false;
        }

        $_arr_fileInfo['name']  = Func::safe($_arr_fileInfo['name']);
        $_arr_fileInfo['ext']   = $_str_ext;
        $_arr_fileInfo['mime']  = $_str_mime;

        $this->fileInfo = $_arr_fileInfo;

        return $_arr_fileInfo;
    }


    /** 移动文件
     * move function.
     *
     * @access public
     * @param mixed $tm_time 上传时间
     * @param mixed $num_attachId 文件ID
     * @return void
     */
    function move($path_dir, $name = true, $replace = true) {
        if (!$this->dirMk($path_dir)) { //建目录失败
            $this->error = 'Failed to create directory';

            return false;
        }

        $name = $this->genFilename($name);

        if (Func::isEmpty($name)) {
            $this->error = 'Missing filename';

            return false;
        }

        $_str_path = Func::fixDs($path_dir) . $name;

        if (!$replace && Func::isFile($_str_path)) {
            $this->error = 'Has the same filename: ' . $_str_path;

            return false;
        }

        if (!move_uploaded_file($this->fileInfo['tmp_name'], $_str_path)) {
            $this->error = 'Failed to move uploaded file';

            return false;
        }

        if (Func::isFile($this->fileInfo['tmp_name'])) {
            $this->fileDelete($this->fileInfo['tmp_name']);
        }

        return $_str_path;
    }


    function setLimit($size) {
        $this->limitSize = $size;
    }


    public function ext() {
        return $this->fileInfo['ext'];
    }


    public function mime() {
        return $this->fileInfo['mime'];
    }


    public function name() {
        return $this->fileInfo['name'];
    }

    public function size() {
        return $this->fileInfo['size'];
    }

    function getError() {
        return $this->error;
    }


    /** 上传初始化
     * upload_init function.
     *
     * @access public
     * @param mixed $arr_mime 允许上传类型数组
     * @param mixed $arr_thumb 缩略图数组
     * @return void
     */
    private function init($config = array()) {
        $_arr_config   = Config::get('upload', 'var_extra');

        if (!Func::isEmpty($config)) {
            $_arr_config = array_replace_recursive($_arr_config, $config);
        }

        $_arr_config['limit_unit'] = strtolower($_arr_config['limit_unit']);

        switch ($_arr_config['limit_unit']) { //初始化单位
            case 'gb':
                $_num_sizeUnit = 1024 * 1024 * 1024;
            break;

            case 'mb':
                $_num_sizeUnit = 1024 * 1024;
            break;

            case 'kb':
                $_num_sizeUnit = 1024;
            break;

            default:
                $_num_sizeUnit = 1;
            break;
        }

        if ($this->limitSize < 1) {
            $this->limitSize = $_arr_config['limit_size'] * $_num_sizeUnit;
        }
    }


    private function errorProcess($error_no) {
        switch ($error_no) {
            case UPLOAD_ERR_INI_SIZE:
                $this->error = 'Upload file size exceeds the php.ini settings';
            break;

            case UPLOAD_ERR_FORM_SIZE:
                $this->error = 'Upload file size exceeds the form settings';
            break;

            case UPLOAD_ERR_PARTIAL:
                $this->error = 'Only the portion of file is uploaded';
            break;

            case UPLOAD_ERR_NO_FILE:
                $this->error = 'No files uploaded';
            break;

            case UPLOAD_ERR_NO_TMP_DIR:
                $this->error = 'Upload temp dir not found';
            break;

            case UPLOAD_ERR_CANT_WRITE:
                $this->error = 'File write error';
            break;

            default:
                $this->error = 'Unknown upload error';
            break;
        }
    }


    /**
     * __destruct function.
     *
     * @access public
     * @return void
     */
    function __destruct() { //析构函数

    }
}
