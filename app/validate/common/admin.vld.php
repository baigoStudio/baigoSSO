<?php
/*-----------------------------------------------------------------
！！！！警告！！！！
以下为系统文件，请勿修改
-----------------------------------------------------------------*/

namespace app\validate\common;

use ginkgo\Validate;

// 不能非法包含或直接执行
if (!defined('IN_GINKGO')) {
  return 'Access denied';
}

/*-------------管理员模型-------------*/
class Admin extends Validate {

  protected $rule     = array(
    'admin_name' => array(
      'length'  => '1,30',
      'format'  => 'alpha_dash',
    ),
    'admin_mail' => array(
      'max'     => 30,
      'format'  => 'email'
    ),
    'admin_nick' => array(
      'max'     => 30,
    ),
    '__token__' => array(
      'require' => true,
      'token'   => true,
    ),
  );


  protected function v_init() { //构造函数

    $_arr_attrName = array(
      'admin_name'            => $this->obj_lang->get('Username'),
      'admin_pass'            => $this->obj_lang->get('Password'),
      'admin_mail'            => $this->obj_lang->get('Email'),
      'admin_nick'            => $this->obj_lang->get('Nickname'),
      '__token__'             => $this->obj_lang->get('Token'),
    );

    $_arr_typeMsg = array(
      'require'   => $this->obj_lang->get('{:attr} require'),
      'length'    => $this->obj_lang->get('Size of {:attr} must be {:rule}'),
      'max'       => $this->obj_lang->get('Max size of {:attr} must be {:rule}'),
      'token'     => $this->obj_lang->get('Form token is incorrect'),
    );

    $_arr_formatMsg = array(
      'alpha_dash' => $this->obj_lang->get('{:attr} must be alpha-numeric, dash, underscore'),
      'email'     => $this->obj_lang->get('{:attr} not a valid email address'),
    );

    $this->setAttrName($_arr_attrName);
    $this->setTypeMsg($_arr_typeMsg);
    $this->setFormatMsg($_arr_formatMsg);
  }
}
