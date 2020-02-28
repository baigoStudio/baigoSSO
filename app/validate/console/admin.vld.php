<?php
/*-----------------------------------------------------------------
！！！！警告！！！！
以下为系统文件，请勿修改
-----------------------------------------------------------------*/

namespace app\validate\console;

use ginkgo\Validate;

//不能非法包含或直接执行
defined('IN_GINKGO') or exit('Access denied');

/*-------------管理员模型-------------*/
class Admin extends Validate {

    protected $rule     = array(
        'admin_id' => array(
            'require' => true,
            'format'  => 'int',
        ),
        'admin_name' => array(
            'length' => '1,30',
            'format' => 'alpha_dash',
        ),
        'admin_pass' => array(
            'require' => true,
        ),
        'admin_status' => array(
            'require' => true,
        ),
        'admin_type' => array(
            'require' => true,
        ),
        'admin_nick' => array(
            'max' => 30,
        ),
        'admin_note' => array(
            'max' => 30,
        ),
        'admin_allow' => array(
            'max' => 3000,
        ),
        'admin_allow_profile' => array(
            'max' => 1000,
        ),
        'admin_ids' => array(
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
            'admin_id',
            'admin_name',
            'admin_pass',
            'admin_status',
            'admin_type',
            'admin_nick',
            'admin_note',
            'admin_allow',
            'admin_allow_profile',
            '__token__',
        ),
        'submit_db' => array(
            'admin_id',
            'admin_name',
            'admin_status',
            'admin_type',
            'admin_nick',
            'admin_note',
            'admin_allow',
            'admin_allow_profile',
        ),
        'status' => array(
            'admin_ids',
            'act',
            '__token__',
        ),
        'delete' => array(
            'admin_ids',
            '__token__',
        ),
    );

    function v_init() { //构造函数

        $_arr_attrName = array(
            'admin_id'              => $this->obj_lang->get('ID'),
            'admin_name'            => $this->obj_lang->get('Username'),
            'admin_pass'            => $this->obj_lang->get('Password'),
            'admin_status'          => $this->obj_lang->get('Status'),
            'admin_type'            => $this->obj_lang->get('Type'),
            'admin_nick'            => $this->obj_lang->get('Nickname'),
            'admin_note'            => $this->obj_lang->get('Note'),
            'admin_allow'           => $this->obj_lang->get('Permission'),
            'admin_allow_profile'   => $this->obj_lang->get('Personal permission'),
            'admin_ids'             => $this->obj_lang->get('Administrator'),
            'act'                   => $this->obj_lang->get('Action'),
            '__token__'             => $this->obj_lang->get('Token'),
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
        );

        $this->setAttrName($_arr_attrName);
        $this->setTypeMsg($_arr_typeMsg);
        $this->setFormatMsg($_arr_formatMsg);
    }
}
