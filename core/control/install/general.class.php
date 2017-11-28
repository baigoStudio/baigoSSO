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
class GENERAL_INSTALL {

    public $config;

    function __construct() { //构造函数
        $this->config   = $GLOBALS['obj_base']->config;

        $this->obj_tpl  = new CLASS_TPL(BG_PATH_TPLSYS . 'install' . DS . BG_DEFAULT_UI); //初始化视图对象

        $this->obj_tpl->phplib = fn_include(BG_PATH_INC . 'phplib.inc.php');
        $this->obj_tpl->opt = $GLOBALS['obj_config']->arr_opt; //系统设置配置文件

        $this->obj_tpl->setModule();

        //语言文件
        $this->obj_tpl->lang = array(
            'common'    => fn_include(BG_PATH_LANG . $this->config['lang'] . DS . 'common.php'), //通用
            'phplib'    => fn_include(BG_PATH_LANG . $this->config['lang'] . DS . 'phplib.php'), //菜单
            'opt'       => fn_include(BG_PATH_LANG . $this->config['lang'] . DS . 'opt.php'), //系统设置
            'rcode'     => fn_include(BG_PATH_LANG . $this->config['lang'] . DS . 'rcode.php'), //返回代码
        );

        if (file_exists(BG_PATH_LANG . $this->config['lang'] . DS . 'install' . DS . $GLOBALS['route']['bg_mod'] . '.php')) {
            $this->obj_tpl->lang['mod'] = fn_include(BG_PATH_LANG . $this->config['lang'] . DS . 'install' . DS . $GLOBALS['route']['bg_mod'] . '.php');
        }
    }
}
