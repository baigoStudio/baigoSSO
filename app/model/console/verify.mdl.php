<?php
/*-----------------------------------------------------------------
！！！！警告！！！！
以下为系统文件，请勿修改
-----------------------------------------------------------------*/
namespace app\model\console;

use app\model\common\Verify as Verify_Common;
use ginkgo\Func;
use ginkgo\Arrays;

// 不能非法包含或直接执行
if (!defined('IN_GINKGO')) {
  return 'Access denied';
}

/*-------------验证模型-------------*/
class Verify extends Verify_Common {

  public $inputStatus = array();
  public $inputDelete = array();

  /** 更改状态
   * status function.
   *
   * @access public
   * @param mixed $str_status
   * @return void
   */
  public function status() {
    $_arr_verifyUpdate = array(
      'verify_status' => $this->inputStatus['act'],
    );

    $_num_count     = $this->where('verify_id', 'IN', $this->inputStatus['verify_ids'], 'verify_ids')->update($_arr_verifyUpdate); //更新数据

    //如影响行数大于0则返回成功
    if ($_num_count > 0) {
      $_str_rcode = 'y120103'; //成功
      $_str_msg   = 'Successfully updated {:count} tokens';
    } else {
      $_str_rcode = 'x120103'; //失败
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
    $_num_count     = $this->where('verify_id', 'IN', $this->inputDelete['verify_ids'], 'verify_ids')->delete();

    //如车影响行数小于0则返回错误
    if ($_num_count > 0) {
      $_str_rcode = 'y120104'; //成功
      $_str_msg   = 'Successfully deleted {:count} tokens';
    } else {
      $_str_rcode = 'x120104'; //失败
      $_str_msg   = 'No token have been deleted';
    }

    return array(
      'count' => $_num_count,
      'rcode' => $_str_rcode,
      'msg'   => $_str_msg,
    );
  }


  /** 选择 verify
   * inputStatus function.
   *
   * @access public
   * @return void
   */
  public function inputStatus() {
    $_arr_inputParam = array(
      'verify_ids'    => array('arr', array()),
      'act'           => array('txt', ''),
      '__token__'     => array('txt', ''),
    );

    $_arr_inputStatus = $this->obj_request->post($_arr_inputParam);

    //print_r($_arr_inputStatus);

    $_arr_inputStatus['verify_ids'] = Arrays::unique($_arr_inputStatus['verify_ids']);

    $_mix_vld = $this->validate($_arr_inputStatus, '', 'status');

    //print_r($_mix_vld);

    if ($_mix_vld !== true) {
      return array(
        'rcode' => 'x120201',
        'msg'   => end($_mix_vld),
      );
    }

    $_arr_inputStatus['rcode'] = 'y120201';

    $this->inputStatus = $_arr_inputStatus;

    return $_arr_inputStatus;
  }


  public function inputDelete() {
    $_arr_inputParam = array(
      'verify_ids'    => array('arr', array()),
      '__token__'     => array('txt', ''),
    );

    $_arr_inputDelete = $this->obj_request->post($_arr_inputParam);

    //print_r($_arr_inputDelete);

    $_arr_inputDelete['verify_ids'] = Arrays::unique($_arr_inputDelete['verify_ids']);

    $_mix_vld = $this->validate($_arr_inputDelete, '', 'delete');

    if ($_mix_vld !== true) {
      return array(
        'rcode' => 'x120201',
        'msg'   => end($_mix_vld),
      );
    }

    $_arr_inputDelete['rcode'] = 'y120201';

    $this->inputDelete = $_arr_inputDelete;

    return $_arr_inputDelete;
  }
}
