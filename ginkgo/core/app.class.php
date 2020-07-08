<?php
/*-----------------------------------------------------------------
！！！！警告！！！！
以下为系统文件，请勿修改
-----------------------------------------------------------------*/

namespace ginkgo;

// 不能非法包含或直接执行
defined('IN_GINKGO') or exit('Access denied');

// 应用调度
class App {

    private static $obj_request; // 请求实例
    private static $obj_lang; // 语言实例
    private static $route; // 路由
    private static $init; // 是否初始化标志

    protected function __construct() { }

    protected function __clone() { }

    public static function init() {
        self::$obj_request  = Request::instance();
        self::$obj_lang     = Lang::instance();

        self::configProcess(); // 配置处理
        self::langProcess(); // 语言处理

        Plugin::listen('action_fw_init'); // 框架初始化时触发

        self::$init = true; // 标识为已初始化
    }


    /** 运行 app
     * run function.
     *
     * @access public
     * @static
     * @return 响应实例
     */
    public static function run() {
        $_arr_route     = Route::check(); // 验证路由
        self::$route    = $_arr_route['route'];

        if (Func::isEmpty(self::$init)) {
            self::init();
        }

        $_arr_configDefault = Config::get('var_default'); // 获取默认配置

        self::setTimezone($_arr_configDefault['timezone']); // 设置时区

        Loader::load(GK_PATH_APP . 'common' . GK_EXT); // 载入应用通用文件

        self::extraProcess(); // 扩展函数处理

        $_obj_ctrl = Loader::ctrl($_arr_route['route']['ctrl'], '', true, $_arr_route['param']); // 实例化控制器

        if (!method_exists($_obj_ctrl, $_arr_route['route']['act'])) { // 空动作处理
            if (method_exists($_obj_ctrl, 'a_empty')) { // 假如存在空动作, 则初始化
                $_arr_route['route']['act'] = 'a_empty';
            } else { // 否则抛出错误
                unset($_obj_ctrl);

                $_obj_excpt = new Exception('Action not found', 404);
                $_obj_excpt->setData('err_detail', $_arr_route['route']['mod'] . '->' . $_arr_route['route']['ctrl'] . '->' . $_arr_route['route']['act']);

                throw $_obj_excpt;
            }
        }

        $_mix_content = call_user_func(array($_obj_ctrl, $_arr_route['route']['act']), $_arr_route['param']); // 执行动作

        unset($_obj_ctrl);

        Loader::clearInstance(); // 清空实例

        //print_r($_mix_content);

        // 判断动作执行以后返回的类型
        if ($_mix_content instanceof Response) { // 返回的为响应实例
            $_obj_response = $_mix_content;
        } else { //返回的是普通数据
            if (self::$obj_request->isAjax()) { // 如果请求为 ajax
                if (Func::isEmpty($_arr_configDefault['return_type_ajax'])) { // 根据配置处理返回类型
                    $_str_type = $_arr_configDefault['return_type'];
                } else {
                    $_str_type = $_arr_configDefault['return_type_ajax'];
                }
            } else {
                $_str_type = self::$obj_request->type(); // 取得返回类型
            }

            $_obj_response = Response::create($_mix_content, $_str_type); // 实例化响应类
        }

        return $_obj_response; // 返回响应实例
    }


    // 配置处理
    private static function configProcess() {
        $_arr_configExtra = Config::get('config_extra'); // 获取哪些扩展配置需要载入

        if (is_array($_arr_configExtra) && !Func::isEmpty($_arr_configExtra)) {
            foreach ($_arr_configExtra as $_key=>$_value) {
                if ($_value === true || $_value === 'true') { // 载入
                    $_str_pathExtra = GK_APP_CONFIG . 'extra_' . $_key . GK_EXT_INC;

                    Config::load($_str_pathExtra, $_key, 'var_extra');
                }
            }
        }

        // 载入模块配置
        $_str_pathCommon = GK_APP_CONFIG . self::$route['mod'] . DS . 'common' . GK_EXT_INC;
        Config::load($_str_pathCommon, self::$route['mod']);

        // 载入控制器配置
        $_str_pathCtrl = GK_APP_CONFIG . self::$route['mod'] . DS . self::$route['ctrl'] . GK_EXT_INC;
        Config::load($_str_pathCtrl, self::$route['mod'], self::$route['ctrl']);

        // 载入插件注册配置
        $_str_pathPlugin = GK_APP_CONFIG . 'plugin' . GK_EXT_INC;
        Config::load($_str_pathPlugin, 'plugin');
    }

    // 扩展函数处理
    private static function extraProcess() {
        $_arr_funcExtra = Config::get('func_extra'); // 获取扩展函数配置

        if (is_array($_arr_funcExtra) && !Func::isEmpty($_arr_funcExtra)) { // 载入扩展函数
            foreach ($_arr_funcExtra as $_key=>$_value) {
                Loader::load($_value);
            }
        }
    }


    // 语言处理
    private static function langProcess() {
        $_current = self::$obj_lang->getCurrent(); // 当前语言
        self::$obj_lang->range(self::$route['mod'] . '.' . self::$route['ctrl']); // 设置默认语言作用域

        // 载入模块语言
        $_str_pathCommon = GK_APP_LANG . $_current . DS . self::$route['mod'] . DS . 'common' . GK_EXT_LANG;
        self::$obj_lang->load($_str_pathCommon, self::$route['mod'] . '.common');

        // 载入控制器语言
        $_str_pathCtrl = GK_APP_LANG . $_current . DS . self::$route['mod'] . DS . self::$route['ctrl'] . GK_EXT_LANG;
        self::$obj_lang->load($_str_pathCtrl, self::$route['mod'] . '.' . self::$route['ctrl']);
    }


    /** 设置时区
     * setTimezone function.
     *
     * @access public
     * @static
     * @param string $timezone (default: '') 时区字符串
     * @return void
     */
    public static function setTimezone($timezone = '') {
        $timezone = (string)$timezone; //转换为字符串类型

        if (!Func::isEmpty($timezone)) {
            date_default_timezone_set($timezone);
        }
    }
}


