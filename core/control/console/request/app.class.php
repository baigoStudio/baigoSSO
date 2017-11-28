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
class CONTROL_CONSOLE_REQUEST_APP {

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

        $this->obj_sign         = new CLASS_SIGN();
        $this->mdl_app          = new MODEL_APP(); //设置管理员模型
        $this->mdl_belong       = new MODEL_BELONG();
        $this->mdl_user         = new MODEL_USER(); //设置管理员模型
    }


    function ctrl_reset() {
        if (!isset($this->adminLogged['admin_allow']['app']['edit']) && !$this->is_super) {
            $_arr_tplData = array(
                'rcode' => 'x050303',
            );
            $this->obj_tpl->tplDisplay('result', $_arr_tplData);
        }

        $_num_appId   = fn_getSafe(fn_post('app_id'), 'int', 0);

        if ($_num_appId < 1) {
            $_arr_tplData = array(
                'rcode' => 'x050203',
            );
            $this->obj_tpl->tplDisplay('result', $_arr_tplData);
        }

        $_arr_appRow = $this->mdl_app->mdl_read($_num_appId);
        if ($_arr_appRow['rcode'] != 'y050102') {
            $this->obj_tpl->tplDisplay('result', $_arr_appRow);
        }

        $_arr_appRow  = $this->mdl_app->mdl_reset($_num_appId);

        $this->obj_tpl->tplDisplay('result', $_arr_appRow);
    }


    function ctrl_deauth() {
        if (!isset($this->adminLogged['admin_allow']['app']['edit']) && !$this->is_super) {
            $_arr_tplData = array(
                'rcode' => 'x050303',
            );
            $this->obj_tpl->tplDisplay('result', $_arr_tplData);
        }

        $_arr_userIds = $this->mdl_user->input_ids();

        //print_r($_arr_userIds);

        if ($_arr_userIds['rcode'] != 'ok') {
            $this->obj_tpl->tplDisplay('result', $_arr_userIds);
        }

        $_num_appId = fn_getSafe(fn_post('app_id'), 'int', 0);

        if ($_num_appId < 1) {
            $_arr_tplData = array(
                'rcode' => 'x050203',
            );
            $this->obj_tpl->tplDisplay('result', $_arr_tplData);
        }

        $this->mdl_belong->mdl_del($_num_appId, 0, false, $_arr_userIds['user_ids']);

        //$_arr_appRow     = $this->mdl_app->mdl_order();

        $_arr_tplData = array(
            'rcode' => 'y070402',
        );
        $this->obj_tpl->tplDisplay('result', $_arr_tplData);
    }


    function ctrl_auth() {
        if (!isset($this->adminLogged['admin_allow']['app']['edit']) && !$this->is_super) {
            $_arr_tplData = array(
                'rcode' => 'x050303',
            );
            $this->obj_tpl->tplDisplay('result', $_arr_tplData);
        }

        $_arr_userIds = $this->mdl_user->input_ids();

        if ($_arr_userIds['rcode'] != 'ok') {
            $this->obj_tpl->tplDisplay('result', $_arr_userIds);
        }

        $_num_appId = fn_getSafe(fn_post('app_id'), 'int', 0);

        if ($_num_appId < 1) {
            $_arr_tplData = array(
                'rcode' => 'x050203',
            );
            $this->obj_tpl->tplDisplay('result', $_arr_tplData);
        }

        foreach ($_arr_userIds['user_ids'] as $_key=>$_value) {
            $_arr_userRow = $this->mdl_user->mdl_read($_value);
            if ($_arr_userRow['rcode'] == 'y010102') {
                $this->mdl_belong->mdl_submit($_value, $_num_appId);
            }
        }

        //$_arr_appRow     = $this->mdl_app->mdl_order();

        $_arr_tplData = array(
            'rcode' => 'y070401',
        );
        $this->obj_tpl->tplDisplay('result', $_arr_tplData);
    }


    /**
     * ctrl_notify function.
     *
     * @access public
     */
    function ctrl_notify() {
        $_num_appId = fn_getSafe(fn_post('app_id_notify'), 'int', 0);
        if ($_num_appId < 1) {
            $_arr_tplData = array(
                'rcode' => 'x050203',
            );
            $this->obj_tpl->tplDisplay('result', $_arr_tplData);
        }

        if (!isset($this->adminLogged['admin_allow']['app']['browse']) && !$this->is_super) {
            $_arr_tplData = array(
                'rcode' => 'x050301',
            );
            $this->obj_tpl->tplDisplay('result', $_arr_tplData);
        }

        $_arr_appRow = $this->mdl_app->mdl_read($_num_appId);
        if ($_arr_appRow['rcode'] != 'y050102') {
            $this->obj_tpl->tplDisplay('result', $_arr_appRow);
        }

        $_tm_time   = time();
        $_str_echo  = fn_rand();

        $_arr_data = array(
            'act'       => 'test',
            'time'      => $_tm_time,
            'echostr'   => $_str_echo,
            'app_id'    => $_arr_appRow['app_id'],
            'app_key'   => fn_baigoCrypt($_arr_appRow['app_key'], $_arr_appRow['app_name']),
        );

        $_arr_data['signature'] = $this->obj_sign->sign_make($_arr_data);

        if (stristr($_arr_appRow['app_url_notify'], '?')) {
            $_str_conn = '&';
        } else {
            $_str_conn = '?';
        }

        $_arr_notify = fn_http($_arr_appRow['app_url_notify'] . $_str_conn . 'mod=notify', $_arr_data, 'get');
        //print_r($_arr_notify);

        if ($_arr_notify['ret'] == $_str_echo) {
            $_str_rcode = 'y050401';
        } else {
            $_str_rcode = 'x050401';
            //exit('test');
        }

        $_arr_tplData = array(
            'rcode' => $_str_rcode,
        );
        $this->obj_tpl->tplDisplay('result', $_arr_tplData);
    }


    /**
     * ctrl_submit function.
     *
     * @access public
     */
    function ctrl_submit() {
        $_arr_appInput = $this->mdl_app->input_submit();

        if ($_arr_appInput['rcode'] != 'ok') {
            $this->obj_tpl->tplDisplay('result', $_arr_appInput);
        }

        if ($_arr_appInput['app_id'] > 0) {
            if (!isset($this->adminLogged['admin_allow']['app']['edit']) && !$this->is_super) {
                $_arr_tplData = array(
                    'rcode' => 'x050303',
                );
                $this->obj_tpl->tplDisplay('result', $_arr_tplData);
            }
        } else {
            if (!isset($this->adminLogged['admin_allow']['app']['add']) && !$this->is_super) {
                $_arr_tplData = array(
                    'rcode' => 'x050302',
                );
                $this->obj_tpl->tplDisplay('result', $_arr_tplData);
            }
        }

        $_arr_appRow = $this->mdl_app->mdl_submit();

        $this->obj_tpl->tplDisplay('result', $_arr_appRow);
    }


    /**
     * ctrl_status function.
     *
     * @access public
     */
    function ctrl_status() {
        if (!isset($this->adminLogged['admin_allow']['app']['edit']) && !$this->is_super) {
            $_arr_tplData = array(
                'rcode' => 'x050303',
            );
            $this->obj_tpl->tplDisplay('result', $_arr_tplData);
        }

        $_str_status = fn_getSafe($GLOBALS['route']['bg_act'], 'txt', '');
        if (fn_isEmpty($_str_status)) {
            $_arr_tplData = array(
                'rcode' => 'x050206',
            );
            $this->obj_tpl->tplDisplay('result', $_arr_tplData);
        }

        $_arr_appIds = $this->mdl_app->input_ids();
        if ($_arr_appIds['rcode'] != 'ok') {
            $this->obj_tpl->tplDisplay('result', $_arr_appIds);
        }

        $_arr_appRow = $this->mdl_app->mdl_status($_str_status);

        $this->obj_tpl->tplDisplay('result', $_arr_appRow);
    }


    /**
     * ctrl_del function.
     *
     * @access public
     */
    function ctrl_del() {
        if (!isset($this->adminLogged['admin_allow']['app']['del']) && !$this->is_super) {
            $_arr_tplData = array(
                'rcode' => 'x050304',
            );
            $this->obj_tpl->tplDisplay('result', $_arr_tplData);
        }

        $_arr_appIds = $this->mdl_app->input_ids();
        if ($_arr_appIds['rcode'] != 'ok') {
            $this->obj_tpl->tplDisplay('result', $_arr_appIds);
        }

        $_arr_appRow = $this->mdl_app->mdl_del();

        $this->obj_tpl->tplDisplay('result', $_arr_appRow);
    }
}
