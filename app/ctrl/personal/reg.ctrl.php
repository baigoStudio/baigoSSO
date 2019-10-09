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

//不能非法包含或直接执行
defined('IN_GINKGO') or exit('Access denied');


/*-------------管理员控制器-------------*/
class Reg extends Ctrl {

    protected function c_init($param = array()) {
        parent::c_init();

        $this->mdl_reg          = Loader::model('Reg');
    }


    function nomail() {
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

        $_arr_inputNomail = $this->mdl_reg->inputNomail();

        if ($_arr_inputNomail['rcode'] != 'y010201') {
            return $this->fetchJson($_arr_inputNomail['msg'], $_arr_inputNomail['rcode']);
        }

        $_arr_userRow = $this->mdl_user->read($_arr_inputNomail['user_name'], 'user_name');
        if ($_arr_userRow['rcode'] != 'y010102') {
            return $this->fetchJson('User not found', $_arr_userRow['rcode']);
        }

        if ($_arr_userRow['user_status'] != 'wait') {
            return $this->fetchJson('Repeat activation is unnecessary', 'x010409');
        }

        $_arr_submitResult = $this->mdl_verify->submit($_arr_userRow['user_id'], $_arr_userRow['user_mail'], 'confirm');

        if ($_arr_submitResult['rcode'] != 'y120101' && $_arr_submitResult['rcode'] != 'y120103') {
            return $this->fetchJson('Send confirmation email failed', 'x010407');
        }

        $_str_verifyUrl = $this->generalData['url_personal'] . 'verify/confirm/id/' . $_arr_submitResult['verify_id'] . '/token/' . $_arr_submitResult['verify_token'] . '/';

        $_arr_src   = array('{:verify_url}', '{:site_name}', '{:user_name}', '{:user_mail}');

        $_arr_dst   = array($_str_verifyUrl, $this->configBase['site_name'], $_arr_userRow['user_name'], $_arr_userRow['user_mail']);

        $_str_html  = str_ireplace($_arr_src, $_arr_dst, $this->configMailtpl['reg_content']);

        //print_r($_str_html);

        $_obj_smtp  = Smtp::instance();

        if (!$_obj_smtp->connect()) {
            $_arr_error = $_obj_smtp->getError();
            return $this->fetchJson(end($_arr_error), 'x010407');
        }

        $_obj_smtp->addRcpt($_arr_userRow['user_mail']); //发送至
        $_obj_smtp->setSubject($this->configMailtpl['reg_subject']); //主题
        $_obj_smtp->setContent($_str_html); //内容
        $_obj_smtp->setContentAlt(strip_tags($_str_html)); //内容

        if (!$_obj_smtp->send()) {
            $_arr_error = $_obj_smtp->getError();
            return $this->fetchJson(end($_arr_error), 'x010407');
        }

        return $this->fetchJson('A verification email has been sent to your mailbox, please verify.', 'y010407');
    }
}
