<?php
/*-----------------------------------------------------------------
！！！！警告！！！！
以下为系统文件，请勿修改
-----------------------------------------------------------------*/
namespace app\model\api;

use app\model\User;
use ginkgo\Json;
use ginkgo\Func;
use ginkgo\Crypt;
use ginkgo\Config;

// 不能非法包含或直接执行
defined('IN_GINKGO') or exit('Access denied');

/*-------------用户模型-------------*/
class Profile extends User {

    public $inputInfo;
    public $inputPass;
    public $inputToken;
    public $configDefault;
    protected $table = 'user';

    function m_init() { //构造函数
        $this->configBase       = Config::get('base', 'var_extra');
    }


    function token($num_userId, $str_userName) {
        $_str_accessToken   = Func::rand();
        $_tm_accessExpire   = GK_NOW + $this->configBase['access_expire'] * GK_MINUTE;

        $_arr_userData = array(
            'user_access_token'     => $_str_accessToken,
            'user_access_expire'    => $_tm_accessExpire,
        );

        $_num_count     = $this->where('user_id', '=', $num_userId)->update($_arr_userData);

        if ($_num_count > 0) {
            $_str_rcode = 'y010103'; //更新成功
            $_str_msg   = 'Refresh access token successfully';
        } else {
            $_str_rcode = 'x010103';
            $_str_msg   = 'Refresh access token failed';
        }

        return array(
            'user_id'               => $num_userId,
            'user_access_token'     => Crypt::crypt($_str_accessToken, $str_userName),
            'user_access_expire'    => $_tm_accessExpire,
            'rcode'                 => $_str_rcode, //成功
            'msg'                   => $_str_msg,
        );
    }


    function info() {
        $_arr_userData = array();

        if (isset($this->inputInfo['user_nick'])) {
            $_arr_userData['user_nick'] = $this->inputInfo['user_nick'];
        }

        if (isset($this->inputInfo['user_contact'])) {
            $_arr_userData['user_contact'] = $this->inputInfo['user_contact'];
        }

        if (isset($this->inputInfo['user_extend'])) {
            $_arr_userData['user_extend'] = $this->inputInfo['user_extend'];
        }

        $_mix_vld = $this->validate($_arr_userData, '', 'info_db');

        if ($_mix_vld !== true) {
            return array(
                'user_id'   => $this->inputInfo['user_id'],
                'rcode'     => 'x010201',
                'msg'       => end($_mix_vld),
            );
        }

        $_arr_userData['user_contact']    = Json::encode($_arr_userData['user_contact']);
        $_arr_userData['user_extend']     = Json::encode($_arr_userData['user_extend']);

        $_num_count     = $this->where('user_id', '=', $this->inputInfo['user_id'])->update($_arr_userData);

        if ($_num_count > 0) {
            $_str_rcode = 'y010103'; //更新成功
            $_str_msg   = 'Update user successfully';
        } else {
            $_str_rcode = 'x010103';
            $_str_msg   = 'Did not make any changes';
        }

        $_arr_return = array(
            'user_id'   => $this->inputInfo['user_id'],
            'rcode'     => $_str_rcode,
            'msg'       => $_str_msg,
        );

        return array_replace_recursive($_arr_userData, $_arr_return);
    }


    /** api 登录表单验证
     * inputInfo_api function.
     *
     * @access public
     * @return void
     */
    function inputInfo($arr_data) {
        $_arr_inputParam = array(
            'user_pass'     => array('txt', ''),
            'user_nick'     => array('txt', ''),
            'user_contact'  => array('arr', array()),
            'user_extend'   => array('arr', array()),
            'timestamp'     => array('int', 0),
        );

        $_arr_inputInfo  = $this->obj_request->fillParam($arr_data, $_arr_inputParam);

        $_arr_inputUserCommon  = $this->inputUserCommon($arr_data);

        $_arr_inputInfo  = array_replace_recursive($_arr_inputInfo, $_arr_inputUserCommon);

        $_mix_vld = $this->validate($_arr_inputInfo, '', 'info');

        if ($_mix_vld !== true) {
            return array(
                'rcode' => 'x010201',
                'msg'   => end($_mix_vld),
            );
        }

        $_arr_inputInfo['rcode'] = 'y010201';

        $this->inputInfo = $_arr_inputInfo;

        return $_arr_inputInfo;
    }


    function inputPass($arr_data) {
        $_arr_inputParam = array(
            'user_pass'     => array('txt', ''),
            'user_pass_new' => array('txt', ''),
            'timestamp'     => array('int', 0),
        );

        $_arr_inputPass  = $this->obj_request->fillParam($arr_data, $_arr_inputParam);

        $_arr_inputUserCommon  = $this->inputUserCommon($arr_data);

        $_arr_inputPass  = array_replace_recursive($_arr_inputPass, $_arr_inputUserCommon);

        $_mix_vld = $this->validate($_arr_inputPass, '', 'pass');

        if ($_mix_vld !== true) {
            return array(
                'rcode' => 'x010201',
                'msg'   => end($_mix_vld),
            );
        }

        $_arr_inputPass['rcode'] = 'y010201';

        $this->inputPass = $_arr_inputPass;

        return $_arr_inputPass;
    }


    function inputSecqa($arr_data) {
        $_arr_inputParam = array(
            'user_pass'        => array('txt', ''),
            'user_sec_ques'    => array('arr', array()),
            'user_sec_answ'    => array('str', ''),
            'timestamp'        => array('int', 0),
        );

        $_arr_inputSecqa  = $this->obj_request->fillParam($arr_data, $_arr_inputParam);

        $_arr_inputUserCommon   = $this->inputUserCommon($arr_data);

        $_arr_inputSecqa  = array_replace_recursive($_arr_inputSecqa, $_arr_inputUserCommon);

        $_mix_vld = $this->validate($_arr_inputSecqa, '', 'secqa');

        if ($_mix_vld !== true) {
            return array(
                'rcode' => 'x010201',
                'msg'   => end($_mix_vld),
            );
        }

        $_arr_inputSecqa['rcode'] = 'y010201';

        $this->inputSecqa = $_arr_inputSecqa;

        return $_arr_inputSecqa;
    }


    function inputMailbox($arr_data) {
        $_arr_inputParam = array(
            'user_pass'        => array('txt', ''),
            'user_mail_new'    => array('txt', ''),
            'timestamp'        => array('int', 0),
        );

        $_arr_inputMailbox  = $this->obj_request->fillParam($arr_data, $_arr_inputParam);

        $_arr_inputUserCommon     = $this->inputUserCommon($arr_data);

        $_arr_inputMailbox  = array_replace_recursive($_arr_inputMailbox, $_arr_inputUserCommon);

        $_mix_vld = $this->validate($_arr_inputMailbox, '', 'mailbox');

        if ($_mix_vld !== true) {
            return array(
                'rcode' => 'x010201',
                'msg'   => end($_mix_vld),
            );
        }

        $_arr_inputMailbox['rcode'] = 'y010201';

        $this->inputMailbox = $_arr_inputMailbox;

        return $_arr_inputMailbox;
    }


    function inputToken($arr_data) {
        $_arr_inputParam = array(
            'user_refresh_token' => array('txt', ''),
            'timestamp'          => array('int', 0),
        );

        $_arr_inputToken  = $this->obj_request->fillParam($arr_data, $_arr_inputParam);

        $_arr_inputUserCommon   = $this->inputUserCommon($arr_data);

        $_arr_inputToken  = array_replace_recursive($_arr_inputToken, $_arr_inputUserCommon);

        $_mix_vld = $this->validate($_arr_inputToken, '', 'token');

        if ($_mix_vld !== true) {
            return array(
                'rcode' => 'x010201',
                'msg'   => end($_mix_vld),
            );
        }

        $_arr_inputToken['rcode'] = 'y010201';

        $this->inputToken = $_arr_inputToken;

        return $_arr_inputToken;
    }
}

