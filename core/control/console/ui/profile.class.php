<?php
/*-----------------------------------------------------------------
！！！！警告！！！！
以下为系统文件，请勿修改
-----------------------------------------------------------------*/

//不能非法包含或直接执行
if (!defined("IN_BAIGO")) {
    exit("Access Denied");
}

/*-------------管理员控制器-------------*/
class CONTROL_CONSOLE_UI_PROFILE {

    function __construct() { //构造函数
        $this->obj_console      = new CLASS_CONSOLE();
        $this->obj_console->chk_install();

        $this->adminLogged      = $this->obj_console->ssin_begin(); //获取已登录信息
        $this->obj_console->is_admin($this->adminLogged);

        $this->obj_tpl          = $this->obj_console->obj_tpl;

        $this->tplData = array(
            "adminLogged" => $this->adminLogged
        );

        $this->mdl_admin        = new MODEL_ADMIN(); //设置管理组模型
        $this->mdl_user         = new MODEL_USER(); //设置管理组模型
    }


    function ctrl_mailbox() {
        $this->obj_tpl->tplDisplay("profile_mailbox", $this->tplData);
    }


    function ctrl_qa() {
        $this->obj_tpl->tplDisplay("profile_qa", $this->tplData);
    }


    function ctrl_pass() {
        $this->obj_tpl->tplDisplay("profile_pass", $this->tplData);
    }

    /**
     * ctrl_my function.
     *
     * @access public
     */
    function ctrl_info() {
        $this->obj_tpl->tplDisplay("profile_info", $this->tplData);
    }
}
