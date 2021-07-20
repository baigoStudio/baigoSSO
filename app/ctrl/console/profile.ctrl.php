<?php
/*-----------------------------------------------------------------
！！！！警告！！！！
以下为系统文件，请勿修改
-----------------------------------------------------------------*/

namespace app\ctrl\console;

use app\classes\console\Ctrl;
use ginkgo\Loader;
use ginkgo\Crypt;
use ginkgo\Config;
use ginkgo\Func;
use ginkgo\Smtp;
use ginkgo\Arrays;

// 不能非法包含或直接执行
if (!defined('IN_GINKGO')) {
    return 'Access denied';
}

class Profile extends Ctrl {

    protected function c_init($param = array()) {
        parent::c_init();

        $this->mdl_verify     = Loader::model('Verify');
        $this->mdl_user       = Loader::model('User');
        $this->mdl_profile    = Loader::model('Profile');
    }


    function info() {
        $_mix_init = $this->init();

        if ($_mix_init !== true) {
            return $this->error($_mix_init['msg'], $_mix_init['rcode']);
        }

        if (isset($this->adminLogged['admin_allow_profile']['info'])) {
            return $this->error('You do not have permission', 'x020305');
        }

        $_arr_tplData = array(
            'token'     => $this->obj_request->token(),
        );

        //print_r($_arr_tplData['timezoneType']);

        $_arr_tpl = array_replace_recursive($this->generalData, $_arr_tplData);

        $this->assign($_arr_tpl);

        return $this->fetch();
    }


    function infoSubmit() {
        $_mix_init = $this->init();

        if ($_mix_init !== true) {
            return $this->fetchJson($_mix_init['msg'], $_mix_init['rcode']);
        }

        if (!$this->isAjaxPost) {
            return $this->fetchJson('Access denied', '', 405);
        }

        if (isset($this->adminLogged['admin_allow_profile']['info'])) {
            return $this->fetchJson('You do not have permission', 'x020305');
        }

        $_arr_inputInfo = $this->mdl_profile->inputInfo();

        if ($_arr_inputInfo['rcode'] != 'y020201') {
            return $this->fetchJson($_arr_inputInfo['msg'], $_arr_inputInfo['rcode']);
        }

        $_arr_userRow = $this->mdl_user->read($this->adminLogged['admin_id']);

        if ($_arr_userRow['rcode'] != 'y010102') {
            return $this->fetchJson($_arr_userRow['msg'], $_arr_userRow['rcode']);
        }

        $_str_crypt = Crypt::crypt($_arr_inputInfo['admin_pass'], $_arr_userRow['user_rand']);

        if ($_str_crypt != $_arr_userRow['user_pass']) {
            return $this->fetchJson('Password is incorrect', 'x010201');
        }

        $this->mdl_profile->inputInfo['admin_id'] = $this->adminLogged['admin_id'];

        $_arr_infoResult = $this->mdl_profile->info();

        return $this->fetchJson($_arr_infoResult['msg'], $_arr_infoResult['rcode']);
    }


    function pass() {
        $_mix_init = $this->init();

        if ($_mix_init !== true) {
            return $this->error($_mix_init['msg'], $_mix_init['rcode']);
        }

        if (isset($this->adminLogged['admin_allow_profile']['pass'])) {
            return $this->error('You do not have permission', 'x020305');
        }

        $_arr_tplData = array(
            'token'     => $this->obj_request->token(),
        );

        //print_r($_arr_tplData['timezoneType']);

        $_arr_tpl = array_replace_recursive($this->generalData, $_arr_tplData);

        $this->assign($_arr_tpl);

        return $this->fetch();
    }


    function passSubmit() {
        $_mix_init = $this->init();

        if ($_mix_init !== true) {
            return $this->fetchJson($_mix_init['msg'], $_mix_init['rcode']);
        }

        if (!$this->isAjaxPost) {
            return $this->fetchJson('Access denied', '', 405);
        }

        if (isset($this->adminLogged['admin_allow_profile']['pass'])) {
            return $this->fetchJson('You do not have permission', 'x020305');
        }

        $_arr_inputPass = $this->mdl_profile->inputPass();

        if ($_arr_inputPass['rcode'] != 'y020201') {
            return $this->fetchJson($_arr_inputPass['msg'], $_arr_inputPass['rcode']);
        }

        $_arr_userRow = $this->mdl_user->read($this->adminLogged['admin_id']);

        if ($_arr_userRow['rcode'] != 'y010102') {
            return $this->fetchJson($_arr_userRow['msg'], $_arr_userRow['rcode']);
        }

        $_str_crypt = Crypt::crypt($_arr_inputPass['admin_pass'], $_arr_userRow['user_rand']);

        if ($_str_crypt != $_arr_userRow['user_pass']) {
            return $this->fetchJson('Password is incorrect', 'x010201');
        }

        $_str_rand     = Func::rand();

        $_str_passNew  = Crypt::crypt($_arr_inputPass['admin_pass_new'], $_str_rand);

        $_arr_passResult   = $this->mdl_user->pass($this->adminLogged['admin_id'], $_str_passNew, $_str_rand);

        return $this->fetchJson($_arr_passResult['msg'], $_arr_passResult['rcode']);
    }


    function secqa() {
        $_mix_init = $this->init();

        if ($_mix_init !== true) {
            return $this->error($_mix_init['msg'], $_mix_init['rcode']);
        }

        if (isset($this->adminLogged['admin_allow_profile']['secqa'])) {
            return $this->error('You do not have permission', 'x020305');
        }

        $_arr_secqaRows    = Config::get('secqa', 'console.profile');

        $_arr_userRow = $this->mdl_user->read($this->adminLogged['admin_id']);

        $_arr_tplData = array(
            'userRow'   => $_arr_userRow,
            'secqaRows' => $_arr_secqaRows,
            'token'     => $this->obj_request->token(),
        );

        $_arr_tpl = array_replace_recursive($this->generalData, $_arr_tplData);

        $this->assign($_arr_tpl);

        return $this->fetch();
    }


    function secqaSubmit() {
        $_mix_init = $this->init();

        if ($_mix_init !== true) {
            return $this->fetchJson($_mix_init['msg'], $_mix_init['rcode']);
        }

        if (!$this->isAjaxPost) {
            return $this->fetchJson('Access denied', '', 405);
        }

        if (isset($this->adminLogged['admin_allow_profile']['secqa'])) {
            return $this->fetchJson('You do not have permission', 'x020305');
        }

        $_arr_inputSecqa = $this->mdl_profile->inputSecqa();

        if ($_arr_inputSecqa['rcode'] != 'y020201') {
            return $this->fetchJson($_arr_inputSecqa['msg'], $_arr_inputSecqa['rcode']);
        }

        $_arr_userRow = $this->mdl_user->read($this->adminLogged['admin_id']);

        if ($_arr_userRow['rcode'] != 'y010102') {
            return $this->fetchJson($_arr_userRow['msg'], $_arr_userRow['rcode']);
        }

        $_str_crypt = Crypt::crypt($_arr_inputSecqa['admin_pass'], $_arr_userRow['user_rand']);

        if ($_str_crypt != $_arr_userRow['user_pass']) {
            return $this->fetchJson('Password is incorrect', 'x010201');
        }

        $_arr_inputSecqa['admin_sec_ques']    = Arrays::toJson($_arr_inputSecqa['admin_sec_ques']);
        $_arr_inputSecqa['admin_sec_answ']    = Arrays::toJson($_arr_inputSecqa['admin_sec_answ']);

        $this->mdl_user->inputSecqa['user_sec_ques']    = $_arr_inputSecqa['admin_sec_ques'];
        $this->mdl_user->inputSecqa['user_sec_answ']    = Crypt::crypt($_arr_inputSecqa['admin_sec_answ'], $_arr_userRow['user_name']);
        $this->mdl_user->inputSecqa['user_id']          = $this->adminLogged['admin_id'];

        $_arr_secqaResult    = $this->mdl_user->secqa();

        return $this->fetchJson($_arr_secqaResult['msg'], $_arr_secqaResult['rcode']);
    }


    function mailbox() {
        $_mix_init = $this->init();

        if ($_mix_init !== true) {
            return $this->error($_mix_init['msg'], $_mix_init['rcode']);
        }

        if (isset($this->adminLogged['admin_allow_profile']['mailbox'])) {
            return $this->error('You do not have permission', 'x020305');
        }

        $_arr_userRow = $this->mdl_user->read($this->adminLogged['admin_id']);

        $_arr_tplData = array(
            'userRow'   => $_arr_userRow,
            'token'     => $this->obj_request->token(),
        );

        $_arr_tpl = array_replace_recursive($this->generalData, $_arr_tplData);

        $this->assign($_arr_tpl);

        return $this->fetch();
    }


    function mailboxSubmit() {
        $_mix_init = $this->init();

        if ($_mix_init !== true) {
            return $this->fetchJson($_mix_init['msg'], $_mix_init['rcode']);
        }

        if (!$this->isAjaxPost) {
            return $this->fetchJson('Access denied', '', 405);
        }

        if (isset($this->adminLogged['admin_allow_profile']['mailbox'])) {
            return $this->fetchJson('You do not have permission', 'x020305');
        }

        $_arr_inputMailbox = $this->mdl_profile->inputMailbox();

        if ($_arr_inputMailbox['rcode'] != 'y020201') {
            return $this->fetchJson($_arr_inputMailbox['msg'], $_arr_inputMailbox['rcode']);
        }

        $_arr_userRow = $this->mdl_user->read($this->adminLogged['admin_id']);

        if ($_arr_userRow['rcode'] != 'y010102') {
            return $this->fetchJson($_arr_userRow['msg'], $_arr_userRow['rcode']);
        }

        $_str_crypt = Crypt::crypt($_arr_inputMailbox['admin_pass'], $_arr_userRow['user_rand']);

        if ($_str_crypt != $_arr_userRow['user_pass']) {
            return $this->fetchJson('Password is incorrect', 'x010201');
        }


        if (isset($_arr_inputMailbox['admin_mail_new']) && !Func::isEmpty($_arr_inputMailbox['admin_mail_new'])) {
            $_arr_userRowChk = $this->mdl_user->read($_arr_inputMailbox['admin_mail_new'], 'user_mail', $_arr_userRow['user_id']); //检查邮箱
            if ($_arr_userRowChk['rcode'] == 'y010102') {
                return $this->fetchJson('Mailbox already exists', 'x010201');
            }
        }

        if ($this->config['var_extra']['reg']['reg_confirm'] === 'on') {
            $_arr_submitResult    = $this->mdl_verify->submit($_arr_userRow['user_id'], $_arr_inputMailbox['admin_mail_new'], 'mailbox');

            if ($_arr_submitResult['rcode'] != 'y120101' && $_arr_submitResult['rcode'] != 'y120103') {
                return $this->fetchJson('Mailbox already exists', 'x010405');
            }

            $_str_verifyUrl = $this->generalData['url_personal'] . 'verify/mailbox/id/' . $_arr_submitResult['verify_id'] . '/token/' . $_arr_submitResult['verify_token'] . '/';

            $_arr_src   = array('{:verify_url}', '{:site_name}', '{:user_name}', '{:user_mail}', '{:user_mail_new}');

            $_arr_dst   = array($_str_verifyUrl, $this->config['var_extra']['base']['site_name'], $_arr_userRow['user_name'], $_arr_userRow['user_mail'], $_arr_inputMailbox['user_mail_new']);

            $_str_html  = str_ireplace($_arr_src, $_arr_dst, $this->config['extra_mailtpl']['mailbox_content']);

            $_obj_smtp = Smtp::instance();

            $_obj_smtp->addRcpt($_arr_inputMailbox['admin_mail_new']); //发送至
            $_obj_smtp->setSubject($this->config['extra_mailtpl']['mailbox_subject']); //主题
            $_obj_smtp->setContent($_str_html); //内容
            $_obj_smtp->setContentAlt(strip_tags($_str_html)); //内容

            if (!$_obj_smtp->send()) {
                $_arr_error = $_obj_smtp->getError();
                $_str_msg   = end($_arr_error);

                if (Func::isEmpty($_str_msg)) {
                    $_str_msg = 'Send verification email failed';
                }

                return $this->fetchJson($_str_msg, 'x010405');
            }

            $_arr_mailboxResult = array(
                'rcode' => 'y010405',
                'msg'   => $this->obj_lang->get('A verification email has been sent to your new mailbox, please verify.'),
            );
        } else {
            $this->mdl_user->inputMailbox['user_id']        = $this->adminLogged['admin_id'];
            $this->mdl_user->inputMailbox['user_mail_new']  = $_arr_inputMailbox['admin_mail_new'];

            $_arr_mailboxResult    = $this->mdl_user->mailbox();
        }

        return $this->fetchJson($_arr_mailboxResult['msg'], $_arr_mailboxResult['rcode']);
    }
}
