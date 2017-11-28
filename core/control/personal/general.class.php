<?php
/*-----------------------------------------------------------------
！！！！警告！！！！
以下为系统文件，请勿修改
-----------------------------------------------------------------*/

//不能非法包含或直接执行
if (!defined('IN_BAIGO')) {
    exit('Access Denied');
}


/*-------------控制中心通用类-------------*/
class GENERAL_PERSONAL {

    public $config;
    public $dspType = '';

    function __construct() { //构造函数
        $this->config   = $GLOBALS['obj_base']->config;

        //$this->obj_dir = new CLASS_DIR();

        $this->obj_tpl  = new CLASS_TPL(BG_PATH_TPL . 'personal' . DS . BG_SITE_TPL); //初始化视图对象

        //语言文件
        $this->obj_tpl->lang = array(
            'common'        => fn_include(BG_PATH_LANG . $this->config['lang'] . DS . 'common.php'), //通用
            'rcode'         => fn_include(BG_PATH_LANG . $this->config['lang'] . DS . 'rcode.php'), //返回代码
        );

        if (file_exists(BG_PATH_LANG . $this->config['lang'] . DS . 'personal' . DS . $GLOBALS['route']['bg_mod'] . '.php')) {
            $this->obj_tpl->lang['mod'] = fn_include(BG_PATH_LANG . $this->config['lang'] . DS . 'personal' . DS . $GLOBALS['route']['bg_mod'] . '.php');
        }
    }
}
