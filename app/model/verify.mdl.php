<?php
/*-----------------------------------------------------------------
！！！！警告！！！！
以下为系统文件，请勿修改
-----------------------------------------------------------------*/
namespace app\model;

use app\classes\Model;
use ginkgo\Func;
use ginkgo\Crypt;
use ginkgo\Config;

//不能非法包含或直接执行
defined('IN_GINKGO') or exit('Access denied');

/*-------------验证模型-------------*/
class Verify extends Model {

    public $arr_status  = array('enable', 'disabled');
    public $arr_type    = array('mailbox', 'confirm', 'forgot');
    public $configBase;

    function m_init() { //构造函数
        $this->configBase   = Config::get('base', 'var_extra');
    }


    function check($mix_verify, $str_by = 'verify_id') {
        $_arr_select = array(
            'verify_id',
        );

        $_arr_verifyRow = $this->read($mix_verify, $str_by, $_arr_select);

        return $_arr_verifyRow;
    }


    /** 提交
     * submit function.
     *
     * @access public
     * @return void
     */
    function submit($num_userId, $str_mail, $str_type) {
        $_arr_verifyRow = $this->check($num_userId, 'verify_user_id');

        $_str_rand      = Func::rand();
        $_str_token     = Func::rand();
        $_str_tokenDo   = Crypt::crypt($_str_token, $_str_rand);

        $_arr_verifyData = array(
            'verify_user_id'        => $num_userId,
            'verify_mail'           => $str_mail,
            'verify_type'           => $str_type,
            'verify_token'          => $_str_token,
            'verify_rand'           => $_str_rand,
            'verify_token_expire'   => GK_NOW + $this->configBase['verify_expire'] * GK_MINUTE,
            'verify_status'         => 'enable',
            'verify_time_refresh'   => GK_NOW,
        );

        if ($_arr_verifyRow['rcode'] == 'x120102') {
            $_arr_verifyData['verify_time'] = GK_NOW;

            $_num_verifyId  = $this->insert($_arr_verifyData);

            if ($_num_verifyId > 0) {
                $_str_rcode = 'y120101'; //更新成功
                $_str_msg   = 'Add token successfully';
            } else {
                $_str_rcode = 'x120101'; //更新失败
                $_str_msg   = 'Add token failed';
            }
        } else {
            $_num_verifyId  = $_arr_verifyRow['verify_id'];

            $_num_count     = $this->where('verify_id', '=', $_num_verifyId)->update($_arr_verifyData);

            if ($_num_count > 0) {
                $_str_rcode = 'y120103'; //更新成功
                $_str_msg   = 'Update token successfully';
            } else {
                $_str_rcode = 'x120103';
                $_str_msg   = 'Did not make any changes';
            }
        }

        return array(
            'verify_id'     => $_num_verifyId,
            'verify_token'  => $_str_tokenDo,
            'msg'           => $_str_msg,
            'rcode'         => $_str_rcode, //成功
        );
    }


    /** 读取
     * read function.
     *
     * @access public
     * @param mixed $mix_verify
     * @param string $str_by (default: 'verify_id')
     * @param int $num_notId (default: 0)
     * @return void
     */
    function read($mix_verify, $str_by = 'verify_id', $arr_select = array()) {
        if (Func::isEmpty($arr_select)) {
            $arr_select = array(
                'verify_id',
                'verify_user_id',
                'verify_token',
                'verify_token_expire',
                'verify_mail',
                'verify_status',
                'verify_type',
                'verify_rand',
                'verify_time',
                'verify_time_refresh',
                'verify_time_disabled',
            );
        }

        $_arr_where = $this->readQueryProcess($mix_verify, $str_by);

        $_arr_verifyRow = $this->where($_arr_where)->find($arr_select);

        if (!$_arr_verifyRow) {
            return array(
                'msg'   => 'Token not found',
                'rcode' => 'x120102', //不存在记录
            );
        }

        $_arr_verifyRow['rcode'] = 'y120102';
        $_arr_verifyRow['msg']   = '';

        return $this->rowProcess($_arr_verifyRow);
    }


    /** 列出
     * mdl_list function.
     *
     * @access public
     * @param mixed $num_no
     * @param int $num_except (default: 0)
     * @return void
     */
    function lists($num_no, $num_except = 0) {
        $_arr_verifySelect = array(
            'verify_id',
            'verify_user_id',
            'verify_token',
            'verify_token_expire',
            'verify_mail',
            'verify_status',
            'verify_type',
            'verify_time',
            'verify_time_refresh',
            'verify_time_disabled',
        );

        $_arr_verifyRows = $this->order('verify_id', 'DESC')->limit($num_except, $num_no)->select($_arr_verifySelect);

        foreach ($_arr_verifyRows as $_key=>$_value) {
            $_arr_verifyRows[$_key] = $this->rowProcess($_value);
        }

        return $_arr_verifyRows;
    }


    /** 计数
     * mdl_count function.
     *
     * @access public
     * @return void
     */
    function count() {
        $_num_verifyCount = $this->where(false)->count();

        return $_num_verifyCount;
    }


    function readQueryProcess($mix_verify, $str_by = 'verify_id') {
        $_arr_where[] = array($str_by, '=', $mix_verify);

        return $_arr_where;
    }


    protected function rowProcess($arr_verifyRow = array()) {
        if (isset($arr_verifyRow['verify_token_expire'])) {
            if ($arr_verifyRow['verify_token_expire'] < GK_NOW) {
                $arr_verifyRow['verify_status'] = 'expired';
            }
        }

        if (!isset($arr_verifyRow['verify_time_refresh'])) {
            $arr_verifyRow['verify_time_refresh'] = GK_NOW;
        }

        if (!isset($arr_verifyRow['verify_token_expire'])) {
            $arr_verifyRow['verify_token_expire'] = GK_NOW;
        }

        if (!isset($arr_verifyRow['verify_time_disabled'])) {
            $arr_verifyRow['verify_time_disabled'] = GK_NOW;
        }

        $arr_verifyRow['verify_time_refresh_format']       = $this->dateFormat($arr_verifyRow['verify_time_refresh']);
        $arr_verifyRow['verify_time_expire_format']        = $this->dateFormat($arr_verifyRow['verify_token_expire']);
        $arr_verifyRow['verify_time_disabled_format']      = $this->dateFormat($arr_verifyRow['verify_time_disabled']);

        return $arr_verifyRow;
    }
}
