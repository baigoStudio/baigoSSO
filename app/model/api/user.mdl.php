<?php
/*-----------------------------------------------------------------
！！！！警告！！！！
以下为系统文件，请勿修改
-----------------------------------------------------------------*/
namespace app\model\api;

use app\model\User as User_Base;
use ginkgo\Json;
use ginkgo\Func;

// 不能非法包含或直接执行
defined('IN_GINKGO') or exit('Access denied');

/*-------------用户模型-------------*/
class User extends User_Base {

    public $inputEdit;

    function readBase($mix_user, $str_by = 'user_id', $num_notId = 0) {
        $_arr_select = array(
            'user_id',
            'user_name',
            'user_mail',
            'user_contact',
            'user_extend',
            'user_nick',
            'user_status',
            'user_time',
            'user_time_login',
            'user_ip',
            'user_sec_ques',
        );

        $_arr_userRow = $this->read($mix_user, $str_by, $num_notId, $_arr_select);

        return $_arr_userRow;
    }


    function edit() {
        $_arr_userData = array();

        if (isset($this->inputEdit['user_pass']) && !Func::isEmpty($this->inputEdit['user_pass'])) { //如果 新密码 为空，则不修改
            $_arr_userData['user_pass']    = $this->inputEdit['user_pass'];
            $_arr_userData['user_rand']    = $this->inputEdit['user_rand'];
        }

        if (isset($this->inputEdit['user_mail_new']) && $this->inputEdit['user_mail_new']) { //如果 新邮箱 为空，则不修改
            $_arr_userData['user_mail']    = $this->inputEdit['user_mail_new'];
        }

        if (isset($this->inputEdit['user_nick']) && $this->inputEdit['user_nick']) { //如果 昵称 为空，则不修改
            $_arr_userData['user_nick']    = $this->inputEdit['user_nick'];
        }

        if (isset($this->inputEdit['user_contact'])) { //如果 联系方式 为空，则不修改
            $_arr_userData['user_contact'] = $this->inputEdit['user_contact'];
        }

        if (isset($this->inputEdit['user_extend'])) { //如果 扩展字段 为空，则不修改
            $_arr_userData['user_extend']  = $this->inputEdit['user_extend'];
        }

        $_mix_vld = $this->validate($_arr_userData, '', 'edit_db');

        if ($_mix_vld !== true) {
            return array(
                'user_id'   => $this->inputEdit['user_id'],
                'rcode'     => 'x010201',
                'msg'       => end($_mix_vld),
            );
        }

        $_arr_userData['user_contact']    = Json::encode($_arr_userData['user_contact']);
        $_arr_userData['user_extend']     = Json::encode($_arr_userData['user_extend']);

        $_num_count     = $this->where('user_id', '=', $this->inputEdit['user_id'])->update($_arr_userData); //更新数据

        if ($_num_count > 0) {
            $_str_rcode = 'y010103'; //更新成功
            $_str_msg   = 'Update user successfully';
        } else {
            $_str_rcode = 'x010103';
            $_str_msg   = 'Did not make any changes';
        }

        unset($_arr_userData['user_pass']);

        $_arr_userReturn = $this->rowProcess($_arr_userData);

        $_arr_userReturn['user_id'] = $this->inputEdit['user_id'];
        $_arr_userReturn['rcode']   = $_str_rcode;
        $_arr_userReturn['msg']     = $_str_msg;

        return $_arr_userReturn;
    }


    function inputRead($arr_data) {
        $_arr_inputParam = array(
            'timestamp'  => array('int', 0),
        );

        $_arr_inputRead  = $this->obj_request->fillParam($arr_data, $_arr_inputParam);

        $_arr_inputUserCommon  = $this->inputUserCommon($arr_data);

        $_arr_inputRead  = array_replace_recursive($_arr_inputRead, $_arr_inputUserCommon);

        $_mix_vld = $this->validate($_arr_inputRead, '', 'read');

        if ($_mix_vld !== true) {
            return array(
                'rcode' => 'x010201',
                'msg'   => end($_mix_vld),
            );
        }

        $_arr_inputRead['rcode'] = 'y010201';

        $this->inputRead = $_arr_inputRead;

        return $_arr_inputRead;
    }


    function inputEdit($arr_data) {
        $_arr_inputParam = array(
            'user_pass'     => array('txt', ''),
            'user_nick'     => array('txt', ''),
            'user_mail_new' => array('txt', ''),
            'user_contact'  => array('arr', array()),
            'user_extend'   => array('arr', array()),
            'timestamp'     => array('int', 0),
        );

        $_arr_inputEdit  = $this->obj_request->fillParam($arr_data, $_arr_inputParam);

        $_arr_inputUserCommon  = $this->inputUserCommon($arr_data);

        $_arr_inputEdit  = array_replace_recursive($_arr_inputEdit, $_arr_inputUserCommon);

        $_mix_vld = $this->validate($_arr_inputEdit, '', 'edit');

        if ($_mix_vld !== true) {
            return array(
                'rcode' => 'x010201',
                'msg'   => end($_mix_vld),
            );
        }

        $_arr_inputEdit['rcode'] = 'y010201';

        $this->inputEdit = $_arr_inputEdit;

        return $_arr_inputEdit;
    }
}

