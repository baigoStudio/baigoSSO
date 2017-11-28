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
class CONTROL_API_API_SIGNATURE {

    function __construct() { //构造函数
        $this->general_api  = new GENERAL_API();
        //$this->general_api->chk_install();

        $this->obj_sign = $this->general_api->obj_sign;
    }


    /**
     * ctrl_signature function.
     *
     * @access public
     * @return void
     */
    function ctrl_signature() {
        $_arr_apiChks = $this->general_api->app_chk('post');
        if ($_arr_apiChks['rcode'] != 'ok') {
            $this->general_api->show_result($_arr_apiChks);
        }

        $_arr_params    = fn_post('params');

        $_arr_paramsSrc = array();

        if (!fn_isEmpty($_arr_params)) {
            foreach ($_arr_params as $_key=>$_value) {
                if (!fn_isEmpty($_value)) {
                    $_arr_paramsSrc[$_key] = fn_getSafe($_value, 'txt', '');
                }
            }
        }

        //print_r($_arr_paramsSrc);

        $_str_sign = $this->obj_sign->sign_make($_arr_paramsSrc);

        $_arr_tplData = array(
            'signature'  => $_str_sign,
            'rcode'      => 'y050404',
        );

        $this->general_api->show_result($_arr_tplData);
    }


    /**
     * ctrl_verify function.
     *
     * @access public
     * @return void
     */
    function ctrl_verify() {
        $_arr_apiChks = $this->general_api->app_chk('post');
        if ($_arr_apiChks['rcode'] != 'ok') {
            $this->general_api->show_result($_arr_apiChks);
        }

        $_arr_params = fn_post('params');

        $_arr_signature = fn_validate(fn_post('signature'), 1, 0);
        switch ($_arr_signature['status']) {
            case 'too_short':
                $_arr_tplData = array(
                    'rcode' => 'x050226',
                );
                $this->general_api->show_result($_arr_tplData);
            break;

            case 'ok':
                $_str_sign = $_arr_signature['str'];
            break;
        }

        if ($this->obj_sign->sign_check($_arr_params, $_str_sign)) {
            $_str_rcode = 'y050403';
        } else {
            $_str_rcode = 'x050403';
        }

        $_arr_tplData = array(
            'rcode' => $_str_rcode,
        );

        $this->general_api->show_result($_arr_tplData);
    }
}
