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
class Login extends Validate {

    protected $rule = array(
        'admin_name' => array(
            'length' => '1,30',
            'format' => 'alpha_dash',
        ),
        'admin_pass' => array(
            'require' => true,
        ),
        'captcha' => array(
            'length'    => '4,4',
            'format'    => 'alpha_number',
            'captcha'   => 'console_login',
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
            'captcha',
            '__token__',
        ),
    );

    function v_init() { //构造函数

        $_arr_attrName = array(
            'admin_name'    => $this->obj_lang->get('Username'),
            'admin_pass'    => $this->obj_lang->get('Password'),
            'captcha'       => $this->obj_lang->get('Captcha'),
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
            'alpha_dash'    => $this->obj_lang->get('{:attr} must be alpha-numeric, dash, underscore'),
        );

        $this->setAttrName($_arr_attrName);
        $this->setTypeMsg($_arr_typeMsg);
        $this->setFormatMsg($_arr_formatMsg);
    }
}
