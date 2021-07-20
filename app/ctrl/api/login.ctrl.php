<?php
/*-----------------------------------------------------------------
！！！！警告！！！！
以下为系统文件，请勿修改
-----------------------------------------------------------------*/

namespace app\ctrl\api;

use app\classes\api\Ctrl;
use ginkgo\Loader;
use ginkgo\Crypt;
use ginkgo\Arrays;
use ginkgo\Sign;
use ginkgo\Func;

// 不能非法包含或直接执行
if (!defined('IN_GINKGO')) {
    return 'Access denied';
}

class Login extends Ctrl {

    protected function c_init($param = array()) {
        parent::c_init();

        $this->mdl_login    = Loader::model('Login');
    }


    function login() {
        $_mix_init = $this->init();

        if ($_mix_init !== true) {
            return $this->fetchJson($_mix_init['msg'], $_mix_init['rcode']);
        }

        if (!$this->isPost) {
            return $this->fetchJson('Access denied', '', 405);
        }

        $_arr_inputSubmit = $this->mdl_login->inputSubmit($this->decryptRow);

        if ($_arr_inputSubmit['rcode'] != 'y010201') {
            return $this->fetchJson($_arr_inputSubmit['msg'], $_arr_inputSubmit['rcode']);
        }

        $_arr_userRow  = $this->mdl_login->read($_arr_inputSubmit['user_str'], $_arr_inputSubmit['user_by']);

        if ($_arr_userRow['rcode'] != 'y010102') {
            return $this->fetchJson($_arr_userRow['msg'], $_arr_userRow['rcode']);
        }

        if ($_arr_userRow['user_status'] == 'disabled') {
            return $this->fetchJson('User is disabled', 'x010402');
        }

        $_str_crypt = Crypt::crypt($_arr_inputSubmit['user_pass'], $_arr_userRow['user_rand'], true);

        if ($_str_crypt != $_arr_userRow['user_pass']) {
            return $this->fetchJson('Password is incorrect', 'x010201');
        }

        $this->mdl_login->inputSubmit['user_id'] = $_arr_userRow['user_id'];

        $_arr_loginResult = $this->mdl_login->login();

        if ($_arr_loginResult['rcode'] != 'y010103') {
            return $this->fetchJson($_arr_loginResult['msg'], $_arr_loginResult['rcode']);
        }

        $_arr_loginResult['timestamp'] = GK_NOW;

        $_str_src       = Arrays::toJson($_arr_loginResult);

        $_str_sign      = Sign::make($_str_src, $this->appRow['app_key'] . $this->appRow['app_secret']);

        $_str_encrypt   = Crypt::encrypt($_str_src, $this->appRow['app_key'], $this->appRow['app_secret']);

        if ($_str_encrypt === false) {
            $_str_error = Crypt::getError();
            return $this->fetchJson($_str_error, 'x050405', 200, 'api.common');
        }

        $_arr_data = array(
            'rcode' => $_arr_loginResult['rcode'],
            'msg'   => $this->obj_lang->get($_arr_loginResult['msg']),
            'code'  => $_str_encrypt,
            'sign'  => $_str_sign,
        );

        $_arr_tpl = array_replace_recursive($this->version, $_arr_data);

        return $this->json($_arr_tpl);
    }
}
