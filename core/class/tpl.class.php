<?php
/*-----------------------------------------------------------------
！！！！警告！！！！
以下为系统文件，请勿修改
-----------------------------------------------------------------*/

//不能非法包含或直接执行
if (!defined('IN_BAIGO')) {
    exit('Access Denied');
}


/*-------------模板类-------------*/
class CLASS_TPL {

    public $common  = array();
    public $lang    = array();
    public $opt     = array();
    public $help    = array();

    function __construct($str_pathTpl = '') { //构造函数
        $this->config   = $GLOBALS['obj_base']->config;

        $this->pathTpl  = $str_pathTpl;

        $this->common['tokenRow']   = fn_token(); //生成令牌
    }

    /** 显示界面
     * tplDisplay function.
     *
     * @access public
     * @param mixed $str_tpl
     * @param string $arr_tplData (default: '')
     * @return void
     */
    function tplDisplay($str_tpl, $arr_tplData = array()) {
        $this->tplData  = $arr_tplData;
        $_str_return    = '';

        if (file_exists($this->pathTpl . DS . $str_tpl . '.php')) {
            ob_start();
            require($this->pathTpl . DS . $str_tpl . '.php');
            $_str_return = ob_get_contents();
            ob_end_clean();
        } else {
            if (BG_DEBUG_SYS > 0) {
                $_str_msg = 'Template &quot;' . $this->pathTpl . DS . $str_tpl . '.php&quot; does not exist';
            } else {
                $_str_msg = 'Template does not exist';
            }

            $_str_return = '{"rcode":"x","msg":"Fatal Error: ' . $_str_msg . '!"}';
        }

        if (isset($_SESSION)) {
            session_write_close();
        }

        if (isset($GLOBALS['obj_db'])) {
            $GLOBALS['obj_db']->close();
        }

        unset($GLOBALS);

        exit($_str_return);
    }
}
