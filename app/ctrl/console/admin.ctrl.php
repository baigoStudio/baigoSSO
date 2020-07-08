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

// 不能非法包含或直接执行
defined('IN_GINKGO') or exit('Access denied');

class Admin extends Ctrl {

    protected function c_init($param = array()) {
        parent::c_init();

        $this->mdl_user     = Loader::model('User');
        $this->mdl_admin    = Loader::model('Admin');

        $this->generalData['status']    = $this->mdl_admin->arr_status;
        $this->generalData['type']      = $this->mdl_admin->arr_type;
    }

    function index() {
        $_mix_init = $this->init();

        if ($_mix_init !== true) {
            return $this->error($_mix_init['msg'], $_mix_init['rcode']);
        }

        if (!isset($this->adminAllow['admin']['browse']) && !$this->isSuper) { //判断权限
            return $this->error('You do not have permission', 'x020301');
        }

        $_arr_searchParam = array(
            'key'       => array('txt', ''),
            'status'    => array('txt', ''),
            'type'      => array('txt', ''),
        );

        $_arr_search = $this->obj_request->param($_arr_searchParam);

        $_num_adminCount  = $this->mdl_admin->count($_arr_search); //统计记录数
        $_arr_pageRow     = $this->obj_request->pagination($_num_adminCount); //取得分页数据
        $_arr_adminRows   = $this->mdl_admin->lists($this->config['var_default']['perpage'], $_arr_pageRow['except'], $_arr_search); //列出

        $_arr_tplData = array(
            'pageRow'    => $_arr_pageRow,
            'search'     => $_arr_search,
            'adminRows'  => $_arr_adminRows,
            'token'      => $this->obj_request->token(),
        );

        $_arr_tpl = array_replace_recursive($this->generalData, $_arr_tplData);

        //print_r($_arr_adminRows);

        $this->assign($_arr_tpl);

        return $this->fetch();
    }


    function show() {
        $_mix_init = $this->init();

        if ($_mix_init !== true) {
            return $this->error($_mix_init['msg'], $_mix_init['rcode']);
        }

        if (!isset($this->adminAllow['admin']['browse']) && !$this->isSuper) { //判断权限
            return $this->error('You do not have permission', 'x020301');
        }

        $_num_adminId = 0;

        if (isset($this->param['id'])) {
            $_num_adminId = $this->obj_request->input($this->param['id'], 'int', 0);
        }

        if ($_num_adminId < 1) {
            return $this->error('Missing ID', 'x020202');
        }

        $_arr_userRow = $this->mdl_user->read($_num_adminId);

        if ($_arr_userRow['rcode'] != 'y010102') {
            return $this->error($_arr_userRow['msg'], $_arr_userRow['rcode']);
        }

        $_arr_adminRow = $this->mdl_admin->read($_num_adminId);

        if ($_arr_adminRow['rcode'] != 'y020102') {
            return $this->error($_arr_adminRow['msg'], $_arr_adminRow['rcode']);
        }

        $_arr_tplData['adminRow'] = $_arr_adminRow;

        $_arr_tpl = array_replace_recursive($this->generalData, $_arr_tplData);

        //print_r($_arr_adminRows);

        $this->assign($_arr_tpl);

        return $this->fetch();
    }


    function form() {
        $_mix_init = $this->init();

        if ($_mix_init !== true) {
            return $this->error($_mix_init['msg'], $_mix_init['rcode']);
        }

        $_num_adminId = 0;

        if (isset($this->param['id'])) {
            $_num_adminId = $this->obj_request->input($this->param['id'], 'int', 0);
        }

        if ($_num_adminId > 0) {
            if (!isset($this->adminAllow['admin']['edit']) && !$this->isSuper) { //判断权限
                return $this->error('You do not have permission', 'x020303');
            }

            if ($_num_adminId == $this->adminLogged['admin_id'] && !$this->isSuper) {
                return $this->error('Prohibit editing yourself', 'x020306');
            }

            $_arr_userRow = $this->mdl_user->read($_num_adminId);

            if ($_arr_userRow['rcode'] != 'y010102') {
                return $this->error($_arr_userRow['msg'], $_arr_userRow['rcode']);
            }

            $_arr_adminRow = $this->mdl_admin->read($_num_adminId);

            if ($_arr_adminRow['rcode'] != 'y020102') {
                return $this->error($_arr_adminRow['msg'], $_arr_adminRow['rcode']);
            }
        } else {
            if (!isset($this->adminAllow['admin']['add']) && !$this->isSuper) { //判断权限
                return $this->error('You do not have permission', 'x020302');
            }

            $_arr_adminRow = array(
                'admin_id'      => 0,
                'admin_nick'    => '',
                'admin_note'    => '',
                'admin_status'  => $this->mdl_admin->arr_status[0],
                'admin_type'    => $this->mdl_admin->arr_type[0],
                'admin_allow'   => array(),
            );
        }

        $_arr_tplData = array(
            'adminRow'  => $_arr_adminRow,
            'token'     => $this->obj_request->token(),
        );

        $_arr_tpl = array_replace_recursive($this->generalData, $_arr_tplData);

        //print_r($_arr_adminRows);

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

        $_arr_inputSubmit = $this->mdl_admin->inputSubmit();

        if ($_arr_inputSubmit['rcode'] != 'y020201') {
            return $this->fetchJson($_arr_inputSubmit['msg'], $_arr_inputSubmit['rcode']);
        }

        if ($_arr_inputSubmit['admin_id'] > 0) {
            if (!isset($this->adminAllow['admin']['edit']) && !$this->isSuper) {
                return $this->fetchJson('You do not have permission', 'x020303');
            }

            if ($_arr_inputSubmit['admin_id'] == $this->adminLogged['admin_id'] && !$this->isSuper) {
                return $this->fetchJson('Prohibit editing yourself', 'x020306');
            }

            $_arr_userRow = $this->mdl_user->check($_arr_inputSubmit['admin_id']);

            //print_r($_arr_userRow);

            if ($_arr_userRow['rcode'] != 'y010102') {
                return $this->fetchJson($_arr_userRow['msg'], $_arr_userRow['rcode']);
            }

            if (!Func::isEmpty($_arr_inputSubmit['admin_pass'])) {
                $_str_rand          = Func::rand();
                $_str_passNew       = Crypt::crypt($_arr_inputSubmit['admin_pass'], $_str_rand);
                $_arr_passResult    = $this->mdl_user->pass($_arr_userRow['user_id'], $_str_passNew, $_str_rand);
            }
        } else {
            if (!isset($this->adminAllow['admin']['add']) && !$this->isSuper) {
                return $this->fetchJson('You do not have permission', 'x020302');
            }

            //检验用户名是否重复
            $_arr_userRow = $this->mdl_user->check($_arr_inputSubmit['admin_name'], 'user_name');

            if ($_arr_userRow['rcode'] == 'y010102') {
                $_arr_adminRow = $this->mdl_admin->check($_arr_userRow['user_id']);
                if ($_arr_adminRow['rcode'] == 'y020102') {
                    $_str_rcode = 'x020404';
                    $_str_msg   = 'Administrator already exists';
                } else {
                    $_str_rcode = 'x010404';
                    $_str_msg   = 'User already exists, please use authorization as administrator';
                }

                return $this->fetchJson($_str_msg, $_str_rcode);
            }

            $_str_rand = Func::rand();

            $this->mdl_user->inputSubmit['user_id']     = 0;
            $this->mdl_user->inputSubmit['user_name']   = $_arr_inputSubmit['admin_name'];
            $this->mdl_user->inputSubmit['user_pass']   = Crypt::crypt($_arr_inputSubmit['admin_pass'], $_str_rand);
            $this->mdl_user->inputSubmit['user_rand']   = $_str_rand;

            $_arr_submitResult       = $this->mdl_user->submit();

            $this->mdl_admin->inputSubmit['admin_id'] = $_arr_submitResult['user_id'];
        }

        $_arr_submitResult = $this->mdl_admin->submit();

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

        if (!isset($this->adminAllow['admin']['edit']) && !$this->isSuper) { //判断权限
            return $this->fetchJson('You do not have permission', 'x020303');
        }

        $_arr_inputStatus = $this->mdl_admin->inputStatus();

        if ($_arr_inputStatus['rcode'] != 'y020201') {
            return $this->fetchJson($_arr_inputStatus['msg'], $_arr_inputStatus['rcode']);
        }

        $_arr_statusResult = $this->mdl_admin->status();

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

        if (!isset($this->adminAllow['admin']['delete']) && !$this->isSuper) { //判断权限
            return $this->fetchJson('You do not have permission', 'x020304');
        }

        $_arr_inputDelete = $this->mdl_admin->inputDelete();

        if ($_arr_inputDelete['rcode'] != 'y020201') {
            return $this->fetchJson($_arr_inputDelete['msg'], $_arr_inputDelete['rcode']);
        }

        $_arr_deleteResult = $this->mdl_admin->delete();

        $_arr_langReplace = array(
            'count' => $_arr_deleteResult['count'],
        );

        return $this->fetchJson($_arr_deleteResult['msg'], $_arr_deleteResult['rcode'], 200, $_arr_langReplace);
    }


    function check() {
        $_arr_return = array(
            'msg' => '',
        );

        $_str_adminName = $this->obj_request->get('admin_name');

        if (!Func::isEmpty($_str_adminName)) {
            $_arr_userRow   = $this->mdl_user->check($_str_adminName, 'user_name');

            if ($_arr_userRow['rcode'] == 'y010102') {
                $_arr_adminRow = $this->mdl_admin->check($_arr_userRow['user_id']);
                if ($_arr_adminRow['rcode'] == 'y020102') {
                    $_arr_return = array(
                        'rcode' => $_arr_adminRow['rcode'],
                        'error' => $this->obj_lang->get('Administrator already exists'),
                    );
                } else {
                    $_arr_return = array(
                        'rcode' => $_arr_userRow['rcode'],
                        'error' => $this->obj_lang->get('User already exists, please use authorization as administrator'),
                    );
                }
            }
        }

        return $this->json($_arr_return);
    }
}
