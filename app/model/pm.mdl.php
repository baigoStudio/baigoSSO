<?php
/*-----------------------------------------------------------------
！！！！警告！！！！
以下为系统文件，请勿修改
-----------------------------------------------------------------*/
namespace app\model;

use app\classes\Model;
use ginkgo\Func;
use ginkgo\Arrays;

// 不能非法包含或直接执行
if (!defined('IN_GINKGO')) {
  return 'Access denied';
}

/*-------------短消息模型-------------*/
class Pm extends Model {

  public $arr_status  = array('wait', 'read'); //状态
  public $arr_type    = array('in', 'out'); //类型

  /** 读取
   * read function.
   *
   * @access public
   * @param mixed $mix_pm
   * @param string $str_by (default: 'pm_id')
   * @param int $num_notId (default: 0)
   * @return void
   */
  public function read($mix_pm, $str_by = 'pm_id', $num_notId = 0) {
    $_arr_pmRow = $this->readProcess($mix_pm, $str_by, $num_notId);

    return $this->rowProcess($_arr_pmRow);
  }


  public function readProcess($mix_pm, $str_by = 'pm_id', $num_notId = 0) {
    $_arr_pmSelect = array(
      'pm_id',
      'pm_send_id',
      'pm_to',
      'pm_from',
      'pm_title',
      'pm_content',
      'pm_type',
      'pm_status',
      'pm_time',
    );

    $_arr_where = $this->readQueryProcess($mix_pm, $str_by, $num_notId);

    $_arr_pmRow = $this->where($_arr_where)->find($_arr_pmSelect);

    if ($_arr_pmRow === false) {
      $_arr_pmRow          = $this->obj_request->fillParam(array(), $arr_select);
      $_arr_pmRow['msg']   = 'Message not found';
      $_arr_pmRow['rcode'] = 'x110102';
    } else {
      $_arr_pmRow['rcode'] = 'y110102';
      $_arr_pmRow['msg']   = '';
    }

    return $_arr_pmRow;
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
    $_arr_pmSelect = array(
      'pm_id',
      'pm_send_id',
      'pm_to',
      'pm_from',
      'pm_title',
      'pm_type',
      'pm_status',
      'pm_time',
    );

    $_arr_where      = $this->queryProcess($arr_search);
    $_arr_pagination = $this->paginationProcess($pagination);
    $_arr_getData    = $this->where($_arr_where)->order('pm_id', 'DESC')->limit($_arr_pagination['limit'], $_arr_pagination['length'])->paginate($_arr_pagination['perpage'], $_arr_pagination['current'])->select($_arr_pmSelect);

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
   * @param array $arr_search (default: array())
   * @return void
   */
  public function counts($arr_search = array()) {
    $_arr_where     = $this->queryProcess($arr_search);

    $_num_pmCount = $this->where($_arr_where)->count();

    return $_num_pmCount;
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
      $_arr_where[] = array('pm_title|pm_content', 'LIKE', '%' . $arr_search['key'] . '%', 'key');
    }

    if (isset($arr_search['status']) && Func::notEmpty($arr_search['status'])) {
      $_arr_where[] = array('pm_status', '=', $arr_search['status']);
    }

    if (isset($arr_search['type']) && Func::notEmpty($arr_search['type'])) {
      $_arr_where[] = array('pm_type', '=', $arr_search['type']);
    }

    if (isset($arr_search['from']) && $arr_search['from'] > 0) {
      $_arr_where[] = array('pm_from', '=', $arr_search['from']);
    }

    if (isset($arr_search['to']) && $arr_search['to'] > 0) {
      $_arr_where[] = array('pm_to', '=', $arr_search['to']);
    }

    if (isset($arr_search['ids']) && Func::notEmpty($arr_search['ids'])) {
      $arr_search['ids'] = Arrays::unique($arr_search['ids']);

      if (Func::notEmpty($arr_search['ids'])) {
        $_arr_where[] = array('pm_id', 'IN', $arr_search['ids'], 'ids');
      }
    }

    return $_arr_where;
  }


  protected function readQueryProcess($mix_pm, $str_by = 'pm_id', $num_notId = 0) {
    $_arr_where[] = array($str_by, '=', $mix_pm);

    if ($num_notId > 0) {
      $_arr_where[] = array('pm_id', '<>', $num_notId);
    }

    return $_arr_where;
  }


  protected function rowProcess($arr_pmRow = array()) {
    if (!isset($arr_pmRow['pm_time'])) {
      $arr_pmRow['pm_time'] = GK_NOW;
    }

    $arr_pmRow['pm_time_format'] = $this->dateFormat($arr_pmRow['pm_time']);

    return $arr_pmRow;
  }
}
