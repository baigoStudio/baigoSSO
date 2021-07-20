<?php
/*-----------------------------------------------------------------
！！！！警告！！！！
以下为系统文件，请勿修改
-----------------------------------------------------------------*/
namespace app\model\api;

use app\model\User as User_Base;
use ginkgo\Config;
use ginkgo\Arrays;
use ginkgo\Func;

// 不能非法包含或直接执行
if (!defined('IN_GINKGO')) {
    return 'Access denied';
}

/*-------------用户模型-------------*/
class Reg extends User_Base {

    public $inputReg;
    protected $table = 'user';

    function m_init() {
        $this->configReg     = Config::get('reg', 'var_extra');
    }

    function reg() {
        $_arr_userData = array(
            'user_name'         => $this->inputReg['user_name'],
            'user_pass'         => $this->inputReg['user_pass'],
            'user_status'       => $this->inputReg['user_status'],
            'user_rand'         => $this->inputReg['user_rand'],
            'user_time'         => GK_NOW,
            'user_time_login'   => GK_NOW,
        );

        if (isset($this->inputReg['user_mail'])) {
            $_arr_userData['user_mail'] = $this->inputReg['user_mail'];
        }

        if (isset($this->inputReg['user_nick'])) {
            $_arr_userData['user_nick'] = $this->inputReg['user_nick'];
        }

        if (isset($this->inputReg['user_contact'])) {
            $_arr_userData['user_contact'] = $this->inputReg['user_contact'];
        }

        if (isset($this->inputReg['user_extend'])) {
            $_arr_userData['user_extend'] = $this->inputReg['user_extend'];
        }

        if (isset($this->inputReg['user_app_id'])) {
            $_arr_userData['user_app_id'] = $this->inputReg['user_app_id'];
        }

        if (isset($this->inputReg['user_ip'])) {
            $_arr_userData['user_ip'] = $this->inputReg['user_ip'];
        } else {
            $_arr_userData['user_ip'] = $this->obj_request->ip();
        }

        $_mix_vld = $this->validate($_arr_userData, '', 'reg_db');

        //print_r($_mix_vld);

        if ($_mix_vld !== true) {
            return array(
                'rcode' => 'x010201',
                'msg'   => end($_mix_vld),
            );
        }

        $_arr_userData['user_contact']    = Arrays::toJson($_arr_userData['user_contact']);
        $_arr_userData['user_extend']     = Arrays::toJson($_arr_userData['user_extend']);

        $_num_userId     = $this->insert($_arr_userData);

        if ($_num_userId > 0) {
            $_str_rcode  = 'y010101'; //注册成功
            $_str_msg    = 'Registration successfully';
        } else {
            $_str_rcode  = 'x010101'; //注册成功
            $_str_msg    = 'Registration failed';
        }

        $_arr_return = array(
            'user_id'       => $_num_userId,
            'user_name'     => $this->inputReg['user_name'],
            'user_status'   => $this->inputReg['user_status'],
            'rcode'         => $_str_rcode,
            'msg'           => $_str_msg,
        );

        $_arr_return = array_replace_recursive($this->inputReg, $_arr_return);

        return $this->rowProcess($_arr_return);
    }


    /** api 注册表单验证
     * inputReg_api function.
     *
     * @access public
     * @return void
     */
    function inputReg($arr_data) {
        $_arr_inputParam = array(
            'user_name'     => array('txt', ''),
            'user_mail'     => array('txt', ''),
            'user_pass'     => array('txt', ''),
            'user_nick'     => array('txt', ''),
            'user_ip'       => array('txt', ''),
            'user_contact'  => array('arr', array()),
            'user_extend'   => array('arr', array()),
            'timestamp'     => array('int', 0),
        );

        $_arr_inputReg = $this->obj_request->fillParam($arr_data, $_arr_inputParam);

        $_mix_vld = $this->validate($_arr_inputReg, '', 'reg');

        if ($_mix_vld !== true) {
            return array(
                'rcode' => 'x010201',
                'msg'   => end($_mix_vld),
            );
        }

        $_arr_checkResult   = $this->nameChkProcess($_arr_inputReg['user_name']);

        if ($_arr_checkResult['rcode'] != 'y010401') {
            return $_arr_checkResult;
        }

        if (!Func::isEmpty($_arr_inputReg['user_mail'])) {
            $_arr_checkResult   = $this->nameChkProcess($_arr_inputReg['user_mail']);

            if ($_arr_checkResult['rcode'] != 'y010401') {
                return $_arr_checkResult;
            }
        }

        if ($_arr_configReg['reg_confirm'] === 'on') { //开启验证则为等待
            $_arr_inputReg['user_status'] = 'wait';
        } else {
            $_arr_inputReg['user_status'] = 'enable';
        }

        $_arr_inputReg['rcode'] = 'y010201';

        $this->inputReg = $_arr_inputReg;

        return $_arr_inputReg;
    }


    function inputChkname($arr_data) {
        $_arr_inputParam = array(
            'user_name'     => array('txt', ''),
            'timestamp'     => array('int', 0),
        );

        $_arr_inputChkname = $this->obj_request->fillParam($arr_data, $_arr_inputParam);

        $_mix_vld = $this->validate($_arr_inputChkname, '', 'chkname');

        if ($_mix_vld !== true) {
            return array(
                'rcode' => 'x010201',
                'msg'   => end($_mix_vld),
            );
        }

        $_arr_checkResult   = $this->nameChkProcess($_arr_inputChkname['user_name']);

        if ($_arr_checkResult['rcode'] != 'y010401') {
            return $_arr_checkResult;
        }

        $_arr_inputChkname['rcode'] = 'y010201';

        $this->inputChkname = $_arr_inputChkname;

        return $_arr_inputChkname;
    }


    function inputChkmail($arr_data) {
        $_arr_inputParam = array(
            'user_mail'     => array('txt', ''),
            'timestamp'     => array('int', 0),
        );

        $_arr_inputChkmail = $this->obj_request->fillParam($arr_data, $_arr_inputParam);

        $_mix_vld = $this->validate($_arr_inputChkmail, '', 'chkmail');

        if ($_mix_vld !== true) {
            return array(
                'rcode' => 'x010201',
                'msg'   => end($_mix_vld),
            );
        }

        $_arr_checkResult   = $this->nameChkProcess($_arr_inputChkmail['user_mail']);

        if ($_arr_checkResult['rcode'] != 'y010401') {
            return $_arr_checkResult;
        }

        $_arr_inputChkmail['rcode'] = 'y010201';

        $this->inputChkmail = $_arr_inputChkmail;

        return $_arr_inputChkmail;
    }


    private function nameChkProcess($str_userName) {
        $_arr_configReg     = $this->configReg;

        if (isset($_arr_configReg['bad_name']) && !Func::isEmpty($_arr_configReg['bad_name'])) {
            $_str_badName = str_replace(PHP_EOL, '|', $_arr_configReg['bad_name']);

            if (Func::checkRegex($str_userName, $_str_badName, true)) {
                return array(
                    'rcode' => 'x010201',
                    'msg'   => 'Illegal content in username',
                );
            }
        }

        $_arr_checkResult   = $this->check($str_userName, 'user_name');

        if ($_arr_checkResult['rcode'] == 'y010102') {
            return array(
                'user_id' => $_arr_checkResult['user_id'],
                'rcode'   => 'x010404',
                'msg'     => 'User already exists',
            );
        } else {
            return array(
                'rcode' => 'y010401',
                'msg'   => 'Username can be registered',
            );
        }
    }


    private function mailChkProcess($str_userMail) {
        $_arr_configReg     = $this->configReg;

        if (isset($_arr_configReg['acc_mail']) && !Func::isEmpty($_arr_configReg['acc_mail'])) {
            $_str_accName = str_replace(PHP_EOL, '|', $_arr_configReg['acc_mail']);

            if (!Func::checkRegex($str_userMail, $_str_accName, true)) {
                return array(
                    'rcode' => 'x010201',
                    'msg'   => 'Email is not allowed',
                );
            }
        } else if (isset($_arr_configReg['bad_mail']) && !Func::isEmpty($_arr_configReg['bad_mail'])) {
            $_str_badName = str_replace(PHP_EOL, '|', $_arr_configReg['bad_mail']);

            if (Func::checkRegex($str_userMail, $_str_badName, true)) {
                return array(
                    'rcode' => 'x010201',
                    'msg'   => 'Illegal content in mailbox',
                );
            }
        }

        $_arr_checkResult   = $this->check($str_userMail, 'user_mail');

        if ($_arr_checkResult['rcode'] == 'y010102') {
            return array(
                'user_id' => $_arr_checkResult['user_id'],
                'rcode'   => 'x010404',
                'msg'     => 'Mailbox already exists',
            );
        } else {
            return array(
                'rcode' => 'y010401',
                'msg'   => 'Mailbox can be used',
            );
        }
    }
}
