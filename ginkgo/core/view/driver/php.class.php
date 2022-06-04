<?php
/*-----------------------------------------------------------------
！！！！警告！！！！
以下为系统文件，请勿修改
-----------------------------------------------------------------*/

namespace ginkgo\view\driver;

use ginkgo\Func;
use ginkgo\File;
use ginkgo\view\Driver;
use ginkgo\except\Tpl_Not_Found;

// 不能非法包含或直接执行
if (!defined('IN_GINKGO')) {
  return 'Access denied';
}

// 视图驱动类 (php 原生)
class Php extends Driver {

  /** 渲染文件模板
   * fetch function.
   *
   * @access public
   * @param string $tpl (default: '') 模板名
   * @param string $data (default: '') 内容
   * @return 渲染结果
   */
  public function fetch($tpl = '', $data = '') {
    $_str_tpl = $this->pathProcess($tpl); // 路径处理

    if (!File::fileHas($_str_tpl)) {
      $_obj_excpt = new Tpl_Not_Found('Template not found', 500); // 报错
      $_obj_excpt->setData('err_detail', $_str_tpl);

      throw $_obj_excpt;
    }

    //$this->data = $data;

    if (Func::notEmpty($data)) {
      extract($data, EXTR_OVERWRITE); // 拆分为模板变量
    }

    if (Func::notEmpty($this->obj)) {
      extract($this->obj, EXTR_OVERWRITE); // 拆分为模板变量
    }

    require($_str_tpl);
  }


  /** 渲染内容模板
   * display function.
   *
   * @access public
   * @param string $content (default: '')
   * @param string $data (default: '')
   * @return void
   */
  public function display($content = '', $data = '') {

    if (Func::notEmpty($data)) {
      extract($data, EXTR_OVERWRITE);
    }

    eval('?>' . $content);
  }
}
