<?php
/*-------------同步类-------------*/
class CONTROL_API_SYNC {

    function __construct() { //构造函数
        $this->obj_notify   = new CLASS_NOTIFY();
        $this->obj_crypt    = new CLASS_CRYPT();
        $this->obj_sign     = new CLASS_SIGN();
    }



    /** 同步登录
     * ctrl_login function.
     *
     * @access public
     * @return void
     */
    function ctrl_login() {
        $_arr_notifyInput = $this->obj_notify->notify_input('get');
        if ($_arr_notifyInput['rcode'] != 'ok') {
            $this->obj_notify->show_result($_arr_notifyInput);
        }

        $_arr_sign = $this->obj_sign->sign_check(json_encode(array_merge($this->arr_data, $_arr_notifyInput)), $_arr_notifyInput['sign'], BG_SSO_APPKEY, BG_SSO_APPSECRET);
        if ($_arr_sign['rcode'] != 'y050403') {
            $this->obj_notify->show_result($_arr_sign);
        }

        $_tm_diff = $_arr_notifyInput['time'] - time();

        if ($_tm_diff > 1800 || $_tm_diff < -1800) {
            $_arr_return = array(
                'rcode'     => 'x350213',
            );
            $this->obj_notify->show_result($_arr_return);
        }

        $_arr_decode    = $this->obj_crypt->decrypt($_arr_notifyInput['code'], BG_SSO_APPKEY, BG_SSO_APPSECRET);

        $_arr_appChk    = $this->obj_notify->app_chk($_arr_decode['app_id'], $_arr_decode['app_key']);
        if ($_arr_appChk['rcode'] != 'ok') {
            $this->obj_notify->show_result($_arr_appChk);
        }


        /* 开始会话等操作  */


        $_arr_return = array(
            'rcode' => 'y020405',
        );
        $this->obj_notify->show_result($_arr_return, false, true);
    }


    /** 同步登出
     * ctrl_logout function.
     *
     * @access public
     * @return void
     */
    function ctrl_logout() {
        $_arr_notifyInput = $this->obj_notify->notify_input('get');
        if ($_arr_notifyInput['rcode'] != 'ok') {
            $this->obj_notify->show_result($_arr_notifyInput);
        }

        $_arr_sign = $this->obj_sign->sign_check(json_encode(array_merge($this->arr_data, $_arr_notifyInput)), $_arr_notifyInput['sign'], BG_SSO_APPKEY, BG_SSO_APPSECRET);
        if ($_arr_sign['rcode'] != 'y050403') {
            $this->obj_notify->show_result($_arr_sign);
        }

        $_tm_diff = $_arr_notifyInput['time'] - time();

        if ($_tm_diff > 1800 || $_tm_diff < -1800) {
            $_arr_return = array(
                'rcode'     => 'x350213',
            );
            $this->obj_notify->show_result($_arr_return);
        }

        $_arr_decode  = $this->obj_crypt->decrypt($_arr_notifyInput['code'], BG_SSO_APPKEY, BG_SSO_APPSECRET);

        $_arr_appChk    = $this->obj_notify->app_chk($_arr_decode['app_id'], $_arr_decode['app_key']);
        if ($_arr_appChk['rcode'] != 'ok') {
            $this->obj_notify->show_result($_arr_appChk);
        }


        /* 结束会话等操作  */


        $_arr_return = array(
            'rcode' => 'y020406',
        );
        $this->obj_notify->show_result($_arr_return, false, true);
    }
}
