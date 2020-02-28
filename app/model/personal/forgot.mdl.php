<?php
/*-----------------------------------------------------------------
！！！！警告！！！！
以下为系统文件，请勿修改
-----------------------------------------------------------------*/

namespace app\model\personal;

use ginkgo\Request;
use ginkgo\Loader;

//不能非法包含或直接执行
defined('IN_GINKGO') or exit('Access denied');

/*-------------管理员模型-------------*/
class Forgot {

    public $inputByMail;
    public $inputBySecqa;

    function __construct() {
        $this->obj_request = Request::instance();

        $this->vld_forgot  = Loader::validate('Forgot');
    }

    /** 通过邮件找回密码, 输入验证
     * inputByMail function.
     *
     * @access public
     * @return void
     */
    function inputByMail() {
        $_arr_inputParam = array(
            'user_name'     => array('txt', ''),
            'captcha_mail'  => array('txt', ''),
            '__token__'     => array('txt', ''),
        );

        $_arr_inputByMail = $this->obj_request->post($_arr_inputParam);

        $_is_vld = $this->vld_forgot->scene('by_mail')->verify($_arr_inputByMail);

        if ($_is_vld !== true) {
            $_arr_message = $this->vld_forgot->getMessage();
            return array(
                'rcode' => 'x010201',
                'msg'   => end($_arr_message),
            );
        }

        $_arr_inputByMail['rcode'] = 'y010201';

        $this->inputByMail = $_arr_inputByMail;

        return $_arr_inputByMail;
    }


    /** 通过密保问题找回密码, 输入验证
     * inputBySecqa function.
     *
     * @access public
     * @return void
     */
    function inputBySecqa() {
        $_arr_inputParam = array(
            'user_name'            => array('txt', ''),
            'user_pass_new'        => array('txt', ''),
            'user_pass_confirm'    => array('txt', ''),
            'user_sec_answ'        => array('arr', array()),
            'captcha_secqa'        => array('txt', ''),
            '__token__'            => array('txt', ''),
        );

        $_arr_inputBySecqa = $this->obj_request->post($_arr_inputParam);

        $_is_vld = $this->vld_forgot->scene('by_secqa')->verify($_arr_inputBySecqa);

        if ($_is_vld !== true) {
            $_arr_message = $this->vld_forgot->getMessage();
            return array(
                'rcode' => 'x010201',
                'msg'   => end($_arr_message),
            );
        }

        $_arr_inputBySecqa['rcode'] = 'y010201';

        $this->inputBySecqa = $_arr_inputBySecqa;

        return $_arr_inputBySecqa;
    }
}
