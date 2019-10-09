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

//不能非法包含或直接执行
defined('IN_GINKGO') or exit('Access denied');

class Captcha {

    function __construct($arr_param = array()) {
        $this->obj_captcha = CaptchaGen::instance();
    }

    public function index() {
        $this->obj_captcha->set();

        return $this->obj_captcha->create();
    }

    public function check() {
        $_obj_request  = Request::instance();
        $_obj_lang     = Lang::instance();

        $_route        = $_obj_request->route();

        $_obj_lang->range($_route['mod'] . '.' . $_route['ctrl']);
        $_str_current       = $_obj_lang->getCurrent();
        $_str_langPath      = GK_APP_LANG . $_str_current . DS . $_route['mod'] . DS . $_route['ctrl'] . GK_EXT_LANG;
        $_obj_lang->load($_str_langPath);

        $_str_captcha = strtolower($_obj_request->get('captcha'));

        if ($this->obj_captcha->check($_str_captcha, '', false)) {
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
