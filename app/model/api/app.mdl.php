<?php
/*-----------------------------------------------------------------
！！！！警告！！！！
以下为系统文件，请勿修改
-----------------------------------------------------------------*/

namespace app\model\api;

use app\model\App as App_Base;
use ginkgo\Config;
use ginkgo\Func;
use ginkgo\Crypt;
use ginkgo\Arrays;
use ginkgo\Html;

// 不能非法包含或直接执行
if (!defined('IN_GINKGO')) {
    return 'Access denied';
}

/*-------------应用模型-------------*/
class App extends App_Base {

    function submit() {
        $_str_appKey    = Func::rand(64);
        $_str_appSecret = Func::rand(16);

        $_arr_appData = array(
            'app_name'          => $this->inputSubmit['app_name'],
            'app_url_notify'    => $this->inputSubmit['app_url_notify'],
            'app_url_sync'      => $this->inputSubmit['app_url_sync'],
            'app_note'          => $this->inputSubmit['app_name'],
            'app_allow'         => $this->inputSubmit['app_allow'],
            'app_status'        => 'enable',
            'app_sync'          => 'on',
            'app_key'           => $_str_appKey,
            'app_secret'        => $_str_appSecret,
            'app_time'          => GK_NOW,
        );

        $_mix_vld = $this->validate($_arr_appData, '', 'submit_db');

        if ($_mix_vld !== true) {
            return array(
                'app_id'        => 0,
                'app_key'       => '',
                'app_secret'    => '',
                'rcode'         => 'x050201',
                'msg'           => end($_mix_vld),
            );
        }

        $_arr_appData['app_allow'] = Arrays::toJson($_arr_appData['app_allow']);

        $_num_appId  = $this->insert($_arr_appData);

        if ($_num_appId > 0) {
            $_str_rcode = 'y050101'; //更新成功
            $_str_msg   = 'Add App successfully';
        } else {
            $_str_rcode = 'x050101'; //更新失败
            $_str_msg   = 'Add App failed';
        }

        return array(
            'app_id'        => $_num_appId,
            'app_key'       => Crypt::crypt($_str_appKey, $this->inputSubmit['app_name']),
            'app_secret'    => $_str_appSecret,
            'rcode'         => $_str_rcode, //成功
            'msg'           => $_str_msg,
        );
    }


    function inputSubmit($arr_data) {
        $_arr_inputParam = array(
            'app_name'          => array('txt', ''),
            'app_url_notify'    => array('txt', ''),
            'app_url_sync'      => array('txt', ''),
            'timestamp'         => array('int', 0),
        );

        $_arr_inputSubmit = $this->obj_request->fillParam($arr_data, $_arr_inputParam);

        //print_r($_arr_inputSubmit);
        $_str_configAllow   = BG_PATH_CONFIG . 'console' . DS . 'app' . GK_EXT_INC;
        $_arr_allowRows     = Config::load($_str_configAllow, 'app', 'console');

        foreach ($_arr_allowRows as $_key=>$_value) {
            foreach ($_value['allow'] as $_key_sub=>$_value_sub) {
                $_arr_appAllow[$_key][$_key_sub] = 1;
            }
        }

        $_arr_inputSubmit['app_allow']        = $_arr_appAllow;
        $_arr_inputSubmit['app_url_notify']   = Html::decode($_arr_inputSubmit['app_url_notify'], 'url');
        $_arr_inputSubmit['app_url_sync']     = Html::decode($_arr_inputSubmit['app_url_sync'], 'url');

        $_mix_vld = $this->validate($_arr_inputSubmit, '', 'submit');

        if ($_mix_vld !== true) {
            return array(
                'rcode' => 'x050201',
                'msg'   => end($_mix_vld),
            );
        }

        $_arr_inputSubmit['rcode'] = 'y050201';

        $this->inputSubmit = $_arr_inputSubmit;

        return $_arr_inputSubmit;
    }


    /** 表单验证
     * inputSubmit function.
     *
     * @access public
     * @return void
     */
    function inputCommon() {
        $_arr_inputParam = array(
            'app_id'    => array('int', 0),
            'app_key'   => array('txt', ''),
            'sign'      => array('txt', ''),
            'code'      => array('txt', ''),
            'type'      => array('txt', 'json'),
        );

        $_arr_inputCommon = $this->obj_request->request($_arr_inputParam);

        $_mix_vld = $this->validate($_arr_inputCommon, '', 'common');

        if ($_mix_vld !== true) {
            return array(
                'rcode' => 'x050201',
                'msg'   => end($_mix_vld),
            );
        }

        $_arr_inputCommon['rcode'] = 'y050201';

        $this->inputCommon = $_arr_inputCommon;

        return $_arr_inputCommon;
    }
}
