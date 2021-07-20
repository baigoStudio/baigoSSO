<?php
/*-----------------------------------------------------------------
！！！！警告！！！！
以下为系统文件，请勿修改
-----------------------------------------------------------------*/

namespace app\ctrl\console;

use app\classes\console\Ctrl;
use ginkgo\Loader;
use ginkgo\Func;
use ginkgo\Crypt;
use ginkgo\Plugin;
use ginkgo\Arrays;

// 不能非法包含或直接执行
if (!defined('IN_GINKGO')) {
    return 'Access denied';
}

class User extends Ctrl {

    protected function c_init($param = array()) {
        parent::c_init();

        $this->mdl_app          = Loader::model('App');
        $this->mdl_appBelong    = Loader::model('App_Belong');
        $this->mdl_user         = Loader::model('User');

        $this->generalData['status']    = $this->mdl_user->arr_status;
    }


    function index() {
        $_mix_init = $this->init();

        if ($_mix_init !== true) {
            return $this->error($_mix_init['msg'], $_mix_init['rcode']);
        }

        if (!isset($this->adminAllow['user']['browse']) && !$this->isSuper) { //判断权限
            return $this->error('You do not have permission', 'x010301');
        }

        $_arr_searchParam = array(
            'key'       => array('txt', ''),
            'status'    => array('txt', ''),
        );

        $_arr_search = $this->obj_request->param($_arr_searchParam);

        //print_r($_arr_search);

        $_num_userCount = $this->mdl_user->count($_arr_search); //统计记录数
        $_arr_pageRow   = $this->obj_request->pagination($_num_userCount); //取得分页数据
        $_arr_userRows  = $this->mdl_user->lists($this->config['var_default']['perpage'], $_arr_pageRow['offset'], $_arr_search); //列出

        $_arr_tplData = array(
            'pageRow'    => $_arr_pageRow,
            'search'     => $_arr_search,
            'userRows'   => $_arr_userRows,
            'token'      => $this->obj_request->token(),
        );

        $_arr_tpl = array_replace_recursive($this->generalData, $_arr_tplData);

        //print_r($_arr_userRows);

        $this->assign($_arr_tpl);

        return $this->fetch();
    }


    function show() {
        $_mix_init = $this->init();

        if ($_mix_init !== true) {
            return $this->error($_mix_init['msg'], $_mix_init['rcode']);
        }

        if (!isset($this->adminAllow['user']['browse']) && !$this->isSuper) { //判断权限
            return $this->error('You do not have permission', 'x010301');
        }

        $_num_userId = 0;

        if (isset($this->param['id'])) {
            $_num_userId = $this->obj_request->input($this->param['id'], 'int', 0);
        }

        if ($_num_userId < 1) {
            return $this->error('Missing ID', 'x010202');
        }

        $_arr_userRow = $this->mdl_user->read($_num_userId);

        if ($_arr_userRow['rcode'] != 'y010102') {
            return $this->error($_arr_userRow['msg'], $_arr_userRow['rcode']);
        }

        $_arr_appRow = $this->mdl_app->read($_arr_userRow['user_app_id']);

        //print_r($_arr_appRow);

        $_arr_searchBelong = array(
            'belong_user_id' => $_num_userId,
        );
        $_arr_appBelongRows   = $this->mdl_appBelong->lists(1000, 0, $_arr_searchBelong);

        $_arr_appRows       = array();
        $_arr_belongAppIds  = array();

        foreach ($_arr_appBelongRows as $_key=>$_value) {
            $_arr_belongAppIds[] = $_value['belong_app_id'];
        }

        if (!Func::isEmpty($_arr_belongAppIds)) {
            $_arr_belongAppIds = Arrays::filter($_arr_belongAppIds);

            foreach ($_arr_belongAppIds as $_key=>$_value) {
                $_arr_appRow = $this->mdl_app->read($_value);
                if ($_arr_appRow['rcode'] == 'y050102') {
                    $_arr_appRows[] = $this->mdl_app->read($_value);
                }
            }
        }

        $_arr_tplData = array(
            'appRow'    => $_arr_appRow,
            'appRows'   => $_arr_appRows,
            'userRow'   => $_arr_userRow,
        );

        $_arr_tpl = array_replace_recursive($this->generalData, $_arr_tplData);

        //print_r($_arr_userRows);

        $this->assign($_arr_tpl);

        return $this->fetch();
    }


    function form() {
        $_mix_init = $this->init();

        if ($_mix_init !== true) {
            return $this->error($_mix_init['msg'], $_mix_init['rcode']);
        }

        $_num_userId = 0;

        if (isset($this->param['id'])) {
            $_num_userId = $this->obj_request->input($this->param['id'], 'int', 0);
        }

        //print_r($_num_userId);

        if ($_num_userId > 0) {
            if (!isset($this->adminAllow['user']['edit']) && !$this->isSuper) { //判断权限
                return $this->error('You do not have permission', 'x010303');
            }

            $_arr_userRow = $this->mdl_user->read($_num_userId);

            if ($_arr_userRow['rcode'] != 'y010102') {
                return $this->error($_arr_userRow['msg'], $_arr_userRow['rcode']);
            }
        } else {
            if (!isset($this->adminAllow['user']['add']) && !$this->isSuper) { //判断权限
                return $this->error('You do not have permission', 'x010302');
            }

            $_arr_userRow = array(
                'user_id'       => 0,
                'user_mail'     => '',
                'user_nick'     => '',
                'user_note'     => '',
                'user_status'   => $this->mdl_user->arr_status[0],
                'user_allow'    => array(),
                'user_contact'  => array(),
                'user_extend'   => array(),
            );
        }

        $_arr_tplData = array(
            'userRow'   => $_arr_userRow,
            'token'     => $this->obj_request->token(),
        );

        $_arr_tpl = array_replace_recursive($this->generalData, $_arr_tplData);

        //print_r($_arr_userRows);

        $this->assign($_arr_tpl);

        return $this->fetch();
    }


    function submit() {
        $_mix_init = $this->init();

        if ($_mix_init !== true) {
            return $this->fetchJson($_mix_init['msg'], $_mix_init['rcode']);
        }

        if (!$this->isAjaxPost) {
            return $this->fetchJson('Access denied', '', 405);
        }

        $_arr_inputSubmit = $this->mdl_user->inputSubmit();

        if ($_arr_inputSubmit['rcode'] != 'y010201') {
            return $this->fetchJson($_arr_inputSubmit['msg'], $_arr_inputSubmit['rcode']);
        }

        if ($_arr_inputSubmit['user_id'] > 0) {
            if (!isset($this->adminAllow['user']['edit']) && !$this->isSuper) {
                return $this->fetchJson('You do not have permission', 'x010303');
            }

            if (!Func::isEmpty($_arr_inputSubmit['user_pass'])) {
                $_str_rand          = Func::rand();
                $this->mdl_user->inputSubmit['user_pass']   = Crypt::crypt($_arr_inputSubmit['user_pass'], $_str_rand);
                $this->mdl_user->inputSubmit['user_rand']   = $_str_rand;
            }
        } else {
            if (!isset($this->adminAllow['user']['add']) && !$this->isSuper) {
                return $this->fetchJson('You do not have permission', 'x010302');
            }

            $_str_rand = Func::rand();

            $this->mdl_user->inputSubmit['user_pass']   = Crypt::crypt($_arr_inputSubmit['user_pass'], $_str_rand);
            $this->mdl_user->inputSubmit['user_rand']   = $_str_rand;
        }

        $_arr_submitResult = $this->mdl_user->submit();

        return $this->fetchJson($_arr_submitResult['msg'], $_arr_submitResult['rcode']);
    }


    function status() {
        $_mix_init = $this->init();

        if ($_mix_init !== true) {
            return $this->fetchJson($_mix_init['msg'], $_mix_init['rcode']);
        }

        if (!$this->isAjaxPost) {
            return $this->fetchJson('Access denied', '', 405);
        }

        if (!isset($this->adminAllow['user']['edit']) && !$this->isSuper) { //判断权限
            return $this->fetchJson('You do not have permission', 'x010303');
        }

        $_arr_inputStatus = $this->mdl_user->inputStatus();

        if ($_arr_inputStatus['rcode'] != 'y010201') {
            return $this->fetchJson($_arr_inputStatus['msg'], $_arr_inputStatus['rcode']);
        }

        $_arr_return = array(
            'user_ids'      => $_arr_inputStatus['user_ids'],
            'user_status'   => $_arr_inputStatus['act'],
        );

        Plugin::listen('action_console_user_status', $_arr_return); //改变用户状态时触发

        $_arr_statusResult = $this->mdl_user->status();

        $_arr_langReplace = array(
            'count' => $_arr_statusResult['count'],
        );

        return $this->fetchJson($_arr_statusResult['msg'], $_arr_statusResult['rcode'], 200, $_arr_langReplace);
    }


    function delete() {
        $_mix_init = $this->init();

        if ($_mix_init !== true) {
            return $this->fetchJson($_mix_init['msg'], $_mix_init['rcode']);
        }

        if (!$this->isAjaxPost) {
            return $this->fetchJson('Access denied', '', 405);
        }

        if (!isset($this->adminAllow['user']['delete']) && !$this->isSuper) { //判断权限
            return $this->fetchJson('You do not have permission', 'x010304');
        }

        $_arr_inputDelete = $this->mdl_user->inputDelete();

        if ($_arr_inputDelete['rcode'] != 'y010201') {
            return $this->fetchJson($_arr_inputDelete['msg'], $_arr_inputDelete['rcode']);
        }

        $_arr_return = array(
            'user_ids'      => $_arr_inputDelete['user_ids'],
        );

        Plugin::listen('action_console_user_delete', $_arr_return); //删除链接时触发

        $_arr_deleteResult = $this->mdl_user->delete();

        $_arr_langReplace = array(
            'count' => $_arr_deleteResult['count'],
        );

        return $this->fetchJson($_arr_deleteResult['msg'], $_arr_deleteResult['rcode'], 200, $_arr_langReplace);
    }


    function check() {
        $_mix_init = $this->init();

        if ($_mix_init !== true) {
            return $this->fetchJson($_mix_init['msg'], $_mix_init['rcode']);
        }

        $_arr_return    = array(
            'msg' => '',
        );

        $_str_userName = $this->obj_request->get('user_name');

        if (!Func::isEmpty($_str_userName)) {
            $_arr_userRow   = $this->mdl_user->check($_str_userName, 'user_name');

            if ($_arr_userRow['rcode'] == 'y010102') {
                $_arr_return = array(
                    'rcode'     => $_arr_userRow['rcode'],
                    'error_msg' => $this->obj_lang->get('User already exists'),
                );
            }
        }

        return $this->json($_arr_return);
    }
}
