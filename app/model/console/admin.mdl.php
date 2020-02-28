<?php
/*-----------------------------------------------------------------
！！！！警告！！！！
以下为系统文件，请勿修改
-----------------------------------------------------------------*/

namespace app\model\console;

use app\model\Admin as Admin_Base;
use ginkgo\Json;
use ginkgo\Func;

//不能非法包含或直接执行
defined('IN_GINKGO') or exit('Access denied');

/*-------------管理员模型-------------*/
class Admin extends Admin_Base {

    public $ip;
    public $inputSubmit;
    public $inputStatus;
    public $inputDelete;

    /** 管理员创建、编辑提交
     * submit function.
     *
     * @access public
     * @param string $str_adminPass (default: '')
     * @param string $str_adminRand (default: '')
     * @return void
     */
    function submit() {
        $_arr_adminRow  = $this->check($this->inputSubmit['admin_id']);

        $_arr_adminData = array(
            'admin_id'          => $this->inputSubmit['admin_id'],
            'admin_name'        => $this->inputSubmit['admin_name'],
            'admin_time'        => GK_NOW,
            'admin_time_login'  => GK_NOW,
            'admin_ip'          => $this->obj_request->ip(),
        );

        if (isset($this->inputSubmit['admin_type'])) {
            $_arr_adminData['admin_type'] = $this->inputSubmit['admin_type'];
        }

        if (isset($this->inputSubmit['admin_status'])) {
            $_arr_adminData['admin_status'] = $this->inputSubmit['admin_status'];
        }

        if (isset($this->inputSubmit['admin_note'])) {
            $_arr_adminData['admin_note'] = $this->inputSubmit['admin_note'];
        }

        if (isset($this->inputSubmit['admin_nick'])) {
            $_arr_adminData['admin_nick'] = $this->inputSubmit['admin_nick'];
        }

        if (isset($this->inputSubmit['admin_allow'])) {
            $_arr_adminData['admin_allow'] = $this->inputSubmit['admin_allow'];
        }

        if (isset($this->inputSubmit['admin_allow_profile'])) {
            $_arr_adminData['admin_allow_profile'] = $this->inputSubmit['admin_allow_profile'];
        }

        $_mix_vld = $this->validate($_arr_adminData, '', 'submit_db');

        if ($_mix_vld !== true) {
            return array(
                'admin_id'  => $this->inputSubmit['admin_id'],
                'rcode'     => 'x020201',
                'msg'       => end($_mix_vld),
            );
        }

        $_arr_adminData['admin_allow']            = Json::encode($_arr_adminData['admin_allow']);
        $_arr_adminData['admin_allow_profile']    = Json::encode($_arr_adminData['admin_allow_profile']);

        if ($_arr_adminRow['rcode'] == 'x020102') {
            $_num_adminId   = $this->insert($_arr_adminData);

            if ($_num_adminId > 0) {
                $_str_rcode = 'y020101'; //插入成功
                $_str_msg   = 'Add administrator successfully';
            } else {
                $_str_rcode = 'x020101'; //插入失败
                $_str_msg   = 'Add administrator failed';
            }
        } else {
            $_num_adminId   = $this->inputSubmit['admin_id'];

            unset($_arr_adminData['admin_id'], $_arr_adminData['admin_name']);

            $_num_count     = $this->where('admin_id', '=', $_num_adminId)->update($_arr_adminData); //更新数

            if ($_num_count > 0) {
                $_str_rcode = 'y020103'; //更新成功
                $_str_msg   = 'Update administrator successfully';
            } else {
                $_str_rcode = 'x020103'; //更新成功
                $_str_msg   = 'Did not make any changes';
            }
        }

        return array(
            'admin_id'   => $_num_adminId,
            'rcode'      => $_str_rcode,
            'msg'        => $_str_msg,
        );
    }


    /** 编辑状态
     * status function.
     *
     * @access public
     * @param mixed $str_status
     * @return void
     */
    function status() {
        $_arr_adminUpdate = array(
            'admin_status' => $this->inputStatus['act'],
        );

        $_num_count     = $this->where('admin_id', 'IN', $this->inputStatus['admin_ids'], 'admin_ids')->update($_arr_adminUpdate); //更新数据

        //如影响行数大于0则返回成功
        if ($_num_count > 0) {
            $_str_rcode = 'y020103'; //成功
            $_str_msg   = 'Successfully updated {:count} administrators';
        } else {
            $_str_rcode = 'x020103'; //失败
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
     * @return void
     */
    function delete() {
        $_num_count     = $this->where('admin_id', 'IN', $this->inputDelete['admin_ids'], 'admin_ids')->delete(); //更新数据

        //如车影响行数小于0则返回错误
        if ($_num_count > 0) {
            $_str_rcode = 'y020104'; //成功
            $_str_msg   = 'Successfully deleted {:count} administrators';
        } else {
            $_str_rcode = 'x020104'; //失败
            $_str_msg   = 'No administrator have been deleted';
        }

        return array(
            'count' => $_num_count,
            'rcode' => $_str_rcode,
            'msg'   => $_str_msg,
        );
    }


    /** 创建、编辑表单验证
     * inputSubmit function.
     *
     * @access public
     * @return void
     */
    function inputSubmit() {
        $_arr_inputParam = array(
            'admin_id'              => array('int', 0),
            'admin_name'            => array('txt', ''),
            'admin_pass'            => array('txt', ''),
            'admin_note'            => array('txt', ''),
            'admin_status'          => array('txt', ''),
            'admin_type'            => array('txt', ''),
            'admin_nick'            => array('txt', ''),
            'admin_allow'           => array('arr', array()),
            'admin_allow_profile'   => array('arr', array()),
            '__token__'             => array('txt', ''),
        );

        $_arr_inputSubmit = $this->obj_request->post($_arr_inputParam);

        $_arr_remove = array();

        if ($_arr_inputSubmit['admin_id'] > 0) {
            $_arr_remove = array('admin_name', 'admin_pass');
        }

        $_mix_vld = $this->validate($_arr_inputSubmit, '', 'submit', '', $_arr_remove);

        if ($_mix_vld !== true) {
            return array(
                'rcode' => 'x020201',
                'msg'   => end($_mix_vld),
            );
        }

        if ($_arr_inputSubmit['admin_id'] > 0) {
            $_arr_adminRow = $this->check($_arr_inputSubmit['admin_id']);

            if ($_arr_adminRow['rcode'] != 'y020102') {
                return $_arr_adminRow;
            }
        }

        $_arr_inputSubmit['rcode'] = 'y020201';

        $this->inputSubmit = $_arr_inputSubmit;

        return $_arr_inputSubmit;
    }


    /** 选择管理员
     * inputStatus function.
     *
     * @access public
     * @return void
     */
    function inputStatus() {
        $_arr_inputParam = array(
            'admin_ids' => array('arr', array()),
            'act'       => array('txt', ''),
            '__token__' => array('txt', ''),
        );

        $_arr_inputStatus = $this->obj_request->post($_arr_inputParam);

        $_arr_inputStatus['admin_ids'] = Func::arrayFilter($_arr_inputStatus['admin_ids']);

        $_mix_vld = $this->validate($_arr_inputStatus, '', 'status');

        if ($_mix_vld !== true) {
            return array(
                'rcode' => 'x020201',
                'msg'   => end($_mix_vld),
            );
        }

        $_arr_inputStatus['rcode'] = 'y020201';

        $this->inputStatus = $_arr_inputStatus;

        return $_arr_inputStatus;
    }


    function inputDelete() {
        $_arr_inputParam = array(
            'admin_ids' => array('arr', array()),
            '__token__' => array('txt', ''),
        );

        $_arr_inputDelete = $this->obj_request->post($_arr_inputParam);

        $_arr_inputDelete['admin_ids'] = Func::arrayFilter($_arr_inputDelete['admin_ids']);

        $_mix_vld = $this->validate($_arr_inputDelete, '', 'delete');

        if ($_mix_vld !== true) {
            return array(
                'rcode' => 'x020201',
                'msg'   => end($_mix_vld),
            );
        }

        $_arr_inputDelete['rcode'] = 'y020201';

        $this->inputDelete = $_arr_inputDelete;

        return $_arr_inputDelete;
    }
}
