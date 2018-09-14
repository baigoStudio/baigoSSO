<?php
/*-------------目录操作类-------------*/
class CLASS_FILE {

    public $status; //返回操作状态(成功/失败)
    public $perms = 0771;

    /**
     * dir_list function.
     *
     * @access public
     * @param mixed $str_path
     * @return void
     */
    function dir_list($str_path, $str_ext = '') {
        $this->dir_mk($str_path);

        $_arr_return  = array();
        $_arr_dir     = array();

        if (is_dir($str_path)) {
            $_arr_dir = scandir($str_path);
        }

        if (!fn_isEmpty($_arr_dir)) {
            foreach ($_arr_dir as $_key=>$_value) {
                if ($_value != '.' && $_value != '..') {
                    if (substr($str_path, -1) == DS) {
                        $_str_pathFull  = $str_path . $_value;
                    } else {
                        $_str_pathFull  = $str_path . DS . $_value;
                    }

                    $_str_type      = filetype($_str_pathFull);

                    $_arr_returnTmp = array(
                        'name' => $_value,
                        'path' => $_str_pathFull,
                        'type' => $_str_type,
                    );

                    if ($_str_type == 'dir') {
                        $_arr_returnTmp['sub'] = $this->dir_list($_str_pathFull, $str_ext);

                        $_arr_return[] = $_arr_returnTmp;
                    } else {
                        if (fn_isEmpty($str_ext)) {
                            $_arr_return[] = $_arr_returnTmp;
                        } else {
                            $_arr_pathinfo = pathinfo($_value);

                            if ($_arr_pathinfo['extension'] == $str_ext) {
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
     * dir_mk function.
     *
     * @access public
     * @param mixed $str_path
     * @return void
     */
    function dir_mk($str_path) {
        $old_umask = umask(0);

        if (stristr($str_path, '.')) {
            $str_path = dirname($str_path);
        }
        if (is_dir($str_path) || stristr($str_path, '.')) { //已存在
            $this->status = true;
        } else {
            //创建目录
            if ($this->dir_mk(dirname($str_path))) { //递归
                if (mkdir($str_path, $this->perms, true)) { //创建成功
                    $this->status = true;
                    //chmod($str_path, $this->perms);
                } else {
                    $this->status = false; //失败
                }
            } else {
                $this->status = false;
            }
        }

        //print_r($old_umask);
        umask($old_umask);

        return $this->status;
    }

    function dir_copy($str_src, $str_dst) {
        $this->dir_mk($str_dst);

        $_arr_dir = $this->dir_list($str_src); //逐级列出

        foreach ($_arr_dir as $_key=>$_value) {
            if (substr($str_dst, -1) == DS) {
                $_str_pathFull  = $str_dst . $_value['name'];
            } else {
                $_str_pathFull  = $str_dst . DS . $_value['name'];
            }

            if ($_value['type'] == 'file' && file_exists($_value['path'])) {
                copy($_value['path'], $_str_pathFull);
            } else if (is_dir($_value['path'])) {
                $this->dir_copy($_value['path'], $_str_pathFull);
            }
        }
    }

    /**
     * dir_del function.
     *
     * @access public
     * @param mixed $str_path
     * @return void
     */
    function dir_del($str_path) {

        //删除目录及目录里所有的目录和文件
        if (is_dir($str_path)) {
            $_arr_dir = $this->dir_list($str_path); //逐级列出

            foreach ($_arr_dir as $_key=>$_value) {
                if (substr($str_path, -1) == DS) {
                    $_str_pathFull  = $str_path . $_value['name'];
                } else {
                    $_str_pathFull  = $str_path . DS . $_value['name'];
                }

                if ($_value['type'] == 'file' && file_exists($_str_pathFull)) {
                    $this->file_del($_str_pathFull);  //删除
                } else if (is_dir($_str_pathFull)) {
                    $this->dir_del($_str_pathFull); //递归
                }
            }

            if (is_dir($str_path)) {
                rmdir($str_path);
            }
        }
    }

    function file_read($str_path) {
        return file_get_contents($str_path);
    }

    function file_put($str_path, $str_content) {
        $this->dir_mk($str_path);
        $_num_size = file_put_contents($str_path, $str_content);
        return $_num_size;
    }

    function file_copy($str_src, $str_dst) {
        $bool_return = false;
        $this->dir_mk($str_dst);
        if (file_exists($str_src) && is_dir(dirname($str_dst))) {
            $bool_return = copy($str_src, $str_dst);
        }
        return $bool_return;
    }

    function file_del($str_path) {
        $bool_return = false;
        if (file_exists($str_path)) {
            unlink($str_path);  //删除
            $bool_return = true;
        }
        return $bool_return;
    }
}