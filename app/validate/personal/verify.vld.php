<?php
/*-----------------------------------------------------------------
！！！！警告！！！！
以下为系统文件，请勿修改
-----------------------------------------------------------------*/

namespace app\validate\personal;

use ginkgo\Validate;

// 不能非法包含或直接执行
if (!defined('IN_GINKGO')) {
  return 'Access denied';
}

/*-------------管理员模型-------------*/
class Verify extends Validate {

  protected $rule     = array(
    'verify_id' => array(
      'require' => true,
    ),
    'verify_token' => array(
      'require' => true,
    ),
    'captcha' => array(
      'length'    => '4,4',
      'format'    => 'alpha_number',
      'captcha'   => 'captcha_verify',
    ),
    'user_pass_new' => array(
      'require' => true,
    ),
    'user_pass_confirm'=> array(
      'confirm' => 'user_pass_new',
    ),
    '__token__' => array(
      'require' => true,
      'token'   => true,
    ),
  );

  protected $scene = array(
    'common' => array(
      'verify_id',
      'verify_token',
      'captcha',
      '__token__',
    ),
    'pass' => array(
      'verify_id',
      'verify_token',
      'user_pass_new',
      'user_pass_confirm',
      'captcha',
      '__token__',
    ),
  );

  protected function v_init() { //构造函数

    $_arr_attrName = array(
      'verify_id'         => $this->obj_lang->get('ID'),
      'verify_token'      => $this->obj_lang->get('Validation token'),
      'user_pass_new'     => $this->obj_lang->get('New password'),
      'user_pass_confirm' => $this->obj_lang->get('Confirm password'),
      'captcha'           => $this->obj_lang->get('Captcha'),
      '__token__'         => $this->obj_lang->get('Token'),
    );

    $_arr_typeMsg = array(
      'require'   => $this->obj_lang->get('{:attr} require'),
      'length'    => $this->obj_lang->get('Size of {:attr} must be {:rule}'),
      'confirm'   => $this->obj_lang->get('{:attr} out of accord with {:confirm}'),
      'token'     => $this->obj_lang->get('Form token is incorrect'),
      'captcha'   => $this->obj_lang->get('Captcha is incorrect'),
    );

    $_arr_formatMsg = array(
      'alpha_number'    => $this->obj_lang->get('{:attr} must be alpha-numeric'),
    );

    $this->setAttrName($_arr_attrName);
    $this->setTypeMsg($_arr_typeMsg);
    $this->setFormatMsg($_arr_formatMsg);
  }
}
