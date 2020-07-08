<?php
/*-----------------------------------------------------------------
！！！！警告！！！！
以下为系统文件，请勿修改
-----------------------------------------------------------------*/

namespace ginkgo;

// 不能非法包含或直接执行
defined('IN_GINKGO') or exit('Access denied');

// 路由处理
class Route {

    // 默认路由
    private static $route = array(
        'mod'   => 'index',
        'ctrl'  => 'index',
        'act'   => 'index',
    );

    // 默认原始路由
    private static $routeOrig = array(
        'mod'   => 'index',
        'ctrl'  => 'index',
        'act'   => 'index',
    );

    private static $param = array(); // 参数

    private static $pathOrig; // 原始 pathinfo
    private static $pathinfo; // pathinfo
    private static $arr_path; // pathinfo 解析后的路径数组
    private static $config; // 配置
    private static $obj_request; // 请求实例
    private static $urlSuffix = '.html'; // url 后缀
    private static $init; // 是否初始化标志
    private static $routeExclude = array('page'); // 排除参数

    protected function __construct() {

    }

    protected function __clone() {

    }

    /** 初始化
     * init function.
     *
     * @access private
     * @static
     * @return void
     */
    private static function init() {
        self::$obj_request  = Request::instance();
        $_arr_config        = Config::get('route'); // 取得路由配置

        $_arr_route     = array();
        $_arr_routeOrig = array();

        if (!Func::isEmpty($_arr_config['url_suffix'])) {
            self::$urlSuffix = $_arr_config['url_suffix'];
        }

        if (!Func::isEmpty($_arr_config['default_mod'])) {
            $_arr_route['mod']     = $_arr_config['default_mod'];
            $_arr_routeOrig['mod'] = $_arr_config['default_mod'];
        }

        if (!Func::isEmpty($_arr_config['default_ctrl'])) {
            $_arr_route['ctrl']        = $_arr_config['default_ctrl'];
            $_arr_routeOrig['ctrl']    = $_arr_config['default_ctrl'];
        }

        if (!Func::isEmpty($_arr_config['default_act'])) {
            $_arr_route['act']     = $_arr_config['default_act'];
            $_arr_routeOrig['act'] = $_arr_config['default_act'];
        }

        self::$route        = array_replace_recursive(self::$route, $_arr_route);
        self::$routeOrig    = array_replace_recursive(self::$routeOrig, $_arr_routeOrig);
        self::$config       = $_arr_config;
        self::$init         = true; // 标识为已初始化
    }


    /** 取得路由
     * get function.
     *
     * @access public
     * @static
     * @return void
     */
    public static function get() {
        return self::$route;
    }

    /** 设置路由规则
     * rule function.
     *
     * @access public
     * @static
     * @param array $rule (default: array())
     * @return void
     */
    public static function rule($rule = array()) {
        self::$config['route_rule'] = array_replace_recursive(self::$config['route_rule'], $rule);
    }

    /** 解析路由
     * check function.
     *
     * @access public
     * @static
     * @return 路由信息数组
     */
    public static function check() {
        if (Func::isEmpty(self::$init)) {
            self::init();
        }

        $_str_error     = '';
        $_str_detail    = '';

        self::pathinfoProcess(); // 解析 pathinfo
        self::ruleProcess(); // 解析规则
        self::routeProcess(); // 解析路由
        self::routeOrigProcess(); // 解析原始路由
        self::paramProcess(); // 解析参数

        if (self::validateRoute() === false) {
            $_obj_excpt = new Exception('Not a valid route', 404);
            $_obj_excpt->setData('err_detail', self::$pathinfo);

            throw $_obj_excpt;
        }

        self::$obj_request->route       = self::$route;
        self::$obj_request->routeOrig   = self::$routeOrig;
        self::$obj_request->param       = self::$param;

        return array(
            'route'     => self::$route,
            'routeOrig' => self::$routeOrig,
            'param'     => self::$param,
        );
    }


    /** 建立路由
     * build function.
     *
     * @access public
     * @static
     * @param string $path (default: '') 路径
     * @param string $param (default: '') 参数
     * @param array $exclude (default: array()) 排除参数
     * @return void
     */
    public static function build($path = '', $param = '', $exclude = array()) {
        if (Func::isEmpty($path)) { // 如果路径为空, 则取得当前原始路径
            $path  = self::$obj_request->baseUrl() . implode('/', self::$routeOrig);
        }

        $_arr_routeExclude  = self::$routeExclude; // 排除参数

        if (!Func::isEmpty($exclude)) {
            $_arr_routeExclude = array_merge($_arr_routeExclude, $exclude); // 合并排除参数
        }

        $_str_param = '';

        if (Func::isEmpty($param)) {
            $param  = self::$param; // 如果没有传入参数, 则直接使用路由中的参数
        }

        if (!Func::isEmpty($param)) {
            foreach ($param as $_key_param=>$_value_param) { // 遍历
                if (!Func::isEmpty($_value_param) && !in_array($_key_param, $_arr_routeExclude)) { // 如果参数值不为空且不在排除范围内, 则拼合
                    $_str_param .= '/' . $_key_param . '/' . $_value_param;
                }
            }
        }

        //print_r($_str_param);

        return Func::fixDs($path . $_str_param); // 拼合路径和参数并补全分隔符
    }

    /** 设置排除参数
     * setExclude function.
     *
     * @access public
     * @static
     * @param string $exclude (default: '')
     * @return void
     */
    public static function setExclude($exclude = '') {
        if (!Func::isEmpty($exclude)) {
            if (is_array($exclude)) { // 如果为数组, 则合并
                self::$routeExclude = array_merge(self::$routeExclude, $exclude);
            } else if (is_string($exclude)) { // 如果为字符, 则 push
                array_push(self::$routeExclude, $exclude);
            }
        }
    }


    /// pathinfo 处理
    private static function pathinfoProcess() {
        $_str_pathinfo = self::$obj_request->server('PATH_INFO'); //直接使用 pathinfo
        if (Func::isEmpty($_str_pathinfo)) {
            $_str_pathinfo = self::$obj_request->get('pathname'); //不支持 pathinfo 的处理
        }
        $_str_pathinfo = str_replace('\\', '/', $_str_pathinfo); // 替换分隔符
        $_str_pathinfo = trim($_str_pathinfo, '/'); // 去除两边多余的分隔符
        $_str_pathinfo = Html::decode($_str_pathinfo, 'url'); // html 解码

        $_arr_suffix = explode(',', self::$urlSuffix); // 分离后缀配置

        foreach ($_arr_suffix as $_key=>$_value) { // 遍历后缀配置
            $_str_pathinfo = str_ireplace($_value, '', $_str_pathinfo); // 去除后缀
        }

        $_str_pathinfo = trim($_str_pathinfo, '/'); // 去除两边多余的分隔符

        //print_r($_str_pathinfo);

        self::$pathinfo = $_str_pathinfo; // 定义 pathinfo
        self::$pathOrig = $_str_pathinfo; // 定义原始 pathinfo
    }


    // 规则处理
    private static function ruleProcess() {
        $_str_pathinfo  = self::$pathinfo;
        //$_arr_pathinfo  = explode('/', $_str_pathinfo);

        if (!Func::isEmpty(self::$config['route_rule'])) {
            foreach (self::$config['route_rule'] as $_key=>$_value) { // 遍历规则
                if (is_array($_value)) { // 如果是数组
                    if (!isset($_value[1])) { // 规则格式错误
                        $_obj_excpt = new Exception('Routing configuration error', 500);
                        $_obj_excpt->setData('err_detail', 'Missing parameter');

                        throw $_obj_excpt;
                    }

                    if (isset($_value[2])) { // 正则规则
                        if (preg_match($_value[0], $_str_pathinfo, $_arr_pathinfo)) {
                            //print_r($_arr_pathinfo);

                            $_str_param = '';

                            if (is_array($_value[2])) { // 正则解析多个参数
                                foreach ($_value[2] as $_key_param=>$_value_param) { // 遍历参数规则
                                    if (!Func::isEmpty($_value_param) && isset($_arr_pathinfo[$_key_param + 1])) {
                                        $_str_param .= $_value_param  . '/' . $_arr_pathinfo[$_key_param + 1] . '/'; // 拼合
                                    }
                                }
                            } else { // 单个参数
                                $_str_param .= $_value[2]  . '/' . $_arr_pathinfo[1] . '/'; // 拼合
                            }

                            $_str_param = str_replace('//', '/', $_str_param); // 替换多余分隔符

                            //print_r($_str_param);

                            self::$pathinfo = $_value[1] . '/' . $_str_param; // 拼合
                            break; // 匹配到第一条就跳出遍历
                        }
                    } else { // 动态规则
                        $_arr_rule  = explode('/', trim($_value[0], '/')); // 分解参数规则
                        $_str_rule  = '';
                        $_arr_param = array();

                        if (!Func::isEmpty($_arr_rule)) {
                            foreach ($_arr_rule as $_key_rule=>$_value_rule) { // 遍历参数规则
                                if (strpos($_value_rule, ':') === false) { // 没有包含 : 符号的, 直接拼合
                                    $_str_rule .= $_value_rule . '/';
                                } else { // 否则去除 : 符号, 并将此作为参数名
                                    $_arr_param[] = ltrim($_value_rule, ':');
                                }
                            }
                        }

                        //print_r($_arr_param);

                        $_str_rule = str_replace('//', '/', $_str_rule); // 替换多余分隔符

                        /*print_r($_str_rule);
                        print_r('<br>');*/

                        if (strripos($_str_pathinfo, $_str_rule) === 0) { // pathinfo 是否匹配规则
                            $_str_pathinfo  = str_ireplace($_str_rule, '', $_str_pathinfo);
                            $_str_pathinfo  = trim($_str_pathinfo, '/');

                            //print_r($_str_pathinfo);

                            $_arr_pathinfo  = explode('/', $_str_pathinfo);

                            $_str_param     = '';

                            foreach ($_arr_param as $_key_param=>$_value_param) { // 遍历参数
                                if (isset($_arr_pathinfo[$_key_param]) && !Func::isEmpty($_arr_pathinfo[$_key_param])) { // 参数是否存在
                                    $_str_param .= $_value_param . '/' . $_arr_pathinfo[$_key_param] . '/';
                                }
                            }

                            $_str_param = str_replace('//', '/', $_str_param); // 替换多余分隔符

                            self::$pathinfo = $_value[1] . '/' . $_str_param; // 拼合
                            break; // 匹配到第一条就跳出遍历
                        }
                    }
                } else { // 静态规则, 简单替换
                    $_key           = Func::fixDs($_key, '/');
                    $_value         = Func::fixDs($_value, '/');
                    $_str_pathinfo  = Func::fixDs($_str_pathinfo, '/');

                    if (strripos($_str_pathinfo, $_key) === 0) {
                        self::$pathinfo = str_ireplace($_key, $_value, $_str_pathinfo); // 简单替换
                        break; // 匹配到第一条就跳出遍历
                    }
                }
            }
        }

        //print_r(self::$pathinfo);
    }


    // 路由处理
    private static function routeProcess() {
        $_str_pathinfo = self::$pathinfo;

        $_arr_path   = array();
        $_arr_route = self::$route;

        if (defined('GK_BIND_MOD')) {
            $_arr_route['mod']  = GK_BIND_MOD;
        }

        if (!Func::isEmpty($_str_pathinfo)) {
            $_arr_path = explode('/', $_str_pathinfo); // 分解 pathinfo

            if (defined('GK_BIND_MOD')) { // 如果定义了绑定模块, 则路由依次为 控制器->动作, 忽略模块
                if (isset($_arr_path[0]) && !Func::isEmpty($_arr_path[0])) {
                    $_arr_route['ctrl']  = $_arr_path[0];
                }

                if (isset($_arr_path[1]) && !Func::isEmpty($_arr_path[1])) {
                     $_arr_route['act'] = $_arr_path[1];
                }
            } else { // 否则路由依次为 模块->控制器->动作
                if (isset($_arr_path[0]) && !Func::isEmpty($_arr_path[0])) {
                    $_arr_route['mod']  = $_arr_path[0];
                }

                if (isset($_arr_path[1]) && !Func::isEmpty($_arr_path[1])) {
                    $_arr_route['ctrl']  = $_arr_path[1];
                }

                if (isset($_arr_path[2]) && !Func::isEmpty($_arr_path[2])) {
                     $_arr_route['act'] = $_arr_path[2];
                }
            }
        }

        // 兼容 - 符号
        $_arr_route['mod']  = str_replace('-', '_', $_arr_route['mod']);
        $_arr_route['ctrl'] = str_replace('-', '_', $_arr_route['ctrl']);
        $_arr_route['act']  = str_replace('_', '-', $_arr_route['act']);

        // 转换为下划线分隔的驼峰命名
        $_arr_route['act']  = Func::toHump($_arr_route['act'], '-', true);

        // 合并路由
        self::$route = array_replace_recursive(self::$route, $_arr_route);

        // 定义路径数组
        self::$arr_path   = $_arr_path;
    }


    // 原始路由处理
    private static function routeOrigProcess() {
        $_str_pathinfo = self::$pathOrig;

        $_arr_path       = array();
        $_arr_routeOrig = self::$routeOrig;

        if (!Func::isEmpty($_str_pathinfo)) {
            $_arr_path = explode('/', $_str_pathinfo); // 分解 pathinfo

            if (isset($_arr_path[0]) && !Func::isEmpty($_arr_path[0])) {
                $_arr_routeOrig['mod']  = $_arr_path[0];
            }

            if (isset($_arr_path[1]) && !Func::isEmpty($_arr_path[1])) {
                $_arr_routeOrig['ctrl']  = $_arr_path[1];
            }

            if (isset($_arr_path[2]) && !Func::isEmpty($_arr_path[2])) {
                 $_arr_routeOrig['act'] = $_arr_path[2];
            }
        }

        self::$routeOrig = array_replace_recursive(self::$routeOrig, $_arr_routeOrig);
    }


    // 参数处理
    private static function paramProcess() {
        $_arr_path = self::$arr_path;

        // 默认情况提出第一第二个元素
        if (isset($_arr_path[0])) {
            unset($_arr_path[0]);
        }

        if (isset($_arr_path[1])) {
            unset($_arr_path[1]);
        }

        // 如果定义了绑定模块, 则剔除第三个元素
        if (!defined('GK_BIND_MOD')) {
            if (isset($_arr_path[2])) {
                unset($_arr_path[2]);
            }
        }

        // 重置 路径 数组
        $_arr_path = array_values($_arr_path);

        $_arr_key   = array();
        $_arr_value = array();
        $_arr_param = array();

        if (!Func::isEmpty($_arr_path)) {
            foreach ($_arr_path as $_key=>$_value) { // 遍历 路径 数组
                //$_value = (string)$_value;

                if (Func::isOdd($_key)) { // 奇数作为参数值
                    $_arr_value[] = $_value;
                } else { // 偶数作为参数名
                    $_arr_key[] = $_value;
                }
            }
        }

        foreach ($_arr_key as $_key=>$_value) { // 遍历参数名
            if (!Func::isEmpty($_value)) { // 参数名不为空才拼合
                if (isset($_arr_value[$_key])) { // 如果有此参数
                    $_arr_param[$_value]    = $_arr_value[$_key]; // 加入到参数属性
                    $_GET[$_value]          = $_arr_value[$_key]; // 额外加入到 $_GET 变量
                } else { // 无此参数, 则用空值填充
                    $_arr_param[$_value]    = '';
                    $_GET[$_value]          = '';
                }
            }
        }

        self::$param = $_arr_param; // 定义参数属性
    }


    /** 验证路由是否合法
     * validateRoute function.
     *
     * @access private
     * @static
     * @return 验证结果
     */
    private static function validateRoute() {
        $_arr_rule = array(
            'mod'   => array(
                'format' => 'chs_dash'
            ),
            'ctrl'  => array(
                'format' => 'chs_dash'
            ),
            'act'   => array(
                'format' => 'chs_dash'
            ),
        );

        $_obj_validate = Validate::instance();
        $_obj_validate->rule($_arr_rule);

        return $_obj_validate->verify(self::$route);
    }
}


