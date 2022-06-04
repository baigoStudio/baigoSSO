<?php
/*-----------------------------------------------------------------
！！！！警告！！！！
以下为系统文件，请勿修改
-----------------------------------------------------------------*/

namespace app\classes;

use ginkgo\Ctrl as Gk_Ctrl;
use ginkgo\App;
use ginkgo\Loader;
use ginkgo\Route;
use ginkgo\Config;
use ginkgo\Func;

// 不能非法包含或直接执行
if (!defined('IN_GINKGO')) {
  return 'Access denied';
}

/*-------------控制中心通用控制器-------------*/
abstract class Ctrl extends Gk_Ctrl {

  protected $isAjaxPost   = false;
  protected $isAjax       = false;
  protected $isPost       = false;
  protected $isGet        = false;
  protected $generalData  = array();
  protected $url          = array();
  protected $config;
  protected $tplPath;
  protected $hrefBase;

  protected function c_init($param = array()) { //构造函数
    $this->pathProcess();
    $this->configProcess();
    $this->configAdd();

    if ($this->obj_request->isAjax() && $this->obj_request->isPost()) {
      $this->isAjaxPost = true;
      $this->isAjax     = true;
      $this->isPost     = true;
    }

    if ($this->obj_request->isAjax()) {
      $this->isAjax = true;
    }

    if ($this->obj_request->isPost()) {
      $this->isPost = true;
    }

    if ($this->obj_request->isGet()) {
      $this->isGet = true;
    }

    App::setTimezone($this->configBase['site_timezone']);

    $_arr_data = array(
      'ui_ctrl'      => $this->configUi,
      'config'       => $this->config,
      'route'        => $this->route,
      'route_orig'   => $this->routeOrig,
      'param'        => $this->param,
      'is_ajax_post' => $this->isAjaxPost,
      'is_ajax'      => $this->isAjax,
      'is_post'      => $this->isPost,
      'is_get'       => $this->isGet,
    );

    $this->generalData = array_replace_recursive($this->generalData, $_arr_data);
  }


  protected function error($msg, $rcode = '', $status = 200, $langReplace = '') {
    $_arr_data = $this->dataProcess($msg, $rcode, $langReplace);

    $_arr_tpl = array_replace_recursive($this->generalData, $_arr_data);

    return $this->fetch('common' . DS . 'error', $_arr_tpl, '', $status);
  }


  protected function fetchJson($msg, $rcode = '', $status = 200, $langReplace = '') {
    $_arr_data = $this->dataProcess($msg, $rcode, $langReplace);

    return $this->json($_arr_data, $status);
  }


  protected function fetchJsonp($msg, $rcode = '', $status = 200, $langReplace = '') {
    $_arr_data = $this->dataProcess($msg, $rcode, $langReplace);

    return $this->jsonp($_arr_data, $status);
  }


  private function dataProcess($msg, $rcode = '', $langReplace = '') {
    $_arr_data = array(
      'msg'       => $this->obj_lang->get($msg, '', $langReplace),
      'detail'    => $this->obj_lang->get($rcode, '', $langReplace, false),
      'rcode'     => $rcode,
      'rstatus'   => substr($rcode, 0, 1),
    );

    return $_arr_data;
  }

  private function configAdd() {
    $_arr_config     = Config::get();
    $_arr_configUi   = Config::get('ui_ctrl');
    $_arr_convention = Loader::load(BG_PATH_CONFIG . 'convention' . GK_EXT_INC);

    $_arr_config = array_replace_recursive($_arr_convention, $_arr_config);

    if (isset($_arr_configUi['update_check']) && $_arr_configUi['update_check'] != 'on') {
      //print_r('dis');
      Config::delete('chkver', 'console.opt');
      if (isset($_arr_config['console']['opt']['chkver'])) {
        unset($_arr_config['console']['opt']['chkver']);
      }
    }

    if (!isset($_arr_configUi['logo_personal']) || Func::isEmpty($_arr_configUi['logo_personal'])) {
      $_arr_configUi['logo_personal'] = '{:DIR_STATIC}sso/image/logo_green.svg';
    }

    if (!isset($_arr_configUi['logo_console_login']) || Func::isEmpty($_arr_configUi['logo_console_login'])) {
      $_arr_configUi['logo_console_login'] = '{:DIR_STATIC}sso/image/logo_green.svg';
    }

    if (!isset($_arr_configUi['logo_console_head']) || Func::isEmpty($_arr_configUi['logo_console_head'])) {
      $_arr_configUi['logo_console_head'] = '{:DIR_STATIC}sso/image/logo_white.svg';
    }

    if (!isset($_arr_configUi['logo_console_foot']) || Func::isEmpty($_arr_configUi['logo_console_foot'])) {
      $_arr_configUi['logo_console_foot'] = '{:DIR_STATIC}sso/image/logo_white.svg';
    }

    if (!isset($_arr_configUi['logo_install']) || Func::isEmpty($_arr_configUi['logo_install'])) {
      $_arr_configUi['logo_install'] = '{:DIR_STATIC}sso/image/logo_green.svg';
    }

    $this->config       = $_arr_config;
    $this->configUi     = $_arr_configUi;

    Config::set($_arr_config);

    $this->configBase   = $_arr_config['var_extra']['base'];
  }

  protected function configProcess() {
    $_str_pathMod  = BG_PATH_CONFIG . $this->route['mod'] . DS . 'common' . GK_EXT_INC;
    Config::load($_str_pathMod, $this->route['mod']);

    $_str_pathCtrl  = BG_PATH_CONFIG . $this->route['mod'] . DS . $this->route['ctrl'] . GK_EXT_INC;
    Config::load($_str_pathCtrl, $this->route['ctrl'], $this->route['mod']);
  }


  protected function pathProcess() {
    $_str_dirRoot       = $this->obj_request->root();
    $_str_urlRoot       = $this->obj_request->baseUrl(true);
    $_str_routeRoot     = $this->obj_request->baseUrl();

    Route::setExclude(array('page_belong', 'page-belong'));

    $this->tplPath = Config::get('path', 'tpl');

    $_str_pathTplCommon = GK_APP_TPL . 'common' . DS;

    if (Func::notEmpty($this->tplPath)) {
      $_str_pathTplCommon .= $this->tplPath . DS;
    }

    $_arr_url = array(
      'dir_root'          => $_str_dirRoot,
      'dir_static'        => $_str_dirRoot . GK_NAME_STATIC . '/',
      'url_root'          => $_str_urlRoot,
      'url_api'           => $_str_urlRoot . 'api/',
      'url_personal'      => $_str_urlRoot . 'personal/',
      'route_root'        => $_str_routeRoot,
      'route_install'     => $_str_routeRoot . 'install/',
      'route_console'     => $_str_routeRoot . 'console/',
      'route_misc'        => $_str_routeRoot . 'misc/',
      'route_personal'    => $_str_routeRoot . 'personal/',
      'path_tpl'          => $this->pathTpl,
      'tpl_include'       => $this->pathTpl . 'include' . DS,
      'tpl_ctrl'          => $this->pathTpl . $this->routeOrig['ctrl'] . DS . 'include' . DS,
      'tpl_common'        => $_str_pathTplCommon,
      'tpl_icon'          => $_str_pathTplCommon . 'icon' . DS,
    );

    $this->url = array_replace_recursive($this->url, $_arr_url);

    $this->generalData = array_replace_recursive($this->generalData, $_arr_url);
  }
}
