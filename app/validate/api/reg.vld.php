<?php
/*-----------------------------------------------------------------
！！！！警告！！！！
以下为系统文件，请勿修改
-----------------------------------------------------------------*/

namespace app\validate\api;

use ginkgo\Validate;
use ginkgo\Config;

// 不能非法包含或直接执行
if (!defined('IN_GINKGO')) {
  return 'Access denied';
}

/*-------------管理员模型-------------*/
class Reg extends Validate {

  protected $rule =  array(
    'user_name' => array(
      'length' => '1,30',
      'format' => 'alpha_dash'
    ),
    'user_pass' => array(
      'require' => true,
    ),
    'user_mail' => array(
      'max'    => 300,
      'format' => 'email',
    ),
    'user_nick' => array(
      'max' => 30,
    ),
    'user_ip' => array(
      'format' => 'ip',
    ),
    'user_contact' => array(
      'max' => 3000,
    ),
    'user_extend' => array(
      'max' => 3000,
    ),
    'not_id' => array(
      'format'  => 'int',
    ),
    'timestamp' => array(
      '>' => 0,
    ),
  );

  protected $scene = array(
    'reg' => array(
      'user_name',
      'user_pass',
      'user_mail',
      'user_nick',
      'user_ip',
      'user_contact',
      'user_extend',
      'timestamp',
    ),
    'reg_db' => array(
      'user_name',
      'user_pass',
      'user_mail',
      'user_nick',
      'user_ip',
      'user_contact',
      'user_extend',
    ),
    'chkname' => array(
      'user_name',
      'timestamp',
    ),
    'chkmail' => array(
      'user_mail' => array(
        'require' => true,
        'format'  => 'email',
      ),
      'not_id',
      'timestamp',
    ),
  );

  protected function v_init() { //构造函数
    $_arr_configReg = Config::get('reg', 'var_extra');

    if (isset($_arr_configReg['reg_needmail']) && $_arr_configReg['reg_needmail'] === 'on') {
      $this->rule['user_mail'] = array(
        'length'    => '1,300',
        'format'    => 'email',
      );
    }

    $_arr_attrName = array(
      'user_name'     => $this->obj_lang->get('Username'),
      'user_pass'     => $this->obj_lang->get('Password'),
      'user_mail'     => $this->obj_lang->get('Email'),
      'user_nick'     => $this->obj_lang->get('Nickname'),
      'user_ip'       => $this->obj_lang->get('IP Address'),
      'user_note'     => $this->obj_lang->get('Note'),
      'user_contact'  => $this->obj_lang->get('Contact'),
      'user_extend'   => $this->obj_lang->get('Extend'),
      'timestamp'     => $this->obj_lang->get('Timestamp', 'api.common'),
    );

    $_arr_typeMsg = array(
      'require'   => $this->obj_lang->get('{:attr} require'),
      'length'    => $this->obj_lang->get('Size of {:attr} must be {:rule}'),
      'max'       => $this->obj_lang->get('Max size of {:attr} must be {:rule}'),
      'gt'        => $this->obj_lang->get('{:attr} require'),
    );

    $_arr_formatMsg = array(
      'alpha_dash'  => $this->obj_lang->get('{:attr} must be alpha-numeric, dash, underscore'),
      'int'         => $this->obj_lang->get('{:attr} must be integer'),
      'email'       => $this->obj_lang->get('{:attr} not a valid email address'),
      'ip'          => $this->obj_lang->get('{:attr} not a valid ip'),
    );

    $this->setAttrName($_arr_attrName);
    $this->setTypeMsg($_arr_typeMsg);
    $this->setFormatMsg($_arr_formatMsg);
  }
}
