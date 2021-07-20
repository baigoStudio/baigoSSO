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
class Pm extends Validate {

    protected $rule     = array(
        'user_id' => array(
            '>' => 0,
        ),
        'user_access_token' => array(
            'require' => true,
        ),
        'pm_to' => array(
            '>' => 0,
            'format'  => 'int',
        ),
        'pm_from' => array(
            '>' => 0,
            'format'  => 'int',
        ),
        'pm_title' => array(
            'max' => 90,
        ),
        'pm_content'=> array(
            'length' => '1,900',
        ),
        'pm_type' => array(
            'require' => true,
        ),
        'pm_status' => array(
            'require' => true,
        ),
        'pm_id' => array(
            'require' => true,
        ),
        'pm_ids' => array(
            'require' => true,
        ),
        'pm_to_name' => array(
            'require' => true,
        ),
        'timestamp' => array(
            '>' => 0,
        ),
    );

    protected $scene = array(
        'lists' => array(
            'user_id',
            'user_access_token',
            'pm_type',
            'timestamp',
        ),
        'read' => array(
            'user_id',
            'user_access_token',
            'pm_id',
            'timestamp',
        ),
        'status' => array(
            'user_id',
            'user_access_token',
            'pm_status',
            'pm_ids',
            'timestamp',
        ),
        'send' => array(
            'user_id',
            'user_access_token',
            'pm_to_name',
            'pm_title',
            'pm_content',
            'timestamp',
        ),
        'send_db' => array(
            'pm_to',
            'pm_from',
            'pm_title',
            'pm_content',
        ),
        'delete' => array(
            'user_id',
            'user_access_token',
            'pm_ids',
            'timestamp',
        ),
        'check' => array(
            'user_id',
            'user_access_token',
            'timestamp',
        ),
    );

    function v_init() { //构造函数

        $_arr_attrName = array(
            'user_id'           => $this->obj_lang->get('User ID'),
            'user_access_token' => $this->obj_lang->get('Access token'),
            'pm_type'           => $this->obj_lang->get('Message type'),
            'pm_status'         => $this->obj_lang->get('Message status'),
            'pm_id'             => $this->obj_lang->get('ID'),
            'pm_ids'            => $this->obj_lang->get('Message ID'),
            'pm_to'             => $this->obj_lang->get('Recipient'),
            'pm_frome'          => $this->obj_lang->get('Sender'),
            'pm_to_name'        => $this->obj_lang->get('Recipient'),
            'pm_title'          => $this->obj_lang->get('Title'),
            'pm_content'        => $this->obj_lang->get('Content'),
            'timestamp'         => $this->obj_lang->get('Timestamp', 'api.common'),
        );

        $_arr_typeMsg = array(
            'require'   => $this->obj_lang->get('{:attr} require'),
            'length '   => $this->obj_lang->get('Size of {:attr} must be {:rule}'),
            'max'       => $this->obj_lang->get('Max size of {:attr} must be {:rule}'),
            'gt'        => $this->obj_lang->get('{:attr} require'),
        );

        $_arr_formatMsg = array(
            'int' => $this->obj_lang->get('{:attr} must be integer'),
        );

        $this->setAttrName($_arr_attrName);
        $this->setTypeMsg($_arr_typeMsg);
        $this->setFormatMsg($_arr_formatMsg);
    }
}
