<?php
/*-----------------------------------------------------------------
！！！！警告！！！！
以下为系统文件，请勿修改
-----------------------------------------------------------------*/
namespace app\model\api;

use app\model\User;

// 不能非法包含或直接执行
defined('IN_GINKGO') or exit('Access denied');

/*-------------用户模型-------------*/
class Sync extends User {

    protected $table = 'user';

    function inputCommon($arr_data) {
        $_arr_inputParam = array(
            'timestamp'         => array('int', 0),
            'user_access_token' => array('txt', ''),
        );

        $_arr_inputCommon  = $this->obj_request->fillParam($arr_data, $_arr_inputParam);

        $_arr_inputUserCommon    = $this->inputUserCommon($arr_data);

        $_arr_inputCommon  = array_replace_recursive($_arr_inputCommon, $_arr_inputUserCommon);

        $_mix_vld = $this->validate($_arr_inputCommon);

        if ($_mix_vld !== true) {
            return array(
                'rcode' => 'x010201',
                'msg'   => end($_mix_vld),
            );
        }

        $_arr_inputCommon['rcode'] = 'y010201';

        $this->inputCommon = $_arr_inputCommon;

        return $_arr_inputCommon;
    }
}

