<?php
/*-----------------------------------------------------------------
！！！！警告！！！！
以下为系统文件，请勿修改
-----------------------------------------------------------------*/

namespace app\model\common;

use app\model\Admin as Admin_Base;
use ginkgo\Func;
use ginkgo\Arrays;

// 不能非法包含或直接执行
if (!defined('IN_GINKGO')) {
  return 'Access denied';
}

/*-------------管理员模型-------------*/
class Admin extends Admin_Base {

  public $inputSubmit = array();

  /** 管理员创建、编辑提交
   * submit function.
   *
   * @access public
   * @param string $str_adminPass (default: '')
   * @param string $str_adminRand (default: '')
   * @return void
   */
  public function submit() {
    $_arr_adminRow  = $this->check($this->inputSubmit['admin_id']);

    $_arr_adminData = array(
      'admin_id'          => $this->inputSubmit['admin_id'],
      'admin_name'        => $this->inputSubmit['admin_name'],
      'admin_time'        => GK_NOW,
      'admin_time_login'  => GK_NOW,
      'admin_ip'          => $this->obj_request->ip(),
    );

    if (isset($this->inputSubmit['admin_type'])) {
      $_arr_adminData['admin_type'] = $this->inputSubmit['admin_type'];
    }

    if (isset($this->inputSubmit['admin_status'])) {
      $_arr_adminData['admin_status'] = $this->inputSubmit['admin_status'];
    }

    if (isset($this->inputSubmit['admin_note'])) {
      $_arr_adminData['admin_note'] = $this->inputSubmit['admin_note'];
    }

    if (isset($this->inputSubmit['admin_nick'])) {
      $_arr_adminData['admin_nick'] = $this->inputSubmit['admin_nick'];
    }

    if (isset($this->inputSubmit['admin_allow'])) {
      $_arr_adminData['admin_allow'] = $this->inputSubmit['admin_allow'];
    }

    if (isset($this->inputSubmit['admin_allow_profile'])) {
      $_arr_adminData['admin_allow_profile'] = $this->inputSubmit['admin_allow_profile'];
    }

    $_mix_vld = $this->validate($_arr_adminData, '', 'submit_db');

    if ($_mix_vld !== true) {
      return array(
        'admin_id'  => $this->inputSubmit['admin_id'],
        'rcode'     => 'x020201',
        'msg'       => end($_mix_vld),
      );
    }

    if (isset($this->inputSubmit['admin_allow'])) {
      $_arr_adminData['admin_allow'] = Arrays::toJson($_arr_adminData['admin_allow']);
    }

    if (isset($this->inputSubmit['admin_allow_profile'])) {
      $_arr_adminData['admin_allow_profile']    = Arrays::toJson($_arr_adminData['admin_allow_profile']);
    }

    if ($_arr_adminRow['rcode'] == 'x020102') {
      $_num_adminId   = $this->insert($_arr_adminData);

      if ($_num_adminId > 0) {
        $_str_rcode = 'y020101'; //插入成功
        $_str_msg   = 'Add administrator successfully';
      } else {
        $_str_rcode = 'x020101'; //插入失败
        $_str_msg   = 'Add administrator failed';
      }
    } else {
      $_num_adminId   = $this->inputSubmit['admin_id'];

      unset($_arr_adminData['admin_id'], $_arr_adminData['admin_name']);

      $_num_count     = $this->where('admin_id', '=', $_num_adminId)->update($_arr_adminData); //更新数

      if ($_num_count > 0) {
        $_str_rcode = 'y020103'; //更新成功
        $_str_msg   = 'Update administrator successfully';
      } else {
        $_str_rcode = 'x020103'; //更新成功
        $_str_msg   = 'Did not make any changes';
      }
    }

    return array(
      'admin_id'   => $_num_adminId,
      'rcode'      => $_str_rcode,
      'msg'        => $_str_msg,
    );
  }
}
