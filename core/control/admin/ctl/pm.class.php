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
include_once(BG_PATH_MODEL . "pm.class.php"); //载入管理帐号模型
include_once(BG_PATH_MODEL . "user.class.php"); //载入管理帐号模型

/*-------------管理员控制器-------------*/
class CONTROL_PM {

    private $adminLogged;
    private $obj_base;
    private $config; //配置
    private $obj_tpl;
    private $mdl_pm;
    private $mdl_belong;
    private $mdl_user;
    private $tplData;
    private $is_super = false;

    function __construct() { //构造函数
        $this->obj_base     = $GLOBALS["obj_base"]; //获取界面类型
        $this->config       = $this->obj_base->config;
        $this->adminLogged  = $GLOBALS["adminLogged"]; //获取已登录信息
        $this->mdl_pm       = new MODEL_PM(); //设置管理员模型
        $this->mdl_user     = new MODEL_USER(); //设置管理员模型
        $_arr_cfg["admin"]  = true;
        $this->obj_tpl      = new CLASS_TPL(BG_PATH_TPLSYS . "admin/" . BG_DEFAULT_UI, $_arr_cfg); //初始化视图对象
        $this->tplData = array(
            "adminLogged" => $this->adminLogged
        );

        if ($this->adminLogged["admin_type"] == "super") {
            $this->is_super = true;
        }
    }


    function ctl_send() {
        if (!isset($this->adminLogged["admin_allow"]["pm"]["send"]) && !$this->is_super) {
            return array(
                "alert" => "x110303",
            );
        }

        $this->obj_tpl->tplDisplay("pm_send.tpl", $this->tplData);

        return array(
            "alert" => "y110303",
        );
    }


    /*============编辑管理员界面============
    返回提示
    */
    function ctl_show() {
        if (!isset($this->adminLogged["admin_allow"]["pm"]["browse"]) && !$this->is_super) {
            return array(
                "alert" => "x110301",
            );
        }

        $_num_pmId = fn_getSafe(fn_get("pm_id"), "int", 0);
        if ($_num_pmId < 1) {
            return array(
                "alert" => "x110211",
            );
        }

        $_arr_pmRow = $this->mdl_pm->mdl_read($_num_pmId);
        if ($_arr_pmRow["alert"] != "y110102") {
            return $_arr_pmRow;
        }

        $_arr_pmRow["toUser"]   = $this->mdl_user->mdl_read($_arr_pmRow["pm_to"]);
        $_arr_pmRow["fromUser"] = $this->mdl_user->mdl_read($_arr_pmRow["pm_from"]);

        $this->tplData["pmRow"] = $_arr_pmRow;

        $this->obj_tpl->tplDisplay("pm_show.tpl", $this->tplData);

        return array(
            "alert" => "y110102",
        );
    }

    /*============群发界面============
    返回提示
    */
    function ctl_bulk() {
        if (!isset($this->adminLogged["admin_allow"]["pm"]["bulk"]) && !$this->is_super) {
            return array(
                "alert" => "x110302",
            );
        }

        $this->tplData["begin_time"]    = time() - 86400;
        $this->tplData["end_time"]      = time();

        $this->obj_tpl->tplDisplay("pm_bulk.tpl", $this->tplData);

        return array(
            "alert" => "y110102",
        );
    }


    /*============列出管理员界面============
    无返回
    */
    function ctl_list() {
        if (!isset($this->adminLogged["admin_allow"]["pm"]["browse"]) && !$this->is_super) {
            return array(
                "alert" => "x110301",
            );
        }

        $_arr_search = array(
            "key"       => fn_getSafe(fn_get("key"), "txt", ""),
            "type"      => fn_getSafe(fn_get("type"), "txt", ""),
            "status"    => fn_getSafe(fn_get("status"), "txt", ""),
            "pm_from"   => fn_getSafe(fn_get("pm_from"), "int", 0),
            "pm_to"     => fn_getSafe(fn_get("pm_to"), "int", 0),
        );

        $_num_pmCount   = $this->mdl_pm->mdl_count($_arr_search);
        $_arr_page      = fn_page($_num_pmCount); //取得分页数据
        $_str_query     = http_build_query($_arr_search);
        $_arr_pmRows    = $this->mdl_pm->mdl_list(BG_DEFAULT_PERPAGE, $_arr_page["except"], $_arr_search);

        foreach ($_arr_pmRows as $_key=>$_value) {
            $_arr_pmRows[$_key]["toUser"]   = $this->mdl_user->mdl_read($_value["pm_to"]);
            $_arr_pmRows[$_key]["fromUser"] = $this->mdl_user->mdl_read($_value["pm_from"]);
        }

        $_arr_tpl = array(
            "query"     => $_str_query,
            "pageRow"   => $_arr_page,
            "search"    => $_arr_search,
            "pmRows"    => $_arr_pmRows,
        );

        $_arr_tplData = array_merge($this->tplData, $_arr_tpl);

        $this->obj_tpl->tplDisplay("pm_list.tpl", $_arr_tplData);
        return array(
            "alert" => "y110302",
        );
    }
}
