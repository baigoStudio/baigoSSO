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
class CONTROL_API_API_PM {

    function __construct() { //构造函数
        $this->config           = $GLOBALS['obj_base']->config;

        $this->general_api          = new GENERAL_API();
        //$this->general_api->chk_install();

        $this->obj_crypt        = $this->general_api->obj_crypt;
        $this->obj_sign         = $this->general_api->obj_sign;

        $this->mdl_pm           = new MODEL_PM();
        $this->mdl_user_api     = new MODEL_USER_API();
        $this->mdl_user_profile = new MODEL_USER_PROFILE();
    }

    /**
     * ctrl_reg function.
     *
     * @access public
     * @return void
     */
    function ctrl_send() {
        $_arr_apiChks = $this->general_api->app_chk('post');
        if ($_arr_apiChks['rcode'] != 'ok') {
            $this->general_api->show_result($_arr_apiChks);
        }

        $_arr_decrypt = $this->obj_crypt->decrypt($_arr_apiChks['appInput']['code'], $_arr_apiChks['appKey'], $_arr_apiChks['appRow']['app_secret']);  //解密数据

        if ($_arr_decrypt['rcode'] != 'ok') {
            $this->general_api->show_result($_arr_decrypt);
        }

        $_arr_inputSend = fn_jsonDecode($_arr_decrypt['decrypt'], true);

        $this->user_check($_arr_inputSend);

        $_arr_pmSend    = $this->mdl_pm->input_send_api($_arr_inputSend);
        if ($_arr_pmSend['rcode'] != 'ok') {
            $this->general_api->show_result($_arr_pmSend['rcode']);
        }

        if (!$this->obj_sign->sign_check($_arr_decrypt['decrypt'], $_arr_apiChks['appInput']['sign'], $_arr_apiChks['appKey'], $_arr_apiChks['appRow']['app_secret'])) {
            $_arr_return = array(
                'rcode' => 'x050403',
            );
            $this->general_api->show_result($_arr_return);
        }

        if (stristr($_arr_pmSend['pm_to'], '|')) {
            $_arr_pmTo = explode('|', $_arr_pmSend['pm_to']);
        } else {
            $_arr_pmTo = array($_arr_pmSend['pm_to']);
        }

        $_arr_pmTo = array_filter(array_unique($_arr_pmTo));

        $_arr_pmRows = array();

        foreach ($_arr_pmTo as $_key=>$_value) {
            $_arr_toUser = $this->mdl_user_api->mdl_read($_value, 'user_name');
            if ($_arr_toUser['rcode'] == 'y010102') {
                $_arr_pmRows[$_key] = $this->mdl_pm->mdl_submit($_arr_toUser['user_id'], $this->userRow['user_id']);
                $_arr_pmRows[$_key]['pm_to'] = $_arr_toUser['user_id'];
            } else {
                $_arr_pmRows[$_key]['rcode'] = 'x110101';
            }
        }

        $_arr_tplData = array(
            'rcode' => $_arr_pmRows[$_key]['rcode'],
        );
        $this->general_api->show_result($_arr_tplData);
    }


    function ctrl_revoke() {
        $_arr_apiChks = $this->general_api->app_chk('post');
        if ($_arr_apiChks['rcode'] != 'ok') {
            $this->general_api->show_result($_arr_apiChks);
        }

        $_arr_decrypt = $this->obj_crypt->decrypt($_arr_apiChks['appInput']['code'], $_arr_apiChks['appKey'], $_arr_apiChks['appRow']['app_secret']);  //解密数据

        if ($_arr_decrypt['rcode'] != 'ok') {
            $this->general_api->show_result($_arr_decrypt);
        }

        $_arr_inputRevoke = fn_jsonDecode($_arr_decrypt['decrypt'], true);

        $this->user_check($_arr_inputRevoke);

        $_arr_pmIds   = $this->mdl_pm->input_ids_api($_arr_inputRevoke);

        if (!$this->obj_sign->sign_check($_arr_decrypt['decrypt'], $_arr_apiChks['appInput']['sign'], $_arr_apiChks['appKey'], $_arr_apiChks['appRow']['app_secret'])) {
            $_arr_return = array(
                'rcode' => 'x050403',
            );
            $this->general_api->show_result($_arr_return);
        }

        $_arr_pmDel = $this->mdl_pm->mdl_del($this->userRow['user_id'], true);

        $this->general_api->show_result($_arr_pmDel);
    }


    function ctrl_status() {
        $_arr_apiChks = $this->general_api->app_chk('post');
        if ($_arr_apiChks['rcode'] != 'ok') {
            $this->general_api->show_result($_arr_apiChks);
        }

        $_arr_decrypt = $this->obj_crypt->decrypt($_arr_apiChks['appInput']['code'], $_arr_apiChks['appKey'], $_arr_apiChks['appRow']['app_secret']);  //解密数据

        if ($_arr_decrypt['rcode'] != 'ok') {
            $this->general_api->show_result($_arr_decrypt);
        }

        $_arr_inputStatus = fn_jsonDecode($_arr_decrypt['decrypt'], true);

        $this->user_check($_arr_inputStatus);

        $_arr_pmIds   = $this->mdl_pm->input_ids_api($_arr_inputStatus);

        $_str_status = '';

        if (isset($_arr_inputStatus['pm_status'])) {
            $_str_status = $_arr_inputStatus['pm_status'];
        }

        $_str_status = fn_getSafe($_str_status, 'txt', '');
        if (fn_isEmpty($_str_status)) {
            $_arr_return = array(
                'rcode' => 'x110219',
            );
            $this->general_api->show_result($_arr_return);
        }

        if (!$this->obj_sign->sign_check($_arr_decrypt['decrypt'], $_arr_apiChks['appInput']['sign'], $_arr_apiChks['appKey'], $_arr_apiChks['appRow']['app_secret'])) {
            $_arr_return = array(
                'rcode' => 'x050403',
            );
            $this->general_api->show_result($_arr_return);
        }

        $_arr_pmStatus = $this->mdl_pm->mdl_status($_str_status, $this->userRow['user_id']);

        $this->general_api->show_result($_arr_pmStatus);
    }

    /**
     * ctrl_del function.
     *
     * @access public
     * @return void
     */
    function ctrl_del() {
        $_arr_apiChks = $this->general_api->app_chk('post');
        if ($_arr_apiChks['rcode'] != 'ok') {
            $this->general_api->show_result($_arr_apiChks);
        }

        $_arr_decrypt = $this->obj_crypt->decrypt($_arr_apiChks['appInput']['code'], $_arr_apiChks['appKey'], $_arr_apiChks['appRow']['app_secret']);  //解密数据

        if ($_arr_decrypt['rcode'] != 'ok') {
            $this->general_api->show_result($_arr_decrypt);
        }

        $_arr_inputDel = fn_jsonDecode($_arr_decrypt['decrypt'], true);

        $this->user_check($_arr_inputDel);

        $_arr_pmIds   = $this->mdl_pm->input_ids_api($_arr_inputDel);

        if (!$this->obj_sign->sign_check($_arr_decrypt['decrypt'], $_arr_apiChks['appInput']['sign'], $_arr_apiChks['appKey'], $_arr_apiChks['appRow']['app_secret'])) {
            $_arr_return = array(
                'rcode' => 'x050403',
            );
            $this->general_api->show_result($_arr_return);
        }

        $_arr_pmDel = $this->mdl_pm->mdl_del($this->userRow['user_id']);

        $this->general_api->show_result($_arr_pmDel);
    }


    /**
     * ctrl_read function.
     *
     * @access public
     * @return void
     */
    function ctrl_read() {
        $_arr_apiChks = $this->general_api->app_chk('get');
        if ($_arr_apiChks['rcode'] != 'ok') {
            $this->general_api->show_result($_arr_apiChks);
        }

        $_arr_decrypt = $this->obj_crypt->decrypt($_arr_apiChks['appInput']['code'], $_arr_apiChks['appKey'], $_arr_apiChks['appRow']['app_secret']);  //解密数据

        if ($_arr_decrypt['rcode'] != 'ok') {
            $this->general_api->show_result($_arr_decrypt);
        }

        $_arr_inputRead = fn_jsonDecode($_arr_decrypt['decrypt'], true);

        $this->user_check($_arr_inputRead);

        $_num_pmId = 0;

        if (isset($_arr_inputRead['pm_id'])) {
            $_num_pmId = $_arr_inputRead['pm_id'];
        }

        $_num_pmId = fn_getSafe($_arr_inputRead['pm_id'], 'int', 0);
        if ($_num_pmId < 1) {
            $_arr_return = array(
                'rcode' => 'x110211',
            );
            $this->general_api->show_result($_arr_return);
        }

        if (!$this->obj_sign->sign_check($_arr_decrypt['decrypt'], $_arr_apiChks['appInput']['sign'], $_arr_apiChks['appKey'], $_arr_apiChks['appRow']['app_secret'])) {
            $_arr_return = array(
                'rcode' => 'x050403',
            );
            $this->general_api->show_result($_arr_return);
        }

        $_arr_pmRow = $this->mdl_pm->mdl_read($_num_pmId);
        if ($_arr_pmRow['rcode'] != 'y110102') {
            $this->general_api->show_result($_arr_pmRow);
        }

        if ($_arr_pmRow['pm_from'] != $this->userRow['user_id'] && $_arr_pmRow['pm_to'] != $this->userRow['user_id']) {
            $_arr_return = array(
                'rcode' => 'x110403',
            );
            $this->general_api->show_result($_arr_return);
        }

        $_arr_pmRow['fromUser'] = $this->mdl_user_api->mdl_read($_arr_pmRow['pm_from']);
        $_arr_pmRow['toUser']   = $this->mdl_user_api->mdl_read($_arr_pmRow['pm_to']);

        if ($_arr_pmRow['pm_type'] == 'out') {
            $_arr_sendRow = $this->mdl_pm->mdl_read($_arr_pmRow['pm_send_id']);
            if ($_arr_sendRow['rcode'] != 'y110102') {
                $_arr_pmRow['pm_send_status'] = 'revoke';
            } else {
                $_arr_pmRow['pm_send_status'] = $_arr_sendRow['pm_status'];
            }
        }

        //unset($_arr_pmRow['rcode']);
        $_str_src       = $this->general_api->encode_result($_arr_pmRow);
        $_str_sign      = $this->obj_sign->sign_make($_str_src, $_arr_apiChks['appKey'], $_arr_apiChks['appRow']['app_secret']);
        $_arr_encrypt   = $this->obj_crypt->encrypt($_str_src, $_arr_apiChks['appKey'], $_arr_apiChks['appRow']['app_secret']);

        if ($_arr_encrypt['rcode'] != 'ok') {
            $this->general_api->show_result($_arr_encrypt);
        }

        $_arr_return = array(
            'code'      => $_arr_encrypt['encrypt'],
            'sign' => $_str_sign,
            'rcode'     => $_arr_pmRow['rcode'],
        );

        $this->general_api->show_result($_arr_return);
    }


    function ctrl_check() {
        $_arr_apiChks = $this->general_api->app_chk('get');
        if ($_arr_apiChks['rcode'] != 'ok') {
            $this->general_api->show_result($_arr_apiChks);
        }

        $_arr_decrypt = $this->obj_crypt->decrypt($_arr_apiChks['appInput']['code'], $_arr_apiChks['appKey'], $_arr_apiChks['appRow']['app_secret']);  //解密数据

        if ($_arr_decrypt['rcode'] != 'ok') {
            $this->general_api->show_result($_arr_decrypt);
        }

        $_arr_inputCheck = fn_jsonDecode($_arr_decrypt['decrypt'], true);

        $this->user_check($_arr_inputCheck);

        if (!$this->obj_sign->sign_check($_arr_decrypt['decrypt'], $_arr_apiChks['appInput']['sign'], $_arr_apiChks['appKey'], $_arr_apiChks['appRow']['app_secret'])) {
            $_arr_return = array(
                'rcode' => 'x050403',
            );
            $this->general_api->show_result($_arr_return);
        }

        $_str_status = 'wait';

        if (isset($_arr_inputCheck['status'])) {
            $_str_status = fn_getSafe($_arr_inputCheck['status'], 'txt', 'wait');
        }

        $_arr_search = array(
            'type'      => 'in',
            'pm_to'     => $this->userRow['user_id'],
            'status'    => $_str_status,
        );

        $_num_pmCount   = $this->mdl_pm->mdl_count($_arr_search);

        $_arr_return = array(
            'pm_count'  => $_num_pmCount,
            'rcode'     => 'y110402',
        );

        $this->general_api->show_result($_arr_return);
    }


    /**
     * ctrl_chkname function.
     *
     * @access public
     * @return void
     */
    function ctrl_list() {
        $_arr_apiChks = $this->general_api->app_chk('get');
        if ($_arr_apiChks['rcode'] != 'ok') {
            $this->general_api->show_result($_arr_apiChks);
        }

        $_arr_decrypt = $this->obj_crypt->decrypt($_arr_apiChks['appInput']['code'], $_arr_apiChks['appKey'], $_arr_apiChks['appRow']['app_secret']);  //解密数据

        if ($_arr_decrypt['rcode'] != 'ok') {
            $this->general_api->show_result($_arr_decrypt);
        }

        $_arr_inputList = fn_jsonDecode($_arr_decrypt['decrypt'], true);

        //print_r($_arr_inputList);

        $this->user_check($_arr_inputList);

        $_num_perPage   = BG_SITE_PERPAGE;
        $_str_pmIds     = '';
        $_str_type      = '';
        $_str_status    = '';
        $_str_key       = '';

        if (isset($_arr_inputList['per_page'])) {
            $_num_perPage   = fn_getSafe($_arr_inputList['per_page'], 'int', BG_SITE_PERPAGE);
        }

        if (isset($_arr_inputList['pm_ids'])) {
            $_str_pmIds     = fn_getSafe($_arr_inputList['pm_ids'], 'txt', '');
        }

        if (isset($_arr_inputList['pm_type'])) {
            $_str_type      = fn_getSafe($_arr_inputList['pm_type'], 'txt', '');
        }

        if (isset($_arr_inputList['pm_status'])) {
            $_str_status    = fn_getSafe($_arr_inputList['pm_status'], 'txt', '');
        }

        if (isset($_arr_inputList['key'])) {
            $_str_key       = fn_getSafe($_arr_inputList['key'], 'txt', '');
        }

        if (!$this->obj_sign->sign_check($_arr_decrypt['decrypt'], $_arr_apiChks['appInput']['sign'], $_arr_apiChks['appKey'], $_arr_apiChks['appRow']['app_secret'])) {
            $_arr_return = array(
                'rcode' => 'x050403',
            );
            $this->general_api->show_result($_arr_return);
        }

        $_arr_pmIds = array();

        if (!fn_isEmpty($_str_pmIds)) {
            if (stristr($_str_pmIds, '|')) {
                $_arr_pmIds = explode('|', $_str_pmIds);
            } else {
                $_arr_pmIds = array($_str_pmIds);
            }
        }

        if (fn_isEmpty($_str_type)) {
            $_arr_return = array(
                'rcode' => 'x110218',
            );
            $this->general_api->show_result($_arr_return);
        }

        $_arr_search = array(
            'type'      => $_str_type,
            'status'    => $_str_status,
            'key'       => $_str_key,
            'pm_ids'    => $_arr_pmIds,
        );

        switch ($_str_type) {
            case 'in':
                $_arr_search['pm_to']   = $this->userRow['user_id'];
            break;

            case 'out':
                $_arr_search['pm_from'] = $this->userRow['user_id'];
            break;
        }

        $_arr_pmRows    = array();
        $_num_pmCount   = $this->mdl_pm->mdl_count($_arr_search);
        $_arr_page      = fn_page($_num_pmCount);
        $_arr_pmRows    = $this->mdl_pm->mdl_list($_num_perPage, $_arr_page['except'], $_arr_search);

        //print_r($_arr_pmRows);

        foreach ($_arr_pmRows as $_key=>$_value) {
            $_arr_pmRows[$_key]['fromUser'] = $this->mdl_user_api->mdl_read($_value['pm_from']);
            $_arr_pmRows[$_key]['toUser']   = $this->mdl_user_api->mdl_read($_value['pm_to']);

            if ($_str_type == 'out') {
                $_arr_sendRow = $this->mdl_pm->mdl_read($_value['pm_send_id']);
                if ($_arr_sendRow['rcode'] != 'y110102') {
                    $_arr_pmRows[$_key]['pm_send_status'] = 'revoke';
                } else {
                    $_arr_pmRows[$_key]['pm_send_status'] = $_arr_sendRow['pm_status'];
                }
            }
        }

        //print_r($_arr_pmRows);

        $_arr_return = array(
            'pmRows'    => $_arr_pmRows,
            'pageRow'   => $_arr_page,
        );

        $_str_src       = $this->general_api->encode_result($_arr_return);
        $_str_sign      = $this->obj_sign->sign_make($_str_src, $_arr_apiChks['appKey'], $_arr_apiChks['appRow']['app_secret']);
        $_arr_encrypt   = $this->obj_crypt->encrypt($_str_src, $_arr_apiChks['appKey'], $_arr_apiChks['appRow']['app_secret']);

        if ($_arr_encrypt['rcode'] != 'ok') {
            $this->general_api->show_result($_arr_encrypt);
        }

        $_arr_return = array(
            'code'  => $_arr_encrypt['encrypt'],
            'sign'  => $_str_sign,
            'rcode' => 'y110402',
        );

        $this->general_api->show_result($_arr_return);
    }


    private function user_check($arr_inputCheck) {
        $this->userInput = $this->mdl_user_api->input_token($arr_inputCheck);

        if ($this->userInput['rcode'] != 'ok') {
            $this->general_api->show_result($this->userInput);
        }

        $this->userRow = $this->mdl_user_profile->mdl_read($this->userInput['user_str'], $this->userInput['user_by']);
        if ($this->userRow['rcode'] != 'y010102') {
            $this->general_api->show_result($this->userRow);
        }

        if ($this->userRow['user_status'] == 'disable') {
            $_arr_return = array(
                'rcode' => 'x010401',
            );
            $this->general_api->show_result($_arr_return);
        }

        if ($this->userRow['user_access_expire'] < time()) {
            $_arr_return = array(
                'rcode' => 'x010231',
            );
            $this->general_api->show_result($_arr_return);
        }

        /*print_r($this->userInput);
        print_r('<br>');
        print_r($this->userRow);*/

        if ($this->userInput['user_access_token'] != md5(fn_baigoCrypt($this->userRow['user_access_token'], $this->userRow['user_name']))) {
            $_arr_return = array(
                'rcode' => 'x010230',
            );
            $this->general_api->show_result($_arr_return);
        }
    }
}
