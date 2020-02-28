<?php
/*-----------------------------------------------------------------
！！！！警告！！！！
以下为系统文件，请勿修改
-----------------------------------------------------------------*/

namespace ginkgo;


//不能非法包含或直接执行
defined('IN_GINKGO') or exit('Access denied');

class App {

    private static $obj_request;
    private static $obj_lang;
    private static $route;
    private static $config;
    private static $init;

    protected function __construct() { }

    protected function __clone() { }

    public static function init() {
        self::$obj_request  = Request::instance();
        self::$obj_lang     = Lang::instance();

        self::configProcess();
        self::langProcess();

        Plugin::listen('action_fw_init'); //框架初始化时触发

        self::$init = true;
    }

    public static function run() {
        $_arr_route     = Route::check();
        self::$route    = $_arr_route['route'];

        if (Func::isEmpty(self::$init)) {
            self::init();
        }

        $_arr_configDefault = Config::get('var_default');

        self::setTimezone($_arr_configDefault['timezone']);

        if (Func::isFile(GK_PATH_APP . 'common' . GK_EXT)) {
            Loader::load(GK_PATH_APP . 'common' . GK_EXT);
        }

        $_arr_funcExtra = Config::get('func_extra');

        if (is_array($_arr_funcExtra) && !Func::isEmpty($_arr_funcExtra)) {
            foreach ($_arr_funcExtra as $_key=>$_value) {
                if (Func::isFile($_value)) {
                    $_arr_result = Loader::load($_value);
                }
            }
        }

        $_obj_ctrl = Loader::ctrl($_arr_route['route']['ctrl'], '', true, $_arr_route['param']); //实例化控制器

        if (!method_exists($_obj_ctrl, $_arr_route['route']['act'])) {
            if (method_exists($_obj_ctrl, 'a_empty')) {
                $_arr_route['route']['act'] = 'a_empty';
            } else {
                unset($_obj_ctrl);

                $_obj_excpt = new Exception('Action not found', 404);
                $_obj_excpt->setData('err_detail', $_arr_route['route']['mod'] . '->' . $_arr_route['route']['ctrl'] . '->' . $_arr_route['route']['act']);

                throw $_obj_excpt;
            }
        }

        $_mix_content = call_user_func(array($_obj_ctrl, $_arr_route['route']['act']), $_arr_route['param']);

        unset($_obj_ctrl);

        Loader::clearInstance(); //清空实例

        //print_r($_mix_content);

        if ($_mix_content instanceof Response) {
            $_obj_response = $_mix_content;
        } else {
            if (self::$obj_request->isAjax()) {
                if (Func::isEmpty($_arr_configDefault['return_type_ajax'])) {
                    $_str_type = $_arr_configDefault['return_type'];
                } else {
                    $_str_type = $_arr_configDefault['return_type_ajax'];
                }
            } else {
                $_str_type = self::$obj_request->type();
            }

            $_obj_response = Response::create($_mix_content, $_str_type);
        }

        return $_obj_response;
    }


    private static function configProcess() {
        $_arr_configExtra = Config::get('config_extra');

        if (is_array($_arr_configExtra) && !Func::isEmpty($_arr_configExtra)) {
            foreach ($_arr_configExtra as $_key=>$_value) {
                if ($_value === true || $_value == 'true') {
                    $_str_pathExtra = GK_APP_CONFIG . 'extra_' . $_key . GK_EXT_INC;

                    if (Func::isFile($_str_pathExtra)) {
                        Config::load($_str_pathExtra, $_key, 'var_extra');
                    }
                }
            }
        }

        $_str_pathRoute  = GK_APP_CONFIG . self::$route['mod'] . DS . 'common' . GK_EXT_INC;
        $_str_pathPlugin = GK_APP_CONFIG . 'plugin' . GK_EXT_INC;

        if (Func::isFile($_str_pathRoute)) {
            Config::load($_str_pathRoute, self::$route['mod']);
        }

        if (Func::isFile($_str_pathPlugin)) {
            Config::load($_str_pathPlugin, 'plugin');
        }
    }

    private static function langProcess() {
        $_current  = self::$obj_lang->getCurrent();
        self::$obj_lang->range(self::$route['mod'] . '.' . self::$route['ctrl']);

        $_str_pathCommon = GK_APP_LANG . $_current . DS . self::$route['mod'] . DS . 'common' . GK_EXT_LANG;
        self::$obj_lang->load($_str_pathCommon, self::$route['mod'] . '.common');

        $_str_pathCtrl = GK_APP_LANG . $_current . DS . self::$route['mod'] . DS . self::$route['ctrl'] . GK_EXT_LANG;
        self::$obj_lang->load($_str_pathCtrl, self::$route['mod'] . '.' . self::$route['ctrl']);
    }


    public static function setTimezone($timezone = '') {
        if (!Func::isEmpty($timezone)) {
            date_default_timezone_set($timezone);
        }
    }
}


