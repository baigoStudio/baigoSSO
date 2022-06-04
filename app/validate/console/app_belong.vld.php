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
class App_Belong extends Validate {

  protected $rule     = array(
    'app_id' => array(
      'require' => true,
      'format'  => 'int',
    ),
    'user_ids' => array(
      'require' => true,
    ),
    'user_ids_belong' => array(
      'require' => true,
    ),
    '__token__' => array(
      'require' => true,
      'token'   => true,
    ),
  );

  protected $scene = array(
    'submit' => array(
      'app_id' => array(
        '>' => 0,
      ),
      'user_ids',
      '__token__',
    ),
    'remove' => array(
      'app_id' => array(
        '>' => 0,
      ),
      'user_ids_belong',
      '__token__',
    ),
  );

  protected function v_init() { //构造函数

    $_arr_attrName = array(
      'app_id'            => $this->obj_lang->get('App ID'),
      'user_ids'          => $this->obj_lang->get('User'),
      'user_ids_belong'   => $this->obj_lang->get('User'),
      '__token__'         => $this->obj_lang->get('Token'),
    );

    $_arr_typeMsg = array(
      'require'   => $this->obj_lang->get('{:attr} require'),
      'gt'        => $this->obj_lang->get('{:attr} require'),
      'token'     => $this->obj_lang->get('Form token is incorrect'),
    );

    $_arr_formatMsg = array(
      'int' => $this->obj_lang->get('{:attr} must be integer'),
    );

    $this->setAttrName($_arr_attrName);
    $this->setTypeMsg($_arr_typeMsg);
    $this->setFormatMsg($_arr_formatMsg);
  }
}
