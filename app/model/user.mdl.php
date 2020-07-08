<?php
/*-----------------------------------------------------------------
！！！！警告！！！！
以下为系统文件，请勿修改
-----------------------------------------------------------------*/

namespace app\model;

use ginkgo\Json;
use ginkgo\Func;
use ginkgo\Config;

// 不能非法包含或直接执行
defined('IN_GINKGO') or exit('Access denied');

/*-------------用户模型-------------*/
class User extends User_Common {

    public $arr_status  = array('enable', 'wait', 'disabled');

    function check($mix_user, $str_by = 'user_id', $num_notId = 0) {
        $_arr_select = array(
            'user_id',
        );

        $_arr_userRow = $this->read($mix_user, $str_by, $num_notId, $_arr_select);

        return $_arr_userRow;
    }


    /** 提交
     * submit function.
     *
     * @access public
     * @param string $str_userPass (default: '')
     * @return void
     */
    function submit() {
        $_arr_userData = array();

        if (isset($this->inputSubmit['user_name'])) {
            $_arr_userData['user_name'] = $this->inputSubmit['user_name'];
        }

        if (isset($this->inputSubmit['user_mail'])) {
            $_arr_userData['user_mail'] = $this->inputSubmit['user_mail'];
        }

        if (isset($this->inputSubmit['user_status'])) {
            $_arr_userData['user_status'] = $this->inputSubmit['user_status'];
        }

        if (isset($this->inputSubmit['user_nick'])) {
            $_arr_userData['user_nick'] = $this->inputSubmit['user_nick'];
        }

        if (isset($this->inputSubmit['user_note'])) {
            $_arr_userData['user_note'] = $this->inputSubmit['user_note'];
        }

        if (isset($this->inputSubmit['user_contact'])) {
            $_arr_userData['user_contact'] = $this->inputSubmit['user_contact'];
        }

        if (isset($this->inputSubmit['user_extend'])) {
            $_arr_userData['user_extend'] = $this->inputSubmit['user_extend'];
        }

        $_arr_userData['user_contact']    = Json::encode($_arr_userData['user_contact']);
        $_arr_userData['user_extend']     = Json::encode($_arr_userData['user_extend']);

        if ($this->inputSubmit['user_id'] > 0) {
            if (isset($this->inputSubmit['user_pass']) && !Func::isEmpty($this->inputSubmit['user_pass'])) {
                $_arr_userData['user_pass'] = $this->inputSubmit['user_pass']; //如果密码为空，则不修改
                $_arr_userData['user_rand'] = $this->inputSubmit['user_rand']; //如果密码为空，则不修改
            }
            $_num_userId    = $this->inputSubmit['user_id'];

            $_num_count     = $this->where('user_id', '=', $_num_userId)->update($_arr_userData); //更新数据

            if ($_num_count > 0) {
                $_str_rcode = 'y010103'; //更新成功
                $_str_msg   = 'Update user successfully';
            } else {
                $_str_rcode = 'x010103'; //更新失败
                $_str_msg   = 'Did not make any changes';
            }
        } else {
            $_arr_insert = array(
                'user_pass'         => $this->inputSubmit['user_pass'],
                'user_rand'         => $this->inputSubmit['user_rand'],
                'user_time'         => GK_NOW,
                'user_time_login'   => GK_NOW,
                'user_ip'           => $this->obj_request->ip(),
                //'user_app_id'       => $this->inputSubmit['user_app_id'],
            );
            $_arr_data      = array_replace_recursive($_arr_userData, $_arr_insert);

            $_num_userId    = $this->insert($_arr_data); //更新数据

            if ($_num_userId > 0) {
                $_str_rcode = 'y010101'; //更新成功
                $_str_msg   = 'Add user successfully';
            } else {
                $_str_rcode = 'x010101'; //更新失败
                $_str_msg   = 'Add user failed';
            }
        }

        return array(
            'user_id'   => $_num_userId,
            'rcode'     => $_str_rcode,
            'msg'       => $_str_msg,
        );
    }


    function pass($num_userId, $str_userPass, $str_userRand) {
        $_arr_userData = array(
            'user_rand'         => $str_userRand,
            'user_pass'         => $str_userPass,
        );

        if (!Func::isEmpty($_arr_userData)) {
            $_num_count     = $this->where('user_id', '=', $num_userId)->update($_arr_userData); //更新数据
        }

        if ($_num_count > 0) {
            $_str_rcode = 'y010103'; //更新成功
            $_str_msg   = 'Update password successfully';
        } else {
            $_str_rcode = 'x010103'; //更新成功
            $_str_msg   = 'Did not make any changes';
        }

        return array(
            'rcode' => $_str_rcode, //成功
            'msg'   => $_str_msg,
        );
    }


    function mailbox() {
        $_arr_userData = array(
            'user_mail' => $this->inputMailbox['user_mail_new'],
        );

        $_num_count     = $this->where('user_id', '=', $this->inputMailbox['user_id'])->update($_arr_userData); //更新数据

        if ($_num_count > 0) {
            $_str_rcode = 'y010103';
            $_str_msg   = 'Change mailbox successfully';
        } else {
            $_str_rcode = 'x010103';
            $_str_msg   = 'Did not make any changes';
        }

        return array(
            'rcode'     => $_str_rcode, //成功
            'msg'       => $_str_msg,
        );
    }


    function secqa() {
        $_arr_userData = array(
            'user_sec_ques' => $this->inputSecqa['user_sec_ques'],
            'user_sec_answ' => $this->inputSecqa['user_sec_answ'],
        );

        $_arr_userData['user_sec_ques'] = Json::encode($_arr_userData['user_sec_ques']);

        $_num_count     = $this->where('user_id', '=', $this->inputSecqa['user_id'])->update($_arr_userData); //更新数据

        if ($_num_count > 0) {
            $_str_rcode = 'y010103'; //更新成功
            $_str_msg   = 'Update security question successfully';
        } else {
            $_str_rcode = 'x010103'; //更新成功
            $_str_msg   = 'Did not make any changes';
        }

        return array(
            'rcode' => $_str_rcode, //成功
            'msg'   => $_str_msg,
        );
    }


    function read($mix_user, $str_by = 'user_id', $num_notId = 0, $arr_select = array()) {
        $_arr_userRow = $this->readProcess($mix_user, $str_by, $num_notId, $arr_select);

        if ($_arr_userRow['rcode'] != 'y010102') {
            return $_arr_userRow;
        }

        return $this->rowProcess($_arr_userRow);

    }


    function readProcess($mix_user, $str_by = 'user_id', $num_notId = 0, $arr_select = array()) {
        if (Func::isEmpty($arr_select)) {
            $arr_select = array(
                'user_id',
                'user_name',
                'user_pass',
                'user_rand',
                'user_mail',
                'user_contact',
                'user_extend',
                'user_nick',
                'user_note',
                'user_status',
                'user_time',
                'user_time_login',
                'user_ip',
                'user_access_token',
                'user_access_expire',
                'user_refresh_token',
                'user_refresh_expire',
                'user_app_id',
                'user_sec_ques',
                'user_sec_answ',
            );
        }

        $_arr_where = $this->readQueryProcess($mix_user, $str_by, $num_notId);

        $_arr_userRow = $this->where($_arr_where)->find($arr_select);

        if (!$_arr_userRow) {
            return array(
                'msg'   => 'User not found',
                'rcode' => 'x010102', //不存在记录
            );
        }

        $_arr_userRow['rcode'] = 'y010102';
        $_arr_userRow['msg']   = '';

        return $_arr_userRow;
    }


    /** 列出
     * mdl_list function.
     *
     * @access public
     * @param mixed $num_no
     * @param int $num_except (default: 0)
     * @param array $arr_search (default: array())
     * @return void
     */
    function lists($num_no, $num_except = 0, $arr_search = array()) {
        $_arr_userSelect = array(
            'user_id',
            'user_name',
            'user_mail',
            'user_nick',
            'user_note',
            'user_status',
            'user_time',
            'user_time_login',
            'user_ip',
        );

        $_arr_where    = $this->queryProcess($arr_search);

        $_arr_userRows = $this->where($_arr_where)->order('user_id', 'DESC')->limit($num_except, $num_no)->select($_arr_userSelect);

        return $_arr_userRows;
    }


    /** 计数
     * mdl_count function.
     *
     * @access public
     * @param array $arr_search (default: array())
     * @return void
     */
    function count($arr_search = array()) {
        $_arr_where     = $this->queryProcess($arr_search);

        $_num_userCount = $this->where($_arr_where)->count();

        return $_num_userCount;
    }


    /** 列出及统计 SQL 处理
     * queryProcess function.
     *
     * @param array $arr_search (default: array())
     * @return void
     */
    function queryProcess($arr_search = array()) {
        $_arr_where = array();

        if (isset($arr_search['key']) && !Func::isEmpty($arr_search['key'])) {
            $_arr_where[] = array('user_name|user_mail|user_note', 'LIKE', '%' . $arr_search['key'] . '%', 'key');
        }

        if (isset($arr_search['key_name']) && !Func::isEmpty($arr_search['key_name'])) {
            $_arr_where[] = array('user_name', 'LIKE', '%' . $arr_search['key_name'] . '%', 'key_name');
        }

        if (isset($arr_search['key_mail']) && !Func::isEmpty($arr_search['key_mail'])) {
            $_arr_where[] = array('user_mail', 'LIKE', '%' . $arr_search['key_mail'] . '%', 'key_mail');
        }

        if (isset($arr_search['min_id']) && $arr_search['min_id'] > 0) {
            $_arr_where[] = array('user_id', '>', $arr_search['min_id'], 'min_id');
        }

        if (isset($arr_search['max_id']) && $arr_search['max_id'] > 0) {
            $_arr_where[] = array('user_id', '<', $arr_search['max_id'], 'max_id');
        }

        if (isset($arr_search['begin_time']) && $arr_search['begin_time'] > 0) {
            $_arr_where[] = array('user_time', '>', $arr_search['begin_time'], 'begin_time');
        }

        if (isset($arr_search['end_time']) && $arr_search['end_time'] > 0) {
            $_arr_where[] = array('user_time', '<', $arr_search['end_time'], 'end_time');
        }

        if (isset($arr_search['begin_login']) && $arr_search['begin_login'] > 0) {
            $_arr_where[] = array('user_time_login', '>', $arr_search['begin_login'], 'begin_login');
        }

        if (isset($arr_search['end_login']) && $arr_search['end_login'] > 0) {
            $_arr_where[] = array('user_time_login', '<', $arr_search['end_login'], 'end_login');
        }

        if (isset($arr_search['status']) && !Func::isEmpty($arr_search['status'])) {
            $_arr_where[] = array('user_status', '=', $arr_search['status']);
        }

        if (isset($arr_search['user_mail']) && !Func::isEmpty($arr_search['user_mail'])) {
            $_arr_where[] = array('user_mail', '=', $arr_search['user_mail']);
        }

        if (isset($arr_search['user_names']) && !Func::isEmpty($arr_search['user_names'])) {
            $arr_search['user_names']    = Func::arrayFilter($arr_search['user_names']);
            $_arr_where[] = array('user_name', 'IN', $arr_search['user_names'], 'user_names');
        }

        if (isset($arr_search['not_in']) && !Func::isEmpty($arr_search['not_in'])) {
            $_arr_where[] = array('user_id', 'NOT IN', $arr_search['not_in']);
        }

        //print_r($_arr_where);

        return $_arr_where;
    }


    function readQueryProcess($mix_user, $str_by = 'user_id', $num_notId = 0) {
        $_arr_where[] = array($str_by, '=', $mix_user);

        if ($num_notId > 0) {
            $_arr_where[] = array('user_id', '<>', $num_notId);
        }

        return $_arr_where;
    }


    protected function rowProcess($arr_userRow = array()) {
        if (isset($arr_userRow['user_contact'])) {
            $arr_userRow['user_contact']   = Json::decode($arr_userRow['user_contact']);
        } else {
            $arr_userRow['user_contact']   = array();
        }

        if (isset($arr_userRow['user_extend'])) {
            $arr_userRow['user_extend']    = Json::decode($arr_userRow['user_extend']);
        } else {
            $arr_userRow['user_extend']    = array();
        }

        if (isset($arr_userRow['user_sec_ques'])) {
            $arr_userRow['user_sec_ques']  = Json::decode($arr_userRow['user_sec_ques']);
        } else {
            $arr_userRow['user_sec_ques']  = array();
        }

        $_num_countSecqa = Config::get('count_secqa', 'var_default');

        for ($_iii = 1; $_iii <= $_num_countSecqa; $_iii++) {
            if (!isset($arr_userRow['user_sec_ques'][$_iii])) {
                $arr_userRow['user_sec_ques'][$_iii] = '';
            }
        }

        return $arr_userRow;
    }
}
