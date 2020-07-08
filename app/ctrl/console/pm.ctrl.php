<?php
/*-----------------------------------------------------------------
！！！！警告！！！！
以下为系统文件，请勿修改
-----------------------------------------------------------------*/

namespace app\ctrl\console;

use app\classes\console\Ctrl;
use ginkgo\Loader;
use ginkgo\Func;
use ginkgo\Html;
use ginkgo\Plugin;
use ginkgo\Ubbcode;

// 不能非法包含或直接执行
defined('IN_GINKGO') or exit('Access denied');

class Pm extends Ctrl {

    protected function c_init($param = array()) {
        parent::c_init();

        $this->mdl_user       = Loader::model('User');
        $this->mdl_pm         = Loader::model('Pm');

        $this->generalData['status']    = $this->mdl_pm->arr_status;
        $this->generalData['type']      = $this->mdl_pm->arr_type;
    }


    function index() {
        $_mix_init = $this->init();

        if ($_mix_init !== true) {
            return $this->error($_mix_init['msg'], $_mix_init['rcode']);
        }

        if (!isset($this->adminAllow['pm']['browse']) && !$this->isSuper) { //判断权限
            return $this->error('You do not have permission', 'x110301');
        }

        $_arr_searchParam = array(
            'key'       => array('txt', ''),
            'status'    => array('txt', ''),
            'type'      => array('txt', ''),
            'from'      => array('int', 0),
            'to'        => array('int', 0),
        );

        $_arr_search = $this->obj_request->param($_arr_searchParam);

        //print_r($_arr_search);

        $_num_pmCount   = $this->mdl_pm->count($_arr_search); //统计记录数
        $_arr_pageRow   = $this->obj_request->pagination($_num_pmCount); //取得分页数据
        $_arr_pmRows    = $this->mdl_pm->lists($this->config['var_default']['perpage'], $_arr_pageRow['except'], $_arr_search); //列出

        foreach ($_arr_pmRows as $_key=>$_value) {
            $_arr_pmRows[$_key]['toUser']   = $this->mdl_user->read($_value['pm_to']);
            $_arr_pmRows[$_key]['fromUser'] = $this->mdl_user->read($_value['pm_from']);
            $_arr_pmRows[$_key]['pm_title'] = Html::decode($_value['pm_title'], 'json');
        }

        $_arr_tplData = array(
            'pageRow'       => $_arr_pageRow,
            'search'        => $_arr_search,
            'pmRows'        => $_arr_pmRows,
            'token'         => $this->obj_request->token(),
        );

        $_arr_tpl = array_replace_recursive($this->generalData, $_arr_tplData);

        //print_r($_arr_pmRows);

        $this->assign($_arr_tpl);

        return $this->fetch();
    }


    function show() {
        $_mix_init = $this->init();

        if ($_mix_init !== true) {
            return $this->error($_mix_init['msg'], $_mix_init['rcode']);
        }

        if (!isset($this->adminAllow['pm']['browse']) && !$this->isSuper) { //判断权限
            return $this->error('You do not have permission', 'x110301');
        }

        $_num_pmId = 0;

        if (isset($this->param['id'])) {
            $_num_pmId = $this->obj_request->input($this->param['id'], 'int', 0);
        }

        if ($_num_pmId < 1) {
            return $this->error('Missing ID', 'x110202');
        }

        $_arr_pmRow = $this->mdl_pm->read($_num_pmId);

        if ($_arr_pmRow['rcode'] != 'y110102') {
            return $this->error($_arr_pmRow['msg'], $_arr_pmRow['rcode']);
        }

        $_arr_pmRow['pm_title']     = Ubbcode::convert($_arr_pmRow['pm_title']);
        $_arr_pmRow['pm_content']   = Ubbcode::convert($_arr_pmRow['pm_content']);
        $_arr_pmRow['toUser']       = $this->mdl_user->read($_arr_pmRow['pm_to']);
        $_arr_pmRow['fromUser']     = $this->mdl_user->read($_arr_pmRow['pm_from']);

        $_arr_tplData = array(
            'pmRow'   => $_arr_pmRow,
        );

        $_arr_tpl = array_replace_recursive($this->generalData, $_arr_tplData);

        //print_r($_arr_pmRows);

        $this->assign($_arr_tpl);

        return $this->fetch();
    }


    function bulk() {
        $_mix_init = $this->init();

        if ($_mix_init !== true) {
            return $this->error($_mix_init['msg'], $_mix_init['rcode']);
        }

        if (!isset($this->adminAllow['pm']['bulk']) && !$this->isSuper) { //判断权限
            return $this->error('You do not have permission', 'x110302');
        }

        $_arr_tplData = array(
            'begin_datetime'    => date($this->config['var_extra']['base']['site_date'] . ' ' . $this->config['var_extra']['base']['site_time_short'], GK_NOW - GK_DAY),
            'end_datetime'      => date($this->config['var_extra']['base']['site_date'] . ' ' . $this->config['var_extra']['base']['site_time_short'], GK_NOW),
            'token'         => $this->obj_request->token(),
        );

        $_arr_tpl = array_replace_recursive($this->generalData, $_arr_tplData);

        //print_r($_arr_pmRows);

        $this->assign($_arr_tpl);

        return $this->fetch();
    }


    function bulkSubmit() {
        $_mix_init = $this->init();

        if ($_mix_init !== true) {
            return $this->fetchJson($_mix_init['msg'], $_mix_init['rcode']);
        }

        if (!$this->isAjaxPost) {
            return $this->fetchJson('Access denied', '', 405);
        }

        if (!isset($this->adminAllow['pm']['bulk']) && !$this->isSuper) {
            return $this->fetchJson('You do not have permission', 'x110302');
        }

        $_arr_inputBulk = $this->mdl_pm->inputBulk();

        if ($_arr_inputBulk['rcode'] != 'y110201') {
            return $this->fetchJson($_arr_inputBulk['msg'], $_arr_inputBulk['rcode']);
        }

        $_arr_search = array();

        switch ($_arr_inputBulk['pm_bulk_type']) {
            case 'bulk_users':
                $_arr_toUsers = explode(',', $_arr_inputBulk['pm_to_users']);
                $_arr_search = array(
                    'user_names' => $_arr_toUsers,
                );
            break;

            case 'bulk_key_name':
                $_arr_search = array(
                    'key_name' => $_arr_inputBulk['pm_to_key_name'],
                );
            break;

            case 'bulk_key_mail':
                $_arr_search = array(
                    'key_mail' => $_arr_inputBulk['pm_to_key_mail'],
                );
            break;

            case 'bulk_range_id':
                $_arr_search = array(
                    'min_id'    => $_arr_inputBulk['pm_to_min_id'],
                    'max_id'    => $_arr_inputBulk['pm_to_max_id'],
                );
            break;

            case 'bulk_range_reg':
                $_arr_search = array(
                    'begin_time'  => Func::strtotime($_arr_inputBulk['pm_to_begin_reg']),
                    'end_time'    => Func::strtotime($_arr_inputBulk['pm_to_end_reg']),
                );
            break;

            case 'bulk_range_login':
                $_arr_search = array(
                    'begin_login'  => Func::strtotime($_arr_inputBulk['pm_to_begin_login']),
                    'end_login'    => Func::strtotime($_arr_inputBulk['pm_to_end_login']),
                );
            break;
        }

        $_arr_userRows = $this->mdl_user->lists(1000, 0, $_arr_search);

        if (Func::isEmpty($_arr_userRows)) {
            return $this->fetchJson('No eligible recipients', 'x110201');
        }

        $_num_count = 0;

        foreach ($_arr_userRows as $_key=>$_value) {
            $this->mdl_pm->inputBulk['pm_to'] = $_value['user_id'];
            $_arr_bulkResult   = $this->mdl_pm->bulk();
            if ($_arr_bulkResult['rcode'] == 'y110101') {
                ++$_num_count;
            }
        }

        $_arr_langReplace = array(
            'count' => $_num_count,
        );

        if ($_num_count > 0) {
            $_str_rcode = 'y110101';
            $_str_msg   = 'Successfully sent {:count} messages';
        } else {
            $_str_rcode = 'x110101';
            $_str_msg   = 'Send message failed';
        }

        return $this->fetchJson($_str_msg, $_str_rcode, 200, $_arr_langReplace);
    }


    function delete() {
        $_mix_init = $this->init();

        if ($_mix_init !== true) {
            return $this->fetchJson($_mix_init['msg'], $_mix_init['rcode']);
        }

        if (!$this->isAjaxPost) {
            return $this->fetchJson('Access denied', '', 405);
        }

        if (!isset($this->adminAllow['pm']['delete']) && !$this->isSuper) { //判断权限
            return $this->fetchJson('You do not have permission', 'x110304');
        }

        $_arr_inputDelete = $this->mdl_pm->inputDelete();

        if ($_arr_inputDelete['rcode'] != 'y110201') {
            return $this->fetchJson($_arr_inputDelete['msg'], $_arr_inputDelete['rcode']);
        }

        $_arr_return = array(
            'pm_ids'      => $_arr_inputDelete['pm_ids'],
        );

        Plugin::listen('action_console_pm_delete', $_arr_return); //删除链接时触发

        $_arr_deleteResult = $this->mdl_pm->delete();

        $_arr_langReplace = array(
            'count' => $_arr_deleteResult['count'],
        );

        return $this->fetchJson($_arr_deleteResult['msg'], $_arr_deleteResult['rcode'], 200, $_arr_langReplace);
    }


    function status() {
        $_mix_init = $this->init();

        if ($_mix_init !== true) {
            return $this->fetchJson($_mix_init['msg'], $_mix_init['rcode']);
        }

        if (!$this->isAjaxPost) {
            return $this->fetchJson('Access denied', '', 405);
        }

        if (!isset($this->adminAllow['pm']['edit']) && !$this->isSuper) { //判断权限
            return $this->fetchJson('You do not have permission', 'x110305');
        }

        $_arr_inputStatus = $this->mdl_pm->inputStatus();

        if ($_arr_inputStatus['rcode'] != 'y110201') {
            return $this->fetchJson($_arr_inputStatus['msg'], $_arr_inputStatus['rcode']);
        }

        $_arr_return = array(
            'pm_ids'      => $_arr_inputStatus['pm_ids'],
            'pm_status'   => $_arr_inputStatus['act'],
        );

        Plugin::listen('action_console_pm_status', $_arr_return); //删除链接时触发

        $_arr_statusResult = $this->mdl_pm->status();

        $_arr_langReplace = array(
            'count' => $_arr_statusResult['count'],
        );

        return $this->fetchJson($_arr_statusResult['msg'], $_arr_statusResult['rcode'], 200, $_arr_langReplace);
    }
}
