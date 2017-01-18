<?php
/*-----------------------------------------------------------------
！！！！警告！！！！
以下为系统文件，请勿修改
-----------------------------------------------------------------*/

//不能非法包含或直接执行
if (!defined("IN_BAIGO")) {
    exit("Access Denied");
}

/*-------------管理员控制器-------------*/
class CONTROL_CONSOLE_UI_USER {

    private $is_super = false;

    function __construct() { //构造函数
        $this->obj_base         = $GLOBALS["obj_base"]; //获取界面类型
        $this->config           = $this->obj_base->config;

        $this->obj_console      = new CLASS_CONSOLE();
        $this->obj_console->chk_install();

        $this->adminLogged      = $this->obj_console->ssin_begin(); //获取已登录信息
        $this->obj_console->is_admin($this->adminLogged);

        $this->obj_tpl          = $this->obj_console->obj_tpl;

        $this->tplData = array(
            "adminLogged" => $this->adminLogged
        );

        if ($this->adminLogged["admin_type"] == "super") {
            $this->is_super = true;
        }

        $this->mdl_user                 = new MODEL_USER(); //设置管理员模型

        $this->charsetRows              = require(BG_PATH_LANG . $this->config["lang"] . "/charset.php");
        $this->charsetOften             = array_keys($this->charsetRows["often"]["list"]);
        $this->charsetList              = array_keys($this->charsetRows["list"]["list"]);
        $this->charsetKeys              = array_unique(array_merge($this->charsetOften, $this->charsetList));
        $this->mdl_user->charsetKeys    = $this->charsetKeys;
    }


    function ctrl_import() {
        if (!isset($this->adminLogged["admin_allow"]["user"]["import"]) && !$this->is_super) {
            $this->tplData["rcode"] = "x010305";
            $this->obj_tpl->tplDisplay("error", $this->tplData);
        }

        $_str_charset = fn_getSafe(fn_get("charset"), "txt", "");

        $_str_charset = fn_htmlcode($_str_charset, "decode", "url");

        $_arr_csvRows = $this->mdl_user->mdl_import($_str_charset);

        //print_r(stream_get_filters());
        //print_r($_arr_csvRows);

        $_arr_tpl = array(
            "charset"       => $_str_charset,
            "csvRows"       => $_arr_csvRows,
            "charsetRows"   => $this->charsetRows,
        );

        $_arr_tplData = array_merge($this->tplData, $_arr_tpl);

        $this->obj_tpl->tplDisplay("user_import", $_arr_tplData);
    }


    function ctrl_form() {
        $_num_userId  = fn_getSafe(fn_get("user_id"), "int", 0);

        if ($_num_userId > 0) {
            if (!isset($this->adminLogged["admin_allow"]["user"]["edit"]) && !$this->is_super) {
                $this->tplData["rcode"] = "x010303";
                $this->obj_tpl->tplDisplay("error", $this->tplData);
            }
            $_arr_userRow = $this->mdl_user->mdl_read($_num_userId);
            if ($_arr_userRow["rcode"] != "y010102") {
                $this->tplData["rcode"] = $_arr_userRow["rcode"];
                $this->obj_tpl->tplDisplay("error", $this->tplData);
            }
        } else {
            if (!isset($this->adminLogged["admin_allow"]["user"]["add"]) && !$this->is_super) {
                $this->tplData["rcode"] = "x010302";
                $this->obj_tpl->tplDisplay("error", $this->tplData);
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

        $this->obj_tpl->tplDisplay("user_form", $_arr_tplData);
    }

    /**
     * ctrl_list function.
     *
     * @access public
     * @return void
     */
    function ctrl_list() {
        if (!isset($this->adminLogged["admin_allow"]["user"]["browse"]) && !$this->is_super) {
            $this->tplData["rcode"] = "x010301";
            $this->obj_tpl->tplDisplay("error", $this->tplData);
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

        $this->obj_tpl->tplDisplay("user_list", $_arr_tplData);
    }

}
