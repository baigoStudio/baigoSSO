<?php
/*-----------------------------------------------------------------
！！！！警告！！！！
以下为系统文件，请勿修改
-----------------------------------------------------------------*/

namespace ginkgo;

// 不能非法包含或直接执行
defined('IN_GINKGO') or exit('Access denied');

// 框架系统视图
class View_Sys {

    /** 渲染错误模板
     * fetch function.
     *
     * @access public
     * @static
     * @param string $tpl (default: '') 模板名称
     * @param array $data (default: array()) 渲染内容
     * @return void
     */
    public static function fetch($tpl = '', $data = array()) {
        $_obj_request  = Request::instance();
        $_obj_lang     = Lang::instance();
        $_obj_lang->range('__ginkgo__');  //设置语言作用域

        $_arr_obj['lang']    = $_obj_lang;
        $_arr_obj['request'] = $_obj_request;

        $_arr_configDebug = Config::get('debug'); // 取得调试配置

        $_str_tpl = self::pathProcess($tpl);

        if ($_arr_configDebug['dump']) { // 输出调试信息
            if (!Func::isFile($_str_tpl)) {
                return '<pre>' . var_export($data, true) . '</pre>';
            }

            if (!Func::isEmpty($data)) {
                extract($data, EXTR_OVERWRITE); // 将内容数组转换为模板变量
            }
        } else {
            if (!Func::isFile($_str_tpl)) {
                return '<div>' . $data['http_status'] . '</div>';
            }
        }

        $_str_content = '';

        ob_start(); // 打开缓冲
        ob_implicit_flush(0); // 关闭绝对刷送

        if (!Func::isEmpty($_arr_obj)) {
            extract($_arr_obj, EXTR_OVERWRITE); // 将对象数组转换为模板变量
        }

        require($_str_tpl); // 载入模板文件

        $_str_content = ob_get_clean(); // 取得输出缓冲内容并清理关闭

        // 路径处理
        $_str_urlBase       = $_obj_request->baseUrl(true);
        $_str_urlRoot       = $_obj_request->root(true);
        $_str_dirRoot       = $_obj_request->root();
        $_str_routeRoot     = $_obj_request->baseUrl();

        // 模板中的替换处理
        $_arr_replaceSrc = array(
            '{:URL_BASE}',
            '{:URL_ROOT}',
            '{:DIR_ROOT}',
            '{:DIR_STATIC}',
            '{:ROUTE_ROOT}',
        );

        $_arr_replaceDst = array(
            $_str_urlBase,
            $_str_urlRoot,
            $_str_dirRoot,
            $_str_dirRoot . GK_NAME_STATIC . '/',
            $_str_routeRoot,
        );

        $_str_content = str_ireplace($_arr_replaceSrc, $_arr_replaceDst, $_str_content);

        return $_str_content; // 返回渲染后的内容
    }


    private static function pathProcess($tpl = '') {
        $_str_suffix            = GK_EXT_TPL; // 默认模板文件后缀
        $_arr_configTplSys      = Config::get('tpl_sys'); // 取得系统模板目录
        $_arr_configExceptPage  = Config::get('exception_page'); // 取得异常页配置

        if (!Func::isEmpty($_arr_configTplSys['suffix'])) { // 默认后缀
            $_str_suffix = $_arr_configTplSys['suffix'];
        }

        $_str_pathTpl   = GK_PATH_TPL;
        $_str_tpl       = $_str_pathTpl . 'exception' . $_str_suffix;

        if (!Func::isEmpty($_arr_configTplSys['path'])) { // 如果定义了模板路径, 则替换默认路径
            if (strpos($_arr_configTplSys['path'], DS) !== false) {
                $_str_pathTpl = Func::fixDs($_arr_configTplSys['path']);
            } else {
                $_str_pathTpl .= Func::fixDs($_arr_configTplSys['path']);
            }
        }

        if (!Func::isEmpty($tpl) && isset($_arr_configExceptPage[$tpl])) { // 假如定义了模板参数, 且异常页配置中有匹配的元素
            $_str_tplName = $_arr_configExceptPage[$tpl]; // 取出异常页面的定义

            if (strpos($_str_tplName, DS) !== false) { // 如果模板名中有目录分隔符, 则认为是定义了完整路径
                $_str_tpl = $_str_tplName;
            } else { // 否则用系统默认的路径和后缀补全
                $_str_tpl = $_str_pathTpl . $_str_tplName . $_str_suffix;
            }
        } else if (!Func::isEmpty($tpl)) { // 假如只定义了模板参数, 则直接用该模板
            $_str_tpl = $_str_pathTpl . $tpl . $_str_suffix; // 用系统默认的路径和后缀补全
        }

        //print_r($_str_tpl);

        $_str_ext = pathinfo($_str_tpl, PATHINFO_EXTENSION); // 取得模板路径的扩展名

        if (Func::isEmpty($_str_ext)) { // 如有没有扩展名, 用系统默认的后缀补全
            $_str_tpl .= $_str_suffix;
        }

        return $_str_tpl;
    }
}


