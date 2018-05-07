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
class CONTROL_CONSOLE_UI_ADMIN {

    private $is_super = false;

    function __construct() { //构造函数
        $this->general_console  = new GENERAL_CONSOLE();
        $this->general_console->chk_install();

        $this->adminLogged  = $this->general_console->ssin_begin(); //获取已登录信息
        $this->general_console->is_admin($this->adminLogged);

        $this->obj_tpl      = $this->general_console->obj_tpl;

        if ($this->adminLogged['admin_type'] == 'super') {
            $this->is_super = true;
        }

        $this->mdl_admin        = new MODEL_ADMIN(); //设置管理组模型
        //$this->mdl_user         = new MODEL_USER(); //设置管理组模型

        $this->tplData = array(
            'adminLogged'   => $this->adminLogged,
            'status'        => $this->mdl_admin->arr_status,
            'type'          => $this->mdl_admin->arr_type,
        );
    }


    function ctrl_show() {
        $_num_adminId = fn_getSafe(fn_get('admin_id'), 'int', 0); //get 获取 admin_id

        if (!isset($this->adminLogged['admin_allow']['admin']['browse']) && !$this->is_super) { //判断权限
            $this->tplData['rcode'] = 'x020303';
            $this->obj_tpl->tplDisplay('error', $this->tplData);
        }
        $_arr_adminRow = $this->mdl_admin->mdl_read($_num_adminId); //读取
        if ($_arr_adminRow['rcode'] != 'y020102') {
            $this->tplData['rcode'] = $_arr_adminRow['rcode'];
            $this->obj_tpl->tplDisplay('error', $this->tplData);
        }

        $this->tplData['adminRow'] = $_arr_adminRow; //管理员信息

        $this->obj_tpl->tplDisplay('admin_show', $this->tplData); //显示
    }


    function ctrl_auth() {
        if (!isset($this->adminLogged['admin_allow']['admin']['add']) && !$this->is_super) { //判断权限
            $this->tplData['rcode'] = 'x020302';
            $this->obj_tpl->tplDisplay('error', $this->tplData);
        }
        $_arr_adminRow = array(
            'admin_id'      => 0,
            'admin_nick'    => '',
            'admin_note'    => '',
            'admin_status'  => $this->mdl_admin->arr_status[0],
            'admin_type'    => $this->mdl_admin->arr_type[0],
            'admin_allow'   => array(),
        );

        $this->tplData['adminRow'] = $_arr_adminRow; //管理员信息

        $this->obj_tpl->tplDisplay('admin_auth', $this->tplData);
    }


    /**
     * ctrl_form function.
     *
     * @access public
     */
    function ctrl_form() {
        $_num_adminId = fn_getSafe(fn_get('admin_id'), 'int', 0); //get 获取 admin_id

        if ($_num_adminId > 0) {
            if (!isset($this->adminLogged['admin_allow']['admin']['edit']) && !$this->is_super) { //判断权限
                $this->tplData['rcode'] = 'x020303';
                $this->obj_tpl->tplDisplay('error', $this->tplData);
            }
            if ($_num_adminId == $this->adminLogged['admin_id'] && !$this->is_super) {
                $this->tplData['rcode'] = 'x020306';
                $this->obj_tpl->tplDisplay('error', $this->tplData);
            }
            $_arr_adminRow = $this->mdl_admin->mdl_read($_num_adminId);
            if ($_arr_adminRow['rcode'] != 'y020102') {
                $this->tplData['rcode'] = $_arr_adminRow['rcode'];
                $this->obj_tpl->tplDisplay('error', $this->tplData);
            }
        } else {
            if (!isset($this->adminLogged['admin_allow']['admin']['add']) && !$this->is_super) { //判断权限
                $this->tplData['rcode'] = 'x020302';
                $this->obj_tpl->tplDisplay('error', $this->tplData);
            }
            $_arr_adminRow = array(
                'admin_id'      => 0,
                'admin_nick'    => '',
                'admin_note'    => '',
                'admin_status'  => $this->mdl_admin->arr_status[0],
                'admin_type'    => $this->mdl_admin->arr_type[0],
                'admin_allow'   => array(),
            );
        }

        $this->tplData['adminRow'] = $_arr_adminRow; //管理员信息

        $this->obj_tpl->tplDisplay('admin_form', $this->tplData);
    }


    /**
     * ctrl_list function.
     *
     * @access public
     */
    function ctrl_list() {
        if (!isset($this->adminLogged['admin_allow']['admin']['browse']) && !$this->is_super) { //判断权限
            $this->tplData['rcode'] = 'x020301';
            $this->obj_tpl->tplDisplay('error', $this->tplData);
        }

        $_arr_search = array(
            'key'       => fn_getSafe(fn_get('key'), 'txt', ''), //搜索关键词
            'status'    => fn_getSafe(fn_get('status'), 'txt', ''), //搜索状态
            'type'      => fn_getSafe(fn_get('type'), 'txt', ''), //搜索状态
        );

        $_num_adminCount  = $this->mdl_admin->mdl_count($_arr_search); //统计记录数
        $_arr_page        = fn_page($_num_adminCount); //取得分页数据
        $_str_query       = http_build_query($_arr_search); //将搜索参数拼合成查询串
        $_arr_adminRows   = $this->mdl_admin->mdl_list(BG_DEFAULT_PERPAGE, $_arr_page['except'], $_arr_search); //列出

        $_arr_tpl = array(
            'query'      => $_str_query,
            'pageRow'    => $_arr_page,
            'search'     => $_arr_search,
            'adminRows'  => $_arr_adminRows,
        );

        $_arr_tplData = array_merge($this->tplData, $_arr_tpl);

        $this->obj_tpl->tplDisplay('admin_list', $_arr_tplData);
    }
}