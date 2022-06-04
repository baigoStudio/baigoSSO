<?php
/*-----------------------------------------------------------------
！！！！警告！！！！
以下为系统文件，请勿修改
-----------------------------------------------------------------*/

namespace app\validate\install;

use app\validate\common\Admin as Admin_Common;

// 不能非法包含或直接执行
if (!defined('IN_GINKGO')) {
  return 'Access denied';
}

/*-------------管理员模型-------------*/
class Admin extends Admin_Common {

  protected $scene = array(
    'submit' => array(
      'admin_name',
      'admin_pass',
      'admin_pass_confirm',
      'admin_mail',
      'admin_nick',
      '__token__',
    ),
    'submit_db' => array(
      'admin_id',
      'admin_name',
    ),
    'auth' => array(
      'admin_name',
      '__token__',
    ),
  );

  protected function v_init() { //构造函数
    parent::v_init();

    $_arr_rule     = array(
      'admin_pass' => array(
        'require' => true,
      ),
      'admin_pass_confirm' => array(
        'confirm' => true,
      ),
    );

    $_arr_attrName = array(
      'admin_pass_confirm'    => $this->obj_lang->get('Confirm password'),
    );

    $_arr_typeMsg = array(
      'confirm'   => $this->obj_lang->get('{:attr} out of accord with {:confirm}'),
    );

    $this->rule($_arr_rule);
    $this->setAttrName($_arr_attrName);
    $this->setTypeMsg($_arr_typeMsg);
  }
}
