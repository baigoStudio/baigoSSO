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
class Combine_Belong extends Validate {

  protected $rule     = array(
    'combine_id' => array(
      'require' => true,
      'format'  => 'int',
    ),
    'app_ids' => array(
      'require' => true,
    ),
    'app_ids_belong' => array(
      'require' => true,
    ),
    '__token__' => array(
      'require' => true,
      'token'   => true,
    ),
  );

  protected $scene = array(
    'submit' => array(
      'combine_id' => array(
        '>' => 0,
      ),
      'app_ids',
      '__token__',
    ),
    'remove' => array(
      'combine_id' => array(
        '>' => 0,
      ),
      'app_ids_belong',
      '__token__',
    ),
  );

  protected function v_init() { //构造函数

    $_arr_attrName = array(
      'combine_id'            => $this->obj_lang->get('Combine ID'),
      'app_ids'          => $this->obj_lang->get('App'),
      'app_ids_belong'   => $this->obj_lang->get('App'),
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
