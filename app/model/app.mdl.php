<?php
/*-----------------------------------------------------------------
！！！！警告！！！！
以下为系统文件，请勿修改
-----------------------------------------------------------------*/

namespace app\model;

use app\classes\Model;
use ginkgo\Func;
use ginkgo\Html;
use ginkgo\Arrays;

// 不能非法包含或直接执行
if (!defined('IN_GINKGO')) {
  return 'Access denied';
}

/*-------------应用模型-------------*/
class App extends Model {

  public $arr_status  = array('enable', 'disabled'); //状态
  public $arr_sync    = array('on', 'off'); //是否同步

  public function check($mix_app, $str_by = 'app_id', $num_notId = 0) {
    $_arr_select = array(
      'app_id',
    );

    return $this->readProcess($mix_app, $str_by, $num_notId, $_arr_select);
  }


  /** 读取
   * read function.
   *
   * @access public
   * @param mixed $mix_app
   * @param string $str_by (default: 'app_id')
   * @param int $num_notId (default: 0)
   * @return void
   */
  public function read($mix_app, $str_by = 'app_id', $num_notId = 0, $arr_select = array()) {
    $_arr_appRow = $this->readProcess($mix_app, $str_by, $num_notId, $arr_select);

    return $this->rowProcess($_arr_appRow);
  }


  public function readProcess($mix_app, $str_by = 'app_id', $num_notId = 0, $arr_select = array()) {
    if (Func::isEmpty($arr_select)) {
      $arr_select = array(
        'app_id',
        'app_name',
        'app_url_notify',
        'app_url_sync',
        'app_key',
        'app_secret',
        'app_note',
        'app_status',
        'app_time',
        'app_ip_allow',
        'app_ip_bad',
        'app_sync',
        'app_allow',
        'app_param',
      );
    }

    $_arr_where = $this->readQueryProcess($mix_app, $str_by, $num_notId);

    $_arr_appRow = $this->where($_arr_where)->find($arr_select);

    if ($_arr_appRow === false) {
      $_arr_appRow          = $this->obj_request->fillParam(array(), $arr_select);
      $_arr_appRow['msg']   = 'App not found';
      $_arr_appRow['rcode'] = 'x050102';
    } else {
      $_arr_appRow['rcode'] = 'y050102';
      $_arr_appRow['msg']   = '';
    }

    return $_arr_appRow;
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
    $_arr_appSelect = array(
      'app_id',
      'app_key',
      'app_secret',
      'app_param',
      'app_name',
      'app_url_notify',
      'app_url_sync',
      'app_note',
      'app_status',
      'app_sync',
      'app_time',
    );

    $_arr_where      = $this->queryProcess($arr_search);
    $_arr_pagination = $this->paginationProcess($pagination);
    $_arr_getData    = $this->where($_arr_where)->order('app_id', 'DESC')->limit($_arr_pagination['limit'], $_arr_pagination['length'])->paginate($_arr_pagination['perpage'], $_arr_pagination['current'])->select($_arr_appSelect);

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
    $_arr_where = $this->queryProcess($arr_search);

    $_num_appCount = $this->where($_arr_where)->count();

    return $_num_appCount;
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
      $_arr_where[] = array('app_name|app_note', 'LIKE', '%' . $arr_search['key'] . '%', 'key');
    }

    if (isset($arr_search['status']) && Func::notEmpty($arr_search['status'])) {
      $_arr_where[] = array('app_status', '=', $arr_search['status']);
    }

    if (isset($arr_search['sync']) && Func::notEmpty($arr_search['sync'])) {
      $_arr_where[] = array('app_sync', '=', $arr_search['sync']);
    }

    if (isset($arr_search['has_notify'])) {
      $_arr_where[] = array('LENGTH(`app_url_notify`)', '>', 0, 'app_url_notify');
    }

    if (isset($arr_search['has_sync'])) {
      $_arr_where[] = array('LENGTH(`app_url_sync`)', '>', 0, 'app_url_sync');
    }

    if (isset($arr_search['not_ids']) && Func::notEmpty($arr_search['not_ids'])) {
      $arr_search['not_ids'] = Arrays::unique($arr_search['not_ids']);

      if (Func::notEmpty($arr_search['not_ids'])) {
        $_arr_where[] = array('app_id', 'NOT IN', $arr_search['not_ids'], 'not_ids');
      }
    }

    return $_arr_where;
  }


  protected function readQueryProcess($mix_app, $str_by = 'app_id', $num_notId = 0) {
    $_arr_where[] = array($str_by, '=', $mix_app);

    if ($num_notId > 0) {
      $_arr_where[] = array('app_id', '<>', $num_notId);
    }

    return $_arr_where;
  }


  protected function rowProcess($arr_appRow = array()) {
    if (isset($arr_appRow['app_allow'])) {
      $arr_appRow['app_allow'] = Arrays::fromJson($arr_appRow['app_allow']);
    } else {
      $arr_appRow['app_allow'] = array();
    }

    if (isset($arr_appRow['app_param'])) {
      $arr_appRow['app_param'] = Arrays::fromJson($arr_appRow['app_param']);
    } else {
      $arr_appRow['app_param'] = array();
    }

    if (!isset($arr_appRow['app_url_sync'])) {
      if (isset($arr_appRow['app_url_notify'])) {
        $arr_appRow['app_url_sync'] = $arr_appRow['app_url_notify'];
      } else {
        $arr_appRow['app_url_sync'] = '';
      }
    }

    if (!isset($arr_appRow['app_url_notify'])) {
      $arr_appRow['app_url_notify'] = '';
    }

    $arr_appRow['app_url_notify']  = Html::decode($arr_appRow['app_url_notify'], 'url');
    $arr_appRow['app_url_sync']    = Html::decode($arr_appRow['app_url_sync'], 'url');

    return $arr_appRow;
  }
}
