<?php
/*-----------------------------------------------------------------
！！！！警告！！！！
以下为系统文件，请勿修改
-----------------------------------------------------------------*/
namespace app\model\common;

use app\model\Verify as Verify_Base;
use ginkgo\Func;
use ginkgo\Crypt;

// 不能非法包含或直接执行
if (!defined('IN_GINKGO')) {
  return 'Access denied';
}

/*-------------验证模型-------------*/
class Verify extends Verify_Base {

  /** 提交
   * submit function.
   *
   * @access public
   * @return void
   */
  public function submit($num_userId, $str_mail, $str_type) {
    $_arr_verifyRow = $this->check($num_userId, 'verify_user_id');

    $_str_rand      = Func::rand();
    $_str_token     = Func::rand();
    $_str_tokenDo   = Crypt::crypt($_str_token, $_str_rand);

    $_arr_verifyData = array(
      'verify_user_id'        => $num_userId,
      'verify_mail'           => $str_mail,
      'verify_type'           => $str_type,
      'verify_token'          => $_str_token,
      'verify_rand'           => $_str_rand,
      'verify_token_expire'   => GK_NOW + $this->configBase['verify_expire'] * GK_MINUTE,
      'verify_status'         => 'enable',
      'verify_time_refresh'   => GK_NOW,
    );

    if ($_arr_verifyRow['rcode'] == 'x120102') {
      $_arr_verifyData['verify_time'] = GK_NOW;

      $_num_verifyId  = $this->insert($_arr_verifyData);

      if ($_num_verifyId > 0) {
        $_str_rcode = 'y120101'; //更新成功
        $_str_msg   = 'Add token successfully';
      } else {
        $_str_rcode = 'x120101'; //更新失败
        $_str_msg   = 'Add token failed';
      }
    } else {
      $_num_verifyId  = $_arr_verifyRow['verify_id'];

      $_num_count     = $this->where('verify_id', '=', $_num_verifyId)->update($_arr_verifyData);

      if ($_num_count > 0) {
        $_str_rcode = 'y120103'; //更新成功
        $_str_msg   = 'Update token successfully';
      } else {
        $_str_rcode = 'x120103';
        $_str_msg   = 'Did not make any changes';
      }
    }

    return array(
      'verify_id'     => $_num_verifyId,
      'verify_token'  => $_str_tokenDo,
      'msg'           => $_str_msg,
      'rcode'         => $_str_rcode, //成功
    );
  }
}
