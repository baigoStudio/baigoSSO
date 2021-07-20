<?php
/*-----------------------------------------------------------------
！！！！警告！！！！
以下为系统文件，请勿修改
-----------------------------------------------------------------*/

namespace app\model;

use app\classes\Model;
use ginkgo\Func;
use ginkgo\Json;
use ginkgo\Html;
use ginkgo\Arrays;

// 不能非法包含或直接执行
if (!defined('IN_GINKGO')) {
    return 'Access denied';
}

/*-------------应用模型-------------*/
class Combine extends Model {

    function check($mix_combine, $str_by = 'combine_id', $num_notId = 0) {
        $_arr_select = array(
            'combine_id',
        );

        return $this->read($mix_combine, $str_by, $num_notId, $_arr_select);
    }


    function read($mix_combine, $str_by = 'combine_id', $num_notId = 0, $arr_select = array()) {
        if (Func::isEmpty($arr_select)) {
            $arr_select = array(
                'combine_id',
                'combine_name',
            );
        }

        $_arr_where = $this->readQueryProcess($mix_combine, $str_by, $num_notId);

        $_arr_combineRow = $this->where($_arr_where)->find($arr_select);

        if (!$_arr_combineRow) {
            return array(
                'msg'   => 'Combine not found',
                'rcode' => 'x040102', //不存在记录
            );
        }

        $_arr_combineRow['rcode'] = 'y040102';
        $_arr_combineRow['msg']   = '';

        return $_arr_combineRow;
    }


    /** 列出
     * mdl_list function.
     *
     * @access public
     * @param mixed $num_no
     * @param int $num_offset (default: 0)
     * @param array $arr_search (default: array())
     * @return void
     */
    function lists($num_no, $num_offset = 0, $arr_search = array()) {
        $_arr_combineSelect = array(
            'combine_id',
            'combine_name',
        );

        $_arr_where = $this->queryProcess($arr_search);

        $_arr_combineRows = $this->where($_arr_where)->order('combine_id', 'DESC')->limit($num_offset, $num_no)->select($_arr_combineSelect);

        return $_arr_combineRows;
    }


    /** 计数
     * mdl_count function.
     *
     * @access public
     * @param array $arr_search (default: array())
     * @return void
     */
    function count($arr_search = array()) {
        $_arr_where = $this->queryProcess($arr_search);

        $_num_combineCount = $this->where($_arr_where)->count();

        return $_num_combineCount;
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
            $_arr_where[] = array('combine_name', 'LIKE', '%' . $arr_search['key'] . '%', 'key');
        }

        if (isset($arr_search['not_ids']) && !Func::isEmpty($arr_search['not_ids'])) {
            $arr_search['not_ids'] = Arrays::filter($arr_search['not_ids']);
            $_arr_where[] = array('combine_id', 'NOT IN', $arr_search['not_ids'], 'not_ids');
        }

        return $_arr_where;
    }


    function readQueryProcess($mix_combine, $str_by = 'combine_id', $num_notId = 0) {
        $_arr_where[] = array($str_by, '=', $mix_combine);

        if ($num_notId > 0) {
            $_arr_where[] = array('combine_id', '<>', $num_notId);
        }

        return $_arr_where;
    }
}
