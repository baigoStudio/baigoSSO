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

// 不能非法包含或直接执行
if (!defined('IN_GINKGO')) {
  return 'Access denied';
}

/*-------------用户类-------------*/
class Sync extends Ctrl {

  protected function c_init($param = array()) {
    parent::c_init();

    $this->mdl_sync     = Loader::model('Sync');
  }


  public function login() {
    $_mix_init = $this->init();

    if ($_mix_init !== true) {
      return $this->fetchJson($_mix_init['msg'], $_mix_init['rcode']);
    }

    if (!$this->isPost) {
      return $this->fetchJson('Access denied', '', 405);
    }

    $_arr_inputCommon = $this->mdl_sync->inputCommon($this->decryptRow);

    if ($_arr_inputCommon['rcode'] != 'y010201') {
      return $this->fetchJson($_arr_inputCommon['msg'], $_arr_inputCommon['rcode']);
    }

    $_arr_userRow   = $this->userCheck($_arr_inputCommon);

    $this->appLists();

    $_arr_urlRows = $this->syncProcess('login');

    $_arr_tplData = array(
      'rcode'      => 'y050401',
      'urlRows'    => $_arr_urlRows,
    );

    $_arr_tpl = array_replace_recursive($this->version, $_arr_tplData);

    return $this->json($_arr_tpl);
  }


  public function logout() {
    $_mix_init = $this->init();

    if ($_mix_init !== true) {
      return $this->fetchJson($_mix_init['msg'], $_mix_init['rcode']);
    }

    if (!$this->isPost) {
      return $this->fetchJson('Access denied', '', 405);
    }

    $_arr_inputCommon = $this->mdl_sync->inputCommon($this->decryptRow);

    if ($_arr_inputCommon['rcode'] != 'y010201') {
      return $this->fetchJson($_arr_inputCommon['msg'], $_arr_inputCommon['rcode']);
    }

    $_arr_userRow   = $this->userCheck($_arr_inputCommon);

    $this->appLists();

    $_arr_urlRows = $this->syncProcess('logout');

    $_arr_tplData = array(
      'rcode'      => 'y050401',
      'urlRows'    => $_arr_urlRows,
    );

    $_arr_tpl = array_replace_recursive($this->version, $_arr_tplData);

    return $this->json($_arr_tpl);
  }


  private function appLists() {
    $_mdl_appCombineView    = Loader::model('App_Combine_View');
    $_mdl_combineBelong     = Loader::model('Combine_Belong');

    $_arr_search = array(
      'app_id' => $this->appRow['app_id'],
    );

    $_arr_combineIds = $_mdl_combineBelong->combineIds($_arr_search);

    $_arr_search = array(
      'status'        => 'enable',
      'sync'          => 'on',
      'has_sync'      => true,
      'not_ids'       => array($this->appRow['app_id']),
      'combine_ids'   => $_arr_combineIds,
    );
    $this->appRows = $_mdl_appCombineView->lists(array(100, 'limit'), $_arr_search);
  }


  protected function userCheck($arr_inputCheck) {
    if ($arr_inputCheck['rcode'] != 'y010201') {
      return $arr_inputCheck;
    }

    $_arr_select = array(
      'user_id',
      'user_name',
      'user_mail',
      'user_status',
      'user_access_token',
      'user_access_expire',
    );

    $_arr_userRow = $this->mdl_sync->read($arr_inputCheck['user_id'], 'user_id', 0, $_arr_select);

    $this->userRow = $_arr_userRow;

    return $this->userCheckProcess($_arr_userRow, $arr_inputCheck);
  }


  private function syncProcess($type = 'login') {
    $_arr_src     = $this->userRow;
    $_arr_urlRows = array();

    foreach ($this->appRows as $_key=>$_value) {
      $_str_appKey           = Crypt::crypt($_value['app_key'], $_value['app_name']);

      $_arr_src['app_id']    = $_value['app_id'];
      $_arr_src['app_key']   = $_str_appKey;
      $_arr_src['timestamp'] = GK_NOW;

      //unset($_arr_src['rcode']);
      $_str_src              = Arrays::toJson($_arr_src);
      $_str_encrypt          = Crypt::encrypt($_str_src, $_str_appKey, $_value['app_secret']);

      if ($_str_encrypt !== false) {
        if (stristr($_value['app_url_sync'], '?')) {
          $_str_conn = '&';
        } else {
          $_str_conn = '?';
        }
        $_str_url = $_value['app_url_sync'] . $_str_conn . 'm=sso&c=sync&a=' . $type;

        $_arr_data = array(
          'code' => $_str_encrypt,
        );

        $_arr_data['sign'] = Sign::make($_str_src, $_str_appKey . $_value['app_secret']);

        $_arr_urlRows[] = $_str_url . '&' . http_build_query($_arr_data);
      }
    }

    return $_arr_urlRows;
  }
}
