<?php
/*-----------------------------------------------------------------
！！！！警告！！！！
以下为系统文件，请勿修改
-----------------------------------------------------------------*/

namespace app\ctrl\misc;

use ginkgo\Request;
use ginkgo\Lang;
use ginkgo\Arrays;
use ginkgo\Captcha as Captcha_Gen;

// 不能非法包含或直接执行
if (!defined('IN_GINKGO')) {
  return 'Access denied';
}

class Captcha {

  public function __construct($param = array()) {
    $this->obj_captcha  = Captcha_Gen::instance();
    $this->obj_request  = Request::instance();

    if (isset($param['id'])) {
      $param['id'] = $this->obj_request->input($param['id'], 'str', '');
    } else {
      $param['id'] = '';
    }

    $this->param = $param;
  }

  public function index() {
    return $this->obj_captcha->create($this->param['id']);
  }

  public function check() {
    $_obj_lang     = Lang::instance();
    $_route        = $this->obj_request->route();
    $_obj_lang->range($_route['mod'] . '.' . $_route['ctrl']);
    $_str_current       = $_obj_lang->getCurrent();
    $_str_langPath      = GK_APP_LANG . $_str_current . DS . $_route['mod'] . DS . $_route['ctrl'] . GK_EXT_LANG;
    $_obj_lang->load($_str_langPath);

    $_str_captcha = strtolower($this->obj_request->get('captcha'));

    //print_r($_str_captcha);

    if ($this->obj_captcha->check($_str_captcha, $this->param['id'], false)) {
      $_arr_return = array(
        'msg'   => '',
      );
    } else {
      $_arr_return = array(
        'rcode'     => 'x030202',
        'error_msg' => $_obj_lang->get('Captcha is incorrect'),
      );
    }

    return Arrays::toJson($_arr_return);
  }
}
