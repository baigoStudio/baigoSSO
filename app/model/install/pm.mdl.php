<?php
/*-----------------------------------------------------------------
！！！！警告！！！！
以下为系统文件，请勿修改
-----------------------------------------------------------------*/

namespace app\model\install;

use app\classes\install\Model;
use ginkgo\Loader;
use ginkgo\Func;

// 不能非法包含或直接执行
if (!defined('IN_GINKGO')) {
  return 'Access denied';
}

/*-------------短消息模型-------------*/
class Pm extends Model {

  protected $pk = 'pm_id';
  protected $comment = '短消息';

  public $arr_status = array();
  public $arr_type   = array();

  protected function m_init() { //构造函数
    $_mdl_pm = Loader::model('Pm', '', false);
    $this->arr_status = $_mdl_pm->arr_status;
    $this->arr_type   = $_mdl_pm->arr_type;

    $_str_status    = implode('\',\'', $this->arr_status);
    $_str_type      = implode('\',\'', $this->arr_type);

    $this->create = array(
      'pm_id' => array(
        'type'      => 'int(11)',
        'not_null'  => true,
        'ai'        => true,
        'comment'   => 'ID',
      ),
      'pm_send_id' => array(
        'type'      => 'int(11)',
        'not_null'  => true,
        'default'   => 0,
        'comment'   => '发出 ID', //已发送短信的目标 ID
      ),
      'pm_to' => array(
        'type'      => 'int(11)',
        'not_null'  => true,
        'default'   => 0,
        'comment'   => '收件用户 ID',
      ),
      'pm_from' => array(
        'type'      => 'int(11)',
        'not_null'  => true,
        'default'   => 0,
        'comment'   => '发件用户 ID',
      ),
      'pm_title' => array(
        'type'      => 'varchar(90)',
        'not_null'  => true,
        'default'   => '',
        'comment'   => '标题',
      ),
      'pm_content' => array(
        'type'      => 'varchar(900)',
        'not_null'  => true,
        'default'   => '',
        'comment'   => '随机串',
      ),
      'pm_status' => array(
        'type'      => 'enum(\'' . $_str_status . '\')',
        'not_null'  => true,
        'default'   => $this->arr_status[0],
        'comment'   => '状态',
        'update'    => $this->arr_status[0],
      ),
      'pm_type' => array(
        'type'      => 'enum(\'' . $_str_type . '\')',
        'not_null'  => true,
        'default'   => $this->arr_type[0],
        'comment'   => '类型',
        'update'    => $this->arr_type[0],
      ),
      'pm_time' => array(
        'type'      => 'int(11)',
        'not_null'  => true,
        'default'   => 0,
        'comment'   => '创建时间',
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
      $_str_rcode = 'y110105'; //更新成功
      $_str_msg   = 'Create table successfully';
    } else {
      $_str_rcode = 'x110105'; //更新成功
      $_str_msg   = 'Create table failed';
    }

    return array(
      'rcode' => $_str_rcode, //更新成功
      'msg'   => $_str_msg,
    );
  }


  public function alterTable() {
    $_str_rcode = 'y110111';
    $_str_msg   = 'No need to update table';

    $_num_count  = $this->alter();

    if ($_num_count === false) {
      $_str_rcode = 'x110106';
      $_str_msg   = 'Update table failed';
    } else if ($_num_count > 0) {
      $_str_rcode = 'y110106';
      $_str_msg   = 'Update table successfully';
    }

    return array(
      'rcode' => $_str_rcode,
      'msg'   => $_str_msg,
    );
  }
}
