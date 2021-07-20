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
class Sync extends Validate {

    protected $rule     = array(
        'user_id' => array(
            '>' => 0,
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
            'user_id'           => $this->obj_lang->get('User ID'),
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
