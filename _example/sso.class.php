<?php
/*-------------单点登录类-------------*/
/*-------------单点登录类-------------*/
class CLASS_SSO {

    public $obj_sign;

    function __construct() { //构造函数
        $this->obj_file     = new CLASS_FILE();
        $this->obj_crypt    = new CLASS_CRYPT();
        $this->obj_sign     = new CLASS_SIGN();

        $this->arr_data = array(
            'app_id'    => BG_SSO_APPID, //APP ID
            'app_key'   => BG_SSO_APPKEY, //APP KEY
            'time'      => time(),
        );
    }


    /** 解码
     * sso_decode function.
     *
     * @access public
     * @return void
     */
    function sso_decode($str_code, $str_sign) {
        //解码
        $_arr_decrypt = $this->obj_crypt->decrypt($str_code, BG_SSO_APPKEY, BG_SSO_APPSECRET);
        if ($_arr_decrypt['rcode'] != 'ok') {
            return $_arr_decrypt;
        }

        //验证签名
        if (!$this->obj_sign->sign_check($_arr_decrypt['decrypt'], $str_sign, BG_SSO_APPKEY, BG_SSO_APPSECRET)) {
            return array(
                'rcode' => 'x050403',
            );
        }

        return fn_jsonDecode($_arr_decrypt['decrypt'], true);
    }


    /** 注册
     * sso_user_reg function.
     *
     * @access public
     * @param mixed $str_user 用户名
     * @param mixed $str_userPass 密码
     * @param string $str_userMail (default: '') Email
     * @param string $str_userNick (default: '') 昵称
     * @return 解码后数组 注册结果
     */
    function sso_user_reg($arr_userSubmit = array()) {
        $_arr_crypt = array(
            'user_name' => $arr_userSubmit['user_name'],
            'user_pass' => md5($arr_userSubmit['user_pass']),
            'user_mail' => $arr_userSubmit['user_mail'],
            'user_nick' => $arr_userSubmit['user_nick'],
            'user_ip'   => fn_getIp(),
        );

        $_str_crypt = fn_jsonEncode($_arr_crypt, true);

        $_arr_encrypt = $this->obj_crypt->encrypt($_str_crypt, BG_SSO_APPKEY, BG_SSO_APPSECRET);
        if ($_arr_encrypt['rcode'] != 'ok') {
            return $_arr_encrypt;
        }

        $_arr_sso = array(
            'a'     => 'reg',
            'code'  => $_arr_encrypt['encrypt'],
        );

        if (isset($this->appInstall)) { //仅在安装时使用
            $_arr_ssoData = array_merge($this->appInstall, $_arr_sso); //合并数组
            $_arr_ssoData['sign'] = $this->obj_sign->sign_make($_str_crypt, BG_SSO_APPKEY, BG_SSO_APPSECRET);
            $_arr_get     = fn_http($this->sso_url . '?m=user&c=api', $_arr_ssoData, 'post'); //提交
        } else {
            $_arr_ssoData = array_merge($this->arr_data, $_arr_sso); //合并数组
            $_arr_ssoData['sign'] = $this->obj_sign->sign_make($_str_crypt, BG_SSO_APPKEY, BG_SSO_APPSECRET);
            $_arr_get     = fn_http(BG_SSO_URL . '?m=user&c=api', $_arr_ssoData, 'post'); //提交
        }
        $_arr_result  = $this->result_process($_arr_get);

        if ($_arr_result['rcode'] != 'y010101' && $_arr_result['rcode'] != 'y010410' && $_arr_result['rcode'] != 'x010410') {
            return $_arr_result; //返回错误信息
        }

        $_arr_decode          = $this->sso_decode($_arr_result['code'], $_arr_result['sign']); //解码

        $_arr_decode['rcode'] = $_arr_result['rcode'];

        //print_r($_arr_decode);

        return $_arr_decode;
    }


    /** 登录
     * sso_user_login function.
     *
     * @access public
     * @param mixed $str_userName 用户名
     * @param mixed $str_userPass 密码
     * @return 解码后数组 登录结果
     */
    function sso_user_login($str_userName, $str_userPass) {
        $_arr_crypt = array(
            'user_name' => $str_userName,
            'user_pass' => md5($str_userPass),
            'user_ip'   => fn_getIp(),
        );

        $_str_crypt = fn_jsonEncode($_arr_crypt, true);

        $_arr_encrypt = $this->obj_crypt->encrypt($_str_crypt, BG_SSO_APPKEY, BG_SSO_APPSECRET);
        if ($_arr_encrypt['rcode'] != 'ok') {
            return $_arr_encrypt;
        }

        $_arr_sso = array(
            'a'     => 'login',
            'code'  => $_arr_encrypt['encrypt'],
        );

        $_arr_ssoData = array_merge($this->arr_data, $_arr_sso);
        $_arr_ssoData['sign'] = $this->obj_sign->sign_make($_str_crypt, BG_SSO_APPKEY, BG_SSO_APPSECRET);
        $_arr_get     = fn_http(BG_SSO_URL . '?m=user&c=api', $_arr_ssoData, 'post'); //提交

        $_arr_result  = $this->result_process($_arr_get);

        if ($_arr_result['rcode'] != 'y010401') {
            return $_arr_result; //返回错误信息
        }

        $_arr_decode          = $this->sso_decode($_arr_result['code'], $_arr_result['sign']); //解码
        $_arr_decode['rcode'] = $_arr_result['rcode'];

        return $_arr_decode;
    }


    /** 读取用户信息
     * sso_user_read function.
     *
     * @access public
     * @param mixed $str_user ID（或用户名）
     * @param string $str_by (default: 'user_id') 用何种方式读取（默认用ID）
     * @return 解码后数组 用户信息
     */
    function sso_user_read($str_user, $str_by = 'user_id') {
        $_arr_crypt = array(
            $str_by => $str_user,
        );

        $_str_crypt = fn_jsonEncode($_arr_crypt, true);

        $_arr_encrypt = $this->obj_crypt->encrypt($_str_crypt, BG_SSO_APPKEY, BG_SSO_APPSECRET);
        if ($_arr_encrypt['rcode'] != 'ok') {
            return $_arr_encrypt;
        }

        $_arr_sso = array(
            'a'     => 'read',
            'code'  => $_arr_encrypt['encrypt'],
        );

        $_arr_ssoData = array_merge($this->arr_data, $_arr_sso);
        $_arr_ssoData['sign'] = $this->obj_sign->sign_make($_str_crypt, BG_SSO_APPKEY, BG_SSO_APPSECRET);
        $_arr_get     = fn_http(BG_SSO_URL . '?m=user&c=api', $_arr_ssoData, 'get'); //提交
        $_arr_result  = $this->result_process($_arr_get);

        if ($_arr_result['rcode'] != 'y010102') {
            return $_arr_result; //返回错误信息
        }

        $_arr_decode          = $this->sso_decode($_arr_result['code'], $_arr_result['sign']); //解码
        $_arr_decode['rcode'] = $_arr_result['rcode'];

        return $_arr_decode;
    }


    /** 编辑用户
     * sso_user_edit function.
     *
     * @access public
     * @param mixed $str_user 用户名
     * @param string $str_userPass (default: '') 密码
     * @param string $str_userPassNew (default: '') 新密码
     * @param string $str_userMail (default: '') Email
     * @param string $str_userNick (default: '') 昵称
     * @param string $str_by (default: 'user_name') 用何种方式编辑（默认用用户名）
     * @param string $str_checkPass (default: 'off') 是否验证密码（默认不验证）
     * @return 解码后数组 编辑结果
     */
    function sso_user_edit($str_user, $str_by = 'user_name', $arr_userSubmit = array()) {
        $_arr_crypt = array(
            $str_by => $str_user,
        );

        if (isset($arr_userSubmit['user_pass']) && !fn_isEmpty($arr_userSubmit['user_pass'])) {
            $_arr_crypt['user_pass'] = md5($arr_userSubmit['user_pass']);
        }

        if (isset($arr_userSubmit['user_mail_new']) && !fn_isEmpty($arr_userSubmit['user_mail_new'])) {
            $_arr_crypt['user_mail_new'] = $arr_userSubmit['user_mail_new'];
        }

        if (isset($arr_userSubmit['user_nick']) && !fn_isEmpty($arr_userSubmit['user_nick'])) {
            $_arr_crypt['user_nick'] = $arr_userSubmit['user_nick'];
        }

        $_str_crypt = fn_jsonEncode($_arr_crypt, true);

        $_arr_encrypt = $this->obj_crypt->encrypt($_str_crypt, BG_SSO_APPKEY, BG_SSO_APPSECRET);
        if ($_arr_encrypt['rcode'] != 'ok') {
            return $_arr_encrypt;
        }

        $_arr_sso = array(
            'a'     => 'edit',
            'code'  => $_arr_encrypt['encrypt'],
        );

        $_arr_ssoData = array_merge($this->arr_data, $_arr_sso);
        $_arr_ssoData['sign'] = $this->obj_sign->sign_make($_str_crypt, BG_SSO_APPKEY, BG_SSO_APPSECRET);
        $_arr_get     = fn_http(BG_SSO_URL . '?m=user&c=api', $_arr_ssoData, 'post'); //提交
        $_arr_result  = $this->result_process($_arr_get);

        return $_arr_result;
    }


    /** 检查用户名
     * sso_user_chkname function.
     *
     * @access public
     * @param mixed $str_userName 用户名
     * @return 解码后数组 检查结果
     */
    function sso_user_chkname($str_userName) {
        $_arr_crypt = array(
            'user_name' => $str_userName,
        );

        $_str_crypt = fn_jsonEncode($_arr_crypt, true);

        $_arr_encrypt = $this->obj_crypt->encrypt($_str_crypt, BG_SSO_APPKEY, BG_SSO_APPSECRET);
        if ($_arr_encrypt['rcode'] != 'ok') {
            return $_arr_encrypt;
        }

        $_arr_sso = array(
            'a'     => 'chkname',
            'code'  => $_arr_encrypt['encrypt'],
        );

        $_arr_ssoData = array_merge($this->arr_data, $_arr_sso);
        $_arr_ssoData['sign'] = $this->obj_sign->sign_make($_str_crypt, BG_SSO_APPKEY, BG_SSO_APPSECRET);
        $_arr_get     = fn_http(BG_SSO_URL . '?m=user&c=api', $_arr_ssoData, 'get'); //提交
        $_arr_result  = $this->result_process($_arr_get);

        return $_arr_result;
    }


    /** 检查 Email
     * sso_user_chkmail function.
     *
     * @access public
     * @param mixed $str_userMail Email
     * @param int $num_userId (default: 0) 当前用户ID（默认为0，忽略）
     * @return 解码后数组 检查结果
     */
    function sso_user_chkmail($str_userMail, $num_userId = 0) {
        $_arr_crypt = array(
            'user_mail' => $str_userMail,
            'not_id'    => $num_userId,
        );

        $_str_crypt = fn_jsonEncode($_arr_crypt, true);

        $_arr_encrypt = $this->obj_crypt->encrypt($_str_crypt, BG_SSO_APPKEY, BG_SSO_APPSECRET);
        if ($_arr_encrypt['rcode'] != 'ok') {
            return $_arr_encrypt;
        }

        $_arr_sso = array(
            'a'     => 'chkmail',
            'code'  => $_arr_encrypt['encrypt'],
        );

        $_arr_ssoData = array_merge($this->arr_data, $_arr_sso);
        $_arr_ssoData['sign'] = $this->obj_sign->sign_make($_str_crypt, BG_SSO_APPKEY, BG_SSO_APPSECRET);
        $_arr_get     = fn_http(BG_SSO_URL . '?m=user&c=api', $_arr_ssoData, 'get'); //提交
        $_arr_result  = $this->result_process($_arr_get);

        return $_arr_result;
    }



    function sso_profile_info($str_user, $str_by = 'user_name', $arr_userSubmit = array()) {
        $_arr_crypt = array(
            $str_by     => $str_user,
            'user_pass' => md5($arr_userSubmit['user_pass']),
        );

        if (isset($arr_userSubmit['user_nick']) && !fn_isEmpty($arr_userSubmit['user_nick'])) {
            $_arr_crypt['user_nick'] = $arr_userSubmit['user_nick'];
        }

        $_str_crypt = fn_jsonEncode($_arr_crypt, true);

        $_arr_encrypt = $this->obj_crypt->encrypt($_str_crypt, BG_SSO_APPKEY, BG_SSO_APPSECRET);
        if ($_arr_encrypt['rcode'] != 'ok') {
            return $_arr_encrypt;
        }

        $_arr_sso = array(
            'a'     => 'info',
            'code'  => $_arr_encrypt['encrypt'],
        );

        $_arr_ssoData = array_merge($this->arr_data, $_arr_sso);
        $_arr_ssoData['sign'] = $this->obj_sign->sign_make($_str_crypt, BG_SSO_APPKEY, BG_SSO_APPSECRET);
        $_arr_get     = fn_http(BG_SSO_URL . '?m=profile&c=api', $_arr_ssoData, 'post'); //提交
        $_arr_result  = $this->result_process($_arr_get);

        return $_arr_result;
    }


    function sso_profile_pass($str_user, $str_by = 'user_name', $arr_userSubmit = array()) {
        $_arr_crypt = array(
            $str_by         => $str_user,
            'user_pass'     => md5($arr_userSubmit['user_pass']),
            'user_pass_new' => md5($arr_userSubmit['user_pass_new']),
        );

        $_str_crypt = fn_jsonEncode($_arr_crypt, true);

        $_arr_encrypt = $this->obj_crypt->encrypt($_str_crypt, BG_SSO_APPKEY, BG_SSO_APPSECRET);
        if ($_arr_encrypt['rcode'] != 'ok') {
            return $_arr_encrypt;
        }

        $_arr_sso = array(
            'a'     => 'pass',
            'code'  => $_arr_encrypt['encrypt'],
        );

        $_arr_ssoData = array_merge($this->arr_data, $_arr_sso);
        $_arr_ssoData['sign'] = $this->obj_sign->sign_make($_str_crypt, BG_SSO_APPKEY, BG_SSO_APPSECRET);
        $_arr_get     = fn_http(BG_SSO_URL . '?m=profile&c=api', $_arr_ssoData, 'post'); //提交
        $_arr_result  = $this->result_process($_arr_get);

        return $_arr_result;
    }


    function sso_profile_qa($str_user, $str_by = 'user_name', $arr_userSubmit = array()) {
        $_arr_crypt = array(
            $str_by     => $str_user,
            'user_pass' => md5($arr_userSubmit['user_pass']),
        );

        for ($_iii = 1; $_iii <= 3; $_iii++) {
            $_arr_crypt['user_sec_ques_' . $_iii] = $arr_userSubmit['user_sec_ques_' . $_iii];
            $_arr_crypt['user_sec_answ_' . $_iii] = md5($arr_userSubmit['user_sec_answ_' . $_iii]);
        }

        $_str_crypt = fn_jsonEncode($_arr_crypt, true);

        $_arr_encrypt = $this->obj_crypt->encrypt($_str_crypt, BG_SSO_APPKEY, BG_SSO_APPSECRET);
        if ($_arr_encrypt['rcode'] != 'ok') {
            return $_arr_encrypt;
        }

        $_arr_sso = array(
            'a'     => 'qa',
            'code'  => $_arr_encrypt['encrypt'],
        );

        $_arr_ssoData = array_merge($this->arr_data, $_arr_sso);
        $_arr_ssoData['sign'] = $this->obj_sign->sign_make($_str_crypt, BG_SSO_APPKEY, BG_SSO_APPSECRET);
        $_arr_get     = fn_http(BG_SSO_URL . '?m=profile&c=api', $_arr_ssoData, 'post'); //提交
        $_arr_result  = $this->result_process($_arr_get);

        return $_arr_result; //返回错误信息
    }


    function sso_profile_mailbox($str_user, $str_by = 'user_name', $arr_userSubmit = array()) {
        $_arr_crypt = array(
            $str_by         => $str_user,
            'user_pass'     => md5($arr_userSubmit['user_pass']),
            'user_mail_new' => $arr_userSubmit['user_mail_new'],
        );

        $_str_crypt = fn_jsonEncode($_arr_crypt, true);

        $_arr_encrypt = $this->obj_crypt->encrypt($_str_crypt, BG_SSO_APPKEY, BG_SSO_APPSECRET);
        if ($_arr_encrypt['rcode'] != 'ok') {
            return $_arr_encrypt;
        }

        $_arr_sso = array(
            'a'     => 'mailbox',
            'code'  => $_arr_encrypt['encrypt'],
        );

        $_arr_ssoData = array_merge($this->arr_data, $_arr_sso);
        $_arr_ssoData['sign'] = $this->obj_sign->sign_make($_str_crypt, BG_SSO_APPKEY, BG_SSO_APPSECRET);
        $_arr_get     = fn_http(BG_SSO_URL . '?m=profile&c=api', $_arr_ssoData, 'post'); //提交
        $_arr_result  = $this->result_process($_arr_get);

        return $_arr_result; //返回错误信息
    }


    function sso_profile_token($str_user, $str_by = 'user_name', $str_refreshToken = '') {
        $_arr_crypt = array(
            $str_by                 => $str_user,
            'user_refresh_token'    => md5($str_refreshToken),
        );

        $_str_crypt = fn_jsonEncode($_arr_crypt, true);

        $_arr_encrypt = $this->obj_crypt->encrypt($_str_crypt, BG_SSO_APPKEY, BG_SSO_APPSECRET);
        if ($_arr_encrypt['rcode'] != 'ok') {
            return $_arr_encrypt;
        }

        $_arr_sso = array(
            'a'     => 'token',
            'code'  => $_arr_encrypt['encrypt'],
        );

        $_arr_ssoData = array_merge($this->arr_data, $_arr_sso);
        $_arr_ssoData['sign'] = $this->obj_sign->sign_make($_str_crypt, BG_SSO_APPKEY, BG_SSO_APPSECRET);
        $_arr_get     = fn_http(BG_SSO_URL . '?m=profile&c=api', $_arr_ssoData, 'post'); //提交
        $_arr_result  = $this->result_process($_arr_get);

        if ($_arr_result['rcode'] != 'y010411') {
            return $_arr_result; //返回错误信息
        }

        $_arr_decode          = $this->sso_decode($_arr_result['code'], $_arr_result['sign']); //解码
        $_arr_decode['rcode'] = $_arr_result['rcode'];

        return $_arr_decode;
    }


    function sso_forgot_bymail($str_userName) {
        $_arr_crypt = array(
            'user_name' => $str_userName,
        );

        $_str_crypt = fn_jsonEncode($_arr_crypt, true);

        $_arr_encrypt = $this->obj_crypt->encrypt($_str_crypt, BG_SSO_APPKEY, BG_SSO_APPSECRET);
        if ($_arr_encrypt['rcode'] != 'ok') {
            return $_arr_encrypt;
        }

        $_arr_sso = array(
            'a'     => 'bymail',
            'code'  => $_arr_encrypt['encrypt'],
        );

        $_arr_ssoData = array_merge($this->arr_data, $_arr_sso);
        $_arr_ssoData['sign'] = $this->obj_sign->sign_make($_str_crypt, BG_SSO_APPKEY, BG_SSO_APPSECRET);
        $_arr_get     = fn_http(BG_SSO_URL . '?m=forgot&c=api', $_arr_ssoData, 'post'); //提交

        $_arr_result  = $this->result_process($_arr_get);

        return $_arr_result;
    }


    function sso_forgot_byqa($arr_userSubmit = array()) {
        $_arr_crypt = array(
            'user_name'         => $arr_userSubmit['user_name'],
            'user_pass_new'     => md5($arr_userSubmit['user_pass_new']),
            'user_pass_confirm' => md5($arr_userSubmit['user_pass_confirm']),
        );

        for ($_iii = 1; $_iii <= 3; $_iii++) {
            $_arr_crypt['user_sec_answ_' . $_iii] = md5($arr_userSubmit['user_sec_answ_' . $_iii]);
        }

        $_str_crypt = fn_jsonEncode($_arr_crypt, true);

        $_arr_encrypt = $this->obj_crypt->encrypt($_str_crypt, BG_SSO_APPKEY, BG_SSO_APPSECRET);
        if ($_arr_encrypt['rcode'] != 'ok') {
            return $_arr_encrypt;
        }

        $_arr_sso = array(
            'a'     => 'byqa',
            'code'  => $_arr_encrypt['encrypt'],
        );

        $_arr_ssoData = array_merge($this->arr_data, $_arr_sso);
        $_arr_ssoData['sign'] = $this->obj_sign->sign_make($_str_crypt, BG_SSO_APPKEY, BG_SSO_APPSECRET);
        $_arr_get     = fn_http(BG_SSO_URL . '?m=forgot&c=api', $_arr_ssoData, 'post'); //提交

        $_arr_result  = $this->result_process($_arr_get);

        return $_arr_result;
    }


    /** 同步登录
     * sso_sync_login function.
     *
     * @access public
     * @param mixed $num_userId
     * @return void
     */
    function sso_sync_login($_arr_userSubmit = array()) {
        $_arr_crypt = array(
            'user_id'           => $_arr_userSubmit['user_id'],
            'user_access_token' => md5($_arr_userSubmit['user_access_token']),
        );

        $_str_crypt = fn_jsonEncode($_arr_crypt, true);

        $_arr_encrypt = $this->obj_crypt->encrypt($_str_crypt, BG_SSO_APPKEY, BG_SSO_APPSECRET);
        if ($_arr_encrypt['rcode'] != 'ok') {
            return $_arr_encrypt;
        }

        $_arr_sso = array(
            'a'     => 'login',
            'code'  => $_arr_encrypt['encrypt'],
        );

        $_arr_ssoData   = array_merge($this->arr_data, $_arr_sso);
        $_arr_ssoData['sign'] = $this->obj_sign->sign_make($_str_crypt, BG_SSO_APPKEY, BG_SSO_APPSECRET);
        $_arr_get       = fn_http(BG_SSO_URL . '?m=sync&c=api', $_arr_ssoData, 'post'); //提交
        //print_r($_arr_get);
        $_arr_result    = $this->result_process($_arr_get);

        if (isset($_arr_result['urlRows']) && !fn_isEmpty($_arr_result['urlRows'])) {
            foreach ($_arr_result['urlRows'] as $_key=>$_value) {
                $_arr_result['urlRows'][$_key] = fn_htmlcode(urldecode($_value), 'decode', 'url');
            }
        }

        return $_arr_result;
    }


    function sso_pm_send($str_user, $str_by = 'user_id', $_arr_pmSubmit = array()) {
        $_arr_crypt = array(
            $str_by             => $str_user,
            'user_access_token' => md5($_arr_pmSubmit['user_access_token']),
            'pm_to'             => $_arr_pmSubmit['pm_to'],
            'pm_title'          => $_arr_pmSubmit['pm_title'],
            'pm_content'        => $_arr_pmSubmit['pm_content'],
        );

        $_str_crypt = fn_jsonEncode($_arr_crypt, true);

        $_arr_encrypt = $this->obj_crypt->encrypt($_str_crypt, BG_SSO_APPKEY, BG_SSO_APPSECRET);
        if ($_arr_encrypt['rcode'] != 'ok') {
            return $_arr_encrypt;
        }

        $_arr_sso = array(
            'a'     => 'send',
            'code'  => $_arr_encrypt['encrypt'],
        );

        $_arr_ssoData = array_merge($this->arr_data, $_arr_sso);
        $_arr_ssoData['sign'] = $this->obj_sign->sign_make($_str_crypt, BG_SSO_APPKEY, BG_SSO_APPSECRET);
        $_arr_get     = fn_http(BG_SSO_URL . '?m=pm&c=api', $_arr_ssoData, 'post'); //提交

        $_arr_result  = $this->result_process($_arr_get);

        return $_arr_result;
    }


    function sso_pm_status($str_user, $str_by = 'user_id', $_arr_pmSubmit = array()) {
        $_arr_crypt = array(
            $str_by             => $str_user,
            'user_access_token' => md5($_arr_pmSubmit['user_access_token']),
            'pm_ids'            => implode('|', $_arr_pmSubmit['pm_ids']),
            'pm_status'         => $_arr_pmSubmit['pm_status'],
        );

        $_str_crypt = fn_jsonEncode($_arr_crypt, true);

        $_arr_encrypt = $this->obj_crypt->encrypt($_str_crypt, BG_SSO_APPKEY, BG_SSO_APPSECRET);
        if ($_arr_encrypt['rcode'] != 'ok') {
            return $_arr_encrypt;
        }

        $_arr_sso = array(
            'a'     => 'status',
            'code'  => $_arr_encrypt['encrypt'],
        );

        $_arr_ssoData = array_merge($this->arr_data, $_arr_sso);
        $_arr_ssoData['sign'] = $this->obj_sign->sign_make($_str_crypt, BG_SSO_APPKEY, BG_SSO_APPSECRET);
        $_arr_get     = fn_http(BG_SSO_URL . '?m=pm&c=api', $_arr_ssoData, 'post'); //提交

        $_arr_result  = $this->result_process($_arr_get);

        return $_arr_result;
    }


    function sso_pm_revoke($str_user, $str_by = 'user_id', $_arr_pmSubmit = array()) {
        $_arr_crypt = array(
            $str_by             => $str_user,
            'user_access_token' => md5($_arr_pmSubmit['user_access_token']),
            'pm_ids'            => implode('|', $_arr_pmSubmit['pm_ids']),
        );

        $_str_crypt = fn_jsonEncode($_arr_crypt, true);

        $_arr_encrypt = $this->obj_crypt->encrypt($_str_crypt, BG_SSO_APPKEY, BG_SSO_APPSECRET);
        if ($_arr_encrypt['rcode'] != 'ok') {
            return $_arr_encrypt;
        }

        $_arr_sso = array(
            'a'     => 'revoke',
            'code'  => $_arr_encrypt['encrypt'],
        );

        $_arr_ssoData = array_merge($this->arr_data, $_arr_sso);
        $_arr_ssoData['sign'] = $this->obj_sign->sign_make($_str_crypt, BG_SSO_APPKEY, BG_SSO_APPSECRET);
        $_arr_get     = fn_http(BG_SSO_URL . '?m=pm&c=api', $_arr_ssoData, 'post'); //提交

        $_arr_result  = $this->result_process($_arr_get);

        return $_arr_result;
    }


    function sso_pm_del($str_user, $str_by = 'user_id', $_arr_pmSubmit = array()) {
        $_arr_crypt = array(
            $str_by             => $str_user,
            'user_access_token' => md5($_arr_pmSubmit['user_access_token']),
            'pm_ids'            => implode('|', $_arr_pmSubmit['pm_ids']),
        );

        $_str_crypt = fn_jsonEncode($_arr_crypt, true);

        $_arr_encrypt = $this->obj_crypt->encrypt($_str_crypt, BG_SSO_APPKEY, BG_SSO_APPSECRET);
        if ($_arr_encrypt['rcode'] != 'ok') {
            return $_arr_encrypt;
        }

        $_arr_sso = array(
            'a'     => 'del',
            'code'  => $_arr_encrypt['encrypt'],
        );

        $_arr_ssoData = array_merge($this->arr_data, $_arr_sso);
        $_arr_ssoData['sign'] = $this->obj_sign->sign_make($_str_crypt, BG_SSO_APPKEY, BG_SSO_APPSECRET);
        $_arr_get     = fn_http(BG_SSO_URL . '?m=pm&c=api', $_arr_ssoData, 'post'); //提交

        $_arr_result  = $this->result_process($_arr_get);

        return $_arr_result;
    }


    function sso_pm_read($str_user, $str_by = 'user_id', $_arr_pmSubmit = array()) {
        $_arr_crypt = array(
            $str_by             => $str_user,
            'user_access_token' => md5($_arr_pmSubmit['user_access_token']),
            'pm_id'             => $_arr_pmSubmit['pm_id'],
        );

        $_str_crypt = fn_jsonEncode($_arr_crypt, true);

        $_arr_encrypt = $this->obj_crypt->encrypt($_str_crypt, BG_SSO_APPKEY, BG_SSO_APPSECRET);
        if ($_arr_encrypt['rcode'] != 'ok') {
            return $_arr_encrypt;
        }

        $_arr_sso = array(
            'a'     => 'read',
            'code'  => $_arr_encrypt['encrypt'],
        );

        $_arr_ssoData = array_merge($this->arr_data, $_arr_sso);
        $_arr_ssoData['sign'] = $this->obj_sign->sign_make($_str_crypt, BG_SSO_APPKEY, BG_SSO_APPSECRET);
        $_arr_get     = fn_http(BG_SSO_URL . '?m=pm&c=api', $_arr_ssoData, 'get'); //提交

        $_arr_result  = $this->result_process($_arr_get);

        if ($_arr_result['rcode'] != 'y110102') {
            return $_arr_result; //返回错误信息
        }

        $_arr_decode          = $this->sso_decode($_arr_result['code'], $_arr_result['sign']); //解码
        $_arr_decode['rcode'] = $_arr_result['rcode'];

        return $_arr_decode;
    }


    function sso_pm_list($str_user, $str_by = 'user_id', $_arr_pmSubmit = array()) {
        $_arr_crypt = array(
            $str_by             => $str_user,
            'user_access_token' => md5($_arr_pmSubmit['user_access_token']),
            'pm_type'           => $_arr_pmSubmit['pm_type'],
            'pm_status'         => $_arr_pmSubmit['pm_status'],
            'key'               => $_arr_pmSubmit['key'],
        );

        $_str_crypt = fn_jsonEncode($_arr_crypt, true);

        $_arr_encrypt = $this->obj_crypt->encrypt($_str_crypt, BG_SSO_APPKEY, BG_SSO_APPSECRET);
        if ($_arr_encrypt['rcode'] != 'ok') {
            return $_arr_encrypt;
        }

        $_arr_sso = array(
            'a'     => 'list',
            'code'  => $_arr_encrypt['encrypt'],
        );

        $_arr_ssoData = array_merge($this->arr_data, $_arr_sso);
        $_arr_ssoData['sign'] = $this->obj_sign->sign_make($_str_crypt, BG_SSO_APPKEY, BG_SSO_APPSECRET);
        $_arr_get     = fn_http(BG_SSO_URL . '?m=pm&c=api', $_arr_ssoData, 'get'); //提交

        //print_r($_arr_get);

        $_arr_result  = $this->result_process($_arr_get);

        if ($_arr_result['rcode'] != 'y110402') {
            return $_arr_result; //返回错误信息
        }

        $_arr_decode          = $this->sso_decode($_arr_result['code'], $_arr_result['sign']); //解码
        $_arr_decode['rcode'] = $_arr_result['rcode'];

        return $_arr_decode;
    }


    function sso_pm_check($str_user, $str_by = 'user_id', $str_accessToken = '') {
        $_arr_crypt = array(
            $str_by             => $str_user,
            'user_access_token' => md5($str_accessToken),
        );

        $_str_crypt = fn_jsonEncode($_arr_crypt, true);

        $_arr_encrypt = $this->obj_crypt->encrypt($_str_crypt, BG_SSO_APPKEY, BG_SSO_APPSECRET);
        if ($_arr_encrypt['rcode'] != 'ok') {
            return $_arr_encrypt;
        }

        $_arr_sso = array(
            'a'     => 'check',
            'code'  => $_arr_encrypt['encrypt'],
        );

        $_arr_ssoData = array_merge($this->arr_data, $_arr_sso);
        $_arr_ssoData['sign'] = $this->obj_sign->sign_make($_str_crypt, BG_SSO_APPKEY, BG_SSO_APPSECRET);
        $_arr_get     = fn_http(BG_SSO_URL . '?m=pm&c=api', $_arr_ssoData, 'get'); //提交

        $_arr_result  = $this->result_process($_arr_get);

        return $_arr_result;
    }


    function sso_setup() {
        $_arr_ssoData = array(
            'a'             => 'dbconfig',
            'db_host'       => BG_DB_HOST,
            'db_port'       => BG_DB_PORT,
            'db_name'       => BG_DB_NAME,
            'db_user'       => BG_DB_USER,
            'db_pass'       => BG_DB_PASS,
            'db_charset'    => BG_DB_CHARSET,
            'db_table'      => 'sso_',
        );
        $_arr_get     = fn_http(BG_SITE_URL . BG_URL_SSO . 'api/api.php?m=setup&c=api', $_arr_ssoData, 'post'); //提交
        $_arr_result  = $this->result_process($_arr_get);
        if ($_arr_result['rcode'] != 'y030404') {
            return $_arr_result;
        }

        $_arr_ssoData = array(
            'a'   => 'dbtable',
        );
        $_arr_get     = fn_http(BG_SITE_URL . BG_URL_SSO . 'api/api.php?m=setup&c=api', $_arr_ssoData, 'post'); //提交
        //print_r($_arr_get);
        $_arr_result  = $this->result_process($_arr_get);
        if ($_arr_result['rcode'] != 'y030108') {
            return $_arr_result;
        }

        return $_arr_result;
    }


    /** 管理员
     * sso_admin function.
     *
     * @access public
     * @param mixed $str_adminName
     * @param mixed $str_adminPass
     * @return void
     */
    function sso_admin($str_adminName, $str_adminPass) {
        $_arr_sso = array(
            'a'             => 'admin',
            'admin_name'    => $str_adminName,
            'admin_pass'    => md5($str_adminPass),
        );

        $_arr_ssoData = array_merge($this->arr_data, $_arr_sso); //合并数组
        $_arr_get     = fn_http(BG_SITE_URL . BG_URL_SSO . 'api/api.php?m=setup&c=api', $_arr_ssoData, 'post'); //提交
        $_arr_resultAdmin  = $this->result_process($_arr_get);
        if ($_arr_resultAdmin['rcode'] != 'y010101') {
            return $_arr_resultAdmin;
        }

        $_arr_ssoData = array(
            'a'                 => 'over',
            'app_name'          => 'baigo CMS',
            'app_url_notify'    => BG_SITE_URL . BG_URL_API . 'api.php?m=notify&c=sso',
            'app_url_sync'      => BG_SITE_URL . BG_URL_API . 'api.php?m=sync&c=sso',
        );
        $_arr_get     = fn_http(BG_SITE_URL . BG_URL_SSO . 'api/api.php?m=setup&c=api', $_arr_ssoData, 'post'); //提交
        $_arr_resultApp  = $this->result_process($_arr_get);
        if ($_arr_resultApp['rcode'] != 'y030408') {
            return $_arr_resultApp;
        }

        $this->sso_url = $_arr_resultApp['sso_url'];

        $this->appInstall = array(
            'app_id'     => $_arr_resultApp['app_id'],
            'app_key'    => $_arr_resultApp['app_key'],
            'time'       => time(),
        );

        $_str_outPut = '<?php' . PHP_EOL;
        $_str_outPut .= 'define(\'BG_SSO_URL\', \'' . $_arr_resultApp['sso_url'] . '\');' . PHP_EOL;
        $_str_outPut .= 'define(\'BG_SSO_APPID\', ' . $_arr_resultApp['app_id'] . ');' . PHP_EOL;
        $_str_outPut .= 'define(\'BG_SSO_APPKEY\', \'' . $_arr_resultApp['app_key'] . '\');' . PHP_EOL;
        $_str_outPut .= 'define(\'BG_SSO_APPSECRET\', \'' . $_arr_resultApp['app_secret'] . '\');' . PHP_EOL;
        $_str_outPut .= 'define(\'BG_SSO_SYNC\', \'on\');' . PHP_EOL;

        $_num_size = $this->obj_file->put_file(BG_PATH_CONFIG . 'opt_sso.inc.php', $_str_outPut);

        if ($_num_size > 0) {
            $_str_rcode = 'y060101';
        } else {
            $_str_rcode = 'x060101';
        }

        $_arr_resultAdmin['rcode'] = $_str_rcode;

        return $_arr_resultAdmin;
    }

    /**
     * result_process function.
     *
     * @access private
     * @return void
     */
    private function result_process($arr_get) {
        //print_r($arr_get);
        //exit;
        if (!isset($arr_get['ret'])) {
            $_arr_result = array(
                'rcode' => 'x030208'
            );
            return $_arr_result;
        }

        $_arr_result = json_decode($arr_get['ret'], true);

        if (!isset($_arr_result['rcode'])) {
            $_arr_result = array(
                'rcode' => 'x030209'
            );
            return $_arr_result;
        }

        if (!isset($_arr_result['prd_sso_pub'])) {
            $_arr_result = array(
                'rcode' => 'x030210'
            );
            return $_arr_result;
        }

        if ($_arr_result['prd_sso_pub'] < 20180224) {
            $_arr_result = array(
                'rcode' => 'x030211'
            );
            return $_arr_result;
        }

        return $_arr_result;
    }
}
