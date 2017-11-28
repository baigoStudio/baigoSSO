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
    public $phplib  = array();
    public $help    = array();

    function __construct($str_pathTpl = '') { //构造函数
        $this->config   = $GLOBALS['obj_base']->config;

        $this->pathTpl  = $str_pathTpl;

        $this->common['tokenRow']   = fn_token(); //生成令牌
    }


    function setModule() {
        unset($this->opt['base']['list']['BG_SITE_SSIN']);
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

        if (file_exists($this->pathTpl . DS . $str_tpl . '.php')) {
            require($this->pathTpl . DS . $str_tpl . '.php');
            exit;
        } else {
            if (defined('BG_DEBUG_SYS') && BG_DEBUG_SYS > 0) {
                $_str_msg = 'Template &quot;' . $this->pathTpl . DS . $str_tpl . '.php&quot; not exists';
            } else {
                $_str_msg = 'Template not exists';
            }

            exit('{"rcode":"x","msg":"Fatal Error: ' . $_str_msg . '!"}');
        }
    }
}
