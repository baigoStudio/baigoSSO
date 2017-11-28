<?php
/*-----------------------------------------------------------------
！！！！警告！！！！
以下为系统文件，请勿修改
-----------------------------------------------------------------*/

//不能非法包含或直接执行
if (!defined('IN_BAIGO')) {
    exit('Access Denied');
}

/*-------------用户模型-------------*/
class MODEL_USER_API extends MODEL_USER {

    public $obj_db;
    public $regInput;
    public $editInput;
    public $loginInput;
    public $tokenInput;

    function __construct() { //构造函数
        $this->obj_db = $GLOBALS['obj_db']; //设置数据库对象
    }

    function mdl_reg($arr_userSubmit = array()) {
        $_arr_userData = array(
            'user_pass'             => $arr_userSubmit['user_pass'],
            'user_time'             => time(),
            'user_time_login'       => time(),
            'user_crypt_type'       => 2,
        );

        if (isset($arr_userSubmit['user_name'])) {
            $_arr_userData['user_name'] = $arr_userSubmit['user_name'];
        } else if (isset($this->regInput['user_name'])) {
            $_arr_userData['user_name'] = $this->regInput['user_name'];
        }

        if (isset($arr_userSubmit['user_mail'])) {
            $_arr_userData['user_mail'] = $arr_userSubmit['user_mail'];
        } else if (isset($this->regInput['user_mail'])) {
            $_arr_userData['user_mail'] = $this->regInput['user_mail'];
        } else {
            $_arr_userData['user_mail'] = '';
        }

        if (isset($arr_userSubmit['user_nick'])) {
            $_arr_userData['user_nick'] = $arr_userSubmit['user_nick'];
        } else if (isset($this->regInput['user_nick'])) {
            $_arr_userData['user_nick'] = $this->regInput['user_nick'];
        } else {
            $_arr_userData['user_nick'] = '';
        }

        if (isset($arr_userSubmit['user_contact'])) {
            $_arr_userData['user_contact'] = $arr_userSubmit['user_contact'];
        } else if (isset($this->regInput['user_contact'])) {
            $_arr_userData['user_contact'] = $this->regInput['user_contact'];
        }

        if (isset($arr_userSubmit['user_extend'])) {
            $_arr_userData['user_extend'] = $arr_userSubmit['user_extend'];
        } else if (isset($this->regInput['user_extend'])) {
            $_arr_userData['user_extend'] = $this->regInput['user_extend'];
        }

        if (isset($arr_userSubmit['user_status'])) {
            $_arr_userData['user_status'] = $arr_userSubmit['user_status'];
        } else if (isset($this->regInput['user_status'])) {
            $_arr_userData['user_status'] = $this->regInput['user_status'];
        }

        if (isset($arr_userSubmit['user_note'])) {
            $_arr_userData['user_note'] = $arr_userSubmit['user_note'];
        } else if (isset($this->regInput['user_note'])) {
            $_arr_userData['user_note'] = $this->regInput['user_note'];
        }

        if (isset($arr_userSubmit['user_ip'])) {
            $_arr_userData['user_ip'] = $arr_userSubmit['user_ip'];
        } else if (isset($this->regInput['user_ip'])) {
            $_arr_userData['user_ip'] = $this->regInput['user_ip'];
        } else {
            $_arr_userData['user_ip'] = fn_getIp();
        }

        $_num_userId = $this->obj_db->insert(BG_DB_TABLE . 'user', $_arr_userData); //更新数据
        if ($_num_userId > 0) {
            $_str_rcode  = 'y010101'; //更新成功
        } else {
            return array(
                'rcode' => 'x010101', //更新失败
            );
        }


        unset($_arr_userData['user_pass'], $_arr_userData['user_crypt_type']);

        if (isset($_arr_userData['user_contact'])) {
            $_arr_userData['user_contact']   = fn_jsonEncode($_arr_userData['user_contact'], 'encode');
        }
        if (isset($_arr_userData['user_extend'])) {
            $_arr_userData['user_extend']    = fn_jsonEncode($_arr_userData['user_extend'], 'encode');
        }

        $_arr_userReturn            = $_arr_userData;
        $_arr_userReturn['user_id'] = $_num_userId;
        $_arr_userReturn['rcode']   = $_str_rcode;

        return $_arr_userReturn;
    }


    function mdl_login($arr_userSubmit = array()) {
        $_tm_timeLogin  = time();

        $_arr_userData = array(
            'user_time_login'   => $_tm_timeLogin,
        );

        if (isset($arr_userSubmit['user_ip']) && !fn_isEmpty($arr_userSubmit['user_ip'])) {
            $_str_userIp = $arr_userSubmit['user_ip'];
        } else {
            $_str_userIp = fn_getIp();
        }
        $_arr_userData['user_ip'] = $_str_userIp;

        $_arr_userRow = parent::mdl_read($arr_userSubmit['user_id']);

        if ($_arr_userRow['user_access_expire'] <= time()) { //如果访问口令过期
            $_str_accessToken   = fn_rand(32);
            $_tm_accessExpire   = time() + BG_ACCESS_EXPIRE * 60;

            $_arr_userData['user_access_token']     = $_str_accessToken;
            $_arr_userData['user_access_expire']    = $_tm_accessExpire;
        } else {
            $_str_accessToken   = $_arr_userRow['user_access_token'];
            $_tm_accessExpire   = $_arr_userRow['user_access_expire'];
        }

        if ($_arr_userRow['user_refresh_expire'] <= time()) { //如果刷新口令过期
            $_str_refreshToken  = fn_rand(32);
            $_tm_refreshExpire  = time() + BG_REFRESH_EXPIRE * 86400;

            $_arr_userData['user_refresh_token']    = $_str_refreshToken;
            $_arr_userData['user_refresh_expire']   = $_tm_refreshExpire;
        } else {
            $_str_refreshToken  = $_arr_userRow['user_refresh_token'];
            $_tm_refreshExpire  = $_arr_userRow['user_refresh_expire'];
        }

        $_num_db = $this->obj_db->update(BG_DB_TABLE . 'user', $_arr_userData, '`user_id`=' . $arr_userSubmit['user_id']); //更新数据
        if ($_num_db > 0) {
            $_str_rcode = 'y010103'; //更新成功
        } else {
            return array(
                'rcode' => 'x010103', //更新失败
            );
        }

        return array(
            'user_id'               => $arr_userSubmit['user_id'],
            'user_ip'               => $_str_userIp,
            'user_time_login'       => $_tm_timeLogin,
            'user_access_token'     => fn_baigoCrypt($_str_accessToken, $_arr_userRow['user_name']),
            'user_access_expire'    => $_tm_accessExpire,
            'user_refresh_token'    => fn_baigoCrypt($_str_refreshToken, $_arr_userRow['user_name']),
            'user_refresh_expire'   => $_tm_refreshExpire,
            'rcode'                 => $_str_rcode, //成功
        );
    }


    function mdl_read($str_user, $str_by = 'user_id', $num_notId = 0) {
        $_arr_userSelect = array(
            'user_id',
            'user_name',
            'user_mail',
            'user_contact',
            'user_extend',
            'user_nick',
            'user_status',
            'user_time',
            'user_time_login',
            'user_ip',
        );

        for ($_iii = 1; $_iii <= 3; $_iii++) {
            $_arr_userSelect[] = 'user_sec_ques_' . $_iii;
        }

        switch ($str_by) {
            case 'user_id':
                $_str_sqlWhere = '`user_id`=' . $str_user;
            break;
            default:
                $_str_sqlWhere = $str_by . '=\'' . $str_user . '\'';
            break;
        }

        if ($num_notId > 0) {
            $_str_sqlWhere .= ' AND `user_id`<>' . $num_notId;
        }

        $_arr_userRows    = $this->obj_db->select(BG_DB_TABLE . 'user', $_arr_userSelect, $_str_sqlWhere, '', '', 1, 0); //检查本地表是否存在记录

        if (isset($_arr_userRows[0])) { //用户名不存在则返回错误
            $_arr_userRow = $_arr_userRows[0];
        } else {
            return array(
                'rcode' => 'x010102', //不存在记录
            );
        }

        $_arr_userRow['user_contact']   = fn_jsonDecode($_arr_userRow['user_contact'], 'decode');
        $_arr_userRow['user_extend']    = fn_jsonDecode($_arr_userRow['user_extend'], 'decode');

        $_arr_userRow['rcode']          = 'y010102';

        return $_arr_userRow;
    }


    function mdl_edit($arr_userSubmit = array()) {
        $_arr_userData = array();

        if (isset($arr_userSubmit['user_pass']) && !fn_isEmpty($arr_userSubmit['user_pass'])) { //如果 新密码 为空，则不修改
            $_arr_userData['user_pass']         = $arr_userSubmit['user_pass'];
            $_arr_userData['user_crypt_type']   = 2;
        }

        if (isset($this->editInput['user_mail_new']) && $this->editInput['user_mail_new']) { //如果 新邮箱 为空，则不修改
            $_arr_userData['user_mail'] = $this->editInput['user_mail_new'];
        }

        if (isset($this->editInput['user_nick']) && $this->editInput['user_nick']) { //如果 昵称 为空，则不修改
            $_arr_userData['user_nick'] = $this->editInput['user_nick'];
        }

        if (isset($this->editInput['user_contact'])) { //如果 联系方式 为空，则不修改
            $_arr_userData['user_contact'] = $this->editInput['user_contact'];
        }

        if (isset($this->editInput['user_extend'])) { //如果 扩展字段 为空，则不修改
            $_arr_userData['user_extend'] = $this->editInput['user_extend'];
        }

        //print_r($_arr_userData);
        //exit;

        $_num_db   = $this->obj_db->update(BG_DB_TABLE . 'user', $_arr_userData, '`user_id`=' . $arr_userSubmit['user_id']); //更新数据

        if ($_num_db > 0) {
            $_str_rcode = 'y010103'; //更新成功
        } else {
            return array(
                'rcode' => 'x010103', //更新失败
            );
        }

        unset($_arr_userData['user_pass'], $_arr_userData['user_crypt_type']);

        if (isset($_arr_userData['user_contact'])) {
            $_arr_userData['user_contact']   = fn_jsonEncode($_arr_userData['user_contact'], 'encode');
        }
        if (isset($_arr_userData['user_extend'])) {
            $_arr_userData['user_extend']    = fn_jsonEncode($_arr_userData['user_extend'], 'encode');
        }

        $_arr_userReturn            = $_arr_userData;
        $_arr_userReturn['user_id'] = $arr_userSubmit['user_id'];
        $_arr_userReturn['rcode']   = $_str_rcode;

        return $_arr_userReturn;
    }


    /** api 注册表单验证
     * input_reg_api function.
     *
     * @access public
     * @return void
     */
    function input_reg() {
        $_arr_userName = $this->chk_user_name(fn_post('user_name'));
        if ($_arr_userName['rcode'] != 'ok') {
            return $_arr_userName;
        }
        $this->regInput['user_name'] = $_arr_userName['user_name'];

        $_arr_userRow = $this->mdl_read($this->regInput['user_name'], 'user_name');
        if ($_arr_userRow['rcode'] == 'y010102') {
            return array(
                'rcode' => 'x010205',
            );
        }

        $_arr_userMail = $this->chk_user_mail(fn_post('user_mail'));
        if ($_arr_userMail['rcode'] != 'ok') {
            return $_arr_userMail;
        }
        $this->regInput['user_mail'] = $_arr_userMail['user_mail'];

        if ((BG_REG_ONEMAIL == 'false' || BG_LOGIN_MAIL == 'on') && !fn_isEmpty($_arr_userMail['user_mail'])) {
            $_arr_userRow = $this->mdl_read($_arr_userMail['user_mail'], 'user_mail'); //检查邮箱
            if ($_arr_userRow['rcode'] == 'y010102') {
                return array(
                    'rcode' => 'x010211',
                );
            }
        }

        $_arr_userPass = $this->chk_user_pass(fn_post('user_pass'));
        if ($_arr_userPass['rcode'] != 'ok') {
            return $_arr_userPass;
        }
        $this->regInput['user_pass'] = $_arr_userPass['user_pass'];

        $_arr_userNick = $this->chk_user_nick(fn_post('user_nick'));
        if ($_arr_userNick['rcode'] != 'ok') {
            return $_arr_userNick;
        }
        $this->regInput['user_nick'] = $_arr_userNick['user_nick'];

        $this->regInput['user_ip']   = fn_getSafe(fn_post('user_ip'), 'txt', '');

        $_str_userContact                   = fn_getSafe(fn_post('user_contact'), 'txt', '');
        $this->regInput['user_contactStr']  = $_str_userContact;

        $_str_userContact                   = fn_htmlcode($_str_userContact, 'decode', 'json');
        $_arr_userContact                   = json_decode($_str_userContact, true);

        $this->regInput['user_contact']     = fn_jsonEncode($_arr_userContact, 'encode');


        $_str_userExtend                    = fn_getSafe(fn_post('user_extend'), 'txt', '');
        $this->regInput['user_extendStr']   = $_str_userExtend;

        $_str_userExtend                    = fn_htmlcode($_str_userExtend, 'decode', 'json');
        $_arr_userExtend                    = json_decode($_str_userExtend, true);

        $this->regInput['user_extend']      = fn_jsonEncode($_arr_userExtend, 'encode');

        $this->regInput['rcode']            = 'ok';

        return $this->regInput;
    }


    /** api 登录表单验证
     * input_login_api function.
     *
     * @access public
     * @return void
     */
    function input_login() {
        $_arr_userInput = $this->input_user('post');

        if ($_arr_userInput['rcode'] != 'ok') {
            return $_arr_userInput;
        }

        $this->loginInput   = $_arr_userInput;

        $_arr_userPass    = $this->chk_user_pass(fn_post('user_pass'));
        if ($_arr_userPass['rcode'] != 'ok') {
            return $_arr_userPass;
        }
        $this->loginInput['user_pass']   = $_arr_userPass['user_pass'];

        $this->loginInput['user_ip']     = fn_getSafe(fn_post('user_ip'), 'txt', '');

        $this->loginInput['rcode']       = 'ok';

        return $this->loginInput;
    }


    function input_edit() {
        $_arr_userInput = $this->input_user('post');
        if ($_arr_userInput['rcode'] != 'ok') {
            return $_arr_userInput;
        }

        $this->editInput = $_arr_userInput;

        if (!fn_isEmpty(fn_post('user_pass'))) {
            $this->editInput['user_pass']  = fn_post('user_pass');
        }

        if (!fn_isEmpty(fn_post('user_mail_new'))) {
            $_arr_userMailNew = $this->chk_user_mail(fn_post('user_mail_new'));
            if ($_arr_userMailNew['rcode'] != 'ok') {
                return $_arr_userMailNew;
            }
            $this->editInput['user_mail_new'] = $_arr_userMailNew['user_mail'];
        }

        $_arr_userNick = $this->chk_user_nick(fn_post('user_nick'));
        if ($_arr_userNick['rcode'] != 'ok') {
            return $_arr_userNick;
        }
        $this->editInput['user_nick']   = $_arr_userNick['user_nick'];

        $_str_userContact = fn_getSafe(fn_post('user_contact'), 'txt', '');
        $this->editInput['user_contactStr'] = $_str_userContact;
        $_str_userContact = fn_htmlcode($_str_userContact, 'decode', 'json');
        $_arr_userContact = json_decode($_str_userContact, true);
        $this->editInput['user_contact'] = fn_jsonEncode($_arr_userContact, 'encode');

        $_str_userExtend = fn_getSafe(fn_post('user_extend'), 'txt', '');
        $this->editInput['user_extendStr'] = $_str_userExtend;
        $_str_userExtend = fn_htmlcode($_str_userExtend, 'decode', 'json');
        $_arr_userExtend = json_decode($_str_userExtend, true);
        $this->editInput['user_extend'] = fn_jsonEncode($_arr_userExtend, 'encode');

        $this->editInput['rcode']       = 'ok';

        return $this->editInput;
    }


    function input_token($str_method = 'get') {
        $_arr_userInput = $this->input_user($str_method);
        if ($_arr_userInput['rcode'] != 'ok') {
            return $_arr_userInput;
        }

        $this->tokenInput = $_arr_userInput;

        if ($str_method == 'get') {
            $_str_accessToken = fn_get('user_access_token');
        } else {
            $_str_accessToken = fn_post('user_access_token');
        }

        $_arr_accessToken = fn_validate($_str_accessToken, 1, 32);
        switch ($_arr_accessToken['status']) {
            case 'too_short':
                return array(
                    'rcode' => 'x010228',
                );
            break;

            case 'too_long':
                return array(
                    'rcode' => 'x010229',
                );
            break;

            case 'ok':
                $this->tokenInput['user_access_token'] = $_arr_accessToken['str'];
            break;

        }

        $this->tokenInput['rcode'] = 'ok';

        return $this->tokenInput;
    }
}

