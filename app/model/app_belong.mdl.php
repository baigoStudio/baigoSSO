<?php
/*-----------------------------------------------------------------
！！！！警告！！！！
以下为系统文件，请勿修改
-----------------------------------------------------------------*/
namespace app\model;

use app\classes\Model;
use ginkgo\Func;

// 不能非法包含或直接执行
defined('IN_GINKGO') or exit('Access denied');

/*-------------应用归属-------------*/
class App_Belong extends Model {

    /** 读取
     * read function.
     *
     * @access public
     * @param int $num_userId (default: 0)
     * @param int $num_appId (default: 0)
     * @return void
     */
    function read($num_appId = 0, $num_userId = 0) {
        $_arr_belongSelect = array(
            'belong_id',
            'belong_app_id',
            'belong_user_id',
        );

        $_arr_where = $this->readQueryProcess($num_appId, $num_userId);

        $_arr_belongRow = $this->where($_arr_where)->find($_arr_belongSelect);

        if (!$_arr_belongRow) {
            return array(
                'rcode' => 'x070102', //不存在记录
            );
        }

        $_arr_belongRow['rcode'] = 'y070102';

        return $_arr_belongRow;
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
        $_arr_belongSelect = array(
            'belong_id',
            'belong_app_id',
            'belong_user_id',
        );

        $_arr_query = $this->queryProcess($arr_search);

        $_arr_belongRows = $this->where($_arr_query)->order('belong_id', 'DESC')->limit($num_except, $num_no)->select($_arr_belongSelect);

        return $_arr_belongRows;
    }


    /** 计数
     * mdl_count function.
     *
     * @access public
     * @param array $arr_search (default: array())
     * @return void
     */
    function count($arr_search = array()) {

        $_arr_query = $this->queryProcess($arr_search);

        $_num_belongCount = $this->where($_arr_query)->count();

        return $_num_belongCount;
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

        if (isset($arr_search['app_id']) && $arr_search['app_id'] > 0) {
            $_arr_where[] = array('belong_app_id', '=', $arr_search['app_id']);
        }

        if (isset($arr_search['user_id']) && $arr_search['user_id'] > 0) {
            $_arr_where[] = array('belong_user_id', '=', $arr_search['user_id']);
        }

        if (isset($arr_search['user_ids']) && !Func::isEmpty($arr_search['user_ids'])) {
            $arr_search['user_ids'] = Func::arrayFilter($arr_search['user_ids'], 'user_ids');

            $_arr_where[] = array('belong_user_id', 'IN', $arr_search['user_ids'], 'user_ids');
        }

        if (isset($arr_search['min_id']) && $arr_search['min_id'] > 0) {
            $_arr_where[] = array('belong_id', ' >', $arr_search['min_id'], 'min_id');
        }

        if (isset($arr_search['max_id']) && $arr_search['max_id'] > 0) {
            $_arr_where[] = array('belong_id', '<', $arr_search['max_id'], 'max_id');
        }

        return $_arr_where;
    }


    function readQueryProcess($num_appId = 0, $num_userId = 0) {
        $_arr_where[] = array('belong_app_id', '=', $num_appId);

        if ($num_userId > 0) {
            $_arr_where[] = array('belong_user_id', '=', $num_userId);
        }

        return $_arr_where;
    }
}
