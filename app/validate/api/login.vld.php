<?php
/*-----------------------------------------------------------------
！！！！警告！！！！
以下为系统文件，请勿修改
-----------------------------------------------------------------*/

namespace app\validate\api;

use ginkgo\Validate;

// 不能非法包含或直接执行
if (!defined('IN_GINKGO')) {
    return 'Access denied';
}

/*-------------管理员模型-------------*/
class Login extends Validate {

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
        'user_ip' => array(
            'format' => 'ip',
        ),
        'timestamp' => array(
            '>' => 0,
        ),
    );

    protected $scene = array(
        'login' => array(
            'user_str',
            'user_by',
            'user_pass',
            'user_ip',
            'timestamp',
        ),
    );

    function v_init() { //构造函数

        $_arr_attrName = array(
            'user_str'      => $this->obj_lang->get('User ID, Username or Email'),
            'user_pass'     => $this->obj_lang->get('Password'),
            'user_ip'       => $this->obj_lang->get('IP Address'),
            'timestamp'     => $this->obj_lang->get('Timestamp', 'api.common'),
        );

        $_arr_typeMsg = array(
            'require'   => $this->obj_lang->get('{:attr} require'),
            'gt'        => $this->obj_lang->get('{:attr} require'),
        );

        $_arr_formatMsg = array(
            'ip'          => $this->obj_lang->get('{:attr} not a valid ip'),
        );

        $this->setAttrName($_arr_attrName);
        $this->setTypeMsg($_arr_typeMsg);
        $this->setFormatMsg($_arr_formatMsg);
    }
}
