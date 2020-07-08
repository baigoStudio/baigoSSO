<?php
/*-----------------------------------------------------------------
！！！！警告！！！！
以下为系统文件，请勿修改
-----------------------------------------------------------------*/

namespace app\validate\personal;

use ginkgo\Validate;

// 不能非法包含或直接执行
defined('IN_GINKGO') or exit('Access denied');

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


    function byMail($arr_data) {
        //print_r($arr_data);

        $_arr_rule = array(
            'user_name' => array(
                'require' => true,
            ),
            'captcha_mail' => array(
                'length' => '4,4',
                'format' => 'alpha_number',
            ),
            '__token__' => array(
                'require' => true,
                'token'   => true,
            ),
        );

        $this->rule($_arr_rule);

        $_arr_attrName = array(
            'user_name'         => $this->obj_lang->get('Username'),
            'user_pass_new'     => $this->obj_lang->get('New password'),
            'user_pass_confirm' => $this->obj_lang->get('Confirm password'),
            'user_sec_answ'     => $this->obj_lang->get('Answer'),
            'captcha_secqa'     => $this->obj_lang->get('Captcha'),
            'captcha_mail'      => $this->obj_lang->get('Captcha'),
            '__token__'         => $this->obj_lang->get('Token'),
        );

        $this->setAttrName($_arr_attrName);

        $_arr_typeMsg = array(
            'require'   => $this->obj_lang->get('{:attr} require'),
            'length'    => $this->obj_lang->get('Size of {:attr} must be {:rule}'),
            'confirm'   => $this->obj_lang->get('{:attr} out of accord with {:confirm}'),
            'token'     => $this->obj_lang->get('Form token is incorrect'),
        );

        $this->setTypeMsg($_arr_typeMsg);

        if (!$this->verify($arr_data)) {
            $_arr_message = $this->getMessage();

            $_arr_return = array(
                'rcode' => 'x010201',
                'msg'   => end($_arr_message),
            );

            return $_arr_return;
        }

        if (!$this->obj_captcha->verify($arr_data['captcha_mail'])) {
            return array(
                'rcode' => 'x030202',
                'msg'   => $this->obj_lang->get('Captcha is incorrect'),
            );
        }

        $_arr_return = array(
            'rcode' => 'y010201',
        );

        return $_arr_return;
    }


    function bySecqa($arr_data) {
        //print_r($arr_data);

        $_arr_rule = array(
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
            'captcha_secqa' => array(
                'length' => '4,4',
                'format' => 'alpha_number',
            ),
            '__token__' => array(
                'require' => true,
                'token'   => true,
            ),
        );

        $this->rule($_arr_rule);

        $_arr_attrName = array(
            'user_name'            => $this->obj_lang->get('Username'),
            'user_pass_new'        => $this->obj_lang->get('New password'),
            'user_pass_confirm'    => $this->obj_lang->get('Confirm password'),
            'user_sec_answ'        => $this->obj_lang->get('Answer'),
            'captcha_secqa'        => $this->obj_lang->get('Captcha'),
            '__token__'            => $this->obj_lang->get('Token'),
        );

        $this->setAttrName($_arr_attrName);

        $_arr_typeMsg = array(
            'require'   => $this->obj_lang->get('{:attr} require'),
            'length'    => $this->obj_lang->get('Size of {:attr} must be {:rule}'),
            'confirm'   => $this->obj_lang->get('{:attr} out of accord with {:confirm}'),
            'token'     => $this->obj_lang->get('Form token is incorrect'),
        );

        $this->setTypeMsg($_arr_typeMsg);

        if (!$this->verify($arr_data)) {
            $_arr_message = $this->getMessage();

            $_arr_return = array(
                'rcode' => 'x010201',
                'msg'   => end($_arr_message),
            );

            return $_arr_return;
        }

        if (!$this->obj_captcha->verify($arr_data['captcha_secqa'])) {
            return array(
                'rcode' => 'x030202',
                'msg'   => $this->obj_lang->get('Captcha is incorrect'),
            );
        }

        $_arr_return = array(
            'rcode' => 'y010201',
        );

        return $_arr_return;
    }
}
