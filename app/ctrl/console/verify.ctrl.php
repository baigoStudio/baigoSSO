<?php
/*-----------------------------------------------------------------
！！！！警告！！！！
以下为系统文件，请勿修改
-----------------------------------------------------------------*/

namespace app\ctrl\console;

use app\classes\console\Ctrl;
use ginkgo\Loader;

//不能非法包含或直接执行
defined('IN_GINKGO') or exit('Access denied');

class Verify extends Ctrl {

    protected function c_init($param = array()) {
        parent::c_init();

        $this->mdl_user     = Loader::model('User');
        $this->mdl_verify   = Loader::model('Verify');

        $this->generalData['status']    = $this->mdl_verify->arr_status;
        $this->generalData['type']      = $this->mdl_verify->arr_type;
    }


    function index() {
        $_mix_init = $this->init();

        if ($_mix_init !== true) {
            return $this->error($_mix_init['msg'], $_mix_init['rcode']);
        }

        if (!isset($this->adminLogged['admin_allow']['verify']['verify']) && !$this->isSuper) { //判断权限
            return $this->error('You do not have permission', 'x120301');
        }

        //print_r($_arr_search);

        $_num_verifyCount   = $this->mdl_verify->count(); //统计记录数
        $_arr_pageRow       = $this->obj_request->pagination($_num_verifyCount); //取得分页数据
        $_arr_verifyRows    = $this->mdl_verify->lists($this->config['var_default']['perpage'], $_arr_pageRow['except']); //列出

        foreach ($_arr_verifyRows as $_key=>$_value) {
            //print_r($_value);
            $_arr_verifyRows[$_key]['userRow']      = $this->mdl_user->read($_value['verify_user_id']);
        }

        $_arr_tplData = array(
            'pageRow'       => $_arr_pageRow,
            'verifyRows'    => $_arr_verifyRows,
            'token'         => $this->obj_request->token(),
        );

        $_arr_tpl = array_replace_recursive($this->generalData, $_arr_tplData);

        //print_r($_arr_verifyRows);

        $this->assign($_arr_tpl);

        return $this->fetch();
    }


    function show() {
        $_mix_init = $this->init();

        if ($_mix_init !== true) {
            return $this->error($_mix_init['msg'], $_mix_init['rcode']);
        }

        if (!isset($this->adminLogged['admin_allow']['verify']['verify']) && !$this->isSuper) { //判断权限
            return $this->error('You do not have permission', 'x120301');
        }

        $_num_verifyId = 0;

        if (isset($this->param['id'])) {
            $_num_verifyId = $this->obj_request->input($this->param['id'], 'int', 0);
        }

        if ($_num_verifyId < 1) {
            return $this->error('Missing ID', 'x120201');
        }

        $_arr_verifyRow = $this->mdl_verify->read($_num_verifyId);

        if ($_arr_verifyRow['rcode'] != 'y120102') {
            return $this->error($_arr_verifyRow['msg'], $_arr_verifyRow['rcode']);
        }

        $_arr_verifyRow['userRow']      = $this->mdl_user->read($_arr_verifyRow['verify_user_id']);

        $_arr_tplData = array(
            'verifyRow'   => $_arr_verifyRow,
        );

        $_arr_tpl = array_replace_recursive($this->generalData, $_arr_tplData);

        //print_r($_arr_verifyRows);

        $this->assign($_arr_tpl);

        return $this->fetch();
    }


    function delete() {
        $_mix_init = $this->init();

        if ($_mix_init !== true) {
            return $this->fetchJson($_mix_init['msg'], $_mix_init['rcode']);
        }

        if (!$this->isAjaxPost) {
            return $this->fetchJson('Access denied', '', 405);
        }

        if (!isset($this->adminLogged['admin_allow']['verify']['verify']) && !$this->isSuper) { //判断权限
            return $this->fetchJson('You do not have permission', 'x120301');
        }

        $_arr_inputDelete = $this->mdl_verify->inputDelete();

        if ($_arr_inputDelete['rcode'] != 'y120201') {
            return $this->fetchJson($_arr_inputDelete['msg'], $_arr_inputDelete['rcode']);
        }

        $_arr_deleteResult = $this->mdl_verify->delete();

        $_arr_langReplace = array(
            'count' => $_arr_deleteResult['count'],
        );

        return $this->fetchJson($_arr_deleteResult['msg'], $$_arr_deleteResult['rcode'], 200, $_arr_langReplace);
    }


    function status() {
        $_mix_init = $this->init();

        if ($_mix_init !== true) {
            return $this->fetchJson($_mix_init['msg'], $_mix_init['rcode']);
        }

        if (!$this->isAjaxPost) {
            return $this->fetchJson('Access denied', '', 405);
        }

        if (!isset($this->adminLogged['admin_allow']['verify']['verify']) && !$this->isSuper) { //判断权限
            return $this->fetchJson('You do not have permission', 'x120301');
        }

        $_arr_inputStatus = $this->mdl_verify->inputStatus();

        if ($_arr_inputStatus['rcode'] != 'y120201') {
            return $this->fetchJson($_arr_inputStatus['msg'], $_arr_inputStatus['rcode']);
        }

        $_arr_statusResult = $this->mdl_verify->status();

        $_arr_langReplace = array(
            'count' => $_arr_statusResult['count'],
        );

        return $this->fetchJson($_arr_statusResult['msg'], $_arr_statusResult['rcode'], 200, $_arr_langReplace);
    }
}
