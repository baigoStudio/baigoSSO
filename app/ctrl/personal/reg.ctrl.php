<?php
/*-----------------------------------------------------------------
！！！！警告！！！！
以下为系统文件，请勿修改
-----------------------------------------------------------------*/

namespace app\ctrl\personal;

use app\classes\personal\Ctrl;
use ginkgo\Loader;
use ginkgo\Crypt;
use ginkgo\Func;
use ginkgo\Smtp;
use ginkgo\Config;

// 不能非法包含或直接执行
if (!defined('IN_GINKGO')) {
    return 'Access denied';
}


/*-------------管理员控制器-------------*/
class Reg extends Ctrl {

    protected function c_init($param = array()) {
        parent::c_init();

        $this->mdl_reg          = Loader::model('Reg');
        $this->mdl_verify       = Loader::model('Verify');

        $this->configReg        = Config::get('reg', 'var_extra');
    }


    function index() {
        if ($this->configReg['reg_acc'] != 'on') {
            return $this->error('Registration is prohibited', 'x050316');
        }

        $_arr_tplData = array(
            'token'     => $this->obj_request->token(),
        );

        $_arr_tpl = array_replace_recursive($this->generalData, $_arr_tplData);

        $this->assign($_arr_tpl);

        return $this->fetch();
    }


    function submit() {
        if (!$this->isAjaxPost) {
            return $this->fetchJson('Access denied', '', 405);
        }

        if ($this->configReg['reg_acc'] != 'on') {
            return $this->fetchJson('Registration is prohibited', 'x050316');
        }

        $_arr_inputReg = $this->mdl_reg->inputReg();

        if ($_arr_inputReg['rcode'] != 'y010201') {
            return $this->fetchJson($_arr_inputReg['msg'], $_arr_inputReg['rcode']);
        }

        $_str_rand = Func::rand();

        $this->mdl_reg->inputReg['user_pass']      = Crypt::crypt($_arr_inputReg['user_pass'], $_str_rand);
        $this->mdl_reg->inputReg['user_rand']      = $_str_rand;

        $_arr_regResult = $this->mdl_reg->reg();

        if ($_arr_regResult['rcode'] != 'y010101') {
            return $this->fetchJson($_arr_regResult['msg'], $_arr_regResult['rcode']);
        }

        if ($this->configReg['reg_confirm'] === 'on') { //开启验证则为等待
            $_arr_verifySubmitResult    = $this->mdl_verify->submit($_arr_regResult['user_id'], $_arr_inputReg['user_mail'], 'confirm');

            if ($_arr_verifySubmitResult['rcode'] != 'y120101' && $_arr_verifySubmitResult['rcode'] != 'y120103') { //生成验证失败
                return $this->fetchJson('Send verification email failed', 'x010407');
            }

            $_str_verifyUrl = $this->url['url_personal'] . 'verify/confirm/id/' . $_arr_verifySubmitResult['verify_id'] . '/token/' . $_arr_verifySubmitResult['verify_token'] . '/';

            $_arr_src   = array('{:verify_url}', '{:site_name}', '{:user_name}', '{:user_mail}');

            $_arr_dst   = array($_str_verifyUrl, $this->configBase['site_name'], $_arr_inputReg['user_name'], $_arr_inputReg['user_mail']);

            $_str_html  = str_ireplace($_arr_src, $_arr_dst, $this->configMailtpl['reg_content']);

            $_obj_smtp = Smtp::instance();

            $_obj_smtp->addRcpt($_arr_regResult['user_mail']); //发送至
            $_obj_smtp->setSubject($this->configMailtpl['reg_subject']); //主题
            $_obj_smtp->setContent($_str_html); //内容
            $_obj_smtp->setContentAlt(strip_tags($_str_html)); //内容

            if (!$_obj_smtp->send()) {
                $_arr_error = $_obj_smtp->getError();
                $_str_msg   = end($_arr_error);

                if (Func::isEmpty($_str_msg)) {
                    $_str_msg = 'Send verification email failed';
                }

                return $this->fetchJson(end($_arr_error), 'x010407');
            }
        }

        return $this->fetchJson($_arr_regResult['msg'], $_arr_regResult['rcode']);
    }


    function nomail() {
        if ($this->configReg['reg_acc'] != 'on') {
            return $this->error('Registration is prohibited', 'x050316');
        }

        $_arr_tplData = array(
            'token'     => $this->obj_request->token(),
        );

        $_arr_tpl = array_replace_recursive($this->generalData, $_arr_tplData);

        $this->assign($_arr_tpl);

        return $this->fetch();
    }


    function nomailSubmit() {
        if (!$this->isAjaxPost) {
            return $this->fetchJson('Access denied', '', 405);
        }

        if ($this->configReg['reg_acc'] != 'on') {
            return $this->fetchJson('Registration is prohibited', 'x050316');
        }

        $_arr_inputNomail = $this->mdl_reg->inputNomail();

        if ($_arr_inputNomail['rcode'] != 'y010201') {
            return $this->fetchJson($_arr_inputNomail['msg'], $_arr_inputNomail['rcode']);
        }

        $_arr_userRow = $this->mdl_user->read($_arr_inputNomail['user_name'], 'user_name');
        if ($_arr_userRow['rcode'] != 'y010102') {
            return $this->fetchJson($_arr_userRow['msg'], $_arr_userRow['rcode']);
        }

        if ($_arr_userRow['user_status'] != 'wait') {
            return $this->fetchJson('Repeat activation is unnecessary', 'x010409');
        }

        $_arr_submitResult = $this->mdl_verify->submit($_arr_userRow['user_id'], $_arr_userRow['user_mail'], 'confirm');

        if ($_arr_submitResult['rcode'] != 'y120101' && $_arr_submitResult['rcode'] != 'y120103') {
            return $this->fetchJson('Send verification email failed', 'x010407');
        }

        $_str_verifyUrl = $this->generalData['url_personal'] . 'verify/confirm/id/' . $_arr_submitResult['verify_id'] . '/token/' . $_arr_submitResult['verify_token'] . '/';

        $_arr_src   = array('{:verify_url}', '{:site_name}', '{:user_name}', '{:user_mail}');

        $_arr_dst   = array($_str_verifyUrl, $this->configBase['site_name'], $_arr_userRow['user_name'], $_arr_userRow['user_mail']);

        $_str_html  = str_ireplace($_arr_src, $_arr_dst, $this->configMailtpl['reg_content']);

        //print_r($_str_html);

        $_obj_smtp  = Smtp::instance();

        $_obj_smtp->addRcpt($_arr_userRow['user_mail']); //发送至
        $_obj_smtp->setSubject($this->configMailtpl['reg_subject']); //主题
        $_obj_smtp->setContent($_str_html); //内容
        $_obj_smtp->setContentAlt(strip_tags($_str_html)); //内容

        if (!$_obj_smtp->send()) {
            $_arr_error = $_obj_smtp->getError();
            $_str_msg   = end($_arr_error);

            if (Func::isEmpty($_str_msg)) {
                $_str_msg = 'Send verification email failed';
            }

            return $this->fetchJson($_str_msg, 'x010407');
        }

        return $this->fetchJson('A verification email has been sent to your mailbox, please verify.', 'y010407');
    }


    function chkname() {
        $_arr_return = array(
            'msg' => '',
        );

        $_arr_inputChkname = $this->mdl_reg->inputChkname();

        if ($_arr_inputChkname['rcode'] != 'y010201') {
            $_arr_return = array(
                'rcode'     => $_arr_inputChkname['rcode'],
                'error_msg' => $this->obj_lang->get($_arr_inputChkname['msg']),
            );
        }

        return $this->json($_arr_return);
    }


    function chkmail() {
        $_arr_return = array(
            'msg' => '',
        );

        $_arr_inputChkmail = $this->mdl_reg->inputChkmail();

        if ($_arr_inputChkmail['rcode'] != 'y010201') {
            $_arr_return = array(
                'rcode'     => $_arr_inputChkmail['rcode'],
                'error_msg' => $this->obj_lang->get($_arr_inputChkmail['msg']),
            );
        }

        return $this->json($_arr_return);
    }
}
