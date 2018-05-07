<?php
/*-----------------------------------------------------------------
！！！！警告！！！！
以下为系统文件，请勿修改
-----------------------------------------------------------------*/

//不能非法包含或直接执行
if (!defined('IN_BAIGO')) {
    exit('Access Denied');
}

/*-------------管理员控制器-------------*/
class CONTROL_CONSOLE_UI_PM {

    private $is_super = false;

    function __construct() { //构造函数
        $this->general_console      = new GENERAL_CONSOLE();
        $this->general_console->chk_install();

        $this->adminLogged      = $this->general_console->ssin_begin(); //获取已登录信息
        $this->general_console->is_admin($this->adminLogged);

        $this->obj_tpl          = $this->general_console->obj_tpl;

        if ($this->adminLogged['admin_type'] == 'super') {
            $this->is_super = true;
        }

        $this->mdl_pm           = new MODEL_PM(); //设置管理员模型
        $this->mdl_user         = new MODEL_USER(); //设置管理员模型

        $this->tplData = array(
            'adminLogged'   => $this->adminLogged,
            'status'        => $this->mdl_pm->arr_status,
            'type'          => $this->mdl_pm->arr_type,
        );
    }


    function ctrl_send() {
        if (!isset($this->adminLogged['admin_allow']['pm']['send']) && !$this->is_super) {
            $this->tplData['rcode'] = 'x110303';
            $this->obj_tpl->tplDisplay('error', $this->tplData);
        }

        $this->obj_tpl->tplDisplay('pm_send', $this->tplData);
    }


    /*============群发界面============
    返回提示
    */
    function ctrl_bulk() {
        if (!isset($this->adminLogged['admin_allow']['pm']['bulk']) && !$this->is_super) {
            $this->tplData['rcode'] = 'x110302';
            $this->obj_tpl->tplDisplay('error', $this->tplData);
        }

        $this->tplData['begin_time']    = time() - 86400;
        $this->tplData['end_time']      = time();

        $this->obj_tpl->tplDisplay('pm_bulk', $this->tplData);
    }


    /*============编辑管理员界面============
    返回提示
    */
    function ctrl_show() {
        if (!isset($this->adminLogged['admin_allow']['pm']['browse']) && !$this->is_super) {
            $this->tplData['rcode'] = 'x110301';
            $this->obj_tpl->tplDisplay('error', $this->tplData);
        }

        $_num_pmId = fn_getSafe(fn_get('pm_id'), 'int', 0);
        if ($_num_pmId < 1) {
            $this->tplData['rcode'] = 'x110211';
            $this->obj_tpl->tplDisplay('error', $this->tplData);
        }

        $_arr_pmRow = $this->mdl_pm->mdl_read($_num_pmId);
        if ($_arr_pmRow['rcode'] != 'y110102') {
            $this->tplData['rcode'] = $_arr_pmRow['rcode'];
            $this->obj_tpl->tplDisplay('error', $this->tplData);
        }

        $_arr_pmRow['toUser']   = $this->mdl_user->mdl_read($_arr_pmRow['pm_to']);
        $_arr_pmRow['fromUser'] = $this->mdl_user->mdl_read($_arr_pmRow['pm_from']);

        $this->tplData['pmRow'] = $_arr_pmRow;

        $this->obj_tpl->tplDisplay('pm_show', $this->tplData);
    }

    /*============列出管理员界面============
    无返回
    */
    function ctrl_list() {
        if (!isset($this->adminLogged['admin_allow']['pm']['browse']) && !$this->is_super) {
            $this->tplData['rcode'] = 'x110301';
            $this->obj_tpl->tplDisplay('error', $this->tplData);
        }

        $_arr_search = array(
            'key'       => fn_getSafe(fn_get('key'), 'txt', ''),
            'type'      => fn_getSafe(fn_get('type'), 'txt', ''),
            'status'    => fn_getSafe(fn_get('status'), 'txt', ''),
            'pm_from'   => fn_getSafe(fn_get('pm_from'), 'int', 0),
            'pm_to'     => fn_getSafe(fn_get('pm_to'), 'int', 0),
        );

        $_num_pmCount   = $this->mdl_pm->mdl_count($_arr_search);
        $_arr_page      = fn_page($_num_pmCount); //取得分页数据
        $_str_query     = http_build_query($_arr_search);
        $_arr_pmRows    = $this->mdl_pm->mdl_list(BG_DEFAULT_PERPAGE, $_arr_page['except'], $_arr_search);

        foreach ($_arr_pmRows as $_key=>$_value) {
            $_arr_pmRows[$_key]['toUser']   = $this->mdl_user->mdl_read($_value['pm_to']);
            $_arr_pmRows[$_key]['fromUser'] = $this->mdl_user->mdl_read($_value['pm_from']);
        }

        $_arr_tpl = array(
            'query'     => $_str_query,
            'pageRow'   => $_arr_page,
            'search'    => $_arr_search,
            'pmRows'    => $_arr_pmRows,
        );

        $_arr_tplData = array_merge($this->tplData, $_arr_tpl);

        $this->obj_tpl->tplDisplay('pm_list', $_arr_tplData);
    }
}
