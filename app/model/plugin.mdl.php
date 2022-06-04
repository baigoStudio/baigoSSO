<?php
/*-----------------------------------------------------------------
！！！！警告！！！！
以下为系统文件，请勿修改
-----------------------------------------------------------------*/

namespace app\model;

use ginkgo\Request;
use ginkgo\Loader;
use ginkgo\Config;
use ginkgo\File;

// 不能非法包含或直接执行
if (!defined('IN_GINKGO')) {
  return 'Access denied';
}

/*-------------插件模型-------------*/
class Plugin {

  protected $configPlugin = array();
  protected $obj_request;
  protected $vld_plugin;

  public function __construct() { //构造函数
    $this->configPlugin = Config::get('plugin');

    $this->obj_request  = Request::instance();
    $this->obj_file     = File::instance();
    $this->vld_plugin   = Loader::validate('plugin');
  }
}
