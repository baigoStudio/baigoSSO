<?php
/*-----------------------------------------------------------------
！！！！警告！！！！
以下为系统文件，请勿修改
-----------------------------------------------------------------*/

namespace app\ctrl\console;

use app\classes\console\Ctrl;
use ginkgo\Loader;
use ginkgo\Crypt;
use ginkgo\Func;

// 不能非法包含或直接执行
if (!defined('IN_GINKGO')) {
  return 'Access denied';
}

class Login extends Ctrl {

  protected function c_init($param = array()) {
    parent::c_init();

    $this->mdl_user     = Loader::model('User');

    $_str_hrefBase = $this->hrefBase . 'login/';

    $_arr_hrefRow   = array(
      'login'         => $_str_hrefBase,
      'submit'        => $_str_hrefBase . 'submit/',
      'forgot'        => $this->url['url_personal'] . 'forgot/',
      'cookie'        => $this->url['route_console'] . 'cookie/clear/',
      'captcha'       => $this->url['route_misc'] . 'captcha/index/id/console_login/',
      'captcha-check' => $this->url['route_misc'] . 'captcha/check/id/console_login/',
    );

    $this->generalData['hrefRow']   = array_replace_recursive($this->generalData['hrefRow'], $_arr_hrefRow);
  }

  public function index() {
    $_mix_init = $this->init(false);

    if ($_mix_init !== true) {
      return $this->error($_mix_init['msg'], $_mix_init['rcode']);
    }

    $_str_urlJump = $this->url['route_console'];

    if ($this->adminLogged['rcode'] == 'y020102') {
      return $this->redirect($_str_urlJump);
    }

    $_str_forward = $this->redirect()->restore();

    if (Func::isEmpty($_str_forward) || !stristr($_str_forward, 'console')) {
      $_str_forward = $this->url['route_console'];
    }

    $_arr_tplData = array(
      'forward'   => $_str_forward,
      'token'     => $this->obj_request->token(),
    );

    $_arr_tpl = array_replace_recursive($this->generalData, $_arr_tplData);

    $this->assign($_arr_tpl);

    return $this->fetch();
  }


  public function submit() {
    $_mix_init = $this->init(false);

    if ($_mix_init !== true) {
      return $this->fetchJson($_mix_init['msg'], $_mix_init['rcode']);
    }

    if (!$this->isAjaxPost) {
      return $this->fetchJson('Access denied', '', 405);
    }

    $_arr_inputSubmit = $this->mdl_login->inputSubmit();

    if ($_arr_inputSubmit['rcode'] != 'y020201') {
      return $this->fetchJson($_arr_inputSubmit['msg'], $_arr_inputSubmit['rcode']);
    }

    $_arr_userRow = $this->mdl_user->read($_arr_inputSubmit['admin_name'], 'user_name');
    if ($_arr_userRow['rcode'] != 'y010102') {
      return $this->fetchJson($_arr_userRow['msg'], $_arr_userRow['rcode']);
    }

    if ($_arr_userRow['user_status'] == 'disabled') {
      return $this->fetchJson('User is disabled', 'x010402');
    }

    $_arr_adminRow = $this->mdl_login->read($_arr_userRow['user_id']);
    if ($_arr_adminRow['rcode'] != 'y020102') {
      return $this->fetchJson($_arr_adminRow['msg'], $_arr_adminRow['rcode']);
    }

    if ($_arr_adminRow['admin_status'] != 'enable') {
      return $this->fetchJson('Administrator is disabled', 'x020402');
    }

    $_str_crypt = Crypt::crypt($_arr_inputSubmit['admin_pass'], $_arr_userRow['user_rand']);

    if ($_str_crypt != $_arr_userRow['user_pass']) {
      return $this->fetchJson('Password is incorrect', 'x010201');
    }

    $this->mdl_user->inputSubmit['user_id']     = $_arr_userRow['user_id'];
    $this->mdl_user->login();

    $this->mdl_login->inputSubmit['admin_id']   = $_arr_adminRow['admin_id'];
    $_arr_loginResult = $this->mdl_login->login();

    // 如新加密规则与数据库不一致，则对密码重新加密
    /*$_str_userPass  = Crypt::crypt($_arr_inputSubmit['admin_pass'], $_arr_userRow['user_name']);

    if ($_str_userPass != $_arr_userRow['user_pass']) {
      $this->mdl_user->pass($_arr_userRow['user_id'], $_str_userPass);
    }*/

    /*print_r($_str_crypt . '<br>');
    print_r($_arr_userRow['user_pass']);
    exit;*/

    $_arr_adminRow = array_replace_recursive($_arr_adminRow, $_arr_loginResult);

    $this->obj_auth->write($_arr_adminRow, false, 'form', $_arr_inputSubmit['admin_remember'], $this->url['route_console']);

    return $this->fetchJson('Login successful', 'y020401');
  }

  public function logout() {
    $this->obj_auth->end(true);

    return $this->redirect($this->url['route_console']);
  }
}
