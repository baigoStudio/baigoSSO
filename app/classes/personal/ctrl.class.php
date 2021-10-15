<?php
/*-----------------------------------------------------------------
！！！！警告！！！！
以下为系统文件，请勿修改
-----------------------------------------------------------------*/

namespace app\classes\personal;

use app\classes\Ctrl as Ctrl_Base;
use ginkgo\Loader;
use ginkgo\Route;
use ginkgo\Func;
use ginkgo\Config;
use ginkgo\Plugin;

// 不能非法包含或直接执行
if (!defined('IN_GINKGO')) {
  return 'Access denied';
}


/*-------------控制中心通用控制器-------------*/
abstract class Ctrl extends Ctrl_Base {

  protected function c_init($param = array()) { //构造函数
    parent::c_init();

    Plugin::listen('action_personal_init'); //管理后台初始化时触发

    $this->mdl_user       = Loader::model('User');
    $this->mdl_verify     = Loader::model('Verify');

    $this->configMailtpl    = Config::get('mailtpl', 'var_extra');;

    $this->obj_view->setPath(BG_TPL_PERSONAL . $this->configBase['site_tpl']);
  }

  protected function pathProcess() {
    parent::pathProcess();

    $this->tplPathPersonal = GK_PATH_TPL;

    if (Func::notEmpty($this->tplPath)) {
      $_str_pathTplPersonal = BG_TPL_PERSONAL . DS . $this->tplPath . DS;
    }

    $_arr_url = array(
      'path_tpl_personal' => $_str_pathTplPersonal,
    );

    $this->url = $_arr_url;

    $this->generalData = array_replace_recursive($this->generalData, $_arr_url);
  }
}
