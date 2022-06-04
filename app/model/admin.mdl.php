<?php
/*-----------------------------------------------------------------
！！！！警告！！！！
以下为系统文件，请勿修改
-----------------------------------------------------------------*/

namespace app\model;

use app\classes\Model;
use ginkgo\Arrays;
use ginkgo\Func;

// 不能非法包含或直接执行
if (!defined('IN_GINKGO')) {
  return 'Access denied';
}

/*-------------管理员模型-------------*/
class Admin extends Model {

  public $arr_status  = array('enable', 'disabled'); //状态
  public $arr_type    = array('normal', 'super'); //类型

  public function check($mix_admin, $str_by = 'admin_id', $num_notId = 0) {
    $_arr_select = array(
      'admin_id',
    );

    return $this->readProcess($mix_admin, $str_by, $num_notId, $_arr_select);
  }


  /** 读取
   * read function.
   *
   * @access public
   * @param mixed $str_admin
   * @param string $str_by (default: 'admin_id')
   * @param int $num_notId (default: 0)
   * @return void
   */
  public function read($mix_admin, $str_by = 'admin_id', $num_notId = 0, $arr_select = array()) {
    $_arr_adminRow = $this->readProcess($mix_admin, $str_by, $num_notId, $arr_select);

    return $this->rowProcess($_arr_adminRow);
  }


  public function readProcess($mix_admin, $str_by = 'admin_id', $num_notId = 0, $arr_select = array()) {
    if (Func::isEmpty($arr_select)) {
      $arr_select = array(
        'admin_id',
        'admin_name',
        'admin_note',
        'admin_nick',
        'admin_allow',
        'admin_allow_profile',
        'admin_status',
        'admin_type',
        'admin_time',
        'admin_time_login',
        'admin_ip',
        'admin_shortcut',
      );
    }

    $_arr_where = $this->readQueryProcess($mix_admin, $str_by, $num_notId);

    $_arr_adminRow = $this->where($_arr_where)->find($arr_select);

    if ($_arr_adminRow === false) {
      $_arr_adminRow          = $this->obj_request->fillParam(array(), $arr_select);
      $_arr_adminRow['msg']   = 'Administrator not found';
      $_arr_adminRow['rcode'] = 'x020102';
    } else {
      $_arr_adminRow['rcode'] = 'y020102';
      $_arr_adminRow['msg']   = '';
    }

    return $_arr_adminRow;
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
    $_arr_adminSelect = array(
      'admin_id',
      'admin_name',
      'admin_note',
      'admin_nick',
      'admin_status',
      'admin_type',
    );

    $_arr_where      = $this->queryProcess($arr_search);
    $_arr_pagination = $this->paginationProcess($pagination);
    $_arr_adminRows  = $this->where($_arr_where)->order('admin_id', 'DESC')->limit($_arr_pagination['limit'], $_arr_pagination['length'])->paginate($_arr_pagination['perpage'], $_arr_pagination['current'])->select($_arr_adminSelect);

    return $_arr_adminRows;
  }



  /** 计数
   * mdl_count function.
   *
   * @access public
   * @param array $arr_search (default: array())
   * @return void
   */
  public function counts($arr_search = array()) {
    $_arr_where = $this->queryProcess($arr_search);

    $_num_adminCount = $this->where($_arr_where)->count();

    return $_num_adminCount;
  }


  /** 列出及统计 SQL 处理
   * queryProcess function.
   *
   * @access private
   * @param array $arr_search (default: array())
   * @return void
   */
  protected function queryProcess($arr_search = array()) {
    $_arr_where = array();

    if (isset($arr_search['key']) && Func::notEmpty($arr_search['key'])) {
      $_arr_where[] = array('admin_name|admin_note|admin_nick', 'LIKE', '%' . $arr_search['key'] . '%', 'key');
    }

    if (isset($arr_search['status']) && Func::notEmpty($arr_search['status'])) {
      $_arr_where[] = array('admin_status', '=', $arr_search['status']);
    }

    if (isset($arr_search['type']) && Func::notEmpty($arr_search['type'])) {
      $_arr_where[] = array('admin_type', '=', $arr_search['type']);
    }

    return $_arr_where;
  }


  protected function readQueryProcess($mix_admin, $str_by = 'admin_id', $num_notId = 0) {
    $_arr_where[] = array($str_by, '=', $mix_admin);

    if ($num_notId > 0) {
      $_arr_where[] = array('admin_id', '<>', $num_notId);
    }

    return $_arr_where;
  }


  protected function rowProcess($arr_adminRow = array()) {
    if (isset($arr_adminRow['admin_allow'])) {
      $arr_adminRow['admin_allow'] = Arrays::fromJson($arr_adminRow['admin_allow']); //json 解码
    } else {
      $arr_adminRow['admin_allow'] = array();
    }

    if (isset($arr_adminRow['admin_allow_profile'])) {
      $arr_adminRow['admin_allow_profile'] = Arrays::fromJson($arr_adminRow['admin_allow_profile']); //json 解码
    } else {
      $arr_adminRow['admin_allow_profile'] = array();
    }

    if (isset($arr_adminRow['admin_shortcut'])) {
      $arr_adminRow['admin_shortcut'] = Arrays::fromJson($arr_adminRow['admin_shortcut']); //json 解码
    } else {
      $arr_adminRow['admin_shortcut'] = array();
    }

    return $arr_adminRow;
  }
}
