<?php
/*-----------------------------------------------------------------
！！！！警告！！！！
以下为系统文件，请勿修改
-----------------------------------------------------------------*/

namespace app\model\install;

use app\classes\install\Model;
use ginkgo\Loader;
use ginkgo\Func;

// 不能非法包含或直接执行
defined('IN_GINKGO') or exit('Access denied');

/*-------------同步组模型-------------*/
class Combine extends Model {

    private $create;

    function m_init() { //构造函数
        $this->create = array(
            'combine_id' => array(
                'type'      => 'smallint(6)',
                'not_null'  => true,
                'ai'        => true,
                'comment'   => 'ID',
            ),
            'combine_name' => array(
                'type'      => 'varchar(30)',
                'not_null'  => true,
                'default'   => '',
                'comment'   => '同步组名',
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
        $_num_count  = $this->create($this->create, 'combine_id', '同步组');

        if ($_num_count !== false) {
            $_str_rcode = 'y040105'; //更新成功
            $_str_msg   = 'Create table successfully';
        } else {
            $_str_rcode = 'x040105'; //更新成功
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

        $_str_rcode = 'y040111';
        $_str_msg   = 'No need to update table';

        if (!Func::isEmpty($_arr_alter)) {
            $_num_count = $this->alter($_arr_alter);

            if ($_num_count !== false) {
                $_str_rcode = 'y040106';
                $_str_msg   = 'Update table successfully';

                foreach ($this->create as $_key=>$_value) {
                    if (isset($_value['update'])) {
                        $_arr_data = array(
                            $_key => $_value['update'],
                        );
                        $this->where('LENGTH(`' . $_key . '`) < 1')->update($_arr_data);
                    }
                }
            } else {
                $_str_rcode = 'x040106';
                $_str_msg   = 'Update table failed';
            }
        }

        return array(
            'rcode' => $_str_rcode,
            'msg'   => $_str_msg,
        );
    }
}
