<?php
/*-----------------------------------------------------------------
！！！！警告！！！！
以下为系统文件，请勿修改
-----------------------------------------------------------------*/
namespace app\model\console;

use app\model\App_Belong as App_Belong_Base;
use ginkgo\Func;

// 不能非法包含或直接执行
defined('IN_GINKGO') or exit('Access denied');

/*-------------应用归属-------------*/
class App_Belong extends App_Belong_Base {

    /** 提交
     * submit function.
     *
     * @access public
     * @param mixed $num_userId
     * @param mixed $num_appId
     * @return void
     */
    function submit() {
        $_num_count         = 0;
        $_num_countGlobal   = 0;

        foreach ($this->inputSubmit['user_ids'] as $_key=>$_value) {
            if ($_value > 0 && $this->inputSubmit['app_id'] > 0) { //插入
                $_arr_belongRow = $this->read($this->inputSubmit['app_id'], $_value); //是否重复

                if ($_arr_belongRow['rcode'] == 'x070102') { //不存在
                    $_arr_belongData = array(
                        'belong_user_id' => $_value,
                        'belong_app_id'  => $this->inputSubmit['app_id'],
                    );

                    //print_r($_arr_belongData);

                    $_arr_belongRowSub = $this->read(0, $_value); //是否有闲置数据

                    if ($_arr_belongRowSub['rcode'] == 'y070102') {
                        $_num_count     = $this->where('belong_id', '=', $_arr_belongRowSub['belong_id'])->update($_arr_belongData);

                        if ($_num_count > 0) {
                            $_num_countGlobal = $_num_countGlobal + $_num_count;
                        }
                    } else {
                        $_num_belongId  = $this->insert($_arr_belongData);

                        if ($_num_belongId > 0) {
                            ++$_num_countGlobal;
                        }
                    }
                }
            }
        }

        if ($_num_countGlobal > 0) {
            $_str_rcode = 'y070103';
            $_str_msg   = 'Successfully authorized {:count} users';
        } else {
            $_str_rcode = 'x070103';
            $_str_msg   = 'Did not make any changes';
        }

        return array(
            'msg'    => $_str_msg,
            'count'  => $_num_countGlobal,
            'rcode'  => $_str_rcode,
        );
    }


    function remove() {
        $_num_count         = 0;
        $_num_countGlobal   = 0;

        foreach ($this->inputRemove['user_ids_belong'] as $_key=>$_value) {
            if ($_value > 0 && $this->inputRemove['app_id'] > 0) { //插入
                $_arr_belongRow = $this->read($this->inputRemove['app_id'], $_value); //是否存在

                /*print_r($_arr_belongRow);
                print_r(PHP_EOL);*/

                if ($_arr_belongRow['rcode'] == 'y070102') { //存在
                    $_arr_belongData = array(
                        'belong_app_id'  => 0,
                    );

                    $_num_count = $this->delete(0, 0, false, false, false, false, $_arr_belongRow['belong_id']); //作为闲置数据

                    if ($_num_count > 0) {
                        $_num_countGlobal = $_num_countGlobal + $_num_count;
                    }
                }
            }
        }

        if ($_num_countGlobal > 0) {
            $_str_rcode = 'y070104';
            $_str_msg   = 'Successfully removed {:count} users';
        } else {
            $_str_rcode = 'x070104';
            $_str_msg   = 'Did not make any changes';
        }

        return array(
            'msg'    => $_str_msg,
            'count'  => $_num_countGlobal,
            'rcode'  => $_str_rcode,
        );
    }


    function clear($num_no, $num_except = 0, $arr_search = array()) {
        $_arr_belongSelect = array(
            'belong_id',
            'belong_app_id',
            'belong_user_id',
        );

        $_arr_where = $this->queryProcess($arr_search);

        $_arr_belongRows = $this->where($_arr_where)->order('belong_id', 'DESC')->limit($num_except, $num_no)->select($_arr_belongSelect);

        foreach ($_arr_belongRows as $_key=>$_value) {
            $_arr_userSelect = array(
                'user_id',
            );

            $_arr_userRow = $this->table('user')->where('user_id', '=', $_value['belong_user_id'])->order('user_id', 'ASC')->find($_arr_userSelect);

            if (!$_arr_userRow) {
                $this->delete(0, 0, false, false, false, false, $_value['belong_id']);
            }

            $_arr_appSelect = array(
                'app_id',
            );

            $_arr_appRow = $this->table('app')->where('app_id', '=', $_value['belong_app_id'])->order('app_id', 'ASC')->find($_arr_appSelect);

            if (!$_arr_appRow) {
                $this->delete(0, 0, false, false, false, false, $_value['belong_id']);
            }
        }

        return $_arr_belongRows;
    }



    /** 删除
     * delete function.
     *
     * @access public
     * @param int $num_appId (default: 0)
     * @param int $num_userId (default: 0)
     * @param bool $arr_appIds (default: false)
     * @param bool $arr_userIds (default: false)
     * @param bool $arr_notAppIds (default: false)
     * @param bool $arr_notUserIds (default: false)
     * @return void
     */
    function delete($num_appId = 0, $num_userId = 0, $arr_appIds = false, $arr_userIds = false, $arr_notAppIds = false, $arr_notUserIds = false, $num_belongId = 0) {

        $_arr_where = array();

        if ($num_belongId > 0) {
            $_arr_where[] = array('belong_id', '=', $num_belongId);
        }

        if ($num_appId > 0) {
            $_arr_where[] = array('belong_app_id', '=', $num_appId);
        }

        if ($num_userId > 0) {
            $_arr_where[] = array('belong_user_id', '=', $num_userId);
        }

        if (!Func::isEmpty($arr_appIds)) {
            $arr_appIds = Func::arrayFilter($arr_appIds);

            $_arr_where[] = array('belong_app_id', 'IN', $arr_appIds, 'app_ids');
        }

        if (!Func::isEmpty($arr_userIds)) {
            $arr_userIds = Func::arrayFilter($arr_userIds);

            $_arr_where[] = array('belong_user_id', 'IN', $arr_userIds, 'user_ids');
        }

        if (!Func::isEmpty($arr_notAppIds)) {
            $arr_notAppIds = Func::arrayFilter($arr_notAppIds);

            $_arr_where[] = array('belong_app_id', 'NOT IN', $arr_notAppIds, 'not_app_ids');
        }

        if (!Func::isEmpty($arr_notUserIds)) {
            $arr_notUserIds = Func::arrayFilter($arr_notUserIds);

            $_arr_where[] = array('belong_user_id', 'NOT IN', $arr_notUserIds, 'not_user_ids');
        }

        $_arr_belongData = array(
            'belong_app_id'  => 0,
        );

        $_num_count = $this->where($_arr_where)->update($_arr_belongData); //更新数据

        return $_num_count; //成功
    }


    function inputSubmit() {
        $_arr_inputParam = array(
            'app_id'    => array('int', 0),
            'user_ids'  => array('arr', array()),
            '__token__' => array('txt', ''),
        );

        $_arr_inputSubmit = $this->obj_request->post($_arr_inputParam);

        $_mix_vld = $this->validate($_arr_inputSubmit, '', 'submit');

        if ($_mix_vld !== true) {
            return array(
                'rcode' => 'x070201',
                'msg'   => end($_mix_vld),
            );
        }

        $_arr_inputSubmit['rcode'] = 'y070201';

        $this->inputSubmit = $_arr_inputSubmit;

        return $_arr_inputSubmit;
    }


    function inputRemove() {
        $_arr_inputParam = array(
            'app_id'            => array('int', 0),
            'user_ids_belong'   => array('arr', array()),
            '__token__'         => array('txt', ''),
        );

        $_arr_inputRemove = $this->obj_request->post($_arr_inputParam);

        $_mix_vld = $this->validate($_arr_inputRemove, '', 'remove');

        if ($_mix_vld !== true) {
            return array(
                'rcode' => 'x070201',
                'msg'   => end($_mix_vld),
            );
        }

        $_arr_inputRemove['rcode'] = 'y070201';

        $this->inputRemove = $_arr_inputRemove;

        return $_arr_inputRemove;
    }
}
