<?php
/*-----------------------------------------------------------------
！！！！警告！！！！
以下为系统文件，请勿修改
-----------------------------------------------------------------*/

namespace app\ctrl\install;

use app\classes\install\Ctrl;
use ginkgo\Loader;
use ginkgo\File;
use ginkgo\Crypt;
use ginkgo\Func;

// 不能非法包含或直接执行
if (!defined('IN_GINKGO')) {
    return 'Access denied';
}

class Index extends Ctrl {

    function index() {
        $_mix_init = $this->init(true, false);

        if ($_mix_init !== true) {
            return $this->error($_mix_init['msg'], $_mix_init['rcode']);
        }

        $_arr_tplData = array(
            //'token' => $this->obj_request->token(),
        );

        $_arr_tpl = array_replace_recursive($this->generalData, $_arr_tplData);

        $this->assign($_arr_tpl);

        return $this->fetch();
    }

    function dbconfig() {
        $_mix_init = $this->init();

        if ($_mix_init !== true) {
            return $this->error($_mix_init['msg'], $_mix_init['rcode']);
        }

        $_arr_tplData = array(
            'token' => $this->obj_request->token(),
        );

        $_arr_tpl = array_replace_recursive($this->generalData, $_arr_tplData);

        $this->assign($_arr_tpl);

        return $this->fetch();
    }

    function dbconfigSubmit() {
        $_mix_init = $this->init();

        if ($_mix_init !== true) {
            return $this->fetchJson($_mix_init['msg'], $_mix_init['rcode']);
        }

        if (!$this->isAjaxPost) {
            return $this->fetchJson('Access denied', '', 405);
        }

        $_arr_inputDbconfig = $this->mdl_opt->inputDbconfig();

        if ($_arr_inputDbconfig['rcode'] != 'y030201') {
            return $this->fetchJson($_arr_inputDbconfig['msg'], $_arr_inputDbconfig['rcode']);
        }

        $_arr_dbconfigResult = $this->mdl_opt->dbconfig();

        return $this->fetchJson($_arr_dbconfigResult['msg'], $_arr_dbconfigResult['rcode']);
    }


    function admin() {
        $_mix_init = $this->init();

        if ($_mix_init !== true) {
            return $this->error($_mix_init['msg'], $_mix_init['rcode']);
        }

        $_arr_tplData = array(
            'token'         => $this->obj_request->token(),
        );

        $_arr_tpl = array_replace_recursive($this->generalData, $_arr_tplData);

        $this->assign($_arr_tpl);

        return $this->fetch();
    }

    function adminCheck() {
        $_mix_init = $this->init();

        if ($_mix_init !== true) {
            return $this->fetchJson($_mix_init['msg'], $_mix_init['rcode']);
        }

        $_arr_return    = array(
            'msg' => '',
        );

        $_str_adminName = $this->obj_request->get('admin_name');

        if (!Func::isEmpty($_str_adminName)) {
            $_mdl_user      = Loader::model('User', '', false);

            $_arr_userRow   = $_mdl_user->check($_str_adminName, 'user_name');

            if ($_arr_userRow['rcode'] == 'y010102') {
                $_arr_return = array(
                    'rcode'     => 'x010404',
                    'error_msg' => $this->obj_lang->get('User already exists'),
                );
            }
        }

        return $this->json($_arr_return);
    }

    function adminSubmit() {
        $_mix_init = $this->init();

        if ($_mix_init !== true) {
            return $this->fetchJson($_mix_init['msg'], $_mix_init['rcode']);
        }

        if (!$this->isAjaxPost) {
            return $this->fetchJson('Access denied', '', 405);
        }

        $_mdl_user  = Loader::model('User', '', false);
        $_mdl_admin = Loader::model('Admin');

        $_arr_inputSubmit = $_mdl_admin->inputSubmit();

        if ($_arr_inputSubmit['rcode'] != 'y020201') {
            return $this->fetchJson($_arr_inputSubmit['msg'], $_arr_inputSubmit['rcode']);
        }

        //检验用户名是否重复
        $_arr_userRow = $_mdl_user->check($_arr_inputSubmit['admin_name'], 'user_name');

        if ($_arr_userRow['rcode'] == 'y010102') {
            return $this->fetchJson('User already exists', 'x010404');
        }

        $_str_rand = Func::rand();

        $_mdl_user->inputSubmit['user_id']     = 0;
        $_mdl_user->inputSubmit['user_name']   = $_arr_inputSubmit['admin_name'];
        $_mdl_user->inputSubmit['user_pass']   = Crypt::crypt($_arr_inputSubmit['admin_pass'], $_str_rand);
        $_mdl_user->inputSubmit['user_rand']   = $_str_rand;
        $_mdl_user->inputSubmit['user_nick']   = $_arr_inputSubmit['admin_nick'];
        $_mdl_user->inputSubmit['user_note']   = $_arr_inputSubmit['admin_name'];
        $_mdl_user->inputSubmit['user_mail']   = $_arr_inputSubmit['admin_mail'];

        $_arr_submitResult   = $_mdl_user->submit();

        if ($_arr_submitResult['rcode'] != 'y010101') {
            return $this->fetchJson($_arr_submitResult, $_arr_submitResult['rcode']);
        }

        $_mdl_admin->inputSubmit['admin_id'] = $_arr_submitResult['user_id'];

        $_arr_submitResult = $_mdl_admin->submit();

        return $this->fetchJson($_arr_submitResult['msg'], $_arr_submitResult['rcode']);
    }


    function over() {
        $_mix_init = $this->init();

        if ($_mix_init !== true) {
            return $this->error($_mix_init['msg'], $_mix_init['rcode']);
        }

        $_arr_tplData = array(
            'token'         => $this->obj_request->token(),
        );

        $_arr_tpl = array_replace_recursive($this->generalData, $_arr_tplData);

        $this->assign($_arr_tpl);

        return $this->fetch();
    }

    function overSubmit() {
        $_mix_init = $this->init();

        if ($_mix_init !== true) {
            return $this->fetchJson($_mix_init['msg'], $_mix_init['rcode']);
        }

        $_arr_return = array();

        if (!$this->isAjaxPost) {
            return $this->fetchJson('Access denied', '', 405);
        }

        $_arr_inputOver = $this->mdl_opt->inputCommon();

        if ($_arr_inputOver['rcode'] != 'y030201') {
            return $this->fetchJson($_arr_inputOver['msg'], $_arr_inputOver['rcode']);
        }

        $_arr_overResult = $this->mdl_opt->over();

        return $this->fetchJson($_arr_overResult['msg'], $_arr_overResult['rcode']);
    }


    function data() {
        $_mix_init = $this->init();

        if ($_mix_init !== true) {
            return $this->error($_mix_init['msg'], $_mix_init['rcode']);
        }

        $_arr_tplData = array(
            'token' => $this->obj_request->token(),
        );

        $_arr_tpl = array_replace_recursive($this->generalData, $_arr_tplData);

        $this->assign($_arr_tpl);

        return $this->fetch();
    }


    function dataSubmit() {
        $_mix_init = $this->init();

        if ($_mix_init !== true) {
            return $this->fetchJson($_mix_init['msg'], $_mix_init['rcode']);
        }

        $_arr_return = array();

        if (!$this->isAjaxPost) {
            return $this->fetchJson('Access denied', '', 405);
        }

        $_arr_inputData = $this->mdl_opt->inputData();

        if ($_arr_inputData['rcode'] != 'y030201') {
            return $this->fetchJson($_arr_inputData['msg'], $_arr_inputData['rcode']);
        }

        switch ($_arr_inputData['type']) {
            case 'index':
                $_arr_return = $this->createIndex($_arr_inputData['model']);
            break;

            case 'view':
                $_arr_return = $this->createView($_arr_inputData['model']);
            break;

            default:
                $_arr_return = $this->createTable($_arr_inputData['model']);
            break;
        }

        return $this->fetchJson($_arr_return['msg'], $_arr_return['rcode']);
    }
}
