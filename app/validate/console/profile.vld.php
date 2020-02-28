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
class Profile extends Validate {

    protected $rule     = array(
        'admin_pass' => array(
            'require' => true,
        ),
        'admin_pass_new' => array(
            'require' => true,
        ),
        'admin_pass_confirm'=> array(
            'confirm' => 'admin_pass_new',
        ),
        'admin_sec_ques' => array(
            'length' => '1,900',
        ),
        'admin_sec_answ' => array(
            'require' => true,
        ),
        'admin_mail_new' => array(
            'length' => '1,300',
            'format' => 'email',
        ),
        'admin_nick' => array(
            'max' => 30,
        ),
        'admin_shortcut' => array(
            'require' => true,
        ),
        '__token__' => array(
            'require' => true,
            'token'   => true,
        ),
    );

    protected $scene = array(
        'shortcut' => array(
            'admin_shortcut',
            '__token__',
        ),
        'shortcut_db' => array(
            'admin_shortcut',
        ),
        'info' => array(
            'admin_pass',
            'admin_nick',
            '__token__',
        ),
        'info_db' => array(
            'admin_nick',
        ),
        'pass' => array(
            'admin_pass',
            'admin_pass_new',
            'admin_pass_confirm',
            '__token__',
        ),
        'secqa' => array(
            'admin_pass',
            'admin_sec_ques',
            'admin_sec_answ',
            '__token__',
        ),
        'mailbox' => array(
            'admin_pass',
            'admin_mail_new',
            '__token__',
        ),
    );


    function v_init() { //构造函数

        $_arr_attrName = array(
            'admin_pass'            => $this->obj_lang->get('Password'),
            'admin_pass_new'        => $this->obj_lang->get('New password'),
            'admin_pass_confirm'    => $this->obj_lang->get('Confirm password'),
            'admin_sec_ques'        => $this->obj_lang->get('Security question'),
            'admin_sec_answ'        => $this->obj_lang->get('Security answer'),
            'admin_mail_new'        => $this->obj_lang->get('New mailbox'),
            'admin_nick'            => $this->obj_lang->get('Nickname'),
            'admin_shortcut'        => $this->obj_lang->get('Shortcut'),
            '__token__'             => $this->obj_lang->get('Token'),
        );

        $_arr_typeMsg = array(
            'require'   => $this->obj_lang->get('{:attr} require'),
            'length'    => $this->obj_lang->get('Size of {:attr} must be {:rule}'),
            'max'       => $this->obj_lang->get('Max size of {:attr} must be {:rule}'),
            'confirm'   => $this->obj_lang->get('{:attr} out of accord with {:confirm}'),
            'token'     => $this->obj_lang->get('Form token is incorrect'),
        );

        $_arr_formatMsg = array(
            'email'     => $this->obj_lang->get('{:attr} not a valid email address'),
        );

        $this->setAttrName($_arr_attrName);
        $this->setTypeMsg($_arr_typeMsg);
        $this->setFormatMsg($_arr_formatMsg);
    }
}
