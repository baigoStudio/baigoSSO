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

/*-------------用户模型-------------*/
class User_App_View extends Model {

  protected $create = array(
    array('user.user_id'),
    array('user.user_name'),
    array('user.user_mail'),
    array('user.user_nick'),
    array('user.user_note'),
    array('user.user_status'),
    array('user.user_time'),
    array('user.user_time_login'),
    array('user.user_ip'),
  );


  /** 创建视图
   * mdl_create_view function.
   *
   * @access public
   * @return void
   */
  public function createView() {
    $this->create[] = 'IFNULL(' . $this->obj_builder->table('app_belong') . '.`belong_app_id`, 0) AS `belong_app_id`';

    $_arr_join = array(
      'app_belong',
      array('user.user_id', '=', 'app_belong.belong_user_id'),
      'LEFT',
    );

    $_num_count  = $this->viewFrom('user')->viewJoin($_arr_join)->create();

    if ($_num_count !== false) {
      $_str_rcode = 'y010108'; //更新成功
      $_str_msg   = 'Create view successfully';
    } else {
      $_str_rcode = 'x010108'; //更新成功
      $_str_msg   = 'Create view failed';
    }

    return array(
      'rcode' => $_str_rcode, //更新成功
      'msg'   => $_str_msg,
    );
  }
}
