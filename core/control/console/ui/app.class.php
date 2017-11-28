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
class CONTROL_CONSOLE_UI_APP {

    private $is_super = false;

    function __construct() { //构造函数
        $this->config   = $GLOBALS['obj_base']->config;

        $this->general_console      = new GENERAL_CONSOLE();
        $this->general_console->chk_install();

        $this->adminLogged      = $this->general_console->ssin_begin(); //获取已登录信息
        $this->general_console->is_admin($this->adminLogged);

        $this->obj_tpl          = $this->general_console->obj_tpl;
        $this->obj_tpl->lang['allow']          = fn_include(BG_PATH_LANG . $this->config['lang'] . DS . 'allow.php');

        if ($this->adminLogged['admin_type'] == 'super') {
            $this->is_super = true;
        }

        $this->mdl_app          = new MODEL_APP(); //设置管理员模型
        $this->mdl_user         = new MODEL_USER(); //设置管理员模型

        $this->tplData = array(
            'adminLogged'   => $this->adminLogged,
            'status'        => $this->mdl_app->arr_status,
            'sync'          => $this->mdl_app->arr_sync,
            'allow'         => fn_include(BG_PATH_INC . 'allow.inc.php'),
        );
    }


    /*============编辑管理员界面============
    返回提示
    */
    function ctrl_show() {
        if (!isset($this->adminLogged['admin_allow']['app']['browse']) && !$this->is_super) {
            $this->tplData['rcode'] = 'x050301';
            $this->obj_tpl->tplDisplay('error', $this->tplData);
        }

        $_num_appId = fn_getSafe(fn_get('app_id'), 'int', 0);
        if ($_num_appId < 1) {
            $this->tplData['rcode'] = 'x050203';
            $this->obj_tpl->tplDisplay('error', $this->tplData);
        }

        $_arr_appRow = $this->mdl_app->mdl_read($_num_appId);
        if ($_arr_appRow['rcode'] != 'y050102') {
            $this->tplData['rcode'] = $_arr_appRow['rcode'];
            $this->obj_tpl->tplDisplay('error', $this->tplData);
        }

        $_arr_appRow['app_key'] = fn_baigoCrypt($_arr_appRow['app_key'], $_arr_appRow['app_name']);

        $_arr_searchView = array(
            'app_id'     => $_num_appId,
        );
        $_arr_userViews   = $this->mdl_user->mdl_list_view($_arr_searchView);

        $_arr_tpl = array(
            'userViews'  => $_arr_userViews,
            'appRow'     => $_arr_appRow,
        );

        $_arr_tplData = array_merge($this->tplData, $_arr_tpl);

        $this->obj_tpl->tplDisplay('app_show', $_arr_tplData);
    }

    /*============编辑管理员界面============
    返回提示
    */
    function ctrl_form() {
        $_num_appId = fn_getSafe(fn_get('app_id'), 'int', 0);

        if ($_num_appId > 0) {
            if (!isset($this->adminLogged['admin_allow']['app']['edit']) && !$this->is_super) {
                $this->tplData['rcode'] = 'x050303';
                $this->obj_tpl->tplDisplay('error', $this->tplData);
            }
            $_arr_appRow = $this->mdl_app->mdl_read($_num_appId);
            if ($_arr_appRow['rcode'] != 'y050102') {
                $this->tplData['rcode'] = $_arr_appRow['rcode'];
                $this->obj_tpl->tplDisplay('error', $this->tplData);
            }
        } else {
            if (!isset($this->adminLogged['admin_allow']['app']['add']) && !$this->is_super) {
                $this->tplData['rcode'] = 'x050302';
                $this->obj_tpl->tplDisplay('error', $this->tplData);
            }

            $_arr_appRow = array(
                'app_id'            => 0,
                'app_name'          => '',
                'app_url_notify'    => '',
                'app_url_sync'      => '',
                'app_ip_allow'      => '',
                'app_ip_bad'        => '',
                'app_note'          => '',
                'app_status'        => $this->mdl_app->arr_status[0],
                'app_sync'          => $this->mdl_app->arr_sync[0],
            );
        }

        $this->tplData['appRow'] = $_arr_appRow; //管理员信息

        $this->obj_tpl->tplDisplay('app_form', $this->tplData);
    }


    function ctrl_belong() {
        if (!isset($this->adminLogged['admin_allow']['app']['edit']) && !$this->is_super) {
            $this->tplData['rcode'] = 'x050303';
            $this->obj_tpl->tplDisplay('error', $this->tplData);
        }

        $_num_appId       = fn_getSafe(fn_get('app_id'), 'int', 0);
        $_str_key         = fn_getSafe(fn_get('key'), 'txt', '');
        $_str_keyBelong   = fn_getSafe(fn_get('key_belong'), 'txt', '');

        if ($_num_appId < 1) {
            $this->tplData['rcode'] = 'x050203';
            $this->obj_tpl->tplDisplay('error', $this->tplData);
        }

        $_arr_appRow = $this->mdl_app->mdl_read($_num_appId);
        if ($_arr_appRow['rcode'] != 'y050102') {
            $this->tplData['rcode'] = $_arr_appRow['rcode'];
            $this->obj_tpl->tplDisplay('error', $this->tplData);
        }

        $_arr_search = array(
            'app_id'        => $_num_appId,
            'key'           => $_str_key,
            'key_belong'    => $_str_keyBelong,
        );

        $_num_userCount   = $this->mdl_user->mdl_count($_arr_search);
        $_arr_page        = fn_page($_num_userCount); //取得分页数据
        $_str_query       = http_build_query($_arr_search);
        $_arr_userRows    = $this->mdl_user->mdl_list(BG_DEFAULT_PERPAGE, $_arr_page['except'], $_arr_search);

        $_arr_searchView = array(
            'key'        => $_str_keyBelong,
            'app_id'     => $_num_appId,
        );
        $_arr_userViews   = $this->mdl_user->mdl_list_view($_arr_searchView);

        $_arr_tpl = array(
            'query'      => $_str_query,
            'pageRow'    => $_arr_page,
            'search'     => $_arr_search,
            'userRows'   => $_arr_userRows,
            'userViews'  => $_arr_userViews,
            'appRow'     => $_arr_appRow,
        );

        $_arr_tplData = array_merge($this->tplData, $_arr_tpl);

        $this->obj_tpl->tplDisplay('app_belong', $_arr_tplData);
    }


    /*============列出管理员界面============
    无返回
    */
    function ctrl_list() {
        if (!isset($this->adminLogged['admin_allow']['app']['browse']) && !$this->is_super) {
            $this->tplData['rcode'] = 'x050301';
            $this->obj_tpl->tplDisplay('error', $this->tplData);
        }

        $_arr_search = array(
            'key'    => fn_getSafe(fn_get('key'), 'txt', ''),
            'status' => fn_getSafe(fn_get('status'), 'txt', ''),
        );

        $_num_appCount    = $this->mdl_app->mdl_count($_arr_search);
        $_arr_page        = fn_page($_num_appCount); //取得分页数据
        $_str_query       = http_build_query($_arr_search);
        $_arr_appRows     = $this->mdl_app->mdl_list(BG_DEFAULT_PERPAGE, $_arr_page['except'], $_arr_search);

        $_arr_tpl = array(
            'query'      => $_str_query,
            'pageRow'    => $_arr_page,
            'search'     => $_arr_search,
            'appRows'    => $_arr_appRows,
        );

        $_arr_tplData = array_merge($this->tplData, $_arr_tpl);

        $this->obj_tpl->tplDisplay('app_list', $_arr_tplData);
    }
}
