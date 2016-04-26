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

/*-------------管理员控制器-------------*/
class CONTROL_PROFILE {

    private $adminLogged;
    private $obj_base;
    private $config; //配置
    private $obj_tpl;
    private $tplData;

    function __construct() { //构造函数
        $this->obj_base       = $GLOBALS["obj_base"]; //获取界面类型
        $this->config         = $this->obj_base->config;
        $this->adminLogged    = $GLOBALS["adminLogged"]; //获取已登录信息
        $_arr_cfg["admin"]    = true;
        $this->obj_tpl        = new CLASS_TPL(BG_PATH_TPLSYS . "admin/" . $this->config["ui"], $_arr_cfg); //初始化视图对象
        $this->tplData = array(
            "adminLogged" => $this->adminLogged
        );
    }

    /**
     * ctl_my function.
     *
     * @access public
     * @return void
     */
    function ctl_info() {
        $this->obj_tpl->tplDisplay("profile_info.tpl", $this->tplData);

        return array(
            "alert" => "y020108",
        );
    }


    function ctl_pass() {
        $this->obj_tpl->tplDisplay("profile_pass.tpl", $this->tplData);

        return array(
            "alert" => "y020109",
        );
    }


}
