<?php
/*-----------------------------------------------------------------
！！！！警告！！！！
以下为系统文件，请勿修改
-----------------------------------------------------------------*/

namespace app\ctrl\console;

use app\classes\console\Ctrl;
use ginkgo\Loader;
use ginkgo\Config;
use ginkgo\Strings;

// 不能非法包含或直接执行
if (!defined('IN_GINKGO')) {
  return 'Access denied';
}

class Index extends Ctrl {

  protected function c_init($param = array()) {
    parent::c_init();

    $this->countLists    = Config::get('count_lists', 'console.index');

    $this->mdl_profile  = Loader::model('Profile');

    $_str_hrefBase = $this->hrefBase . 'index/';

    $_arr_hrefRow = array(
      'setting' => $_str_hrefBase . 'setting/',
      'submit'  => $_str_hrefBase . 'submit/',
    );

    $this->generalData['countLists'] = $this->countLists;
    $this->generalData['hrefRow']    = array_replace_recursive($this->generalData['hrefRow'], $_arr_hrefRow);

    foreach ($this->countLists as $_key=>$_value) {
      $this->mdlRows[$_key]  = Loader::model(Strings::ucwords($_key));

      if (isset($_value['status'])) {
        $this->generalData['status_' . $_key] = $this->mdlRows[$_key]->arr_status;
      }

      if (isset($_value['type'])) {
        $this->generalData['type_' . $_key] = $this->mdlRows[$_key]->arr_type;
      }
    }
  }

  public function index() {
    $_mix_init = $this->init();

    if ($_mix_init !== true) {
      return $this->error($_mix_init['msg'], $_mix_init['rcode']);
    }

    $_arr_tplData = array();

    foreach ($this->countLists as $_key=>$_value) {
      if (isset($_value['lists']['total'])) {
        if (is_array($_value['lists']['total'])) {
          if (isset($_value['lists']['total'][0]) && isset($_value['lists']['total'][1])) {
            $_arr_search = array(
              $_value['lists']['total'][0] => $_value['lists']['total'][1],
            );
            $_arr_tplData['countRows'][$_key]['total'] = $this->mdlRows[$_key]->counts($_arr_search);
          } else {
            $_arr_tplData['countRows'][$_key]['total'] = $this->mdlRows[$_key]->counts();
          }
        } else {
          $_arr_tplData['countRows'][$_key]['total'] = $this->mdlRows[$_key]->counts();
        }
      }

      if (isset($_value['lists']['status'])) {
        foreach ($this->mdlRows[$_key]->arr_status as $_key_sub=>$_value_sub) {
          $_arr_search = array(
            'status' => $_value_sub,
          );
          $_arr_tplData['countRows'][$_key][$_value_sub] = $this->mdlRows[$_key]->counts($_arr_search);
        }
      }

      if (isset($_value['lists']['type'])) {
        foreach ($this->mdlRows[$_key]->arr_type as $_key_sub=>$_value_sub) {
          $_arr_search = array(
            'type' => $_value_sub,
          );
          $_arr_tplData['countRows'][$_key][$_value_sub] = $this->mdlRows[$_key]->counts($_arr_search);
        }
      }
    }

    $_arr_tpl = array_replace_recursive($this->generalData, $_arr_tplData);

    $this->assign($_arr_tpl);

    return $this->fetch();
  }


  public function setting() {
    $_mix_init = $this->init();

    if ($_mix_init !== true) {
      return $this->error($_mix_init['msg'], $_mix_init['rcode']);
    }

    $_arr_tplData = array(
      'token'     => $this->obj_request->token(),
    );

    $_arr_tpl = array_replace_recursive($this->generalData, $_arr_tplData);

    $this->assign($_arr_tpl);

    return $this->fetch();
  }


  public function submit() {
    $_mix_init = $this->init();

    if ($_mix_init !== true) {
      return $this->fetchJson($_mix_init['msg'], $_mix_init['rcode']);
    }

    if (!$this->isAjaxPost) {
      return $this->fetchJson('Access denied', '', 405);
    }

    $_arr_inputShortcut = $this->mdl_profile->inputShortcut();

    if ($_arr_inputShortcut['rcode'] != 'y020201') {
      return $this->fetchJson($_arr_inputShortcut['msg'], $_arr_inputShortcut['rcode']);
    }

    $this->mdl_profile->inputShortcut['admin_id'] = $this->adminLogged['admin_id'];

    $_arr_shortcutResult = $this->mdl_profile->shortcut();

    return $this->fetchJson($_arr_shortcutResult['msg'], $_arr_shortcutResult['rcode']);
  }
}
