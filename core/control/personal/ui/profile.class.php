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
class CONTROL_PERSONAL_UI_PROFILE {

    function __construct() { //构造函数
        $this->general_personal  = new GENERAL_PERSONAL();

        $this->obj_tpl      = $this->general_personal->obj_tpl;

        $this->mdl_user     = new MODEL_USER(); //设置管理员模型
        $this->mdl_verify   = new MODEL_VERIFY(); //设置管理员模型
    }


    function ctrl_mailbox() {
        $_num_verifyId      = fn_getSafe(fn_get('verify_id'), 'int', 0);
        $_str_verifyToken   = fn_getSafe(fn_get('verify_token'), 'txt', '');

        if ($_num_verifyId < 1) {
            $_arr_tplData = array(
                'rcode' => 'x120201',
            );
            $this->obj_tpl->tplDisplay('error', $_arr_tplData);
        }

        if (fn_isEmpty($_str_verifyToken)) {
            $_arr_tplData = array(
                'rcode' => 'x120202',
            );
            $this->obj_tpl->tplDisplay('error', $_arr_tplData);
        }

        $_arr_verifyRow = $this->mdl_verify->mdl_read($_num_verifyId);
        if ($_arr_verifyRow['rcode'] != 'y120102') {
            $this->obj_tpl->tplDisplay('error', $_arr_verifyRow);
        }

        if ($_arr_verifyRow['verify_status'] != 'enable') {
            $_arr_tplData = array(
                'rcode' => 'x120203',
            );
            $this->obj_tpl->tplDisplay('error', $_arr_tplData);
        }

        if ($_arr_verifyRow['verify_type'] != 'mailbox') {
            $_arr_tplData = array(
                'rcode' => 'x120207',
            );
            $this->obj_tpl->tplDisplay('error', $_arr_tplData);
        }

        if ($_arr_verifyRow['verify_token_expire'] < time()) {
            $_arr_tplData = array(
                'rcode' => 'x120204',
            );
            $this->obj_tpl->tplDisplay('error', $_arr_tplData);
        }

        if (fn_baigoCrypt($_arr_verifyRow['verify_token'], $_arr_verifyRow['verify_rand']) != $_str_verifyToken) {
            $_arr_tplData = array(
                'rcode' => 'x120205',
            );
            $this->obj_tpl->tplDisplay('error', $_arr_tplData);
        }

        $_arr_userRow = $this->mdl_user->mdl_read($_arr_verifyRow['verify_user_id']);
        if ($_arr_userRow['rcode'] != 'y010102') {
            $this->obj_tpl->tplDisplay('error', $_arr_userRow);
        }

        if ($_arr_userRow['user_status'] == 'disable') {
            $_arr_tplData = array(
                'rcode' => 'x010401',
            );
            $this->obj_tpl->tplDisplay('error', $_arr_tplData);
        }

        $_arr_verifyRow['verify_token'] = $_str_verifyToken;

        $_arr_tplData = array(
            'userRow'   => $_arr_userRow,
            'verifyRow' => $_arr_verifyRow,
        );

        $this->obj_tpl->tplDisplay('profile_mailbox', $_arr_tplData);
    }
}
