<?php
/*-----------------------------------------------------------------
！！！！警告！！！！
以下为系统文件，请勿修改
-----------------------------------------------------------------*/

namespace app\ctrl\api;

use app\classes\api\Ctrl;
use ginkgo\Loader;

// 不能非法包含或直接执行
if (!defined('IN_GINKGO')) {
    return 'Access denied';
}

class Base extends Ctrl {

    function pm() {
        $_mix_init = $this->init();

        if ($_mix_init !== true) {
            return $this->fetchJson($_mix_init['msg'], $_mix_init['rcode']);
        }

        $_mdl_pm = Loader::model('Pm');

        foreach ($_mdl_pm->arr_status as $_key=>$_value) {
            $_arr_status[$_value] = $this->obj_lang->get($_value);
        }

        foreach ($_mdl_pm->arr_type as $_key=>$_value) {
            $_arr_type[$_value] = $this->obj_lang->get($_value);
        }

        $_arr_return = array(
            'status'    => $_arr_status,
            'type'      => $_arr_type,
        );

        $_arr_tpl = array_replace_recursive($this->version, $_arr_return);

        return $this->json($_arr_tpl);
    }


    function urls() {
        $_mix_init = $this->init();

        if ($_mix_init !== true) {
            return $this->fetchJson($_mix_init['msg'], $_mix_init['rcode']);
        }

        $_arr_return = array(
            'url_forgot' => $this->generalData['url_personal'] . 'forgot/',
            'url_nomail' => $this->generalData['url_personal'] . 'reg/nomail/',
            'url_reg'    => $this->generalData['url_personal'] . 'reg/',
        );

        $_arr_tpl = array_replace_recursive($this->version, $_arr_return);

        return $this->json($_arr_tpl);

    }
}
