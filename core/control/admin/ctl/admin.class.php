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

/*-------------管理员控制器-------------*/
class CONTROL_ADMIN {

    private $adminLogged;
    private $obj_base;
    private $config; //配置
    private $obj_tpl;
    private $mdl_admin;
    private $tplData;
    private $is_super = false;

    function __construct() { //构造函数
        $this->obj_base       = $GLOBALS["obj_base"]; //获取界面类型
        $this->config         = $this->obj_base->config;
        $this->adminLogged    = $GLOBALS["adminLogged"]; //获取已登录信息
        $this->mdl_admin      = new MODEL_ADMIN(); //设置管理员模型
        $_arr_cfg["admin"]    = true;
        $this->obj_tpl        = new CLASS_TPL(BG_PATH_TPLSYS . "admin/" . BG_DEFAULT_UI, $_arr_cfg); //初始化视图对象
        $this->tplData = array(
            "adminLogged" => $this->adminLogged
        );

        if ($this->adminLogged["admin_type"] == "super") {
            $this->is_super = true;
        }
    }


    function ctl_show() {
        $_num_adminId = fn_getSafe(fn_get("admin_id"), "int", 0); //get 获取 admin_id

        if (!isset($this->adminLogged["admin_allow"]["admin"]["browse"]) && !$this->is_super) { //判断权限
            return array(
                "alert" => "x020303",
            );
        }
        $_arr_adminRow = $this->mdl_admin->mdl_read($_num_adminId); //读取
        if ($_arr_adminRow["alert"] != "y020102") {
            return $_arr_adminRow;
        }

        $this->tplData["adminRow"] = $_arr_adminRow; //管理员信息

        $this->obj_tpl->tplDisplay("admin_show.tpl", $this->tplData); //显示

        return array(
            "alert" => "y020102",
        );
    }


    /**
     * ctl_form function.
     *
     * @access public
     * @return void
     */
    function ctl_form() {
        $_num_adminId = fn_getSafe(fn_get("admin_id"), "int", 0); //get 获取 admin_id

        if ($_num_adminId > 0) {
            if (!isset($this->adminLogged["admin_allow"]["admin"]["edit"]) && !$this->is_super) { //判断权限
                return array(
                    "alert" => "x020303",
                );
            }
            if ($_num_adminId == $this->adminLogged["admin_id"] && !$this->is_super) {
                return array(
                    "alert" => "x020306",
                );
            }
            $_arr_adminRow = $this->mdl_admin->mdl_read($_num_adminId);
            if ($_arr_adminRow["alert"] != "y020102") {
                return $_arr_adminRow;
            }
        } else {
            if (!isset($this->adminLogged["admin_allow"]["admin"]["add"]) && !$this->is_super) { //判断权限
                return array(
                    "alert" => "x020302",
                );
            }
            $_arr_adminRow = array(
                "admin_id"      => 0,
                "admin_nick"    => "",
                "admin_note"    => "",
                "admin_status"  => "enable",
                "admin_type"    => "normal",
            );
        }

        $this->tplData["adminRow"] = $_arr_adminRow; //管理员信息

        $this->obj_tpl->tplDisplay("admin_form.tpl", $this->tplData);

        return array(
            "alert" => "y020102",
        );
    }


    /**
     * ctl_list function.
     *
     * @access public
     * @return void
     */
    function ctl_list() {
        if (!isset($this->adminLogged["admin_allow"]["admin"]["browse"]) && !$this->is_super) { //判断权限
            return array(
                "alert" => "x020301",
            );
        }

        $_arr_search = array(
            "key"       => fn_getSafe(fn_get("key"), "txt", ""), //搜索关键词
            "status"    => fn_getSafe(fn_get("status"), "txt", ""), //搜索状态
            "type"      => fn_getSafe(fn_get("type"), "txt", ""), //搜索状态
        );

        $_num_adminCount  = $this->mdl_admin->mdl_count($_arr_search); //统计记录数
        $_arr_page        = fn_page($_num_adminCount); //取得分页数据
        $_str_query       = http_build_query($_arr_search); //将搜索参数拼合成查询串
        $_arr_adminRows   = $this->mdl_admin->mdl_list(BG_DEFAULT_PERPAGE, $_arr_page["except"], $_arr_search); //列出

        $_arr_tpl = array(
            "query"      => $_str_query,
            "pageRow"    => $_arr_page,
            "search"     => $_arr_search,
            "adminRows"  => $_arr_adminRows,
        );

        $_arr_tplData = array_merge($this->tplData, $_arr_tpl);

        $this->obj_tpl->tplDisplay("admin_list.tpl", $_arr_tplData);

        return array(
            "alert" => "y020302",
        );
    }
}