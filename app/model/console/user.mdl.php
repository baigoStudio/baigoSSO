<?php
/*-----------------------------------------------------------------
！！！！警告！！！！
以下为系统文件，请勿修改
-----------------------------------------------------------------*/

namespace app\model\console;

use app\model\User as User_Base;
use ginkgo\Func;
use ginkgo\Arrays;

// 不能非法包含或直接执行
if (!defined('IN_GINKGO')) {
    return 'Access denied';
}

/*-------------用户模型-------------*/
class User extends User_Base {

    public $inputSubmit;
    public $inputDelete;
    public $inputStatus;


    function login() {
        $_tm_timeLogin  = GK_NOW;

        if (isset($this->inputSubmit['user_ip']) && !Func::isEmpty($this->inputSubmit['user_ip'])) {
            $_str_userIp = $this->inputSubmit['user_ip'];
        } else {
            $_str_userIp = $this->obj_request->ip();
        }

        $_arr_userData = array(
            'user_time_login'   => $_tm_timeLogin,
            'user_ip'           => $_str_userIp,
        );

        $_num_count = $this->where('user_id', '=', $this->inputSubmit['user_id'])->update($_arr_userData); //更新数据

        if ($_num_count > 0) {
            $_str_rcode = 'y010103';
            $_str_msg   = 'Update user successfully';
        } else {
            $_str_rcode = 'x010103';
            $_str_msg   = 'Did not make any changes';
        }

        return array(
            'rcode'     => $_str_rcode, //成功
            'msg'       => $_str_msg,
        );
    }


    /** 更新状态
     * status function.
     *
     * @access public
     * @param mixed $str_status
     * @return void
     */
    function status() {
        $_arr_userUpdate = array(
            'user_status' => $this->inputStatus['act'],
        );

        $_num_count     = $this->where('user_id', 'IN', $this->inputStatus['user_ids'], 'user_ids')->update($_arr_userUpdate); //更新数据

        //如影响行数大于0则返回成功
        if ($_num_count > 0) {
            $_str_rcode = 'y010103'; //成功
            $_str_msg   = 'Successfully updated {:count} users';
        } else {
            $_str_rcode = 'x010103'; //失败
            $_str_msg   = 'Did not make any changes';
        }

        return array(
            'count' => $_num_count,
            'rcode' => $_str_rcode,
            'msg'   => $_str_msg,
        );
    }


     /** 删除
     * delete function.
     *
     * @access public
     * @param mixed $_arr_inputDelete
     * @return void
     */
    function delete() {
        $_num_count     = $this->where('user_id', 'IN', $this->inputDelete['user_ids'], 'user_ids')->delete(); //更新数据

        //如车影响行数小于0则返回错误
        if ($_num_count > 0) {
            $_str_rcode = 'y010104'; //成功
            $_str_msg   = 'Successfully deleted {:count} users';
        } else {
            $_str_rcode = 'x010104'; //失败
            $_str_msg   = 'No user have been deleted';
        }

        return array(
            'count' => $_num_count,
            'rcode' => $_str_rcode,
            'msg'   => $_str_msg,
        );
    }


    /** 表单验证
     * inputSubmit function.
     *
     * @access public
     * @return void
     */
    function inputSubmit() {
        $_arr_inputParam = array(
            'user_id'       => array('int', 0),
            'user_name'     => array('txt', ''),
            'user_pass'     => array('txt', ''),
            'user_mail'     => array('txt', ''),
            'user_note'     => array('txt', ''),
            'user_status'   => array('txt', ''),
            'user_nick'     => array('txt', ''),
            'user_contact'  => array('arr', array()),
            'user_extend'   => array('arr', array()),
            '__token__'     => array('txt', ''),
        );

        $_arr_inputSubmit = $this->obj_request->post($_arr_inputParam);

        $_arr_remove = array();

        if ($_arr_inputSubmit['user_id'] > 0) {
            $_arr_remove = array('user_name', 'user_pass');
        }

        $_mix_vld = $this->validate($_arr_inputSubmit, '', 'submit', '', $_arr_remove);

        if ($_mix_vld !== true) {
            return array(
                'rcode' => 'x010201',
                'msg'   => end($_mix_vld),
            );
        }

        if ($_arr_inputSubmit['user_id'] > 0) {
            $_arr_userRow = $this->check($_arr_inputSubmit['user_id']);

            if ($_arr_userRow['rcode'] != 'y010102') {
                return $_arr_userRow;
            }
        } else {
            //检验用户名是否重复
            $_arr_userRow = $this->check($_arr_inputSubmit['user_name'], 'user_name');

            if ($_arr_userRow['rcode'] == 'y010102') {
                return array(
                    'rcode' => 'x010404',
                    'msg'   => 'User already exists',
                );
            }
        }

        $_arr_inputSubmit['rcode'] = 'y010201';

        $this->inputSubmit = $_arr_inputSubmit;

        return $_arr_inputSubmit;
    }


    /** 选择
     * inputStatus function.
     *
     * @access public
     * @return void
     */
    function inputStatus() {
        $_arr_inputParam = array(
            'user_ids'  => array('arr', array()),
            'act'       => array('txt', ''),
            '__token__' => array('txt', ''),
        );

        $_arr_inputStatus = $this->obj_request->post($_arr_inputParam);

        $_arr_inputStatus['user_ids'] = Arrays::filter($_arr_inputStatus['user_ids']);

        $_mix_vld = $this->validate($_arr_inputStatus, '', 'status');

        //print_r($_mix_vld);

        if ($_mix_vld !== true) {
            return array(
                'rcode' => 'x010201',
                'msg'   => end($_mix_vld),
            );
        }

        $_arr_inputStatus['rcode'] = 'y010201';

        $this->inputStatus = $_arr_inputStatus;

        return $_arr_inputStatus;
    }


    function inputDelete() {
        $_arr_inputParam = array(
            'user_ids' => array('arr', array()),
            '__token__' => array('txt', ''),
        );

        $_arr_inputDelete = $this->obj_request->post($_arr_inputParam);

        $_arr_inputDelete['user_ids'] = Arrays::filter($_arr_inputDelete['user_ids']);

        $_mix_vld = $this->validate($_arr_inputDelete, '', 'delete');

        if ($_mix_vld !== true) {
            return array(
                'rcode' => 'x010201',
                'msg'   => end($_mix_vld),
            );
        }

        $_arr_inputDelete['rcode'] = 'y010201';

        $this->inputDelete = $_arr_inputDelete;

        return $_arr_inputDelete;
    }
}
