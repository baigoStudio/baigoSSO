<?php
/*-----------------------------------------------------------------
！！！！警告！！！！
以下为系统文件，请勿修改
-----------------------------------------------------------------*/

//不能非法包含或直接执行
if(!defined("IN_BAIGO")) {
    exit("Access Denied");
}

include_once(BG_PATH_CLASS . "tpl.class.php"); //载入模板类
include_once(BG_PATH_MODEL . "verify.class.php"); //载入管理帐号模型
include_once(BG_PATH_MODEL . "user.class.php"); //载入管理帐号模型

/*-------------管理员控制器-------------*/
class CONTROL_VERIFY {

    private $adminLogged;
    private $obj_base;
    private $config; //配置
    private $obj_tpl;
    private $mdl_verify;
    private $tplData;

    function __construct() { //构造函数
        $this->obj_base     = $GLOBALS["obj_base"];
        $this->config       = $this->obj_base->config;
        $this->adminLogged  = $GLOBALS["adminLogged"]; //获取已登录信息
        $this->mdl_verify   = new MODEL_VERIFY();
        $this->mdl_user     = new MODEL_USER();
        $_arr_cfg["admin"]  = true;
        $this->obj_tpl        = new CLASS_TPL(BG_PATH_TPLSYS . "admin/" . $this->config["ui"], $_arr_cfg); //初始化视图对象
        $this->tplData = array(
            "adminLogged" => $this->adminLogged
        );
    }


    function ctl_show() {
        if (!isset($this->adminLogged["admin_allow"]["log"]["verify"])) {
            return array(
                "alert" => "x120301",
            );
        }

        $_num_verifyId = fn_getSafe(fn_get("verify_id"), "int", 0);

        if ($_num_verifyId < 1) {
            return array(
                "alert" => "x120201",
            );
        }

        $_arr_verifyRow = $this->mdl_verify->mdl_read($_num_verifyId);

        if ($_arr_verifyRow["alert"] != "y120102") {
            return $_arr_verifyRow;
        }

        $_arr_verifyRow["userRow"] = $this->mdl_user->mdl_read($_arr_verifyRow["verify_user_id"]);

        $_arr_tpl = array(
            "verifyRow"    => $_arr_verifyRow,
        );

        $_arr_tplData = array_merge($this->tplData, $_arr_tpl);

        $this->obj_tpl->tplDisplay("verify_show.tpl", $_arr_tplData);

        return array(
            "alert" => "y120302",
        );
    }

    /**
     * ctl_list function.
     *
     * @access public
     * @return void
     */
    function ctl_list() {
        if (!isset($this->adminLogged["admin_allow"]["log"]["verify"])) {
            return array(
                "alert" => "x120301",
            );
        }

        $_num_verifyCount   = $this->mdl_verify->mdl_count();
        $_arr_page          = fn_page($_num_verifyCount); //取得分页数据
        $_arr_verifyRows    = $this->mdl_verify->mdl_list(BG_DEFAULT_PERPAGE, $_arr_page["except"]);

        foreach ($_arr_verifyRows as $_key=>$_value) {
            $_arr_verifyRows[$_key]["userRow"] = $this->mdl_user->mdl_read($_value["verify_user_id"]);
        }

        $_arr_tpl = array(
            "pageRow"       => $_arr_page,
            "verifyRows"    => $_arr_verifyRows,
        );

        $_arr_tplData = array_merge($this->tplData, $_arr_tpl);

        $this->obj_tpl->tplDisplay("verify_list.tpl", $_arr_tplData);

        return array(
            "alert" => "y120302",
        );
    }
}
