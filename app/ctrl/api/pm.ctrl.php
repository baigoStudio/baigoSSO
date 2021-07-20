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
use ginkgo\Html;
use ginkgo\Plugin;

// 不能非法包含或直接执行
if (!defined('IN_GINKGO')) {
    return 'Access denied';
}

class Pm extends Ctrl {

    protected function c_init($param = array()) {
        parent::c_init();

        $this->mdl_user     = Loader::model('User');
        $this->mdl_pm       = Loader::model('Pm');
    }


    function lists() {
        $_mix_init = $this->init();

        if ($_mix_init !== true) {
            return $this->fetchJson($_mix_init['msg'], $_mix_init['rcode']);
        }

        $_arr_inputLists = $this->mdl_pm->inputLists($this->decryptRow);

        $_arr_userRow = $this->userCheck($_arr_inputLists);

        if ($_arr_userRow['rcode'] != 'y010102') {
            return $this->fetchJson($_arr_userRow['msg'], $_arr_userRow['rcode']);
        }

        $_arr_pmIds = array();

        if (!Func::isEmpty($_arr_inputLists['pm_ids'])) {
            $_arr_pmIds = $_arr_inputLists['pm_ids'];
        }

        $_arr_search = array(
            'type'      => $_arr_inputLists['pm_type'],
            'status'    => $_arr_inputLists['pm_status'],
            'key'       => $_arr_inputLists['key'],
            'page'      => $_arr_inputLists['page'],
            'ids'       => $_arr_pmIds,
        );

        switch ($_arr_inputLists['pm_type']) {
            case 'in':
                $_arr_search['to']   = $_arr_userRow['user_id'];
            break;

            case 'out':
                $_arr_search['from'] = $_arr_userRow['user_id'];
            break;
        }

        $_num_pmCount   = $this->mdl_pm->count($_arr_search);
        $_arr_pageRow   = $this->obj_request->pagination($_num_pmCount, $_arr_inputLists['perpage'], $_arr_inputLists['page']);
        $_arr_pmRows    = $this->mdl_pm->lists($_arr_inputLists['perpage'], $_arr_pageRow['offset'], $_arr_search);

        foreach ($_arr_pmRows as $_key=>$_value) {
            $_arr_pmRows[$_key]['fromUser'] = $this->mdl_user->readBase($_value['pm_from']);
            $_arr_pmRows[$_key]['toUser']   = $this->mdl_user->readBase($_value['pm_to']);

            if ($_arr_inputLists['pm_type'] == 'out') {
                $_arr_sendRow = $this->mdl_pm->read($_value['pm_send_id']);
                if ($_arr_sendRow['rcode'] != 'y110102') {
                    $_arr_pmRows[$_key]['pm_send_status'] = 'revoke';
                } else {
                    $_arr_pmRows[$_key]['pm_send_status'] = $_arr_sendRow['pm_status'];
                }
            }
        }

        $_arr_return = array(
            'pmRows'    => $_arr_pmRows,
            'pageRow'   => $_arr_pageRow,
        );

        $_arr_return    = Plugin::listen('filter_api_pm_lists', $_arr_return); //编辑文章时触发

        $_arr_return['timestamp'] = GK_NOW;

        $_str_src     = Arrays::toJson($_arr_return);
        $_str_sign    = Sign::make($_str_src, $this->appRow['app_key'] . $this->appRow['app_secret']);
        $_str_encrypt = Crypt::encrypt($_str_src, $this->appRow['app_key'], $this->appRow['app_secret']);

        if ($_str_encrypt === false) {
            $_str_error = Crypt::getError();
            return $this->fetchJson($_str_error, 'x050405', 200, 'api.common');
        }

        $_arr_data = array(
            'rcode' => 'y110102',
            'code'  => $_str_encrypt,
            'sign'  => $_str_sign,
        );

        $_arr_tpl = array_replace_recursive($this->version, $_arr_data);

        return $this->json($_arr_tpl);
    }


    function read() {
        $_mix_init = $this->init();

        if ($_mix_init !== true) {
            return $this->fetchJson($_mix_init['msg'], $_mix_init['rcode']);
        }

        $_arr_inputRead = $this->mdl_pm->inputRead($this->decryptRow);

        $_arr_userRow = $this->userCheck($_arr_inputRead);

        if ($_arr_userRow['rcode'] != 'y010102') {
            return $this->fetchJson($_arr_userRow['msg'], $_arr_userRow['rcode']);
        }

        $_arr_pmRow = $this->mdl_pm->read($_arr_inputRead['pm_id']);

        if ($_arr_pmRow['rcode'] != 'y110102') {
            return $this->fetchJson($_arr_pmRow['msg'], $_arr_pmRow['rcode']);
        }

        if ($_arr_pmRow['pm_from'] != $_arr_userRow['user_id'] && $_arr_pmRow['pm_to'] != $_arr_userRow['user_id']) {
            return $this->fetchJson('Message does not belong to you', 'x110403');
        }

        $_arr_pmRow['pm_title']     = Html::decode($_arr_pmRow['pm_title'], 'json');
        $_arr_pmRow['pm_content']   = Html::decode($_arr_pmRow['pm_content'], 'json');
        $_arr_pmRow['fromUser']     = $this->mdl_user->readBase($_arr_pmRow['pm_from']);
        $_arr_pmRow['toUser']       = $this->mdl_user->readBase($_arr_pmRow['pm_to']);

        $_arr_pmRow['timestamp'] = GK_NOW;

        $_arr_pmRow   = Plugin::listen('filter_api_pm_read', $_arr_pmRow); //编辑文章时触发

        $_str_src     = Arrays::toJson($_arr_pmRow);
        $_str_sign    = Sign::make($_str_src, $this->appRow['app_key'] . $this->appRow['app_secret']);
        $_str_encrypt = Crypt::encrypt($_str_src, $this->appRow['app_key'], $this->appRow['app_secret']);

        if ($_str_encrypt === false) {
            $_str_error = Crypt::getError();
            return $this->fetchJson($_str_error, 'x050405', 200, 'api.common');
        }

        $_arr_data = array(
            'rcode' => $_arr_pmRow['rcode'],
            'code'  => $_str_encrypt,
            'sign'  => $_str_sign,
        );

        $_arr_tpl = array_replace_recursive($this->version, $_arr_data);

        return $this->json($_arr_tpl);
    }


    function send() {
        $_mix_init = $this->init();

        if ($_mix_init !== true) {
            return $this->fetchJson($_mix_init['msg'], $_mix_init['rcode']);
        }

        if (!$this->isPost) {
            return $this->fetchJson('Access denied', '', 405);
        }

        $_arr_inputSend = $this->mdl_pm->inputSend($this->decryptRow);

        $_arr_userRow = $this->userCheck($_arr_inputSend);

        if ($_arr_userRow['rcode'] != 'y010102') {
            return $this->fetchJson($_arr_userRow['msg'], $_arr_userRow['rcode']);
        }

        $_arr_inputSend['pm_to_name'] = explode(',', $_arr_inputSend['pm_to_name']);

        $_arr_search = array(
            'user_names'  => $_arr_inputSend['pm_to_name'],
        );
        $_arr_userRows = $this->mdl_user->lists(1000, 0, $_arr_search);

        $_num_count = 0;

        foreach ($_arr_userRows as $_key=>$_value) {
            $this->mdl_pm->inputSend['pm_to']   = $_value['user_id'];
            $this->mdl_pm->inputSend['pm_from'] = $_arr_userRow['user_id'];
            $_arr_pmRow   = $this->mdl_pm->send();
            if ($_arr_pmRow['rcode'] == 'y110101') {
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

        $_arr_sendResult = array(
            'rcode' => $_str_rcode,
            'msg'   => $_str_msg,
        );

        $_arr_tpl = array_replace_recursive($this->version, $_arr_sendResult);

        $_arr_tpl['msg'] = $this->obj_lang->get($_arr_tpl['msg'], '', $_arr_langReplace);

        return $this->json($_arr_tpl);
    }


    function status() {
        $_mix_init = $this->init();

        if ($_mix_init !== true) {
            return $this->fetchJson($_mix_init['msg'], $_mix_init['rcode']);
        }

        if (!$this->isPost) {
            return $this->fetchJson('Access denied', '', 405);
        }

        $_arr_inputStatus = $this->mdl_pm->inputStatus($this->decryptRow);

        $_arr_userRow = $this->userCheck($_arr_inputStatus);

        if ($_arr_userRow['rcode'] != 'y010102') {
            return $this->fetchJson($_arr_userRow['msg'], $_arr_userRow['rcode']);
        }

        $_arr_return = array(
            'pm_ids'      => $_arr_inputStatus['pm_ids'],
            'pm_status'   => $_arr_inputStatus['act'],
        );

        Plugin::listen('action_api_pm_status', $_arr_return); //删除链接时触发

        $this->mdl_pm->inputStatus['pm_ids']  = $_arr_inputStatus['pm_ids'];
        $this->mdl_pm->inputStatus['act']     = $_arr_inputStatus['pm_status'];
        $this->mdl_pm->inputStatus['pm_to']   = $_arr_userRow['user_id'];
        $this->mdl_pm->inputStatus['pm_type'] = 'in';

        $_arr_statusResult = $this->mdl_pm->status();

        $_arr_langReplace = array(
            'count' => $_arr_statusResult['count'],
        );

        $_arr_tpl = array_replace_recursive($this->version, $_arr_statusResult);

        $_arr_tpl['msg'] = $this->obj_lang->get($_arr_tpl['msg'], '', $_arr_langReplace);

        return $this->json($_arr_tpl);
    }


    function revoke() {
        $_mix_init = $this->init();

        if ($_mix_init !== true) {
            return $this->fetchJson($_mix_init['msg'], $_mix_init['rcode']);
        }

        if (!$this->isPost) {
            return $this->fetchJson('Access denied', '', 405);
        }

        $_arr_inputDelete = $this->mdl_pm->inputDelete($this->decryptRow);

        $_arr_userRow = $this->userCheck($_arr_inputDelete);

        if ($_arr_userRow['rcode'] != 'y010102') {
            return $this->fetchJson($_arr_userRow['msg'], $_arr_userRow['rcode']);
        }

        $_arr_return = array(
            'pm_ids' => $_arr_inputDelete['pm_ids'],
        );

        Plugin::listen('action_api_pm_revoke', $_arr_return); //删除链接时触发

        //$this->mdl_pm->inputDelete['pm_ids']      = $_arr_inputDelete['pm_ids'];
        $this->mdl_pm->inputDelete['pm_from']     = $_arr_userRow['user_id'];
        $this->mdl_pm->inputDelete['pm_type']     = 'in';
        $this->mdl_pm->inputDelete['pm_status']   = 'wait';

        $_arr_revokeResult = $this->mdl_pm->delete();

        $_arr_langReplace = array(
            'count' => $_arr_revokeResult['count'],
        );

        $_arr_tpl = array_replace_recursive($this->version, $_arr_revokeResult);

        $_arr_tpl['msg'] = $this->obj_lang->get($_arr_tpl['msg'], '', $_arr_langReplace);

        return $this->json($_arr_tpl);
    }


    function delete() {
        $_mix_init = $this->init();

        if ($_mix_init !== true) {
            return $this->fetchJson($_mix_init['msg'], $_mix_init['rcode']);
        }

        if (!$this->isPost) {
            return $this->fetchJson('Access denied', '', 405);
        }

        $_arr_inputDelete = $this->mdl_pm->inputDelete($this->decryptRow);

        $_arr_userRow = $this->userCheck($_arr_inputDelete);

        if ($_arr_userRow['rcode'] != 'y010102') {
            return $this->fetchJson($_arr_userRow['msg'], $_arr_userRow['rcode']);
        }

        $_arr_return = array(
            'pm_ids'      => $_arr_inputDelete['pm_ids'],
        );

        Plugin::listen('action_api_pm_delete', $_arr_return); //删除链接时触发

        $this->mdl_pm->inputDelete['user_id'] = $_arr_userRow['user_id'];

        $_arr_deleteResult = $this->mdl_pm->delete();

        $_arr_langReplace = array(
            'count' => $_arr_deleteResult['count'],
        );

        $_arr_tpl = array_replace_recursive($this->version, $_arr_deleteResult);

        $_arr_tpl['msg'] = $this->obj_lang->get($_arr_tpl['msg'], '', $_arr_langReplace);

        return $this->json($_arr_tpl);
    }


    function check() {
        $_mix_init = $this->init();

        if ($_mix_init !== true) {
            return $this->fetchJson($_mix_init['msg'], $_mix_init['rcode']);
        }

        $_arr_inputCheck = $this->mdl_pm->inputCheck($this->decryptRow);

        $_arr_userRow = $this->userCheck($_arr_inputCheck);

        if ($_arr_userRow['rcode'] != 'y010102') {
            return $this->fetchJson($_arr_userRow['msg'], $_arr_userRow['rcode']);
        }

        $_arr_search = array(
            'type'      => 'in',
            'to'        => $_arr_userRow['user_id'],
            'status'    => 'wait',
        );

        $_num_pmCount   = $this->mdl_pm->count($_arr_search);

        $_arr_return = array(
            'rcode'     => 'y110102',
            'pm_count'  => $_num_pmCount,
        );

        $_arr_tpl = array_replace_recursive($this->version, $_arr_return);

        return $this->json($_arr_tpl);
    }


    protected function userCheck($arr_inputCheck) {
        if ($arr_inputCheck['rcode'] != 'y110201') {
            return $arr_inputCheck;
        }

        $_arr_userRow = $this->mdl_user->read($arr_inputCheck['user_id']);

        return $this->userCheckProcess($_arr_userRow, $arr_inputCheck);
    }
}
