<?php
/*-----------------------------------------------------------------
！！！！警告！！！！
以下为系统文件，请勿修改
-----------------------------------------------------------------*/

//不能非法包含或直接执行
if (!defined('IN_BAIGO')) {
    exit('Access Denied');
}


/*-------------登录控制器-------------*/
class CONTROL_CONSOLE_REQUEST_LOGIN {

    function __construct() { //构造函数
        $this->general_console  = new GENERAL_CONSOLE();
        $this->general_console->dspType = 'result';
        $this->general_console->chk_install();

        $this->obj_tpl          = $this->general_console->obj_tpl;

        $this->mdl_admin        = new MODEL_ADMIN(); //设置管理员模型
        $this->mdl_user_profile = new MODEL_USER_PROFILE(); //设置管理员模型
        $this->mdl_user_api     = new MODEL_USER_API(); //设置管理员模型
    }

    /**
     * ctrl_login function.
     *
     * @access public
     */
    function ctrl_login() {
        $_arr_adminInput = $this->mdl_admin->input_login();
        if ($_arr_adminInput['rcode'] != 'ok') {
            $this->obj_tpl->tplDisplay('result', $_arr_adminInput);
        }

        $_arr_userRow = $this->mdl_user_profile->mdl_read($_arr_adminInput['admin_name'], 'user_name');
        if ($_arr_userRow['rcode'] != 'y010102') {
            $this->obj_tpl->tplDisplay('result', $_arr_userRow);
        }

        if ($_arr_userRow['user_status'] == 'disable') {
            $_arr_tplData = array(
                'rcode'     => 'x010401',
            );
            $this->obj_tpl->tplDisplay('result', $_arr_tplData);
        }

        $_arr_adminRow = $this->mdl_admin->mdl_read($_arr_userRow['user_id']);
        if ($_arr_adminRow['rcode'] != 'y020102') {
            $this->obj_tpl->tplDisplay('result', $_arr_adminRow);
        }

        if ($_arr_adminRow['admin_status'] != 'enable') {
            $this->obj_tpl->tplDisplay('result', $_arr_adminRow);
        }

        switch ($_arr_userRow['user_crypt_type']) {
            case 0:
            case 1:
                $_str_crypt = fn_baigoCrypt($_arr_adminInput['admin_pass'], $_arr_userRow['user_rand'], false, $_arr_userRow['user_crypt_type']);
            break;

            default:
                $_str_crypt = fn_baigoCrypt($_arr_adminInput['admin_pass'], $_arr_userRow['user_name']);
            break;
        }

        //特殊处理，针对上一版本加密错误
        if ($_str_crypt != $_arr_userRow['user_pass']) {
            $_str_crypt = fn_baigoCrypt($_arr_adminInput['admin_pass'], $_arr_userRow['user_name'], false, 0);
        }

        if ($_str_crypt != $_arr_userRow['user_pass']) {
            $_arr_tplData = array(
                'rcode'     => 'x010213',
            );
            $this->obj_tpl->tplDisplay('result', $_arr_tplData);
        }

        $_arr_userSubmit = array(
            'user_id' => $_arr_userRow['user_id'],
        );
        $this->mdl_user_api->mdl_login($_arr_userSubmit);

        //如新加密规则与数据库不一致，则对密码重新加密
        $_str_userPass  = fn_baigoCrypt($_arr_adminInput['admin_pass'], $_arr_userRow['user_name']);

        if ($_str_userPass != $_arr_userRow['user_pass']) {
            $this->mdl_user_profile->mdl_pass($_arr_userRow['user_id'], $_str_userPass);
        }

        /*print_r($_str_crypt . '<br>');
        print_r($_arr_userRow['user_pass']);
        exit;*/

        $_arr_adminSubmit = array(
            'admin_id'      => $_arr_adminRow['admin_id'],
            'admin_name'    => $_arr_adminRow['admin_name'],
        );
        $_arr_loginRow = $this->mdl_admin->mdl_login($_arr_adminSubmit);

        $_arr_ssin = $this->general_console->ssin_login($_arr_loginRow);
        if ($_arr_ssin['rcode'] != 'ok') {
            $this->obj_tpl->tplDisplay('result', $_arr_ssin);
        }

        $_arr_tplData = array(
            'rcode'     => 'y020401',
        );
        $this->obj_tpl->tplDisplay('result', $_arr_tplData);
    }
}
