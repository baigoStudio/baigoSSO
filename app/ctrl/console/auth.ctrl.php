<?php
/*-----------------------------------------------------------------
！！！！警告！！！！
以下为系统文件，请勿修改
-----------------------------------------------------------------*/

namespace app\ctrl\console;

use app\classes\console\Ctrl;
use ginkgo\Loader;
use ginkgo\Func;

// 不能非法包含或直接执行
if (!defined('IN_GINKGO')) {
  return 'Access denied';
}

class Auth extends Ctrl {

  protected function c_init($param = array()) {
    parent::c_init();

    $this->mdl_user = Loader::model('User');
    $this->mdl_auth = Loader::model('Auth');

    $_str_hrefBase = $this->hrefBase . 'auth/';

    $_arr_hrefRow   = array(
      'submit' => $_str_hrefBase . 'submit/',
      'check'  => $_str_hrefBase . 'check/',
      'back'   => $this->url['route_console'] . 'admin/',
    );

    $this->generalData['status']    = $this->mdl_auth->arr_status;
    $this->generalData['type']      = $this->mdl_auth->arr_type;
    $this->generalData['hrefRow']   = array_replace_recursive($this->generalData['hrefRow'], $_arr_hrefRow);
  }


  public function form() {
    $_mix_init = $this->init();

    if ($_mix_init !== true) {
      return $this->error($_mix_init['msg'], $_mix_init['rcode']);
    }

    if (!isset($this->adminAllow['admin']['add']) && !$this->isSuper) { //判断权限
      return $this->error('You do not have permission', 'x020302');
    }

    $_arr_adminRow = array(
      'admin_status'  => $this->mdl_auth->arr_status[0],
      'admin_type'    => $this->mdl_auth->arr_type[0],
    );

    $_arr_tplData = array(
      'adminRow'  => $_arr_adminRow,
      'token'     => $this->obj_request->token(),
    );

    $_arr_tpl = array_replace_recursive($this->generalData, $_arr_tplData);

    //print_r($_arr_adminRows);

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

    if (!isset($this->adminAllow['admin']['add']) && !$this->isSuper) {
      return $this->fetchJson('You do not have permission', 'x020302');
    }

    $_arr_inputSubmit = $this->mdl_auth->inputSubmit();

    if ($_arr_inputSubmit['rcode'] != 'y020201') {
      return $this->fetchJson($_arr_inputSubmit['msg'], $_arr_inputSubmit['rcode']);
    }

    //检验用户名是否存在
    $_arr_userRow = $this->mdl_user->check($_arr_inputSubmit['admin_name'], 'user_name');

    if ($_arr_userRow['rcode'] != 'y010102') {
      return $this->fetchJson('User not found, please use add administrator', $_arr_userRow['rcode']);
    }

    $_arr_adminRow = $this->mdl_auth->check($_arr_userRow['user_id']);

    if ($_arr_adminRow['rcode'] == 'y020102') {
      return $this->fetchJson('Administrator already exists', 'x020404');
    }

    $this->mdl_auth->inputSubmit['admin_id'] = $_arr_userRow['user_id'];

    $_arr_authResult = $this->mdl_auth->submit();

    return $this->fetchJson($_arr_authResult['msg'], $_arr_authResult['rcode']);
  }


  public function check() {
    $_mix_init = $this->init();

    if ($_mix_init !== true) {
      return $this->fetchJson($_mix_init['msg'], $_mix_init['rcode']);
    }

    $_arr_return = array(
      'msg' => '',
    );

    $_str_adminName = $this->obj_request->get('admin_name');

    if (Func::notEmpty($_str_adminName)) {
      $_arr_userRow   = $this->mdl_user->check($_str_adminName, 'user_name');

      if ($_arr_userRow['rcode'] != 'y010102') {
        $_arr_return = array(
          'rcode'     => $_arr_userRow['rcode'],
          'error_msg' => $this->obj_lang->get('User not found, please use add administrator'),
        );
      } else {
        $_arr_adminRow = $this->mdl_auth->check($_arr_userRow['user_id']);

        //print_r($_arr_adminRow);

        if ($_arr_adminRow['rcode'] == 'y020102') {
          $_arr_return = array(
            'rcode'     => 'x020404',
            'error_msg' => $this->obj_lang->get('Administrator already exists'),
          );
        }
      }
    }

    return $this->json($_arr_return);
  }
}
