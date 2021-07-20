<?php
/*-----------------------------------------------------------------
！！！！警告！！！！
以下为系统文件，请勿修改
-----------------------------------------------------------------*/

namespace app\ctrl\api;

use app\classes\api\Ctrl;
use ginkgo\Loader;
use ginkgo\Config;
use ginkgo\Crypt;
use ginkgo\Arrays;
use ginkgo\Smtp;
use ginkgo\Sign;
use ginkgo\Func;

// 不能非法包含或直接执行
if (!defined('IN_GINKGO')) {
    return 'Access denied';
}

class Profile extends Ctrl {

    protected function c_init($param = array()) {
        parent::c_init();

        $this->mdl_profile  = Loader::model('Profile');

        $this->configReg     = Config::get('reg', 'var_extra');
    }


    function info() {
        $_mix_init = $this->init();

        if ($_mix_init !== true) {
            return $this->fetchJson($_mix_init['msg'], $_mix_init['rcode']);
        }

        if (!$this->isPost) {
            return $this->fetchJson('Access denied', '', 405);
        }

        $_arr_inputInfo = $this->mdl_profile->inputInfo($this->decryptRow);

        if ($_arr_inputInfo['rcode'] != 'y010201') {
            return $this->fetchJson($_arr_inputInfo['msg'], $_arr_inputInfo['rcode']);
        }

        $_arr_userRow  = $this->mdl_profile->read($_arr_inputInfo['user_id']);

        if ($_arr_userRow['rcode'] != 'y010102') {
            return $this->fetchJson($_arr_userRow['msg'], $_arr_userRow['rcode']);
        }

        $_str_crypt = Crypt::crypt($_arr_inputInfo['user_pass'], $_arr_userRow['user_rand'], true);

        if ($_str_crypt != $_arr_userRow['user_pass']) {
            return $this->fetchJson('Password is incorrect', 'x010201');
        }

        $this->mdl_profile->inputInfo['user_id'] = $_arr_userRow['user_id'];

        $_arr_infoResult  = $this->mdl_profile->info();

        if ($_arr_infoResult['rcode'] != 'y010103') {
            return $this->fetchJson($_arr_infoResult['msg'], $_arr_infoResult['rcode']);
        }

        $_arr_infoResult['timestamp'] = GK_NOW;

        $_str_src   = Arrays::toJson($_arr_infoResult);

        $this->notify($_str_src, 'info'); //通知

        $_arr_tpl = array_replace_recursive($this->version, $_arr_infoResult);

        $_arr_tpl['msg'] = $this->obj_lang->get($_arr_tpl['msg']);

        return $this->json($_arr_tpl);
    }


    function pass() {
        $_mix_init = $this->init();

        if ($_mix_init !== true) {
            return $this->fetchJson($_mix_init['msg'], $_mix_init['rcode']);
        }

        if (!$this->isPost) {
            return $this->fetchJson('Access denied', '', 405);
        }

        $_arr_inputPass = $this->mdl_profile->inputPass($this->decryptRow);

        if ($_arr_inputPass['rcode'] != 'y010201') {
            return $this->fetchJson($_arr_inputPass['msg'], $_arr_inputPass['rcode']);
        }

        $_arr_userRow  = $this->mdl_profile->read($_arr_inputPass['user_id']);

        if ($_arr_userRow['rcode'] != 'y010102') {
            return $this->fetchJson($_arr_userRow['msg'], $_arr_userRow['rcode']);
        }

        $_str_crypt = Crypt::crypt($_arr_inputPass['user_pass'], $_arr_userRow['user_rand'], true);

        if ($_str_crypt != $_arr_userRow['user_pass']) {
            return $this->fetchJson('The old password is incorrect', 'x010201');
        }

        $_str_rand          = Func::rand();

        $_str_passNew       = Crypt::crypt($_arr_inputPass['user_pass_new'], $_str_rand, true);

        $_arr_passResult    = $this->mdl_profile->pass($_arr_userRow['user_id'], $_str_passNew, $_str_rand);

        $_arr_tpl = array_replace_recursive($this->version, $_arr_passResult);

        $_arr_tpl['msg'] = $this->obj_lang->get($_arr_tpl['msg']);

        return $this->json($_arr_tpl);
    }


    function secqa() {
        $_mix_init = $this->init();

        if ($_mix_init !== true) {
            return $this->fetchJson($_mix_init['msg'], $_mix_init['rcode']);
        }

        if (!$this->isPost) {
            return $this->fetchJson('Access denied', '', 405);
        }

        $_arr_inputSecqa = $this->mdl_profile->inputSecqa($this->decryptRow);

        if ($_arr_inputSecqa['rcode'] != 'y010201') {
            return $this->fetchJson($_arr_inputSecqa['msg'], $_arr_inputSecqa['rcode']);
        }

        $_arr_userRow  = $this->mdl_profile->read($_arr_inputSecqa['user_id']);

        if ($_arr_userRow['rcode'] != 'y010102') {
            return $this->fetchJson($_arr_userRow['msg'], $_arr_userRow['rcode']);
        }

        $_str_crypt = Crypt::crypt($_arr_inputSecqa['user_pass'], $_arr_userRow['user_rand'], true);

        if ($_str_crypt != $_arr_userRow['user_pass']) {
            return $this->fetchJson('The old password is incorrect', 'x010201');
        }

        $this->mdl_profile->inputSecqa['user_id']          = $_arr_userRow['user_id'];
        $this->mdl_profile->inputSecqa['user_sec_answ']    = Crypt::crypt($_arr_inputSecqa['user_sec_answ'], $_arr_userRow['user_name'], true);

        $_arr_secqaResult = $this->mdl_profile->secqa();

        $_arr_tpl = array_replace_recursive($this->version, $_arr_secqaResult);

        $_arr_tpl['msg'] = $this->obj_lang->get($_arr_tpl['msg']);

        return $this->json($_arr_tpl);
    }


    function mailbox() {
        $_mix_init = $this->init();

        if ($_mix_init !== true) {
            return $this->fetchJson($_mix_init['msg'], $_mix_init['rcode']);
        }

        if (!$this->isPost) {
            return $this->fetchJson('Access denied', '', 405);
        }

        $_arr_inputMailbox = $this->mdl_profile->inputMailbox($this->decryptRow);

        if ($_arr_inputMailbox['rcode'] != 'y010201') {
            return $this->fetchJson($_arr_inputMailbox['msg'], $_arr_inputMailbox['rcode']);
        }

        $_arr_userRow  = $this->mdl_profile->read($_arr_inputMailbox['user_id']);

        if ($_arr_userRow['rcode'] != 'y010102') {
            return $this->fetchJson($_arr_userRow['msg'], $_arr_userRow['rcode']);
        }

        $_str_crypt = Crypt::crypt($_arr_inputMailbox['user_pass'], $_arr_userRow['user_rand'], true);

        if ($_str_crypt != $_arr_userRow['user_pass']) {
            return $this->fetchJson('The old password is incorrect', 'x010201');
        }

        if ($this->configReg['reg_confirm'] === 'on') {
            $_mdl_verify    = Loader::model('Verify');

            $_arr_returnRow = $_mdl_verify->submit($_arr_userRow['user_id'], $_arr_inputMailbox['user_mail_new'], 'mailbox');

            if ($_arr_returnRow['rcode'] != 'y120101' && $_arr_returnRow['rcode'] != 'y120103') {
                return $this->fetchJson('Mailbox already exists', 'x010405');
            }

            $_str_verifyUrl = $this->generalData['url_personal'] . 'verify/mailbox/id/' . $_arr_returnRow['verify_id'] . '/token/' . $_arr_returnRow['verify_token'] . '/';

            $_arr_src   = array('{:verify_url}', '{:site_name}', '{:user_name}', '{:user_mail}', '{:user_mail_new}');

            $_arr_dst   = array($_str_verifyUrl, $this->configBase['site_name'], $_arr_userRow['user_name'], $_arr_userRow['user_mail'], $_arr_inputMailbox['user_mail_new']);

            $_str_html  = str_ireplace($_arr_src, $_arr_dst, $this->configMailtpl['mailbox_content']);

            $_obj_smtp = Smtp::instance();

            $_obj_smtp->addRcpt($_arr_inputMailbox['user_mail_new']); //发送至
            $_obj_smtp->setSubject($this->configMailtpl['mailbox_subject']); //主题
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
                'msg'   => 'A verification email has been sent to your new mailbox, please verify.',
            );
        } else {
            $this->mdl_profile->inputMailbox['user_id'] = $_arr_userRow['user_id'];

            $_arr_mailboxResult = $this->mdl_profile->mailbox();
        }

        $_arr_mailboxResult['timestamp'] = GK_NOW;

        $_str_src   = Arrays::toJson($_arr_mailboxResult);

        $this->notify($_str_src, 'mailbox'); //通知

        $_arr_tpl = array_replace_recursive($this->version, $_arr_mailboxResult);

        $_arr_tpl['msg'] = $this->obj_lang->get($_arr_tpl['msg']);

        return $this->json($_arr_tpl);
    }


    function token() {
        $_mix_init = $this->init();

        if ($_mix_init !== true) {
            return $this->fetchJson($_mix_init['msg'], $_mix_init['rcode']);
        }

        if (!$this->isPost) {
            return $this->fetchJson('Access denied', '', 405);
        }

        $_arr_inputToken = $this->mdl_profile->inputToken($this->decryptRow);

        if ($_arr_inputToken['rcode'] != 'y010201') {
            return $this->fetchJson($_arr_inputToken['msg'], $_arr_inputToken['rcode']);
        }

        $_arr_userRow  = $this->mdl_profile->read($_arr_inputToken['user_id']);

        if ($_arr_userRow['rcode'] != 'y010102') {
            return $this->fetchJson($_arr_userRow['msg'], $_arr_userRow['rcode']);
        }

        if ($_arr_userRow['user_status'] == 'disabled') {
            return $this->fetchJson('User is disabled', 'x010402');
        }

        if ($_arr_userRow['user_refresh_expire'] < GK_NOW) {
            return $this->fetchJson('Refresh token expired', 'x010201');
        }

        if ($_arr_inputToken['user_refresh_token'] != md5(Crypt::crypt($_arr_userRow['user_refresh_token'], $_arr_userRow['user_name']))) {
            return $this->fetchJson('Refresh token is incorrect', 'x010201');
        }

        //print_r($_arr_userRow);

        $_arr_tokenResult = $this->mdl_profile->token($_arr_userRow['user_id'], $_arr_userRow['user_name']);

        if ($_arr_tokenResult['rcode'] != 'y010103') {
            return $this->fetchJson($_arr_tokenResult['msg'], $_arr_tokenResult['rcode']);
        }

        $_arr_tokenResult['timestamp'] = GK_NOW;

        $_str_src       = Arrays::toJson($_arr_tokenResult);

        $_str_sign      = Sign::make($_str_src, $this->appRow['app_key'] . $this->appRow['app_secret']);

        $_str_encrypt   = Crypt::encrypt($_str_src, $this->appRow['app_key'], $this->appRow['app_secret']);

        if ($_str_encrypt === false) {
            $_str_error = Crypt::getError();
            return $this->fetchJson($_str_error, 'x050405', 200, 'api.common');
        }

        $_arr_data = array(
            'rcode' => $_arr_tokenResult['rcode'],
            'msg'   => $this->obj_lang->get($_arr_tokenResult['msg']),
            'code'  => $_str_encrypt,
            'sign'  => $_str_sign,
        );

        $_arr_tpl = array_replace_recursive($this->version, $_arr_data);

        return $this->json($_arr_tpl);
    }
}
