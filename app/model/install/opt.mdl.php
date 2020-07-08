<?php
/*-----------------------------------------------------------------
！！！！警告！！！！
以下为系统文件，请勿修改
-----------------------------------------------------------------*/

namespace app\model\install;

use app\model\Opt as Opt_Base;

// 不能非法包含或直接执行
defined('IN_GINKGO') or exit('Access denied');

/*-------------设置项模型-------------*/
class Opt extends Opt_Base {

    function inputData() {
        $_arr_inputParam = array(
            'type'      => array('txt', ''),
            'model'     => array('txt', ''),
            '__token__' => array('txt', ''),
        );

        $_arr_inputData = $this->obj_request->post($_arr_inputParam);

        $_is_vld = $this->vld_opt->scene('data')->verify($_arr_inputData);

        if ($_is_vld !== true) {
            $_arr_message = $this->vld_opt->getMessage();
            return array(
                'rcode' => 'x030201',
                'msg'   => end($_arr_message),
            );
        }

        $_arr_inputData['rcode'] = 'y030201';

        $this->inputData = $_arr_inputData;

        return $_arr_inputData;
    }
}
