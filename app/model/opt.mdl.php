<?php
/*-----------------------------------------------------------------
！！！！警告！！！！
以下为系统文件，请勿修改
-----------------------------------------------------------------*/

namespace app\model;

use ginkgo\Request;
use ginkgo\Loader;

// 不能非法包含或直接执行
if (!defined('IN_GINKGO')) {
  return 'Access denied';
}

/*-------------设置项模型-------------*/
class Opt {

  protected $obj_request;
  protected $vld_opt;

  public function __construct() { //构造函数
    $this->obj_request  = Request::instance();
    $this->vld_opt      = Loader::validate('Opt');
  }
}
