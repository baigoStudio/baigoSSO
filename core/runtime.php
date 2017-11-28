<?php
/*-----------------------------------------------------------------
！！！！警告！！！！
以下为系统文件，请勿修改
-----------------------------------------------------------------*/

//不能非法包含或直接执行
if (!defined('IN_BAIGO')) {
    exit('Access Denied');
}

function fn_include($str_path, $type = 'require') {
    if (file_exists($str_path)) {
        switch ($type) {
            case 'include':
                return include($str_path);
            break;

            case 'include_once':
                return include_once($str_path);
            break;

            case 'require_once':
                return require_once($str_path);
            break;

            default:
                return require($str_path);
            break;
        }
    } else {
        if (defined('BG_DEBUG_SYS') && BG_DEBUG_SYS > 0) {
            $_str_msg = 'File &quot;' . $str_path . '&quot; not exists';
        } else {
            $_str_msg = 'File not exists';
        }

        exit('{"rcode":"x","msg":"Fatal Error: ' . $_str_msg . '!"}');
    }
}

fn_include(BG_PATH_CONFIG . 'opt_dbconfig.inc.php'); //数据库配置
fn_include(BG_PATH_CONFIG . 'opt_base.inc.php'); //基础配置
fn_include(BG_PATH_CONFIG . 'opt_reg.inc.php'); //注册配置
fn_include(BG_PATH_CONFIG . 'opt_smtp.inc.php'); //SMTP 配置

if (!defined("BG_SITE_TIMEZONE")) {
    define("BG_SITE_TIMEZONE", 'Asia/Shanghai');
}

if (!defined('BG_SITE_TPL')) {
    define('BG_SITE_TPL', 'default');
}

if (!defined('BG_PATH_LIB')) {
    define('BG_PATH_LIB', BG_PATH_CORE . DS . 'lib' . DS);
}

if (!defined('BG_SMTP_TYPE')) {
    define('BG_SMTP_TYPE', 'smtp');
}

if (!defined('BG_SMTP_SEC')) {
    define('BG_SMTP_SEC', 'off');
}

if (!defined('BG_DB_PORT')) {
    define('BG_DB_PORT', '3306');
}

fn_include(BG_PATH_INC . 'version.inc.php'); //版本
fn_include(BG_PATH_FUNC . 'common.func.php'); //载入通用函数
fn_include(BG_PATH_FUNC . 'validate.func.php'); //载入表单验证函数

$GLOBALS['method']  = strtolower(fn_server('REQUEST_METHOD'));
$GLOBALS['view']    = fn_getSafe(fn_request('view'), 'txt', ''); //界面 (是否 iframe)

class CLASS_RUNTIME {

    public $route;

    //运行
    function __construct() { //构造函数
        //自动载入
        spl_autoload_register(array($this, 'autoload'), true, true);

        //路由分析
        $this->callHook();
    }


    function run($arr_set) {
        $this->arr_set = $arr_set;

        //设置错误报告
        $this->setReport();

        //验证 PHP 版本
        $this->chkPHP();

        if (isset($this->arr_set['db'])) { //连接数据库
            $this->setDatabase();
        }

        if (isset($this->arr_set['ssin'])) {  //启动会话
            $this->setSession();
        }

        if (isset($this->arr_set['base'])) {
            $GLOBALS['obj_base'] = new CLASS_BASE(); //初始化基类
        }
    }

    //主请求方法，主要目的是拆分URL请求
    private function callHook() {
        switch (BG_APP) {
            case 'help':
                $GLOBALS['route']['bg_mod']   = fn_getSafe(fn_request('mod'), 'txt', 'intro');
                $GLOBALS['route']['bg_act']   = fn_getSafe(fn_request('act'), 'txt', 'outline');
            break;

            case 'install':
                $GLOBALS['route']['bg_mod']   = fn_getSafe(fn_request('mod'), 'txt', 'setup');
                $GLOBALS['route']['bg_act']   = fn_getSafe(fn_request('act'), 'txt', 'phplib');
            break;

            default:
                $GLOBALS['route']['bg_mod']   = fn_getSafe(fn_request('mod'), 'txt', 'user');
                $GLOBALS['route']['bg_act']   = fn_getSafe(fn_request('act'), 'txt', 'list');
            break;
        }

        if (BG_APP == 'help') {
            $GLOBALS['route']['bg_mod'] = 'help';
        }

        $_arr_routeAllow    = fn_include(BG_PATH_INC . 'route.inc.php'); //允许的 app、类型、模块
        if (file_exists(BG_PATH_CONFIG . 'route.inc.php')) {
            $_arr_routeExt = fn_include(BG_PATH_CONFIG . 'route.inc.php'); //扩展的 app、类型、模块
        }

        if (!array_key_exists(BG_APP, $_arr_routeAllow)) {
            exit('{"rcode":"x","msg":"Fatal Error: Not Allowed App!"}');
        }

        if (!array_key_exists(BG_TYPE, $_arr_routeAllow[BG_APP])) {
            exit('{"rcode":"x","msg":"Fatal Error: Not Allowed Type!"}');
        }

        if (!in_array($GLOBALS['route']['bg_mod'], $_arr_routeAllow[BG_APP][BG_TYPE])) {
            exit('{"rcode":"x","msg":"Fatal Error: Not Allowed Module!"}');
        }
    }

    private function setReport() {
        if (defined('BG_DEBUG_SYS') && BG_DEBUG_SYS > 0) {
            error_reporting(E_ALL);
        } else {
            error_reporting(0);
        }
    }

    private function chkPHP() {
        if (version_compare(PHP_VERSION, '5.3.0', '<')) { //php 版本 5.3.0 或以上
            exit('{"rcode":"x","msg":"Fatal Error: PHP version requires at least 5.3.0!"}');
        }
    }

    private function autoload($str_className) {
        $_arr_class = explode('_', strtolower($str_className));
        switch ($_arr_class[0]) {
            case 'class':
                fn_include(BG_PATH_CLASS . $_arr_class[1] . '.class.php'); //载入类
            break;
            case 'model':
                if (isset($_arr_class[2]) && !fn_isEmpty($_arr_class[2])) {
                    fn_include(BG_PATH_MODEL . $_arr_class[1] . '_' . $_arr_class[2] . '.class.php'); //载入数据模型
                } else {
                    fn_include(BG_PATH_MODEL . $_arr_class[1] . '.class.php'); //载入数据模型
                }
            break;
            case 'general':
                fn_include(BG_PATH_CONTROL . $_arr_class[1] . DS . 'general.class.php'); //载入类
            break;
            case 'control':
                if (isset($_arr_class[3]) && !fn_isEmpty($_arr_class[3])) {
                    fn_include(BG_PATH_CONTROL . $_arr_class[1] . DS . $_arr_class[2] . DS . $_arr_class[3] . '.class.php'); //载入数据模型
                } else {
                    fn_include(BG_PATH_CONTROL . $_arr_class[1] . DS . $_arr_class[2] . '.class.php'); //载入数据模型
                }
            break;
        }
    }

    private function setDatabase() {
        $_cfg_host = array(
            'host'      => BG_DB_HOST,
            'name'      => BG_DB_NAME,
            'user'      => BG_DB_USER,
            'pass'      => BG_DB_PASS,
            'charset'   => BG_DB_CHARSET,
            'debug'     => BG_DEBUG_DB,
            'port'      => BG_DB_PORT,
        );

        $GLOBALS['obj_db'] = new CLASS_DATABASE($_cfg_host); //设置数据库对象
    }

    private function setSession() {
        if (isset($this->arr_set['ssin_file'])) {
            if (fn_isEmpty(ini_get('session.save_path'))) {
                ini_set('session.save_path', BG_PATH_CACHE . 'ssin');
            }
        } else {
            $_mdl_session = new MODEL_SESSION();
            session_set_save_handler(array(&$_mdl_session, 'mdl_open'), array(&$_mdl_session, 'mdl_close'), array(&$_mdl_session, 'mdl_read'), array(&$_mdl_session, 'mdl_write'), array(&$_mdl_session, 'mdl_destroy'), array(&$_mdl_session, 'mdl_gc'));
        }

        session_start(); //开启session
    }
}

$obj_runtime = new CLASS_RUNTIME(); //调度

//载入模块
if (file_exists(BG_PATH_MODULE . BG_APP . DS . BG_TYPE . DS . $GLOBALS['route']['bg_mod'] . '.mod.php')) {
    require(BG_PATH_MODULE . BG_APP . DS . BG_TYPE . DS . $GLOBALS['route']['bg_mod'] . '.mod.php');
} else {
    if (defined('BG_DEBUG_SYS') && BG_DEBUG_SYS > 0) {
        $_str_msg = 'Module &quot;' . BG_PATH_MODULE . BG_APP . DS . BG_TYPE . DS . $GLOBALS['route']['bg_mod'] . '.mod.php&quot; not exists';
    } else {
        $_str_msg = 'Module not exists';
    }

    exit('{"rcode":"x","msg":"Fatal Error: ' . $_str_msg . '!"}');
}

