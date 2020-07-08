<?php
/*-----------------------------------------------------------------
！！！！警告！！！！
以下为系统文件，请勿修改
-----------------------------------------------------------------*/

namespace app\ctrl\misc;

use ginkgo\Request;
use ginkgo\Session;
use ginkgo\Lang;
use ginkgo\Json;
use ginkgo\Captcha as CaptchaGen;

// 不能非法包含或直接执行
defined('IN_GINKGO') or exit('Access denied');

class Captcha {

    function __construct($param = array()) {
        $this->param = $param;
        $this->obj_captcha  = CaptchaGen::instance();
        $this->obj_request  = Request::instance();
    }

    public function index() {
        $_str_id = '';

        if (isset($this->param['id'])) {
            $_str_id = $this->obj_request->input($this->param['id'], 'str', '');
        }

        $this->obj_captcha->set();

        return $this->obj_captcha->create($_str_id);
    }

    public function check() {
        $_str_id = '';

        if (isset($this->param['id'])) {
            $_str_id = $this->obj_request->input($this->param['id'], 'str', '');
        }

        $_obj_lang     = Lang::instance();

        $_route        = $this->obj_request->route();

        $_obj_lang->range($_route['mod'] . '.' . $_route['ctrl']);
        $_str_current       = $_obj_lang->getCurrent();
        $_str_langPath      = GK_APP_LANG . $_str_current . DS . $_route['mod'] . DS . $_route['ctrl'] . GK_EXT_LANG;
        $_obj_lang->load($_str_langPath);

        $_str_captcha = strtolower($this->obj_request->get('captcha'));

        //print_r($_str_captcha);

        if ($this->obj_captcha->check($_str_captcha, $_str_id, false)) {
            $_arr_return = array(
                'msg'   => '',
            );
        } else {
            $_arr_return = array(
                'rcode' => 'x030202',
                'error' => $_obj_lang->get('Captcha is incorrect'),
            );
        }

        return Json::encode($_arr_return);
    }
}
