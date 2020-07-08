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
class Sync extends Validate {

    protected $rule     = array(
        'user_str' => array(
            'require' => true,
        ),
        'user_by' => array(
            'in' => 'user_id,user_name,user_mail',
        ),
        'user_access_token' => array(
            'require' => true,
        ),
        'timestamp' => array(
            '>' => 0,
        ),
    );

    function v_init() { //构造函数

        $_arr_attrName = array(
            'user_str'          => $this->obj_lang->get('User ID, Username or Email'),
            'user_access_token' => $this->obj_lang->get('Access token'),
            'timestamp'         => $this->obj_lang->get('Timestamp', 'api.common'),
        );

        $_arr_typeMsg = array(
            'require'   => $this->obj_lang->get('{:attr} require'),
            'gt'        => $this->obj_lang->get('{:attr} require'),
        );

        $this->setAttrName($_arr_attrName);
        $this->setTypeMsg($_arr_typeMsg);
    }
}
