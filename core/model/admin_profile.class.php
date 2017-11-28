<?php
/*-----------------------------------------------------------------
！！！！警告！！！！
以下为系统文件，请勿修改
-----------------------------------------------------------------*/

//不能非法包含或直接执行
if (!defined('IN_BAIGO')) {
    exit('Access Denied');
}

/*-------------管理员模型-------------*/
class MODEL_ADMIN_PROFILE extends MODEL_ADMIN {

    public $obj_db;
    public $infoInput;
    public $qaInput;
    public $passInput;
    public $mailboxInput;

    function __construct() { //构造函数
        $this->obj_db = $GLOBALS['obj_db']; //设置数据库对象
    }

    /** 修改个人资料
     * mdl_info function.
     *
     * @access public
     * @param mixed $num_adminId
     * @return void
     */
    function mdl_info($num_adminId) {
        $_arr_adminData = array(
            'admin_nick' => $this->infoInput['admin_nick'],
        );

        $_num_adminId = $num_adminId;
        $_num_db   = $this->obj_db->update(BG_DB_TABLE . 'admin', $_arr_adminData, '`admin_id`=' . $_num_adminId); //更新数据
        if ($_num_db > 0) {
            $_str_rcode = 'y020108'; //更新成功
        } else {
            return array(
                'rcode' => 'x020103', //更新失败
            );
        }

        return array(
            'admin_id'   => $_num_adminId,
            'rcode'      => $_str_rcode, //成功
        );
    }


    /** 修改个人资料表单验证
     * input_info function.
     *
     * @access public
     * @return void
     */
    function input_info() {
        if (!fn_token('chk')) { //令牌
            return array(
                'rcode' => 'x030206',
            );
        }

        $_arr_adminPass = fn_validate(fn_post('admin_pass'), 1, 0);
        switch ($_arr_adminPass['status']) {
            case 'too_short':
                return array(
                    'rcode' => 'x010243',
                );
            break;

            case 'ok':
                $this->infoInput['admin_pass'] = $_arr_adminPass['str'];
            break;
        }

        $_arr_adminNick = fn_validate(fn_post('admin_nick'), 0, 30);
        switch ($_arr_adminNick['status']) {
            case 'too_long':
                return array(
                    'rcode' => 'x020204',
                );
            break;

            case 'ok':
                $this->infoInput['admin_nick'] = $_arr_adminNick['str'];
            break;

        }

        $this->infoInput['rcode']  = 'ok';

        return $this->infoInput;
    }


    /**
     * input_qa function.
     *
     * @access public
     * @return void
     */
    function input_qa() {
        if (!fn_token('chk')) { //令牌
            return array(
                'rcode' => 'x030206',
            );
        }

        $_arr_adminPass = fn_validate(fn_post('admin_pass'), 1, 0);
        switch ($_arr_adminPass['status']) {
            case 'too_short':
                return array(
                    'rcode' => 'x010243',
                );
            break;

            case 'ok':
                $this->qaInput['admin_pass'] = $_arr_adminPass['str'];
            break;
        }

        for ($_iii = 1; $_iii <= 3; $_iii++) {
            $_arr_adminSecQues = fn_validate(fn_post('admin_sec_ques_' . $_iii), 1, 0);
            switch ($_arr_adminSecQues['status']) {
                case 'too_short':
                    return array(
                        'rcode' => 'x010238',
                    );
                break;

                case 'ok':
                    $this->qaInput['admin_sec_ques_' . $_iii] = $_arr_adminSecQues['str'];
                break;
            }

            $_arr_adminSecAnsw = fn_validate(fn_post('admin_sec_answ_' . $_iii), 1, 0);
            switch ($_arr_adminSecAnsw['status']) {
                case 'too_short':
                    return array(
                        'rcode' => 'x010237',
                    );
                break;

                case 'ok':
                    $this->qaInput['admin_sec_answ_' . $_iii] = $_arr_adminSecAnsw['str'];
                break;
            }
        }

        $this->qaInput['rcode']  = 'ok';

        return $this->qaInput;
    }


    /**
     * input_pass function.
     *
     * @access public
     * @return void
     */
    function input_pass() {
        if (!fn_token('chk')) { //令牌
            return array(
                'rcode' => 'x030206',
            );
        }

        $_arr_adminPass = fn_validate(fn_post('admin_pass'), 1, 0);
        switch ($_arr_adminPass['status']) {
            case 'too_short':
                return array(
                    'rcode' => 'x010243',
                );
            break;

            case 'ok':
                $this->passInput['admin_pass'] = $_arr_adminPass['str'];
            break;
        }

        $_arr_adminPassNew = fn_validate(fn_post('admin_pass_new'), 1, 0);
        switch ($_arr_adminPassNew['status']) {
            case 'too_short':
                return array(
                    'rcode' => 'x010222',
                );
            break;

            case 'ok':
                $this->passInput['admin_pass_new'] = $_arr_adminPassNew['str'];
            break;
        }

        $_arr_adminPassConfirm = fn_validate(fn_post('admin_pass_confirm'), 1, 0);
        switch ($_arr_adminPassConfirm['status']) {
            case 'too_short':
                return array(
                    'rcode' => 'x010224',
                );
            break;

            case 'ok':
                $this->passInput['admin_pass_confirm'] = $_arr_adminPassConfirm['str'];
            break;
        }

        if ($this->passInput['admin_pass_confirm'] != $this->passInput['admin_pass_new']) {
            return array(
                'rcode' => 'x010225',
            );
        }

        $this->passInput['rcode']  = 'ok';

        return $this->passInput;
    }


    /**
     * input_mailbox function.
     *
     * @access public
     * @return void
     */
    function input_mailbox() {
        if (!fn_token('chk')) { //令牌
            return array(
                'rcode' => 'x030206',
            );
        }

        $_arr_adminPass = fn_validate(fn_post('admin_pass'), 1, 0);
        switch ($_arr_adminPass['status']) {
            case 'too_short':
                return array(
                    'rcode' => 'x010243',
                );
            break;

            case 'ok':
                $this->mailboxInput['admin_pass'] = $_arr_adminPass['str'];
            break;
        }

        $_arr_adminMailNew = fn_validate(fn_post('admin_mail_new'), 1, 300, 'str', 'email');

        switch ($_arr_adminMailNew['status']) {
            case 'too_short':
                return array(
                    'rcode' => 'x010206',
                );
            break;

            case 'too_long':
                return array(
                    'rcode' => 'x010207',
                );
            break;

            case 'format_err':
                return array(
                    'rcode' => 'x010208',
                );
            break;

            case 'ok':
                $this->mailboxInput['admin_mail_new'] = $_arr_adminMailNew['str'];
            break;
        }

        $this->mailboxInput['rcode'] = 'ok';

        return $this->mailboxInput;
    }

}
