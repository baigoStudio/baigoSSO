<?php
/*-----------------------------------------------------------------
！！！！警告！！！！
以下为系统文件，请勿修改
-----------------------------------------------------------------*/

namespace app\model\console;

use app\model\Admin as Admin_Base;
use ginkgo\Func;
use ginkgo\Arrays;

// 不能非法包含或直接执行
if (!defined('IN_GINKGO')) {
  return 'Access denied';
}

/*-------------管理员模型-------------*/
class Profile extends Admin_Base {

  public $inputInfo     = array();
  public $inputShortcut = array();
  public $inputSecqa    = array();
  public $inputPass     = array();
  public $inputMailbox  = array();

  protected $table = 'admin';


  /** 修改个人资料
   * info function.
   *
   * @access public
   * @param mixed $num_adminId
   * @return void
   */
  public function info() {
    $_arr_adminData = array(
      'admin_nick' => $this->inputInfo['admin_nick'],
    );

    $_mix_vld = $this->validate($_arr_adminData, '', 'info_db');

    if ($_mix_vld !== true) {
      return array(
        'rcode' => 'x020201',
        'msg'   => end($_mix_vld),
      );
    }

    $_num_count     = $this->where('admin_id', '=', $this->inputInfo['admin_id'])->update($_arr_adminData); //更新数

    if ($_num_count > 0) {
      $_str_rcode = 'y020103'; //更新成功
      $_str_msg   = 'Update administrator successfully';
    } else {
      $_str_rcode = 'x020103'; //更新失败
      $_str_msg   = 'Did not make any changes';
    }

    return array(
      'rcode'      => $_str_rcode, //成功
      'msg'        => $_str_msg,
    );
  }


  public function shortcut() {
    $_arr_adminData = array(
      'admin_shortcut' => $this->inputShortcut['admin_shortcut'],
    );

    $_mix_vld = $this->validate($_arr_adminData, '', 'shortcut_db');

    if ($_mix_vld !== true) {
      return array(
        'rcode' => 'x020201',
        'msg'   => end($_mix_vld),
      );
    }

    $_arr_adminData['admin_shortcut'] = Arrays::toJson($_arr_adminData['admin_shortcut']);

    $_num_count     = $this->where('admin_id', '=', $this->inputShortcut['admin_id'])->update($_arr_adminData); //更新数

    if ($_num_count > 0) {
      $_str_rcode = 'y020103'; //更新成功
      $_str_msg   = 'Shortcut set successfully';
    } else {
      $_str_rcode = 'x020103'; //更新失败
      $_str_msg   = 'Did not make any changes';
    }

    return array(
      'rcode'      => $_str_rcode, //成功
      'msg'        => $_str_msg,
    );
  }


  /** 修改个人资料表单验证
   * inputInfo function.
   *
   * @access public
   * @return void
   */
  public function inputInfo() {
    $_arr_inputParam = array(
      'admin_pass'    => array('txt', ''),
      'admin_nick'    => array('txt', ''),
      '__token__'     => array('txt', ''),
    );

    $_arr_inputInfo = $this->obj_request->post($_arr_inputParam);

    $_mix_vld = $this->validate($_arr_inputInfo, '', 'info');

    if ($_mix_vld !== true) {
      return array(
        'rcode' => 'x020201',
        'msg'   => end($_mix_vld),
      );
    }

    $_arr_inputInfo['rcode'] = 'y020201';

    $this->inputInfo = $_arr_inputInfo;

    return $_arr_inputInfo;
  }


  /**
   * inputSecqa function.
   *
   * @access public
   * @return void
   */
  public function inputSecqa() {
    $_arr_inputParam = array(
      'admin_pass'        => array('txt', ''),
      'admin_sec_ques'    => array('arr', array()),
      'admin_sec_answ'    => array('arr', array()),
      '__token__'         => array('txt', ''),
    );

    $_arr_inputSecqa = $this->obj_request->post($_arr_inputParam);

    $_mix_vld = $this->validate($_arr_inputSecqa, '', 'secqa');

    if ($_mix_vld !== true) {
      return array(
        'rcode' => 'x020201',
        'msg'   => end($_mix_vld),
      );
    }

    $_arr_inputSecqa['rcode'] = 'y020201';

    $this->inputSecqa = $_arr_inputSecqa;

    return $_arr_inputSecqa;
  }


  /**
   * inputPassword function.
   *
   * @access public
   * @return void
   */
  public function inputPass() {
    $_arr_inputParam = array(
      'admin_pass'            => array('txt', ''),
      'admin_pass_new'        => array('txt', ''),
      'admin_pass_confirm'    => array('txt', ''),
      '__token__'             => array('txt', ''),
    );

    $_arr_inputPass = $this->obj_request->post($_arr_inputParam);

    $_mix_vld = $this->validate($_arr_inputPass, '', 'pass');

    if ($_mix_vld !== true) {
      return array(
        'rcode' => 'x020201',
        'msg'   => end($_mix_vld),
      );
    }

    $_arr_inputPass['rcode'] = 'y020201';

    $this->inputPass = $_arr_inputPass;

    return $_arr_inputPass;
  }


  /**
   * inputMailbox function.
   *
   * @access public
   * @return void
   */
  public function inputMailbox() {
    $_arr_inputParam = array(
      'admin_pass'            => array('txt', ''),
      'admin_mail_new'        => array('txt', ''),
      '__token__'             => array('txt', ''),
    );

    $_arr_inputMailbox = $this->obj_request->post($_arr_inputParam);

    $_mix_vld = $this->validate($_arr_inputMailbox, '', 'mailbox');

    if ($_mix_vld !== true) {
      return array(
        'rcode' => 'x020201',
        'msg'   => end($_mix_vld),
      );
    }

    $_arr_inputMailbox['rcode'] = 'y020201';

    $this->inputMailbox = $_arr_inputMailbox;

    return $_arr_inputMailbox;
  }


  public function inputShortcut() {
    $_arr_inputParam = array(
      'admin_shortcut'   => array('arr', array()),
      '__token__'        => array('txt', ''),
    );

    $_arr_inputShortcut = $this->obj_request->post($_arr_inputParam);

    $_mix_vld = $this->validate($_arr_inputShortcut, '', 'shortcut');

    if ($_mix_vld !== true) {
      return array(
        'rcode' => 'x020201',
        'msg'   => end($_mix_vld),
      );
    }

    $_arr_inputShortcut['rcode'] = 'y020201';

    $this->inputShortcut = $_arr_inputShortcut;

    return $_arr_inputShortcut;
  }
}
