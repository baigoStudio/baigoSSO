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
class Auth extends Validate {

    protected $rule     = array(
        'admin_id' => array(
            'require' => true,
        ),
        'admin_name' => array(
            'length' => '1,30',
            'format' => 'alpha_dash',
        ),
        'admin_status' => array(
            'require' => true,
        ),
        'admin_type' => array(
            'require' => true,
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
        '__token__' => array(
            'require' => true,
            'token'   => true,
        ),
    );

    protected $scene = array(
        'submit' => array(
            'admin_name',
            'admin_status',
            'admin_type',
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
            'admin_note',
            'admin_allow',
            'admin_allow_profile',
        ),
    );

    function v_init() { //构造函数

        $_arr_attrName = array(
            'admin_id'              => $this->obj_lang->get('ID'),
            'admin_name'            => $this->obj_lang->get('Username'),
            'admin_status'          => $this->obj_lang->get('Status'),
            'admin_type'            => $this->obj_lang->get('Type'),
            'admin_note'            => $this->obj_lang->get('Note'),
            'admin_allow'           => $this->obj_lang->get('Permission'),
            'admin_allow_profile'   => $this->obj_lang->get('Personal permission'),
            '__token__'             => $this->obj_lang->get('Token'),
        );

        $_arr_typeMsg = array(
            'require'   => $this->obj_lang->get('{:attr} require'),
            'length'    => $this->obj_lang->get('Size of {:attr} must be {:rule}'),
            'max'       => $this->obj_lang->get('Max size of {:attr} must be {:rule}'),
            'token'     => $this->obj_lang->get('Form token is incorrect'),
        );

        $_arr_formatMsg = array(
            'alpha_dash'   => $this->obj_lang->get('{:attr} must be alpha-numeric, dash, underscore'),
        );

        $this->setAttrName($_arr_attrName);
        $this->setTypeMsg($_arr_typeMsg);
        $this->setFormatMsg($_arr_formatMsg);
    }
}
