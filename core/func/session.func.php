<?php
/*-----------------------------------------------------------------
！！！！警告！！！！
以下为系统文件，请勿修改
-----------------------------------------------------------------*/

//不能非法包含或直接执行
if(!defined("IN_BAIGO")) {
    exit("Access Denied");
}

include_once(BG_PATH_MODEL . "admin.class.php"); //载入管理帐号模型


/*============验证 session, 并获取用户信息============
返回数组
    admin_id ID
    admin_open_label OPEN ID
    admin_open_site OPEN 站点
    admin_note 备注
    group_allow 权限
    str_alert 提示信息
*/
function fn_ssin_begin() {
    $_mdl_admin = new MODEL_ADMIN(); //设置管理员模型

    $_num_adminTimeDiff = fn_session("admin_ssin_time") + BG_DEFAULT_SESSION; //session有效期

    if (!fn_session("admin_id") || !fn_session("admin_ssin_time") || !fn_session("admin_hash") || $_num_adminTimeDiff < time()) {
        fn_ssin_end();
        $_arr_adminRow["alert"] = "x020401";
        return $_arr_adminRow;
        exit;
    }

    $_arr_adminRow = $_mdl_admin->mdl_read(fn_session("admin_id"));

    //print_r($_arr_adminRow);

    if (fn_baigoEncrypt($_arr_adminRow["admin_time"], $_arr_adminRow["admin_rand"]) != fn_session("admin_hash")){
        fn_ssin_end();
        $_arr_adminRow["alert"] = "x020403";
        return $_arr_adminRow;
        exit;
    }

    fn_session("admin_ssin_time", "mk", time());

    return $_arr_adminRow;
}


/** 结束登录 session
 * fn_ssin_end function.
 *
 * @access public
 * @return void
 */
function fn_ssin_end() {
    fn_session("admin_id", "unset");
    fn_session("admin_ssin_time", "unset");
    fn_session("admin_hash", "unset");
}
