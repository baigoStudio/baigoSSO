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
class CONTROL_CONSOLE_UI_VERIFY {

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

        $this->mdl_verify       = new MODEL_VERIFY();
        $this->mdl_user         = new MODEL_USER();

        $this->tplData = array(
            'adminLogged'   => $this->adminLogged,
            'status'        => $this->mdl_verify->arr_status,
            'type'          => $this->mdl_verify->arr_type,
        );
    }


    function ctrl_show() {
        if (!isset($this->adminLogged['admin_allow']['verify']) && !$this->is_super) {
            $this->tplData['rcode'] = 'x120301';
            $this->obj_tpl->tplDisplay('error', $this->tplData);
        }

        $_num_verifyId = fn_getSafe(fn_get('verify_id'), 'int', 0);

        if ($_num_verifyId < 1) {
            $this->tplData['rcode'] = 'x120201';
            $this->obj_tpl->tplDisplay('error', $this->tplData);
        }

        $_arr_verifyRow = $this->mdl_verify->mdl_read($_num_verifyId);

        if ($_arr_verifyRow['rcode'] != 'y120102') {
            $this->tplData['rcode'] = $_arr_verifyRow['rcode'];
            $this->obj_tpl->tplDisplay('error', $this->tplData);
        }

        $_arr_verifyRow['userRow'] = $this->mdl_user->mdl_read($_arr_verifyRow['verify_user_id']);

        $_arr_tpl = array(
            'verifyRow'    => $_arr_verifyRow,
        );

        $_arr_tplData = array_merge($this->tplData, $_arr_tpl);

        $this->obj_tpl->tplDisplay('verify_show', $_arr_tplData);
    }

    /**
     * ctrl_list function.
     *
     * @access public
     */
    function ctrl_list() {
        if (!isset($this->adminLogged['admin_allow']['verify']) && !$this->is_super) {
            $this->tplData['rcode'] = 'x120301';
            $this->obj_tpl->tplDisplay('error', $this->tplData);
        }

        $_num_verifyCount   = $this->mdl_verify->mdl_count();
        $_arr_page          = fn_page($_num_verifyCount); //取得分页数据
        $_arr_verifyRows    = $this->mdl_verify->mdl_list(BG_DEFAULT_PERPAGE, $_arr_page['except']);

        foreach ($_arr_verifyRows as $_key=>$_value) {
            $_arr_verifyRows[$_key]['userRow'] = $this->mdl_user->mdl_read($_value['verify_user_id']);
        }

        $_arr_tpl = array(
            'pageRow'       => $_arr_page,
            'verifyRows'    => $_arr_verifyRows,
        );

        $_arr_tplData = array_merge($this->tplData, $_arr_tpl);

        $this->obj_tpl->tplDisplay('verify_list', $_arr_tplData);
    }
}
