<?php
/*-----------------------------------------------------------------
！！！！警告！！！！
以下为系统文件，请勿修改
-----------------------------------------------------------------*/

namespace app\model\console;

use app\model\Combine as Combine_Base;
use ginkgo\Func;
use ginkgo\Json;
use ginkgo\Arrays;

// 不能非法包含或直接执行
if (!defined('IN_GINKGO')) {
    return 'Access denied';
}

/*-------------应用模型-------------*/
class Combine extends Combine_Base {

    public $inputSubmit;
    public $inputDelete;


    /** 提交
     * submit function.
     *
     * @access public
     * @return void
     */
    function submit() {
        $_arr_data = array(
            'combine_name'          => $this->inputSubmit['combine_name'],
        );

        $_mix_vld = $this->validate($_arr_data, '', 'submit_db');

        if ($_mix_vld !== true) {
            return array(
                'combine_id'        => $this->inputSubmit['combine_id'],
                'rcode'         => 'x040201',
                'msg'           => end($_mix_vld),
            );
        }

        if ($this->inputSubmit['combine_id'] > 0) {
            $_num_combineId     = $this->inputSubmit['combine_id'];
            $_num_count     = $this->where('combine_id', '=', $_num_combineId)->update($_arr_data);

            if ($_num_count > 0) {
                $_str_rcode = 'y040103'; //更新成功
                $_str_msg   = 'Update combine successfully';
            } else {
                $_str_rcode = 'x040103'; //更新失败
                $_str_msg   = 'Did not make any changes';
            }
        } else {
            $_num_combineId     = $this->insert($_arr_data);

            if ($_num_combineId > 0) {
                $_str_rcode = 'y040101'; //更新成功
                $_str_msg   = 'Add combine successfully';
            } else {
                $_str_rcode = 'x040101'; //更新失败
                $_str_msg   = 'Add combine failed';
            }
        }

        return array(
            'combine_id'        => $_num_combineId,
            'rcode'         => $_str_rcode, //成功
            'msg'           => $_str_msg,
        );
    }


    /** 删除
     * delete function.
     *
     * @access public
     * @return void
     */
    function delete() {
        $_num_count     = $this->where('combine_id', 'IN', $this->inputDelete['combine_ids'], 'combine_ids')->delete(); //更新数据

        //如车影响行数小于0则返回错误
        if ($_num_count > 0) {
            $_str_rcode = 'y040104'; //成功
            $_str_msg   = 'Successfully deleted {:count} combines';
        } else {
            $_str_rcode = 'x040104'; //失败
            $_str_msg   = 'No combine have been deleted';
        }

        return array(
            'count' => $_num_count,
            'rcode' => $_str_rcode,
            'msg'   => $_str_msg,
        );
    }


    /** 表单验证
     * inputSubmit function.
     *
     * @access public
     * @return void
     */
    function inputSubmit() {
        $_arr_inputParam = array(
            'combine_id'    => array('int', 0),
            'combine_name'  => array('txt', ''),
            '__token__'     => array('txt', ''),
        );

        $_arr_inputSubmit = $this->obj_request->post($_arr_inputParam);

        $_mix_vld = $this->validate($_arr_inputSubmit, '', 'submit');

        if ($_mix_vld !== true) {
            return array(
                'rcode' => 'x040201',
                'msg'   => end($_mix_vld),
            );
        }

        if ($_arr_inputSubmit['combine_id'] > 0) {
            $_arr_combineRow = $this->check($_arr_inputSubmit['combine_id']);

            if ($_arr_combineRow['rcode'] != 'y040102') {
                return $_arr_combineRow;
            }
        }

        $_arr_inputSubmit['rcode'] = 'y040201';

        $this->inputSubmit = $_arr_inputSubmit;

        return $_arr_inputSubmit;
    }


    /** 选择
     * inputStatus function.
     *
     * @access public
     * @return void
     */
    function inputDelete() {
        $_arr_inputParam = array(
            'combine_ids'   => array('arr', array()),
            '__token__' => array('txt', ''),
        );

        $_arr_inputDelete = $this->obj_request->post($_arr_inputParam);

        $_arr_inputDelete['combine_ids'] = Arrays::filter($_arr_inputDelete['combine_ids']);

        $_mix_vld = $this->validate($_arr_inputDelete, '', 'delete');

        if ($_mix_vld !== true) {
            return array(
                'rcode' => 'x040201',
                'msg'   => end($_mix_vld),
            );
        }

        $_arr_inputDelete['rcode'] = 'y040201';

        $this->inputDelete = $_arr_inputDelete;

        return $_arr_inputDelete;
    }
}
