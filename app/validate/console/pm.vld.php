<?php
/*-----------------------------------------------------------------
！！！！警告！！！！
以下为系统文件，请勿修改
-----------------------------------------------------------------*/

namespace app\validate\console;

use ginkgo\Validate;

// 不能非法包含或直接执行
if (!defined('IN_GINKGO')) {
  return 'Access denied';
}

/*-------------管理员模型-------------*/
class Pm extends Validate {

  protected $rule     = array(
    'pm_to' => array(
      '>' => 0,
      'format'  => 'int',
    ),
    'pm_title' => array(
      'max' => 90,
    ),
    'pm_content' => array(
      'length' => '1,900',
    ),
    'pm_bulk_type' => array(
      'require' => true,
    ),
    'pm_to_users' => array(
      'require' => true,
    ),
    'pm_to_key_name' => array(
      'require' => true,
    ),
    'pm_to_key_mail' => array(
      'require' => true,
    ),
    'pm_to_min_id' => array(
      'require' => true,
      'format'  => 'int',
    ),
    'pm_to_max_id' => array(
      'require' => true,
      'format'  => 'int',
    ),
    'pm_to_begin_reg' => array(
      'require' => true,
      'format'  => 'date_time',
    ),
    'pm_to_end_reg' => array(
      'require' => true,
      'format'  => 'date_time',
    ),
    'pm_to_begin_login' => array(
      'require' => true,
      'format'  => 'date_time',
    ),
    'pm_to_end_login' => array(
      'require' => true,
      'format'  => 'date_time',
    ),
    'pm_ids' => array(
      'require' => true,
    ),
    'act' => array(
      'require' => true,
    ),
    '__token__' => array(
      'require' => true,
      'token'   => true,
    ),
  );

  protected $scene = array(
    'submit_db' => array(
      'pm_to',
      'pm_title',
      'pm_content',
    ),
    'bulk_users' => array(
      'pm_to_users',
      '__token__',
    ),
    'bulk_key_name' => array(
      'pm_to_key_name',
      '__token__',
    ),
    'bulk_key_mail' => array(
      'pm_to_key_mail',
      '__token__',
    ),
    'bulk_range_id' => array(
      'pm_to_min_id',
      'pm_to_max_id',
      '__token__',
    ),
    'bulk_range_reg' => array(
      'pm_to_begin_reg',
      'pm_to_end_reg',
      '__token__',
    ),
    'bulk_range_login' => array(
      'pm_to_begin_login',
      'pm_to_end_login',
      '__token__',
    ),
    'status' => array(
      'pm_ids',
      'act',
      '__token__',
    ),
    'delete' => array(
      'pm_ids',
      '__token__',
    ),
  );

  protected function v_init() { //构造函数

    $_arr_attrName = array(
      'pm_title'          => $this->obj_lang->get('Title'),
      'pm_content'        => $this->obj_lang->get('Content'),
      'pm_bulk_type'      => $this->obj_lang->get('Bulk send type'),
      'pm_to_users'       => $this->obj_lang->get('Recipient'),
      'pm_to_key_name'    => $this->obj_lang->get('Keyword'),
      'pm_to_key_mail'    => $this->obj_lang->get('Keyword'),
      'pm_to_min_id'      => $this->obj_lang->get('Start ID'),
      'pm_to_max_id'      => $this->obj_lang->get('End ID'),
      'pm_to_begin_reg'   => $this->obj_lang->get('Start time'),
      'pm_to_end_reg'     => $this->obj_lang->get('End time'),
      'pm_to_begin_login' => $this->obj_lang->get('Start time'),
      'pm_to_end_login'   => $this->obj_lang->get('End time'),
      'pm_ids'            => $this->obj_lang->get('Message'),
      'act'               => $this->obj_lang->get('Action'),
      '__token__'         => $this->obj_lang->get('Token'),
    );

    $_arr_typeMsg = array(
      'require'   => $this->obj_lang->get('{:attr} require'),
      'length'    => $this->obj_lang->get('Size of {:attr} must be {:rule}'),
      'max'       => $this->obj_lang->get('Max size of {:attr} must be {:rule}'),
      'int'       => $this->obj_lang->get('{:attr} must be integer'),
      'token'     => $this->obj_lang->get('Form token is incorrect'),
    );

    $_arr_formatMsg = array(
      'date_time'   => $this->obj_lang->get('{:attr} not a valid datetime'),
      'int'         => $this->obj_lang->get('{:attr} must be integer'),
    );

    $this->setAttrName($_arr_attrName);
    $this->setTypeMsg($_arr_typeMsg);
    $this->setFormatMsg($_arr_formatMsg);
  }

}
