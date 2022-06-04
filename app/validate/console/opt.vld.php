<?php
/*-----------------------------------------------------------------
！！！！警告！！！！
以下为系统文件，请勿修改
-----------------------------------------------------------------*/

namespace app\validate\console;

use app\validate\common\Opt as Opt_Common;
use ginkgo\Config;
use ginkgo\Func;

// 不能非法包含或直接执行
if (!defined('IN_GINKGO')) {
  return 'Access denied';
}

/*-------------设置项模型-------------*/
class Opt extends Opt_Common {

  protected function v_init() { //构造函数
    parent::v_init();

    $_arr_rule =  array(
      'method' => array(
        'require' => true,
      ),
      'secure' => array(
        'require' => true
      ),
      'auth' => array(
        'require' => true
      ),
      'user' => array(
        'require' => true
      ),
      'pass' => array(
        'require' => true
      ),
      'from_addr' => array(
        'require' => true,
        'format' => 'email'
      ),
      'reply_addr' => array(
        'format' => 'email'
      ),
      'site_timezone' => array(
        'require' => true
      ),
      'site_tpl' => array(
        'require' => true
      ),
    );

    $_arr_scene = array(
      'smtp' => array(
        'method',
        'host',
        'port',
        'secure',
        'auth',
        'user',
        'pass',
        'from_addr',
        'reply_addr',
        '__token__',
      ),
      'func' => array(
        'method',
        '__token__',
      ),
      'mailtpl' => array(
        '__token__',
      ),
    );

    $_arr_attrName = array(
      'method'        => $this->obj_lang->get('Send method'),
      'host'          => $this->obj_lang->get('SMTP host'),
      'secure'        => $this->obj_lang->get('Secure type'),
      'auth'          => $this->obj_lang->get('Server authentication'),
      'user'          => $this->obj_lang->get('Username'),
      'pass'          => $this->obj_lang->get('Password'),
      'from_addr'     => $this->obj_lang->get('From'),
      'reply_addr'    => $this->obj_lang->get('Reply'),
      'site_timezone' => $this->obj_lang->get('Timezone'),
      'site_tpl'      => $this->obj_lang->get('Template'),
      '__token__'     => $this->obj_lang->get('Token'),
    );

    $_arr_typeMsg = array(
      'require'   => $this->obj_lang->get('{:attr} require'),
      'token'     => $this->obj_lang->get('Form token is incorrect'),
    );

    $_arr_formatMsg = array(
      'url' => $this->obj_lang->get('{:attr} not a valid url'),
    );

    $_arr_config = Config::get('mailtpl', 'console');

    if (Func::notEmpty($_arr_config)) {
      foreach ($_arr_config as $_key=>$_value) {
        foreach ($_value['lists'] as $_key_list=>$_value_list) {
          $_arr_rule[$_key . '_' . $_key_list]['require'] = 'true';
          $_arr_scene['mailtpl'][] = $_key . '_' . $_key_list;
          $_arr_attrName[$_key . '_' . $_key_list] = $this->obj_lang->get($_value_list['title']);
        }
      }
    }

    $_arr_config = Config::get('opt', 'console');

    if (Func::notEmpty($_arr_config)) {
      foreach ($_arr_config as $_key=>$_value) {
        $_arr_scene[$_key][] = '__token__';

        if ($_key == 'base') {
          $_arr_scene[$_key][] = 'site_timezone';
          $_arr_scene[$_key][] = 'site_tpl';
        }

        if (isset($_value['lists']) && Func::notEmpty($_value['lists'])) {
          foreach ($_value['lists'] as $_key_list=>$_value_list) {
            $_arr_rule[$_key_list]['require'] = $_value_list['require'];

            if (isset($_value_list['format'])) {
              $_arr_rule[$_key_list]['format'] = $_value_list['format'];
            }

            $_arr_scene[$_key][] = $_key_list;
            $_arr_attrName[$_key_list]  = $this->obj_lang->get($_value_list['title']);
          }
        }
      }
    }

    $this->rule($_arr_rule);
    $this->setScene($_arr_scene);
    $this->setAttrName($_arr_attrName);
    $this->setTypeMsg($_arr_typeMsg);
    $this->setFormatMsg($_arr_formatMsg);
  }
}
