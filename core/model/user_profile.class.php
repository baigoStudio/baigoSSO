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
class MODEL_USER_PROFILE extends MODEL_USER {

    public $obj_db;
    public $qaInput;
    public $infoInput;
    public $mailboxInput;
    public $tokenInput;

    function __construct() { //构造函数
        $this->obj_db = $GLOBALS['obj_db']; //设置数据库对象
    }

    function mdl_token($num_userId, $str_userName) {
        $_str_accessToken   = fn_rand(32);
        $_tm_accessExpire   = time() + BG_ACCESS_EXPIRE * 60;

        $_arr_userData = array(
            'user_access_token'     => $_str_accessToken,
            'user_access_expire'    => $_tm_accessExpire,
        );

        $_num_db = $this->obj_db->update(BG_DB_TABLE . 'user', $_arr_userData, '`user_id`=' . $num_userId); //更新数据
        if ($_num_db > 0) {
            $_str_rcode = 'y010103'; //更新成功
        } else {
            return array(
                'rcode' => 'x010103', //更新失败
            );
        }

        return array(
            'user_id'               => $num_userId,
            'user_access_token'     => fn_baigoCrypt($_str_accessToken, $str_userName),
            'user_access_expire'    => $_tm_accessExpire,
            'rcode'                 => $_str_rcode, //成功
        );
    }


    function mdl_mailbox($num_userId, $str_mailbox) {
        $_arr_userData = array(
            'user_mail' => $str_mailbox,
        );

        $_num_db   = $this->obj_db->update(BG_DB_TABLE . 'user', $_arr_userData, '`user_id`=' . $num_userId); //更新数据

        if ($_num_db >= 0) {
            $_str_rcode = 'y010405'; //更新成功
        } else {
            return array(
                'rcode' => 'x010405', //更新失败
            );
        }

        return array(
            'user_mail' => $str_mailbox, //成功
            'rcode'     => $_str_rcode, //成功
        );
    }


    function mdl_qa($arr_userSubmit = array()) {

        $_arr_userData = array();

        for ($_iii = 1; $_iii <= 3; $_iii++) {
            if (isset($arr_userSubmit['user_sec_ques_' . $_iii]) && !fn_isEmpty($arr_userSubmit['user_sec_ques_' . $_iii])) {
                $_arr_userData['user_sec_ques_' . $_iii] = $arr_userSubmit['user_sec_ques_' . $_iii];
            } else if (isset($this->qaInput['user_sec_ques_' . $_iii])) {
                $_arr_userData['user_sec_ques_' . $_iii] = $this->qaInput['user_sec_ques_' . $_iii];
            }

            $_arr_userData['user_sec_answ_' . $_iii] = $arr_userSubmit['user_sec_answ_' . $_iii];
        }

        //print_r($arr_userSubmit);

        $_num_db = $this->obj_db->update(BG_DB_TABLE . 'user', $_arr_userData, '`user_id`=' . $arr_userSubmit['user_id']); //更新数据

        if ($_num_db > 0) {
            $_str_rcode = 'y010412'; //更新成功
        } else {
            return array(
                'rcode' => 'x010412', //更新失败
            );
        }

        return array(
            'rcode'     => $_str_rcode, //成功
        );
    }


    /** 忘记密码
     * mdl_pass function.
     *
     * @access public
     * @param mixed $num_userId
     * @return void
     */
    function mdl_pass($num_userId, $str_userPass) {
        $_arr_userData = array(
            'user_pass'         => $str_userPass,
            'user_crypt_type'   => 2,
        );

        if (!fn_isEmpty($_arr_userData)) {
            $_num_db   = $this->obj_db->update(BG_DB_TABLE . 'user', $_arr_userData, '`user_id`=' . $num_userId); //更新数据
        }

        if ($_num_db >= 0) {
            $_str_rcode = 'y010407'; //更新成功
        } else {
            return array(
                'rcode' => 'x010407', //更新失败
            );
        }

        return array(
            'rcode'      => $_str_rcode, //成功
        );
    }


    function mdl_info($arr_userSubmit = array()) {
        $_arr_userData = array();

        if (isset($arr_userSubmit['user_nick']) && !fn_isEmpty($arr_userSubmit['user_nick'])) {
            $_arr_userData['user_nick'] = $arr_userSubmit['user_nick'];
        } else if (isset($this->infoInput['user_nick']) && !fn_isEmpty($this->infoInput['user_nick'])) { //如果 昵称 为空，则不修改
            $_arr_userData['user_nick'] = $this->infoInput['user_nick'];
        }

        if (isset($arr_userSubmit['user_contact']) && !fn_isEmpty($arr_userSubmit['user_contact'])) {
            $_arr_userData['user_contact'] = $arr_userSubmit['user_contact'];
        } else if (isset($this->infoInput['user_contact']) && !fn_isEmpty($this->infoInput['user_contact'])) { //如果 联系方式 为空，则不修改
            $_arr_userData['user_contact'] = $this->infoInput['user_contact'];
        }

        if (isset($arr_userSubmit['user_extend']) && !fn_isEmpty($arr_userSubmit['user_extend'])) {
            $_arr_userData['user_extend'] = $arr_userSubmit['user_extend'];
        } else if (isset($this->infoInput['user_extend']) && !fn_isEmpty($this->infoInput['user_extend'])) { //如果 扩展字段 为空，则不修改
            $_arr_userData['user_extend'] = $this->infoInput['user_extend'];
        }

        $_num_db   = $this->obj_db->update(BG_DB_TABLE . 'user', $_arr_userData, '`user_id`=' . $arr_userSubmit['user_id']); //更新数据

        if ($_num_db > 0) {
            $_str_rcode = 'y010103'; //更新成功
        } else {
            return array(
                'rcode' => 'x010103', //更新失败
            );
        }

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


    function input_qa() {
        $_arr_userInput = $this->input_user('post');

        if ($_arr_userInput['rcode'] != 'ok') {
            return $_arr_userInput;
        }

        $this->qaInput   = $_arr_userInput;

        $_arr_userPass    = $this->chk_user_pass(fn_post('user_pass'));
        if ($_arr_userPass['rcode'] != 'ok') {
            return $_arr_userPass;
        }
        $this->qaInput['user_pass']   = $_arr_userPass['user_pass'];

        for ($_iii = 1; $_iii <= 3; $_iii++) {
            $_arr_userSecQues = fn_validate(fn_post('user_sec_ques_' . $_iii), 1, 0);
            switch ($_arr_userSecQues['status']) {
                case 'too_short':
                    return array(
                        'rcode' => 'x010238',
                    );
                break;

                case 'ok':
                    $this->qaInput['user_sec_ques_' . $_iii] = $_arr_userSecQues['str'];
                break;
            }

            $_arr_userSecAnsw = fn_validate(fn_post('user_sec_answ_' . $_iii), 1, 0);
            switch ($_arr_userSecAnsw['status']) {
                case 'too_short':
                    return array(
                        'rcode' => 'x010237',
                    );
                break;

                case 'ok':
                    $this->qaInput['user_sec_answ_' . $_iii] = $_arr_userSecAnsw['str'];
                break;
            }
        }

        $this->qaInput['rcode']  = 'ok';

        return $this->qaInput;
    }


    function input_info() {
        $_arr_userInput = $this->input_user('post');

        if ($_arr_userInput['rcode'] != 'ok') {
            return $_arr_userInput;
        }

        $this->infoInput   = $_arr_userInput;

        $_arr_userPass    = $this->chk_user_pass(fn_post('user_pass'));
        if ($_arr_userPass['rcode'] != 'ok') {
            return $_arr_userPass;
        }
        $this->infoInput['user_pass']   = $_arr_userPass['user_pass'];

        $_arr_userNick = $this->chk_user_nick(fn_post('user_nick'));
        if ($_arr_userNick['rcode'] != 'ok') {
            return $_arr_userNick;
        }
        $this->infoInput['user_nick'] = $_arr_userNick['user_nick'];

        $_str_userContact                   = fn_getSafe(fn_post('user_contact'), 'txt', '');
        $this->infoInput['user_contactStr'] = $_str_userContact;

        $_str_userContact                   = fn_htmlcode($_str_userContact, 'decode', 'json');
        $_arr_userContact                   = json_decode($_str_userContact, true);

        $this->infoInput['user_contact']    = fn_jsonEncode($_arr_userContact, 'encode');


        $_str_userExtend                    = fn_getSafe(fn_post('user_extend'), 'txt', '');
        $this->infoInput['user_extendStr']  = $_str_userExtend;

        $_str_userExtend                    = fn_htmlcode($_str_userExtend, 'decode', 'json');
        $_arr_userExtend                    = json_decode($_str_userExtend, true);

        $this->infoInput['user_extend']     = fn_jsonEncode($_arr_userExtend, 'encode');

        $this->infoInput['rcode'] = 'ok';

        return $this->infoInput;
    }


    function input_mailbox() {
        $_arr_userInput = $this->input_user('post');

        if ($_arr_userInput['rcode'] != 'ok') {
            return $_arr_userInput;
        }

        $this->mailboxInput = $_arr_userInput;

        $_arr_userPass = $this->chk_user_pass(fn_post('user_pass'));
        if ($_arr_userPass['rcode'] != 'ok') {
            return $_arr_userPass;
        }
        $this->mailboxInput['user_pass'] = $_arr_userPass['user_pass'];

        $_arr_userMailNew = $this->chk_user_mail(fn_post('user_mail_new'), 1);
        if ($_arr_userMailNew['rcode'] != 'ok') {
            return $_arr_userMailNew;
        }
        $this->mailboxInput['user_mail_new'] = $_arr_userMailNew['user_mail'];

        $this->mailboxInput['rcode'] = 'ok';

        return $this->mailboxInput;
    }


    function input_pass() {
        $_arr_userInput = $this->input_user('post');

        if ($_arr_userInput['rcode'] != 'ok') {
            return $_arr_userInput;
        }
        $this->passInput   = $_arr_userInput;

        $_arr_userPass    = $this->chk_user_pass(fn_post('user_pass'));
        if ($_arr_userPass['rcode'] != 'ok') {
            return $_arr_userPass;
        }
        $this->passInput['user_pass']   = $_arr_userPass['user_pass'];

        $_arr_userPassNew = fn_validate(fn_post('user_pass_new'), 1, 0);
        switch ($_arr_userPassNew['status']) {
            case 'too_short':
                return array(
                    'rcode' => 'x010222',
                );
            break;

            case 'ok':
                $this->passInput['user_pass_new'] = $_arr_userPassNew['str'];
            break;
        }

        $this->passInput['rcode']  = 'ok';

        return $this->passInput;
    }


    function input_token($str_method = 'get') {
        $_arr_userInput = $this->input_user($str_method);
        if ($_arr_userInput['rcode'] != 'ok') {
            return $_arr_userInput;
        }

        $this->tokenInput = $_arr_userInput;

        if ($str_method == 'get') {
            $_str_refreshToken = fn_get('user_refresh_token');
        } else {
            $_str_refreshToken = fn_post('user_refresh_token');
        }

        $_arr_refreshToken = fn_validate($_str_refreshToken, 1, 32);
        switch ($_arr_refreshToken['status']) {
            case 'too_short':
                return array(
                    'rcode' => 'x010232',
                );
            break;

            case 'too_long':
                return array(
                    'rcode' => 'x010233',
                );
            break;

            case 'ok':
                $this->tokenInput['user_refresh_token'] = $_arr_refreshToken['str'];
            break;

        }

        $this->tokenInput['rcode'] = 'ok';

        return $this->tokenInput;
    }
}

