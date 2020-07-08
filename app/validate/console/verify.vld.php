<?php
/*-----------------------------------------------------------------
！！！！警告！！！！
以下为系统文件，请勿修改
-----------------------------------------------------------------*/

namespace app\validate\console;

use ginkgo\Validate;

// 不能非法包含或直接执行
defined('IN_GINKGO') or exit('Access denied');

/*-------------管理员模型-------------*/
class Verify extends Validate {

    protected $rule     = array(
        'verify_ids' => array(
            'require' => true,
        ),
        'act' => array(
            'require' => true,
        ),
        '__token__' => array(
            'require' => true,
            'token'   => true,
        ),
    );

    protected $scene = array(
        'status' => array(
            'verify_ids',
            'act',
            '__token__',
        ),
        'delete' => array(
            'verify_ids',
            '__token__',
        ),
    );


    function v_init() { //构造函数

        $_arr_attrName = array(
            'verify_ids'    => $this->obj_lang->get('Log'),
            '__token__'     => $this->obj_lang->get('Token'),
        );

        $_arr_typeMsg = array(
            'require'   => $this->obj_lang->get('Choose at least one {:attr}'),
            'token'     => $this->obj_lang->get('Form token is incorrect'),
        );

        $this->setAttrName($_arr_attrName);
        $this->setTypeMsg($_arr_typeMsg);
    }
}
