<?php
/*-----------------------------------------------------------------
！！！！警告！！！！
以下为系统文件，请勿修改
-----------------------------------------------------------------*/

namespace app\model\console;

use ginkgo\Func;

//不能非法包含或直接执行
defined('IN_GINKGO') or exit('Access denied');

/*-------------用户模型-------------*/
class User_App_View extends User {

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

        if (isset($arr_search['app_id']) && $arr_search['app_id'] > 0) {
            $_arr_where[] = array('belong_app_id', '=', $arr_search['app_id']);
        }

        return $_arr_where;
    }
}
