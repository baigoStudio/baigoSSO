<?php
/*-----------------------------------------------------------------
！！！！警告！！！！
以下为系统文件，请勿修改
-----------------------------------------------------------------*/

//不能非法包含或直接执行
if (!defined('IN_BAIGO')) {
    exit('Access Denied');
}

fn_include(BG_PATH_FUNC . 'mail.func.php');

/*-------------用户类-------------*/
class CONTROL_API_API_FORGOT {

    function __construct() { //构造函数
        $this->config           = $GLOBALS['obj_base']->config;

        $this->general_api          = new GENERAL_API();
        //$this->general_api->chk_install();

        $this->mail             = fn_include(BG_PATH_LANG . $this->config['lang'] . DS . 'mail.php');

        $this->obj_sign         = $this->general_api->obj_sign;

        $this->mdl_user_forgot  = new MODEL_USER_FORGOT(); //设置管理组模型
        $this->mdl_user_profile = new MODEL_USER_PROFILE(); //设置管理组模型
        //$this->mdl_belong       = new MODEL_BELONG();
        $this->mdl_verify       = new MODEL_VERIFY(); //设置管理员模型
    }


    function ctrl_bymail() {
        $_arr_apiChks = $this->general_api->app_chk('post');
        if ($_arr_apiChks['rcode'] != 'ok') {
            $this->general_api->show_result($_arr_apiChks);
        }

        $_arr_bymailInput = $this->mdl_user_forgot->input_user('post');
        if ($_arr_bymailInput['rcode'] != 'ok') {
            $this->general_api->show_result($_arr_bymailInput);
        }

        $_arr_sign = array(
            'act' => $GLOBALS['route']['bg_act'],
            $_arr_bymailInput['user_by'] => $_arr_bymailInput['user_str'],
        );

        if (!$this->obj_sign->sign_check(array_merge($_arr_apiChks['appInput'], $_arr_sign), $_arr_apiChks['appInput']['signature'])) {
            $_arr_tplData = array(
                'rcode' => 'x050403',
            );
            $this->general_api->show_result($_arr_tplData);
        }

        $_arr_userRow = $this->mdl_user_forgot->mdl_read($_arr_bymailInput['user_str'], $_arr_bymailInput['user_by']);
        if ($_arr_userRow['rcode'] != 'y010102') {
            $this->general_api->show_result($_arr_userRow);
        }

        if ($_arr_userRow['user_status'] == 'disable') {
            $_arr_tplData = array(
                'rcode' => 'x010401',
            );
            $this->general_api->show_result($_arr_tplData);
        }

        $_arr_returnRow    = $this->mdl_verify->mdl_submit($_arr_userRow['user_id'], $_arr_userRow['user_mail'], 'forgot');
        if ($_arr_returnRow['rcode'] != 'y120101' && $_arr_returnRow['rcode'] != 'y120103') {
            $_arr_tplData = array(
                'rcode' => 'x010408',
            );
            $this->general_api->show_result($_arr_tplData);
        }

        $_str_verifyUrl = BG_SITE_URL . BG_URL_PERSONAL . 'index.php?mod=forgot&act=bymail&verify_id=' . $_arr_returnRow['verify_id'] . '&verify_token=' . $_arr_returnRow['verify_token'];
        $_str_url       = '<a href="' . $_str_verifyUrl . '">' . $_str_verifyUrl . '</a>';
        $_str_html      = str_ireplace('{verify_url}', $_str_url, $this->mail['forgot']['content']);
        $_str_html      = str_ireplace('{user_name}', $_arr_userRow['user_name'], $_str_html);

        //file_put_contents(BG_PATH_ROOT . 'test.txt', $_str_html);

        if (fn_mailSend($_arr_userRow['user_mail'], $this->mail['forgot']['subject'], $_str_html)) {
            $_arr_returnRow['rcode'] = 'y010408';
        } else {
            $_arr_returnRow['rcode'] = 'x010408';
        }

        $_arr_returnRow['user_id']      = $_arr_userRow['user_id'];
        $_arr_returnRow['user_name']    = $_arr_userRow['user_name'];

        $_arr_tplData = array(
            'rcode'  => $_arr_returnRow['rcode'],
        );
        $this->general_api->show_result($_arr_tplData);
    }


    function ctrl_byqa() {
        $_arr_apiChks = $this->general_api->app_chk('post');
        if ($_arr_apiChks['rcode'] != 'ok') {
            $this->general_api->show_result($_arr_apiChks);
        }

        $_arr_byqaInput = $this->mdl_user_forgot->input_byqa('post');
        if ($_arr_byqaInput['rcode'] != 'ok') {
            $this->general_api->show_result($_arr_byqaInput);
        }

        $_arr_sign = array(
            'act' => $GLOBALS['route']['bg_act'],
            $_arr_byqaInput['user_by']  => $_arr_byqaInput['user_str'],
            'user_pass_new'             => $_arr_byqaInput['user_pass_new'],
            'user_pass_confirm'         => $_arr_byqaInput['user_pass_confirm'],
        );

        for ($_iii = 1; $_iii <= 3; $_iii++) {
            $_arr_sign['user_sec_answ_' . $_iii] = $_arr_byqaInput['user_sec_answ_' . $_iii];
        }

        if (!$this->obj_sign->sign_check(array_merge($_arr_apiChks['appInput'], $_arr_sign), $_arr_apiChks['appInput']['signature'])) {
            $_arr_tplData = array(
                'rcode' => 'x050403',
            );
            $this->general_api->show_result($_arr_tplData);
        }

        $_arr_userRow = $this->mdl_user_forgot->mdl_read($_arr_byqaInput['user_str'], $_arr_byqaInput['user_by']);
        if ($_arr_userRow['rcode'] != 'y010102') {
            $this->general_api->show_result($_arr_userRow);
        }

        if ($_arr_userRow['user_status'] == 'disable') {
            $_arr_tplData = array(
                'rcode' => 'x010401',
            );
            $this->general_api->show_result($_arr_tplData);
        }

        for ($_iii = 1; $_iii <= 3; $_iii++) {
            $_str_sec_answ = fn_baigoCrypt($_arr_byqaInput['user_sec_answ_' . $_iii], $_arr_userRow['user_name'], true);
            if ($_str_sec_answ != $_arr_userRow['user_sec_answ_' . $_iii]) {
                $_arr_tplData = array(
                    'rcode'     => 'x010245',
                );
                $this->general_api->show_result($_arr_tplData);
            }
        }

        $_str_userPass  = fn_baigoCrypt($_arr_byqaInput['user_pass_new'], $_arr_userRow['user_name'], true);
        $_arr_returnRow = $this->mdl_user_profile->mdl_pass($_arr_userRow['user_id'], $_str_userPass);


        $this->general_api->show_result($_arr_returnRow);
    }
}
