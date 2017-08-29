<?php
/*-----------------------------------------------------------------
！！！！警告！！！！
以下为系统文件，请勿修改
-----------------------------------------------------------------*/

//不能非法包含或直接执行
if (!defined('IN_BAIGO')) {
    exit('Access Denied');
}


/*-------------文章类-------------*/
class CONTROL_HELP_UI_HELP {

    private $obj_tpl;

    function __construct() { //构造函数
        $this->config   = $GLOBALS['obj_base']->config;
        $this->obj_tpl  = new CLASS_TPL(BG_PATH_TPLSYS . 'help' . DS . BG_DEFAULT_UI); //初始化视图对象

        $this->obj_tpl->help = fn_include(BG_PATH_INC . 'help.inc.php');

        $this->obj_tpl->lang = array(
            'rcode'     => fn_include(BG_PATH_LANG . $this->config['lang'] . DS . 'rcode.php'), //返回代码
            'common'    => fn_include(BG_PATH_LANG . $this->config['lang'] . DS . 'common.php'),
            'mod'       => fn_include(BG_PATH_LANG . $this->config['lang'] . DS . 'help' . DS . 'help.php'),
        );
    }

    function ctrl_show() {
        $this->mod      = fn_getSafe($GLOBALS['mod'], 'txt', 'intro');
        $this->act      = fn_getSafe($GLOBALS['act'], 'txt', 'outline');

        if (BG_DEFAULT_UI != 'default') {
            $_arr_config = str_ireplace('baigo', BG_DEFAULT_UI, $_arr_config);
        }

        $this->tplData = array(
            'mod'       => $this->mod,
            'act'       => $this->act,
            'content'   => $this->str_process(),
        );

        $this->obj_tpl->tplDisplay('help', $this->tplData);
    }

    private function str_process() {
        $_str_content = '';

        ob_start();

        if (file_exists(BG_PATH_HELP . $this->config['lang'] . DS . $this->mod . DS . $this->act . '.html')) {
            fn_include(BG_PATH_HELP . $this->config['lang'] . DS . $this->mod . DS . $this->act . '.html');
        } else {
            fn_include(BG_PATH_HELP . $this->config['lang'] . DS . 'intro' . DS . 'outline.html');
        }

        $_str_content = ob_get_contents();

        ob_end_clean();

        $_str_content = str_ireplace('{images}', BG_URL_STATIC . 'help' . DS . BG_DEFAULT_UI . DS . $this->mod . DS, $_str_content);
        $_str_content = str_ireplace('{BG_URL_HELP}', BG_URL_HELP, $_str_content);

        if (BG_DEFAULT_UI != 'default') {
            $_str_content = str_ireplace('baigo', BG_DEFAULT_UI, $_str_content);
        }

        return $_str_content;
    }
}
