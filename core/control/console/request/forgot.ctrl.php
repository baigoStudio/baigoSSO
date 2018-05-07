<?php
/*-----------------------------------------------------------------
！！！！警告！！！！
以下为系统文件，请勿修改
-----------------------------------------------------------------*/

//不能非法包含或直接执行
if (!defined('IN_BAIGO')) {
    exit('Access Denied');
}

if (!function_exists('fn_mailSend')) {
    fn_include(BG_PATH_FUNC . 'mail.func.php'); //载入模板类
}

/*-------------登录控制器-------------*/
class CONTROL_CONSOLE_REQUEST_FORGOT {

    function __construct() { //构造函数
        $this->config   = $GLOBALS['obj_base']->config;

        $this->general_console      = new GENERAL_CONSOLE();
        $this->general_console->dspType = 'result';
        $this->general_console->chk_install();

        $this->obj_tpl          = $this->general_console->obj_tpl;

        $this->mdl_admin_forgot = new MODEL_ADMIN_FORGOT(); //设置管理员模型
        $this->mdl_user_profile = new MODEL_USER_PROFILE(); //设置管理员模型
        $this->mdl_verify       = new MODEL_VERIFY(); //设置管理员模型
    }


    function ctrl_bymail() {
        $_arr_mailContent = fn_include(BG_PATH_LANG . $this->config['lang'] . DS . 'mail.php');

        $_arr_bymailInput = $this->mdl_admin_forgot->input_bymail();
        if ($_arr_bymailInput['rcode'] != 'ok') {
            $this->obj_tpl->tplDisplay('result', $_arr_bymailInput);
        }

        $_arr_userRow = $this->mdl_user_profile->mdl_read($_arr_bymailInput['admin_name'], 'user_name');
        if ($_arr_userRow['rcode'] != 'y010102') {
            $this->obj_tpl->tplDisplay('result', $_arr_userRow);
        }

        if ($_arr_userRow['user_status'] == 'disable') {
            $_arr_tplData = array(
                'rcode'     => 'x010401',
            );
            $this->obj_tpl->tplDisplay('result', $_arr_tplData);
        }

        $_arr_adminRow = $this->mdl_admin_forgot->mdl_read($_arr_userRow['user_id']);
        if ($_arr_adminRow['rcode'] != 'y020102') {
            $this->obj_tpl->tplDisplay('result', $_arr_adminRow);
        }

        if ($_arr_adminRow['admin_status'] == 'disable') {
            $_arr_tplData = array(
                'rcode'     => 'x020402',
            );
            $this->obj_tpl->tplDisplay('result', $_arr_tplData);
        }

        $_arr_returnRow    = $this->mdl_verify->mdl_submit($_arr_userRow['user_id'], $_arr_userRow['user_mail'], 'forgot');
        if ($_arr_returnRow['rcode'] != 'y120101' && $_arr_returnRow['rcode'] != 'y120103') {
            $_arr_tplData = array(
                'rcode'     => 'x010408',
            );
            $this->obj_tpl->tplDisplay('result', $_arr_tplData);
        }

        $_str_verifyUrl = BG_SITE_URL . BG_URL_PERSONAL . 'index.php?m=forgot&a=bymail&verify_id=' . $_arr_returnRow['verify_id'] . '&verify_token=' . $_arr_returnRow['verify_token'];
        $_str_url       = '<a href="' . $_str_verifyUrl . '">' . $_str_verifyUrl . '</a>';
        $_str_html      = str_ireplace('{$verify_url}', $_str_url, $_arr_mailContent['forgot']['content']);
        $_str_html      = str_ireplace('{$user_name}', $_arr_userRow['user_name'], $_str_html);

        if (fn_mailSend($_arr_userRow['user_mail'], $_arr_mailContent['forgot']['subject'], $_str_html)) {
            $_str_rcode = 'y010408';
        } else {
            $_str_rcode = 'x010408';
        }

        $_arr_tplData = array(
            'rcode'     => $_str_rcode,
        );
        $this->obj_tpl->tplDisplay('result', $_arr_tplData);
    }

    /**
     * ctrl_submit function.
     *
     * @access public
     */
    function ctrl_byqa() {
        $_arr_byqaInput = $this->mdl_admin_forgot->input_byqa();

        if ($_arr_byqaInput['rcode'] != 'ok') {
            $this->obj_tpl->tplDisplay('result', $_arr_byqaInput);
        }

        $_arr_userRow = $this->mdl_user_profile->mdl_read($_arr_byqaInput['admin_name'], 'user_name');
        if ($_arr_userRow['rcode'] != 'y010102') {
            $this->obj_tpl->tplDisplay('result', $_arr_userRow);
        }

        if ($_arr_userRow['user_status'] == 'disable') {
            $_arr_tplData = array(
                'rcode'     => 'x010401',
            );
            $this->obj_tpl->tplDisplay('result', $_arr_tplData);
        }

        $_arr_adminRow = $this->mdl_admin_forgot->mdl_read($_arr_userRow['user_id']);
        if ($_arr_adminRow['rcode'] != 'y020102') {
            $this->obj_tpl->tplDisplay('result', $_arr_adminRow);
        }

        if ($_arr_adminRow['admin_status'] == 'disable') {
            $_arr_tplData = array(
                'rcode'     => 'x020402',
            );
            $this->obj_tpl->tplDisplay('result', $_arr_tplData);
        }

        for ($_iii = 1; $_iii <= 3; $_iii++) {
            $_str_sec_answ = fn_baigoCrypt($_arr_byqaInput['admin_sec_answ_' . $_iii], $_arr_userRow['user_name']);
            if ($_str_sec_answ != $_arr_userRow['user_sec_answ_' . $_iii]) {
                $_arr_tplData = array(
                    'rcode'     => 'x010245',
                );
                $this->obj_tpl->tplDisplay('result', $_arr_tplData);
            }
        }

        $_str_userPass  = fn_baigoCrypt($_arr_byqaInput['admin_pass_new'], $_arr_userRow['user_name']);
        $_arr_userRow   = $this->mdl_user_profile->mdl_pass($_arr_userRow['user_id'], $_str_userPass);

        $this->obj_tpl->tplDisplay('result', $_arr_userRow);
    }
}
