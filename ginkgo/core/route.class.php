<?php
/*-----------------------------------------------------------------
！！！！警告！！！！
以下为系统文件，请勿修改
-----------------------------------------------------------------*/

namespace ginkgo;

// 不能非法包含或直接执行
if (!defined('IN_GINKGO')) {
    return 'Access denied';
}

// 路由处理
class Route {

    public static $config = array(); // 配置

    // 默认路由
    public static $route = array(
        'mod'   => 'index',
        'ctrl'  => 'index',
        'act'   => 'index',
    );

    // 默认原始路由
    public static $routeOrig = array(
        'mod'   => 'index',
        'ctrl'  => 'index',
        'act'   => 'index',
    );

    public static $param = array(); // 参数

    public static $pathInfo; // pathInfo
    public static $pathOrig; // 原始 pathInfo
    public static $pathArr; // pathInfo 解析后的路径数组
    public static $routeExclude = array('page'); // 排除参数

    private static $configThis = array(
        'url_suffix'   => '.html', // URL 后缀
        'route_rule'   => array(), // 路由规则
        'default_mod'  => '', // 默认模块 (默认为 index)
        'default_ctrl' => '', // 默认控制器 (默认为 index)
        'default_act'  => '', // 默认动作 (默认为 index)
    );

    private static $obj_request; // 请求实例
    private static $init; // 是否初始化标志


    // 配置 since 0.2.0
    public static function config($config = array()) {
        $_arr_config   = Config::get('route'); // 取得配置

        $_arr_configDo = self::$configThis;

        if (is_array($_arr_config) && !Func::isEmpty($_arr_config)) {
            $_arr_configDo = array_replace_recursive($_arr_configDo, $_arr_config); // 合并配置
        }

        if (is_array(self::$config) && !Func::isEmpty(self::$config)) {
            $_arr_configDo = array_replace_recursive($_arr_configDo, self::$config); // 合并配置
        }

        if (is_array($config) && !Func::isEmpty($config)) {
            $_arr_configDo = array_replace_recursive($_arr_configDo, $config); // 合并配置
        }

        self::$config  = $_arr_configDo;
    }


    /** 取得路由
     * get function.
     *
     * @access public
     * @static
     * @return void
     */
    public static function get($name = '') {
        $_return = '';

        if (Func::isEmpty($name)) {
            $_return = self::$route;
        } else if (isset(self::$route[$name])) {
            $_return = self::$route[$name];
        }

        return $_return;
    }

    /** 设置路由规则
     * rule function.
     *
     * @access public
     * @static
     * @param array $rule (default: array())
     * @return void
     */
    public static function rule($rule, $value = '') {
        if (is_array($rule)) {
            self::$config['route_rule'] = array_replace_recursive(self::$config['route_rule'], $rule);
        } else {
            self::$config['route_rule'][$rule] = $value;
        }
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

        self::pathInfoProcess(); // 解析 pathInfo
        self::ruleProcess(); // 解析规则
        self::routeProcess(); // 解析路由
        self::routeOrigProcess(); // 解析原始路由
        self::paramProcess(); // 解析参数

        if (self::validateRoute() === false) {
            $_obj_excpt = new Exception('Not a valid route', 404);
            $_obj_excpt->setData('err_detail', self::$pathInfo);

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
    public static function build($path = '', $param = array(), $exclude = '') {
        if (Func::isEmpty($path)) { // 如果路径为空, 则取得当前原始路径
            $path  = self::$obj_request->baseUrl() . implode('/', self::$routeOrig);
        }

        $_arr_routeExclude  = self::$routeExclude; // 排除参数

        if (!Func::isEmpty($exclude)) {
            if (is_array($exclude)) {
                $_arr_routeExclude   = array_merge($_arr_routeExclude, $exclude); // 合并排除参数
            } else if (is_string($exclude)) {
                array_push($_arr_routeExclude, $exclude);
            }
        }

        $_arr_routeExclude = Arrays::filter($_arr_routeExclude);

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

        self::$routeExclude = Arrays::filter(self::$routeExclude);
    }


    /** 初始化
     * init function.
     *
     * @access private
     * @static
     * @return void
     */
    private static function init($config = array()) {
        self::$obj_request  = Request::instance();

        self::config($config);

        $_arr_route     = array();
        $_arr_routeOrig = array();

        if (!Func::isEmpty(self::$config['default_mod'])) {
            $_arr_route['mod']     = self::$config['default_mod'];
            $_arr_routeOrig['mod'] = self::$config['default_mod'];
        }

        if (!Func::isEmpty(self::$config['default_ctrl'])) {
            $_arr_route['ctrl']        = self::$config['default_ctrl'];
            $_arr_routeOrig['ctrl']    = self::$config['default_ctrl'];
        }

        if (!Func::isEmpty(self::$config['default_act'])) {
            $_arr_route['act']     = self::$config['default_act'];
            $_arr_routeOrig['act'] = self::$config['default_act'];
        }

        self::$route        = array_replace_recursive(self::$route, $_arr_route);
        self::$routeOrig    = array_replace_recursive(self::$routeOrig, $_arr_routeOrig);
        self::$init         = true; // 标识为已初始化
    }


    /// pathInfo 处理
    private static function pathInfoProcess() {
        $_str_pathInfo = self::$obj_request->server('PATH_INFO'); //直接使用 pathInfo
        if (Func::isEmpty($_str_pathInfo)) {
            $_str_pathInfo = self::$obj_request->get('pathname'); //不支持 pathInfo 的处理
        }
        $_str_pathInfo = str_replace('\\', '/', $_str_pathInfo); // 替换分隔符
        $_str_pathInfo = trim($_str_pathInfo, '/'); // 去除两边多余的分隔符
        $_str_pathInfo = Html::decode($_str_pathInfo, 'url'); // html 解码

        $_arr_suffix = explode(',', self::$config['url_suffix']); // 分离后缀配置

        foreach ($_arr_suffix as $_key=>$_value) { // 遍历后缀配置
            $_str_pathInfo = str_ireplace($_value, '', $_str_pathInfo); // 去除后缀
        }

        $_str_pathInfo = trim($_str_pathInfo, '/'); // 去除两边多余的分隔符

        //print_r($_str_pathInfo);

        self::$pathInfo = $_str_pathInfo; // 定义 pathInfo
        self::$pathOrig = $_str_pathInfo; // 定义原始 pathInfo
    }


    // 规则处理
    private static function ruleProcess() {
        $_str_pathInfo  = self::$pathInfo;
        //$_arr_pathInfo  = explode('/', $_str_pathInfo);

        if (!Func::isEmpty(self::$config['route_rule'])) {
            foreach (self::$config['route_rule'] as $_key=>$_value) { // 遍历规则
                if (strpos($_key, '/') !== false && strpos($_key, '$') !== false) { // 正则规则
                    if (preg_match($_key, $_str_pathInfo, $_arr_pathInfo) && is_array($_value) && isset($_value[0]) && isset($_value[1])) {
                        self::regexRuleProcess($_arr_pathInfo, $_value[0], $_value[1]);
                        break;
                    }
                } else if (strpos($_key, '/:') !== false) { // 动态规则
                    if (self::activeRuleProcess($_key, $_value)) {
                        break;
                    }
                } else {
                    if (is_array($_value) && isset($_value[0]) && isset($_value[1])) { // 如果是数组
                        if (strpos($_value[0], '/') !== false && strpos($_value[0], '$') !== false) { // 正则规则
                            if (preg_match($_value[0], $_str_pathInfo, $_arr_pathInfo) && isset($_value[2])) { // 正则规则
                                self::regexRuleProcess($_arr_pathInfo, $_value[1], $_value[2]);
                                break; // 匹配到第一条就跳出遍历
                            }
                        } else if (strpos($_value[0], '/:') !== false) { // 动态规则
                            if (self::activeRuleProcess($_value[0], $_value[1])) {
                                break; // 匹配到第一条就跳出遍历
                            }
                        }
                    } else if (is_string($_key) && is_string($_value)) { // 静态规则, 简单替换
                        if (self::staticRuleProcess($_key, $_value)) {
                            break; // 匹配到第一条就跳出遍历
                        }
                    }
                }
            }
        }

        //print_r(self::$pathInfo);
    }


    // 路由处理
    private static function routeProcess() {
        $_str_pathInfo = self::$pathInfo;
        $_arr_path     = array();
        $_arr_route    = self::$route;

        if (defined('GK_BIND_MOD')) {
            $_arr_route['mod']  = GK_BIND_MOD;
        }

        if (!Func::isEmpty($_str_pathInfo)) {
            $_arr_path = explode('/', $_str_pathInfo); // 分解 pathInfo

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
        $_arr_route['act']  = Strings::toHump($_arr_route['act'], '-', true);

        // 合并路由
        self::$route = array_replace_recursive(self::$route, $_arr_route);

        // 定义路径数组
        self::$pathArr = $_arr_path;
    }


    // 原始路由处理
    private static function routeOrigProcess() {
        $_str_pathInfo  = self::$pathOrig;

        $_arr_path      = array();
        $_arr_routeOrig = self::$routeOrig;

        if (!Func::isEmpty($_str_pathInfo)) {
            $_arr_path = explode('/', $_str_pathInfo); // 分解 pathInfo

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
        $_arr_path = self::$pathArr;

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
        $_arr_path  = array_values($_arr_path);

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


    // 静态规则处理 since 0.2.0
    private static function staticRuleProcess($rule, $value) {
        $_str_pathInfo = Func::fixDs(self::$pathInfo, '/');
        $rule          = Func::fixDs($rule, '/');
        $value         = Func::fixDs($value, '/');

        if (strripos($_str_pathInfo, $rule) === 0) {
            self::$pathInfo = str_ireplace($rule, $value, $_str_pathInfo); // 简单替换
            return true; // 匹配到就返回
        }

        return false;
    }


    // 静态规则处理 since 0.2.0
    private static function activeRuleProcess($rule, $value) {
        $_str_pathInfo = Func::fixDs(self::$pathInfo, '/');

        $_arr_rule  = explode('/', trim($rule, '/')); // 分解参数规则
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

        if (strripos($_str_pathInfo, $_str_rule) === 0) { // pathInfo 是否匹配规则
            $_str_pathInfo  = str_ireplace($_str_rule, '', $_str_pathInfo);
            $_str_pathInfo  = trim($_str_pathInfo, '/');

            //print_r($_str_pathInfo);

            $_arr_pathInfo  = explode('/', $_str_pathInfo);

            $_str_param     = '';

            foreach ($_arr_param as $_key_param=>$_value_param) { // 遍历参数
                if (isset($_arr_pathInfo[$_key_param]) && !Func::isEmpty($_arr_pathInfo[$_key_param])) { // 参数是否存在
                    $_str_param .= $_value_param . '/' . $_arr_pathInfo[$_key_param] . '/';
                }
            }

            $_str_param = str_replace('//', '/', $_str_param); // 替换多余分隔符

            self::$pathInfo = $value . '/' . $_str_param; // 拼合
            return true; // 匹配到就返回
        }

        return false;
    }


    // 正则规则处理 since 0.2.0
    private static function regexRuleProcess($pathArr, $value, $param) {
        $_str_param = '';

        if (is_array($param)) { // 正则解析多个参数
            foreach ($param as $_key_param=>$_value_param) { // 遍历参数规则
                if (!Func::isEmpty($_value_param) && isset($pathArr[$_key_param + 1])) {
                    $_str_param .= $_value_param  . '/' . $pathArr[$_key_param + 1] . '/'; // 拼合
                }
            }
        } else if (is_string($param)) { // 单个参数
            $_str_param .= $param  . '/' . $pathArr[1] . '/'; // 拼合
        }

        $_str_param = str_replace('//', '/', $_str_param); // 替换多余分隔符

        //print_r($_str_param);

        self::$pathInfo = $value . '/' . $_str_param; // 拼合
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
