<?php
/*-----------------------------------------------------------------
！！！！警告！！！！
以下为系统文件，请勿修改
-----------------------------------------------------------------*/

//不能非法包含或直接执行
if (!defined('IN_BAIGO')) {
    exit('Access Denied');
}


/*-------------安装通用类-------------*/
class GENERAL_HELP {

    public $config;

    function __construct() { //构造函数
        $this->config   = $GLOBALS['obj_base']->config;

        $this->obj_tpl  = new CLASS_TPL(BG_PATH_TPLSYS . 'help' . DS . BG_DEFAULT_UI); //初始化视图对象

        $this->obj_tpl->help = fn_include(BG_PATH_INC . 'help.inc.php');

        //语言文件
        $this->obj_tpl->lang = array(
            'rcode'     => fn_include(BG_PATH_LANG . $this->config['lang'] . DS . 'rcode.php'), //返回代码
            'common'    => fn_include(BG_PATH_LANG . $this->config['lang'] . DS . 'common.php'),
        );

        if (file_exists(BG_PATH_LANG . $this->config['lang'] . DS . 'help' . DS . $GLOBALS['route']['bg_mod'] . '.php')) {
            $this->obj_tpl->lang['mod'] = fn_include(BG_PATH_LANG . $this->config['lang'] . DS . 'help' . DS . $GLOBALS['route']['bg_mod'] . '.php');
        }
    }
}
