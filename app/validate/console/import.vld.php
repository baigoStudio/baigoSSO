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
class Import extends Validate {

    protected $rule     = array(
        'charset' => array(
            'require' => true,
        ),
        '__token__' => array(
            'require' => true,
            'token'   => true,
        ),
    );

    protected $scene = array(
        'submit' => array(
            'charset',
            '__token__',
        ),
        'common' => array(
            '__token__',
        ),
    );

    function v_init() { //构造函数

        $_arr_attrName = array(
            'charset'       => $this->obj_lang->get('Charset'),
            '__token__'     => $this->obj_lang->get('Token'),
        );

        $_arr_typeMsg = array(
            'require'   => $this->obj_lang->get('{:attr} require'),
            'token'     => $this->obj_lang->get('Form token is incorrect'),
        );

        $this->setAttrName($_arr_attrName);
        $this->setTypeMsg($_arr_typeMsg);
    }
}
