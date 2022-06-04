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

/*-------------用户模型-------------*/
class User extends Model {

  protected $pk = 'pm_id';
  protected $comment = '用户';

  public $arr_status = array();

  protected function m_init() { //构造函数
    $_mdl_user = Loader::model('User', '', false);
    $this->arr_status = $_mdl_user->arr_status;

    $_str_status = implode('\',\'', $this->arr_status);

    $this->create = array(
      'user_id' => array(
        'type'      => 'int(11)',
        'not_null'  => true,
        'ai'        => true,
        'comment'   => 'ID',
      ),
      'user_name' => array(
        'type'      => 'varchar(30)',
        'not_null'  => true,
        'default'   => '',
        'comment'   => '用户名',
      ),
      'user_mail' => array(
        'type'      => 'varchar(300)',
        'not_null'  => true,
        'default'   => '',
        'comment'   => '邮箱',
      ),
      'user_contact' => array(
        'type'      => 'varchar(3000)',
        'not_null'  => true,
        'default'   => '',
        'comment'   => '联系方式',
      ),
      'user_extend' => array(
        'type'      => 'varchar(3000)',
        'not_null'  => true,
        'default'   => '',
        'comment'   => '扩展字段',
      ),
      'user_pass' => array(
        'type'      => 'char(32)',
        'not_null'  => true,
        'default'   => '',
        'comment'   => '密码',
      ),
      'user_rand' => array(
        'type'      => 'char(32)',
        'not_null'  => true,
        'default'   => '',
        'comment'   => '随机串',
      ),
      'user_nick' => array(
        'type'      => 'varchar(30)',
        'not_null'  => true,
        'default'   => '',
        'comment'   => '昵称',
      ),
      'user_status' => array(
        'type'      => 'enum(\'' . $_str_status . '\')',
        'not_null'  => true,
        'default'   => $this->arr_status[0],
        'comment'   => '状态',
        'update'    => $this->arr_status[0],
      ),
      'user_note' => array(
        'type'      => 'varchar(30)',
        'not_null'  => true,
        'default'   => '',
        'comment'   => '备注',
      ),
      'user_time' => array(
        'type'      => 'int(11)',
        'not_null'  => true,
        'default'   => 0,
        'comment'   => '创建时间',
      ),
      'user_time_login' => array(
        'type'      => 'int(11)',
        'not_null'  => true,
        'default'   => 0,
        'comment'   => '登录时间',
      ),
      'user_ip' => array(
        'type'      => 'varchar(15)',
        'not_null'  => true,
        'default'   => '',
        'comment'   => '最后 IP 地址',
      ),
      'user_access_token' => array(
        'type'      => 'char(32)',
        'not_null'  => true,
        'default'   => '',
        'comment'   => '访问口令',
      ),
      'user_access_expire' => array(
        'type'      => 'int(11)',
        'not_null'  => true,
        'default'   => 0,
        'comment'   => '访问过期时间',
      ),
      'user_refresh_token' => array(
        'type'      => 'char(32)',
        'not_null'  => true,
        'default'   => '',
        'comment'   => '刷新口令',
      ),
      'user_refresh_expire' => array(
        'type'      => 'int(11)',
        'not_null'  => true,
        'default'   => 0,
        'comment'   => '刷新过期时间',
      ),
      'user_app_id' => array(
        'type'      => 'smallint(6)',
        'not_null'  => true,
        'default'   => 0,
        'comment'   => '来源 APP ID',
      ),
      'user_sec_ques' => array(
        'type'      => 'varchar(900)',
        'not_null'  => true,
        'default'   => '',
        'comment'   => '密保问题',
      ),
      'user_sec_answ' => array(
        'type'      => 'char(32)',
        'not_null'  => true,
        'default'   => '',
        'comment'   => '密保答案',
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
      $_str_rcode = 'y010105'; //更新成功
      $_str_msg   = 'Create table successfully';
    } else {
      $_str_rcode = 'x010105'; //更新成功
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
    $_str_rcode = 'y010111';
    $_str_msg   = 'No need to update table';

    $_num_count = $this->alter();

    if ($_num_count === false) {
      $_str_rcode = 'x010106';
      $_str_msg   = 'Update table failed';
    } else if ($_num_count > 0) {
      $_str_rcode = 'y010106';
      $_str_msg   = 'Update table successfully';

    }

    return array(
      'rcode' => $_str_rcode,
      'msg'   => $_str_msg,
    );
  }
}
