<?php
/*-----------------------------------------------------------------
！！！！警告！！！！
以下为系统文件，请勿修改
-----------------------------------------------------------------*/

namespace app\validate\personal;

use ginkgo\Validate;
use ginkgo\Config;

// 不能非法包含或直接执行
if (!defined('IN_GINKGO')) {
    return 'Access denied';
}

/*-------------管理员模型-------------*/
class Reg extends Validate {

    function v_init() { //构造函数
        parent::v_init();

        $_arr_rule =  array(
            'user_name' => array(
                'require' => true,
            ),
            'user_pass' => array(
                'require' => true,
            ),
            'user_pass_confirm'=> array(
                'confirm' => 'user_pass',
            ),
            'user_mail' => array(
                'max'    => 300,
                'format' => 'email',
            ),
            'captcha' => array(
                'length'    => '4,4',
                'format'    => 'alpha_number',
                'captcha'   => true,
            ),
            '__token__' => array(
                'require' => true,
                'token'   => true,
            ),
        );

        $_arr_configReg = Config::get('reg', 'var_extra');

        if (isset($_arr_configReg['reg_needmail']) && $_arr_configReg['reg_needmail'] === 'on') {
            $_arr_rule['user_mail'] = array(
                'length'    => '1,300',
                'format'    => 'email',
            );
        }

        $_arr_scene = array(
            'reg' => array(
                'user_name',
                'user_pass',
                'user_pass_confirm',
                'user_mail',
                'captcha',
                '__token__',
            ),
            'reg_db' => array(
                'user_name',
                'user_pass',
                'user_mail',
            ),
            'nomail' => array(
                'user_name',
                'captcha',
                '__token__',
            ),
        );

        $_arr_attrName = array(
            'user_name'         => $this->obj_lang->get('Username'),
            'user_pass'         => $this->obj_lang->get('Password'),
            'user_pass_confirm' => $this->obj_lang->get('Confirm password'),
            'user_mail'         => $this->obj_lang->get('Mailbox'),
            'captcha'           => $this->obj_lang->get('Captcha'),
            '__token__'         => $this->obj_lang->get('Token'),
        );

        $_arr_typeMsg = array(
            'require'   => $this->obj_lang->get('{:attr} require'),
            'length'    => $this->obj_lang->get('Size of {:attr} must be {:rule}'),
            'token'     => $this->obj_lang->get('Form token is incorrect'),
            'confirm'   => $this->obj_lang->get('{:attr} out of accord with {:confirm}'),
            'captcha'   => $this->obj_lang->get('Captcha is incorrect'),
        );

        $_arr_formatMsg = array(
            'alpha_dash'    => $this->obj_lang->get('{:attr} must be alpha-numeric, dash, underscore'),
            'alpha_number'  => $this->obj_lang->get('{:attr} must be alpha-numeric'),
            'email'         => $this->obj_lang->get('{:attr} not a valid email address'),
        );

        $this->rule($_arr_rule);
        $this->setScene($_arr_scene);
        $this->setAttrName($_arr_attrName);
        $this->setTypeMsg($_arr_typeMsg);
        $this->setFormatMsg($_arr_formatMsg);
    }
}
