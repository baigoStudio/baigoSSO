<?php
/*-----------------------------------------------------------------
！！！！警告！！！！
以下为系统文件，请勿修改
-----------------------------------------------------------------*/

namespace app\classes\console;

use app\classes\Ctrl as Ctrl_Base;
use ginkgo\Loader;
use ginkgo\Route;
use ginkgo\Func;
use ginkgo\Session;
use ginkgo\Cookie;
use ginkgo\Config;
use ginkgo\Crypt;
use ginkgo\Plugin;
use ginkgo\Auth;
use ginkgo\File;

// 不能非法包含或直接执行
if (!defined('IN_GINKGO')) {
  return 'Access denied';
}

/*-------------控制中心通用控制器-------------*/
abstract class Ctrl extends Ctrl_Base {

  protected $isSuper = false;
  protected $obj_auth;
  protected $mdl_login;

  protected $adminAllow = array();

  protected $_arr_adminLogged = array(
    'admin_status'  => '',
  );

  protected function c_init($param = array()) { //构造函数
    parent::c_init();

    Plugin::listen('action_console_init'); //管理后台初始化时触发

    $this->obj_auth     = Auth::instance(array(), 'admin');
    $this->mdl_login    = Loader::model('Login', '', 'console');

    $this->hrefBase     = $this->url['route_console'];

    $_arr_adminLogged   = $this->sessionRead();

    if (isset($_arr_adminLogged['admin_shortcut']) && Func::notEmpty($_arr_adminLogged['admin_shortcut'])) {
      foreach ($_arr_adminLogged['admin_shortcut'] as $_key=>&$_value) {
        $_value['href'] = $this->url['route_console'] . $_value['ctrl'] . '/' . $_value['act']. '/';
      }
    }

    if (isset($_arr_adminLogged['admin_type']) && $_arr_adminLogged['admin_type'] == 'super') {
      $this->isSuper = true;
    }

    if (isset($_arr_adminLogged['admin_allow'])) {
      $this->adminAllow = $_arr_adminLogged['admin_allow'];
    }

    $this->generalData['hrefRow'] = array(
      'logout' => $this->url['route_console'] . 'login/logout/',
    );

    $this->generalData['adminLogged']       = $_arr_adminLogged;
    $this->adminLogged                      = $_arr_adminLogged;
  }


  protected function init($chk_admin = true) {
    $_arr_chkResult = $this->chkInstall();

    if (Func::notEmpty($_arr_chkResult['rcode'])) {
      return $_arr_chkResult;
    }

    if ($chk_admin) {
      $_arr_adminResult = $this->isAdmin();

      if (Func::notEmpty($_arr_adminResult['rcode'])) {
        return $_arr_adminResult;
      }
    }

    return true;
  }


  /** 验证 session, 并获取用户信息
   * sessionRead function.
   *
   * @access protected
   * @return void
   */
  protected function sessionRead() {
    $_num_adminId  = 0;
    $_arr_authRow  = $this->obj_auth->read();

    $_arr_session  = $_arr_authRow['session'];
    $_arr_remember = $_arr_authRow['remember'];

    if (isset($_arr_session['admin_id']) && $_arr_session['admin_id'] > 0) {
      $_num_adminId = $_arr_session['admin_id'];
    } else if (isset($_arr_remember['admin_id']) && $_arr_remember['admin_id'] > 0) {
      $_num_adminId = $_arr_remember['admin_id'];
    }

    $_arr_adminRow = $this->mdl_login->read($_num_adminId);
    //print_r($_arr_adminRow);

    if ($_arr_adminRow['rcode'] != 'y020102') {
      $this->obj_auth->end();
      return $_arr_adminRow;
    }

    if ($_arr_adminRow['admin_status'] == 'disabled') {
      $this->obj_auth->end();
      return array(
        'rcode' => 'x020402',
      );
    }

    if (!$this->obj_auth->check($_arr_adminRow, $this->url['route_console'])) {
      return array(
        'msg'   => $this->obj_auth->getError(),
        'rcode' => 'x020403',
      );
    }

    return $_arr_adminRow;
  }


  private function isAdmin() {
    $_str_rcode     = '';
    $_str_jump      = '';
    $_str_msg       = '';

    //print_r($this->param);

    if ($this->adminLogged['rcode'] != 'y020102') {
      $this->obj_auth->end();
      $_str_rcode = $this->adminLogged['rcode'];
      $_str_msg   = 'You has not sign in yet';

      if (!isset($this->param['view']) || ($this->param['view'] != 'iframe' && $this->param['view'] != 'modal')) {
        $_str_jump = $this->url['route_console'] . 'login/';
      }
    }

    if (Func::notEmpty($_str_jump) && !$this->isAjaxPost) {
      $_obj_redirect = $this->redirect($_str_jump);
      $_obj_redirect->remember();
      return $_obj_redirect->send('console->is_admin');
    }

    return array(
        'rcode' => $_str_rcode,
        'msg'   => $this->obj_lang->get($_str_msg, $this->route['mod'] . '.common'),
    );
  }


  private function chkInstall() {
    $_str_rcode     = '';
    $_str_jump      = '';
    $_str_msg       = '';

    $_str_configInstalled     = GK_APP_CONFIG . 'installed' . GK_EXT_INC;

    if (File::fileHas($_str_configInstalled)) { //如果新文件存在
      $_arr_installed = Config::load($_str_configInstalled, 'installed');

      if (PRD_SSO_PUB > $_arr_installed['prd_installed_pub']) { //如果小于当前版本
        $_str_rcode = 'x030404';
        $_str_msg   = 'Need to execute the upgrader';
        $_str_jump  = $this->url['route_install'] . 'upgrade';
      }
    } else { //如已安装文件不存在
      $_str_rcode = 'x030403';
      $_str_msg   = 'Need to execute the installer';
      $_str_jump  = $this->url['route_install'];
    }

    if (Func::notEmpty($_str_jump) && !$this->isAjaxPost) {
      $this->redirect($_str_jump)->send('console->chk_install');
    }

    return array(
      'rcode' => $_str_rcode,
      'msg'   => $this->obj_lang->get($_str_msg, $this->route['mod'] . '.common'),
    );
  }


  protected function configProcess() {
    parent::configProcess();

    $_arr_configOptExtra   = Config::get('opt_extra', 'console');

    $_str_configOpt        = BG_PATH_CONFIG . 'console' . DS . 'opt' . GK_EXT_INC;
    $_arr_configOpt        = Config::load($_str_configOpt, 'opt', 'console');

    $_str_configConsoleMod = BG_PATH_CONFIG . 'console' . DS . 'console_mod' . GK_EXT_INC;
    $_arr_configConsoleMod = Config::load($_str_configConsoleMod, 'console_mod', 'console');

    $_str_configProfile    = BG_PATH_CONFIG . 'console' . DS . 'profile_mod' . GK_EXT_INC;
    $_arr_configProfile    = Config::load($_str_configProfile, 'profile_mod', 'console');

    if (is_array($_arr_configOptExtra) && Func::notEmpty($_arr_configOptExtra)) {
      foreach ($_arr_configOptExtra as $_key_m=>&$_value_m) {
        $_value_m['href'] = $this->url['route_console'] . $_value_m['ctrl'] . '/' . $_value_m['act'] . '/';
      }
    }

    if (is_array($_arr_configOpt) && Func::notEmpty($_arr_configOpt)) {
      foreach ($_arr_configOpt as $_key_m=>&$_value_m) {
        $_value_m['href'] = $this->url['route_console'] . 'opt/' . $_key_m . '/';
      }
    }

    if (is_array($_arr_configConsoleMod) && Func::notEmpty($_arr_configConsoleMod)) {
      foreach ($_arr_configConsoleMod as $_key_m=>&$_value_m) {
        $_value_m['main']['href'] = $this->url['route_console'] . $_value_m['main']['ctrl'] . '/';

        if (isset($_value_m['lists']) && Func::notEmpty($_value_m['lists'])) {
          foreach ($_value_m['lists'] as $_key_s=>&$_value_s) {
            $_value_s['href'] = $this->url['route_console'] . $_value_s['ctrl'] . '/' . $_value_s['act'] . '/';
          }
        }
      }
    }

    if (is_array($_arr_configProfile) && Func::notEmpty($_arr_configProfile)) {
      foreach ($_arr_configProfile as $_key_m=>&$_value_m) {
        $_value_m['href'] = $this->url['route_console'] . 'profile/' . $_key_m . '/';
      }
    }

    Config::set('opt_extra', $_arr_configOptExtra, 'console');
    Config::set('opt', $_arr_configOpt, 'console');
    Config::set('console_mod', $_arr_configConsoleMod, 'console');
    Config::set('profile_mod', $_arr_configProfile, 'console');
  }
}
