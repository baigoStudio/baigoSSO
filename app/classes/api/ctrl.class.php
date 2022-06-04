<?php
/*-----------------------------------------------------------------
！！！！警告！！！！
以下为系统文件，请勿修改
-----------------------------------------------------------------*/

namespace app\classes\api;

use app\classes\Ctrl as Ctrl_Base;
use ginkgo\Loader;
use ginkgo\Func;
use ginkgo\Crypt;
use ginkgo\Sign;
use ginkgo\Arrays;
use ginkgo\Http;
use ginkgo\Html;
use ginkgo\Log;
use ginkgo\Plugin;
use ginkgo\Config;

// 不能非法包含或直接执行
if (!defined('IN_GINKGO')) {
  return 'Access denied';
}


/*-------------安装通用控制器-------------*/
abstract class Ctrl extends Ctrl_Base {

  protected $decryptRow;
  protected $version;
  protected $appRow;

  protected function c_init($param = array()) { //构造函数
    parent::c_init();

    $this->mdl_app      = Loader::model('App');

    $_arr_version = array(
      'prd_sso_ver' => PRD_SSO_VER,
      'prd_sso_pub' => PRD_SSO_PUB,
    );

    $this->version = $_arr_version;

    Plugin::listen('action_api_init'); //管理后台初始化时触发

    $_arr_configMailtpl     = Config::get('mailtpl', 'var_extra');

    $this->configMailtpl    = Config::get('mailtpl', 'var_extra');
  }


  protected function init() {
    $_arr_appRow = $this->chkApp();

    if ($_arr_appRow['rcode'] != 'y050102') {
      return $_arr_appRow;
    }

    return true;
  }


  protected function chkApp() {
    $_arr_inputCommon = $this->mdl_app->inputCommon();

    //print_r($_arr_inputCommon);

    if ($_arr_inputCommon['rcode'] != 'y050201') {
      $_arr_inputCommon['msg'] = $this->obj_lang->get($_arr_inputCommon['msg'], 'api.common');
      return $_arr_inputCommon;
    }

    $_arr_appRow = $this->mdl_app->read($_arr_inputCommon['app_id']);

    //print_r($_arr_appRow);

    if ($_arr_appRow['rcode'] != 'y050102') {
      return array(
        'msg'   => $this->obj_lang->get($_arr_appRow['msg'], 'api.common'),
        'rcode' => $_arr_appRow['rcode'],
      );
    }

    if ($_arr_appRow['app_status'] != 'enable') {
      return array(
        'msg'   => $this->obj_lang->get('App is disabled', 'api.common'),
        'rcode' => 'x050402',
      );
    }

    $_str_ip = $this->obj_request->ip();

    //print_r($_str_ip);

    if (Func::notEmpty($_arr_appRow['app_ip_allow'])) {
      $_str_ipAllow = str_replace(PHP_EOL, '|', $_arr_appRow['app_ip_allow']);
      if (!Func::checkRegex($_str_ip, $_str_ipAllow, true)) {
        return array(
          'msg'   => $this->obj_lang->get('Your IP address is not allowed', 'api.common'),
          'rcode' => 'x050407',
        );
      }
    } else if (Func::notEmpty($_arr_appRow['app_ip_bad'])) {
      $_str_ipBad = str_replace(PHP_EOL, '|', $_arr_appRow['app_ip_bad']);
      if (Func::checkRegex($_str_ip, $_str_ipBad, true)) {
        return array(
          'msg'   => $this->obj_lang->get('Your IP address is forbidden', 'api.common'),
          'rcode' => 'x050408',
        );
      }
    }

    $_arr_appRow['app_key'] = Crypt::crypt($_arr_appRow['app_key'], $_arr_appRow['app_name']);

    if ($_arr_inputCommon['app_key'] != $_arr_appRow['app_key']) {
      return array(
        'msg'   => $this->obj_lang->get('App Key is incorrect', 'api.common'),
        'rcode' => 'x050201',
      );
    }

    $_str_decrypt = Crypt::decrypt($_arr_inputCommon['code'], $_arr_appRow['app_key'], $_arr_appRow['app_secret']);  //解密数据

    if ($_str_decrypt === false) {
      $_str_error = Crypt::getError();
      return array(
        'msg'   => $this->obj_lang->get($_str_error, 'api.common'),
        'rcode' => 'x050406',
      );
    }

    if (!Sign::check($_str_decrypt, $_arr_inputCommon['sign'], $_arr_appRow['app_key'] . $_arr_appRow['app_secret'])) {
      return array(
        'msg'   => $this->obj_lang->get('Signature is incorrect', 'api.common'),
        'rcode' => 'x050403',
      );
    }

    $_arr_decryptRow = Arrays::fromJson($_str_decrypt);

    if (!isset($_arr_decryptRow['timestamp'])) {
      return array(
        'msg'   => $this->obj_lang->get('Timestamp require', 'api.common'),
        'rcode' => 'x050201',
      );
    }

    $this->appRow       = $_arr_appRow;
    $this->decryptRow   = $_arr_decryptRow;

    return $_arr_appRow;
  }


  protected function notify($str_src, $str_act) {
    $_obj_http = Http::instance();

    $_arr_search = array(
      'status'        => 'enable',
      'has_notify'    => true,
    );
    $_arr_appRows = $this->mdl_app->lists(array(100, 'limit'), $_arr_search);

    //通知
    foreach ($_arr_appRows as $_key=>$_value) {
      $_str_appKey    = Crypt::crypt($_value['app_key'], $_value['app_name']);
      $_str_encrypt   = Crypt::encrypt($str_src, $_str_appKey, $_value['app_secret']);

      //加密成功
      if ($_str_encrypt !== false) {
        $_arr_data = array(
          'app_id'    => $_value['app_id'],
          'app_key'   => $_str_appKey,
          'code'      => $_str_encrypt,
          'sign'      => Sign::make($str_src, $_str_appKey . $_value['app_secret']),
        );

        if (Func::notEmpty($_value['app_param'])) {
          foreach ($_value['app_param'] as $_key_param=>$_value_param) {
            if (isset($_value_param['key']) && isset($_value_param['value'])) {
              $_arr_data[$_value_param['key']] = $_value_param['value'];
            }
          }
        }

        $_str_urlNotify    = Html::decode($_value['app_url_notify'], 'url');
        $_str_urlNotify    = rtrim($_str_urlNotify, '/');

        if (stristr($_str_urlNotify, '?')) {
          $_str_conn = '&';
        } else {
          $_str_conn = '?';
        }

        $_str_urlNotify = $_str_urlNotify . $_str_conn . 'm=sso&c=notify&a=' . $str_act;

        $_arr_notifyResult = $_obj_http->request($_str_urlNotify, $_arr_data, 'post');

        if (!isset($_arr_notifyResult['msg']) || $_arr_notifyResult['msg'] != 'success') {
          Log::record('type: notify, action: ' . $_str_urlNotify . ', app_id: ' . $_value['app_id'] . ', result: failed ' . Arrays::toJson($_arr_notifyResult), 'log');
        }
      }

      /*print_r($_str_urlNotify . $_str_conn . 'm=notify');
      print_r('-');
      print_r($_arr_get);
      print_r('|');*/
    }
  }


  protected function userCheckProcess($arr_userRow, $arr_inputCheck) {
    if ($arr_userRow['rcode'] != 'y010102') {
      return array(
        'msg'   => $arr_userRow['msg'],
        'rcode' => $arr_userRow['rcode'],
      );
    }

    if ($arr_userRow['user_status'] == 'disabled') {
      return array(
        'msg'   => 'User is disabled',
        'rcode' => 'x010402',
      );
    }

    if ($arr_userRow['user_access_expire'] < GK_NOW) {
      return array(
        'msg'   => 'Access token expired',
        'rcode' => 'x010201',
      );
    }

    if ($arr_inputCheck['user_access_token'] != md5(Crypt::crypt($arr_userRow['user_access_token'], $arr_userRow['user_name']))) {
      return array(
        'msg'   => 'Access token is incorrect',
        'rcode' => 'x010201',
      );
    }

    return $arr_userRow;
  }
}
