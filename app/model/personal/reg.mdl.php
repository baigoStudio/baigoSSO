<?php
/*-----------------------------------------------------------------
！！！！警告！！！！
以下为系统文件，请勿修改
-----------------------------------------------------------------*/

namespace app\model\personal;

use app\model\User as User_Base;
use ginkgo\Loader;

// 不能非法包含或直接执行
defined('IN_GINKGO') or exit('Access denied');

/*-------------管理员模型-------------*/
class Reg extends User_Base {

    public $inputReg;
    public $inputNomail;
    protected $table = 'user';

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

        $_mix_vld = $this->validate($_arr_userData, '', 'reg_db');

        if ($_mix_vld !== true) {
            return array(
                'rcode' => 'x010201',
                'msg'   => end($_mix_vld),
            );
        }

        $_num_userId     = $this->insert($_arr_userData);

        if ($_num_userId > 0) {
            $_str_rcode  = 'y010101'; //注册成功
            $_str_msg    = 'Registration successfully';
        } else {
            $_str_rcode  = 'x010101'; //注册成功
            $_str_msg    = 'Registration failed';
        }

        return array(
            'user_id'   => $_num_userId,
            'rcode'     => $_str_rcode,
            'msg'       => $_str_msg,
        );
    }


    function inputReg() {
        $_arr_inputParam = array(
            'user_name'         => array('txt', ''),
            'user_mail'         => array('txt', ''),
            'user_pass'         => array('txt', ''),
            'user_pass_confirm' => array('txt', ''),
            'captcha'           => array('txt', ''),
            '__token__'         => array('txt', ''),
        );

        $_arr_inputReg = $this->obj_request->post($_arr_inputParam);

        $_mix_vld = $this->validate($_arr_inputReg, '', 'reg');

        if ($_mix_vld !== true) {
            return array(
                'rcode' => 'x010201',
                'msg'   => end($_mix_vld),
            );
        }

        $_arr_configReg     = Config::get('reg', 'var_extra');

        if (isset($_arr_configReg['bad_name']) && !Func::isEmpty($_arr_configReg['bad_name'])) {
            $_str_badName = str_replace(PHP_EOL, '|', $_arr_configReg['bad_name']);

            if (Func::checkRegex($_arr_inputReg['user_name'], $_str_badName, true)) {
                return array(
                    'rcode' => 'x010201',
                    'msg'   => 'Illegal content in username',
                );
            }
        }

        if (isset($_arr_configReg['acc_mail']) && !Func::isEmpty($_arr_configReg['acc_mail'])) {
            $_str_accName = str_replace(PHP_EOL, '|', $_arr_configReg['acc_mail']);

            if (!Func::checkRegex($_arr_inputReg['user_mail'], $_str_accName, true)) {
                return array(
                    'rcode' => 'x010201',
                    'msg'   => 'Email is not allowed',
                );
            }
        } else if (isset($_arr_configReg['bad_mail']) && !Func::isEmpty($_arr_configReg['bad_mail'])) {
            $_str_badName = str_replace(PHP_EOL, '|', $_arr_configReg['bad_mail']);

            if (Func::checkRegex($_arr_inputReg['user_mail'], $_str_badName, true)) {
                return array(
                    'rcode' => 'x010201',
                    'msg'   => 'Illegal content in mailbox',
                );
            }
        }

        $_arr_userRow = $this->check($_arr_inputReg['user_name'], 'user_name');

        if ($_arr_userRow['rcode'] == 'y010102') {
            return array(
                'rcode' => 'x010404',
                'msg'   => 'User already exists',
            );
        }

        if (!Func::isEmpty($_arr_inputReg['user_mail'])) {
            $_arr_checkResult = $this->check($_arr_inputReg['user_mail'], 'user_mail'); //检查邮箱

            return array(
                'rcode' => 'x010404',
                'msg'   => 'Mailbox already exists',
            );
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


    /** 通过邮件找回密码, 输入验证
     * inputNomail function.
     *
     * @access public
     * @return void
     */
    function inputNomail() {
        $_arr_inputParam = array(
            'user_name' => array('txt', ''),
            'captcha'   => array('txt', ''),
            '__token__' => array('txt', ''),
        );

        $_arr_inputNomail = $this->obj_request->post($_arr_inputParam);

        $_mix_vld = $this->validate($_arr_inputNomail, '', 'by_mail');

        if ($_mix_vld !== true) {
            return array(
                'rcode' => 'x010201',
                'msg'   => end($_mix_vld),
            );
        }

        $_arr_inputNomail['rcode'] = 'y010201';

        $this->inputNomail = $_arr_inputNomail;

        return $_arr_inputNomail;
    }
}
