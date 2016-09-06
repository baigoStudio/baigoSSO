<?php
/*-----------------------------------------------------------------
！！！！警告！！！！
以下为系统文件，请勿修改
-----------------------------------------------------------------*/

//不能非法包含或直接执行
if (!defined("IN_BAIGO")) {
    exit("Access Denied");
}

include_once(BG_PATH_CLASS . "tpl.class.php"); //载入模板类
include_once(BG_PATH_MODEL . "user.class.php"); //载入管理帐号模型

/*-------------管理员控制器-------------*/
class CONTROL_USER {

    private $adminLogged;
    private $obj_base;
    private $config; //配置
    private $obj_tpl;
    private $mdl_user;
    private $tplData;
    private $is_super = false;

    function __construct() { //构造函数
        $this->obj_base       = $GLOBALS["obj_base"]; //获取界面类型
        $this->config         = $this->obj_base->config;
        $this->adminLogged    = $GLOBALS["adminLogged"]; //获取已登录信息
        $this->mdl_user       = new MODEL_USER(); //设置管理员模型
        $_arr_cfg["admin"]    = true;
        $this->obj_tpl        = new CLASS_TPL(BG_PATH_TPLSYS . "admin/" . BG_DEFAULT_UI, $_arr_cfg); //初始化视图对象
        $this->tplData = array(
            "adminLogged" => $this->adminLogged
        );

        if ($this->adminLogged["admin_type"] == "super") {
            $this->is_super = true;
        }
    }


    function ctl_import() {
        if (!isset($this->adminLogged["admin_allow"]["user"]["import"]) && !$this->is_super) {
            return array(
                "alert" => "x010305",
            );
        }

        $_arr_csvRows = $this->mdl_user->mdl_import();

        //print_r(stream_get_filters());
        //print_r($_arr_csvRows);

        $_arr_tpl = array(
            "csvRows" => $_arr_csvRows,
        );

        $_arr_tplData = array_merge($this->tplData, $_arr_tpl);

        $this->obj_tpl->tplDisplay("user_import.tpl", $_arr_tplData);

        return array(
            "alert" => "y010305",
        );
    }


    function ctl_form() {
        $_num_userId  = fn_getSafe(fn_get("user_id"), "int", 0);

        if ($_num_userId > 0) {
            if (!isset($this->adminLogged["admin_allow"]["user"]["edit"]) && !$this->is_super) {
                return array(
                    "alert" => "x010303",
                );
            }
            $_arr_userRow = $this->mdl_user->mdl_read($_num_userId);
            if ($_arr_userRow["alert"] != "y010102") {
                return $_arr_userRow;
            }
        } else {
            if (!isset($this->adminLogged["admin_allow"]["user"]["add"]) && !$this->is_super) {
                return array(
                    "alert" => "x010301",
                );
            }
            $_arr_userRow = array(
                "user_id"       => 0,
                "user_mail"     => "",
                "user_nick"     => "",
                "user_note"     => "",
                "user_status"   => "enable",
                "user_contact"  => array(),
                "user_extend"   => array(),
            );
        }

        $_arr_tpl = array(
            "userRow"    => $_arr_userRow,
        );

        $_arr_tplData = array_merge($this->tplData, $_arr_tpl);

        $this->obj_tpl->tplDisplay("user_form.tpl", $_arr_tplData);

        return array(
            "alert" => "y010102",
        );
    }

    /**
     * ctl_list function.
     *
     * @access public
     * @return void
     */
    function ctl_list() {
        if (!isset($this->adminLogged["admin_allow"]["user"]["browse"]) && !$this->is_super) {
            return array(
                "alert" => "x010301",
            );
        }

        $_arr_search = array(
            "key"        => fn_getSafe(fn_get("key"), "txt", ""),
            "status"     => fn_getSafe(fn_get("status"), "txt", ""),
        );

        $_num_userCount   = $this->mdl_user->mdl_count($_arr_search);
        $_arr_page        = fn_page($_num_userCount); //取得分页数据
        $_str_query       = http_build_query($_arr_search);
        $_arr_userRows    = $this->mdl_user->mdl_list(BG_DEFAULT_PERPAGE, $_arr_page["except"], $_arr_search);

        $_arr_tpl = array(
            "query"      => $_str_query,
            "pageRow"    => $_arr_page,
            "search"     => $_arr_search,
            "userRows"   => $_arr_userRows,
        );

        $_arr_tplData = array_merge($this->tplData, $_arr_tpl);

        $this->obj_tpl->tplDisplay("user_list.tpl", $_arr_tplData);

        return array(
            "alert" => "y010302",
        );
    }
}
