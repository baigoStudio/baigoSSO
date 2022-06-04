<?php
/*-----------------------------------------------------------------
！！！！警告！！！！
以下为系统文件，请勿修改
-----------------------------------------------------------------*/

namespace app\model\common;

use app\model\User as User_Base;
use ginkgo\Func;
use ginkgo\Config;
use ginkgo\Arrays;

// 不能非法包含或直接执行
if (!defined('IN_GINKGO')) {
  return 'Access denied';
}

/*-------------用户模型-------------*/
class User extends User_Base {

  public $inputSubmit  = array();
  public $inputSecqa   = array();
  public $inputMailbox = array();

  /** 提交
   * submit function.
   *
   * @access public
   * @param string $str_userPass (default: '')
   * @return void
   */
  public function submit() {
    $_arr_userData = array();

    if (isset($this->inputSubmit['user_name'])) {
      $_arr_userData['user_name'] = $this->inputSubmit['user_name'];
    }

    if (isset($this->inputSubmit['user_mail'])) {
      $_arr_userData['user_mail'] = $this->inputSubmit['user_mail'];
    }

    if (isset($this->inputSubmit['user_status'])) {
      $_arr_userData['user_status'] = $this->inputSubmit['user_status'];
    }

    if (isset($this->inputSubmit['user_nick'])) {
      $_arr_userData['user_nick'] = $this->inputSubmit['user_nick'];
    }

    if (isset($this->inputSubmit['user_note'])) {
      $_arr_userData['user_note'] = $this->inputSubmit['user_note'];
    }

    if (isset($this->inputSubmit['user_contact'])) {
      $_arr_userData['user_contact'] = $this->inputSubmit['user_contact'];
    }

    if (isset($this->inputSubmit['user_extend'])) {
      $_arr_userData['user_extend'] = $this->inputSubmit['user_extend'];
    }

    $_arr_userData['user_contact']    = Arrays::toJson($_arr_userData['user_contact']);
    $_arr_userData['user_extend']     = Arrays::toJson($_arr_userData['user_extend']);

    if ($this->inputSubmit['user_id'] > 0) {
      if (isset($this->inputSubmit['user_pass']) && Func::notEmpty($this->inputSubmit['user_pass'])) {
        $_arr_userData['user_pass'] = $this->inputSubmit['user_pass']; //如果密码为空，则不修改
        $_arr_userData['user_rand'] = $this->inputSubmit['user_rand']; //如果密码为空，则不修改
      }
      $_num_userId    = $this->inputSubmit['user_id'];

      $_num_count     = $this->where('user_id', '=', $_num_userId)->update($_arr_userData); //更新数据

      if ($_num_count > 0) {
        $_str_rcode = 'y010103'; //更新成功
        $_str_msg   = 'Update user successfully';
      } else {
        $_str_rcode = 'x010103'; //更新失败
        $_str_msg   = 'Did not make any changes';
      }
    } else {
      $_arr_insert = array(
        'user_pass'         => $this->inputSubmit['user_pass'],
        'user_rand'         => $this->inputSubmit['user_rand'],
        'user_time'         => GK_NOW,
        'user_time_login'   => GK_NOW,
        'user_ip'           => $this->obj_request->ip(),
        //'user_app_id'       => $this->inputSubmit['user_app_id'],
      );
      $_arr_data      = array_replace_recursive($_arr_userData, $_arr_insert);

      $_num_userId    = $this->insert($_arr_data); //更新数据

      if ($_num_userId > 0) {
        $_str_rcode = 'y010101'; //更新成功
        $_str_msg   = 'Add user successfully';
      } else {
        $_str_rcode = 'x010101'; //更新失败
        $_str_msg   = 'Add user failed';
      }
    }

    return array(
      'user_id'   => $_num_userId,
      'rcode'     => $_str_rcode,
      'msg'       => $_str_msg,
    );
  }


  public function pass($num_userId, $str_userPass, $str_userRand) {
    $_arr_userData = array(
      'user_rand'         => $str_userRand,
      'user_pass'         => $str_userPass,
    );

    if (Func::notEmpty($_arr_userData)) {
      $_num_count     = $this->where('user_id', '=', $num_userId)->update($_arr_userData); //更新数据
    }

    if ($_num_count > 0) {
      $_str_rcode = 'y010103'; //更新成功
      $_str_msg   = 'Update password successfully';
    } else {
      $_str_rcode = 'x010103'; //更新成功
      $_str_msg   = 'Did not make any changes';
    }

    return array(
      'rcode' => $_str_rcode, //成功
      'msg'   => $_str_msg,
    );
  }


  public function mailbox() {
    $_arr_userData = array(
      'user_mail' => $this->inputMailbox['user_mail_new'],
    );

    $_num_count     = $this->where('user_id', '=', $this->inputMailbox['user_id'])->update($_arr_userData); //更新数据

    if ($_num_count > 0) {
      $_str_rcode = 'y010103';
      $_str_msg   = 'Change mailbox successfully';
    } else {
      $_str_rcode = 'x010103';
      $_str_msg   = 'Did not make any changes';
    }

    return array(
      'rcode'     => $_str_rcode, //成功
      'msg'       => $_str_msg,
    );
  }


  public function secqa() {
    $_arr_userData = array(
      'user_sec_ques' => $this->inputSecqa['user_sec_ques'],
      'user_sec_answ' => $this->inputSecqa['user_sec_answ'],
    );

    $_arr_userData['user_sec_ques'] = Arrays::toJson($_arr_userData['user_sec_ques']);

    $_num_count     = $this->where('user_id', '=', $this->inputSecqa['user_id'])->update($_arr_userData); //更新数据

    if ($_num_count > 0) {
      $_str_rcode = 'y010103'; //更新成功
      $_str_msg   = 'Update security question successfully';
    } else {
      $_str_rcode = 'x010103'; //更新成功
      $_str_msg   = 'Did not make any changes';
    }

    return array(
      'rcode' => $_str_rcode, //成功
      'msg'   => $_str_msg,
    );
  }
}
