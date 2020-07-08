<?php
/*-----------------------------------------------------------------
！！！！警告！！！！
以下为系统文件，请勿修改
-----------------------------------------------------------------*/

namespace app\validate\api;

use ginkgo\Validate;

// 不能非法包含或直接执行
defined('IN_GINKGO') or exit('Access denied');

/*-------------管理员模型-------------*/
class User extends Validate {

    protected $rule     = array(
        'user_str' => array(
            'require' => true,
        ),
        'user_by' => array(
            'in' => 'user_id,user_name,user_mail',
        ),
        'user_mail_new' => array(
            'max'    => 300,
            'format' => 'email',
        ),
        'user_nick' => array(
            'max' => 30,
        ),
        'user_contact' => array(
            'max' => 3000,
        ),
        'user_extend' => array(
            'max' => 3000,
        ),
        'timestamp' => array(
            '>' => 0,
        ),
    );

    protected $scene = array(
        'edit' => array(
            'user_str',
            'user_by',
            'user_mail_new',
            'user_nick',
            'user_contact',
            'user_extend',
            'timestamp',
        ),
        'edit_db' => array(
            'user_mail',
            'user_nick',
            'user_contact',
            'user_extend',
        ),
        'read' => array(
            'user_str',
            'user_by',
            'timestamp',
        ),
    );

    function v_init() { //构造函数

        $_arr_attrName = array(
            'user_str'      => $this->obj_lang->get('User ID, Username or Email'),
            'user_mail_new' => $this->obj_lang->get('Email'),
            'user_nick'     => $this->obj_lang->get('Nickname'),
            'user_note'     => $this->obj_lang->get('Note'),
            'user_contact'  => $this->obj_lang->get('Contact'),
            'user_extend'   => $this->obj_lang->get('Extend'),
            'timestamp'     => $this->obj_lang->get('Timestamp', 'api.common'),
        );

        $_arr_typeMsg = array(
            'require'   => $this->obj_lang->get('{:attr} require'),
            'max'       => $this->obj_lang->get('Max size of {:attr} must be {:rule}'),
            'gt'        => $this->obj_lang->get('{:attr} require'),
        );

        $_arr_formatMsg = array(
            'email'       => $this->obj_lang->get('{:attr} not a valid email address'),
        );

        $this->setAttrName($_arr_attrName);
        $this->setTypeMsg($_arr_typeMsg);
        $this->setFormatMsg($_arr_formatMsg);
    }
}
