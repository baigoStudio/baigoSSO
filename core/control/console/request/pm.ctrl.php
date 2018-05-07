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
class CONTROL_CONSOLE_REQUEST_PM {

    private $is_super = false;

    function __construct() { //构造函数
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

        $this->mdl_pm           = new MODEL_PM(); //设置管理员模型
        $this->mdl_user         = new MODEL_USER(); //设置管理员模型
    }


    /**
     * ctrl_submit function.
     *
     * @access public
     */
    function ctrl_send() {
        if (!isset($this->adminLogged['admin_allow']['pm']['send']) && !$this->is_super) {
            $_arr_tplData = array(
                'rcode' => 'x110303',
            );
            $this->obj_tpl->tplDisplay('result', $_arr_tplData);
        }

        $_arr_pmSend = $this->mdl_pm->input_send();

        if ($_arr_pmSend['rcode'] != 'ok') {
            $this->obj_tpl->tplDisplay('result', $_arr_pmSend);
        }

        $_arr_userRow = $this->mdl_user->mdl_read($_arr_pmSend['pm_to'], 'user_name');
        if ($_arr_userRow['rcode'] != 'y010102') {
            $this->obj_tpl->tplDisplay('result', $_arr_userRow);
        }

        $_arr_pmRow = $this->mdl_pm->mdl_submit($_arr_userRow['user_id'], -1);

        $this->obj_tpl->tplDisplay('result', $_arr_pmRow);
    }


    function ctrl_bulk() {
        if (!isset($this->adminLogged['admin_allow']['pm']['bulk']) && !$this->is_super) {
            $_arr_tplData = array(
                'rcode' => 'x110303',
            );
            $this->obj_tpl->tplDisplay('result', $_arr_tplData);
        }

        $_arr_pmBulk = $this->mdl_pm->input_bulk();

        if ($_arr_pmBulk['rcode'] != 'ok') {
            $this->obj_tpl->tplDisplay('result', $_arr_pmBulk);
        }

        switch ($_arr_pmBulk['pm_bulk_type']) {
            case 'bulkUsers':
                if (stristr($_arr_pmBulk['pm_to_users'], '|')) {
                    $_arr_toUsers = explode('|', $_arr_pmBulk['pm_to_users']);
                } else {
                    $_arr_toUsers = array($_arr_pmBulk['pm_to_users']);
                }
                $_arr_search = array(
                    'user_names' => $_arr_toUsers,
                );
                $_arr_userRows = $this->mdl_user->mdl_list(1000, 0, $_arr_search);
            break;

            case 'bulkKeyName':
                $_arr_search = array(
                    'key_name' => $_arr_pmBulk['pm_to_key_name'],
                );
                $_arr_userRows = $this->mdl_user->mdl_list(1000, 0, $_arr_search);
            break;

            case 'bulkKeyMail':
                $_arr_search = array(
                    'key_mail' => $_arr_pmBulk['pm_to_key_mail'],
                );
                $_arr_userRows = $this->mdl_user->mdl_list(1000, 0, $_arr_search);
            break;

            case 'bulkRangeId':
                $_arr_search = array(
                    'min_id'    => $_arr_pmBulk['pm_to_min_id'],
                    'max_id'    => $_arr_pmBulk['pm_to_max_id'],
                );
                $_arr_userRows = $this->mdl_user->mdl_list(1000, 0, $_arr_search);
            break;

            case 'bulkRangeTime':
                $_arr_search = array(
                    'begin_time'  => $_arr_pmBulk['pm_to_begin_time'],
                    'end_time'    => $_arr_pmBulk['pm_to_end_time'],
                );
                $_arr_userRows = $this->mdl_user->mdl_list(1000, 0, $_arr_search);
            break;

            case 'bulkRangeLogin':
                $_arr_search = array(
                    'begin_login'  => $_arr_pmBulk['pm_to_begin_login'],
                    'end_login'    => $_arr_pmBulk['pm_to_end_login'],
                );
                $_arr_userRows = $this->mdl_user->mdl_list(1000, 0, $_arr_search);
            break;
        }

        foreach ($_arr_userRows as $_key=>$value) {
            $_arr_pmRow = $this->mdl_pm->mdl_submit($value['user_id'], -1);
        }

        $this->obj_tpl->tplDisplay('result', $_arr_pmRow);
    }


    /**
     * ctrl_status function.
     *
     * @access public
     */
    function ctrl_status() {
        if (!isset($this->adminLogged['admin_allow']['pm']['edit']) && !$this->is_super) {
            $_arr_tplData = array(
                'rcode' => 'x110305',
            );
            $this->obj_tpl->tplDisplay('result', $_arr_tplData);
        }

        $_str_status = fn_getSafe($GLOBALS['route']['bg_act'], 'txt', '');
        if (fn_isEmpty($_str_status)) {
            $_arr_tplData = array(
                'rcode' => 'x110219',
            );
            $this->obj_tpl->tplDisplay('result', $_arr_tplData);
        }

        $_arr_pmIds = $this->mdl_pm->input_ids();
        if ($_arr_pmIds['rcode'] != 'ok') {
            $this->obj_tpl->tplDisplay('result', $_arr_pmIds);
        }

        $_arr_pmRow = $this->mdl_pm->mdl_status($_str_status);

        $this->obj_tpl->tplDisplay('result', $_arr_pmRow);
    }


    /**
     * ctrl_del function.
     *
     * @access public
     */
    function ctrl_del() {
        if (!isset($this->adminLogged['admin_allow']['pm']['del']) && !$this->is_super) {
            $_arr_tplData = array(
                'rcode' => 'x110304',
            );
            $this->obj_tpl->tplDisplay('result', $_arr_tplData);
        }

        $_arr_pmIds = $this->mdl_pm->input_ids();
        if ($_arr_pmIds['rcode'] != 'ok') {
            $this->obj_tpl->tplDisplay('result', $_arr_pmIds);
        }

        $_arr_pmRow = $this->mdl_pm->mdl_del();

        $this->obj_tpl->tplDisplay('result', $_arr_pmRow);
    }
}
