<?php
/*-----------------------------------------------------------------
！！！！警告！！！！
以下为系统文件，请勿修改
-----------------------------------------------------------------*/

//不能非法包含或直接执行
if (!defined('IN_BAIGO')) {
    exit('Access Denied');
}

/*-------------登录控制器-------------*/
class CONTROL_CONSOLE_UI_LOGIN {

    function __construct() { //构造函数
        $this->general_console  = new GENERAL_CONSOLE();
        $this->general_console->chk_install();

        $this->obj_tpl      = $this->general_console->obj_tpl;

        $this->mdl_admin    = new MODEL_ADMIN(); //设置管理员模型
        $this->mdl_user     = new MODEL_USER(); //设置管理员模型
    }


    /*============登录界面============
    无返回
    */
    function ctrl_login() {
        $_str_forward     = fn_getSafe(fn_get('forward'), 'txt', '');
        $_str_rcode       = fn_getSafe(fn_get('rcode'), 'txt', '');

        $_str_forward = fn_forward($_str_forward, 'decode');

        if (fn_isEmpty($_str_forward)) {
            $_str_forward = BG_URL_CONSOLE . 'index.php';
        }

        $_arr_tplData = array(
            'forward'    => $_str_forward,
            'rcode'      => $_str_rcode,
        );

        $this->obj_tpl->tplDisplay('login', $_arr_tplData);
    }


    /*============登出============
    无返回
    */
    function ctrl_logout() {
        $this->general_console->ssin_end();

        header('Location: ' . BG_URL_CONSOLE . 'index.php');
    }
}
