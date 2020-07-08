<?php
/*-----------------------------------------------------------------
！！！！警告！！！！
以下为系统文件，请勿修改
-----------------------------------------------------------------*/
namespace app\model\personal;

use app\model\Verify as Verify_Base;

// 不能非法包含或直接执行
defined('IN_GINKGO') or exit('Access denied');

/*-------------验证模型-------------*/
class Verify extends Verify_Base {

    public $inputVerify;

    /** 失效
     * disabled function.
     *
     * @access public
     * @return void
     */
    function disabled() {
        $_arr_verifyUpdate = array(
            'verify_status'         => 'disabled',
            'verify_time_disabled'  => GK_NOW,
        );

        $_num_count = $this->where('verify_id', '=', $this->inputVerify['verify_id'])->update($_arr_verifyUpdate);

        //如影响行数大于0则返回成功
        if ($_num_count > 0) {
            $_str_rcode = 'y120103'; //成功
        } else {
            $_str_rcode = 'x120103'; //失败
        }

        return array(
            'rcode' => $_str_rcode,
        );
    }


    /** 表单验证
     * inputVerify function.
     *
     * @access public
     * @return void
     */
    function inputCommon() {
        $_arr_inputParam = array(
            'verify_id'     => array('int', ''),
            'verify_token'  => array('txt', ''),
            'captcha'       => array('txt', ''),
            '__token__'     => array('txt', ''),
        );

        $_arr_inputVerify = $this->obj_request->post($_arr_inputParam);

        $_mix_vld = $this->validate($_arr_inputVerify, '', 'common');

        if ($_mix_vld !== true) {
            return array(
                'rcode' => 'x120201',
                'msg'   => end($_mix_vld),
            );
        }

        $_arr_inputVerify['rcode'] = 'y120201';

        $this->inputVerify = $_arr_inputVerify;

        return $_arr_inputVerify;
    }


    /** 表单验证
     * inputVerify function.
     *
     * @access public
     * @return void
     */
    function inputPass() {
        $_arr_inputParam = array(
            'verify_id'         => array('int', ''),
            'verify_token'      => array('txt', ''),
            'user_pass_new'     => array('txt', ''),
            'user_pass_confirm' => array('txt', ''),
            'captcha'           => array('txt', ''),
            '__token__'         => array('txt', ''),
        );

        $_arr_inputVerify = $this->obj_request->post($_arr_inputParam);

        $_mix_vld = $this->validate($_arr_inputVerify, '', 'pass');

        if ($_mix_vld !== true) {
            return array(
                'rcode' => 'x120201',
                'msg'   => end($_mix_vld),
            );
        }

        $_arr_inputVerify['rcode'] = 'y120201';

        $this->inputVerify = $_arr_inputVerify;

        return $_arr_inputVerify;
    }
}
