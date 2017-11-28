<?php
/*-----------------------------------------------------------------
！！！！警告！！！！
以下为系统文件，请勿修改
-----------------------------------------------------------------*/

//不能非法包含或直接执行
if (!defined('IN_BAIGO')) {
    exit('Access Denied');
}

/*-------------管理员控制器-------------*/
class CONTROL_CONSOLE_UI_PROFILE {

    function __construct() { //构造函数
        $this->general_console      = new GENERAL_CONSOLE();
        $this->general_console->chk_install();

        $this->adminLogged      = $this->general_console->ssin_begin(); //获取已登录信息
        $this->general_console->is_admin($this->adminLogged);

        $this->obj_tpl          = $this->general_console->obj_tpl;

        $this->mdl_admin        = new MODEL_ADMIN(); //设置管理组模型
        $this->mdl_user         = new MODEL_USER(); //设置管理组模型

        $this->tplData = array(
            'adminLogged'   => $this->adminLogged,
            'status'        => $this->mdl_admin->arr_status,
            'type'          => $this->mdl_admin->arr_type,
        );
    }


    function ctrl_mailbox() {
        $this->obj_tpl->tplDisplay('profile_mailbox', $this->tplData);
    }


    function ctrl_qa() {
        $this->obj_tpl->tplDisplay('profile_qa', $this->tplData);
    }


    function ctrl_pass() {
        $this->obj_tpl->tplDisplay('profile_pass', $this->tplData);
    }

    /**
     * ctrl_personal function.
     *
     * @access public
     */
    function ctrl_info() {
        $this->obj_tpl->tplDisplay('profile_info', $this->tplData);
    }
}
