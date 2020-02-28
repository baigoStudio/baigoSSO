<?php
/*-----------------------------------------------------------------
！！！！警告！！！！
以下为系统文件，请勿修改
-----------------------------------------------------------------*/

namespace app\validate\api;

use ginkgo\Validate;
use ginkgo\Config;

//不能非法包含或直接执行
defined('IN_GINKGO') or exit('Access denied');

/*-------------管理员模型-------------*/
class Profile extends Validate {

    protected $rule     = array(
        'user_str' => array(
            'require' => true,
        ),
        'user_by' => array(
            'in' => 'user_id,user_name,user_mail',
        ),
        'user_pass' => array(
            'require' => true,
        ),
        'user_pass_new' => array(
            'require' => true,
        ),
        'user_nick' => array(
            'max' => 30,
        ),
        'user_sec_ques' => array(
            'length' => '1,900',
        ),
        'user_sec_answ' => array(
            'require' => true,
        ),
        'user_mail_new' => array(
            'length'  => '1,300',
            'format'  => 'email',
        ),
        'user_refresh_token' => array(
            'require' => true,
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
        'info' => array(
            'user_str',
            'user_by',
            'user_pass',
            'user_nick',
            'user_contact',
            'user_extend',
            'timestamp',
        ),
        'info_db' => array(
            'user_nick',
            'user_contact',
            'user_extend',
        ),
        'pass' => array(
            'user_str',
            'user_by',
            'user_pass',
            'user_pass_new',
            'timestamp',
        ),
        'secqa' => array(
            'user_str',
            'user_by',
            'user_pass',
            'user_sec_ques',
            'user_sec_answ',
            'timestamp',
        ),
        'mailbox' => array(
            'user_str',
            'user_by',
            'user_pass',
            'user_mail_new',
            'timestamp',
        ),
        'token' => array(
            'user_str',
            'user_by',
            'user_refresh_token',
            'timestamp',
        ),
    );

    function v_init() { //构造函数

        $_arr_attrName = array(
            'user_str'              => $this->obj_lang->get('User ID, Username or Email'),
            'user_pass'             => $this->obj_lang->get('Password'),
            'user_pass_new'         => $this->obj_lang->get('New password'),
            'user_nick'             => $this->obj_lang->get('Nickname'),
            'user_sec_ques'         => $this->obj_lang->get('Security question'),
            'user_sec_answ'         => $this->obj_lang->get('Security answer'),
            'user_mail_new'         => $this->obj_lang->get('Email'),
            'user_refresh_token'    => $this->obj_lang->get('Refresh token'),
            'timestamp'             => $this->obj_lang->get('Timestamp', 'api.common'),
        );

        $_arr_typeMsg = array(
            'require'   => $this->obj_lang->get('{:attr} require'),
            'length'    => $this->obj_lang->get('Size of {:attr} must be {:rule}'),
            'max'       => $this->obj_lang->get('Max size of {:attr} must be {:rule}'),
            'gt'        => $this->obj_lang->get('{:attr} require'),
        );

        $_arr_formatMsg = array(
            'email'     => $this->obj_lang->get('{:attr} not a valid email address'),
        );

        $this->setAttrName($_arr_attrName);
        $this->setTypeMsg($_arr_typeMsg);
        $this->setFormatMsg($_arr_formatMsg);
    }
}
