<?php
/*-----------------------------------------------------------------
！！！！警告！！！！
以下为系统文件，请勿修改
-----------------------------------------------------------------*/

//不能非法包含或直接执行
if (!defined('IN_BAIGO')) {
    exit('Access Denied');
}

fn_include(BG_PATH_FUNC . 'http.func.php'); //载入模板类

/*-------------管理员控制器-------------*/
class CONTROL_CONSOLE_REQUEST_OPT {

    private $is_super = false;

    function __construct() { //构造函数
        $this->config   = $GLOBALS['obj_base']->config;

        $this->general_console      = new GENERAL_CONSOLE();
        $this->general_console->dspType = 'result';
        $this->general_console->chk_install();

        $this->adminLogged      = $this->general_console->ssin_begin(); //获取已登录信息
        $this->general_console->is_admin($this->adminLogged);

        $this->obj_tpl          = $this->general_console->obj_tpl;

        $this->tplData = array(
            'adminLogged' => $this->adminLogged
        );

        if ($this->adminLogged['admin_type'] == 'super') {
            $this->is_super = true;
        }

        $this->act = fn_getSafe($GLOBALS['route']['bg_act'], 'text', 'base');

        $this->mdl_opt          = new MODEL_OPT(); //设置管理组模型
    }


    function ctrl_chkver() {
        if (!isset($this->adminLogged['admin_allow']['opt']['chkver']) && !$this->is_super) {
            $_arr_tplData = array(
                'rcode' => 'x040301',
            );
            $this->obj_tpl->tplDisplay('result', $_arr_tplData);
        }

        $this->mdl_opt->chk_ver(true, 'manual');

        $_arr_tplData = array(
            'rcode' => 'y040402',
        );
        $this->obj_tpl->tplDisplay('result', $_arr_tplData);
    }


    function ctrl_dbconfig() {
        if (!isset($this->adminLogged['admin_allow']['opt']['dbconfig']) && !$this->is_super) {
            $_arr_tplData = array(
                'rcode' => 'x040301',
            );
            $this->obj_tpl->tplDisplay('result', $_arr_tplData);
        }

        $_arr_dbconfigInput = $this->mdl_opt->input_dbconfig();

        if ($_arr_dbconfigInput['rcode'] != 'ok') {
            $this->obj_tpl->tplDisplay('result', $_arr_dbconfigInput);
        }

        $_arr_return = $this->mdl_opt->mdl_dbconfig();

        $this->obj_tpl->tplDisplay('result', $_arr_return);
    }


    function ctrl_submit() {
        if (!isset($this->adminLogged['admin_allow']['opt'][$this->act]) && !$this->is_super) {
            $_arr_tplData = array(
                'rcode' => 'x040301',
            );
            $this->obj_tpl->tplDisplay('result', $_arr_tplData);
        }

        $_num_countSrc = 0;

        foreach ($this->obj_tpl->opt[$this->act]['list'] as $_key=>$_value) {
            if ($_value['min'] > 0) {
                $_num_countSrc++;
            }
        }

        $_arr_const = $this->mdl_opt->input_const($this->act);

        $_num_countInput = count(array_filter($_arr_const));

        if ($_num_countInput < $_num_countSrc) {
            $_arr_tplData = array(
                'rcode' => 'x030204',
            );
            $this->obj_tpl->tplDisplay('result', $_arr_tplData);
        }

        $_arr_return = $this->mdl_opt->mdl_const($this->act);

        if ($_arr_return['rcode'] != 'y040101') {
            $this->obj_tpl->tplDisplay('result', $_arr_return);
        }

        $_arr_tplData = array(
            'rcode' => 'y040401',
        );
        $this->obj_tpl->tplDisplay('result', $_arr_tplData);
    }
}
