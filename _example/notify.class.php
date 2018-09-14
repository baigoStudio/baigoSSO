<?php
/*-------------NOTIFY 接口类-------------*/
class CLASS_NOTIFY {

    /** 验证 app
     * app_chk function.
     *
     * @access public
     * @param mixed $arr_appGet
     * @param mixed $arr_appRow
     * @return void
     */
    function app_chk($num_appId, $str_appKey) {

        $_arr_appId = fn_validate($num_appId, 1, 0, 'str', 'int');
        switch ($_arr_appId['status']) {
            case 'too_short':
                return array(
                    'rcode' => 'x220206',
                );
            break;

            case 'format_err':
                return array(
                    'rcode' => 'x220207',
                );
            break;

            case 'ok':
                $_arr_appChk['app_id'] = $_arr_appId['str'];
            break;
        }

        if ($_arr_appChk['app_id'] != BG_SSO_APPID) {
            return array(
                'rcode' => 'x220208',
            );
        }

        $_arr_appKey = fn_validate($str_appKey, 1, 32, 'str', 'alphabetDigit');
        switch ($_arr_appKey['status']) {
            case 'too_short':
                return array(
                    'rcode' => 'x220209',
                );
            break;

            case 'too_long':
                return array(
                    'rcode' => 'x220210',
                );
            break;

            case 'format_err':
                return array(
                    'rcode' => 'x220211',
                );
            break;

            case 'ok':
                $_arr_appChk['app_key'] = $_arr_appKey['str'];
            break;
        }

        if ($_arr_appChk['app_key'] != BG_SSO_APPKEY) {
            return array(
                'rcode' => 'x220212',
            );
        }

        $_arr_appChk['rcode'] = 'ok';

        return $_arr_appChk;
    }


    /** 读取 app 信息
     * app_get function.
     *
     * @access public
     * @param bool $chk_token (default: false)
     * @return void
     */
    function notify_input($str_method = 'get', $chk_token = false) {
        $str_method = strtolower($str_method);

        $_arr_notifyInput['a']    = $GLOBALS['route']['bg_act'];

        switch ($str_method) {
            case 'post':
                $_str_time              = fn_post('time');
                $_str_sign              = fn_post('sign');
                $_str_code              = fn_post('code');
                $this->jsonp_callback   = fn_post('callback');
            break;

            default:
                $_str_time              = fn_get('time');
                $_str_sign              = fn_get('sign');
                $_str_code              = fn_get('code');
                $this->jsonp_callback   = fn_get('callback');
            break;
        }

        $_arr_time = fn_validate($_str_time, 1, 0);
        switch ($_arr_time['status']) {
            case 'too_short':
                return array(
                    'rcode' => 'x220201',
                );
            break;

            case 'ok':
                $_arr_notifyInput['time'] = $_arr_time['str'];
            break;
        }

        $_arr_sign = fn_validate($_str_sign, 1, 0);
        switch ($_arr_sign['status']) {
            case 'too_short':
                return array(
                    'rcode' => 'x220203',
                );
            break;

            case 'ok':
                $_arr_notifyInput['sign'] = $_arr_sign['str'];
            break;
        }

        $_arr_code = fn_validate($_str_code, 1, 0);
        switch ($_arr_code['status']) {
            case 'too_short':
                return array(
                    'rcode' => 'x220204',
                );
            break;

            case 'ok':
                $_arr_notifyInput['code'] = $_arr_code['str'];
            break;
        }

        $_arr_notifyInput['rcode'] = 'ok';

        return $_arr_notifyInput;
    }


    /** 返回结果
     * show_result function.
     *
     * @access public
     * @param mixed $arr_re
     * @return void
     */
    function show_result($arr_re, $is_encode = false, $is_jsonp = false) {
        if ($is_encode) {
            $_str_return = fn_jsonEncode($arr_re, true);
        } else {
            $_str_return = json_encode($arr_re);
        }
        if ($is_jsonp) {
            $_str_return = $this->jsonp_callback . '(' . $_str_return . ')';
        }
        exit($_str_return); //输出错误信息
    }
}