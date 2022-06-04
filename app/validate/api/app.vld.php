<?php
/*-----------------------------------------------------------------
！！！！警告！！！！
以下为系统文件，请勿修改
-----------------------------------------------------------------*/

namespace app\validate\api;

use ginkgo\Validate;

// 不能非法包含或直接执行
if (!defined('IN_GINKGO')) {
  return 'Access denied';
}

/*-------------管理员模型-------------*/
class App extends Validate {

  protected $rule     = array(
    'app_id' => array(
      '>' => 0,
    ),
    'app_key' => array(
      'require' => true,
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
    'sign' => array(
      'require' => true,
    ),
    'code' => array(
      'require' => true,
    ),
    'timestamp' => array(
      '>' => 0,
    ),
  );

  protected $scene = array(
    'submit' => array(
      'app_name',
      'app_url_notify',
      'app_url_sync',
      'timestamp',
    ),
    'submit_db' => array(
      'app_name',
      'app_url_notify',
      'app_url_sync',
    ),
    'base' => array(
      'sign',
      'code',
    ),
    'common' => array(
      'app_id',
      'app_key',
      'sign',
      'code',
    ),
  );


  protected function v_init() { //构造函数

    $_arr_attrName = array(
      'app_id'            => $this->obj_lang->get('App ID', 'api.common'),
      'app_key'           => $this->obj_lang->get('App Key', 'api.common'),
      'app_name'          => $this->obj_lang->get('App name'),
      'app_url_notify'    => $this->obj_lang->get('URL of notifications'),
      'app_url_sync'      => $this->obj_lang->get('URL of sync notifications'),
      'sign'              => $this->obj_lang->get('Signature', 'api.common'),
      'code'              => $this->obj_lang->get('Encrypted code', 'api.common'),
      'timestamp'         => $this->obj_lang->get('Timestamp', 'api.common'),
    );

    $_arr_typeMsg = array(
      'length'    => $this->obj_lang->get('Size of {:attr} must be {:rule}'),
      'gt'        => $this->obj_lang->get('{:attr} require'),
    );

    $_arr_formatMsg = array(
      'url' => $this->obj_lang->get('{:attr} not a valid url'),
    );

    $this->setAttrName($_arr_attrName);
    $this->setTypeMsg($_arr_typeMsg);
    $this->setFormatMsg($_arr_formatMsg);
  }
}
