<?php
/*-----------------------------------------------------------------
！！！！警告！！！！
以下为系统文件，请勿修改
-----------------------------------------------------------------*/

namespace app\ctrl\index;

// 不能非法包含或直接执行
if (!defined('IN_GINKGO')) {
  return 'Access denied';
}

class Index {

  public function __construct() {
    $_array_tpl = array(
      'path_tpl' => GK_PATH_TPL,
    );
    //$this->obj_tpl = new TPL($_array_tpl);
  }


  public function index() {
    /*$this->obj_tpl->assign($this->param);
    $this->obj_tpl->fetch('index');*/
  }
}
