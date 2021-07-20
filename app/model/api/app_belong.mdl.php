<?php
/*-----------------------------------------------------------------
！！！！警告！！！！
以下为系统文件，请勿修改
-----------------------------------------------------------------*/
namespace app\model\api;

use app\model\App_Belong as App_Belong_Base;

// 不能非法包含或直接执行
if (!defined('IN_GINKGO')) {
    return 'Access denied';
}

/*-------------应用归属-------------*/
class App_Belong extends App_Belong_Base {

    function submit($num_appId, $num_userId) {
        $_str_rcode = 'x070101';

        if ($num_userId > 0 && $num_appId > 0) { //插入
            $_arr_belongRow = $this->read($num_appId, $num_userId);

            if ($_arr_belongRow['rcode'] == 'x070102') { //插入
                $_arr_belongData = array(
                    'belong_user_id' => $num_userId,
                    'belong_app_id'  => $num_appId,
                );

                $_arr_belongRowSub = $this->read(0, $num_userId);

                if ($_arr_belongRowSub['rcode'] == 'y070102') {
                    $_num_count     = $this->where('belong_id', '=', $_arr_belongRowSub['belong_id'])->update($_arr_belongData);

                    if ($_num_count > 0) {
                        $_str_rcode = 'y070103';
                    } else {
                        $_str_rcode = 'x070103';
                    }
                } else {
                    $_num_belongId  = $this->insert($_arr_belongData);

                    if ($_num_belongId > 0) { //数据库插入是否成功
                        $_str_rcode = 'y070101';
                    } else {
                        $_str_rcode = 'x070101';
                    }
                }
            } else {
                $_arr_belongData = array(
                    'belong_app_id'  => 0,
                );

                $_num_count     = $this->where('belong_id', '=', $_arr_belongRow['belong_id'])->update($_arr_belongData);

                if ($_num_count > 0) {
                    $_str_rcode = 'y070103';
                } else {
                    $_str_rcode = 'x070103';
                }
            }
        }

        return array(
            'rcode'  => $_str_rcode,
        );
    }
}
