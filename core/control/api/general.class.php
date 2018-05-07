<?php
/*-----------------------------------------------------------------
！！！！警告！！！！
以下为系统文件，请勿修改
-----------------------------------------------------------------*/

//不能非法包含或直接执行
if (!defined('IN_BAIGO')) {
    exit('Access Denied');
}

if (!function_exists('fn_http')) {
    fn_include(BG_PATH_FUNC . 'http.func.php'); //载入模板类
}

/*-------------API 接口类-------------*/
class GENERAL_API {

    //public $obj_dir;
    public $obj_crypt;
    public $obj_sign;
    public $arr_return;
    public $config;
    public $mdl_app;
    public $appRows;

    function __construct($is_install = false) { //构造函数
        $this->config   = $GLOBALS['obj_base']->config;

        if (!$is_install) {
            $this->mdl_app  = new MODEL_APP(); //设置管理员模型

            $_arr_search = array(
                'status'        => 'enable',
                'has_notify'    => true,
            );
            $this->appRows = $this->mdl_app->mdl_list(100, 0, $_arr_search);
        }

        $this->allow    = fn_include(BG_PATH_INC . 'allow.inc.php'); //载入权限文件
        $this->phplib   = fn_include(BG_PATH_INC . 'phplib.inc.php');
        $this->opt      = $GLOBALS['obj_config']->arr_opt; //系统设置配置文件

        $this->lang['rcode']    = fn_include(BG_PATH_LANG . $this->config['lang'] . DS . 'rcode.php'); //载入返回代码

        $this->obj_crypt    = new CLASS_CRYPT();
        $this->obj_sign     = new CLASS_SIGN();

        $this->arr_return = array(
            'prd_sso_ver' => PRD_SSO_VER,
            'prd_sso_pub' => PRD_SSO_PUB,
        );
    }


    /** 验证 app
     * app_chk function.
     *
     * @access public
     * @return void
     */
    function app_chk($str_method = 'get') {
        $this->appInput = $this->mdl_app->input_api($str_method);

        if ($this->appInput['rcode'] != 'ok') {
            return $this->appInput;
        }

        $this->appRow = $this->mdl_app->mdl_read($this->appInput['app_id']);
        if ($this->appRow['rcode'] != 'y050102') {
            return $this->appRow;
        }

        if ($this->appRow['app_status'] != 'enable') {
            return array(
                'rcode' => 'x050402',
            );
        }

        $_str_ip = fn_getIp();

        if (!fn_isEmpty($this->appRow['app_ip_allow'])) {
            $_str_ipAllow = str_ireplace(PHP_EOL, '|', $this->appRow['app_ip_allow']);
            if (!fn_regChk($_str_ip, $_str_ipAllow, true)) {
                return array(
                    'rcode' => 'x050212',
                );
            }
        } else if (!fn_isEmpty($this->appRow['app_ip_bad'])) {
            $_str_ipBad = str_ireplace(PHP_EOL, '|', $this->appRow['app_ip_bad']);
            if (fn_regChk($_str_ip, $_str_ipBad)) {
                return array(
                    'rcode' => 'x050213',
                );
            }
        }

        if ($this->appInput['app_key'] != fn_baigoCrypt($this->appRow['app_key'], $this->appRow['app_name'])) {
            return array(
                'rcode' => 'x050217',
            );
        }

        return array(
            'rcode'     => 'ok',
            'appKey'    => fn_baigoCrypt($this->appRow['app_key'], $this->appRow['app_name']),
            'appInput'  => $this->appInput,
            'appRow'    => $this->appRow,
        );
    }


    function notify_result($arr_returnRow, $str_act, $type = 'json') {
        $_str_src   = $this->encode_result($arr_returnRow, $type);
        $_tm_time   = time();

        //通知
        foreach ($this->appRows as $_key=>$_value) {
            $_str_appKey    = fn_baigoCrypt($_value['app_key'], $_value['app_name']);
            $_arr_encrypt   = $this->obj_crypt->encrypt($_str_src, $_str_appKey, $_value['app_secret']);

            //加密成功
            if ($_arr_encrypt['rcode'] == 'ok') {
                $_arr_data = array(
                    'app_id'    => $_value['app_id'],
                    'app_key'   => $_str_appKey,
                    'time'      => $_tm_time,
                    'a'         => $str_act,
                    'code'      => $_arr_encrypt['encrypt'],
                );

                $_arr_data['sign'] = $this->obj_sign->sign_make($_str_src, $_str_appKey, $_value['app_secret']);

                if (stristr($_value['app_url_notify'], '?')) {
                    $_str_conn = '&';
                } else {
                    $_str_conn = '?';
                }

                if (stristr($_value['app_url_notify'], '?')) {
                    $_str_conn = '&';
                } else {
                    $_str_conn = '?';
                }

                $_arr_get = fn_http($_value['app_url_notify'] . $_str_conn . 'm=notify', $_arr_data, 'post');
            }

            /*print_r($_value['app_url_notify'] . $_str_conn . 'm=notify');
            print_r('-');
            print_r($_arr_get);
            print_r('|');*/
        }
    }


    function encode_result($arr_data, $type = 'json') {
        return fn_jsonEncode($arr_data, true);
    }


    function show_result($arr_tplData = array(), $type = 'json') {
        header('Content-type: application/json; charset=utf-8');
        header('Cache-Control: no-cache, no-store, max-age=0, must-revalidate');

        $_str_msg   = '';
        $_str_rcode = '';
        $_arr_tpl   = array();

        if (isset($arr_tplData['rcode'])) {
            $_str_rcode = $arr_tplData['rcode'];
        }

        if (!fn_isEmpty($_str_rcode) && isset($this->lang['rcode'][$_str_rcode])) {
            $_str_msg = $this->lang['rcode'][$arr_tplData['rcode']];
        }

        if (isset($arr_tplData['msg']) && !fn_isEmpty($arr_tplData['msg'])) {
            $_str_msg = $arr_tplData['msg'];
        }

        if (!fn_isEmpty($_str_rcode)) {
            $_arr_tpl['rcode'] = $_str_rcode;
        }

        if (!fn_isEmpty($_str_msg)) {
            $_arr_tpl['msg'] = $_str_msg;
        }

        $_arr_tplData = array_merge($this->arr_return, $arr_tplData, $_arr_tpl);

        exit(json_encode($_arr_tplData)); //输出错误信息
    }
}
