<?php
/*-----------------------------------------------------------------
！！！！警告！！！！
以下为系统文件，请勿修改
-----------------------------------------------------------------*/

namespace app\validate\install;

use ginkgo\Validate;

// 不能非法包含或直接执行
defined('IN_GINKGO') or exit('Access denied');

/*-------------管理员模型-------------*/
class Admin extends Validate {

    protected $rule     = array(
        'admin_name' => array(
            'length'  => '1,30',
            'format'  => 'alpha_dash',
        ),
        'admin_pass' => array(
            'require' => true,
        ),
        'admin_pass_confirm' => array(
            'confirm' => true,
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

    protected $scene = array(
        'submit' => array(
            'admin_name',
            'admin_pass',
            'admin_pass_confirm',
            'admin_mail',
            'admin_nick',
            '__token__',
        ),
        'submit_db' => array(
            'admin_id',
            'admin_name',
        ),
        'auth' => array(
            'admin_name',
            '__token__',
        ),
    );

    function v_init() { //构造函数

        $_arr_attrName = array(
            'admin_name'            => $this->obj_lang->get('Username'),
            'admin_pass'            => $this->obj_lang->get('Password'),
            'admin_pass_confirm'    => $this->obj_lang->get('Confirm password'),
            'admin_mail'            => $this->obj_lang->get('Email'),
            'admin_nick'            => $this->obj_lang->get('Nickname'),
            '__token__'             => $this->obj_lang->get('Token'),
        );

        $_arr_typeMsg = array(
            'require'   => $this->obj_lang->get('{:attr} require'),
            'length'    => $this->obj_lang->get('Size of {:attr} must be {:rule}'),
            'confirm'   => $this->obj_lang->get('{:attr} out of accord with {:confirm}'),
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
