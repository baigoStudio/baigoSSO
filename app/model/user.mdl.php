<?php
/*-----------------------------------------------------------------
！！！！警告！！！！
以下为系统文件，请勿修改
-----------------------------------------------------------------*/

namespace app\model;

use app\classes\Model;
use ginkgo\Func;
use ginkgo\Config;
use ginkgo\Arrays;

// 不能非法包含或直接执行
if (!defined('IN_GINKGO')) {
  return 'Access denied';
}

/*-------------用户模型-------------*/
class User extends Model {

  public $arr_status  = array('enable', 'wait', 'disabled');

  public function check($mix_user, $str_by = 'user_id', $num_notId = 0) {
    $_arr_select = array(
      'user_id',
    );

    return $this->readProcess($mix_user, $str_by, $num_notId, $_arr_select);
  }


  public function read($mix_user, $str_by = 'user_id', $num_notId = 0, $arr_select = array()) {
    $_arr_userRow = $this->readProcess($mix_user, $str_by, $num_notId, $arr_select);

    return $this->rowProcess($_arr_userRow);
  }


  public function readProcess($mix_user, $str_by = 'user_id', $num_notId = 0, $arr_select = array()) {
    if (Func::isEmpty($arr_select)) {
      $arr_select = array(
        'user_id',
        'user_name',
        'user_pass',
        'user_rand',
        'user_mail',
        'user_contact',
        'user_extend',
        'user_nick',
        'user_note',
        'user_status',
        'user_time',
        'user_time_login',
        'user_ip',
        'user_access_token',
        'user_access_expire',
        'user_refresh_token',
        'user_refresh_expire',
        'user_app_id',
        'user_sec_ques',
        'user_sec_answ',
      );
    }

    $_arr_where = $this->readQueryProcess($mix_user, $str_by, $num_notId);

    $_arr_userRow = $this->where($_arr_where)->find($arr_select);

    if ($_arr_userRow === false) {
      $_arr_userRow          = $this->obj_request->fillParam(array(), $arr_select);
      $_arr_userRow['msg']   = 'User not found';
      $_arr_userRow['rcode'] = 'x010102';
    } else {
      $_arr_userRow['rcode'] = 'y010102';
      $_arr_userRow['msg']   = '';
    }

    return $_arr_userRow;
  }


  /** 列出
   * mdl_list function.
   *
   * @access public
   * @param mixed $num_no
   * @param int $num_offset (default: 0)
   * @param array $arr_search (default: array())
   * @return void
   */
  public function lists($pagination = 0, $arr_search = array()) {
    $_arr_userSelect = array(
      'user_id',
      'user_name',
      'user_mail',
      'user_nick',
      'user_note',
      'user_status',
      'user_time',
      'user_time_login',
      'user_ip',
    );

    $_arr_where      = $this->queryProcess($arr_search);
    $_arr_pagination = $this->paginationProcess($pagination);

    $_arr_userRows   = $this->where($_arr_where)->order('user_id', 'DESC')->limit($_arr_pagination['limit'], $_arr_pagination['length'])->paginate($_arr_pagination['perpage'], $_arr_pagination['current'])->select($_arr_userSelect);

    return $_arr_userRows;
  }


  /** 计数
   * mdl_count function.
   *
   * @access public
   * @param array $arr_search (default: array())
   * @return void
   */
  public function counts($arr_search = array()) {
    $_arr_where     = $this->queryProcess($arr_search);

    $_num_userCount = $this->where($_arr_where)->count();

    return $_num_userCount;
  }


  /** 列出及统计 SQL 处理
   * queryProcess function.
   *
   * @param array $arr_search (default: array())
   * @return void
   */
  public function queryProcess($arr_search = array()) {
    $_arr_where = array();

    if (isset($arr_search['key']) && Func::notEmpty($arr_search['key'])) {
      $_arr_where[] = array('user_name|user_mail|user_note', 'LIKE', '%' . $arr_search['key'] . '%', 'key');
    }

    if (isset($arr_search['key_name']) && Func::notEmpty($arr_search['key_name'])) {
      $_arr_where[] = array('user_name', 'LIKE', '%' . $arr_search['key_name'] . '%', 'key_name');
    }

    if (isset($arr_search['key_mail']) && Func::notEmpty($arr_search['key_mail'])) {
      $_arr_where[] = array('user_mail', 'LIKE', '%' . $arr_search['key_mail'] . '%', 'key_mail');
    }

    if (isset($arr_search['min_id']) && $arr_search['min_id'] > 0) {
      $_arr_where[] = array('user_id', '>', $arr_search['min_id'], 'min_id');
    }

    if (isset($arr_search['max_id']) && $arr_search['max_id'] > 0) {
      $_arr_where[] = array('user_id', '<', $arr_search['max_id'], 'max_id');
    }

    if (isset($arr_search['begin_time']) && $arr_search['begin_time'] > 0) {
      $_arr_where[] = array('user_time', '>', $arr_search['begin_time'], 'begin_time');
    }

    if (isset($arr_search['end_time']) && $arr_search['end_time'] > 0) {
      $_arr_where[] = array('user_time', '<', $arr_search['end_time'], 'end_time');
    }

    if (isset($arr_search['begin_login']) && $arr_search['begin_login'] > 0) {
      $_arr_where[] = array('user_time_login', '>', $arr_search['begin_login'], 'begin_login');
    }

    if (isset($arr_search['end_login']) && $arr_search['end_login'] > 0) {
      $_arr_where[] = array('user_time_login', '<', $arr_search['end_login'], 'end_login');
    }

    if (isset($arr_search['status']) && Func::notEmpty($arr_search['status'])) {
      $_arr_where[] = array('user_status', '=', $arr_search['status']);
    }

    if (isset($arr_search['user_mail']) && Func::notEmpty($arr_search['user_mail'])) {
      $_arr_where[] = array('user_mail', '=', $arr_search['user_mail']);
    }

    if (isset($arr_search['user_names']) && Func::notEmpty($arr_search['user_names'])) {
      $arr_search['user_names']    = Arrays::unique($arr_search['user_names']);

      if (Func::notEmpty($arr_search['user_names'])) {
        $_arr_where[] = array('user_name', 'IN', $arr_search['user_names'], 'user_names');
      }
    }

    if (isset($arr_search['not_in']) && Func::notEmpty($arr_search['not_in'])) {
      $_arr_where[] = array('user_id', 'NOT IN', $arr_search['not_in']);
    }

    //print_r($_arr_where);

    return $_arr_where;
  }


  protected function readQueryProcess($mix_user, $str_by = 'user_id', $num_notId = 0) {
    $_arr_where[] = array($str_by, '=', $mix_user);

    if ($num_notId > 0) {
      $_arr_where[] = array('user_id', '<>', $num_notId);
    }

    return $_arr_where;
  }


  protected function rowProcess($arr_userRow = array()) {
    if (isset($arr_userRow['user_contact'])) {
      $arr_userRow['user_contact']   = Arrays::fromJson($arr_userRow['user_contact']);
    } else {
      $arr_userRow['user_contact']   = array();
    }

    if (isset($arr_userRow['user_extend'])) {
      $arr_userRow['user_extend']    = Arrays::fromJson($arr_userRow['user_extend']);
    } else {
      $arr_userRow['user_extend']    = array();
    }

    if (isset($arr_userRow['user_sec_ques'])) {
      $arr_userRow['user_sec_ques']  = Arrays::fromJson($arr_userRow['user_sec_ques']);
    } else {
      $arr_userRow['user_sec_ques']  = array();
    }

    $_num_countSecqa = Config::get('count_secqa', 'var_default');

    for ($_iii = 1; $_iii <= $_num_countSecqa; $_iii++) {
      if (!isset($arr_userRow['user_sec_ques'][$_iii])) {
        $arr_userRow['user_sec_ques'][$_iii] = '';
      }
    }

    return $arr_userRow;
  }
}
