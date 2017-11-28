<?php
/*-----------------------------------------------------------------
！！！！警告！！！！
以下为系统文件，请勿修改
-----------------------------------------------------------------*/

//不能非法包含或直接执行
if (!defined('IN_BAIGO')) {
    exit('Access Denied');
}

fn_include(BG_PATH_FUNC . 'mail.func.php');

/*-------------用户类-------------*/
class CONTROL_API_API_USER {

    function __construct() { //构造函数
        $this->config           = $GLOBALS['obj_base']->config;

        $this->general_api          = new GENERAL_API();
        //$this->general_api->chk_install();

        $this->mail             = fn_include(BG_PATH_LANG . $this->config['lang'] . DS . 'mail.php');

        $this->obj_crypt    = $this->general_api->obj_crypt;
        $this->obj_sign     = $this->general_api->obj_sign;

        $this->mdl_user_api     = new MODEL_USER_API(); //设置管理组模型
        $this->mdl_user_profile = new MODEL_USER_PROFILE(); //设置管理组模型
        $this->mdl_belong       = new MODEL_BELONG();
        $this->mdl_verify       = new MODEL_VERIFY(); //设置管理员模型
    }

    /**
     * ctrl_reg function.
     *
     * @access public
     * @return void
     */
    function ctrl_reg() {
        $_arr_apiChks = $this->general_api->app_chk('post');
        if ($_arr_apiChks['rcode'] != 'ok') {
            $this->general_api->show_result($_arr_apiChks);
        }

        if (defined('BG_REG_ACC') && BG_REG_ACC != 'enable') {
            $_arr_tplData = array(
                'rcode' => 'x050316',
            );
            $this->general_api->show_result($_arr_tplData);
        }

        if (!isset($_arr_apiChks['appRow']['app_allow']['user']['reg'])) { //无权限并记录日志
            $_arr_tplData = array(
                'rcode' => 'x050305',
            );
            $_arr_logType = array('user', 'reg');
            $_arr_logTarget[] = array(
                'app_id' => $_arr_apiChks['appInput']['app_id'],
            );
            $this->general_api->show_result($_arr_tplData);
        }

        $_arr_regInput = $this->mdl_user_api->input_reg(); //获取数据
        if ($_arr_regInput['rcode'] != 'ok') {
            $this->general_api->show_result($_arr_regInput);
        }

        $_arr_sign = array(
            'act'           => $GLOBALS['route']['bg_act'],
            'user_name'     => $_arr_regInput['user_name'],
            'user_mail'     => $_arr_regInput['user_mail'],
            'user_pass'     => $_arr_regInput['user_pass'],
            'user_nick'     => $_arr_regInput['user_nick'],
            'user_contact'  => $_arr_regInput['user_contactStr'],
            'user_extend'   => $_arr_regInput['user_extendStr'],
            'user_ip'       => $_arr_regInput['user_ip'],
        );

        if (!$this->obj_sign->sign_check(array_merge($_arr_apiChks['appInput'], $_arr_sign), $_arr_apiChks['appInput']['signature'])) {
            $_arr_tplData = array(
                'rcode' => 'x050403',
            );
            $this->general_api->show_result($_arr_tplData);
        }

        if (BG_REG_CONFIRM == 'on') { //开启验证则为等待
            $_str_status = 'wait';
        } else {
            $_str_status = 'enable';
        }
        $_arr_userSubmit = array(
            'user_pass'     => fn_baigoCrypt($_arr_regInput['user_pass'], $_arr_regInput['user_name'], true),
            'user_status'   => $_str_status,
            'user_app_id'   => $_arr_apiChks['appRow']['app_id'],
        );
        $_arr_userRow = $this->mdl_user_api->mdl_reg($_arr_userSubmit);

        if (BG_REG_CONFIRM == 'on') { //开启验证发送邮件
            $_arr_returnRow    = $this->mdl_verify->mdl_submit($_arr_userRow['user_id'], $_arr_regInput['user_mail'], 'confirm');
            if ($_arr_returnRow['rcode'] != 'y120101' && $_arr_returnRow['rcode'] != 'y120103') { //生成验证失败
                $this->general_api->show_result($_arr_returnRow);
            }

            $_str_verifyUrl = BG_SITE_URL . BG_URL_PERSONAL . 'index.php?mod=reg&act=confirm&verify_id=' . $_arr_returnRow['verify_id'] . '&verify_token=' . $_arr_returnRow['verify_token'];
            $_str_url       = '<a href="' . $_str_verifyUrl . '">' . $_str_verifyUrl . '</a>';
            $_str_html      = str_ireplace('{verify_url}', $_str_url, $this->mail['reg']['content']);
            $_str_html      = str_ireplace('{user_name}', $_arr_regInput['user_name'], $_str_html);
            $_str_html      = str_ireplace('{user_mail}', $_arr_regInput['user_mail'], $_str_html);

            if (fn_mailSend($_arr_regInput['user_mail'], $this->mail['reg']['subject'], $_str_html)) { //发送邮件
                $_str_rcode = 'y010410';
            } else {
                $_str_rcode = 'x010410';
            }

            $_arr_userRow['rcode'] = $_str_rcode;
        }

        $_str_src   = $this->general_api->encode_result($_arr_userRow);

        $this->general_api->notify_result($_arr_userRow, 'reg'); //通知

        $_str_code  = $this->obj_crypt->encrypt($_str_src, fn_baigoCrypt($_arr_apiChks['appRow']['app_key'], $_arr_apiChks['appRow']['app_name']));

        $this->mdl_belong->mdl_submit($_arr_userRow['user_id'], $_arr_apiChks['appInput']['app_id']); //用户授权

        $_arr_tplData = array(
            'code'  => $_str_code,
            'rcode' => $_arr_userRow['rcode'],
        );

        $this->general_api->show_result($_arr_tplData);
    }


    function ctrl_nomail() {
        $_arr_apiChks = $this->general_api->app_chk('post');
        if ($_arr_apiChks['rcode'] != 'ok') {
            $this->general_api->show_result($_arr_apiChks);
        }

        if (!isset($_arr_apiChks['appRow']['app_allow']['user']['reg'])) {
            $_arr_tplData = array(
                'rcode' => 'x050308',
            );
            $_arr_logTarget[] = array(
                'app_id' => $_arr_apiChks['appInput']['app_id'],
            );
            $_arr_logType = array('user', 'reg');
            $this->general_api->show_result($_arr_tplData);
        }

        $_arr_userInput = $this->mdl_user_api->input_user('post');
        if ($_arr_userInput['rcode'] != 'ok') {
            $this->general_api->show_result($_arr_userInput);
        }

        $_arr_sign = array(
            'act' => $GLOBALS['route']['bg_act'],
            $_arr_userInput['user_by'] => $_arr_userInput['user_str'],
        );

        if (!$this->obj_sign->sign_check(array_merge($_arr_apiChks['appInput'], $_arr_sign), $_arr_apiChks['appInput']['signature'])) {
            $_arr_tplData = array(
                'rcode' => 'x050403',
            );
            $this->general_api->show_result($_arr_tplData);
        }

        $_arr_userRow = $this->mdl_user_api->mdl_read($_arr_userInput['user_str'], $_arr_userInput['user_by']);
        if ($_arr_userRow['rcode'] != 'y010102') {
            $this->general_api->show_result($_arr_userRow);
        }

        if ($_arr_userRow['user_status'] == 'enable') {
            $_arr_tplData = array(
                'rcode' => 'x010226',
            );
            $this->general_api->show_result($_arr_tplData);
        }

        if (!isset($_arr_apiChks['appRow']['app_allow']['user']['global'])) {
            $_arr_belongRow = $this->mdl_belong->mdl_read($_arr_userRow['user_id'], $_arr_apiChks['appInput']['app_id']);
            if ($_arr_belongRow['rcode'] != 'y070102') {
                $_arr_tplData = array(
                    'rcode' => 'x050308',
                );
                $this->general_api->show_result($_arr_tplData);
            }
        }

        //file_put_contents(BG_PATH_ROOT . 'test.txt', $_str_userPass . '||' . $_str_rand);

        $_arr_returnRow    = $this->mdl_verify->mdl_submit($_arr_userRow['user_id'], $_arr_userRow['user_mail'], 'mailbox');
        if ($_arr_returnRow['rcode'] != 'y120101' && $_arr_returnRow['rcode'] != 'y120103') {
            $this->general_api->show_result($_arr_returnRow);
        }

        $_str_verifyUrl = BG_SITE_URL . BG_URL_PERSONAL . 'index.php?mod=reg&act=confirm&verify_id=' . $_arr_returnRow['verify_id'] . '&verify_token=' . $_arr_returnRow['verify_token'];
        $_str_url       = '<a href="' . $_str_verifyUrl . '">' . $_str_verifyUrl . '</a>';
        $_str_html      = str_ireplace('{verify_url}', $_str_url, $this->mail['reg']['content']);
        $_str_html      = str_ireplace('{user_name}', $_arr_userRow['user_name'], $_str_html);
        $_str_html      = str_ireplace('{user_mail}', $_arr_userRow['user_mail'], $_str_html);

        if (fn_mailSend($_arr_userRow['user_mail'], $this->mail['reg']['subject'], $_str_html)) {
            $_arr_returnRow['rcode'] = 'y010410';
        } else {
            $_arr_returnRow['rcode'] = 'x010410';
        }

        $_arr_tplData = array(
            'rcode' => $_arr_returnRow['rcode'],
        );
        $this->general_api->show_result($_arr_tplData);
    }


    /**
     * ctrl_login function.
     *
     * @access public
     * @return void
     */
    function ctrl_login() {
        $_arr_apiChks = $this->general_api->app_chk('post');
        if ($_arr_apiChks['rcode'] != 'ok') {
            $this->general_api->show_result($_arr_apiChks);
        }

        $_arr_loginInput = $this->mdl_user_api->input_login();
        if ($_arr_loginInput['rcode'] != 'ok') {
            $this->general_api->show_result($_arr_loginInput);
        }

        $_arr_sign = array(
            'act'                       => $GLOBALS['route']['bg_act'],
            'user_pass'                 => $_arr_loginInput['user_pass'],
            $_arr_loginInput['user_by'] => $_arr_loginInput['user_str'],
            'user_ip'                   => $_arr_loginInput['user_ip'],
        );

        if (!$this->obj_sign->sign_check(array_merge($_arr_apiChks['appInput'], $_arr_sign), $_arr_apiChks['appInput']['signature'])) {
            $_arr_tplData = array(
                'rcode' => 'x050403',
            );
            $this->general_api->show_result($_arr_tplData);
        }

        $_arr_userRow = $this->mdl_user_profile->mdl_read($_arr_loginInput['user_str'], $_arr_loginInput['user_by']);
        if ($_arr_userRow['rcode'] != 'y010102') {
            $this->general_api->show_result($_arr_userRow);
        }

        if ($_arr_userRow['user_status'] == 'disable') {
            $_arr_tplData = array(
                'rcode' => 'x010401',
            );
            $this->general_api->show_result($_arr_tplData);
        }

        switch ($_arr_userRow['user_crypt_type']) {
            case 0:
            case 1:
                $_str_crypt = fn_baigoCrypt($_arr_loginInput['user_pass'], $_arr_userRow['user_rand'], true, $_arr_userRow['user_crypt_type']);
            break;

            default:
                $_str_crypt = fn_baigoCrypt($_arr_loginInput['user_pass'], $_arr_userRow['user_name'], true);
            break;
        }

        //特殊处理，针对上一版本加密错误
        if ($_str_crypt != $_arr_userRow['user_pass']) {
            $_str_crypt = fn_baigoCrypt($_arr_loginInput['user_pass'], $_arr_userRow['user_name'], true, 0);
        }

        if ($_str_crypt != $_arr_userRow['user_pass']) {
            $_arr_tplData = array(
                'rcode' => 'x010213',
            );
            $this->general_api->show_result($_arr_tplData);
        }

        //print_r($_arr_userRow);

        $_arr_userSubmit = array(
            'user_id' => $_arr_userRow['user_id'],
            'user_ip' => $_arr_loginInput['user_ip'],
        );
        $_arr_loginRow = $this->mdl_user_api->mdl_login($_arr_userSubmit);

        //如新加密规则与数据库不一致，则对密码重新加密
        $_str_userPass = fn_baigoCrypt($_arr_loginInput['user_pass'], $_arr_userRow['user_name'], true);

        if ($_str_userPass != $_arr_userRow['user_pass']) {
            $this->mdl_user_profile->mdl_pass($_arr_userRow['user_id'], $_str_userPass);
        }

        unset($_arr_userRow['user_pass'], $_arr_userRow['user_note'], $_arr_userRow['user_crypt_type'], $_arr_userRow['user_rand']);

        for ($_iii = 1; $_iii <= 3; $_iii++) {
            unset($_arr_userRow['user_sec_ques_' . $_iii], $_arr_userRow['user_sec_answ_' . $_iii]);
        }

        $_arr_userRow['user_access_token']      = $_arr_loginRow['user_access_token'];
        $_arr_userRow['user_access_expire']     = $_arr_loginRow['user_access_expire'];
        $_arr_userRow['user_refresh_token']     = $_arr_loginRow['user_refresh_token'];
        $_arr_userRow['user_refresh_expire']    = $_arr_loginRow['user_refresh_expire'];

        //print_r($_arr_userRow);

        $_str_src   = $this->general_api->encode_result($_arr_userRow);
        $_str_code  = $this->obj_crypt->encrypt($_str_src, fn_baigoCrypt($_arr_apiChks['appRow']['app_key'], $_arr_apiChks['appRow']['app_name']));

        $_arr_tplData = array(
            'code'  => $_str_code,
            'rcode' => 'y010401',
        );
        $this->general_api->show_result($_arr_tplData);
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

        $_arr_userInput = $this->mdl_user_api->input_user('get');
        if ($_arr_userInput['rcode'] != 'ok') {
            $this->general_api->show_result($_arr_userInput);
        }

        $_arr_sign = array(
            'act' => $GLOBALS['route']['bg_act'],
            $_arr_userInput['user_by'] => $_arr_userInput['user_str'],
        );

        if (!$this->obj_sign->sign_check(array_merge($_arr_apiChks['appInput'], $_arr_sign), $_arr_apiChks['appInput']['signature'])) {
            $_arr_tplData = array(
                'rcode' => 'x050403',
            );
            $this->general_api->show_result($_arr_tplData);
        }

        $_arr_userRow = $this->mdl_user_api->mdl_read($_arr_userInput['user_str'], $_arr_userInput['user_by']);
        if ($_arr_userRow['rcode'] != 'y010102') {
            $this->general_api->show_result($_arr_userRow);
        }

        //print_r($_arr_userRow);
        unset($_arr_userRow['user_pass'], $_arr_userRow['user_note']);

        //unset($_arr_userRow['rcode']);
        $_str_src   = $this->general_api->encode_result($_arr_userRow);
        $_str_code  = $this->obj_crypt->encrypt($_str_src, fn_baigoCrypt($_arr_apiChks['appRow']['app_key'], $_arr_apiChks['appRow']['app_name']));

        $_arr_tplData = array(
            'code'   => $_str_code,
            'rcode'  => $_arr_userRow['rcode'],
        );

        $this->general_api->show_result($_arr_tplData);
    }


    /**
     * ctrl_edit function.
     *
     * @access public
     * @return void
     */
    function ctrl_edit() {
        $_arr_apiChks = $this->general_api->app_chk('post');
        if ($_arr_apiChks['rcode'] != 'ok') {
            $this->general_api->show_result($_arr_apiChks);
        }

        if (!isset($_arr_apiChks['appRow']['app_allow']['user']['edit'])) { //无权限并记录日志
            $_arr_tplData = array(
                'rcode' => 'x050308',
            );
            $_arr_logTarget[] = array(
                'app_id' => $_arr_apiChks['appInput']['app_id'],
            );
            $_arr_logType = array('user', 'edit');
            $this->general_api->show_result($_arr_tplData);
        }

        $_arr_userInput = $this->mdl_user_api->input_edit();
        if ($_arr_userInput['rcode'] != 'ok') {
            $this->general_api->show_result($_arr_userInput);
        }

        $_arr_userRow = $this->mdl_user_api->mdl_read($_arr_userInput['user_str'], $_arr_userInput['user_by']);
        if ($_arr_userRow['rcode'] != 'y010102') {
            $this->general_api->show_result($_arr_userRow);
        }

        if ($_arr_userRow['user_status'] == 'disable') {
            $_arr_tplData = array(
                'rcode' => 'x010401',
            );
            $this->general_api->show_result($_arr_tplData);
        }

        $_arr_sign = array(
            'act' => $GLOBALS['route']['bg_act'],
            $_arr_userInput['user_by'] => $_arr_userInput['user_str'],
        );

        $_arr_userSubmit = array();

        if (isset($_arr_userInput['user_pass']) && !fn_isEmpty($_arr_userInput['user_pass'])) {
            $_arr_sign['user_pass'] = $_arr_userInput['user_pass'];

            $_arr_userSubmit['user_pass'] = fn_baigoCrypt($_arr_userInput['user_pass'], $_arr_userRow['user_name'], true);
        }

        if (isset($_arr_userInput['user_mail_new']) && !fn_isEmpty($_arr_userInput['user_mail_new'])) {
            $_arr_sign['user_mail_new'] = $_arr_userInput['user_mail_new'];
        }

        if (isset($_arr_userInput['user_nick']) && !fn_isEmpty($_arr_userInput['user_nick'])) {
            $_arr_sign['user_nick'] = $_arr_userInput['user_nick'];
        }

        if (isset($_arr_userInput['user_contactStr']) && !fn_isEmpty($_arr_userInput['user_contactStr'])) {
            $_arr_sign['user_contact'] = $_arr_userInput['user_contactStr'];
        }

        if (isset($_arr_userInput['user_extendStr']) && !fn_isEmpty($_arr_userInput['user_extendStr'])) {
            $_arr_sign['user_extend'] = $_arr_userInput['user_extendStr'];
        }

        //print_r($_arr_userInput);
        //print_r(array_merge($_arr_apiChks['appInput'], $_arr_sign));

        if (!$this->obj_sign->sign_check(array_merge($_arr_apiChks['appInput'], $_arr_sign), $_arr_apiChks['appInput']['signature'])) {
            $_arr_tplData = array(
                'rcode' => 'x050403',
            );
            $this->general_api->show_result($_arr_tplData);
        }

        if (!isset($_arr_apiChks['appRow']['app_allow']['user']['global'])) {  //是否授权
            $_arr_belongRow = $this->mdl_belong->mdl_read($_arr_userRow['user_id'], $_arr_apiChks['appInput']['app_id']);
            if ($_arr_belongRow['rcode'] != 'y070102') {
                $_arr_tplData = array(
                    'rcode' => 'x050308',
                );
                $this->general_api->show_result($_arr_tplData);
            }
        }

        if ((BG_REG_ONEMAIL == 'false' || BG_LOGIN_MAIL == 'on') && isset($_arr_userInput['user_mail_new']) && !fn_isEmpty($_arr_userInput['user_mail_new'])) {
            $_arr_userCheck = $this->mdl_user_api->mdl_read($_arr_userInput['user_mail_new'], 'user_mail', $_arr_userRow['user_id']); //检查邮箱
            if ($_arr_userCheck['rcode'] == 'y010102') {
                return array(
                    'rcode' => 'x010211',
                );
            }
        }

        //file_put_contents(BG_PATH_ROOT . 'test.txt', $_str_userPass . '||' . $_str_rand);

        $_arr_userSubmit['user_id'] = $_arr_userRow['user_id'];

        $_arr_returnRow              = $this->mdl_user_api->mdl_edit($_arr_userSubmit);
        $_arr_returnRow['user_name'] = $_arr_userRow['user_name'];

        //unset($_arr_returnRow['rcode']);
        $this->general_api->notify_result($_arr_returnRow, 'edit'); //通知

        $_arr_tplData = array(
            'rcode' => $_arr_returnRow['rcode'],
        );
        $this->general_api->show_result($_arr_tplData);
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

        if (!isset($_arr_apiChks['appRow']['app_allow']['user']['del'])) {
            $_arr_tplData = array(
                'rcode' => 'x050309',
            );
            $_arr_logTarget[] = array(
                'app_id' => $_arr_apiChks['appInput']['app_id'],
            );
            $_arr_logType = array('user', 'del');
            $this->general_api->show_result($_arr_tplData);
        }

        $_arr_userIds   = $this->mdl_user_api->input_ids_api();

        $_arr_sign = array(
            'act'       => $GLOBALS['route']['bg_act'],
            'user_ids'  => $_arr_userIds['str_userIds'],
        );

        if (!$this->obj_sign->sign_check(array_merge($_arr_apiChks['appInput'], $_arr_sign), $_arr_apiChks['appInput']['signature'])) {
            $_arr_tplData = array(
                'rcode' => 'x050403',
            );
            $this->general_api->show_result($_arr_tplData);
        }

        if (!isset($_arr_apiChks['appRow']['app_allow']['user']['global'])) {
            $_arr_search = array(
                'app_id'    => $_arr_apiChks['appInput']['app_id'],
                'user_ids'  => $_arr_userIds['user_ids'],
            );
            $_arr_users = $this->mdl_belong->mdl_list(1000, 0, $_arr_search);
        } else {
            $_arr_users = $_arr_userIds;
        }

        $_arr_userDel = $this->mdl_user_api->mdl_del($_arr_users);

        $_str_src   = $this->general_api->encode_result($_arr_userIds);

        $this->general_api->notify_result($_arr_userIds, 'del'); //通知

        $_arr_tplData = array(
            'rcode' => $_arr_userDel['rcode'],
        );
        $this->general_api->show_result($_arr_tplData);
    }


    /**
     * ctrl_chkname function.
     *
     * @access public
     * @return void
     */
    function ctrl_chkname() {
        $_arr_apiChks = $this->general_api->app_chk('get');
        if ($_arr_apiChks['rcode'] != 'ok') {
            $this->general_api->show_result($_arr_apiChks);
        }

        $_arr_userName = $this->mdl_user_api->input_name();
        if ($_arr_userName['rcode'] != 'ok') {
            $this->general_api->show_result($_arr_userName);
        }

        $_arr_userName['act'] = $GLOBALS['route']['bg_act'];

        if (!$this->obj_sign->sign_check(array_merge($_arr_apiChks['appInput'], $_arr_userName), $_arr_apiChks['appInput']['signature'])) {
            $_arr_tplData = array(
                'rcode' => 'x050403',
            );
            $this->general_api->show_result($_arr_tplData);
        }

        $_arr_userRow = $this->mdl_user_api->mdl_read($_arr_userName['user_name'], 'user_name');
        if ($_arr_userRow['rcode'] == 'y010102') {
            $_str_rcode = 'x010205';
        } else {
            $_str_rcode = 'y010205';
        }

        $_arr_tplData = array(
            'rcode' => $_str_rcode,
        );
        $this->general_api->show_result($_arr_tplData);
    }


    /**
     * ctrl_chkmail function.
     *
     * @access public
     * @return void
     */
    function ctrl_chkmail() {
        $_arr_apiChks = $this->general_api->app_chk('get');
        if ($_arr_apiChks['rcode'] != 'ok') {
            $this->general_api->show_result($_arr_apiChks);
        }

        $_arr_userMail = $this->mdl_user_api->input_mail();
        if ($_arr_userMail['rcode'] != 'ok') {
            $this->general_api->show_result($_arr_userMail);
        }

        if (BG_REG_ONEMAIL == 'false' || BG_LOGIN_MAIL == 'on') { //不允许重复
            if ($_arr_userMail['rcode'] != 'ok') {
                $this->general_api->show_result($_arr_userMail);
            }

            if (fn_isEmpty($_arr_userMail['user_mail'])) {
                $_str_rcode = 'y010211';
            } else {
                $_arr_userRow = $this->mdl_user_api->mdl_read($_arr_userMail['user_mail'], 'user_mail', $_arr_userMail['not_id']);
                if ($_arr_userRow['rcode'] == 'y010102') {
                    $_str_rcode = 'x010211';
                } else {
                    $_str_rcode = 'y010211';
                }
            }
        } else {
            $_str_rcode = 'y010211';
        }

        $_arr_sign = array(
            'act'       => $GLOBALS['route']['bg_act'],
            'user_mail' => $_arr_userMail['user_mail'],
            'not_id'    => $_arr_userMail['not_id'],
        );

        if (!$this->obj_sign->sign_check(array_merge($_arr_apiChks['appInput'], $_arr_sign), $_arr_apiChks['appInput']['signature'])) {
            $_str_rcode = 'x050403';
        }

        $_arr_tplData = array(
            'rcode' => $_str_rcode,
        );
        $this->general_api->show_result($_arr_tplData);
    }
}
