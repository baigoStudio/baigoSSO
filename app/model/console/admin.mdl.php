<?php
/*-----------------------------------------------------------------
！！！！警告！！！！
以下为系统文件，请勿修改
-----------------------------------------------------------------*/

namespace app\model\console;

use app\model\common\Admin as Admin_Common;
use ginkgo\Func;
use ginkgo\Arrays;

// 不能非法包含或直接执行
if (!defined('IN_GINKGO')) {
  return 'Access denied';
}

/*-------------管理员模型-------------*/
class Admin extends Admin_Common {

  public $inputStatus = array();
  public $inputDelete = array();


  /** 编辑状态
   * status function.
   *
   * @access public
   * @param mixed $str_status
   * @return void
   */
  public function status() {
    $_arr_adminUpdate = array(
      'admin_status' => $this->inputStatus['act'],
    );

    $_num_count     = $this->where('admin_id', 'IN', $this->inputStatus['admin_ids'], 'admin_ids')->update($_arr_adminUpdate); //更新数据

    //如影响行数大于0则返回成功
    if ($_num_count > 0) {
      $_str_rcode = 'y020103'; //成功
      $_str_msg   = 'Successfully updated {:count} administrators';
    } else {
      $_str_rcode = 'x020103'; //失败
      $_str_msg   = 'Did not make any changes';
    }

    return array(
      'count' => $_num_count,
      'rcode' => $_str_rcode,
      'msg'   => $_str_msg,
    );
  }


  /** 删除
   * delete function.
   *
   * @access public
   * @return void
   */
  public function delete() {
    $_num_count     = $this->where('admin_id', 'IN', $this->inputDelete['admin_ids'], 'admin_ids')->delete(); //更新数据

    //如车影响行数小于0则返回错误
    if ($_num_count > 0) {
      $_str_rcode = 'y020104'; //成功
      $_str_msg   = 'Successfully deleted {:count} administrators';
    } else {
      $_str_rcode = 'x020104'; //失败
      $_str_msg   = 'No administrator have been deleted';
    }

    return array(
      'count' => $_num_count,
      'rcode' => $_str_rcode,
      'msg'   => $_str_msg,
    );
  }


  /** 创建、编辑表单验证
   * inputSubmit function.
   *
   * @access public
   * @return void
   */
  public function inputSubmit() {
    $_arr_inputParam = array(
      'admin_id'              => array('int', 0),
      'admin_name'            => array('txt', ''),
      'admin_pass'            => array('txt', ''),
      'admin_note'            => array('txt', ''),
      'admin_status'          => array('txt', ''),
      'admin_type'            => array('txt', ''),
      'admin_nick'            => array('txt', ''),
      'admin_allow'           => array('arr', array()),
      'admin_allow_profile'   => array('arr', array()),
      '__token__'             => array('txt', ''),
    );

    $_arr_inputSubmit = $this->obj_request->post($_arr_inputParam);

    $_arr_remove = array();

    if ($_arr_inputSubmit['admin_id'] > 0) {
      $_arr_remove = array('admin_name', 'admin_pass');
    }

    $_mix_vld = $this->validate($_arr_inputSubmit, '', 'submit', '', $_arr_remove);

    if ($_mix_vld !== true) {
      return array(
        'rcode' => 'x020201',
        'msg'   => end($_mix_vld),
      );
    }

    if ($_arr_inputSubmit['admin_id'] > 0) {
      $_arr_adminRow = $this->check($_arr_inputSubmit['admin_id']);

      if ($_arr_adminRow['rcode'] != 'y020102') {
        return $_arr_adminRow;
      }
    }

    $_arr_inputSubmit['rcode'] = 'y020201';

    $this->inputSubmit = $_arr_inputSubmit;

    return $_arr_inputSubmit;
  }


  /** 选择管理员
   * inputStatus function.
   *
   * @access public
   * @return void
   */
  public function inputStatus() {
    $_arr_inputParam = array(
      'admin_ids' => array('arr', array()),
      'act'       => array('txt', ''),
      '__token__' => array('txt', ''),
    );

    $_arr_inputStatus = $this->obj_request->post($_arr_inputParam);

    $_arr_inputStatus['admin_ids'] = Arrays::unique($_arr_inputStatus['admin_ids']);

    $_mix_vld = $this->validate($_arr_inputStatus, '', 'status');

    if ($_mix_vld !== true) {
      return array(
        'rcode' => 'x020201',
        'msg'   => end($_mix_vld),
      );
    }

    $_arr_inputStatus['rcode'] = 'y020201';

    $this->inputStatus = $_arr_inputStatus;

    return $_arr_inputStatus;
  }


  public function inputDelete() {
    $_arr_inputParam = array(
      'admin_ids' => array('arr', array()),
      '__token__' => array('txt', ''),
    );

    $_arr_inputDelete = $this->obj_request->post($_arr_inputParam);

    $_arr_inputDelete['admin_ids'] = Arrays::unique($_arr_inputDelete['admin_ids']);

    $_mix_vld = $this->validate($_arr_inputDelete, '', 'delete');

    if ($_mix_vld !== true) {
      return array(
        'rcode' => 'x020201',
        'msg'   => end($_mix_vld),
      );
    }

    $_arr_inputDelete['rcode'] = 'y020201';

    $this->inputDelete = $_arr_inputDelete;

    return $_arr_inputDelete;
  }
}
