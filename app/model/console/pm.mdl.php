<?php
/*-----------------------------------------------------------------
！！！！警告！！！！
以下为系统文件，请勿修改
-----------------------------------------------------------------*/
namespace app\model\console;

use app\model\Pm as Pm_Base;
use ginkgo\Func;
use ginkgo\Arrays;

// 不能非法包含或直接执行
if (!defined('IN_GINKGO')) {
    return 'Access denied';
}

/*-------------短消息模型-------------*/
class Pm extends Pm_Base {

    public $inputBulk;
    public $inputDelete;
    public $inputStatus;

    /** 短消息创建、编辑提交
     * send function.
     *
     * @access public
     * @param mixed $num_pmTo
     * @param mixed $num_pmFrom
     * @return void
     */
    function bulk() {
        $_arr_pmData = array(
            'pm_to'        => $this->inputBulk['pm_to'],
            'pm_from'      => -1,
            'pm_title'     => $this->inputBulk['pm_title'],
            'pm_content'   => $this->inputBulk['pm_content'],
            'pm_type'      => 'in',
            'pm_status'    => 'wait',
            'pm_time'      => GK_NOW,
        );

        if (Func::isEmpty($_arr_pmData['pm_title'])) {
            $_arr_pmData['pm_title'] = mb_substr($_arr_pmData['pm_content'], 0, 30);
        }

        $_mix_vld = $this->validate($_arr_pmData, '', 'submit_db');

        if ($_mix_vld !== true) {
            return array(
                'rcode' => 'x110201',
                'msg'   => end($_mix_vld),
            );
        }

        $_num_pmId   = $this->insert($_arr_pmData);

        if ($_num_pmId > 0) {
            $_str_rcode = 'y110101'; //更新成功
            $_str_msg   = 'Send message successfully';
        } else {
            $_str_rcode = 'x110101'; //更新成功
            $_str_msg   = 'Send message failed';
        }

        return array(
            'pm_id' => $_num_pmId,
            'rcode' => $_str_rcode, //成功
            'msg'   => $_str_msg,
        );
    }


    /** 创建、编辑表单验证
     * inputBulk function.
     *
     * @access public
     * @return void
     */
    function inputBulk() {
        $_arr_inputParam = array(
            'pm_title'          => array('txt', ''),
            'pm_content'        => array('txt', ''),
            'pm_bulk_type'      => array('txt', ''),
            'pm_to_users'       => array('txt', ''),
            'pm_to_key_name'    => array('txt', ''),
            'pm_to_key_mail'    => array('txt', ''),
            'pm_to_min_id'      => array('int', 0),
            'pm_to_max_id'      => array('int', 0),
            'pm_to_begin_reg'   => array('txt', ''),
            'pm_to_end_reg'     => array('txt', ''),
            'pm_to_begin_login' => array('txt', ''),
            'pm_to_end_login'   => array('txt', ''),
            '__token__'         => array('txt', ''),
        );

        $_arr_inputBulk = $this->obj_request->post($_arr_inputParam);

        //print_r($this->inputBulk);

        $_mix_vld = $this->validate($_arr_inputBulk, '', $_arr_inputBulk['pm_bulk_type']);

        if ($_mix_vld !== true) {
            return array(
                'rcode' => 'x110201',
                'msg'   => end($_mix_vld),
            );
        }

        $_arr_inputBulk['rcode'] = 'y110201';

        $this->inputBulk = $_arr_inputBulk;

        return $_arr_inputBulk;
    }


    /** 选择短消息
     * inputStatus function.
     *
     * @access public
     * @return void
     */
    function inputStatus() {
        $_arr_inputParam = array(
            'pm_ids'    => array('arr', array()),
            'act'       => array('txt', ''),
            '__token__' => array('txt', ''),
        );

        $_arr_inputStatus = $this->obj_request->post($_arr_inputParam);

        $_arr_inputStatus['pm_ids'] = Arrays::filter($_arr_inputStatus['pm_ids']);

        $_mix_vld = $this->validate($_arr_inputStatus, '', 'status');

        if ($_mix_vld !== true) {
            return array(
                'rcode' => 'x110201',
                'msg'   => end($_mix_vld),
            );
        }

        $_arr_inputStatus['rcode'] = 'y110201';

        $this->inputStatus = $_arr_inputStatus;

        return $_arr_inputStatus;
    }


    function inputDelete() {
        $_arr_inputParam = array(
            'pm_ids'    => array('arr', array()),
            '__token__' => array('txt', ''),
        );

        $_arr_inputDelete = $this->obj_request->post($_arr_inputParam);

        $_arr_inputDelete['pm_ids'] = Arrays::filter($this->inputDelete['pm_ids']);

        $_mix_vld = $this->validate($_arr_inputDelete, '', 'delete');

        if ($_mix_vld !== true) {
            return array(
                'rcode' => 'x110201',
                'msg'   => end($_mix_vld),
            );
        }

        $_arr_inputDelete['rcode'] = 'y110201';

        $this->inputDelete = $_arr_inputDelete;

        return $_arr_inputDelete;
    }
}
