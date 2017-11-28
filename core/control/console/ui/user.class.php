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
class CONTROL_CONSOLE_UI_USER {

    private $is_super = false;

    function __construct() { //构造函数
        $this->config   = $GLOBALS['obj_base']->config;

        $this->general_console      = new GENERAL_CONSOLE();
        $this->general_console->chk_install();

        $this->adminLogged      = $this->general_console->ssin_begin(); //获取已登录信息
        $this->general_console->is_admin($this->adminLogged);

        $this->obj_tpl          = $this->general_console->obj_tpl;

        if ($this->adminLogged['admin_type'] == 'super') {
            $this->is_super = true;
        }

        $this->mdl_user         = new MODEL_USER(); //设置管理员模型
        $this->mdl_user_import  = new MODEL_USER_IMPORT(); //设置管理员模型
        $this->mdl_app          = new MODEL_APP();
        $this->mdl_belong       = new MODEL_BELONG();

        $this->charsetRows              = fn_include(BG_PATH_LANG . $this->config['lang'] . DS . 'charset.php');
        $_arr_charsetOften              = array_keys($this->charsetRows['often']['list']);
        $_arr_charsetList               = array_keys($this->charsetRows['list']['list']);
        $this->mdl_user->charsetKeys    = array_filter(array_unique(array_merge($_arr_charsetOften, $_arr_charsetList)));

        $this->tplData = array(
            'adminLogged'   => $this->adminLogged,
            'status'        => $this->mdl_user->arr_status,
        );
    }


    function ctrl_import() {
        if (!isset($this->adminLogged['admin_allow']['user']['import']) && !$this->is_super) {
            $this->tplData['rcode'] = 'x010305';
            $this->obj_tpl->tplDisplay('error', $this->tplData);
        }

        $_str_charset = fn_getSafe(fn_get('charset'), 'txt', '');

        $_str_charset = fn_htmlcode($_str_charset, 'decode', 'url');

        $_arr_csvRows = $this->mdl_user_import->mdl_import($_str_charset);

        //print_r(stream_get_filters());
        //print_r($_arr_csvRows);

        $_arr_tpl = array(
            'charset'       => $_str_charset,
            'csvRows'       => $_arr_csvRows,
            'charsetRows'   => $this->charsetRows,
        );

        $_arr_tplData = array_merge($this->tplData, $_arr_tpl);

        $this->obj_tpl->tplDisplay('user_import', $_arr_tplData);
    }


    function ctrl_show() {
        if (!isset($this->adminLogged['admin_allow']['user']['import']) && !$this->is_super) {
            $this->tplData['rcode'] = 'x010301';
            $this->obj_tpl->tplDisplay('error', $this->tplData);
        }

        $_num_userId  = fn_getSafe(fn_get('user_id'), 'int', 0);
        if ($_num_userId < 1) {
            $this->tplData['rcode'] = 'x010217';
            $this->obj_tpl->tplDisplay('error', $this->tplData);
        }

        $_arr_userRow = $this->mdl_user->mdl_read($_num_userId);
        if ($_arr_userRow['rcode'] != 'y010102') {
            $this->tplData['rcode'] = $_arr_userRow['rcode'];
            $this->obj_tpl->tplDisplay('error', $this->tplData);
        }

        $_arr_appRow = $this->mdl_app->mdl_read($_arr_userRow['user_app_id']);

        $_arr_searchBelong = array(
            'belong_user_id' => $_num_userId,
        );
        $_arr_belongRows   = $this->mdl_belong->mdl_list(1000, 0, $_arr_searchBelong);

        $_arr_appRows = array();

        foreach ($_arr_belongRows as $_key=>$_value) {
            $_arr_belongAppIds[] = $_value['belong_app_id'];
        }

        $_arr_belongAppIds = array_filter(array_unique($_arr_belongAppIds));

        foreach ($_arr_belongAppIds as $_key=>$_value) {
            $_arr_appRows[] = $this->mdl_app->mdl_read($_value);
        }

        $_arr_tpl = array(
            'appRow'    => $_arr_appRow,
            'appRows'   => $_arr_appRows,
            'userRow'   => $_arr_userRow,
        );

        $_arr_tplData = array_merge($this->tplData, $_arr_tpl);

        $this->obj_tpl->tplDisplay('user_show', $_arr_tplData);
    }


    function ctrl_form() {
        $_num_userId  = fn_getSafe(fn_get('user_id'), 'int', 0);

        if ($_num_userId > 0) {
            if (!isset($this->adminLogged['admin_allow']['user']['edit']) && !$this->is_super) {
                $this->tplData['rcode'] = 'x010303';
                $this->obj_tpl->tplDisplay('error', $this->tplData);
            }
            $_arr_userRow = $this->mdl_user->mdl_read($_num_userId);
            if ($_arr_userRow['rcode'] != 'y010102') {
                $this->tplData['rcode'] = $_arr_userRow['rcode'];
                $this->obj_tpl->tplDisplay('error', $this->tplData);
            }
        } else {
            if (!isset($this->adminLogged['admin_allow']['user']['add']) && !$this->is_super) {
                $this->tplData['rcode'] = 'x010302';
                $this->obj_tpl->tplDisplay('error', $this->tplData);
            }

            $_arr_userRow = array(
                'user_id'       => 0,
                'user_mail'     => '',
                'user_nick'     => '',
                'user_note'     => '',
                'user_status'   => $this->mdl_user->arr_status[0],
                'user_contact'  => array(),
                'user_extend'   => array(),
            );
        }

        $_arr_tpl = array(
            'userRow'    => $_arr_userRow,
        );

        $_arr_tplData = array_merge($this->tplData, $_arr_tpl);

        $this->obj_tpl->tplDisplay('user_form', $_arr_tplData);
    }

    /**
     * ctrl_list function.
     *
     * @access public
     * @return void
     */
    function ctrl_list() {
        if (!isset($this->adminLogged['admin_allow']['user']['browse']) && !$this->is_super) {
            $this->tplData['rcode'] = 'x010301';
            $this->obj_tpl->tplDisplay('error', $this->tplData);
        }

        $_arr_search = array(
            'key'        => fn_getSafe(fn_get('key'), 'txt', ''),
            'status'     => fn_getSafe(fn_get('status'), 'txt', ''),
        );

        $_num_userCount   = $this->mdl_user->mdl_count($_arr_search);
        $_arr_page        = fn_page($_num_userCount); //取得分页数据
        $_str_query       = http_build_query($_arr_search);
        $_arr_userRows    = $this->mdl_user->mdl_list(BG_DEFAULT_PERPAGE, $_arr_page['except'], $_arr_search);

        $_arr_tpl = array(
            'query'      => $_str_query,
            'pageRow'    => $_arr_page,
            'search'     => $_arr_search,
            'userRows'   => $_arr_userRows,
        );

        $_arr_tplData = array_merge($this->tplData, $_arr_tpl);

        $this->obj_tpl->tplDisplay('user_list', $_arr_tplData);
    }

}
