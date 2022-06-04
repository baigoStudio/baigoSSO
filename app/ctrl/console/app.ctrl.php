<?php
/*-----------------------------------------------------------------
！！！！警告！！！！
以下为系统文件，请勿修改
-----------------------------------------------------------------*/

namespace app\ctrl\console;

use app\classes\console\Ctrl;
use ginkgo\Loader;
use ginkgo\Func;
use ginkgo\Config;
use ginkgo\Crypt;
use ginkgo\Sign;
use ginkgo\Arrays;
use ginkgo\Http;
use ginkgo\Html;
use ginkgo\Log;

// 不能非法包含或直接执行
if (!defined('IN_GINKGO')) {
  return 'Access denied';
}

class App extends Ctrl {

  protected function c_init($param = array()) {
    parent::c_init();

    $this->mdl_app  = Loader::model('App');

    $_str_hrefBase = $this->hrefBase . 'app/';

    $_arr_hrefRow   = array(
      'index'      => $_str_hrefBase . 'index/',
      'add'        => $_str_hrefBase . 'form/',
      'show'       => $_str_hrefBase . 'show/id/',
      'notify'     => $_str_hrefBase . 'notify/id/',
      'edit'       => $_str_hrefBase . 'form/id/',
      'submit'     => $_str_hrefBase . 'submit/',
      'delete'     => $_str_hrefBase . 'delete/',
      'status'     => $_str_hrefBase . 'status/',
      'reset'      => $_str_hrefBase . 'reset/',
      'app_belong' => $this->url['route_console'] . 'app_belong/index/id/',
    );

    $this->generalData['status']    = $this->mdl_app->arr_status;
    $this->generalData['sync']      = $this->mdl_app->arr_sync;
    $this->generalData['hrefRow']   = array_replace_recursive($this->generalData['hrefRow'], $_arr_hrefRow);
  }


  public function index() {
    $_mix_init = $this->init();

    if ($_mix_init !== true) {
      return $this->error($_mix_init['msg'], $_mix_init['rcode']);
    }

    if (!isset($this->adminAllow['app']['browse']) && !$this->isSuper) { //判断权限
      return $this->error('You do not have permission', 'x050301');
    }

    $_arr_searchParam = array(
      'key'       => array('txt', ''),
      'status'    => array('txt', ''),
      'sync'      => array('txt', ''),
    );

    $_arr_search = $this->obj_request->param($_arr_searchParam);

    //print_r($_arr_search);

    $_arr_getData   = $this->mdl_app->lists($this->config['var_default']['perpage'], $_arr_search); //列出

    $_arr_tplData = array(
      'search'    => $_arr_search,
      'pageRow'   => $_arr_getData['pageRow'],
      'appRows'   => $_arr_getData['dataRows'],
      'token'     => $this->obj_request->token(),
    );

    $_arr_tpl = array_replace_recursive($this->generalData, $_arr_tplData);

    //print_r($_arr_appRows);

    $this->assign($_arr_tpl);

    return $this->fetch();
  }


  public function show() {
    $_mix_init = $this->init();

    if ($_mix_init !== true) {
      return $this->error($_mix_init['msg'], $_mix_init['rcode']);
    }

    if (!isset($this->adminAllow['app']['browse']) && !$this->isSuper) { //判断权限
      return $this->error('You do not have permission', 'x050301');
    }

    $_num_appId = 0;

    if (isset($this->param['id'])) {
      $_num_appId = $this->obj_request->input($this->param['id'], 'int', 0);
    }

    if ($_num_appId < 1) {
      return $this->error('Missing ID', 'x050202');
    }

    $_arr_appRow = $this->mdl_app->read($_num_appId);

    if ($_arr_appRow['rcode'] != 'y050102') {
      return $this->error($_arr_appRow['msg'], $_arr_appRow['rcode']);
    }

    $_arr_appRow['app_key'] = Crypt::crypt($_arr_appRow['app_key'], $_arr_appRow['app_name']);

    $_arr_allowRows     = Config::get('app', 'console');

    $_arr_tplData = array(
      'appRow'    => $_arr_appRow,
      'allowRows' => $_arr_allowRows,
      'token'     => $this->obj_request->token(),
    );

    $_arr_tpl = array_replace_recursive($this->generalData, $_arr_tplData);

    //print_r($_arr_appRows);

    $this->assign($_arr_tpl);

    return $this->fetch();
  }


  public function form() {
    $_mix_init = $this->init();

    if ($_mix_init !== true) {
      return $this->error($_mix_init['msg'], $_mix_init['rcode']);
    }

    $_num_appId = 0;

    if (isset($this->param['id'])) {
      $_num_appId = $this->obj_request->input($this->param['id'], 'int', 0);
    }

    $_arr_appRow = $this->mdl_app->read($_num_appId);

    if ($_num_appId > 0) {
      if (!isset($this->adminAllow['app']['edit']) && !$this->isSuper) { //判断权限
        return $this->error('You do not have permission', 'x050303');
      }

      if ($_arr_appRow['rcode'] != 'y050102') {
        return $this->error($_arr_appRow['msg'], $_arr_appRow['rcode']);
      }
    } else {
      if (!isset($this->adminAllow['app']['add']) && !$this->isSuper) { //判断权限
        return $this->error('You do not have permission', 'x050302');
      }
    }

    $_arr_allowRows     = Config::get('app', 'console');

    $_arr_tplData = array(
      'appRow'    => $_arr_appRow,
      'allowRows' => $_arr_allowRows,
      'token'     => $this->obj_request->token(),
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

    $_arr_inputSubmit = $this->mdl_app->inputSubmit();

    if ($_arr_inputSubmit['rcode'] != 'y050201') {
      return $this->fetchJson($_arr_inputSubmit['msg'], $_arr_inputSubmit['rcode']);
    }

    if ($_arr_inputSubmit['app_id'] > 0) {
      if (!isset($this->adminAllow['app']['edit']) && !$this->isSuper) {
        return $this->fetchJson('You do not have permission', 'x050303');
      }
    } else {
      if (!isset($this->adminAllow['app']['add']) && !$this->isSuper) {
        return $this->fetchJson('You do not have permission', 'x050302');
      }
    }

    $_arr_submitResult   = $this->mdl_app->submit();

    return $this->fetchJson($_arr_submitResult['msg'], $_arr_submitResult['rcode']);
  }


  public function delete() {
    $_mix_init = $this->init();

    if ($_mix_init !== true) {
      return $this->fetchJson($_mix_init['msg'], $_mix_init['rcode']);
    }

    if (!$this->isAjaxPost) {
      return $this->fetchJson('Access denied', '', 405);
    }

    if (!isset($this->adminAllow['app']['delete']) && !$this->isSuper) { //判断权限
      return $this->fetchJson('You do not have permission', 'x050304');
    }

    $_arr_inputDelete = $this->mdl_app->inputDelete();

    if ($_arr_inputDelete['rcode'] != 'y050201') {
      return $this->fetchJson($_arr_inputDelete['msg'], $_arr_inputDelete['rcode']);
    }

    $_arr_deleteResult = $this->mdl_app->delete();

    $_arr_langReplace = array(
      'count' => $_arr_deleteResult['count'],
    );

    return $this->fetchJson($_arr_deleteResult['msg'], $_arr_deleteResult['rcode'], 200, $_arr_langReplace);
  }


  public function status() {
    $_mix_init = $this->init();

    if ($_mix_init !== true) {
      return $this->fetchJson($_mix_init['msg'], $_mix_init['rcode']);
    }

    if (!$this->isAjaxPost) {
      return $this->fetchJson('Access denied', '', 405);
    }

    if (!isset($this->adminAllow['app']['edit']) && !$this->isSuper) { //判断权限
      return $this->fetchJson('You do not have permission', 'x050303');
    }

    $_arr_inputStatus = $this->mdl_app->inputStatus();

    if ($_arr_inputStatus['rcode'] != 'y050201') {
      return $this->fetchJson($_arr_inputStatus['msg'], $_arr_inputStatus['rcode']);
    }

    $_arr_statusResult = $this->mdl_app->status();

    $_arr_langReplace = array(
      'count' => $_arr_statusResult['count'],
    );

    return $this->fetchJson($_arr_statusResult['msg'], $_arr_statusResult['rcode'], 200, $_arr_langReplace);
  }


  public function reset() {
    $_mix_init = $this->init();

    if ($_mix_init !== true) {
      return $this->fetchJson($_mix_init['msg'], $_mix_init['rcode']);
    }

    if (!$this->isAjaxPost) {
      return $this->fetchJson('Access denied', '', 405);
    }

    if (!isset($this->adminAllow['app']['edit']) && !$this->isSuper) { //判断权限
      return $this->fetchJson('You do not have permission', 'x050303');
    }

    $_arr_inputReset = $this->mdl_app->inputCommon();

    if ($_arr_inputReset['rcode'] != 'y050201') {
      return $this->fetchJson($_arr_inputReset['msg'], $_arr_inputReset['rcode']);
    }

    $_arr_appRow = $this->mdl_app->check($_arr_inputReset['app_id']);

    if ($_arr_appRow['rcode'] != 'y050102') {
      return $this->fetchJson($_arr_appRow['msg'], $_arr_appRow['rcode']);
    }

    $_arr_resetResult = $this->mdl_app->reset();

    return $this->fetchJson($_arr_resetResult['msg'], $_arr_resetResult['rcode']);
  }


  public function notify() {
    $_mix_init = $this->init();

    if ($_mix_init !== true) {
      return $this->error($_mix_init['msg'], $_mix_init['rcode']);
    }

    if (!isset($this->adminAllow['app']['browse']) && !$this->isSuper) { //判断权限
      return $this->error('You do not have permission', 'x050303');
    }

    $_num_appId = 0;

    if (isset($this->param['id'])) {
      $_num_appId = $this->obj_request->input($this->param['id'], 'int', 0);
    }

    if ($_num_appId < 1) {
      return $this->error('Missing ID', 'x050202');
    }

    $_arr_appRow = $this->mdl_app->read($_num_appId);

    if ($_arr_appRow['rcode'] != 'y050102') {
      return $this->error($_arr_appRow['msg'], $_arr_appRow['rcode']);
    }

    $_str_echo = Func::rand();

    $_arr_src  = array(
      'echostr'   => $_str_echo,
      'timestamp' => GK_NOW,
    );

    $_str_src       = Arrays::toJson($_arr_src);

    $_str_appKey    = Crypt::crypt($_arr_appRow['app_key'], $_arr_appRow['app_name']);
    $_str_encrypt   = Crypt::encrypt($_str_src, $_str_appKey, $_arr_appRow['app_secret']);

    if ($_str_encrypt === false) {
      $_str_error = Crypt::getError();
      return $this->error($_str_error, $_arr_appRow['rcode']);
    }

    $_arr_data = array(
      'app_id'    => $_arr_appRow['app_id'],
      'app_key'   => $_str_appKey,
      'code'      => $_str_encrypt,
    );

    if (Func::notEmpty($_arr_appRow['app_param'])) {
      foreach ($_arr_appRow['app_param'] as $_key_param=>$_value_param) {
        if (isset($_value_param['key']) && isset($_value_param['value'])) {
          $_arr_data[$_value_param['key']] = $_value_param['value'];
        }
      }
    }

    $_arr_data['sign'] = Sign::make($_str_src, $_str_appKey . $_arr_appRow['app_secret']);

    $_obj_http = Http::instance();

    $_str_urlNotify    = Html::decode($_arr_appRow['app_url_notify'], 'url');
    $_str_urlNotify    = rtrim($_str_urlNotify, '/');

    if (stristr($_str_urlNotify, '?')) {
      $_str_conn = '&';
    } else {
      $_str_conn = '?';
    }

    $_str_urlNotify = $_str_urlNotify . $_str_conn . 'm=sso&c=notify&a=test';

    $_arr_notifyResult = $_obj_http->request($_str_urlNotify, $_arr_data, 'post');

    //print_r($_obj_http->getResult());

    if (isset($_arr_notifyResult['echostr']) && $_arr_notifyResult['echostr'] == $_str_echo) {
      $_arr_tplData = array(
        'msg'       => 'Testing successfully',
        'rcode'     => 'y050401',
        'rstatus'   => 'y',
      );
    } else {
      $_str_result = $_obj_http->getResult();

      $_arr_tplData = array(
        'msg'       => 'Testing failed',
        'rcode'     => 'x050401',
        'rstatus'   => 'x',
        'msg_more'  => $_str_result,
      );

      //Log::record('type: notify, action: ' . $_str_urlNotify . ', app_id: ' . $_arr_appRow['app_id'] . ', result: failed ' . $_str_result, 'log');
    }

    $_arr_tpl = array_replace_recursive($this->generalData, $_arr_tplData);

    //print_r($_arr_appRows);

    $this->assign($_arr_tpl);

    return $this->fetch();
  }
}
