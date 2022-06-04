<?php
/*-----------------------------------------------------------------
！！！！警告！！！！
以下为系统文件，请勿修改
-----------------------------------------------------------------*/

namespace app\model\console;

use app\model\common\Admin as Admin_Common;
use ginkgo\Arrays;

// 不能非法包含或直接执行
if (!defined('IN_GINKGO')) {
  return 'Access denied';
}

/*-------------管理员模型-------------*/
class Auth extends Admin_Common {

  protected $table = 'admin';


  /** 创建、编辑表单验证
   * inputSubmit function.
   *
   * @access public
   * @return void
   */
  public function inputSubmit() {
    $_arr_inputParam = array(
      'admin_name'            => array('txt', ''),
      'admin_note'            => array('txt', ''),
      'admin_status'          => array('txt', ''),
      'admin_type'            => array('txt', ''),
      'admin_nick'            => array('txt', ''),
      'admin_allow'           => array('arr', array()),
      'admin_allow_profile'   => array('arr', array()),
      '__token__'             => array('txt', ''),
    );

    $_arr_inputSubmit = $this->obj_request->post($_arr_inputParam);

    $_mix_vld = $this->validate($_arr_inputSubmit, '', 'submit');

    if ($_mix_vld !== true) {
      return array(
        'rcode' => 'x020201',
        'msg'   => end($_mix_vld),
      );
    }

    $_arr_inputSubmit['rcode'] = 'y020201';

    $this->inputSubmit = $_arr_inputSubmit;

    return $_arr_inputSubmit;
  }
}
