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
class CONTROL_CONSOLE_UI_OPT {

    private $is_super = false;

    function __construct() { //构造函数
        $this->config   = $GLOBALS['obj_base']->config;

        $this->general_console      = new GENERAL_CONSOLE();
        $this->general_console->chk_install();

        $this->adminLogged      = $this->general_console->ssin_begin(); //获取已登录信息
        $this->general_console->is_admin($this->adminLogged);

        $this->obj_tpl          = $this->general_console->obj_tpl;

        if ($this->adminLogged['admin_type'] == 'super') {
            $this->is_super = true;
        }

        $this->act = fn_getSafe($GLOBALS['route']['bg_act'], 'text', 'base');

        if (!array_key_exists($this->act, $this->obj_tpl->opt)) {
            $this->act = 'base';
        }

        $this->obj_dir          = new CLASS_DIR();
        $this->mdl_opt          = new MODEL_OPT(); //设置管理组模型

        $this->tplData = array(
            'adminLogged'   => $this->adminLogged,
            'act'           => $this->act,
        );
    }



    function ctrl_chkver() {
        if (!isset($this->adminLogged['admin_allow']['opt']['chkver']) && !$this->is_super) {
            $this->tplData['rcode'] = 'x040301';
            $this->obj_tpl->tplDisplay('error', $this->tplData);
        }

        $this->tplData['latest_ver']    = $this->mdl_opt->chk_ver();
        $this->tplData['installed_pub'] = strtotime(PRD_SSO_PUB);

        $this->obj_tpl->tplDisplay('opt_chkver', $this->tplData);
    }


    function ctrl_dbconfig() {
        if (!isset($this->adminLogged['admin_allow']['opt']['dbconfig']) && !$this->is_super) {
            $this->tplData['rcode'] = 'x040301';
            $this->obj_tpl->tplDisplay('error', $this->tplData);
        }

        $this->obj_tpl->tplDisplay('opt_dbconfig', $this->tplData);
    }


    function ctrl_form() {
        if (!isset($this->adminLogged['admin_allow']['opt'][$this->act]) && !$this->is_super) {
            $this->tplData['rcode'] = 'x040301';
            $this->obj_tpl->tplDisplay('error', $this->tplData);
        }

        if ($this->act == 'base') {
            $this->tplData['tplRows']     = $this->obj_dir->list_dir(BG_PATH_TPL . 'personal' . DS);

            $_arr_timezoneRows  = fn_include(BG_PATH_INC . 'timezone.inc.php');

            $this->obj_tpl->lang['timezone']        = fn_include(BG_PATH_LANG . $this->config['lang'] . DS . 'timezone.php');
            $this->obj_tpl->lang['timezoneJson']    = json_encode($this->obj_tpl->lang['timezone']);

            $_arr_timezone[] = '';

            if (stristr(BG_SITE_TIMEZONE, '/')) {
                $_arr_timezone = explode('/', BG_SITE_TIMEZONE);
            }

            $this->tplData['timezoneRows']      = $_arr_timezoneRows;
            $this->tplData['timezoneRowsJson']  = json_encode($_arr_timezoneRows);
            $this->tplData['timezoneType']      = $_arr_timezone[0];
        }

        $this->obj_tpl->tplDisplay('opt_form', $this->tplData);
    }
}
