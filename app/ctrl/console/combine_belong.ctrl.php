<?php
/*-----------------------------------------------------------------
！！！！警告！！！！
以下为系统文件，请勿修改
-----------------------------------------------------------------*/

namespace app\ctrl\console;

use app\classes\console\Ctrl;
use ginkgo\Loader;
use ginkgo\Db;

// 不能非法包含或直接执行
if (!defined('IN_GINKGO')) {
    return 'Access denied';
}

class Combine_Belong extends Ctrl {

    protected function c_init($param = array()) {
        parent::c_init();

        $this->mdl_app                  = Loader::model('App');
        $this->mdl_appCombineView       = Loader::model('App_Combine_View');

        $this->mdl_combine              = Loader::model('Combine');
        $this->mdl_combineBelong        = Loader::model('Combine_Belong');

        $this->generalData['status']    = $this->mdl_app->arr_status;
        $this->generalData['sync']      = $this->mdl_app->arr_sync;
    }


    function index() {
        $_mix_init = $this->init();

        if ($_mix_init !== true) {
            return $this->error($_mix_init['msg'], $_mix_init['rcode']);
        }

        if (!isset($this->adminAllow['app']['edit']) && !$this->isSuper) { //判断权限
            return $this->error('You do not have permission', 'x040305');
        }

        $_arr_searchParam = array(
            'id'        => array('int', 0),
            'key'       => array('txt', ''),
            'status'    => array('txt', ''),
        );

        $_arr_search = $this->obj_request->param($_arr_searchParam);

        if ($_arr_search['id'] < 1) {
            return $this->error('Missing ID', 'x040202');
        }

        $_arr_combineRow = $this->mdl_combine->read($_arr_search['id']);

        if ($_arr_combineRow['rcode'] != 'y040102') {
            return $this->error($_arr_combineRow['msg'], $_arr_combineRow['rcode']);
        }

        $_arr_search['not_in'] = Db::table('combine_belong')->where('belong_combine_id', '=', $_arr_combineRow['combine_id'])->fetchSql()->select('belong_app_id');

        //print_r($_arr_search);

        $_num_appCount   = $this->mdl_app->count($_arr_search); //统计记录数
        $_arr_pageRow    = $this->obj_request->pagination($_num_appCount); //取得分页数据
        $_arr_appRows    = $this->mdl_app->lists($this->config['var_default']['perpage'], $_arr_pageRow['offset'], $_arr_search); //列出

        $_arr_searchBelong = array(
            'combine_id' => $_arr_combineRow['combine_id'],
        );

        $_str_pageParamBelong   = 'page-belong';

        $_num_appCountBelong    = $this->mdl_appCombineView->count($_arr_searchBelong); //统计记录数
        $_arr_pageRowBelong     = $this->obj_request->pagination($_num_appCountBelong, 0, 'get', $_str_pageParamBelong); //取得分页数据
        $_arr_appRowsBelong     = $this->mdl_appCombineView->lists($this->config['var_default']['perpage'], $_arr_pageRowBelong['offset'], $_arr_searchBelong); //列出

        $_arr_tplData = array(
            'combineRow'        => $_arr_combineRow,

            'search'            => $_arr_search,
            'pageRowApp'        => $_arr_pageRow,
            'appRows'           => $_arr_appRows,

            'pageParamBelong'   => $_str_pageParamBelong,
            'pageRowBelong'     => $_arr_pageRowBelong,
            'appRowsBelong'     => $_arr_appRowsBelong,

            'token'             => $this->obj_request->token(),
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

        if (!isset($this->adminAllow['app']['edit']) && !$this->isSuper) {
            return $this->fetchJson('You do not have permission', 'x040305');
        }

        $_arr_inputSubmit = $this->mdl_combineBelong->inputSubmit();

        if ($_arr_inputSubmit['rcode'] != 'y060201') {
            return $this->fetchJson($_arr_inputSubmit['msg'], $_arr_inputSubmit['rcode']);
        }

        $_arr_combineRow = $this->mdl_combine->check($_arr_inputSubmit['combine_id']);

        //print_r($_arr_combineRow);

        if ($_arr_combineRow['rcode'] != 'y040102') {
            return $this->fetchJson($_arr_combineRow['msg'], $_arr_combineRow['rcode']);
        }

        $_arr_submitResult   = $this->mdl_combineBelong->submit();

        $_arr_langReplace = array(
            'count' => $_arr_submitResult['count'],
        );

        return $this->fetchJson($_arr_submitResult['msg'], $_arr_submitResult['rcode'], 200, $_arr_langReplace);
    }


    function remove() {
        $_mix_init = $this->init();

        if ($_mix_init !== true) {
            return $this->fetchJson($_mix_init['msg'], $_mix_init['rcode']);
        }

        if (!$this->isAjaxPost) {
            return $this->fetchJson('Access denied', '', 405);
        }

        if (!isset($this->adminAllow['app']['edit']) && !$this->isSuper) {
            return $this->fetchJson('You do not have permission', 'x040305');
        }

        $_arr_inputRemove = $this->mdl_combineBelong->inputRemove();

        if ($_arr_inputRemove['rcode'] != 'y060201') {
            return $this->fetchJson($_arr_inputRemove['msg'], $_arr_inputRemove['rcode']);
        }

        $_arr_combineRow = $this->mdl_combine->check($_arr_inputRemove['combine_id']);

        //print_r($_arr_combineRow);

        if ($_arr_combineRow['rcode'] != 'y040102') {
            return $this->fetchJson($_arr_combineRow['msg'], $_arr_combineRow['rcode']);
        }

        $_arr_removeResult   = $this->mdl_combineBelong->remove();

        $_arr_langReplace = array(
            'count' => $_arr_removeResult['count'],
        );

        return $this->fetchJson($_arr_removeResult['msg'], $_arr_removeResult['rcode'], 200, $_arr_langReplace);
    }
}
