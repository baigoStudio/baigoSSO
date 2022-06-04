<?php
/*-----------------------------------------------------------------
！！！！警告！！！！
以下为系统文件，请勿修改
-----------------------------------------------------------------*/
namespace app\model\api;

use app\model\common\User as User_Common;

// 不能非法包含或直接执行
if (!defined('IN_GINKGO')) {
  return 'Access denied';
}

/*-------------用户模型-------------*/
class Sync extends User_Common {

  protected $table = 'user';

  public $inputCommon = array();

  public function inputCommon($arr_data) {
    $_arr_inputParam = array(
      'user_id'           => array('int', 0),
      'user_access_token' => array('txt', ''),
      'timestamp'         => array('int', 0),
    );

    $_arr_inputCommon  = $this->obj_request->fillParam($arr_data, $_arr_inputParam);

    $_mix_vld = $this->validate($_arr_inputCommon);

    if ($_mix_vld !== true) {
      return array(
        'rcode' => 'x010201',
        'msg'   => end($_mix_vld),
      );
    }

    $_arr_inputCommon['rcode'] = 'y010201';

    $this->inputCommon = $_arr_inputCommon;

    return $_arr_inputCommon;
  }
}
