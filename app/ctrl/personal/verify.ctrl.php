<?php
/*-----------------------------------------------------------------
！！！！警告！！！！
以下为系统文件，请勿修改
-----------------------------------------------------------------*/

namespace app\ctrl\personal;

use app\classes\personal\Ctrl;
use ginkgo\Loader;
use ginkgo\Crypt;
use ginkgo\Func;

// 不能非法包含或直接执行
if (!defined('IN_GINKGO')) {
  return 'Access denied';
}

class Verify extends Ctrl {

  protected function c_init($param = array()) {
    parent::c_init();

    $_str_hrefBase = $this->hrefBase . 'verify/';

    $_arr_hrefRow = array(
      'pass-submit'    => $_str_hrefBase . 'pass-submit/',
      'mailbox-submit' => $_str_hrefBase . 'mailbox-submit/',
      'confirm-submit' => $_str_hrefBase . 'confirm-submit/',
      'captcha'        => $this->url['route_misc'] . 'captcha/index/id/captcha_verify/',
      'captcha-check'  => $this->url['route_misc'] . 'captcha/check/id/captcha_verify/',
    );

    $this->generalData['hrefRow']   = array_replace_recursive($this->generalData['hrefRow'], $_arr_hrefRow);
  }

  public function confirm() {
    $_arr_searchParam = array(
      'id'       => array('int', 0),
      'token'    => array('txt', ''),
    );

    $_arr_search = $this->obj_request->param($_arr_searchParam);

    if ($_arr_search['id'] < 1) {
      return $this->error('Missing ID', 'x120201');
    }

    if (Func::isEmpty($_arr_search['token'])) {
      return $this->error('Unable to get token', 'x120202');
    }

    $_arr_verifyRow = $this->mdl_verify->read($_arr_search['id']);

    if ($_arr_verifyRow['rcode'] != 'y120102') {
      return $this->error($_arr_verifyRow['msg'], $_arr_verifyRow['rcode']);
    }

    if ($_arr_verifyRow['verify_status'] != 'enable') {
      return $this->error('Token is no longer valid', 'x120203');
    }

    if ($_arr_verifyRow['verify_type'] != 'confirm') {
      return $this->error('Token type error', 'x120207');
    }

    if ($_arr_verifyRow['verify_token_expire'] < GK_NOW) {
      return $this->error('Token expired', 'x120204');
    }

    if (Crypt::crypt($_arr_verifyRow['verify_token'], $_arr_verifyRow['verify_rand']) != $_arr_search['token']) {
      return $this->error('Token is incorrect', 'x120205');
    }

    $_arr_userRow = $this->mdl_user->read($_arr_verifyRow['verify_user_id']);

    if ($_arr_userRow['user_status'] == 'disabled') {
      return $this->error('User is disabled', 'x010402');
    }

    if ($_arr_userRow['user_status'] == 'enable') {
      return $this->error('No need to activate again', 'x010409');
    }

    $_arr_verifyRow['verify_token'] = $_arr_search['token'];

    $_arr_tplData = array(
      'userRow'   => $_arr_userRow,
      'verifyRow' => $_arr_verifyRow,
      'token'     => $this->obj_request->token(),
    );

    $_arr_tpl = array_replace_recursive($this->generalData, $_arr_tplData);

    $this->assign($_arr_tpl);

    return $this->fetch();
  }


  public function confirmSubmit() {
    if (!$this->isAjaxPost) {
      return $this->fetchJson('Access denied', '', 405);
    }

    $_arr_inputConfirm = $this->mdl_verify->inputCommon();

    if ($_arr_inputConfirm['rcode'] != 'y120201') {
      return $this->fetchJson($_arr_inputConfirm['msg'], $_arr_inputConfirm['rcode']);
    }

    $_arr_verifyRow = $this->mdl_verify->read($_arr_inputConfirm['verify_id']);

    if ($_arr_verifyRow['rcode'] != 'y120102') {
      return $this->fetchJson($_arr_verifyRow['msg'], $_arr_verifyRow['rcode']);
    }

    if ($_arr_verifyRow['verify_status'] != 'enable') {
      return $this->fetchJson('Token is no longer valid', 'x120203');
    }

    if ($_arr_verifyRow['verify_type'] != 'confirm') {
      return $this->fetchJson('Token type error', 'x120207');
    }

    if ($_arr_verifyRow['verify_token_expire'] < GK_NOW) {
      return $this->fetchJson('Token expired', 'x120204');
    }

    if (Crypt::crypt($_arr_verifyRow['verify_token'], $_arr_verifyRow['verify_rand']) != $_arr_inputConfirm['verify_token']) {
      return $this->fetchJson('Token is incorrect', 'x120205');
    }

    $_arr_userRow = $this->mdl_user->read($_arr_verifyRow['verify_user_id']);

    if ($_arr_userRow['user_status'] == 'disabled') {
      return $this->fetchJson('User is disabled', 'x010402');
    }

    if ($_arr_userRow['user_status'] == 'enable') {
      return $this->fetchJson('No need to activate again', 'x010409');
    }

    $_arr_confirmResult = $this->mdl_user->confirm($_arr_userRow['user_id']);

    if ($_arr_confirmResult['rcode'] == 'y010103') {
      $this->mdl_verify->disabled();
    }

    return $this->fetchJson($_arr_confirmResult['msg'], $_arr_confirmResult['rcode']);
  }


  public function mailbox() {
    $_arr_searchParam = array(
      'id'       => array('int', 0),
      'token'    => array('txt', ''),
    );

    $_arr_search = $this->obj_request->param($_arr_searchParam);

    if ($_arr_search['id'] < 1) {
      return $this->error('Missing ID', 'x120201');
    }

    if (Func::isEmpty($_arr_search['token'])) {
      return $this->error('Unable to get token', 'x120202');
    }

    $_arr_verifyRow = $this->mdl_verify->read($_arr_search['id']);

    if ($_arr_verifyRow['rcode'] != 'y120102') {
      return $this->error($_arr_verifyRow['msg'], $_arr_verifyRow['rcode']);
    }

    if ($_arr_verifyRow['verify_status'] != 'enable') {
      return $this->error('Token is no longer valid', 'x120203');
    }

    if ($_arr_verifyRow['verify_type'] != 'mailbox') {
      return $this->error('Token type error', 'x120207');
    }

    if ($_arr_verifyRow['verify_token_expire'] < GK_NOW) {
      return $this->error('Token expired', 'x120204');
    }

    if (Crypt::crypt($_arr_verifyRow['verify_token'], $_arr_verifyRow['verify_rand']) != $_arr_search['token']) {
      return $this->error('Token is incorrect', 'x120205');
    }

    $_arr_userRow = $this->mdl_user->read($_arr_verifyRow['verify_user_id']);

    if ($_arr_userRow['user_status'] == 'disabled') {
      return $this->error('User is disabled', 'x010402');
    }

    $_arr_verifyRow['verify_token'] = $_arr_search['token'];

    $_arr_tplData = array(
      'userRow'   => $_arr_userRow,
      'verifyRow' => $_arr_verifyRow,
      'token'     => $this->obj_request->token(),
    );

    $_arr_tpl = array_replace_recursive($this->generalData, $_arr_tplData);

    $this->assign($_arr_tpl);

    return $this->fetch();
  }


  public function mailboxSubmit() {
    if (!$this->isAjaxPost) {
      return $this->fetchJson('Access denied', '', 405);
    }

    $_arr_inputMailbox = $this->mdl_verify->inputCommon();

    if ($_arr_inputMailbox['rcode'] != 'y120201') {
      return $this->fetchJson($_arr_inputMailbox['msg'], $_arr_inputMailbox['rcode']);
    }

    $_arr_verifyRow = $this->mdl_verify->read($_arr_inputMailbox['verify_id']);

    if ($_arr_verifyRow['rcode'] != 'y120102') {
      return $this->fetchJson($_arr_verifyRow['msg'], $_arr_verifyRow['rcode']);
    }

    if ($_arr_verifyRow['verify_status'] != 'enable') {
      return $this->fetchJson('Token is no longer valid', 'x120203');
    }

    if ($_arr_verifyRow['verify_type'] != 'mailbox') {
      return $this->fetchJson('Token type error', 'x120207');
    }

    if ($_arr_verifyRow['verify_token_expire'] < GK_NOW) {
      return $this->fetchJson('Token expired', 'x120204');
    }

    if (Crypt::crypt($_arr_verifyRow['verify_token'], $_arr_verifyRow['verify_rand']) != $_arr_inputMailbox['verify_token']) {
      return $this->fetchJson('Token is incorrect', 'x120205');
    }

    $_arr_userRow = $this->mdl_user->read($_arr_verifyRow['verify_user_id']);

    if ($_arr_userRow['user_status'] == 'disabled') {
      return $this->fetchJson('User is disabled', 'x010402');
    }

    $this->mdl_user->inputMailbox['user_id']        = $_arr_userRow['user_id'];
    $this->mdl_user->inputMailbox['user_mail_new']  = $_arr_verifyRow['verify_mail'];

    $_arr_mailboxResult = $this->mdl_user->mailbox();

    if ($_arr_mailboxResult['rcode'] == 'y010103') {
      $this->mdl_verify->disabled();
    }

    return $this->fetchJson($_arr_mailboxResult['msg'], $_arr_mailboxResult['rcode']);
  }


  public function pass() {
    $_arr_searchParam = array(
      'id'       => array('int', 0),
      'token'    => array('txt', ''),
    );

    $_arr_search = $this->obj_request->param($_arr_searchParam);

    if ($_arr_search['id'] < 1) {
      return $this->error('Missing ID', 'x120201');
    }

    if (Func::isEmpty($_arr_search['token'])) {
      return $this->error('Unable to get token', 'x120202');
    }

    $_arr_verifyRow = $this->mdl_verify->read($_arr_search['id']);

    if ($_arr_verifyRow['rcode'] != 'y120102') {
      return $this->error($_arr_verifyRow['msg'], $_arr_verifyRow['rcode']);
    }

    if ($_arr_verifyRow['verify_status'] != 'enable') {
      return $this->error('Token is no longer valid', 'x120203');
    }

    if ($_arr_verifyRow['verify_type'] != 'forgot') {
      return $this->error('Token type error', 'x120207');
    }

    if ($_arr_verifyRow['verify_token_expire'] < GK_NOW) {
      return $this->error('Token expired', 'x120204');
    }

    /*print_r(Crypt::crypt($_arr_verifyRow['verify_token'], $_arr_verifyRow['verify_rand']));
    print_r('<br>');
    print_r($_arr_search['token']);*/

    if (Crypt::crypt($_arr_verifyRow['verify_token'], $_arr_verifyRow['verify_rand']) != $_arr_search['token']) {
      return $this->error('Token is incorrect', 'x120205');
    }

    $_arr_userRow = $this->mdl_user->read($_arr_verifyRow['verify_user_id']);

    if ($_arr_userRow['user_status'] == 'disabled') {
      return $this->error('User is disabled', 'x010402');
    }

    $_arr_verifyRow['verify_token'] = $_arr_search['token'];

    $_arr_tplData = array(
      'userRow'   => $_arr_userRow,
      'verifyRow' => $_arr_verifyRow,
      'token'     => $this->obj_request->token(),
    );

    $_arr_tpl = array_replace_recursive($this->generalData, $_arr_tplData);

    $this->assign($_arr_tpl);

    return $this->fetch();
  }


  public function passSubmit() {
    if (!$this->isAjaxPost) {
      return $this->fetchJson('Access denied', '', 405);
    }

    $_arr_inputPass = $this->mdl_verify->inputPass();

    if ($_arr_inputPass['rcode'] != 'y120201') {
      return $this->fetchJson($_arr_inputPass['msg'], $_arr_inputPass['rcode']);
    }

    $_arr_verifyRow = $this->mdl_verify->read($_arr_inputPass['verify_id']);

    if ($_arr_verifyRow['rcode'] != 'y120102') {
      return $this->fetchJson($_arr_verifyRow['msg'], $_arr_verifyRow['rcode']);
    }

    if ($_arr_verifyRow['verify_status'] != 'enable') {
      return $this->fetchJson('Token is no longer valid', 'x120203');
    }

    if ($_arr_verifyRow['verify_type'] != 'forgot') {
      return $this->fetchJson('Token type error', 'x120207');
    }

    if ($_arr_verifyRow['verify_token_expire'] < GK_NOW) {
      return $this->fetchJson('Token expired', 'x120204');
    }

    if (Crypt::crypt($_arr_verifyRow['verify_token'], $_arr_verifyRow['verify_rand']) != $_arr_inputPass['verify_token']) {
      return $this->fetchJson('Token is incorrect', 'x120205');
    }

    $_arr_userRow = $this->mdl_user->read($_arr_verifyRow['verify_user_id']);

    if ($_arr_userRow['user_status'] == 'disabled') {
      return $this->fetchJson('User is disabled', 'x010402');
    }

    $_str_rand          = Func::rand();

    $_str_passNew       = Crypt::crypt($_arr_inputPass['user_pass_new'], $_str_rand);

    $_arr_passResult    = $this->mdl_user->pass($_arr_userRow['user_id'], $_str_passNew, $_str_rand);

    if ($_arr_passResult['rcode'] == 'y010103') {
      $this->mdl_verify->disabled();
    }

    return $this->fetchJson($_arr_passResult['msg'], $_arr_passResult['rcode']);
  }
}
