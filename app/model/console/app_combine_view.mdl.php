<?php
/*-----------------------------------------------------------------
！！！！警告！！！！
以下为系统文件，请勿修改
-----------------------------------------------------------------*/

namespace app\model\console;

use ginkgo\Func;

// 不能非法包含或直接执行
if (!defined('IN_GINKGO')) {
  return 'Access denied';
}

/*-------------用户模型-------------*/
class App_Combine_View extends App {

  /** 列出及统计 SQL 处理
   * queryProcess function.
   *
   * @param array $arr_search (default: array())
   * @return void
   */
  public function queryProcess($arr_search = array()) {
    $_arr_where = parent::queryProcess($arr_search);

    if (isset($arr_search['min_id']) && $arr_search['min_id'] > 0) {
      $_arr_where[] = array('app_id', '>', $arr_search['min_id'], 'min_id');
    }

    if (isset($arr_search['max_id']) && $arr_search['max_id'] > 0) {
      $_arr_where[] = array('app_id', '<', $arr_search['max_id'], 'max_id');
    }

    if (isset($arr_search['combine_id']) && $arr_search['combine_id'] > 0) {
      $_arr_where[] = array('belong_combine_id', '=', $arr_search['combine_id']);
    }

    return $_arr_where;
  }
}
