<?php
/*-----------------------------------------------------------------
！！！！警告！！！！
以下为系统文件，请勿修改
-----------------------------------------------------------------*/
namespace app\model\api;

use app\model\common\User as User_Common;
use ginkgo\Func;
use ginkgo\Crypt;

// 不能非法包含或直接执行
if (!defined('IN_GINKGO')) {
  return 'Access denied';
}

/*-------------用户模型-------------*/
class Login extends User_Common {

  protected $table = 'user';

  public function login() {
    $_arr_userData = array(
      'user_time_login'   => GK_NOW,
    );

    if (isset($this->inputSubmit['user_ip']) && Func::notEmpty($this->inputSubmit['user_ip'])) {
      $_str_userIp = $this->inputSubmit['user_ip'];
    } else {
      $_str_userIp = $this->obj_request->ip();
    }
    $_arr_userData['user_ip'] = $_str_userIp;

    $_arr_userRow = $this->read($this->inputSubmit['user_id']);

    if ($_arr_userRow['user_access_expire'] <= GK_NOW) { //如果访问口令过期
      $_str_accessToken   = Func::rand();
      $_tm_accessExpire   = GK_NOW + $this->configBase['access_expire'] * GK_MINUTE;

      $_arr_userData['user_access_token']     = $_str_accessToken;
      $_arr_userData['user_access_expire']    = $_tm_accessExpire;
    } else {
      $_str_accessToken   = $_arr_userRow['user_access_token'];
      $_tm_accessExpire   = $_arr_userRow['user_access_expire'];
    }

    if ($_arr_userRow['user_refresh_expire'] <= GK_NOW) { //如果刷新口令过期
      $_str_refreshToken  = Func::rand();
      $_tm_refreshExpire  = GK_NOW + $this->configBase['refresh_expire'] * GK_DAY;

      $_arr_userData['user_refresh_token']    = $_str_refreshToken;
      $_arr_userData['user_refresh_expire']   = $_tm_refreshExpire;
    } else {
      $_str_refreshToken  = $_arr_userRow['user_refresh_token'];
      $_tm_refreshExpire  = $_arr_userRow['user_refresh_expire'];
    }

    $_num_count     = $this->where('user_id', '=', $_arr_userRow['user_id'])->update($_arr_userData);

    if ($_num_count > 0) {
      $_str_rcode = 'y010103'; //更新成功
      $_str_msg   = 'Login successful';
    } else {
      $_str_rcode = 'x010103';
      $_str_msg   = 'Login failed';
    }

    return array(
      'user_id'               => $_arr_userRow['user_id'],
      'user_name'             => $_arr_userRow['user_name'],
      'user_status'           => $_arr_userRow['user_status'],
      'user_ip'               => $_str_userIp,
      'user_time_login'       => GK_NOW,
      'user_access_token'     => Crypt::crypt($_str_accessToken, $_arr_userRow['user_name']),
      'user_access_expire'    => $_tm_accessExpire,
      'user_refresh_token'    => Crypt::crypt($_str_refreshToken, $_arr_userRow['user_name']),
      'user_refresh_expire'   => $_tm_refreshExpire,
      'rcode'                 => $_str_rcode, //成功
      'msg'                   => $_str_msg,
    );
  }


  /** api 登录表单验证
   * inputSubmit_api function.
   *
   * @access public
   * @return void
   */
  public function inputSubmit($arr_data) {
    $_arr_inputParam = array(
      'user_str'  => array('txt', ''),
      'user_by'   => array('txt', ''),
      'user_pass' => array('txt', ''),
      'user_ip'   => array('txt', ''),
      'timestamp' => array('int', 0),
    );

    $_arr_inputSubmit  = $this->obj_request->fillParam($arr_data, $_arr_inputParam);

    if (isset($arr_data['user_id']) && $arr_data['user_id'] > 0) {
      $_arr_inputSubmit['user_by']  = 'user_id';
      $_arr_inputSubmit['user_str'] = $arr_data['user_id'];
    } else if (isset($arr_data['user_name']) && Func::notEmpty($arr_data['user_name'])) {
      $_arr_inputSubmit['user_by']  = 'user_name';
      $_arr_inputSubmit['user_str'] = $arr_data['user_name'];
    } else if (isset($arr_data['user_mail']) && Func::notEmpty($arr_data['user_mail'])) {
      $_arr_inputSubmit['user_by']  = 'user_mail';
      $_arr_inputSubmit['user_str'] = $arr_data['user_mail'];
    }

    $_mix_vld = $this->validate($_arr_inputSubmit, '', 'login');

    if ($_mix_vld !== true) {
      return array(
        'rcode' => 'x010201',
        'msg'   => end($_mix_vld),
      );
    }

    if ($_arr_inputSubmit['user_by'] == 'user_mail') {
      $_arr_search  = array(
        'user_mail' => $_arr_inputSubmit['user_str'],
      );

      $_num_userCount = $this->count($_arr_search);

      if ($_num_userCount > 0) {
        return array(
          'rcode' => 'x010201',
          'msg'   => 'There are duplicate emails in the system',
        );
      }
    }

    $_arr_inputSubmit['rcode'] = 'y010201';

    $this->inputSubmit = $_arr_inputSubmit;

    return $_arr_inputSubmit;
  }
}
