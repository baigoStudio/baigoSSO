<?php
/*-----------------------------------------------------------------
！！！！警告！！！！
以下为系统文件，请勿修改
-----------------------------------------------------------------*/
namespace app\model\api;

use app\model\User;
use ginkgo\Func;
use ginkgo\Crypt;
use ginkgo\Config;

//不能非法包含或直接执行
defined('IN_GINKGO') or exit('Access denied');

/*-------------用户模型-------------*/
class Login extends User {

    public $inputSubmit;
    public $configBase;
    protected $table = 'user';

    function m_init() { //构造函数
        $this->configBase       = Config::get('base', 'var_extra');
    }


    function login() {
        $_arr_userData = array(
            'user_time_login'   => GK_NOW,
        );

        if (isset($this->inputSubmit['user_ip']) && !Func::isEmpty($this->inputSubmit['user_ip'])) {
            $_str_userIp = $this->inputSubmit['user_ip'];
        } else {
            $_str_userIp = $this->obj_request->ip();
        }
        $_arr_userData['user_ip'] = $_str_userIp;

        $_arr_userRow = $this->read($this->inputSubmit['user_id']);

        if ($_arr_userRow['user_access_expire'] <= GK_NOW) { //如果访问口令过期
            $_str_accessToken   = Func::rand();
            $_tm_accessExpire   = GK_NOW + $this->configBase['access_expire'] * GK_MINUTE;

            $_arr_userData['user_access_token']     = $_str_accessToken;
            $_arr_userData['user_access_expire']    = $_tm_accessExpire;
        } else {
            $_str_accessToken   = $_arr_userRow['user_access_token'];
            $_tm_accessExpire   = $_arr_userRow['user_access_expire'];
        }

        if ($_arr_userRow['user_refresh_expire'] <= GK_NOW) { //如果刷新口令过期
            $_str_refreshToken  = Func::rand();
            $_tm_refreshExpire  = GK_NOW + $this->configBase['refresh_expire'] * GK_DAY;

            $_arr_userData['user_refresh_token']    = $_str_refreshToken;
            $_arr_userData['user_refresh_expire']   = $_tm_refreshExpire;
        } else {
            $_str_refreshToken  = $_arr_userRow['user_refresh_token'];
            $_tm_refreshExpire  = $_arr_userRow['user_refresh_expire'];
        }

        $_num_count     = $this->where('user_id', '=', $_arr_userRow['user_id'])->update($_arr_userData);

        if ($_num_count > 0) {
            $_str_rcode = 'y010103'; //更新成功
            $_str_msg   = 'Login successful';
        } else {
            $_str_rcode = 'x010103';
            $_str_msg   = 'Login failed';
        }

        return array(
            'user_id'               => $_arr_userRow['user_id'],
            //'user_name'             => $_arr_userRow['user_name'],
            //'user_ip'               => $_str_userIp,
            'user_time_login'       => GK_NOW,
            'user_access_token'     => Crypt::crypt($_str_accessToken, $_arr_userRow['user_name']),
            'user_access_expire'    => $_tm_accessExpire,
            'user_refresh_token'    => Crypt::crypt($_str_refreshToken, $_arr_userRow['user_name']),
            'user_refresh_expire'   => $_tm_refreshExpire,
            'rcode'                 => $_str_rcode, //成功
            'msg'                   => $_str_msg,
        );
    }


    /** api 登录表单验证
     * inputSubmit_api function.
     *
     * @access public
     * @return void
     */
    function inputSubmit($arr_data) {
        $_arr_inputParam = array(
            'user_pass'     => array('txt', ''),
            'user_ip'       => array('txt', ''),
            'timestamp'     => array('int', 0),
        );

        $_arr_inputSubmit  = $this->obj_request->fillParam($arr_data, $_arr_inputParam);

        $_arr_inputUserCommon   = $this->inputUserCommon($arr_data);

        $_arr_inputSubmit  = array_replace_recursive($_arr_inputSubmit, $_arr_inputUserCommon);

        $_mix_vld = $this->validate($_arr_inputSubmit, '', 'login');

        if ($_mix_vld !== true) {
            return array(
                'rcode' => 'x010201',
                'msg'   => end($_mix_vld),
            );
        }

        $_arr_inputSubmit['rcode'] = 'y010201';

        $this->inputSubmit = $_arr_inputSubmit;

        return $_arr_inputSubmit;
    }
}

