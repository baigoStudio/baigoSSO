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

/*-------------管理员控制器-------------*/
class CONTROL_CONSOLE_REQUEST_PROFILE {

    function __construct() { //构造函数
        $this->config   = $GLOBALS['obj_base']->config;

        $this->general_console  = new GENERAL_CONSOLE();
        $this->general_console->dspType = 'result';
        $this->general_console->chk_install();

        $this->adminLogged  = $this->general_console->ssin_begin(); //获取已登录信息
        $this->general_console->is_admin($this->adminLogged);

        $this->obj_tpl      = $this->general_console->obj_tpl;

        $this->tplData = array(
            'adminLogged' => $this->adminLogged
        );

        $this->mail         = fn_include(BG_PATH_LANG . $this->config['lang'] . DS . 'mail.php');

        $this->mdl_admin_profile    = new MODEL_ADMIN_PROFILE(); //设置管理组模型
        $this->mdl_user_profile     = new MODEL_USER_PROFILE(); //设置管理组模型
        $this->mdl_verify           = new MODEL_VERIFY();

        $this->general_api  = new GENERAL_API();
    }


    function ctrl_qa() {
        if (isset($this->adminLogged['admin_allow']['qa'])) {
            $_arr_tplData = array(
                'rcode' => 'x020109',
            );
            $this->obj_tpl->tplDisplay('result', $_arr_tplData);
        }

        $_arr_qaInput = $this->mdl_admin_profile->input_qa();
        if ($_arr_qaInput['rcode'] != 'ok') {
            $this->obj_tpl->tplDisplay('result', $_arr_qaInput);
        }

        $_arr_adminRow = $this->mdl_admin_profile->mdl_read($this->adminLogged['admin_id']);
        if ($_arr_adminRow['rcode'] != 'y020102') {
            $this->obj_tpl->tplDisplay('result', $_arr_adminRow);
        }

        $_arr_userRow = $this->mdl_user_profile->mdl_read($this->adminLogged['admin_id']);
        if ($_arr_userRow['rcode'] != 'y010102') {
            $this->obj_tpl->tplDisplay('result', $_arr_userRow);
        }

        switch ($_arr_userRow['user_crypt_type']) {
            case 0:
            case 1:
                $_str_crypt = fn_baigoCrypt($_arr_qaInput['admin_pass'], $_arr_userRow['user_rand'], false, $_arr_userRow['user_crypt_type']);
            break;

            default:
                $_str_crypt = fn_baigoCrypt($_arr_qaInput['admin_pass'], $_arr_userRow['user_name']);
            break;
        }

        if ($_str_crypt != $_arr_userRow['user_pass']) {
            $_arr_tplData = array(
                'rcode' => 'x010244',
            );
            $this->obj_tpl->tplDisplay('result', $_arr_tplData);
        }

        $_arr_userSubmit['user_id'] = $_arr_userRow['user_id'];

        for ($_iii = 1; $_iii <= 3; $_iii++) {
            $_arr_userSubmit['user_sec_ques_' . $_iii] = $_arr_qaInput['admin_sec_ques_' . $_iii];
            $_arr_userSubmit['user_sec_answ_' . $_iii] = fn_baigoCrypt($_arr_qaInput['admin_sec_answ_' . $_iii], $_arr_userRow['user_name']);
        }

        $_arr_userRow = $this->mdl_user_profile->mdl_qa($_arr_userSubmit);

        $this->obj_tpl->tplDisplay('result', $_arr_userRow);
    }


    function ctrl_mailbox() {
        if (isset($this->adminLogged['admin_allow']['mailbox'])) {
            $_arr_tplData = array(
                'rcode' => 'x020110',
            );
            $this->obj_tpl->tplDisplay('result', $_arr_tplData);
        }

        $_arr_mailboxInput = $this->mdl_admin_profile->input_mailbox();
        if ($_arr_mailboxInput['rcode'] != 'ok') {
            $this->obj_tpl->tplDisplay('result', $_arr_mailboxInput);
        }

        $_arr_adminRow = $this->mdl_admin_profile->mdl_read($this->adminLogged['admin_id']);
        if ($_arr_adminRow['rcode'] != 'y020102') {
            $this->obj_tpl->tplDisplay('result', $_arr_adminRow);
        }

        $_arr_userRow = $this->mdl_user_profile->mdl_read($this->adminLogged['admin_id']);
        if ($_arr_userRow['rcode'] != 'y010102') {
            $this->obj_tpl->tplDisplay('result', $_arr_userRow);
        }

        if ($_arr_mailboxInput['admin_mail_new'] == $_arr_userRow['user_mail']) {
            $_arr_tplData = array(
                'rcode' => 'x010223',
            );
            $this->obj_tpl->tplDisplay('result', $_arr_tplData);
        }

        switch ($_arr_userRow['user_crypt_type']) {
            case 0:
            case 1:
                $_str_crypt = fn_baigoCrypt($_arr_mailboxInput['admin_pass'], $_arr_userRow['user_rand'], false, $_arr_userRow['user_crypt_type']);
            break;

            default:
                $_str_crypt = fn_baigoCrypt($_arr_mailboxInput['admin_pass'], $_arr_userRow['user_name']);
            break;
        }

        if ($_str_crypt != $_arr_userRow['user_pass']) {
            $_arr_tplData = array(
                'rcode' => 'x010244',
            );
            $this->obj_tpl->tplDisplay('result', $_arr_tplData);
        }


        if ((BG_REG_ONEMAIL == 'false' || BG_LOGIN_MAIL == 'on') && isset($_arr_mailboxInput['admin_mail_new']) && $_arr_mailboxInput['admin_mail_new']) {
            $_arr_userRowChk = $this->mdl_user_profile->mdl_read($_arr_mailboxInput['admin_mail_new'], 'user_mail', $_arr_userRow['user_id']); //检查邮箱
            if ($_arr_userRowChk['rcode'] == 'y010102') {
                $_arr_tplData = array(
                    'rcode' => 'x010211',
                );
                $this->obj_tpl->tplDisplay('result', $_arr_tplData);
            }
        }

        //file_put_contents(BG_PATH_ROOT . 'test.txt', $_str_userPass . '||' . $_str_rand);

        if (BG_REG_CONFIRM == 'on') {
            $_arr_returnRow    = $this->mdl_verify->mdl_submit($_arr_userRow['user_id'], $_arr_mailboxInput['admin_mail_new'], 'mailbox');
            if ($_arr_returnRow['rcode'] != 'y120101' && $_arr_returnRow['rcode'] != 'y120103') {
                $_arr_tplData = array(
                    'rcode' => 'x010405',
                );
                $this->obj_tpl->tplDisplay('result', $_arr_tplData);
            }

            $_str_verifyUrl = BG_SITE_URL . BG_URL_PERSONAL . 'index.php?m=profile&a=mailbox&verify_id=' . $_arr_returnRow['verify_id'] . '&verify_token=' . $_arr_returnRow['verify_token'];
            $_str_url       = '<a href="' . $_str_verifyUrl . '">' . $_str_verifyUrl . '</a>';
            $_str_html      = str_ireplace('{$verify_url}', $_str_url, $this->mail['mailbox']['content']);
            $_str_html      = str_ireplace('{$user_name}', $_arr_userRow['user_name'], $_str_html);
            $_str_html      = str_ireplace('{$user_mail}', $_arr_userRow['user_mail'], $_str_html);
            $_str_html      = str_ireplace('{$user_mail_new}', $_arr_mailboxInput['admin_mail_new'], $_str_html);

            if (fn_mailSend($_arr_mailboxInput['admin_mail_new'], $this->mail['mailbox']['subject'], $_str_html)) {
                $_arr_returnRow['rcode'] = 'y010406';
            } else {
                $_arr_returnRow['rcode'] = 'x010406';
            }
        } else {
            $_arr_returnRow = $this->mdl_user_profile->mdl_mailbox($_arr_userRow['user_id'], $_arr_mailboxInput['admin_mail_new']);

            $this->general_api->notify_result($_arr_returnRow, 'edit');
        }

        $this->obj_tpl->tplDisplay('result', $_arr_returnRow);
    }


    function ctrl_pass() {
        if (isset($this->adminLogged['admin_allow']['pass'])) {
            $_arr_tplData = array(
                'rcode' => 'x020109',
            );
            $this->obj_tpl->tplDisplay('result', $_arr_tplData);
        }

        $_arr_passInput = $this->mdl_admin_profile->input_pass();
        if ($_arr_passInput['rcode'] != 'ok') {
            $this->obj_tpl->tplDisplay('result', $_arr_passInput);
        }

        $_arr_adminRow = $this->mdl_admin_profile->mdl_read($this->adminLogged['admin_id']);
        if ($_arr_adminRow['rcode'] != 'y020102') {
            $this->obj_tpl->tplDisplay('result', $_arr_adminRow);
        }

        $_arr_userRow = $this->mdl_user_profile->mdl_read($this->adminLogged['admin_id']);
        if ($_arr_userRow['rcode'] != 'y010102') {
            $this->obj_tpl->tplDisplay('result', $_arr_userRow);
        }

        switch ($_arr_userRow['user_crypt_type']) {
            case 0:
            case 1:
                $_str_crypt = fn_baigoCrypt($_arr_passInput['admin_pass'], $_arr_userRow['user_rand'], false, $_arr_userRow['user_crypt_type']);
            break;

            default:
                $_str_crypt = fn_baigoCrypt($_arr_passInput['admin_pass'], $_arr_userRow['user_name']);
            break;
        }

        if ($_str_crypt != $_arr_userRow['user_pass']) {
            $_arr_tplData = array(
                'rcode' => 'x010244',
            );
            $this->obj_tpl->tplDisplay('result', $_arr_tplData);
        }

        $_str_userPass  = fn_baigoCrypt($_arr_passInput['admin_pass_new'], $_arr_userRow['user_name']);
        $_arr_userRow   = $this->mdl_user_profile->mdl_pass($_arr_userRow['user_id'], $_str_userPass);

        $this->obj_tpl->tplDisplay('result', $_arr_userRow);
    }

    /**
     * str_personal function.
     *
     * @access public
     */
    function ctrl_info() {
        if (isset($this->adminLogged['admin_allow']['info'])) {
            $_arr_tplData = array(
                'rcode' => 'x020108',
            );
            $this->obj_tpl->tplDisplay('result', $_arr_tplData);
        }

        $_arr_infoInput = $this->mdl_admin_profile->input_info();
        if ($_arr_infoInput['rcode'] != 'ok') {
            $this->obj_tpl->tplDisplay('result', $_arr_infoInput);
        }

        $_arr_adminRow = $this->mdl_admin_profile->mdl_read($this->adminLogged['admin_id']);
        if ($_arr_adminRow['rcode'] != 'y020102') {
            $this->obj_tpl->tplDisplay('result', $_arr_adminRow);
        }

        $_arr_userRow = $this->mdl_user_profile->mdl_read($this->adminLogged['admin_id']);
        if ($_arr_userRow['rcode'] != 'y010102') {
            $this->obj_tpl->tplDisplay('result', $_arr_userRow);
        }

        switch ($_arr_userRow['user_crypt_type']) {
            case 0:
            case 1:
                $_str_crypt = fn_baigoCrypt($_arr_infoInput['admin_pass'], $_arr_userRow['user_rand'], false, $_arr_userRow['user_crypt_type']);
            break;

            default:
                $_str_crypt = fn_baigoCrypt($_arr_infoInput['admin_pass'], $_arr_userRow['user_name']);
            break;
        }

        if ($_str_crypt != $_arr_userRow['user_pass']) {
            $_arr_tplData = array(
                'rcode' => 'x010244',
            );
            $this->obj_tpl->tplDisplay('result', $_arr_tplData);
        }

        $_arr_adminRow = $this->mdl_admin_profile->mdl_info($this->adminLogged['admin_id']);

        $_arr_userSubmit = array(
            'user_id'   => $this->adminLogged['admin_id'],
            'user_nick' => $_arr_infoInput['admin_nick'],
        );
        $_arr_userRow = $this->mdl_user_profile->mdl_info($_arr_userSubmit);

        if ($_arr_adminRow['rcode'] == 'x020103') {
            if ($_arr_userRow['rcode'] == 'y010103') {
                $_arr_adminRow['rcode'] = 'y020103';
            }
        }

        $this->general_api->notify_result($_arr_userRow, 'edit');

        $this->obj_tpl->tplDisplay('result', $_arr_adminRow);
    }
}
