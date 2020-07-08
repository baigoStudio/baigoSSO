<?php
/*-----------------------------------------------------------------
！！！！警告！！！！
以下为系统文件，请勿修改
-----------------------------------------------------------------*/

namespace app\model;

use app\classes\Model;

// 不能非法包含或直接执行
defined('IN_GINKGO') or exit('Access denied');

/*-------------用户模型-------------*/
class User_Common extends Model {

    function inputUserCommon($arr_data) {
        $_arr_inputUserCommon = array(
            'user_str'  => '',
            'user_by'   => '',
        );

        if (isset($arr_data['user_id'])) {
            $_arr_inputUserCommon['user_by']  = 'user_id';
            $_arr_inputUserCommon['user_str'] = $arr_data['user_id'];
        } else if (isset($arr_data['user_name'])) {
            $_arr_inputUserCommon['user_by']  = 'user_name';
            $_arr_inputUserCommon['user_str'] = $arr_data['user_name'];
        } else if (isset($arr_data['user_mail'])) {
            $_arr_inputUserCommon['user_by']  = 'user_mail';
            $_arr_inputUserCommon['user_str'] = $arr_data['user_mail'];
        }

        return $_arr_inputUserCommon;
    }
}
