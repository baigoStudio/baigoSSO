<?php
/*-----------------------------------------------------------------
！！！！警告！！！！
以下为系统文件，请勿修改
-----------------------------------------------------------------*/

namespace app\classes;

use ginkgo\Model as Gk_Model;
use ginkgo\Config;

//不能非法包含或直接执行
defined('IN_GINKGO') or exit('Access Denied');

/*-------------单点登录类-------------*/
abstract class Model extends Gk_Model {

    function dateFormat($time = GK_NOW) {
        $time = (int)$time;

        $_arr_configBase = Config::get('base', 'var_extra');

        $_arr_return = array();

        $_arr_return['date_time']       = date($_arr_configBase['site_date'] . ' ' . $_arr_configBase['site_time_short'], $time);
        $_arr_return['date_time_short'] = date($_arr_configBase['site_date_short'] . ' ' . $_arr_configBase['site_time_short'], $time);

        $_arr_return['date']            = date($_arr_configBase['site_date'], $time);
        $_arr_return['date_short']      = date($_arr_configBase['site_date_short'], $time);

        return $_arr_return;
    }

}
