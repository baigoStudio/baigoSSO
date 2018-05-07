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
class CONTROL_PERSONAL_REQUEST_PROFILE {

    function __construct() { //构造函数
        $this->general_personal  = new GENERAL_PERSONAL();

        $this->obj_tpl      = $this->general_personal->obj_tpl;

        $this->mdl_user_profile = new MODEL_USER_PROFILE(); //设置管理员模型
        $this->mdl_verify       = new MODEL_VERIFY(); //设置管理员模型

        $this->general_api  = new GENERAL_API();
    }

    function ctrl_mailbox() {
        if (!fn_captcha()) { //验证码
            $_arr_tplData = array(
                'rcode' => 'x030205',
            );
            $this->obj_tpl->tplDisplay('result', $_arr_tplData);
        }

        $_arr_verifyInput = $this->mdl_verify->input_verify();
        if ($_arr_verifyInput['rcode'] != 'ok') {
            $this->obj_tpl->tplDisplay('result', $_arr_verifyInput);
        }

        $_arr_verifyRow = $this->mdl_verify->mdl_read($_arr_verifyInput['verify_id']);
        if ($_arr_verifyRow['rcode'] != 'y120102') {
            $this->obj_tpl->tplDisplay('result', $_arr_verifyRow);
        }

        if ($_arr_verifyRow['verify_status'] != 'enable') {
            $_arr_tplData = array(
                'rcode' => 'x120203',
            );
            $this->obj_tpl->tplDisplay('result', $_arr_tplData);
        }

        if ($_arr_verifyRow['verify_type'] != 'mailbox') {
            $_arr_tplData = array(
                'rcode' => 'x120207',
            );
            $this->obj_tpl->tplDisplay('result', $_arr_tplData);
        }

        if ($_arr_verifyRow['verify_token_expire'] < time()) {
            $_arr_tplData = array(
                'rcode' => 'x120204',
            );
            $this->obj_tpl->tplDisplay('result', $_arr_tplData);
        }

        if (fn_baigoCrypt($_arr_verifyRow['verify_token'], $_arr_verifyRow['verify_rand']) != $_arr_verifyInput['verify_token']) {
            $_arr_tplData = array(
                'rcode' => 'x120205',
            );
            $this->obj_tpl->tplDisplay('result', $_arr_tplData);
        }

        $_arr_userRow = $this->mdl_user_profile->mdl_read($_arr_verifyRow['verify_user_id']);
        if ($_arr_userRow['rcode'] != 'y010102') {
            $this->obj_tpl->tplDisplay('result', $_arr_userRow);
        }

        if ($_arr_userRow['user_status'] == 'disable') {
            $_arr_tplData = array(
                'rcode' => 'x010401',
            );
            $this->obj_tpl->tplDisplay('result', $_arr_tplData);
        }

        $_arr_returnRow = $this->mdl_user_profile->mdl_mailbox($_arr_userRow['user_id'], $_arr_verifyRow['verify_mail']);

        $this->general_api->notify_result($_arr_returnRow, 'edit');

        $this->mdl_verify->mdl_disable();

        $this->obj_tpl->tplDisplay('result', $_arr_returnRow);
    }
}
