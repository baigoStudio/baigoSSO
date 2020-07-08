<?php
/*-----------------------------------------------------------------
！！！！警告！！！！
以下为系统文件，请勿修改
-----------------------------------------------------------------*/

namespace app\ctrl\api;

use app\classes\api\Ctrl;
use ginkgo\Loader;
use ginkgo\Crypt;
use ginkgo\Json;
use ginkgo\Sign;
use ginkgo\Func;

// 不能非法包含或直接执行
defined('IN_GINKGO') or exit('Access denied');

class User extends Ctrl {

    protected function c_init($param = array()) {
        parent::c_init();

        $this->mdl_user     = Loader::model('User');
    }


    function edit() {
        $_mix_init = $this->init();

        if ($_mix_init !== true) {
            return $this->fetchJson($_mix_init['msg'], $_mix_init['rcode']);
        }

        if (!$this->isPost) {
            return $this->fetchJson('Access denied', '', 405);
        }

        if (!isset($this->appRow['app_allow']['user']['edit'])) { //是否有编辑权限
            return $this->fetchJson('Permission denied', 'x050307');
        }

        $_arr_inputEdit = $this->mdl_user->inputEdit($this->decryptRow);

        if ($_arr_inputEdit['rcode'] != 'y010201') {
            return $this->fetchJson($_arr_inputEdit['msg'], $_arr_inputEdit['rcode']);
        }

        $_arr_userRow = $this->mdl_user->check($_arr_inputEdit['user_str'], $_arr_inputEdit['user_by']);

        if ($_arr_userRow['rcode'] != 'y010102') {
            return $this->fetchJson($_arr_userRow['msg'], $_arr_userRow['rcode']);
        }

        if (!isset($this->appRow['app_allow']['user']['global'])) { //是否有全局权限
            $_mdl_appBelong   = Loader::model('App_Belong');

            $_arr_belongRow = $_mdl_appBelong->read($this->appRow['app_id'], $_arr_userRow['user_id']);
            if ($_arr_belongRow['rcode'] != 'y070102') {
                return $this->fetchJson('Permission denied', $_arr_belongRow['rcode']);
            }
        }

        if (!Func::isEmpty($_arr_inputEdit['user_mail_new'])) {
            $_arr_checkResult = $this->mdl_user->check($_arr_inputEdit['user_mail_new'], 'user_mail', $_arr_userRow['user_id']); //检查邮箱
            if ($_arr_checkResult['rcode'] == 'y010102') {
                return $this->fetchJson('Mailbox already exists', $_arr_checkResult['rcode']);
            }
        }

        if (!Func::isEmpty($_arr_inputEdit['user_pass'])) {
            $_str_rand                              = Func::rand();
            $this->mdl_user->inputEdit['user_pass'] = Crypt::crypt($_arr_inputEdit['user_pass'], $_str_rand, true);
            $this->mdl_user->inputEdit['user_rand'] = $_str_rand;
        }

        $this->mdl_user->inputEdit['user_id'] = $_arr_userRow['user_id'];

        $_arr_editResult = $this->mdl_user->edit();

        if ($_arr_editResult['rcode'] != 'y010103') {
            return $this->fetchJson($_arr_editResult['msg'], $_arr_editResult['rcode']);
        }

        $_arr_editResult['timestamp'] = GK_NOW;

        $_str_src   = Json::encode($_arr_editResult);

        $this->notify($_str_src, 'edit'); //通知

        $_arr_data = array(
            'rcode' => $_arr_editResult['rcode'],
            'msg'   => $this->obj_lang->get($_arr_editResult['msg']),
        );

        $_arr_tpl = array_replace_recursive($this->version, $_arr_data);

        return $this->json($_arr_tpl);
    }


    function read() {
        $_mix_init = $this->init();

        if ($_mix_init !== true) {
            return $this->fetchJson($_mix_init['msg'], $_mix_init['rcode']);
        }

        $_arr_inputRead = $this->mdl_user->inputRead($this->decryptRow);

        if ($_arr_inputRead['rcode'] != 'y010201') {
            return $this->fetchJson($_arr_inputRead['msg'], $_arr_inputRead['rcode']);
        }

        $_arr_userRow = $this->mdl_user->readBase($_arr_inputRead['user_str'], $_arr_inputRead['user_by']);

        if ($_arr_userRow['rcode'] != 'y010102') {
            return $this->fetchJson($_arr_userRow['msg'], $_arr_userRow['rcode']);
        }

        $_arr_userRow['timestamp'] = GK_NOW;

        $_str_src     = Json::encode($_arr_userRow);
        $_str_sign    = Sign::make($_str_src, $this->appRow['app_key'] . $this->appRow['app_secret']);
        $_str_encrypt = Crypt::encrypt($_str_src, $this->appRow['app_key'], $this->appRow['app_secret']);

        if ($_str_encrypt === false) {
            $_str_error = Crypt::getError();
            return $this->fetchJson($_str_error, 'x050405', 200, 'api.common');
        }

        $_arr_data = array(
            'rcode' => $_arr_userRow['rcode'],
            'code'  => $_str_encrypt,
            'sign'  => $_str_sign,
        );

        $_arr_tpl = array_replace_recursive($this->version, $_arr_data);

        return $this->json($_arr_tpl);
    }
}
