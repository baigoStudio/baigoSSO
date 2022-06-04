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

/*-------------设置项模型-------------*/
class Opt extends Validate {

  protected $obj_lang;

  protected $rule     = array(
    'host' => array(
      'require' => true,
    ),
    'port' => array(
      'require' => true,
      'format'  => 'int'
    ),
    'name' => array(
      'require' => true
    ),
    'user' => array(
      'require' => true
    ),
    'pass' => array(
      'require' => true
    ),
    'charset' => array(
      'require' => true
    ),
    'prefix' => array(
      'require' => true
    ),
    'type' => array(
      'require' => true
    ),
    'model' => array(
      'require' => true
    ),
    '__token__' => array(
      'require' => true,
      'token'   => true,
    ),
  );

  protected $scene = array(
    'dbconfig' => array(
      'host',
      'port',
      'name',
      'user',
      'pass',
      'charset',
      'prefix',
      '__token__',
    ),
    'data' => array(
      'type',
      'model',
      '__token__',
    ),
    'common' => array(
      '__token__',
    ),
  );

  protected function v_init() { //构造函数

    $_arr_attrName = array(
      'host'       => $this->obj_lang->get('Database host'),
      'port'       => $this->obj_lang->get('Host port'),
      'name'       => $this->obj_lang->get('Database'),
      'user'       => $this->obj_lang->get('Username'),
      'pass'       => $this->obj_lang->get('Password'),
      'charset'    => $this->obj_lang->get('Charset'),
      'prefix'     => $this->obj_lang->get('Prefix'),
      '__token__'  => $this->obj_lang->get('Token'),
    );

    $_arr_typeMsg = array(
      'require' => $this->obj_lang->get('{:attr} require'),
      'token'   => $this->obj_lang->get('Form token is incorrect'),
    );

    $_arr_formatMsg = array(
      'int' => $this->obj_lang->get('{:attr} must be numeric'),
    );

    $this->setAttrName($_arr_attrName);
    $this->setTypeMsg($_arr_typeMsg);
    $this->setFormatMsg($_arr_formatMsg);
  }
}
