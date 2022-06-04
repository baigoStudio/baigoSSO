<?php
/*-----------------------------------------------------------------
！！！！警告！！！！
以下为系统文件，请勿修改
-----------------------------------------------------------------*/
namespace app\model;

use app\classes\Model;
use ginkgo\Func;

// 不能非法包含或直接执行
if (!defined('IN_GINKGO')) {
  return 'Access denied';
}

/*-------------验证模型-------------*/
class Verify extends Model {

  public $arr_status  = array('enable', 'disabled');
  public $arr_type    = array('mailbox', 'confirm', 'forgot');

  public function check($mix_verify, $str_by = 'verify_id') {
    $_arr_select = array(
      'verify_id',
    );

    return $this->readProcess($mix_verify, $str_by, $_arr_select);
  }


  public function read($mix_verify, $str_by = 'verify_id', $arr_select = array()) {
    $_arr_verifyRow = $this->readProcess($mix_verify, $str_by, $arr_select);

    if ($_arr_verifyRow['rcode'] != 'y120102') {
      return $_arr_verifyRow;
    }

    return $this->rowProcess($_arr_verifyRow);
  }


  public function readProcess($mix_verify, $str_by = 'verify_id', $arr_select = array()) {
    if (Func::isEmpty($arr_select)) {
      $arr_select = array(
        'verify_id',
        'verify_user_id',
        'verify_token',
        'verify_token_expire',
        'verify_mail',
        'verify_status',
        'verify_type',
        'verify_rand',
        'verify_time',
        'verify_time_refresh',
        'verify_time_disabled',
      );
    }

    $_arr_where = $this->readQueryProcess($mix_verify, $str_by);

    $_arr_verifyRow = $this->where($_arr_where)->find($arr_select);

    if ($_arr_verifyRow === false) {
      $_arr_verifyRow          = $this->obj_request->fillParam(array(), $arr_select);
      $_arr_verifyRow['msg']   = 'Token not found';
      $_arr_verifyRow['rcode'] = 'x120102';
    } else {
      $_arr_verifyRow['rcode'] = 'y120102';
      $_arr_verifyRow['msg']   = '';
    }

    return $_arr_verifyRow;
  }


  /** 列出
   * mdl_list function.
   *
   * @access public
   * @param mixed $num_no
   * @param int $num_offset (default: 0)
   * @return void
   */
  public function lists($pagination = 0) {
    $_arr_verifySelect = array(
      'verify_id',
      'verify_user_id',
      'verify_token',
      'verify_token_expire',
      'verify_mail',
      'verify_status',
      'verify_type',
      'verify_time',
      'verify_time_refresh',
      'verify_time_disabled',
    );

    $_arr_pagination = $this->paginationProcess($pagination);
    $_arr_getData    = $this->order('verify_id', 'DESC')->limit($_arr_pagination['limit'], $_arr_pagination['length'])->paginate($_arr_pagination['perpage'], $_arr_pagination['current'])->select($_arr_verifySelect);

    if (isset($_arr_getData['dataRows'])) {
      $_arr_eachData = &$_arr_getData['dataRows'];
    } else {
      $_arr_eachData = &$_arr_getData;
    }

    if (Func::notEmpty($_arr_eachData)) {
      foreach ($_arr_eachData as $_key=>&$_value) {
        $_value = $this->rowProcess($_value);
      }
    }

    return $_arr_getData;
  }


  /** 计数
   * mdl_count function.
   *
   * @access public
   * @return void
   */
  public function counts() {
    $_num_verifyCount = $this->where(false)->count();

    return $_num_verifyCount;
  }


  protected function readQueryProcess($mix_verify, $str_by = 'verify_id') {
    $_arr_where[] = array($str_by, '=', $mix_verify);

    return $_arr_where;
  }


  protected function rowProcess($arr_verifyRow = array()) {
    if (isset($arr_verifyRow['verify_token_expire'])) {
      if ($arr_verifyRow['verify_token_expire'] < GK_NOW) {
        $arr_verifyRow['verify_status'] = 'expired';
      }
    }

    if (!isset($arr_verifyRow['verify_time_refresh'])) {
      $arr_verifyRow['verify_time_refresh'] = GK_NOW;
    }

    if (!isset($arr_verifyRow['verify_token_expire'])) {
      $arr_verifyRow['verify_token_expire'] = GK_NOW;
    }

    if (!isset($arr_verifyRow['verify_time_disabled'])) {
      $arr_verifyRow['verify_time_disabled'] = GK_NOW;
    }

    $arr_verifyRow['verify_time_refresh_format']       = $this->dateFormat($arr_verifyRow['verify_time_refresh']);
    $arr_verifyRow['verify_time_expire_format']        = $this->dateFormat($arr_verifyRow['verify_token_expire']);
    $arr_verifyRow['verify_time_disabled_format']      = $this->dateFormat($arr_verifyRow['verify_time_disabled']);

    return $arr_verifyRow;
  }
}
