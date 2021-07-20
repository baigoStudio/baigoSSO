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
class User extends Validate {

    protected $rule     = array(
        'user_id' => array(
            'require' => true,
            'format'  => 'int',
        ),
        'user_name' => array(
            'length' => '1,30',
            'format' => 'alpha_dash',
        ),
        'user_pass' => array(
            'require' => true,
        ),
        'user_mail' => array(
            'max'    => 300,
            'format' => 'email',
        ),
        'user_status' => array(
            'require' => true,
        ),
        'user_nick' => array(
            'max' => 30,
        ),
        'user_note' => array(
            'max' => 30,
        ),
        'user_contact' => array(
            'max' => 3000,
        ),
        'user_extend' => array(
            'max' => 3000,
        ),
        'user_ids' => array(
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
            'user_id',
            'user_name',
            'user_pass',
            'user_mail',
            'user_status',
            'user_nick',
            'user_note',
            'user_contact',
            'user_extend',
            '__token__',
        ),
        'status' => array(
            'user_ids',
            'act',
            '__token__',
        ),
        'delete' => array(
            'user_ids',
            '__token__',
        ),
    );


    function v_init() { //构造函数

        $_arr_attrName = array(
            'user_id'       => $this->obj_lang->get('ID'),
            'user_name'     => $this->obj_lang->get('Username'),
            'user_mail'     => $this->obj_lang->get('Email'),
            'user_pass'     => $this->obj_lang->get('Password'),
            'user_status'   => $this->obj_lang->get('Status'),
            'user_nick'     => $this->obj_lang->get('Nickname'),
            'user_note'     => $this->obj_lang->get('Note'),
            'user_contact'  => $this->obj_lang->get('Contact'),
            'user_extend'   => $this->obj_lang->get('Extend'),
            'user_ids'      => $this->obj_lang->get('User'),
            'act'           => $this->obj_lang->get('Action'),
            '__token__'     => $this->obj_lang->get('Token'),
        );

        $_arr_typeMsg = array(
            'require'   => $this->obj_lang->get('{:attr} require'),
            'length'    => $this->obj_lang->get('Size of {:attr} must be {:rule}'),
            'max'       => $this->obj_lang->get('Max size of {:attr} must be {:rule}'),
            'token'     => $this->obj_lang->get('Form token is incorrect'),
        );

        $_arr_formatMsg = array(
            'alpha_dash'  => $this->obj_lang->get('{:attr} must be alpha-numeric, dash, underscore'),
            'int'         => $this->obj_lang->get('{:attr} must be integer'),
            'email'       => $this->obj_lang->get('{:attr} not a valid email address'),
        );

        $this->setAttrName($_arr_attrName);
        $this->setTypeMsg($_arr_typeMsg);
        $this->setFormatMsg($_arr_formatMsg);
    }
}
