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

/*-------------应用归属-------------*/
class Combine_Belong extends Model {

  /** 读取
   * read function.
   *
   * @access public
   * @param int $num_combineId (default: 0)
   * @param int $num_appId (default: 0)
   * @return void
   */
  public function read($num_combineId = 0, $num_appId = 0) {
    $_arr_belongSelect = array(
      'belong_id',
      'belong_combine_id',
      'belong_app_id',
    );

    $_arr_where = $this->readQueryProcess($num_combineId, $num_appId);

    $_arr_belongRow = $this->where($_arr_where)->find($_arr_belongSelect);

    if ($_arr_belongRow === false) {
      $_arr_belongRow          = $this->obj_request->fillParam(array(), $arr_select);
      $_arr_belongRow['msg']   = 'Data not found';
      $_arr_belongRow['rcode'] = 'x060102';
    } else {
      $_arr_belongRow['msg']   = '';
      $_arr_belongRow['rcode'] = 'y060102';
    }

    return $_arr_belongRow;
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
    $_arr_belongSelect = array(
      'belong_id',
      'belong_app_id',
      'belong_combine_id',
    );

    $_arr_query = $this->queryProcess($arr_search);
    $_arr_pagination = $this->paginationProcess($pagination);
    $_arr_belongRows = $this->where($_arr_query)->order('belong_id', 'DESC')->limit($_arr_pagination['limit'], $_arr_pagination['length'])->paginate($_arr_pagination['perpage'], $_arr_pagination['current'])->select($_arr_belongSelect);

    return $_arr_belongRows;
  }


  /** 计数
   * mdl_count function.
   *
   * @access public
   * @param array $arr_search (default: array())
   * @return void
   */
  public function counts($arr_search = array()) {
    $_arr_query = $this->queryProcess($arr_search);

    $_num_belongCount = $this->where($_arr_query)->count();

    return $_num_belongCount;
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

    if (isset($arr_search['combine_id']) && $arr_search['combine_id'] > 0) {
      $_arr_where[] = array('belong_combine_id', '=', $arr_search['combine_id']);
    }

    if (isset($arr_search['app_id']) && $arr_search['app_id'] > 0) {
      $_arr_where[] = array('belong_app_id', '=', $arr_search['app_id']);
    }

    if (isset($arr_search['app_ids']) && Func::notEmpty($arr_search['app_ids'])) {
      $arr_search['app_ids'] = Arrays::unique($arr_search['app_ids'], 'app_ids');

      if (Func::notEmpty($arr_search['app_ids'])) {
        $_arr_where[] = array('belong_app_id', 'IN', $arr_search['app_ids'], 'app_ids');
      }
    }

    if (isset($arr_search['min_id']) && $arr_search['min_id'] > 0) {
      $_arr_where[] = array('belong_id', ' >', $arr_search['min_id'], 'min_id');
    }

    if (isset($arr_search['max_id']) && $arr_search['max_id'] > 0) {
      $_arr_where[] = array('belong_id', '<', $arr_search['max_id'], 'max_id');
    }

    return $_arr_where;
  }


  protected function readQueryProcess($num_combineId = 0, $num_appId = 0) {
    $_arr_where[] = array('belong_combine_id', '=', $num_combineId);

    if ($num_appId > 0) {
      $_arr_where[] = array('belong_app_id', '=', $num_appId);
    }

    return $_arr_where;
  }
}
