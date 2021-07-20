<?php
/*-----------------------------------------------------------------
！！！！警告！！！！
以下为系统文件，请勿修改
-----------------------------------------------------------------*/

namespace app\model\install;

use app\classes\install\Model;
use ginkgo\Func;

// 不能非法包含或直接执行
if (!defined('IN_GINKGO')) {
    return 'Access denied';
}

/*-------------用户模型-------------*/
class App_Combine_View extends Model {

    /** 创建视图
     * mdl_create_view function.
     *
     * @access public
     * @return void
     */
    function createView() {
        $_arr_viewCreate = array(
            array('app.app_id'),
            array('app.app_name'),
            array('app.app_note'),
            array('app.app_status'),
            array('app.app_key'),
            array('app.app_secret'),
            array('app.app_param'),
            array('app.app_url_notify'),
            array('app.app_url_sync'),
            array('app.app_sync'),
            array('app.app_time'),
            //array('combine_belong.belong_combine_id'),

            'IFNULL(' . $this->obj_builder->table('combine_belong') . '.`belong_combine_id`, 0) AS `belong_combine_id`',
        );

        $_arr_join = array(
            'combine_belong',
            array('app.app_id', '=', 'combine_belong.belong_app_id'),
            'LEFT',
        );

        $_num_count  = $this->viewFrom('app')->viewJoin($_arr_join)->create($_arr_viewCreate);

        if ($_num_count !== false) {
            $_str_rcode = 'y040108'; //更新成功
            $_str_msg   = 'Create view successfully';
        } else {
            $_str_rcode = 'x040108'; //更新成功
            $_str_msg   = 'Create view failed';
        }

        return array(
            'rcode' => $_str_rcode, //更新成功
            'msg'   => $_str_msg,
        );
    }
}
