<?php
/*-----------------------------------------------------------------
！！！！警告！！！！
以下为系统文件，请勿修改
-----------------------------------------------------------------*/

namespace app\model\console;

use app\model\Plugin as Plugin_Base;
use ginkgo\Loader;
use ginkgo\Config;
use ginkgo\Arrays;
use ginkgo\Func;
use ginkgo\File;

// 不能非法包含或直接执行
if (!defined('IN_GINKGO')) {
  return 'Access denied';
}

/*-------------插件模型-------------*/
class Plugin extends Plugin_Base {

  public $inputSubmit    = array();
  public $inputUninstall = array();
  public $inputOpts      = array();

  /** 提交
   * submit function.
   *
   * @access public
   * @return void
   */
  public function submit() {
    $_arr_data = $this->configPlugin;

    $_arr_pluginData = array(
      'plugin_note' => $this->inputSubmit['plugin_note'],
    );

    $_arr_data[$this->inputSubmit['plugin_dir']] = $_arr_pluginData;

    $_num_size = Config::write(GK_APP_CONFIG . 'plugin' . GK_EXT_INC, $_arr_data);

    if ($_num_size > 0) {
      $_str_rcode = 'y190101';
      $_str_msg   = 'Install plugin successfully';
    } else {
      $_str_rcode = 'x190101';
      $_str_msg   = 'Install plugin failed';
    }

    return array(
      'rcode'     => $_str_rcode, //成功
      'msg'       => $_str_msg,
    );
  }


  /** 删除
   * uninstall function.
   *
   * @access public
   * @return void
   */
  public function uninstall() {
    $_arr_data = $this->configPlugin;

    $_num_count = 0;

    foreach ($this->inputUninstall['plugin_dirs'] as $_key=>$_value) {
      if (isset($_arr_data[$_value])) {
        unset($_arr_data[$_value]);
        ++$_num_count;
      }
    }

    $_num_size = Config::write(GK_APP_CONFIG . 'plugin' . GK_EXT_INC, $_arr_data);

    if ($_num_size > 0) {
      $_str_rcode = 'y190104'; //成功
      $_str_msg   = 'Successfully uninstalled {:count} plugins';
    } else {
      $_str_rcode = 'x190104'; //失败
      $_str_msg   = 'No plugin have been uninstalled';
    }


    return array(
      'count' => $_num_count,
      'rcode' => $_str_rcode,
      'msg'   => $_str_msg,
    );
  }


  public function opts() {
    $_str_optsPath = GK_PATH_PLUGIN . $this->inputOpts['plugin_dir'] . DS . 'opts_var.json';

    unset($this->inputOpts['plugin_id'], $this->inputOpts['plugin_dir'], $this->inputOpts['__token__']);

    $_str_outPut = Arrays::toJson($this->inputOpts);

    $_num_size   = $this->obj_file->fileWrite($_str_optsPath, $_str_outPut);

    if ($_num_size > 0) {
      $_str_rcode = 'y190108'; //成功
      $_str_msg   = 'Update plugin successfully';
    } else {
      $_str_rcode = 'x190103'; //失败
      $_str_msg   = 'Did not make any changes';
    }

    return array(
      'rcode' => $_str_rcode,
      'msg'   => $_str_msg,
    );
  }


  public function inputOpts() {
    $_arr_inputParam = array(
      'plugin_dir'    => array('txt', ''),
      '__token__'     => array('txt', ''),
    );

    $_str_pluginDir = $this->obj_request->post('plugin_dir');

    $_str_optsPath  = GK_PATH_PLUGIN . $_str_pluginDir . DS . 'opts.json';

    if (File::fileHas($_str_optsPath)) {
      $_str_pluginOpts = $this->obj_file->fileRead($_str_optsPath);
      $_arr_pluginOpts = Arrays::fromJson($_str_pluginOpts);

      if (is_array($_arr_pluginOpts) && Func::notEmpty($_arr_pluginOpts)) {
        foreach ($_arr_pluginOpts as $_key=>$_value) {
          $_arr_inputParam[$_key] = array('txt', '');
        }
      }
    }

    $_arr_inputOpts = $this->obj_request->post($_arr_inputParam);

    $_is_vld = $this->vld_plugin->scene('common')->verify($_arr_inputOpts);

    if ($_is_vld !== true) {
      $_arr_message = $this->vld_plugin->getMessage();
      return array(
        'rcode' => 'x190201',
        'msg'   => end($_arr_message),
      );
    }

    $_arr_inputOpts['rcode'] = 'y190201';

    $this->inputOpts = $_arr_inputOpts;

    return $_arr_inputOpts;
  }

  /** 提交输入
   * inputSubmit function.
   *
   * @access public
   * @return void
   */
  public function inputSubmit() {
    $_arr_inputParam = array(
      'plugin_dir'     => array('txt', ''),
      'plugin_note'    => array('txt', ''),
      '__token__'      => array('txt', ''),
    );

    $_arr_inputSubmit = $this->obj_request->post($_arr_inputParam);

    $_is_vld = $this->vld_plugin->scene('common')->verify($_arr_inputSubmit);

    if ($_is_vld !== true) {
      $_arr_message = $this->vld_plugin->getMessage();
      return array(
        'rcode' => 'x190201',
        'msg'   => end($_arr_message),
      );
    }

    $_arr_inputSubmit['rcode'] = 'y190201';

    $this->inputSubmit = $_arr_inputSubmit;

    return $_arr_inputSubmit;
  }


  /** 批量操作选择
   * inputUninstall function.
   *
   * @access public
   * @return void
   */
  public function inputUninstall() {
    $_arr_inputParam = array(
      'plugin_dirs'   => array('arr', array()),
      '__token__'     => array('txt', ''),
    );

    $_arr_inputUninstall = $this->obj_request->post($_arr_inputParam);

    //print_r($_arr_inputUninstall);

    $_arr_inputUninstall['plugin_dirs'] = Arrays::unique($_arr_inputUninstall['plugin_dirs']);

    $_is_vld = $this->vld_plugin->scene('uninstall')->verify($_arr_inputUninstall);

    if ($_is_vld !== true) {
      $_arr_message = $this->vld_plugin->getMessage();
      return array(
        'rcode' => 'x190201',
        'msg'   => end($_arr_message),
      );
    }

    $_arr_inputUninstall['rcode'] = 'y190201';

    $this->inputUninstall = $_arr_inputUninstall;

    return $_arr_inputUninstall;
  }
}
