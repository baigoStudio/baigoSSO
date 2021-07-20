<?php
/*-----------------------------------------------------------------
！！！！警告！！！！
以下为系统文件，请勿修改
-----------------------------------------------------------------*/
namespace app\model\api;

use app\model\Combine_Belong as Combine_Belong_Base;
use ginkgo\Func;
use ginkgo\Arrays;

// 不能非法包含或直接执行
if (!defined('IN_GINKGO')) {
    return 'Access denied';
}

/*-------------应用归属-------------*/
class Combine_Belong extends Combine_Belong_Base {

    function combineIds($arr_search) {
        $_arr_belongSelect = array(
            'belong_combine_id',
        );

        $_arr_where = $this->queryProcess($arr_search);

        $_arr_belongRows = $this->where($_arr_where)->select($_arr_belongSelect);

        $_arr_belongIds = array();

        foreach ($_arr_belongRows as $_key=>$_value) {
            $_arr_belongIds[]   = $_value['belong_combine_id'];
        }

        return Arrays::filter($_arr_belongIds);
    }

}
