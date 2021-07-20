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
use ginkgo\Sign;
use ginkgo\Json;
use ginkgo\Http;
use ginkgo\Html;
use ginkgo\Log;

// 不能非法包含或直接执行
if (!defined('IN_GINKGO')) {
    return 'Access denied';
}

class Combine extends Ctrl {

    protected function c_init($param = array()) {
        parent::c_init();

        $this->mdl_combine  = Loader::model('Combine');
    }


    function index() {
        $_mix_init = $this->init();

        if ($_mix_init !== true) {
            return $this->error($_mix_init['msg'], $_mix_init['rcode']);
        }

        if (!isset($this->adminAllow['app']['combine']) && !$this->isSuper) { //判断权限
            return $this->error('You do not have permission', 'x040301');
        }

        $_arr_searchParam = array(
            'key'       => array('txt', ''),
        );

        $_arr_search = $this->obj_request->param($_arr_searchParam);

        //print_r($_arr_search);

        $_num_combineCount  = $this->mdl_combine->count($_arr_search); //统计记录数
        $_arr_pageRow   = $this->obj_request->pagination($_num_combineCount); //取得分页数据
        $_arr_combineRows   = $this->mdl_combine->lists($this->config['var_default']['perpage'], $_arr_pageRow['offset'], $_arr_search); //列出

        $_arr_tplData = array(
            'pageRow'   => $_arr_pageRow,
            'search'    => $_arr_search,
            'combineRows'   => $_arr_combineRows,
            'token'     => $this->obj_request->token(),
        );

        $_arr_tpl = array_replace_recursive($this->generalData, $_arr_tplData);

        //print_r($_arr_combineRows);

        $this->assign($_arr_tpl);

        return $this->fetch();
    }


    function show() {
        $_mix_init = $this->init();

        if ($_mix_init !== true) {
            return $this->error($_mix_init['msg'], $_mix_init['rcode']);
        }

        if (!isset($this->adminAllow['app']['combine']) && !$this->isSuper) { //判断权限
            return $this->error('You do not have permission', 'x040301');
        }

        $_num_combineId = 0;

        if (isset($this->param['id'])) {
            $_num_combineId = $this->obj_request->input($this->param['id'], 'int', 0);
        }

        if ($_num_combineId < 1) {
            return $this->error('Missing ID', 'x040202');
        }

        $_arr_combineRow = $this->mdl_combine->read($_num_combineId);

        if ($_arr_combineRow['rcode'] != 'y040102') {
            return $this->error($_arr_combineRow['msg'], $_arr_combineRow['rcode']);
        }

        $_arr_tplData = array(
            'combineRow'    => $_arr_combineRow,
            'token'     => $this->obj_request->token(),
        );

        $_arr_tpl = array_replace_recursive($this->generalData, $_arr_tplData);

        //print_r($_arr_combineRows);

        $this->assign($_arr_tpl);

        return $this->fetch();
    }


    function form() {
        $_mix_init = $this->init();

        if ($_mix_init !== true) {
            return $this->error($_mix_init['msg'], $_mix_init['rcode']);
        }

        $_num_combineId = 0;

        if (isset($this->param['id'])) {
            $_num_combineId = $this->obj_request->input($this->param['id'], 'int', 0);
        }

        if ($_num_combineId > 0) {
            if (!isset($this->adminAllow['app']['combine']) && !$this->isSuper) { //判断权限
                return $this->error('You do not have permission', 'x040303');
            }

            $_arr_combineRow = $this->mdl_combine->read($_num_combineId);

            if ($_arr_combineRow['rcode'] != 'y040102') {
                return $this->error($_arr_combineRow['msg'], $_arr_combineRow['rcode']);
            }
        } else {
            if (!isset($this->adminAllow['app']['combine']) && !$this->isSuper) { //判断权限
                return $this->error('You do not have permission', 'x040302');
            }

            $_arr_combineRow = array(
                'combine_id'            => 0,
                'combine_name'          => '',
            );
        }

        $_arr_tplData = array(
            'combineRow'    => $_arr_combineRow,
            'token'     => $this->obj_request->token(),
        );

        $_arr_tpl = array_replace_recursive($this->generalData, $_arr_tplData);

        //print_r($_arr_combineRows);

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

        $_arr_inputSubmit = $this->mdl_combine->inputSubmit();

        if ($_arr_inputSubmit['rcode'] != 'y040201') {
            return $this->fetchJson($_arr_inputSubmit['msg'], $_arr_inputSubmit['rcode']);
        }

        if ($_arr_inputSubmit['combine_id'] > 0) {
            if (!isset($this->adminAllow['app']['combine']) && !$this->isSuper) {
                return $this->fetchJson('You do not have permission', 'x040303');
            }
        } else {
            if (!isset($this->adminAllow['app']['combine']) && !$this->isSuper) {
                return $this->fetchJson('You do not have permission', 'x040302');
            }
        }

        $_arr_submitResult   = $this->mdl_combine->submit();

        return $this->fetchJson($_arr_submitResult['msg'], $_arr_submitResult['rcode']);
    }


    function delete() {
        $_mix_init = $this->init();

        if ($_mix_init !== true) {
            return $this->fetchJson($_mix_init['msg'], $_mix_init['rcode']);
        }

        if (!$this->isAjaxPost) {
            return $this->fetchJson('Access denied', '', 405);
        }

        if (!isset($this->adminAllow['app']['combine']) && !$this->isSuper) { //判断权限
            return $this->fetchJson('You do not have permission', 'x040304');
        }

        $_arr_inputDelete = $this->mdl_combine->inputDelete();

        if ($_arr_inputDelete['rcode'] != 'y040201') {
            return $this->fetchJson($_arr_inputDelete['msg'], $_arr_inputDelete['rcode']);
        }

        $_arr_deleteResult = $this->mdl_combine->delete();

        $_arr_langReplace = array(
            'count' => $_arr_deleteResult['count'],
        );

        return $this->fetchJson($_arr_deleteResult['msg'], $_arr_deleteResult['rcode'], 200, $_arr_langReplace);
    }
}
