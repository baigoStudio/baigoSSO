<?php
/*-----------------------------------------------------------------
！！！！警告！！！！
以下为系统文件，请勿修改
-----------------------------------------------------------------*/

namespace app\validate\console;

use app\validate\common\Admin as Admin_Common;

// 不能非法包含或直接执行
if (!defined('IN_GINKGO')) {
  return 'Access denied';
}

/*-------------管理员模型-------------*/
class Admin extends Admin_Common {

  protected $scene = array(
    'submit' => array(
      'admin_id',
      'admin_name',
      'admin_mail',
      'admin_mail_new',
      'admin_status',
      'admin_type',
      'admin_nick',
      'admin_note',
      'admin_allow',
      'admin_allow_profile',
      '__token__',
    ),
    'submit_db' => array(
      'admin_id',
      'admin_name',
      'admin_mail',
      'admin_mail_new',
      'admin_status',
      'admin_type',
      'admin_nick',
      'admin_note',
      'admin_allow',
      'admin_allow_profile',
    ),
    'status' => array(
      'admin_ids',
      'act',
      '__token__',
    ),
    'delete' => array(
      'admin_ids',
      '__token__',
    ),
  );

  protected function v_init() { //构造函数
    parent::v_init();

    $_arr_rule     = array(
      'admin_id' => array(
        'require' => true,
        'format'  => 'int',
      ),
      'admin_mail_new' => array(
        'max'    => 300,
        'format' => 'email',
      ),
      'admin_status' => array(
        'require' => true,
      ),
      'admin_type' => array(
        'require' => true,
      ),
      'admin_note' => array(
        'max' => 30,
      ),
      'admin_allow' => array(
        'max' => 3000,
      ),
      'admin_allow_profile' => array(
        'max' => 1000,
      ),
      'admin_ids' => array(
        'require' => true,
      ),
      'act' => array(
        'require' => true,
      ),
    );

    $_arr_attrName = array(
      'admin_id'              => $this->obj_lang->get('ID'),
      'admin_mail_new'        => $this->obj_lang->get('Email'),
      'admin_status'          => $this->obj_lang->get('Status'),
      'admin_type'            => $this->obj_lang->get('Type'),
      'admin_note'            => $this->obj_lang->get('Note'),
      'admin_allow'           => $this->obj_lang->get('Permission'),
      'admin_allow_profile'   => $this->obj_lang->get('Personal permission'),
      'admin_ids'             => $this->obj_lang->get('Administrator'),
      'act'                   => $this->obj_lang->get('Action'),
    );

    $_arr_formatMsg = array(
      'int'         => $this->obj_lang->get('{:attr} must be integer'),
    );

    $this->rule($_arr_rule);
    $this->setAttrName($_arr_attrName);
    $this->setFormatMsg($_arr_formatMsg);
  }
}
