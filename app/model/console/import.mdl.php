<?php
/*-----------------------------------------------------------------
！！！！警告！！！！
以下为系统文件，请勿修改
-----------------------------------------------------------------*/

namespace app\model\console;

use app\model\common\User as User_Common;
use ginkgo\Func;
use ginkgo\Crypt;
use ginkgo\Html;
use ginkgo\File;

// 不能非法包含或直接执行
if (!defined('IN_GINKGO')) {
  return 'Access denied';
}

/*-------------用户模型-------------*/
class Import extends User_Common {

  public $inputCommon = array();

  public $charsetKeys = array();
  public $csvRows = array();

  public $csvPath;
  public $csvPrefix = GK_PATH_DATA;
  public $csvName = 'user_import.csv';
  protected $table = 'user';

  protected function m_init() { //构造函数
    $this->csvPath = $this->csvPrefix . $this->csvName;
  }


  /** 导入预览
   * preview function.
   *
   * @access public
   * @return void
   */
  public function preview($str_charset = '', $offset = 5) {
    if (File::fileHas($this->csvPath)) {
      $_res_csv    = fopen($this->csvPath, 'r');

      $_str_sample = fread($_res_csv, 100);

      rewind($_res_csv);

      if (Func::isEmpty($str_charset)) {
        $_str_charset = mb_detect_encoding($_str_sample, $this->charsetKeys, true);
      } else {
        $_str_charset = $str_charset;
      }

      //print_r($_str_charset);

      if (Func::notEmpty($_str_charset) && $_str_charset != 'UTF-8') {
        stream_filter_append($_res_csv, 'convert.iconv.' . $_str_charset . '/UTF-8');
      }

      $_num_row = 0;

      while (($offset == 0 || $offset > $_num_row) && !feof($_res_csv)) {
        $_arr_data = fgetcsv($_res_csv);

        if (is_array($_arr_data)) {
          foreach ($_arr_data as $_key=>$_value) {
            if (Func::isEmpty($_value)) {
              $this->csvRows[$_num_row][$_key] = '';
            } else {
              $this->csvRows[$_num_row][$_key] = $this->obj_request->input($_value, 'txt', '');
              }
          }
          ++$_num_row;
        }
      }

      fclose($_res_csv);
    }

    return $this->csvRows;
  }


  /** 转换并导入数据库
   * mdl_convert function.
   *
   * @access public
   * @return void
   */
  public function submit() {
    $_num_count = 0;

    $this->preview($this->inputSubmit['charset'], 0);
    unset($this->csvRows[0]);

    foreach ($this->csvRows as $_key_row=>$_value_row) {
      $_arr_userData = array();

      foreach ($this->inputSubmit['user_convert'] as $_key_cel=>$_value_cel) {
        switch ($_value_cel) {
          case 'ignore':

          break;

          default:
            $_arr_userData[$_value_cel] = $_value_row[$_key_cel];
          break;
        }
      }

      $_arr_userRow = $this->check($_arr_userData['user_name'], 'user_name');

      /*print_r($_arr_userRow);
      print_r($_arr_userData);*/

      if ($_arr_userRow['rcode'] == 'x010102') {
        $_str_rand                  = Func::rand();
        $_arr_userData['user_pass'] = Crypt::crypt($_arr_userData['user_pass'],$_str_rand, true);
        $_arr_userData['user_rand'] = $_str_rand;

        $_num_userId    = $this->insert($_arr_userData);

        if ($_num_userId > 0) { //数据库插入是否成功
          ++$_num_count;
        }
      }
    }

    //print_r($_num_count);

    if ($_num_count > 0) {
      $_str_rcode = 'y010402';
      $_str_msg   = 'Successfully import {:count} users';
    } else {
      $_str_rcode = 'x010402';
      $_str_msg   = 'No data imported';
    }

    return array(
      'count' => $_num_count,
      'rcode' => $_str_rcode,
      'msg'   => $_str_msg,
    );
  }


  /** 转换表单验证
   * inputSubmit function.
   *
   * @access public
   * @return void
   */
  public function inputSubmit() {
    $_arr_inputParam = array(
      'charset'       => array('txt', ''),
      'user_convert'  => array('arr', array()),
      '__token__'     => array('txt', ''),
    );

    $_arr_inputSubmit = $this->obj_request->post($_arr_inputParam);

    $_arr_inputSubmit['charset'] = Html::decode($_arr_inputSubmit['charset'], 'url');

    $_mix_vld = $this->validate($_arr_inputSubmit, '', 'submit');

    if ($_mix_vld !== true) {
      return array(
        'rcode' => 'x010201',
        'msg'   => end($_mix_vld),
      );
    }

    if (!in_array('user_name', $_arr_inputSubmit['user_convert'])) {
      return array(
        'rcode' => 'x010201',
        'msg'   => 'Username is a required item',
      );
    }

    if (!in_array('user_pass', $_arr_inputSubmit['user_convert'])) {
      return array(
        'rcode' => 'x010201',
        'msg'   => 'Password is a required item',
      );
    }

    $_arr_inputSubmit['rcode'] = 'y010201';

    $this->inputSubmit = $_arr_inputSubmit;

    return $_arr_inputSubmit;
  }


  public function inputCommon() {
    $_arr_inputParam = array(
      '__token__' => array('txt', ''),
    );

    $_arr_inputCommon = $this->obj_request->post($_arr_inputParam);

    $_mix_vld = $this->validate($_arr_inputCommon, '', 'common');

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
