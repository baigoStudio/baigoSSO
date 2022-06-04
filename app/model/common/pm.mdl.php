<?php
/*-----------------------------------------------------------------
！！！！警告！！！！
以下为系统文件，请勿修改
-----------------------------------------------------------------*/
namespace app\model\common;

use app\model\Pm as Pm_Base;
use ginkgo\Func;

// 不能非法包含或直接执行
if (!defined('IN_GINKGO')) {
  return 'Access denied';
}

/*-------------短消息模型-------------*/
class Pm extends Pm_Base {

  public $inputDelete = array();
  public $inputStatus = array();

  /** 删除
   * delete function.
   *
   * @access public
   * @return void
   */
  public function delete() {
    $_arr_where[] = array('pm_id', 'IN', $this->inputDelete['pm_ids'], 'pm_ids');

    if (isset($this->inputDelete['user_id']) && $this->inputDelete['user_id'] > 0) {
      $_arr_where[] = array('pm_from|pm_to', '=', $this->inputDelete['user_id'], 'user_id');
    }

    if (isset($this->inputDelete['pm_from']) && $this->inputDelete['pm_from'] > 0) {
      $_arr_where[] = array('pm_from', '=', $this->inputDelete['pm_from']);
    }

    if (isset($this->inputDelete['pm_to']) && $this->inputDelete['pm_to'] > 0) {
      $_arr_where[] = array('pm_to', '=', $this->inputDelete['pm_to']);
    }

    if (isset($this->inputDelete['pm_type']) && Func::notEmpty($this->inputDelete['pm_type'])) {
      $_arr_where[] = array('pm_type', '=', $this->inputDelete['pm_type']);
    }

    if (isset($this->inputDelete['pm_status']) && Func::notEmpty($this->inputDelete['pm_status'])) {
      $_arr_where[] = array('pm_status', '=', $this->inputDelete['pm_status']);
    }

    $_num_count = $this->where($_arr_where)->delete(); //更新数据

    //如车影响行数小于0则返回错误
    if ($_num_count > 0) {
      $_str_rcode = 'y110104'; //成功

      if (isset($this->inputDelete['pm_from']) && $this->inputDelete['pm_from'] > 0) {
        $_str_msg   = 'Successfully revoked {:count} messages';
      } else {
        $_str_msg   = 'Successfully deleted {:count} messages';
      }
    } else {
      $_str_rcode = 'x110104'; //失败

      if (isset($this->inputDelete['pm_from']) && $this->inputDelete['pm_from'] > 0) {
        $_str_msg   = 'No message have been revoked';
      } else {
        $_str_msg   = 'No message have been deleted';
      }
    }

    return array(
      'count' => $_num_count,
      'rcode' => $_str_rcode,
      'msg'   => $_str_msg,
    );
  }


  /** 编辑状态
   * status function.
   *
   * @access public
   * @param mixed $str_status
   * @return void
   */
  public function status() {
    $_arr_pmUpdate = array(
      'pm_status' => $this->inputStatus['act'],
    );

    $_arr_where[] = array('pm_id', 'IN', $this->inputStatus['pm_ids'], 'pm_ids');

    if (isset($this->inputStatus['pm_to']) && $this->inputStatus['pm_to'] > 0) {
      $_arr_where[] = array('pm_to', '=', $this->inputStatus['pm_to']);
    }

    if (isset($this->inputStatus['pm_type']) && Func::notEmpty($this->inputStatus['pm_type'])) {
      $_arr_where[] = array('pm_type', '=', $this->inputStatus['pm_type']);
    }

    $_num_count   = $this->where($_arr_where)->update($_arr_pmUpdate); //更新数据

    //如影响行数大于0则返回成功
    if ($_num_count > 0) {
      $_str_rcode = 'y110103'; //成功
      $_str_msg   = 'Successfully updated {:count} messages';
    } else {
      $_str_rcode = 'x110103'; //失败
      $_str_msg   = 'Did not make any changes';
    }

    return array(
      'count' => $_num_count,
      'rcode' => $_str_rcode,
      'msg'   => $_str_msg,
    );
  }
}
