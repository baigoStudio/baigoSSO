<?php
/*-----------------------------------------------------------------
！！！！警告！！！！
以下为系统文件，请勿修改
-----------------------------------------------------------------*/

//不能非法包含或直接执行
if (!defined("IN_BAIGO")) {
    exit("Access Denied");
}


/*-------------控制中心通用类-------------*/
class CLASS_CONSOLE {

    public $dspType = "";

    function __construct() { //构造函数
        $this->obj_base         = $GLOBALS["obj_base"];
        $this->config           = $this->obj_base->config;
        $this->obj_dir          = new CLASS_DIR();
        $_arr_cfg["console"]    = true;
        $this->obj_tpl          = new CLASS_TPL(BG_PATH_TPLSYS . "console/" . BG_DEFAULT_UI, $_arr_cfg); //初始化视图对象
    }


    /*============验证 session, 并获取用户信息============
    返回数组
        admin_id ID
        admin_open_label OPEN ID
        admin_open_site OPEN 站点
        admin_note 备注
        group_allow 权限
        str_rcode 提示信息
    */
    function ssin_begin() {
        $_mdl_admin = new MODEL_ADMIN(); //设置用户模型
        $_mdl_user  = new MODEL_USER(); //设置用户模型

        $_arr_adminRow  = $_mdl_admin->mdl_read(fn_session("admin_id"));
        $_arr_userRow   = $_mdl_user->mdl_read(fn_session("admin_id"));

        $_num_adminTimeDiff = fn_session("admin_ssin_time") + BG_DEFAULT_SESSION; //session 有效期

        if (fn_isEmpty(fn_session("admin_id")) || fn_isEmpty(fn_session("admin_ssin_time")) || fn_isEmpty(fn_session("admin_hash")) || $_num_adminTimeDiff < time()) {
            $this->ssin_end();
            $_arr_adminRow["rcode"] = "x020401";
            return $_arr_adminRow;
            exit;
        }

        //print_r($_arr_adminRow);

        if (fn_baigoCrypt($_arr_userRow["user_time_login"], $_arr_userRow["user_ip"]) != fn_session("admin_hash")){
            $this->ssin_end();
            $_arr_adminRow["rcode"] = "x020403";
            return $_arr_adminRow;
            exit;
        }

        fn_session("admin_ssin_time", "mk", time());

        $_arr_adminRow["userRow"] = $_arr_userRow;

        return $_arr_adminRow;
    }


    /** 结束登录 session
     * fn_ssin_end function.
     *
     * @access public
     * @return void
     */
    function ssin_end() {
        fn_session("admin_id", "unset");
        fn_session("admin_ssin_time", "unset");
        fn_session("admin_hash", "unset");
    }


    function chk_install() {
        $_str_rcode = "";
        $_str_jump  = "";

        if (file_exists(BG_PATH_CONFIG . "installed.php")) { //如果新文件存在
            require(BG_PATH_CONFIG . "installed.php"); //载入
        } else if (file_exists(BG_PATH_CONFIG . "is_install.php")) { //如果旧文件存在
            $this->obj_dir->copy_file(BG_PATH_CONFIG . "is_install.php", BG_PATH_CONFIG . "installed.php"); //拷贝
            require(BG_PATH_CONFIG . "installed.php"); //载入
        } else { //如已安装文件不存在
            $_str_rcode = "x030410";
            $_str_jump  = BG_URL_INSTALL . "index.php";
        }

        if (defined("BG_INSTALL_PUB") && PRD_SSO_PUB > BG_INSTALL_PUB) { //如果小于当前版本
            $_str_rcode = "x030411";
            $_str_jump  = BG_URL_INSTALL . "index.php?mod=upgrade";
        }

        if (!fn_isEmpty($_str_rcode)) {
            switch ($this->dspType) {
                case "result":
                case "post":
                    $_arr_tplData = array(
                        "rcode" => $_str_rcode,
                    );
                    $this->obj_tpl->tplDisplay("result", $_arr_tplData);
                break;

                default:
                    header("Location: " . $_str_jump);
                    exit;
                break;
            }
        }
    }


    function is_admin($arr_adminLogged) {
        $_str_rcode = "";
        $_str_jump  = "";

        if ($arr_adminLogged["rcode"] != "y020102") {
            $_str_rcode = $arr_adminLogged["rcode"];

            if ($GLOBALS["view"] != "iframe") {
                $_str_forwart   = fn_forward(fn_server("REQUEST_URI"));
                $_str_jump      = BG_URL_CONSOLE . "index.php?mod=login&forward=" . $_str_forwart;
            }
        }

        if (!fn_isEmpty($_str_rcode)) {
            switch ($this->dspType) {
                case "result":
                case "post":
                    $_arr_tplData = array(
                        "rcode" => $_str_rcode,
                    );
                    $this->obj_tpl->tplDisplay("result", $_arr_tplData);
                break;

                default:
                    if (!fn_isEmpty($_str_jump)) {
                        header("Location: " . $_str_jump);
                        exit;
                    }

                    $_arr_tplData = array(
                        "rcode" => $_str_rcode,
                    );
                    $this->obj_tpl->tplDisplay("error", $_arr_tplData);
                break;
            }
        }
    }
}
