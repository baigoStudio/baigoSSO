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

/*-------------应用模型-------------*/
class App extends Model {

  protected $pk = 'app_id';
  protected $comment = '应用';

  public $arr_status = array();
  public $arr_sync   = array();

  protected function m_init() { //构造函数
    $_mdl_app = Loader::model('App', '', false);
    $this->arr_status = $_mdl_app->arr_status;
    $this->arr_sync   = $_mdl_app->arr_sync;

    $_str_status = implode('\',\'', $this->arr_status);
    $_str_sync   = implode('\',\'', $this->arr_sync);

    $this->create = array(
      'app_id' => array(
        'type'      => 'smallint(6)',
        'not_null'  => true,
        'ai'        => true,
        'comment'   => 'ID',
      ),
      'app_name' => array(
        'type'      => 'varchar(30)',
        'not_null'  => true,
        'default'   => '',
        'comment'   => '应用名',
      ),
      'app_key' => array(
        'type'      => 'char(64)',
        'not_null'  => true,
        'default'   => '',
        'comment'   => '通信密钥',
      ),
      'app_secret' => array(
        'type'      => 'char(16)',
        'not_null'  => true,
        'default'   => '',
        'comment'   => '密文密钥',
      ),
      'app_url_notify' => array(
        'type'      => 'varchar(3000)',
        'not_null'  => true,
        'default'   => '',
        'comment'   => '通知接口 URL',
        'old'       => 'app_url_notice',
      ),
      'app_url_sync' => array(
        'type'      => 'varchar(3000)',
        'not_null'  => true,
        'default'   => '',
        'comment'   => '同步登录通知 URL',
        'update'    => '`app_url_notify`',
      ),
      'app_status' => array(
        'type'      => 'enum(\'' . $_str_status . '\')',
        'not_null'  => true,
        'default'   => $this->arr_status[0],
        'comment'   => '状态',
        'update'    => $this->arr_status[0],
      ),
      'app_sync' => array(
        'type'      => 'enum(\'' . $_str_sync . '\')',
        'not_null'  => true,
        'default'   => $this->arr_sync[0],
        'comment'   => '是否同步',
        'update'    => $this->arr_sync[0],
      ),
      'app_note' => array(
        'type'      => 'varchar(30)',
        'not_null'  => true,
        'default'   => '',
        'comment'   => '备注',
      ),
      'app_time' => array(
        'type'      => 'int(11)',
        'not_null'  => true,
        'default'   => 0,
        'comment'   => '创建时间',
      ),
      'app_ip_allow' => array(
        'type'      => 'varchar(1000)',
        'not_null'  => true,
        'default'   => '',
        'comment'   => '允许调用IP地址',
      ),
      'app_ip_bad' => array(
        'type'      => 'varchar(1000)',
        'not_null'  => true,
        'default'   => '',
        'comment'   => '禁止IP',
      ),
      'app_allow' => array(
        'type'      => 'varchar(3000)',
        'not_null'  => true,
        'default'   => '',
        'comment'   => '权限',
      ),
      'app_param' => array(
        'type'      => 'varchar(1000)',
        'not_null'  => true,
        'default'   => '',
        'comment'   => '额外参数',
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
      $_str_rcode = 'y050105'; //更新成功
      $_str_msg   = 'Create table successfully';
    } else {
      $_str_rcode = 'x050105'; //更新成功
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
    $_str_rcode = 'y050111';
    $_str_msg   = 'No need to update table';

    $_num_count = $this->alter();

    if ($_num_count === false) {
      $_str_rcode = 'x050106';
      $_str_msg   = 'Update table failed';
    } else if ($_num_count > 0) {
      $_str_rcode = 'y050106';
      $_str_msg   = 'Update table successfully';
    }

    return array(
      'rcode' => $_str_rcode,
      'msg'   => $_str_msg,
    );
  }
}
