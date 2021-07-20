<?php
/*-----------------------------------------------------------------
！！！！警告！！！！
以下为系统文件，请勿修改
-----------------------------------------------------------------*/
namespace app\model\api;

use app\model\Pm as Pm_Base;
use ginkgo\Func;
use ginkgo\Plugin;

// 不能非法包含或直接执行
if (!defined('IN_GINKGO')) {
    return 'Access denied';
}

/*-------------用户模型-------------*/
class Pm extends Pm_Base {

    public $inputRead;
    public $inputCheck;
    public $inputLists;

    function send() {
        $_arr_pmData = array(
            'pm_to'        => $this->inputSend['pm_to'],
            'pm_from'      => $this->inputSend['pm_from'],
            'pm_title'     => $this->inputSend['pm_title'],
            'pm_content'   => $this->inputSend['pm_content'],
            'pm_type'      => 'in',
            'pm_status'    => 'wait',
            'pm_time'      => GK_NOW,
        );

        if (Func::isEmpty($_arr_pmData['pm_title'])) {
            $_arr_pmData['pm_title'] = mb_substr($_arr_pmData['pm_content'], 0, 30);
        }

        $_arr_pmData    = Plugin::listen('filter_api_pm_send', $_arr_pmData); //编辑文章时触发

        $_mix_vld = $this->validate($_arr_pmData, '', 'send_db');

        if ($_mix_vld !== true) {
            return array(
                'pm_id' => 0,
                'rcode' => 'x110201',
                'msg'   => end($_mix_vld),
            );
        }

        $_num_pmId  = $this->insert($_arr_pmData);

        if ($_num_pmId > 0) {
            $_str_rcode = 'y110101'; //更新成功
            $_str_msg   = 'Send message successfully';
            //在发件箱保存副本
            $_arr_pmData['pm_send_id']  = $_num_pmId;
            $_arr_pmData['pm_type']     = 'out';
            $_arr_pmData['pm_status']   = 'read';

            $this->insert($_arr_pmData);
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


    /** api 注册表单验证
     * inputReg_api function.
     *
     * @access public
     * @return void
     */
    function inputRead($arr_data) {
        $_arr_inputParam = array(
            'user_id'           => array('int', 0),
            'user_access_token' => array('txt', ''),
            'pm_id'             => array('int', 0),
            'timestamp'         => array('int', 0),
        );

        $_arr_inputRead  = $this->obj_request->fillParam($arr_data, $_arr_inputParam);

        $_mix_vld = $this->validate($_arr_inputRead, '', 'read');

        if ($_mix_vld !== true) {
            return array(
                'rcode' => 'x110201',
                'msg'   => end($_mix_vld),
            );
        }

        $_arr_inputRead['rcode'] = 'y110201';

        $this->inputRead = $_arr_inputRead;

        return $_arr_inputRead;
    }


    /** api 登录表单验证
     * inputLists function.
     *
     * @access public
     * @return void
     */
    function inputLists($arr_data) {
        $_arr_inputParam = array(
            'user_id'           => array('int', 0),
            'user_access_token' => array('txt', ''),
            'pm_type'           => array('txt', ''),
            'pm_status'         => array('txt', ''),
            'pm_ids'            => array('arr', array()),
            'page'              => array('int', 1),
            'perpage'           => array('int', $this->configBase['site_perpage']),
            'key'               => array('txt', ''),
            'timestamp'         => array('int', 0),
        );

        $_arr_inputLists  = $this->obj_request->fillParam($arr_data, $_arr_inputParam);

        $_mix_vld = $this->validate($_arr_inputLists, '', 'lists');

        if ($_mix_vld !== true) {
            return array(
                'rcode' => 'x110201',
                'msg'   => end($_mix_vld),
            );
        }

        $_arr_inputLists['rcode'] = 'y110201';

        $this->inputLists = $_arr_inputLists;

        return $_arr_inputLists;
    }


    function inputSend($arr_data) {
        $_arr_inputParam = array(
            'user_id'           => array('int', 0),
            'user_access_token' => array('txt', ''),
            'pm_to_name'        => array('txt', ''),
            'pm_title'          => array('txt', ''),
            'pm_content'        => array('txt', ''),
            'timestamp'         => array('int', 0),
        );

        $_arr_inputSend   = $this->obj_request->fillParam($arr_data, $_arr_inputParam);

        $_mix_vld = $this->validate($_arr_inputSend, '', 'send');

        if ($_mix_vld !== true) {
            return array(
                'rcode' => 'x110201',
                'msg'   => end($_mix_vld),
            );
        }

        $_arr_inputSend['rcode'] = 'y110201';

        $this->inputSend = $_arr_inputSend;

        return $_arr_inputSend;
    }

    function inputCheck($arr_data) {
        $_arr_inputParam = array(
            'user_id'           => array('int', 0),
            'user_access_token' => array('txt', ''),
            'timestamp'         => array('int', 0),
        );

        $_arr_inputCheck  = $this->obj_request->fillParam($arr_data, $_arr_inputParam);

        $_mix_vld = $this->validate($_arr_inputCheck, '', 'check');

        if ($_mix_vld !== true) {
            return array(
                'rcode' => 'x110201',
                'msg'   => end($_mix_vld),
            );
        }

        $_arr_inputCheck['rcode'] = 'y110201';

        $this->inputCheck = $_arr_inputCheck;

        return $_arr_inputCheck;
    }


    function inputStatus($arr_data) {
        $_arr_inputParam = array(
            'user_id'           => array('int', 0),
            'user_access_token' => array('txt', ''),
            'pm_status'         => array('txt', ''),
            'pm_ids'            => array('arr', array()),
            'timestamp'         => array('int', 0),
        );

        $_arr_inputStatus   = $this->obj_request->fillParam($arr_data, $_arr_inputParam);

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


    function inputDelete($arr_data) {
        $_arr_inputParam = array(
            'user_id'           => array('int', 0),
            'user_access_token' => array('txt', ''),
            'pm_ids'            => array('arr', array()),
            'timestamp'         => array('int', 0),
        );

        $_arr_inputDelete   = $this->obj_request->fillParam($arr_data, $_arr_inputParam);

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
