<?php
/*-----------------------------------------------------------------
！！！！警告！！！！
以下为系统文件，请勿修改
-----------------------------------------------------------------*/

namespace app\model\api;

use app\model\Admin as Admin_Base;
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
            'admin_time_login'  => GK_NOW,
            'admin_ip'          => $this->obj_request->ip(),
            'admin_type'        => 'super',
            'admin_status'      => 'enable',
            'admin_note'        => $this->inputSubmit['admin_name'],
            'admin_nick'        => $this->inputSubmit['admin_name'],
            'admin_time'        => GK_NOW,
        );

        $_mix_vld = $this->validate($_arr_adminData, '', 'submit_db');

        if ($_mix_vld !== true) {
            return array(
                'admin_id'  => $this->inputSubmit['admin_id'],
                'rcode'     => 'x020201',
                'msg'       => end($_mix_vld),
            );
        }

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

            $_num_count = $this->where('admin_id', '=', $_num_adminId)->update($_arr_adminData);

            if ($_num_count > 0) {
                $_str_rcode = 'y020101'; //更新成功
                $_str_msg   = 'Update administrator successfully';
            } else {
                $_str_rcode = 'y020101'; //更新成功
                $_str_msg   = 'Did not make any changes';
            }
        }

        return array(
            'admin_id'      => $_num_adminId,
            'admin_name'    => $this->inputSubmit['admin_name'],
            'admin_status'  => 'enable',
            'rcode'         => $_str_rcode,
            'msg'           => $_str_msg,
        );
    }




    /** 创建、编辑表单验证
     * inputSubmit function.
     *
     * @access public
     * @return void
     */
    function inputSubmit($arr_data) {
        $_arr_inputParam = array(
            'admin_name'    => array('txt', ''),
            'admin_pass'    => array('txt', ''),
            'admin_mail'    => array('txt', ''),
            'timestamp'     => array('int', 0),
        );

        $_arr_inputSubmit = $this->obj_request->fillParam($arr_data, $_arr_inputParam);

        $_mix_vld = $this->validate($_arr_inputSubmit, '', 'submit');

        if ($_mix_vld !== true) {
            return array(
                'rcode' => 'x020201',
                'msg'   => end($_mix_vld),
            );
        }

        $_arr_inputSubmit['rcode'] = 'y020201';

        $this->inputSubmit = $_arr_inputSubmit;

        return $_arr_inputSubmit;
    }
}
