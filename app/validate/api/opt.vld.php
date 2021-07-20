<?php
/*-----------------------------------------------------------------
！！！！警告！！！！
以下为系统文件，请勿修改
-----------------------------------------------------------------*/

namespace app\validate\api;

use app\validate\Opt as Opt_Base;
use ginkgo\Func;

// 不能非法包含或直接执行
if (!defined('IN_GINKGO')) {
    return 'Access denied';
}

/*-------------设置项模型-------------*/
class Opt extends Opt_Base {

    function v_init() { //构造函数
        parent::v_init();

        $_arr_rule     = array(
            'sign' => array(
                'require' => true,
            ),
            'code' => array(
                'require' => true,
            ),
            'secret' => array(
                'length' => '16,16'
            ),
            'timestamp' => array(
                '>' => 0,
            ),
        );

        $_arr_scene = array(
            'dbconfig' => array(
                'host',
                'port',
                'name',
                'user',
                'pass',
                'charset',
                'prefix',
                'timestamp',
            ),
            'security' => array(
                'secret',
                'timestamp',
            ),
            'timestamp' => array(
                'timestamp',
            ),
            'common' => array(
                'sign',
                'code',
                'key',
            ),
        );

        $_arr_attrName = array(
            'sign'       => $this->obj_lang->get('Signature', 'api.common'),
            'code'       => $this->obj_lang->get('Encrypted code', 'api.common'),
            'secret'     => $this->obj_lang->get('Secret code', 'api.common'),
            'timestamp'  => $this->obj_lang->get('Timestamp', 'api.common'),
        );

        $_arr_typeMsg = array(
            'length'    => $this->obj_lang->get('Size of {:attr} must be {:rule}'),
            'require'   => $this->obj_lang->get('{:attr} require'),
            'gt'        => $this->obj_lang->get('{:attr} require'),
        );

        $_arr_formatMsg = array(
            'int' => $this->obj_lang->get('{:attr} must be numeric'),
        );

        $this->rule($_arr_rule);
        $this->setScene($_arr_scene);
        $this->setAttrName($_arr_attrName);
        $this->setTypeMsg($_arr_typeMsg);
        $this->setFormatMsg($_arr_formatMsg);
    }
}
