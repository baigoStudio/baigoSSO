<?php
/*-----------------------------------------------------------------
！！！！警告！！！！
以下为系统文件，请勿修改
-----------------------------------------------------------------*/

namespace app\ctrl\console;

use app\classes\console\Ctrl;
use ginkgo\Loader;
use ginkgo\Func;
use ginkgo\Crypt;
use ginkgo\Sign;
use ginkgo\Json;
use ginkgo\Http;
use ginkgo\Html;
use ginkgo\Log;

// 不能非法包含或直接执行
if (!defined('IN_GINKGO')) {
  return 'Access denied';
}

class Combine extends Ctrl {

  protected function c_init($param = array()) {
    parent::c_init();

    $this->mdl_combine  = Loader::model('Combine');

    $_str_hrefBase = $this->hrefBase . 'combine/';

    $_arr_hrefRow   = array(
      'index'          => $_str_hrefBase . 'index/',
      'add'            => $_str_hrefBase . 'form/',
      'edit'           => $_str_hrefBase . 'form/id/',
      'submit'         => $_str_hrefBase . 'submit/',
      'delete'         => $_str_hrefBase . 'delete/',
      'combine_belong' => $this->url['route_console'] . 'combine_belong/index/id/',
    );

    $this->generalData['hrefRow']   = array_replace_recursive($this->generalData['hrefRow'], $_arr_hrefRow);
  }


  public function index() {
    $_mix_init = $this->init();

    if ($_mix_init !== true) {
      return $this->error($_mix_init['msg'], $_mix_init['rcode']);
    }

    if (!isset($this->adminAllow['app']['combine']) && !$this->isSuper) { //判断权限
      return $this->error('You do not have permission', 'x040301');
    }

    $_arr_searchParam = array(
      'key'       => array('txt', ''),
    );

    $_arr_search = $this->obj_request->param($_arr_searchParam);

    //print_r($_arr_search);

    $_arr_getData   = $this->mdl_combine->lists($this->config['var_default']['perpage'], $_arr_search); //列出

    $_arr_tplData = array(
      'search'        => $_arr_search,
      'pageRow'       => $_arr_getData['pageRow'],
      'combineRows'   => $_arr_getData['dataRows'],
      'token'         => $this->obj_request->token(),
    );

    $_arr_tpl = array_replace_recursive($this->generalData, $_arr_tplData);

    //print_r($_arr_combineRows);

    $this->assign($_arr_tpl);

    return $this->fetch();
  }


  public function form() {
    $_mix_init = $this->init();

    if ($_mix_init !== true) {
      return $this->error($_mix_init['msg'], $_mix_init['rcode']);
    }

    $_num_combineId = 0;

    if (isset($this->param['id'])) {
      $_num_combineId = $this->obj_request->input($this->param['id'], 'int', 0);
    }

    $_arr_combineRow = $this->mdl_combine->read($_num_combineId);

    if ($_num_combineId > 0) {
      if (!isset($this->adminAllow['app']['combine']) && !$this->isSuper) { //判断权限
        return $this->error('You do not have permission', 'x040303');
      }

      if ($_arr_combineRow['rcode'] != 'y040102') {
        return $this->error($_arr_combineRow['msg'], $_arr_combineRow['rcode']);
      }
    } else {
      if (!isset($this->adminAllow['app']['combine']) && !$this->isSuper) { //判断权限
        return $this->error('You do not have permission', 'x040302');
      }
    }

    $_arr_tplData = array(
      'combineRow'    => $_arr_combineRow,
      'token'     => $this->obj_request->token(),
    );

    $_arr_tpl = array_replace_recursive($this->generalData, $_arr_tplData);

    //print_r($_arr_combineRows);

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

    $_arr_inputSubmit = $this->mdl_combine->inputSubmit();

    if ($_arr_inputSubmit['rcode'] != 'y040201') {
      return $this->fetchJson($_arr_inputSubmit['msg'], $_arr_inputSubmit['rcode']);
    }

    if ($_arr_inputSubmit['combine_id'] > 0) {
      if (!isset($this->adminAllow['app']['combine']) && !$this->isSuper) {
        return $this->fetchJson('You do not have permission', 'x040303');
      }
    } else {
      if (!isset($this->adminAllow['app']['combine']) && !$this->isSuper) {
        return $this->fetchJson('You do not have permission', 'x040302');
      }
    }

    $_arr_submitResult   = $this->mdl_combine->submit();

    return $this->fetchJson($_arr_submitResult['msg'], $_arr_submitResult['rcode']);
  }


  public function delete() {
    $_mix_init = $this->init();

    if ($_mix_init !== true) {
      return $this->fetchJson($_mix_init['msg'], $_mix_init['rcode']);
    }

    if (!$this->isAjaxPost) {
      return $this->fetchJson('Access denied', '', 405);
    }

    if (!isset($this->adminAllow['app']['combine']) && !$this->isSuper) { //判断权限
      return $this->fetchJson('You do not have permission', 'x040304');
    }

    $_arr_inputDelete = $this->mdl_combine->inputDelete();

    if ($_arr_inputDelete['rcode'] != 'y040201') {
      return $this->fetchJson($_arr_inputDelete['msg'], $_arr_inputDelete['rcode']);
    }

    $_arr_deleteResult = $this->mdl_combine->delete();

    $_arr_langReplace = array(
      'count' => $_arr_deleteResult['count'],
    );

    return $this->fetchJson($_arr_deleteResult['msg'], $_arr_deleteResult['rcode'], 200, $_arr_langReplace);
  }
}
