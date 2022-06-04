<?php
/*-----------------------------------------------------------------
！！！！警告！！！！
以下为系统文件，请勿修改
-----------------------------------------------------------------*/

namespace app\model\install;

use app\classes\install\Model;
use ginkgo\Func;

// 不能非法包含或直接执行
if (!defined('IN_GINKGO')) {
  return 'Access denied';
}

/*-------------应用归属-------------*/
class App_Belong extends Model {

  protected $pk = 'belong_id';
  protected $comment = '应用从属';

  protected function m_init() { //构造函数
    $this->create = array(
      'belong_id' => array(
        'type'      => 'int(11)',
        'not_null'  => true,
        'ai'        => true,
        'comment'   => 'ID',
      ),
      'belong_app_id' => array(
        'type'      => 'smallint(6)',
        'not_null'  => true,
        'default'   => 0,
        'comment'   => '应用ID',
      ),
      'belong_user_id' => array(
        'type'      => 'int(11)',
        'not_null'  => true,
        'default'   => 0,
        'comment'   => '用户ID',
      ),
    );
  }


  /** 创建表
   * mdl_create function.
   *
   * @access public
   * @return void
   */
  public function createTable() {
    $_num_count  = $this->create();

    if ($_num_count !== false) {
      $_str_rcode = 'y070105'; //更新成功
      $_str_msg   = 'Create table successfully';
    } else {
      $_str_rcode = 'x070105'; //更新成功
      $_str_msg   = 'Create table failed';
    }

    return array(
      'rcode' => $_str_rcode, //更新成功
      'msg'   => $_str_msg,
    );
  }


  /** 修改表
   * alterTable function.
   *
   * @access public
   * @return void
   */
  public function alterTable() {
    $_str_rcode = 'y070111';
    $_str_msg   = 'No need to update table';

    $_num_count  = $this->alter();

    if ($_num_count === false) {
      $_str_rcode = 'x070106';
      $_str_msg   = 'Update table failed';
    } else if ($_num_count > 0) {
      $_str_rcode = 'y070106';
      $_str_msg   = 'Update table successfully';
    }

    return array(
      'rcode' => $_str_rcode,
      'msg'   => $_str_msg,
    );
  }
}
