<?php
/*-----------------------------------------------------------------
！！！！警告！！！！
以下为系统文件，请勿修改
-----------------------------------------------------------------*/

namespace app\model\personal;

use app\model\User as User_Base;

//不能非法包含或直接执行
defined('IN_GINKGO') or exit('Access denied');

/*-------------用户模型-------------*/
class User extends User_Base {

    function confirm($num_userId) {
        $_arr_userData['user_status'] = 'enable';

        $_num_count = $this->where('user_id', '=', $num_userId)->update($_arr_userData);

        if ($_num_count > 0) {
            $_str_rcode = 'y010103'; //更新成功
            $_str_msg   = 'Activate user successful';
        } else {
            $_str_rcode = 'x010103';
            $_str_msg   = 'Activate user failed';
        }

        return array(
            'rcode'      => $_str_rcode, //成功
            'msg'        => $_str_msg,
        );
    }
}
