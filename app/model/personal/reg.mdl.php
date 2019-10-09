<?php
/*-----------------------------------------------------------------
！！！！警告！！！！
以下为系统文件，请勿修改
-----------------------------------------------------------------*/

namespace app\model\personal;

use ginkgo\Json;
use ginkgo\Request;
use ginkgo\Loader;

//不能非法包含或直接执行
defined('IN_GINKGO') or exit('Access denied');

/*-------------管理员模型-------------*/
class Reg {

    public $inputNomail;

    function __construct() {
        $this->obj_request = Request::instance();

        $this->vld_reg     = Loader::validate('Reg');
    }

    /** 通过邮件找回密码, 输入验证
     * inputNomail function.
     *
     * @access public
     * @return void
     */
    function inputNomail() {
        $_arr_inputParam = array(
            'user_name' => array('txt', ''),
            'captcha'   => array('txt', ''),
            '__token__' => array('txt', ''),
        );

        $_arr_inputNomail = $this->obj_request->post($_arr_inputParam);

        $_is_vld = $this->vld_reg->scene('by_mail')->verify($_arr_inputNomail);

        if ($_is_vld !== true) {
            $_arr_message = $this->vld_reg->getMessage();
            return array(
                'rcode' => 'x010201',
                'msg'   => end($_arr_message),
            );
        }

        $_arr_inputNomail['rcode'] = 'y010201';

        $this->inputNomail = $_arr_inputNomail;

        return $_arr_inputNomail;
    }
}
