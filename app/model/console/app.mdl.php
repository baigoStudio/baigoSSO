<?php
/*-----------------------------------------------------------------
！！！！警告！！！！
以下为系统文件，请勿修改
-----------------------------------------------------------------*/

namespace app\model\console;

use app\model\App as App_Base;
use ginkgo\Func;
use ginkgo\Json;
use ginkgo\Crypt;
use ginkgo\Html;

//不能非法包含或直接执行
defined('IN_GINKGO') or exit('Access denied');

/*-------------应用模型-------------*/
class App extends App_Base {

    public $inputSubmit;
    public $inputStatus;
    public $inputDelete;
    public $inputCommon;

    /** 重置 app key
     * reset function.
     *
     * @access public
     * @param mixed $num_appId
     * @return void
     */
    function reset() {
        $_arr_appData = array(
            'app_key'       => Func::rand(64),
            'app_secret'    => Func::rand(16),
        );

        $_num_count     = $this->where('app_id', '=', $this->inputCommon['app_id'])->update($_arr_appData);

        if ($_num_count > 0) {
            $_str_rcode = 'y050103'; //更新成功
            $_str_msg   = 'Reset successfully';
        } else {
            $_str_rcode = 'x050103'; //更新失败
            $_str_msg   = 'Reset failed';
        }

        return array(
            'rcode' => $_str_rcode, //成功
            'msg'   => $_str_msg,
        );
    }


    /** 提交
     * submit function.
     *
     * @access public
     * @return void
     */
    function submit() {
        $_str_appKey    = '';
        $_str_appSecret = '';

        $_arr_appData = array(
            'app_name'          => $this->inputSubmit['app_name'],
            'app_url_notify'    => $this->inputSubmit['app_url_notify'],
            'app_url_sync'      => $this->inputSubmit['app_url_sync'],
            'app_note'          => $this->inputSubmit['app_note'],
            'app_status'        => $this->inputSubmit['app_status'],
            'app_ip_allow'      => $this->inputSubmit['app_ip_allow'],
            'app_ip_bad'        => $this->inputSubmit['app_ip_bad'],
            'app_sync'          => $this->inputSubmit['app_sync'],
            'app_allow'         => $this->inputSubmit['app_allow'],
            'app_param'         => $this->inputSubmit['app_param'],
        );

        $_mix_vld = $this->validate($_arr_appData, '', 'submit_db');

        if ($_mix_vld !== true) {
            return array(
                'app_id'        => $this->inputSubmit['app_id'],
                'app_key'       => 0,
                'app_secret'    => 0,
                'rcode'         => 'x050201',
                'msg'           => end($_mix_vld),
            );
        }

        $_arr_appData['app_allow']        = Json::encode($_arr_appData['app_allow']);
        $_arr_appData['app_param']        = Json::encode($_arr_appData['app_param']);

        if ($this->inputSubmit['app_id'] > 0) {
            $_num_appId     = $this->inputSubmit['app_id'];
            $_num_count     = $this->where('app_id', '=', $_num_appId)->update($_arr_appData);

            if ($_num_count > 0) {
                $_str_rcode = 'y050103'; //更新成功
                $_str_msg   = 'Update App successfully';
            } else {
                $_str_rcode = 'x050103'; //更新失败
                $_str_msg   = 'Did not make any changes';
            }
        } else {
            $_str_appKey    = Func::rand(64);
            $_str_appSecret = Func::rand(16);
            $_arr_insert = array(
                'app_key'       => $_str_appKey,
                'app_secret'    => $_str_appSecret,
                'app_time'      => GK_NOW,
            );
            $_arr_data = array_replace_recursive($_arr_appData, $_arr_insert);

            $_num_appId     = $this->insert($_arr_data);

            if ($_num_appId > 0) {
                $_str_rcode = 'y050101'; //更新成功
                $_str_msg   = 'Add App successfully';
            } else {
                $_str_rcode = 'x050101'; //更新失败
                $_str_msg   = 'Add App failed';
            }
        }

        return array(
            'app_id'        => $_num_appId,
            'app_key'       => Crypt::crypt($_str_appKey, $this->inputSubmit['app_name']),
            'app_secret'    => $_str_appSecret,
            'rcode'         => $_str_rcode, //成功
            'msg'           => $_str_msg,
        );
    }


    /** 更改状态
     * status function.
     *
     * @access public
     * @param mixed $str_status
     * @return void
     */
    function status() {
        $_arr_appUpdate = array(
            'app_status' => $this->inputStatus['act'],
        );

        $_num_count     = $this->where('app_id', 'IN', $this->inputStatus['app_ids'], 'app_ids')->update($_arr_appUpdate); //更新数据

        //如影响行数大于0则返回成功
        if ($_num_count > 0) {
            $_str_rcode = 'y050103'; //成功
            $_str_msg   = 'Successfully updated {:count} Apps';
        } else {
            $_str_rcode = 'x050103'; //失败
            $_str_msg   = 'Did not make any changes';
        }

        return array(
            'count' => $_num_count,
            'rcode' => $_str_rcode,
            'msg'   => $_str_msg,
        );
    }


    /** 删除
     * delete function.
     *
     * @access public
     * @return void
     */
    function delete() {
        $_num_count     = $this->where('app_id', 'IN', $this->inputDelete['app_ids'], 'app_ids')->delete(); //更新数据

        //如车影响行数小于0则返回错误
        if ($_num_count > 0) {
            $_str_rcode = 'y050104'; //成功
            $_str_msg   = 'Successfully deleted {:count} Apps';
        } else {
            $_str_rcode = 'x050104'; //失败
            $_str_msg   = 'No App have been deleted';
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
            'app_id'            => array('int', 0),
            'app_name'          => array('txt', ''),
            'app_url_notify'    => array('txt', ''),
            'app_url_sync'      => array('txt', ''),
            'app_note'          => array('txt', ''),
            'app_status'        => array('txt', ''),
            'app_ip_allow'      => array('txt', ''),
            'app_ip_bad'        => array('txt', ''),
            'app_sync'          => array('txt', ''),
            'app_allow'         => array('arr', array()),
            'app_param'         => array('arr', array()),
            '__token__'         => array('txt', ''),
        );

        $_arr_inputSubmit = $this->obj_request->post($_arr_inputParam);

        $_arr_inputSubmit['app_url_notify']   = Html::decode($_arr_inputSubmit['app_url_notify'], 'url');
        $_arr_inputSubmit['app_url_sync']     = Html::decode($_arr_inputSubmit['app_url_sync'], 'url');

        $_mix_vld = $this->validate($_arr_inputSubmit, '', 'submit');

        if ($_mix_vld !== true) {
            return array(
                'rcode' => 'x050201',
                'msg'   => end($_mix_vld),
            );
        }

        if ($_arr_inputSubmit['app_id'] > 0) {
            $_arr_appRow = $this->check($_arr_inputSubmit['app_id']);

            if ($_arr_appRow['rcode'] != 'y050102') {
                return $_arr_appRow;
            }
        }

        $_arr_inputSubmit['rcode'] = 'y050201';

        $this->inputSubmit = $_arr_inputSubmit;

        return $_arr_inputSubmit;
    }


    /** 选择
     * inputCommon function.
     *
     * @access public
     * @return void
     */
    function inputCommon() {
        $_arr_inputParam = array(
            'app_id'    => array('int', 0),
            '__token__' => array('txt', ''),
        );

        $_arr_inputCommon = $this->obj_request->post($_arr_inputParam);

        $_mix_vld = $this->validate($_arr_inputCommon, '', 'common');

        if ($_mix_vld !== true) {
            return array(
                'rcode' => 'x050201',
                'msg'   => end($_mix_vld),
            );
        }

        $_arr_inputCommon['rcode'] = 'y050201';

        $this->inputCommon = $_arr_inputCommon;

        return $_arr_inputCommon;
    }



    /** 选择
     * inputStatus function.
     *
     * @access public
     * @return void
     */
    function inputStatus() {
        $_arr_inputParam = array(
            'app_ids'   => array('arr', array()),
            'act'       => array('txt', ''),
            '__token__' => array('txt', ''),
        );

        $_arr_inputStatus = $this->obj_request->post($_arr_inputParam);

        $_arr_inputStatus['app_ids'] = Func::arrayFilter($_arr_inputStatus['app_ids']);

        $_mix_vld = $this->validate($_arr_inputStatus, '', 'status');

        if ($_mix_vld !== true) {
            return array(
                'rcode' => 'x050201',
                'msg'   => end($_mix_vld),
            );
        }

        $_arr_inputStatus['rcode'] = 'y050201';

        $this->inputStatus = $_arr_inputStatus;

        return $_arr_inputStatus;
    }


    /** 选择
     * inputStatus function.
     *
     * @access public
     * @return void
     */
    function inputDelete() {
        $_arr_inputParam = array(
            'app_ids'   => array('arr', array()),
            '__token__' => array('txt', ''),
        );

        $_arr_inputDelete = $this->obj_request->post($_arr_inputParam);

        $_arr_inputDelete['app_ids'] = Func::arrayFilter($_arr_inputDelete['app_ids']);

        $_mix_vld = $this->validate($_arr_inputDelete, '', 'delete');

        if ($_mix_vld !== true) {
            return array(
                'rcode' => 'x050201',
                'msg'   => end($_mix_vld),
            );
        }

        $_arr_inputDelete['rcode'] = 'y050201';

        $this->inputDelete = $_arr_inputDelete;

        return $_arr_inputDelete;
    }
}
