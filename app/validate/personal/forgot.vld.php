<?php
/*-----------------------------------------------------------------
！！！！警告！！！！
以下为系统文件，请勿修改
-----------------------------------------------------------------*/

namespace app\validate\personal;

use ginkgo\Validate;

// 不能非法包含或直接执行
if (!defined('IN_GINKGO')) {
    return 'Access denied';
}

/*-------------管理员模型-------------*/
class Forgot extends Validate {

    protected $rule     = array(
        'user_name' => array(
            'require' => true,
        ),
        'user_pass_new' => array(
            'require' => true,
        ),
        'user_pass_confirm'=> array(
            'confirm' => 'user_pass_new',
        ),
        'user_sec_answ' => array(
            'require' => true,
        ),
        'captcha_mail' => array(
            'length'    => '4,4',
            'format'    => 'alpha_number',
            'captcha'   => 'captcha_mail',
        ),
        'captcha_secqa' => array(
            'length'    => '4,4',
            'format'    => 'alpha_number',
            'captcha'   => 'captcha_secqa',
        ),
        '__token__' => array(
            'require' => true,
            'token'   => true,
        ),
    );

    protected $scene = array(
        'by_mail' => array(
            'user_name',
            'captcha_mail',
            '__token__',
        ),
        'by_secqa' => array(
            'user_name',
            'user_pass_new',
            'user_pass_confirm',
            'user_sec_answ',
            'captcha_secqa',
            '__token__',
        ),
    );


    function v_init() { //构造函数

        $_arr_attrName = array(
            'user_name'     => $this->obj_lang->get('Username'),
            'captcha_mail'  => $this->obj_lang->get('Captcha'),
            '__token__'     => $this->obj_lang->get('Token'),
        );

        $_arr_typeMsg = array(
            'require'   => $this->obj_lang->get('{:attr} require'),
            'length'    => $this->obj_lang->get('Size of {:attr} must be {:rule}'),
            'token'     => $this->obj_lang->get('Form token is incorrect'),
            'captcha'   => $this->obj_lang->get('Captcha is incorrect'),
        );

        $_arr_formatMsg = array(
            'alpha_number'  => $this->obj_lang->get('{:attr} must be alpha-numeric'),
        );

        $this->setAttrName($_arr_attrName);
        $this->setTypeMsg($_arr_typeMsg);
        $this->setFormatMsg($_arr_formatMsg);
    }
}
