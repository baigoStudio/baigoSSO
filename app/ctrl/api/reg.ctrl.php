<?php
/*-----------------------------------------------------------------
！！！！警告！！！！
以下为系统文件，请勿修改
-----------------------------------------------------------------*/

namespace app\ctrl\api;

use app\classes\api\Ctrl;
use ginkgo\Loader;
use ginkgo\Crypt;
use ginkgo\Config;
use ginkgo\Json;
use ginkgo\Sign;
use ginkgo\Func;
use ginkgo\Smtp;

// 不能非法包含或直接执行
defined('IN_GINKGO') or exit('Access denied');

class Reg extends Ctrl {

    protected function c_init($param = array()) {
        parent::c_init();

        $this->mdl_reg       = Loader::model('Reg');

        $this->configReg     = Config::get('reg', 'var_extra');
    }


    function reg() {
        $_mix_init = $this->init();

        if ($_mix_init !== true) {
            return $this->fetchJson($_mix_init['msg'], $_mix_init['rcode']);
        }

        if (!$this->isPost) {
            return $this->fetchJson('Access denied', '', 405);
        }

        //print_r($this->configReg);

        if ($this->configReg['reg_acc'] != 'on') {
            return $this->fetchJson('Registration is prohibited', 'x050316');
        }

        //print_r($this->appRow);

        if (!isset($this->appRow['app_allow']['user']['reg'])) {
            return $this->fetchJson('Permission denied', 'x050307');
        }

        $_arr_inputReg = $this->mdl_reg->inputReg($this->decryptRow);

        if ($_arr_inputReg['rcode'] != 'y010201') {
            return $this->fetchJson($_arr_inputReg['msg'], $_arr_inputReg['rcode']);
        }

        $_str_rand = Func::rand();

        $this->mdl_reg->inputReg['user_pass']      = Crypt::crypt($_arr_inputReg['user_pass'], $_str_rand, true);
        $this->mdl_reg->inputReg['user_rand']      = $_str_rand;
        $this->mdl_reg->inputReg['user_app_id']    = $this->appRow['app_id'];

        $_arr_regResult = $this->mdl_reg->reg();

        if ($_arr_regResult['rcode'] != 'y010101') {
            return $this->fetchJson($_arr_regResult['msg'], $_arr_regResult['rcode']);
        }

        $_mdl_appBelong   = Loader::model('App_Belong');

        $_mdl_appBelong->submit($this->appRow['app_id'], $_arr_regResult['user_id']); //用户授权

        if ($this->configReg['reg_confirm'] === 'on') { //开启验证则为等待
            $_mdl_verify   = Loader::model('Verify');

            $_arr_verifySubmitResult    = $_mdl_verify->submit($_arr_regResult['user_id'], $_arr_inputReg['user_mail'], 'confirm');

            if ($_arr_verifySubmitResult['rcode'] != 'y120101' && $_arr_verifySubmitResult['rcode'] != 'y120103') { //生成验证失败
                return $this->fetchJson('Send confirmation email failed', 'x010407');
            }

            $_str_verifyUrl = $this->url['url_personal'] . 'verify/confirm/id/' . $_arr_verifySubmitResult['verify_id'] . '/token/' . $_arr_verifySubmitResult['verify_token'] . '/';

            $_arr_src   = array('{:verify_url}', '{:site_name}', '{:user_name}', '{:user_mail}');

            $_arr_dst   = array($_str_verifyUrl, $this->configBase['site_name'], $_arr_inputReg['user_name'], $_arr_inputReg['user_mail']);

            $_str_html  = str_ireplace($_arr_src, $_arr_dst, $this->configMailtpl['reg_content']);

            $_obj_smtp = Smtp::instance();

            if (!$_obj_smtp->connect()) {
                $_arr_error = $_obj_smtp->getError();
                return $this->fetchJson(end($_arr_error), 'x010407');
            }

            $_obj_smtp->addRcpt($_arr_regResult['user_mail']); //发送至
            $_obj_smtp->setSubject($this->configMailtpl['reg_subject']); //主题
            $_obj_smtp->setContent($_str_html); //内容
            $_obj_smtp->setContentAlt(strip_tags($_str_html)); //内容

            if (!$_obj_smtp->send()) {
                $_arr_error = $_obj_smtp->getError();
                return $this->fetchJson(end($_arr_error), 'x010407');
            }
        }

        $_arr_regResult['timestamp'] = GK_NOW;

        $_str_src   = Json::encode($_arr_regResult);

        $_str_sign  = Sign::make($_str_src, $this->appRow['app_key'] . $this->appRow['app_secret']);

        $this->notify($_str_src, 'reg'); //通知

        $_str_encrypt  = Crypt::encrypt($_str_src, $this->appRow['app_key'], $this->appRow['app_secret']);

        if ($_str_encrypt === false) {
            $_str_error = Crypt::getError();
            return $this->fetchJson($_str_error, 'x050405', 200, 'api.common');
        }

        $_arr_data = array(
            'rcode' => $_arr_regResult['rcode'],
            'msg'   => $this->obj_lang->get($_arr_regResult['msg']),
            'code'  => $_str_encrypt,
            'sign'  => $_str_sign,
        );

        $_arr_tpl = array_replace_recursive($this->version, $_arr_data);

        return $this->json($_arr_tpl);
    }


    function chkname() {
        $_mix_init = $this->init();

        if ($_mix_init !== true) {
            return $this->fetchJson($_mix_init['msg'], $_mix_init['rcode']);
        }

        $_arr_inputChkname = $this->mdl_reg->inputChkname($this->decryptRow);

        if ($_arr_inputChkname['rcode'] != 'y010201') {
            return $this->fetchJson($_arr_inputChkname['msg'], $_arr_inputChkname['rcode']);
        }

        $_arr_checkResult = $this->mdl_reg->check($_arr_inputChkname['user_name'], 'user_name');

        if ($_arr_checkResult['rcode'] == 'y010102') {
            $_str_rcode = 'x010404';
            $_str_msg   = $this->obj_lang->get('User already exists');
        } else {
            $_str_rcode = 'y010401';
            $_str_msg   = $this->obj_lang->get('Username can be registered');
        }

        $_arr_return = array(
            'rcode' => $_str_rcode,
            'msg'   => $_str_msg,
        );

        $_arr_tpl = array_replace_recursive($this->version, $_arr_return);

        return $this->json($_arr_tpl);
    }


    function chkmail() {
        $_mix_init = $this->init();

        if ($_mix_init !== true) {
            return $this->fetchJson($_mix_init['msg'], $_mix_init['rcode']);
        }

        $_arr_inputChkmail = $this->mdl_reg->inputChkmail($this->decryptRow);

        if ($_arr_inputChkmail['rcode'] != 'y010201') {
            return $this->fetchJson($_arr_inputChkmail['msg'], $_arr_inputChkmail['rcode']);
        }

        $_str_rcode = 'y010401';
        $_str_msg   = $this->obj_lang->get('Mailbox can be used');

        $_arr_checkResult = $this->mdl_reg->check($_arr_inputChkmail['user_mail'], 'user_mail');

        if ($_arr_checkResult['rcode'] == 'y010102') {
            $_str_rcode = 'x010404';
            $_str_msg   = $this->obj_lang->get('Mailbox already exists');
        }

        $_arr_return = array(
            'rcode' => $_str_rcode,
            'msg'   => $_str_msg,
        );

        $_arr_tpl = array_replace_recursive($this->version, $_arr_return);

        return $this->json($_arr_tpl);
    }
}
