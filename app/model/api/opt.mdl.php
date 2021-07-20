<?php
/*-----------------------------------------------------------------
！！！！警告！！！！
以下为系统文件，请勿修改
-----------------------------------------------------------------*/

namespace app\model\api;

use app\model\Opt as Opt_Base;
use ginkgo\Config;

// 不能非法包含或直接执行
if (!defined('IN_GINKGO')) {
    return 'Access denied';
}

/*-------------设置项模型-------------*/
class Opt extends Opt_Base {

    function security() {
        $_arr_outPut = array(
            'key'       => $this->inputCommon['key'],
            'secret'    => $this->inputSecurity['secret'],
        );

        $_num_size   = Config::write(GK_PATH_TEMP . 'security' . GK_EXT_INC, $_arr_outPut);

        if ($_num_size > 0) {
            $_str_rcode = 'y030401';
            $_str_msg   = 'Security set successful';
        } else {
            $_str_rcode = 'x030401';
            $_str_msg   = 'Security set failed';
        }

        return array(
            'rcode' => $_str_rcode,
            'msg'   => $_str_msg,
        );
    }


    function inputSecurity($arr_data = array()) {
        $_arr_inputParam = array(
            'secret'    => array('txt', ''),
            'timestamp' => array('int', 0),
        );

        $_arr_inputSecurity = $this->obj_request->fillParam($arr_data, $_arr_inputParam);

        $_is_vld = $this->vld_opt->scene('security')->verify($_arr_inputSecurity);

        if ($_is_vld !== true) {
            $_arr_message = $this->vld_opt->getMessage();
            return array(
                'rcode' => 'x030201',
                'msg'   => end($_arr_message),
            );
        }

        $_arr_inputSecurity['rcode'] = 'y030201';

        $this->inputSecurity = $_arr_inputSecurity;

        return $_arr_inputSecurity;
    }


    function inputDbconfig($arr_data = array()) {
        $_arr_inputParam = array(
            'host'          => array('txt', 'localhost'),
            'port'          => array('int', 3306),
            'name'          => array('txt', ''),
            'user'          => array('txt', ''),
            'pass'          => array('txt', ''),
            'charset'       => array('txt', ''),
            'prefix'        => array('txt', ''),
            'timestamp'     => array('int', 0),
        );

        $_arr_inputDbconfig = $this->obj_request->fillParam($arr_data, $_arr_inputParam);

        $_is_vld = $this->vld_opt->scene('dbconfig')->verify($_arr_inputDbconfig);

        if ($_is_vld !== true) {
            $_arr_message = $this->vld_opt->getMessage();
            return array(
                'rcode' => 'x030201',
                'msg'   => end($_arr_message),
            );
        }

        $_arr_inputDbconfig['rcode'] = 'y030201';

        $this->inputDbconfig = $_arr_inputDbconfig;

        return $_arr_inputDbconfig;
    }


    function inputTimestamp($arr_data) {
        $_arr_inputParam = array(
            'timestamp' => array('int', 0),
        );

        $_arr_inputTimestamp = $this->obj_request->fillParam($arr_data, $_arr_inputParam);

        $_is_vld = $this->vld_opt->scene('timestamp')->verify($_arr_inputTimestamp);

        if ($_is_vld !== true) {
            $_arr_message = $this->vld_opt->getMessage();
            return array(
                'rcode' => 'x030201',
                'msg'   => end($_arr_message),
            );
        }

        $_arr_inputTimestamp['rcode'] = 'y030201';

        $this->inputTimestamp = $_arr_inputTimestamp;

        return $_arr_inputTimestamp;
    }


    function inputCommon() {
        $_arr_inputParam = array(
            'key'   => array('txt', ''),
            'sign'  => array('txt', ''),
            'code'  => array('txt', '', true),
        );

        $_arr_inputCommon = $this->obj_request->request($_arr_inputParam);

        $_is_vld = $this->vld_opt->scene('common')->verify($_arr_inputCommon);

        if ($_is_vld !== true) {
            $_arr_message = $this->vld_opt->getMessage();
            return array(
                'rcode' => 'x030201',
                'msg'   => end($_arr_message),
            );
        }

        $_arr_inputCommon['rcode'] = 'y030201';

        $this->inputCommon = $_arr_inputCommon;

        return $_arr_inputCommon;
    }
}
