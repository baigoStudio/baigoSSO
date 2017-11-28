<?php
/*-----------------------------------------------------------------
！！！！警告！！！！
以下为系统文件，请勿修改
-----------------------------------------------------------------*/

//不能非法包含或直接执行
if (!defined('IN_BAIGO')) {
    exit('Access Denied');
}

/*-------------用户类-------------*/
class CONTROL_API_API_CODE {

    function __construct() { //构造函数
        $this->general_api      = new GENERAL_API();
        //$this->general_api->chk_install();

        $this->obj_crypt    = $this->general_api->obj_crypt;
        $this->obj_sign     = $this->general_api->obj_sign;
    }


    /**
     * ctrl_encode function.
     *
     * @access public
     * @return void
     */
    function ctrl_encode() {
        $_arr_apiChks = $this->general_api->app_chk('post');
        if ($_arr_apiChks['rcode'] != 'ok') {
            $this->general_api->show_result($_arr_apiChks);
        }

        $_arr_data = fn_validate(fn_post('data'), 1, 0);
        switch ($_arr_data['status']) {
            case 'too_short':
                $_arr_tplData = array(
                    'rcode' => 'x050222',
                );
                $this->general_api->show_result($_arr_tplData);
            break;

            case 'ok':
                $_str_data = fn_htmlcode($_arr_data['str'], 'decode');
            break;
        }

        $_arr_sign = array(
            'act'   => $GLOBALS['route']['bg_act'],
            'data'  => $_str_data,
        );

        if (!$this->obj_sign->sign_check(array_merge($_arr_apiChks['appInput'], $_arr_sign), $_arr_apiChks['appInput']['signature'])) {
            $_arr_tplData = array(
                'rcode' => 'x050403',
            );
            $this->general_api->show_result($_arr_tplData);
        }

        $_str_code = $this->obj_crypt->encrypt($_str_data, fn_baigoCrypt($_arr_apiChks['appRow']['app_key'], $_arr_apiChks['appRow']['app_name']));

        $_arr_tplData = array(
            'code'   => $_str_code,
            'rcode'  => 'y050405',
        );

        $this->general_api->show_result($_arr_tplData);
    }


    /**
     * ctrl_decode function.
     *
     * @access public
     * @return void
     */
    function ctrl_decode() {
        $_arr_apiChks = $this->general_api->app_chk('post');
        if ($_arr_apiChks['rcode'] != 'ok') {
            $this->general_api->show_result($_arr_apiChks);
        }

        $_arr_code = fn_validate(fn_post('code'), 1, 0);
        switch ($_arr_code['status']) {
            case 'too_short':
                $_arr_tplData = array(
                    'rcode' => 'x050223',
                );
                $this->general_api->show_result($_arr_tplData);
            break;

            case 'ok':
                $_str_code = $_arr_code['str'];
            break;
        }

        $_arr_sign = array(
            'act'   => $GLOBALS['route']['bg_act'],
            'code'  => $_str_code,
        );

        if (!$this->obj_sign->sign_check(array_merge($_arr_apiChks['appInput'], $_arr_sign), $_arr_apiChks['appInput']['signature'])) {
            $_arr_tplData = array(
                'rcode' => 'x050403',
            );
            $this->general_api->show_result($_arr_tplData);
        }

        $_str_result = $this->obj_crypt->decrypt($_str_code, fn_baigoCrypt($_arr_apiChks['appRow']['app_key'], $_arr_apiChks['appRow']['app_name']));

        exit($_str_result);
    }
}
