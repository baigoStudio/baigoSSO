<?php
/*-----------------------------------------------------------------
！！！！警告！！！！
以下为系统文件，请勿修改
-----------------------------------------------------------------*/

namespace app\classes;

use ginkgo\Model as Gk_Model;
use ginkgo\Config;

//不能非法包含或直接执行
if (!defined('IN_GINKGO')) {
    return 'Access denied';
}

/*-------------单点登录类-------------*/
abstract class Model extends Gk_Model {

    protected $configBase;

    protected function m_init() {
        $this->configBase = Config::get('base', 'var_extra');
    }


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


    function paginationProcess($pagination = 0) {
        $_num_limit     = false; // limit 参数 0
        $_num_length    = false; // limit 参数 1
        $_num_perpage   = 0; // 每页记录数
        $_mix_current   = ''; // 当前页获取方法

        if (is_array($pagination)) { // 如果是数组, 则认定为复杂参数
            if (isset($pagination[2]) && isset($pagination[1]) && isset($pagination[0])) { // 指定了参数2
                $pagination[0]  = (int)$pagination[0]; // 转换参数0
                $pagination[1]  = (int)$pagination[1]; // 转换参数1
                $_str_type      = (string)$pagination[2]; // 转换参数2

                if ($_str_type == 'limit') { // 参数2为 limit
                    if ($pagination[1] > 0) {
                        $_num_limit     = $pagination[1]; // 长度或偏移
                        $_num_length    = $pagination[0]; // 长度
                    } else {
                        $_num_limit     = $pagination[0]; // 长度
                    }
                }
            } else if (isset($pagination[1]) && isset($pagination[0])) { // 指定了参数1
                if (is_int($pagination[1])) { // 参数1为整数, 则设置每页记录数和当前页数
                    $_num_perpage = (int)$pagination[0]; // 每页记录数
                    $_mix_current = (int)$pagination[1]; // 当前页数
                } else if (is_string($pagination[1])) { // 参数1为字符
                    $_str_type = (string)$pagination[1];

                    switch ($_str_type) {
                        case 'limit': // 参数1为 limit, 则设置单一 limit 参数
                            $_num_limit   = (int)$pagination[0]; // 长度
                        break;

                        default: // 参数1为其他值, 则设置每页记录数和当前页获取方法
                            $_num_perpage = (int)$pagination[0]; // 每页记录数
                            $_mix_current = (string)$pagination[1]; // 当前页获取方法
                        break;
                    }
                }
            } else if (isset($pagination[0])) { // 只指定了参数0, 则设置每页记录数
                $_num_perpage = (int)$pagination[0]; // 每页记录数
            }
        } else { // 非数组则则设置每页记录数
            $_num_perpage = (int)$pagination; // 每页记录数
        }

        return array(
            'limit'     => $_num_limit,
            'length'    => $_num_length,
            'perpage'   => $_num_perpage,
            'current'   => $_mix_current,
        );
    }


    function timeDiff($time = GK_NOW) {
        $time = (int)$time;

        $_tm_diff       = GK_NOW - $time;
        $_str_year      = date('Y', $time);
        $_str_diff      = '';
        $_str_unit      = '';
        $_tm_today      = strtotime('today 00:00:00');
        $_tm_yesterday  = strtotime('-1 day 00:00:00');
        $_tm_before     = strtotime('-2 days 00:00:00');
        $_str_yearThis  = date('Y');

        if ($_tm_diff < GK_MINUTE) { //1分钟内
            $_str_diff = 'Just now';
        } else if ($_tm_diff < GK_HOUR) { //1小时内
            $_str_diff = floor($_tm_diff / GK_MINUTE);
            $_str_unit = 'Minutes ago';
        } else if ($_tm_diff < GK_DAY) { //24小时内
            if ($time > $_tm_today) { //今天
                $_str_diff = floor($_tm_diff / GK_HOUR); //昨天
                $_str_unit = 'Hours ago';
            } else {
                $_str_diff = 'Yesterday';
            }
        } else if ($_tm_diff < GK_MONTH) { //一个月内
            if ($time > $_tm_yesterday) { //昨天
                $_str_diff = 'Yesterday';
            } else if ($time > $_tm_before) { //前天
                $_str_diff = 'The day before yesterday';
            } else { //3天前
                $_str_diff = floor($_tm_diff / GK_DAY);
                $_str_unit = 'Days ago';
            }
        } else if ($_str_year == $_str_yearThis) { //今年

            $_str_diff = date($this->configBase['site_date_short'] . ' ' . $this->configBase['site_time_short'], $time);
        } else {
            $_str_diff = date($this->configBase['site_date'] . ' ' . $this->configBase['site_time_short'], $time);
        }

        return array(
            'diff' => $_str_diff,
            'unit' => $_str_unit,
        );
    }


    function enumSearch($index = 0, $arr_src = array()) {
        $_mix_return = 0;

        $arr_src    = (array)$arr_src;

        if (is_numeric($index) && isset($arr_src[$index])) {
            $_mix_return = $arr_src[$index];
        } else if (is_string($index) && in_array($index, $arr_src)) {
            $_mix_return = array_search($index, $arr_src);
        }

        return $_mix_return;
    }
}

