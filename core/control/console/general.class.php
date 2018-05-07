<?php
/*-----------------------------------------------------------------
！！！！警告！！！！
以下为系统文件，请勿修改
-----------------------------------------------------------------*/

//不能非法包含或直接执行
if (!defined('IN_BAIGO')) {
    exit('Access Denied');
}

/*-------------控制中心通用类-------------*/
class GENERAL_CONSOLE {

    public $config;
    public $dspType = '';

    function __construct() { //构造函数
        $this->config   = $GLOBALS['obj_base']->config;
        $this->obj_dir  = new CLASS_DIR();
        $this->obj_tpl  = new CLASS_TPL(BG_PATH_TPLSYS . 'console' . DS . BG_DEFAULT_UI); //初始化视图对象

        $this->obj_tpl->opt         = $GLOBALS['obj_config']->arr_opt; //系统设置配置文件
        $this->obj_tpl->consoleMod  = fn_include(BG_PATH_INC . 'consoleMod.inc.php'); //菜单配置文件
        $this->obj_tpl->profile     = fn_include(BG_PATH_INC . 'profile.inc.php'); //菜单配置文件

        $this->obj_tpl->setModule();

        //语言文件
        $this->obj_tpl->lang = array(
            'common'        => fn_include(BG_PATH_LANG . $this->config['lang'] . DS . 'common.php'), //通用
            'opt'           => fn_include(BG_PATH_LANG . $this->config['lang'] . DS . 'opt.php'), //系统设置
            'rcode'         => fn_include(BG_PATH_LANG . $this->config['lang'] . DS . 'rcode.php'), //返回代码
            'consoleMod'    => fn_include(BG_PATH_LANG . $this->config['lang'] . DS . 'consoleMod.php'), //菜单
        );

        if (file_exists(BG_PATH_LANG . $this->config['lang'] . DS . 'console' . DS . $GLOBALS['route']['bg_mod'] . '.php')) {
            $this->obj_tpl->lang['mod'] = fn_include(BG_PATH_LANG . $this->config['lang'] . DS . 'console' . DS . $GLOBALS['route']['bg_mod'] . '.php');
        }

        $this->mdl_admin = new MODEL_ADMIN(); //设置用户模型
        $this->mdl_user  = new MODEL_USER(); //设置用户模型
    }


    /*============验证 session, 并获取用户信息============
    返回数组
        admin_id ID
        admin_open_label OPEN ID
        admin_open_site OPEN 站点
        admin_note 备注
        admin_allow 权限
        str_rcode 提示信息
    */
    function ssin_begin() {
        $_num_ssinTimeDiff      = fn_session('admin_ssin_time') + BG_DEFAULT_SESSION; //session 有效期
        $_num_cookieTimeDiff    = fn_cookie('admin_ssin_time') + BG_DEFAULT_SESSION; //session 有效期

        if (fn_isEmpty(fn_session('admin_id')) || fn_isEmpty(fn_session('admin_ssin_time')) || fn_isEmpty(fn_session('admin_hash')) || $_num_ssinTimeDiff < time() || fn_isEmpty(fn_cookie('admin_id')) || fn_isEmpty(fn_cookie('admin_ssin_time')) || fn_isEmpty(fn_cookie('admin_hash')) || $_num_cookieTimeDiff < time()) {
            $this->ssin_end();
            return array(
                'rcode'     => 'x020401',
            );
        }

        $_arr_adminRow  = $this->mdl_admin->mdl_read(fn_session('admin_id'));
        if ($_arr_adminRow['rcode'] != 'y020102') {
            $this->ssin_end();
            return $_arr_adminRow;
        }

        if ($_arr_adminRow['admin_status'] == 'disable') {
            $this->ssin_end();
            return array(
                'rcode' => 'x020402',
            );
        }

        $_arr_userRow   = $this->mdl_user->mdl_read(fn_session('admin_id'));

        if ($_arr_userRow['rcode'] != 'y010102') {
            $this->ssin_end();
            return $_arr_userRow;
        }

        if ($_arr_userRow['user_status'] == 'disable') {
            $this->ssin_end();
            return array(
                'rcode'     => 'x010401',
            );
        }

        if ($this->hash_process($_arr_adminRow) != fn_session('admin_hash') || $this->hash_process($_arr_adminRow) != fn_cookie('admin_hash')){
            $this->ssin_end();
            return array(
                'rcode'     => 'x020403',
            );
        }

        fn_session('admin_ssin_time', 'mk', time());
        fn_cookie('admin_id', 'mk', fn_session('admin_id'), 3600 * 24 * 30, BG_URL_CONSOLE);
        fn_cookie('admin_ssin_time', 'mk', time(), 3600 * 24 * 30, BG_URL_CONSOLE);
        fn_cookie('admin_hash', 'mk', fn_session('admin_hash'), 3600 * 24 * 30, BG_URL_CONSOLE);

        $_arr_adminRow['userRow'] = $_arr_userRow;

        return $_arr_adminRow;
    }


    function ssin_login($arr_loginRow) {
        fn_session('admin_id', 'mk', $arr_loginRow['admin_id']);
        fn_session('admin_ssin_time', 'mk', time());
        fn_session('admin_hash', 'mk', $this->hash_process($arr_loginRow));
        fn_cookie('admin_id', 'mk', $arr_loginRow['admin_id'], 3600 * 24 * 30, BG_URL_CONSOLE);
        fn_cookie('admin_ssin_time', 'mk', time(), 3600 * 24 * 30, BG_URL_CONSOLE);
        fn_cookie('admin_hash', 'mk', $this->hash_process($arr_loginRow), 3600 * 24 * 30, BG_URL_CONSOLE);

        return array(
            'rcode' => 'ok',
        );
    }


    /** 结束登录 session
     * fn_ssin_end function.
     *
     * @access public
     * @return void
     */
    function ssin_end() {
        fn_session('admin_id', 'unset');
        fn_session('admin_ssin_time', 'unset');
        fn_session('admin_hash', 'unset');
        fn_cookie('admin_id', 'unset', '', '', BG_URL_CONSOLE);
        fn_cookie('admin_ssin_time', 'unset', '', '', BG_URL_CONSOLE);
        fn_cookie('admin_hash', 'unset', '', '', BG_URL_CONSOLE);
    }


    function chk_install() {
        $_str_rcode = '';
        $_str_jump  = '';

        if (file_exists(BG_PATH_CONFIG . 'installed.php')) { //如果新文件存在
            fn_include(BG_PATH_CONFIG . 'installed.php'); //载入
        } else if (file_exists(BG_PATH_CONFIG . 'is_install.php')) { //如果旧文件存在
            $this->obj_dir->copy_file(BG_PATH_CONFIG . 'is_install.php', BG_PATH_CONFIG . 'installed.php'); //拷贝
            fn_include(BG_PATH_CONFIG . 'installed.php'); //载入
        } else { //如已安装文件不存在
            $_str_rcode = 'x030410';
            $_str_jump  = BG_URL_INSTALL . 'index.php';
        }

        if (defined('BG_INSTALL_PUB') && PRD_SSO_PUB > BG_INSTALL_PUB) { //如果小于当前版本
            $_str_rcode = 'x030411';
            $_str_jump  = BG_URL_INSTALL . 'index.php?m=upgrade';
        }

        if (!fn_isEmpty($_str_rcode)) {
            switch ($this->dspType) {
                case 'result':
                    $_arr_tplData = array(
                        'rcode' => $_str_rcode,
                    );
                    $this->obj_tpl->tplDisplay('result', $_arr_tplData);
                break;

                default:
                    header('Location: ' . $_str_jump);
                    exit;
                break;
            }
        }
    }


    function is_admin($arr_adminRow) {
        $_str_rcode = '';
        $_str_jump  = '';

        if ($arr_adminRow['rcode'] != 'y020102') {
            $this->ssin_end();
            $_str_rcode = $arr_adminRow['rcode'];

            if ($GLOBALS['view'] != 'iframe') {
                $_str_forwart   = fn_forward(fn_server('REQUEST_URI'));
                $_str_jump      = BG_URL_CONSOLE . 'index.php?m=login&forward=' . $_str_forwart;
            }
        }

        if (!fn_isEmpty($_str_rcode)) {
            switch ($this->dspType) {
                case 'result':
                    $_arr_tplData = array(
                        'rcode' => $_str_rcode,
                    );
                    $this->obj_tpl->tplDisplay('result', $_arr_tplData);
                break;

                default:
                    if (!fn_isEmpty($_str_jump)) {
                        header('Location: ' . $_str_jump);
                        exit;
                    }

                    $_arr_tplData = array(
                        'rcode' => $_str_rcode,
                    );
                    $this->obj_tpl->tplDisplay('error', $_arr_tplData);
                break;
            }
        }
    }

    private function hash_process($arr_adminRow) {
        return fn_baigoCrypt($arr_adminRow['admin_id'] . $arr_adminRow['admin_name'] . $arr_adminRow['admin_time_login'], $arr_adminRow['admin_ip']);
    }
}
