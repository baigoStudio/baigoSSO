<?php
/*-----------------------------------------------------------------
！！！！警告！！！！
以下为系统文件，请勿修改
-----------------------------------------------------------------*/

namespace app\validate\console;

use ginkgo\Validate;
use ginkgo\Loader;

// 不能非法包含或直接执行
defined('IN_GINKGO') or exit('Access denied');

/*-------------管理员模型-------------*/
class Plugin extends Validate {

    protected $rule     = array(
        'plugin_dir' => array(
            'require' => true,
        ),
        'plugin_dirs' => array(
            'require' => true,
        ),
        '__token__' => array(
            'require' => true,
            'token'   => true,
        ),
    );

    protected $scene = array(
        'common' => array(
            'plugin_dir',
            '__token__',
        ),
        'uninstall' => array(
            'plugin_dirs',
            '__token__',
        ),
    );

    function v_init() { //构造函数

        $_arr_attrName = array(
            'plugin_dir'    => $this->obj_lang->get('Directory'),
            'plugin_dirs'   => $this->obj_lang->get('Plugin'),
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
