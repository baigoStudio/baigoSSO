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
class App extends Validate {

  protected $rule     = array(
    'app_id' => array(
      'require' => true,
      'format'  => 'int',
    ),
    'app_name' => array(
      'length' => '1,30'
    ),
    'app_url_notify' => array(
      'length' => '1,3000',
      'format' => 'url'
    ),
    'app_url_sync' => array(
      'length' => '1,3000',
      'format' => 'url'
    ),
    'app_ip_allow' => array(
      'max' => 3000
    ),
    'app_ip_bad' => array(
      'max' => 3000
    ),
    'app_note' => array(
      'max' => 30
    ),
    'app_status' => array(
      'require' => true
    ),
    'app_sync' => array(
      'require' => true
    ),
    'app_ids' => array(
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
    'submit' => array(
      'app_id',
      'app_name',
      'app_url_notify',
      'app_url_sync',
      'app_ip_allow',
      'app_ip_bad',
      'app_note',
      'app_status',
      'app_sync',
      '__token__',
    ),
    'submit_db' => array(
      'app_name',
      'app_url_notify',
      'app_url_sync',
      'app_ip_allow',
      'app_ip_bad',
      'app_note',
      'app_status',
      'app_sync',
    ),
    'delete' => array(
      'app_ids',
      '__token__',
    ),
    'status' => array(
      'app_ids',
      'act',
      '__token__',
    ),
    'common' => array(
      'app_id' => array(
          '>' => 0,
      ),
      '__token__',
    ),
  );

  protected function v_init() { //构造函数

    $_arr_attrName = array(
      'app_name'          => $this->obj_lang->get('App name'),
      'app_url_notify'    => $this->obj_lang->get('URL of notifications'),
      'app_url_sync'      => $this->obj_lang->get('URL of sync notifications'),
      'app_ip_allow'      => $this->obj_lang->get('Allowed IPs'),
      'app_ip_bad'        => $this->obj_lang->get('Banned IPs'),
      'app_note'          => $this->obj_lang->get('Note'),
      'app_status'        => $this->obj_lang->get('Status'),
      'app_sync'          => $this->obj_lang->get('Sync'),
      '__token__'         => $this->obj_lang->get('Token'),
    );

    $_arr_typeMsg = array(
      'require'   => $this->obj_lang->get('{:attr} require'),
      'length'    => $this->obj_lang->get('Size of {:attr} must be {:rule}'),
      'max'       => $this->obj_lang->get('Max size of {:attr} must be {:rule}'),
      'gt'        => $this->obj_lang->get('{:attr} require'),
      'token'     => $this->obj_lang->get('Form token is incorrect'),
    );

    $_arr_formatMsg = array(
      'url' => $this->obj_lang->get('{:attr} not a valid url'),
      'int' => $this->obj_lang->get('{:attr} must be integer'),
    );

    $this->setAttrName($_arr_attrName);
    $this->setTypeMsg($_arr_typeMsg);
    $this->setFormatMsg($_arr_formatMsg);
  }
}
