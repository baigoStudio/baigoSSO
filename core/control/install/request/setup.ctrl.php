<?php
/*-----------------------------------------------------------------
！！！！警告！！！！
以下为系统文件，请勿修改
-----------------------------------------------------------------*/

//不能非法包含或直接执行
if (!defined('IN_BAIGO')) {
    exit('Access Denied');
}


class CONTROL_INSTALL_REQUEST_SETUP {

    function __construct() { //构造函数
        //$this->config   = $GLOBALS['obj_base']->config;

        $this->mdl_opt          = new MODEL_OPT();

        $this->general_install      = new GENERAL_INSTALL();

        $this->obj_tpl          = $this->general_install->obj_tpl;

        $this->obj_dir          = new CLASS_DIR();
        $this->obj_dir->mk_dir(BG_PATH_CACHE . 'ssin');

        $this->setup_init();
    }


    function ctrl_dbconfig() {
        $_arr_dbconfigInput = $this->mdl_opt->input_dbconfig();

        if ($_arr_dbconfigInput['rcode'] != 'ok') {
            $this->obj_tpl->tplDisplay('result', $_arr_dbconfigInput);
        }

        $_arr_tplData = $this->mdl_opt->mdl_dbconfig();

        $this->obj_tpl->tplDisplay('result', $_arr_tplData);
    }


    function ctrl_submit() {
        $this->check_db();

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
                'rcode'     => 'x030204',
            );
            $this->obj_tpl->tplDisplay('result', $_arr_tplData);
        }

        $_arr_return = $this->mdl_opt->mdl_const($this->act);

        if ($_arr_return['rcode'] != 'y040101') {
            $this->obj_tpl->tplDisplay('result', $_arr_return);
        }

        $_arr_tplData = array(
            'rcode'     => 'y030405',
        );
        $this->obj_tpl->tplDisplay('result', $_arr_tplData);
    }


    function ctrl_auth() {
        $this->check_db();

        $_mdl_admin_install = new MODEL_ADMIN_INSTALL();
        $_mdl_user          = new MODEL_USER();

        $_arr_adminInput = $_mdl_admin_install->input_install_auth();

        if ($_arr_adminInput['rcode'] != 'ok') {
            $this->obj_tpl->tplDisplay('result', $_arr_adminInput);
        }

        //检验用户名是否重复
        $_arr_userRow = $_mdl_user->mdl_read($_arr_adminInput['admin_name'], 'user_name');

        if ($_arr_userRow['rcode'] != 'y010102') {
            $_arr_tplData = array(
                'rcode'     => 'x020207',
            );
            $this->obj_tpl->tplDisplay('result', $_arr_tplData);
        }

        $_arr_adminRow = $_mdl_admin_install->mdl_read($_arr_userRow['user_id']);
        if ($_arr_adminRow['rcode'] == 'y020102') {
            $_arr_tplData = array(
                'rcode'     => 'x020205',
            );
            $this->obj_tpl->tplDisplay('result', $_arr_tplData);
        }

        $_arr_adminInput['admin_id'] = $_arr_userRow['user_id'];

        $_arr_adminReturn = $_mdl_admin_install->mdl_submit($_arr_adminInput);

        $this->obj_tpl->tplDisplay('result', $_arr_adminReturn);
    }


    function ctrl_admin() {
        $this->check_db();

        $_mdl_admin_install = new MODEL_ADMIN_INSTALL();
        $_mdl_user_api      = new MODEL_USER_API();

        $_arr_adminInput = $_mdl_admin_install->input_install_add();

        if ($_arr_adminInput['rcode'] != 'ok') {
            $this->obj_tpl->tplDisplay('result', $_arr_adminInput);
        }

        //检验用户名是否重复
        $_arr_userRow = $_mdl_user_api->mdl_read($_arr_adminInput['admin_name'], 'user_name');

        if ($_arr_userRow['rcode'] == 'y010102') {
            $_arr_adminRow = $_mdl_admin_install->mdl_read($_arr_userRow['user_id']);
            if ($_arr_adminRow['rcode'] == 'y020102') {
                $_arr_tplData = array(
                    'rcode'     => 'x020205',
                );
                $this->obj_tpl->tplDisplay('result', $_arr_tplData);
            } else {
                $_arr_tplData = array(
                    'rcode'     => 'x020206',
                );
                $this->obj_tpl->tplDisplay('result', $_arr_tplData);
            }
        }

        $_arr_userSubmit = array(
            'user_name'     => $_arr_adminInput['admin_name'],
            'user_pass'     => fn_baigoCrypt($_arr_adminInput['admin_pass'], $_arr_adminInput['admin_name']),
            'user_status'   => 'enable',
            'user_nick'     => $_arr_adminInput['admin_nick'],
            'user_note'     => $_arr_adminInput['admin_note'],
        );

        $_arr_userRow   = $_mdl_user_api->mdl_reg($_arr_userSubmit);

        if ($_arr_userRow['rcode'] != 'y010101') {
            $this->obj_tpl->tplDisplay('result', $_arr_userRow);
        }

        $_arr_adminInput['admin_id'] = $_arr_userRow['user_id'];

        $_arr_adminReturn = $_mdl_admin_install->mdl_submit($_arr_adminInput);

        $this->obj_tpl->tplDisplay('result', $_arr_adminReturn);
    }


    function ctrl_over() {
        $this->check_db();

        $_arr_return = $this->mdl_opt->mdl_over();

        if ($_arr_return['rcode'] != 'y040101') {
            $this->obj_tpl->tplDisplay('result', $_arr_return);
        }

        $_arr_tplData = array(
            'rcode'     => 'y030408',
        );
        $this->obj_tpl->tplDisplay('result', $_arr_return);
    }


    function ctrl_chkname() {
        $this->check_db();

        $_str_adminName = fn_getSafe(fn_get('admin_name'), 'txt', '');

        if (fn_isEmpty($_str_adminName)) {
            $_arr_tplData = array(
                'rcode'     => 'x010201',
            );
            $this->obj_tpl->tplDisplay('result', $_arr_tplData);
        }

        $_mdl_user  = new MODEL_USER();
        $_mdl_admin = new MODEL_ADMIN();

        $_arr_userRow = $_mdl_user->mdl_read($_str_adminName, 'user_name');
        if ($_arr_userRow['rcode'] == 'y010102') {
            $_arr_adminRow = $_mdl_admin->mdl_read($_arr_userRow['user_id']);
            if ($_arr_adminRow['rcode'] == 'y020102') {
                $_arr_tplData = array(
                    'rcode'     => 'x020205',
                );
                $this->obj_tpl->tplDisplay('result', $_arr_tplData);
            } else {
                $_arr_tplData = array(
                    'rcode'     => 'x020206',
                );
                $this->obj_tpl->tplDisplay('result', $_arr_tplData);
            }
        }

        $_arr_tplData = array(
            'msg'     => 'ok',
        );
        $this->obj_tpl->tplDisplay('result', $_arr_tplData);
    }


    function ctrl_chkauth() {
        $this->check_db();

        $_str_adminName = fn_getSafe(fn_get('admin_name'), 'txt', '');

        if (fn_isEmpty($_str_adminName)) {
            $_arr_tplData = array(
                'rcode'     => 'x010201',
            );
            $this->obj_tpl->tplDisplay('result', $_arr_tplData);
        }

        $_mdl_user  = new MODEL_USER();
        $_mdl_admin = new MODEL_ADMIN();

        //检验用户名是否重复
        $_arr_userRow = $_mdl_user->mdl_read($_str_adminName, 'user_name');

        if ($_arr_userRow['rcode'] != 'y010102') {
            $_arr_tplData = array(
                'rcode'     => 'x020207',
            );
            $this->obj_tpl->tplDisplay('result', $_arr_tplData);
        }

        $_arr_adminRow = $_mdl_admin->mdl_read($_arr_userRow['user_id']);
        if ($_arr_adminRow['rcode'] == 'y020102') {
            $_arr_tplData = array(
                'rcode'     => 'x020205',
            );
            $this->obj_tpl->tplDisplay('result', $_arr_tplData);
        }


        $_arr_tplData = array(
            'msg'     => 'ok',
        );
        $this->obj_tpl->tplDisplay('result', $_arr_tplData);
    }


    private function check_db() {
        if (!defined('BG_DB_HOST') || fn_isEmpty(BG_DB_HOST) || !defined('BG_DB_NAME') || fn_isEmpty(BG_DB_NAME) || !defined('BG_DB_PASS') || fn_isEmpty(BG_DB_PASS) || !defined('BG_DB_CHARSET') || fn_isEmpty(BG_DB_CHARSET)) {
            $_arr_tplData = array(
                'rcode' => 'x030404',
            );

            $this->obj_tpl->tplDisplay('result', $_arr_tplData);
        }
    }


    private function setup_init() {
        $_str_rcode = '';

        if (file_exists(BG_PATH_CONFIG . 'installed.php')) { //如果新文件存在
            fn_include(BG_PATH_CONFIG . 'installed.php');  //载入
            $_str_rcode = 'x030403';
        } else if (file_exists(BG_PATH_CONFIG . 'is_install.php')) { //如果旧文件存在
            $this->obj_dir->copy_file(BG_PATH_CONFIG . 'is_install.php', BG_PATH_CONFIG . 'installed.php'); //拷贝
            fn_include(BG_PATH_CONFIG . 'installed.php');  //载入
            $_str_rcode = 'x030403';
        }

        if (defined('BG_INSTALL_PUB') && PRD_SSO_PUB > BG_INSTALL_PUB) {
            $_str_rcode = 'x030411';
        }

        if (!fn_isEmpty($_str_rcode)) {
            $_arr_tplData = array(
                'rcode' => $_str_rcode,
            );
            $this->obj_tpl->tplDisplay('result', $_arr_tplData);
        }

        $_arr_phplibRow     = get_loaded_extensions();
        $_num_errCount   = 0;

        foreach ($this->obj_tpl->phplib as $_key=>$_value) {
            if (!in_array($_key, $_arr_phplibRow)) {
                $_num_errCount++;
            }
        }

        if ($_num_errCount > 0) {
            $_arr_tplData = array(
                'rcode' => 'x030413',
            );
            $this->obj_tpl->tplDisplay('result', $_arr_tplData);
        }

        $this->act = fn_getSafe($GLOBALS['route']['bg_act'], 'txt', 'phplib');
    }
}
