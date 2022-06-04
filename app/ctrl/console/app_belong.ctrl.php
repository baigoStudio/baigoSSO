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

class App_Belong extends Ctrl {

  protected function c_init($param = array()) {
    parent::c_init();

    $this->mdl_user         = Loader::model('User');
    $this->mdl_userAppView  = Loader::model('User_App_View');

    $this->mdl_app          = Loader::model('App');
    $this->mdl_appBelong    = Loader::model('App_Belong');

    $_str_hrefBase = $this->hrefBase . 'app_belong/';

    $_arr_hrefRow   = array(
      'index'  => $_str_hrefBase . 'index/id/',
      'submit' => $_str_hrefBase . 'submit/',
      'remove' => $_str_hrefBase . 'remove/',
      'back'   => $this->url['route_console'] . 'app/',
      'show'   => $this->url['route_console'] . 'user/show/id/'
    );

    $this->generalData['status']    = $this->mdl_user->arr_status;
    $this->generalData['hrefRow']   = array_replace_recursive($this->generalData['hrefRow'], $_arr_hrefRow);
  }


  public function index() {
    $_mix_init = $this->init();

    if ($_mix_init !== true) {
      return $this->error($_mix_init['msg'], $_mix_init['rcode']);
    }

    if (!isset($this->adminAllow['app']['belong']) && !$this->isSuper) { //判断权限
      return $this->error('You do not have permission', 'x050305');
    }

    $_arr_searchParam = array(
      'id'        => array('int', 0),
      'key'       => array('txt', ''),
      'status'    => array('txt', ''),
    );

    $_arr_search = $this->obj_request->param($_arr_searchParam);

    if ($_arr_search['id'] < 1) {
      return $this->error('Missing ID', 'x050202');
    }

    $_arr_appRow = $this->mdl_app->read($_arr_search['id']);

    if ($_arr_appRow['rcode'] != 'y050102') {
      return $this->error($_arr_appRow['msg'], $_arr_appRow['rcode']);
    }

    $_arr_searchBelong = array(
      'app_id' => $_arr_appRow['app_id'],
    );

    $_str_pageParamBelong   = 'page-belong';

    $_arr_pagination        = array($this->config['var_default']['perpage'], '', '', $_str_pageParamBelong);

    $_arr_getDataBelong     = $this->mdl_userAppView->lists($_arr_pagination, $_arr_searchBelong); //列出

    //print_r($_arr_getDataBelong);

    $_arr_search['not_in'] = Db::table('app_belong')->where('belong_app_id', '=', $_arr_appRow['app_id'])->fetchSql()->select('belong_user_id');

    //print_r($_arr_search);

    $_arr_getData   = $this->mdl_user->lists($this->config['var_default']['perpage'], $_arr_search); //列出

    $_arr_tplData = array(
      'appRow'            => $_arr_appRow,
      'search'            => $_arr_search,

      'pageParamBelong'   => $_str_pageParamBelong,
      'pageRowBelong'     => $_arr_getDataBelong['pageRow'],
      'userRowsBelong'    => $_arr_getDataBelong['dataRows'],

      'pageRowUser'       => $_arr_getData['pageRow'],
      'userRows'          => $_arr_getData['dataRows'],

      'token'             => $this->obj_request->token(),
    );

    $_arr_tpl = array_replace_recursive($this->generalData, $_arr_tplData);

    //print_r($_arr_appRows);

    $this->assign($_arr_tpl);

    return $this->fetch();
  }


  public function submit() {
    $_mix_init = $this->init();

    if ($_mix_init !== true) {
      return $this->fetchJson($_mix_init['msg'], $_mix_init['rcode']);
    }

    if (!$this->isAjaxPost) {
      return $this->fetchJson('Access denied', '', 405);
    }

    if (!isset($this->adminAllow['app']['belong']) && !$this->isSuper) {
      return $this->fetchJson('You do not have permission', 'x050305');
    }

    $_arr_inputSubmit = $this->mdl_appBelong->inputSubmit();

    if ($_arr_inputSubmit['rcode'] != 'y070201') {
      return $this->fetchJson($_arr_inputSubmit['msg'], $_arr_inputSubmit['rcode']);
    }

    $_arr_appRow = $this->mdl_app->check($_arr_inputSubmit['app_id']);

    //print_r($_arr_appRow);

    if ($_arr_appRow['rcode'] != 'y050102') {
      return $this->fetchJson($_arr_appRow['msg'], $_arr_appRow['rcode']);
    }

    $_arr_submitResult   = $this->mdl_appBelong->submit();

    $_arr_langReplace = array(
      'count' => $_arr_submitResult['count'],
    );

    return $this->fetchJson($_arr_submitResult['msg'], $_arr_submitResult['rcode'], 200, $_arr_langReplace);
  }


  public function remove() {
    $_mix_init = $this->init();

    if ($_mix_init !== true) {
      return $this->fetchJson($_mix_init['msg'], $_mix_init['rcode']);
    }

    if (!$this->isAjaxPost) {
      return $this->fetchJson('Access denied', '', 405);
    }

    if (!isset($this->adminAllow['app']['belong']) && !$this->isSuper) {
      return $this->fetchJson('You do not have permission', 'x050305');
    }

    $_arr_inputRemove = $this->mdl_appBelong->inputRemove();

    if ($_arr_inputRemove['rcode'] != 'y070201') {
      return $this->fetchJson($_arr_inputRemove['msg'], $_arr_inputRemove['rcode']);
    }

    $_arr_appRow = $this->mdl_app->check($_arr_inputRemove['app_id']);

    //print_r($_arr_appRow);

    if ($_arr_appRow['rcode'] != 'y050102') {
      return $this->fetchJson($_arr_appRow['msg'], $_arr_appRow['rcode']);
    }

    $_arr_removeResult   = $this->mdl_appBelong->remove();

    $_arr_langReplace = array(
      'count' => $_arr_removeResult['count'],
    );

    return $this->fetchJson($_arr_removeResult['msg'], $_arr_removeResult['rcode'], 200, $_arr_langReplace);
  }
}
