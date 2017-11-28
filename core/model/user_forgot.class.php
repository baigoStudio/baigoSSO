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
class MODEL_USER_FORGOT extends MODEL_USER {

    public $obj_db;
    public $forgotInputReset;
    public $inputByqa;

    function __construct() { //构造函数
        $this->obj_db = $GLOBALS['obj_db']; //设置数据库对象
    }

    function input_reset() {
        if (!fn_seccode()) { //验证码
            return array(
                'rcode'     => 'x030205',
            );
        }

        if (!fn_token('chk')) { //令牌
            return array(
                'rcode' => 'x030206',
            );
        }

        $_arr_userPassNew = fn_validate(fn_post('user_pass_new'), 1, 0);
        switch ($_arr_userPassNew['status']) {
            case 'too_short':
                return array(
                    'rcode' => 'x010222',
                );
            break;

            case 'ok':
                $this->forgotInputReset['user_pass_new'] = $_arr_userPassNew['str'];
            break;
        }

        $_arr_userPassConfirm = fn_validate(fn_post('user_pass_confirm'), 1, 0);
        switch ($_arr_userPassConfirm['status']) {
            case 'too_short':
                return array(
                    'rcode' => 'x010224',
                );
            break;

            case 'ok':
                $this->forgotInputReset['user_pass_confirm'] = $_arr_userPassConfirm['str'];
            break;
        }

        if ($this->forgotInputReset['user_pass_new'] != $this->forgotInputReset['user_pass_confirm']) {
            return array(
                'rcode' => 'x010225',
            );
        }

        $this->forgotInputReset['rcode'] = 'ok';

        return $this->forgotInputReset;
    }


    function input_byqa() {
        $_arr_userInput = $this->input_user('post');

        if ($_arr_userInput['rcode'] != 'ok') {
            return $_arr_userInput;
        }

        $this->inputByqa   = $_arr_userInput;

        for ($_iii = 1; $_iii <= 3; $_iii++) {
            $_arr_userSecAnsw = fn_validate(fn_post('user_sec_answ_' . $_iii), 1, 0);
            switch ($_arr_userSecAnsw['status']) {
                case 'too_short':
                    return array(
                        'rcode' => 'x010237',
                    );
                break;

                case 'ok':
                    $this->inputByqa['user_sec_answ_' . $_iii] = $_arr_userSecAnsw['str'];
                break;
            }
        }

        $_arr_userPassNew = fn_validate(fn_post('user_pass_new'), 1, 0);
        switch ($_arr_userPassNew['status']) {
            case 'too_short':
                return array(
                    'rcode' => 'x010222',
                );
            break;

            case 'ok':
                $this->inputByqa['user_pass_new'] = $_arr_userPassNew['str'];
            break;
        }

        $_arr_userPassConfirm = fn_validate(fn_post('user_pass_confirm'), 1, 0);
        switch ($_arr_userPassConfirm['status']) {
            case 'too_short':
                return array(
                    'rcode' => 'x010224',
                );
            break;

            case 'ok':
                $this->inputByqa['user_pass_confirm'] = $_arr_userPassConfirm['str'];
            break;
        }

        if ($this->inputByqa['user_pass_new'] != $this->inputByqa['user_pass_confirm']) {
            return array(
                'rcode' => 'x010225',
            );
        }

        $this->inputByqa['rcode'] = 'ok';

        return $this->inputByqa;
    }
}

