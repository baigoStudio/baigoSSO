<?php
/*-----------------------------------------------------------------
！！！！警告！！！！
以下为系统文件，请勿修改
-----------------------------------------------------------------*/

namespace app\ctrl\console;

use app\classes\console\Ctrl;
use ginkgo\Loader;

// 不能非法包含或直接执行
defined('IN_GINKGO') or exit('Access denied');

class Index extends Ctrl {

    protected function c_init($param = array()) {
        parent::c_init();

        $this->mdl_profile  = Loader::model('Profile');

        $this->mdl_user     = Loader::model('User');
        $this->mdl_app      = Loader::model('App');
        $this->mdl_admin    = Loader::model('Admin');
        $this->mdl_pm       = Loader::model('Pm');

        $this->generalData['status_user']   = $this->mdl_user->arr_status;
        $this->generalData['status_app']    = $this->mdl_app->arr_status;
        $this->generalData['status_admin']  = $this->mdl_admin->arr_status;
        $this->generalData['type_admin']    = $this->mdl_admin->arr_type;
        $this->generalData['status_pm']     = $this->mdl_pm->arr_status;
        $this->generalData['type_pm']       = $this->mdl_pm->arr_type;
    }

    function index() {
        $_mix_init = $this->init();

        if ($_mix_init !== true) {
            return $this->error($_mix_init['msg'], $_mix_init['rcode']);
        }

        $_arr_userCount['total'] = $this->mdl_user->count();

        foreach ($this->mdl_user->arr_status as $_key=>$_value) {
            $_arr_search = array(
                'status' => $_value,
            );
            $_arr_userCount[$_value] = $this->mdl_user->count($_arr_search);
        }

        $_arr_appCount['total'] = $this->mdl_app->count();

        foreach ($this->mdl_app->arr_status as $_key=>$_value) {
            $_arr_search = array(
                'status' => $_value,
            );
            $_arr_appCount[$_value] = $this->mdl_app->count($_arr_search);
        }

        $_arr_adminCount['total'] = $this->mdl_admin->count();

        foreach ($this->mdl_admin->arr_status as $_key=>$_value) {
            $_arr_search = array(
                'status' => $_value,
            );
            $_arr_adminCount[$_value] = $this->mdl_admin->count($_arr_search);
        }

        foreach ($this->mdl_admin->arr_type as $_key=>$_value) {
            $_arr_search = array(
                'type' => $_value,
            );
            $_arr_adminCount[$_value] = $this->mdl_admin->count($_arr_search);
        }

        $_arr_pmCount['total'] = $this->mdl_pm->count();

        foreach ($this->mdl_pm->arr_status as $_key=>$_value) {
            $_arr_search = array(
                'status' => $_value,
            );
            $_arr_pmCount[$_value] = $this->mdl_pm->count($_arr_search);
        }

        foreach ($this->mdl_pm->arr_type as $_key=>$_value) {
            $_arr_search = array(
                'type' => $_value,
            );
            $_arr_pmCount[$_value] = $this->mdl_pm->count($_arr_search);
        }

        $_arr_tplData = array(
            'user_count'    => $_arr_userCount,
            'app_count'     => $_arr_appCount,
            'admin_count'   => $_arr_adminCount,
            'pm_count'      => $_arr_pmCount,
        );

        $_arr_tpl = array_replace_recursive($this->generalData, $_arr_tplData);

        $this->assign($_arr_tpl);

        return $this->fetch();
    }


    function setting() {
        $_mix_init = $this->init();

        if ($_mix_init !== true) {
            return $this->error($_mix_init['msg'], $_mix_init['rcode']);
        }

        $_arr_tplData = array(
            'token'     => $this->obj_request->token(),
        );

        $_arr_tpl = array_replace_recursive($this->generalData, $_arr_tplData);

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

        $_arr_inputShortcut = $this->mdl_profile->inputShortcut();

        if ($_arr_inputShortcut['rcode'] != 'y020201') {
            return $this->fetchJson($_arr_inputShortcut['msg'], $_arr_inputShortcut['rcode']);
        }

        $this->mdl_profile->inputShortcut['admin_id'] = $this->adminLogged['admin_id'];

        $_arr_shortcutResult = $this->mdl_profile->shortcut();

        return $this->fetchJson($_arr_shortcutResult['msg'], $_arr_shortcutResult['rcode']);
    }
}
