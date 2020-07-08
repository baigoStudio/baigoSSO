<?php
/*-----------------------------------------------------------------
！！！！警告！！！！
以下为系统文件，请勿修改
-----------------------------------------------------------------*/

namespace app\model\install;

use app\classes\install\Model;
use ginkgo\Func;

// 不能非法包含或直接执行
defined('IN_GINKGO') or exit('Access denied');

/*-------------同步组归属-------------*/
class Combine_Belong extends Model {

    private $create;

    function m_init() { //构造函数
        $this->create = array(
            'belong_id' => array(
                'type'      => 'int(11)',
                'not_null'  => true,
                'ai'        => true,
                'comment'   => 'ID',
            ),
            'belong_app_id' => array(
                'type'      => 'smallint(6)',
                'not_null'  => true,
                'default'   => 0,
                'comment'   => '应用ID',
            ),
            'belong_combine_id' => array(
                'type'      => 'smallint(6)',
                'not_null'  => true,
                'default'   => 0,
                'comment'   => '同步组ID',
            ),
        );
    }


    /** 创建表
     * mdl_create function.
     *
     * @access public
     * @return void
     */
    function createTable() {
        $_num_count  = $this->create($this->create, 'belong_id', '同步组从属');

        if ($_num_count !== false) {
            $_str_rcode = 'y060105'; //更新成功
            $_str_msg   = 'Create table successfully';
        } else {
            $_str_rcode = 'x060105'; //更新成功
            $_str_msg   = 'Create table failed';
        }

        return array(
            'rcode' => $_str_rcode, //更新成功
            'msg'   => $_str_msg,
        );
    }


    /** 修改表
     * alterTable function.
     *
     * @access public
     * @return void
     */
    function alterTable() {
        $_arr_alter = $this->alterProcess($this->create);

        $_str_rcode = 'y060111';
        $_str_msg   = 'No need to update table';

        if (!Func::isEmpty($_arr_alter)) {
            $_num_count  = $this->alter($_arr_alter);

            if ($_num_count !== false) {
                $_str_rcode = 'y060106';
                $_str_msg   = 'Update table successfully';
            } else {
                $_str_rcode = 'x060106';
                $_str_msg   = 'Update table failed';
            }
        }

        return array(
            'rcode' => $_str_rcode,
            'msg'   => $_str_msg,
        );
    }
}
