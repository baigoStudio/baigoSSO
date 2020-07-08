<?php
/*-----------------------------------------------------------------
！！！！警告！！！！
以下为系统文件，请勿修改
-----------------------------------------------------------------*/
namespace app\model;

use ginkgo\Func;
use ginkgo\Config;

// 不能非法包含或直接执行
defined('IN_GINKGO') or exit('Access denied');

/*-------------短消息模型-------------*/
class Pm extends User_Common {

    public $arr_status  = array('wait', 'read'); //状态
    public $arr_type    = array('in', 'out'); //类型

    function m_init() { //构造函数
        $this->configBase = Config::get('base', 'var_extra');
    }


    /** 删除
     * delete function.
     *
     * @access public
     * @return void
     */
    function delete() {
        $_arr_where[] = array('pm_id', 'IN', $this->inputDelete['pm_ids'], 'pm_ids');

        if (isset($this->inputDelete['user_id']) && $this->inputDelete['user_id'] > 0) {
            $_arr_where[] = array('pm_from|pm_to', '=', $this->inputDelete['user_id'], 'user_id');
        }

        if (isset($this->inputDelete['pm_from']) && $this->inputDelete['pm_from'] > 0) {
            $_arr_where[] = array('pm_from', '=', $this->inputDelete['pm_from']);
        }

        if (isset($this->inputDelete['pm_to']) && $this->inputDelete['pm_to'] > 0) {
            $_arr_where[] = array('pm_to', '=', $this->inputDelete['pm_to']);
        }

        if (isset($this->inputDelete['pm_type']) && !Func::isEmpty($this->inputDelete['pm_type'])) {
            $_arr_where[] = array('pm_type', '=', $this->inputDelete['pm_type']);
        }

        if (isset($this->inputDelete['pm_status']) && !Func::isEmpty($this->inputDelete['pm_status'])) {
            $_arr_where[] = array('pm_status', '=', $this->inputDelete['pm_status']);
        }

        $_num_count = $this->where($_arr_where)->delete(); //更新数据

        //如车影响行数小于0则返回错误
        if ($_num_count > 0) {
            $_str_rcode = 'y110104'; //成功

            if (isset($this->inputDelete['pm_from']) && $this->inputDelete['pm_from'] > 0) {
                $_str_msg   = 'Successfully revoked {:count} messages';
            } else {
                $_str_msg   = 'Successfully deleted {:count} messages';
            }
        } else {
            $_str_rcode = 'x110104'; //失败

            if (isset($this->inputDelete['pm_from']) && $this->inputDelete['pm_from'] > 0) {
                $_str_msg   = 'No message have been revoked';
            } else {
                $_str_msg   = 'No message have been deleted';
            }
        }

        return array(
            'count' => $_num_count,
            'rcode' => $_str_rcode,
            'msg'   => $_str_msg,
        );
    }


    /** 编辑状态
     * status function.
     *
     * @access public
     * @param mixed $str_status
     * @return void
     */
    function status() {
        $_arr_pmUpdate = array(
            'pm_status' => $this->inputStatus['act'],
        );

        $_arr_where[] = array('pm_id', 'IN', $this->inputStatus['pm_ids'], 'pm_ids');

        if (isset($this->inputStatus['pm_to']) && $this->inputStatus['pm_to'] > 0) {
            $_arr_where[] = array('pm_to', '=', $this->inputStatus['pm_to']);
        }

        if (isset($this->inputStatus['pm_type']) && !Func::isEmpty($this->inputStatus['pm_type'])) {
            $_arr_where[] = array('pm_type', '=', $this->inputStatus['pm_type']);
        }

        $_num_count   = $this->where($_arr_where)->update($_arr_pmUpdate); //更新数据

        //如影响行数大于0则返回成功
        if ($_num_count > 0) {
            $_str_rcode = 'y110103'; //成功
            $_str_msg   = 'Successfully updated {:count} messages';
        } else {
            $_str_rcode = 'x110103'; //失败
            $_str_msg   = 'Did not make any changes';
        }

        return array(
            'count' => $_num_count,
            'rcode' => $_str_rcode,
            'msg'   => $_str_msg,
        );
    }


    /** 读取
     * read function.
     *
     * @access public
     * @param mixed $mix_pm
     * @param string $str_by (default: 'pm_id')
     * @param int $num_notId (default: 0)
     * @return void
     */
    function read($mix_pm, $str_by = 'pm_id', $num_notId = 0) {
        $_arr_pmRow = $this->readProcess($mix_pm, $str_by, $num_notId);

        if ($_arr_pmRow['rcode'] != 'y020102') {
            return $_arr_pmRow;
        }

        return $this->rowProcess($_arr_pmRow);

    }


    function readProcess($mix_pm, $str_by = 'pm_id', $num_notId = 0) {
        $_arr_pmSelect = array(
            'pm_id',
            'pm_send_id',
            'pm_to',
            'pm_from',
            'pm_title',
            'pm_content',
            'pm_type',
            'pm_status',
            'pm_time',
        );

        $_arr_where = $this->readQueryProcess($mix_pm, $str_by, $num_notId);

        $_arr_pmRow = $this->where($_arr_where)->find($_arr_pmSelect);

        if (!$_arr_pmRow) {
            return array(
                'msg'   => 'Message not found',
                'rcode' => 'x110102', //不存在记录
            );
        }

        $_arr_pmRow['rcode']   = 'y110102';
        $_arr_pmRow['msg']     = '';

        return $_arr_pmRow;

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
        $_arr_pmSelect = array(
            'pm_id',
            'pm_send_id',
            'pm_to',
            'pm_from',
            'pm_title',
            'pm_type',
            'pm_status',
            'pm_time',
        );

        $_arr_where  = $this->queryProcess($arr_search);

        $_arr_pmRows = $this->where($_arr_where)->order('pm_id', 'DESC')->limit($num_except, $num_no)->select($_arr_pmSelect);

        foreach ($_arr_pmRows as $_key=>$_value) {
            $_arr_pmRows[$_key] = $this->rowProcess($_value);
        }

        return $_arr_pmRows;
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

        $_num_pmCount = $this->where($_arr_where)->count();

        return $_num_pmCount;
    }

    /** 列出及统计 SQL 处理
     * queryProcess function.
     *
     * @access private
     * @param array $arr_search (default: array())
     * @return void
     */
    protected function queryProcess($arr_search = array()) {
        $_arr_where = array();

        if (isset($arr_search['key']) && !Func::isEmpty($arr_search['key'])) {
            $_arr_where[] = array('pm_title|pm_content', 'LIKE', '%' . $arr_search['key'] . '%', 'key');
        }

        if (isset($arr_search['status']) && !Func::isEmpty($arr_search['status'])) {
            $_arr_where[] = array('pm_status', '=', $arr_search['status']);
        }

        if (isset($arr_search['type']) && !Func::isEmpty($arr_search['type'])) {
            $_arr_where[] = array('pm_type', '=', $arr_search['type']);
        }

        if (isset($arr_search['from']) && $arr_search['from'] > 0) {
            $_arr_where[] = array('pm_from', '=', $arr_search['from']);
        }

        if (isset($arr_search['to']) && $arr_search['to'] > 0) {
            $_arr_where[] = array('pm_to', '=', $arr_search['to']);
        }

        if (isset($arr_search['ids']) && !Func::isEmpty($arr_search['ids'])) {
            $arr_search['ids'] = Func::arrayFilter($arr_search['ids']);

            $_arr_where[] = array('pm_id', 'IN', $arr_search['ids'], 'ids');
        }

        return $_arr_where;
    }


    function readQueryProcess($mix_pm, $str_by = 'pm_id', $num_notId = 0) {
        $_arr_where[] = array($str_by, '=', $mix_pm);

        if ($num_notId > 0) {
            $_arr_where[] = array('pm_id', '<>', $num_notId);
        }

        return $_arr_where;
    }


    protected function rowProcess($arr_pmRow = array()) {
        if (!isset($arr_pmRow['pm_time'])) {
            $arr_pmRow['pm_time'] = GK_NOW;
        }

        $arr_pmRow['pm_time_format'] = $this->dateFormat($arr_pmRow['pm_time']);

        return $arr_pmRow;
    }
}
