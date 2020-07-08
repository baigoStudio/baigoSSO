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

/*-------------验证模型-------------*/
class Verify extends Model {

    private $create;

    function m_init() { //构造函数
        $_mdl_verify = Loader::model('Verify', '', false);
        $this->arr_status = $_mdl_verify->arr_status;
        $this->arr_type   = $_mdl_verify->arr_type;

        $_str_status   = implode('\',\'', $this->arr_status);
        $_str_type     = implode('\',\'', $this->arr_type);

        $this->create = array(
            'verify_id' => array(
                'type'      => 'int(11)',
                'not_null'  => true,
                'ai'        => true,
                'comment'   => 'ID',
            ),
            'verify_user_id' => array(
                'type'      => 'int(11)',
                'not_null'  => true,
                'default'   => 0,
                'comment'   => '用户 ID',
            ),
            'verify_token' => array(
                'type'      => 'char(32)',
                'not_null'  => true,
                'default'   => '',
                'comment'   => '访问口令',
            ),
            'verify_token_expire' => array(
                'type'      => 'int(11)',
                'not_null'  => true,
                'default'   => 0,
                'comment'   => '口令过期时间',
            ),
            'verify_rand' => array(
                'type'      => 'char(32)',
                'not_null'  => true,
                'default'   => '',
                'comment'   => '随机串',
            ),
            'verify_mail' => array(
                'type'      => 'varchar(300)',
                'not_null'  => true,
                'default'   => '',
                'comment'   => '待验证邮箱',
            ),
            'verify_status' => array(
                'type'      => 'enum(\'' . $_str_status . '\')',
                'not_null'  => true,
                'default'   => $this->arr_status[0],
                'comment'   => '状态',
                'update'    => $this->arr_status[0],
            ),
            'verify_type' => array(
                'type'      => 'enum(\'' . $_str_type . '\')',
                'not_null'  => true,
                'default'   => $this->arr_type[0],
                'comment'   => '类型',
                'update'    => $this->arr_type[0],
            ),
            'verify_time' => array(
                'type'      => 'int(11)',
                'not_null'  => true,
                'default'   => 0,
                'comment'   => '发起时间',
            ),
            'verify_time_refresh' => array(
                'type'      => 'int(11)',
                'not_null'  => true,
                'default'   => 0,
                'comment'   => '更新时间',
            ),
            'verify_time_disabled' => array(
                'type'      => 'int(11)',
                'not_null'  => true,
                'default'   => 0,
                'comment'   => '使用时间',
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
        $_num_count  = $this->create($this->create, 'verify_id', '验证');

        if ($_num_count !== false) {
            $_str_rcode = 'y120105'; //更新成功
            $_str_msg   = 'Create table successfully';
        } else {
            $_str_rcode = 'x120105'; //更新成功
            $_str_msg   = 'Create table failed';
        }

        return array(
            'rcode' => $_str_rcode, //更新成功
            'msg'   => $_str_msg,
        );
    }


    function alterTable() {
        $_arr_alter = $this->alterProcess($this->create);

        $_str_rcode = 'y120111';
        $_str_msg   = 'No need to update table';

        if (!Func::isEmpty($_arr_alter)) {
            $_num_count = $this->alter($_arr_alter);

            if ($_num_count !== false) {
                $_str_rcode = 'y120106';
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
                $_str_rcode = 'x120106';
                $_str_msg   = 'Update table failed';
            }
        }

        return array(
            'rcode' => $_str_rcode,
            'msg'   => $_str_msg,
        );
    }
}
