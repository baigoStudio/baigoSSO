<?php
/*-----------------------------------------------------------------
！！！！警告！！！！
以下为系统文件，请勿修改
-----------------------------------------------------------------*/

namespace app\classes\install;

use app\classes\Ctrl as Ctrl_Base;
use ginkgo\Loader;
use ginkgo\Route;
use ginkgo\Func;
use ginkgo\Config;
use ginkgo\Plugin;
use ginkgo\File;

// 不能非法包含或直接执行
if (!defined('IN_GINKGO')) {
  return 'Access denied';
}


/*-------------安装通用控制器-------------*/
abstract class Ctrl extends Ctrl_Base {

  private $configInstalled;
  protected $mdl_opt;

  protected function c_init($param = array()) {
    parent::c_init();

    $this->configInstalled = 'installed' . GK_EXT_INC;

    Plugin::listen('action_install_init'); //管理后台初始化时触发

    $this->mdl_opt     = Loader::model('Opt');
  }


  protected function init($is_install = true, $chk_php = true) {
    if ($is_install) {
      $_arr_chkResult = $this->chkInstall();
    } else {
      $_arr_chkResult = $this->chkUpgrade();
    }

    $_arr_data = array(
      'installed'         => Config::get('installed'),
      'path_installed'    => $this->configInstalled,
      'step'              => $this->stepProcess(),
    );

    $this->generalData = array_replace_recursive($this->generalData, $_arr_data);

    if (Func::notEmpty($_arr_chkResult['rcode'])) {
      return $_arr_chkResult;
    }

    $_arr_phpResult = $this->chkPhplib();

    if ($chk_php) {
      if (Func::notEmpty($_arr_phpResult['rcode'])) {
        return $_arr_phpResult;
      }
    }

    return true;
  }


  private function chkPhplib() {
    $_str_rcode     = '';
    $_str_msg       = '';

    $_num_errCount  = 0;

    foreach ($this->phplib as $_key=>&$_value) {
      if (extension_loaded($_key)) {
        $_value['installed'] = true;
      } else {
        ++$_num_errCount;
      }
    }

    $_arr_data = array(
      'phplib'    => $this->phplib,
      'err_count' => $_num_errCount,
    );

    $this->generalData = array_replace_recursive($this->generalData, $_arr_data);

    if ($_num_errCount > 0) {
      $_str_rcode     = 'x030405';
      $_str_msg       = 'Missing PHP extensions';
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

    if (File::fileHas(GK_APP_CONFIG . $this->configInstalled)) {
      $_arr_installed = Config::load(GK_APP_CONFIG . $this->configInstalled, 'installed');
      $_str_rcode     = 'x030402';
      $_str_msg       = 'System already installed';

      if (isset($_arr_installed['prd_installed_pub']) && PRD_SSO_PUB > $_arr_installed['prd_installed_pub']) { //如果小于当前版本
        $_str_rcode     = 'x030404';
        $_str_msg       = 'Need to execute the upgrader';
        $_str_jump      = $this->url['route_install'] . 'upgrade/';
      }
    }

    if (Func::notEmpty($_str_jump) && !$this->isAjaxPost) {
      $this->redirect($_str_jump)->send('install->chk_install');
    }

    return array(
      'rcode' => $_str_rcode,
      'msg'   => $this->obj_lang->get($_str_msg, $this->route['mod'] . '.common'),
    );
  }


  private function chkUpgrade() {
    $_str_rcode     = '';
    $_str_jump      = '';
    $_str_msg       = '';

    if (File::fileHas(GK_APP_CONFIG . $this->configInstalled)) { //如果新文件存在
      $_arr_installed = Config::load(GK_APP_CONFIG . $this->configInstalled, 'installed');

      if (!isset($_arr_installed['prd_installed_pub'])) {
        $_str_rcode     = 'x030403';
        $_str_msg       = 'Need to execute the installer';
        $_str_jump      = $this->url['route_install'];
      } else if (PRD_SSO_PUB <= $_arr_installed['prd_installed_pub']) { //如果小于当前版本
        $_str_rcode     = 'x030402';
        $_str_msg       = 'System already installed';
      }
    } else {
      $_str_rcode     = 'x030403';
      $_str_msg       = 'Need to execute the installer';
      $_str_jump      = $this->url['route_install'];
    }

    if (Func::notEmpty($_str_jump) && !$this->isAjaxPost) {
      $this->redirect($_str_jump)->send('install->chk_upgrade');
    }

    return array(
      'rcode' => $_str_rcode,
      'msg'   => $this->obj_lang->get($_str_msg, $this->route['mod'] . '.common'),
    );
  }


  protected function pathProcess() {
    parent::pathProcess();

    $_str_pathTplConsole = GK_PATH_TPL;

    if (Func::notEmpty($this->tplPath)) {
      $_str_pathTplConsole = GK_APP_TPL . 'console' . DS . $this->tplPath . DS;
    }

    $_arr_url = array(
      'path_tpl_console'  => $_str_pathTplConsole,
    );

    $this->url = array_replace_recursive($this->url, $_arr_url);

      $this->generalData = array_replace_recursive($this->generalData, $_arr_url);
  }


  protected function configProcess() {
    parent::configProcess();

    $_str_configPhplib  = BG_PATH_CONFIG . 'install' . DS . 'phplib' . GK_EXT_INC;
    $this->phplib       = Config::load($_str_configPhplib, 'phplib');
  }

  private function stepProcess() {
    $_arr_install      = $this->config['install'][$this->route['ctrl']];

    $_arr_installKeys  = array_keys($_arr_install);

    $_index            = array_search($this->route['act'], $_arr_installKeys);

    //print_r($_arr_install);

    $_arr_prev     = array_slice($_arr_install, $_index - 1, -1);
    if (Func::isEmpty($_arr_prev)) {
      $_key_prev = 'index';
    } else {
      $_key_prev = key($_arr_prev);
    }

    $_arr_next     = array_slice($_arr_install, $_index + 1, 1);
    if (Func::isEmpty($_arr_next)) {
      $_key_next = 'over';
    } else {
      $_key_next = key($_arr_next);
    }

    return array(
        'prev' => $_key_prev,
        'next' => $_key_next,
    );
  }


  protected function createTable($table) {
    $_mdl_table         = Loader::model($table, '', 'install');
    $_arr_creatResult   = $_mdl_table->createTable();

    return array(
      'rcode'   => $_arr_creatResult['rcode'],
      'msg'     => $_arr_creatResult['msg'],
    );
  }


  protected function alterTable($table) {
    $_mdl_table         = Loader::model($table, '', 'install');
    $_arr_creatResult   = $_mdl_table->alterTable();

   return array(
      'rcode'   => $_arr_creatResult['rcode'],
      'msg'     => $_arr_creatResult['msg'],
    );
  }


  protected function createIndex($index) {
    $_mdl_index         = Loader::model($index, '', 'install');
    $_arr_creatResult   = $_mdl_index->createIndex();

    return array(
      'rcode'   => $_arr_creatResult['rcode'],
      'msg'     => $_arr_creatResult['msg'],
    );
  }


  protected function createView($view) {
    $_mdl_view          = Loader::model($view, '', 'install');
    $_arr_creatResult   = $_mdl_view->createView();

    return array(
      'rcode'   => $_arr_creatResult['rcode'],
      'msg'     => $_arr_creatResult['msg'],
    );
  }
}
