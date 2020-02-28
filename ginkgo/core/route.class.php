<?php
/*-----------------------------------------------------------------
！！！！警告！！！！
以下为系统文件，请勿修改
-----------------------------------------------------------------*/

namespace ginkgo;

//不能非法包含或直接执行
defined('IN_GINKGO') or exit('Access denied');

class Route {

    protected static $instance;

    private static $route = array(
        'mod'   => 'index',
        'ctrl'  => 'index',
        'act'   => 'index',
    );

    private static $routeOrig = array(
        'mod'   => 'index',
        'ctrl'  => 'index',
        'act'   => 'index',
    );

    private static $param;
    private static $pathOrig;
    private static $pathinfo;
    private static $url;
    private static $config;
    private static $obj_request;
    private static $urlSuffix = '.html';
    private static $init;
    private static $routeExclude = array('page');

    protected function __construct() {

    }

    protected function __clone() {

    }

    private static function init() {
        self::$obj_request  = Request::instance();
        self::$config       = Config::get('route');

        if (!Func::isEmpty(self::$config['url_suffix'])) {
            self::$urlSuffix = self::$config['url_suffix'];
        }

        if (!Func::isEmpty(self::$config['default_mod'])) {
            self::$route['mod']     = self::$config['default_mod'];
            self::$routeOrig['mod'] = self::$config['default_mod'];
        }

        if (!Func::isEmpty(self::$config['default_ctrl'])) {
            self::$route['ctrl']        = self::$config['default_ctrl'];
            self::$routeOrig['ctrl']    = self::$config['default_ctrl'];
        }

        if (!Func::isEmpty(self::$config['default_act'])) {
            self::$route['act']     = self::$config['default_act'];
            self::$routeOrig['act'] = self::$config['default_act'];
        }

        self::$init = true;
    }


    public static function get() {
        return self::$route;
    }

    public static function rule($rule = array()) {
        self::$config['route_rule'] = array_replace_recursive(self::$config['route_rule'], $rule);
    }

    public static function check() {
        if (Func::isEmpty(self::$init)) {
            self::init();
        }

        $_str_error     = '';
        $_str_detail    = '';

        self::parsePathinfo();
        self::parseRule();
        self::parseRoute();
        self::parseRouteOrig();
        self::parseParam();

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

    public static function build($path = '', $param = '', $exclude = array()) {
        if (Func::isEmpty($path)) {
            $_str_path  = Func::fixDs(self::$obj_request->baseUrl(), '/') . implode('/', self::$routeOrig);
        } else {
            $_str_path  = $path;
        }

        $_str_path = Func::fixDs($_str_path, '/');

        $_arr_routeExclude  = self::$routeExclude;

        if (!Func::isEmpty($exclude)) {
            if (Func::isEmpty($_arr_routeExclude)) {
                $_arr_routeExclude = $exclude;
            } else {
                $_arr_routeExclude = array_merge($_arr_routeExclude, $exclude);
            }
        }

        $_str_param = '';

        if (Func::isEmpty($param)) {
            $_arr_param  = self::$param;
        } else {
            $_arr_param  = $param;
        }

        if (!Func::isEmpty($_arr_param)) {
            foreach ($_arr_param as $_key_param=>$_value_param) {
                if (!in_array($_key_param, $_arr_routeExclude) && !Func::isEmpty($_value_param)) {
                    $_str_param .= $_key_param . '/' . $_value_param . '/';
                }
            }
        }

        $_str_path = Func::fixDs($_str_path, '/');

        /*print_r($_str_path);
        print_r('<br>');*/

        return $_str_path . $_str_param;
    }

    public static function setExclude($exclude = '') {
        if (!Func::isEmpty($exclude)) {
            if (is_array($exclude)) {
                if (Func::isEmpty($this->_bind)) {
                    self::$routeExclude = $exclude;
                } else {
                    self::$routeExclude = array_merge(self::$routeExclude, $exclude);
                }
            } else if (is_string($exclude)) {
                array_push(self::$routeExclude, $exclude);
            }
        }
    }

    private static function parseRule() {
        $_str_pathinfo  = self::$pathinfo;
        //$_arr_pathinfo  = explode('/', $_str_pathinfo);

        if (!Func::isEmpty(self::$config['route_rule'])) {
            foreach (self::$config['route_rule'] as $_key=>$_value) {
                if (is_array($_value)) {
                    if (!isset($_value[1])) {
                        $_obj_excpt = new Exception('Routing configuration error', 500);
                        $_obj_excpt->setData('err_detail', 'Missing parameter');

                        throw $_obj_excpt;
                    }

                    if (isset($_value[2])) { //正则规则
                        if (preg_match($_value[0], $_str_pathinfo, $_arr_pathinfo)) {
                            //print_r($_arr_pathinfo);

                            $_str_param = '';

                            if (is_array($_value[2])) {
                                foreach ($_value[2] as $_key_param=>$_value_param) {
                                    if (!Func::isEmpty($_value_param) && isset($_arr_pathinfo[$_key_param + 1])) {
                                        $_str_param .= $_value_param  . '/' . $_arr_pathinfo[$_key_param + 1] . '/';
                                    }
                                }
                            } else {
                                $_str_param .= $_value[2]  . '/' . $_arr_pathinfo[1] . '/';
                            }

                            $_str_param = str_replace('//', '/', $_str_param);

                            //print_r($_str_param);

                            self::$pathinfo = $_value[1] . '/' . $_str_param;
                            break;
                        }
                    } else { //动态规则
                        $_arr_rule  = explode('/', trim($_value[0], '/'));
                        $_str_rule  = '';
                        $_arr_param = array();

                        if (!Func::isEmpty($_arr_rule)) {
                            foreach ($_arr_rule as $_key_rule=>$_value_rule) {
                                if (strpos($_value_rule, ':') === false) {
                                    $_str_rule .= $_value_rule . '/';
                                } else {
                                    $_arr_param[] = ltrim($_value_rule, ':');
                                }
                            }
                        }

                        //print_r($_arr_param);

                        $_str_rule = str_replace('//', '/', $_str_rule);

                        /*print_r($_str_rule);
                        print_r('<br>');*/

                        if (strripos($_str_pathinfo, $_str_rule) === 0) {
                            $_str_pathinfo  = str_ireplace($_str_rule, '', $_str_pathinfo);
                            $_str_pathinfo  = trim($_str_pathinfo, '/');

                            //print_r($_str_pathinfo);

                            $_arr_pathinfo  = explode('/', $_str_pathinfo);

                            $_str_param     = '';

                            foreach ($_arr_param as $_key_param=>$_value_param) {
                                if (isset($_arr_pathinfo[$_key_param]) && !Func::isEmpty($_arr_pathinfo[$_key_param])) {
                                    $_str_param .= $_value_param . '/' . $_arr_pathinfo[$_key_param] . '/';
                                }
                            }

                            $_str_param = str_replace('//', '/', $_str_param);

                            self::$pathinfo = $_value[1] . '/' . $_str_param;
                            break;
                        }
                    }
                } else { //静态规则, 简单替换
                    $_key           = Func::fixDs($_key, '/');
                    $_value         = Func::fixDs($_value, '/');
                    $_str_pathinfo  = Func::fixDs($_str_pathinfo, '/');

                    if (strripos($_str_pathinfo, $_key) === 0) {
                        self::$pathinfo = str_ireplace($_key, $_value, $_str_pathinfo);
                        break;
                    }
                }
            }
        }

        //print_r(self::$pathinfo);
    }


    private static function parsePathinfo() {
        $_str_pathinfo = self::$obj_request->server('PATH_INFO'); //直接使用 pathinfo
        if (Func::isEmpty($_str_pathinfo)) {
            $_str_pathinfo = self::$obj_request->get('pathname'); //不支持 pathinfo 的处理
        }
        $_str_pathinfo = str_replace('\\', '/', $_str_pathinfo);
        $_str_pathinfo = trim($_str_pathinfo, '/');
        $_str_pathinfo = Html::decode($_str_pathinfo, 'url');

        $_arr_suffix = explode(',', self::$urlSuffix);

        foreach ($_arr_suffix as $_key=>$_value) {
            $_str_pathinfo = str_ireplace($_value, '', $_str_pathinfo); //去除后缀
        }

        $_str_pathinfo = trim($_str_pathinfo, '/');

        //print_r($_str_pathinfo);

        self::$pathinfo = $_str_pathinfo;
        self::$pathOrig = $_str_pathinfo;

        return $_str_pathinfo;
    }


    private static function parseRouteOrig() {
        $_str_pathinfo = self::$pathOrig;

        $_arr_url       = array();
        $_arr_routeOrig = self::$routeOrig;

        if (!Func::isEmpty($_str_pathinfo)) {
            $_arr_url = explode('/', $_str_pathinfo);

            if (isset($_arr_url[0]) && !Func::isEmpty($_arr_url[0])) {
                $_arr_routeOrig['mod']  = $_arr_url[0];
            }

            if (isset($_arr_url[1]) && !Func::isEmpty($_arr_url[1])) {
                $_arr_routeOrig['ctrl']  = $_arr_url[1];
            }

            if (isset($_arr_url[2]) && !Func::isEmpty($_arr_url[2])) {
                 $_arr_routeOrig['act'] = $_arr_url[2];
            }
        }

        self::$routeOrig = array_replace_recursive(self::$routeOrig, $_arr_routeOrig);
    }


    private static function parseRoute() {
        $_str_pathinfo = self::$pathinfo;

        $_arr_url   = array();
        $_arr_route = self::$route;

        if (defined('GK_BIND_MOD')) {
            $_arr_route['mod']  = GK_BIND_MOD;
        }

        if (!Func::isEmpty($_str_pathinfo)) {
            $_arr_url = explode('/', $_str_pathinfo);

            if (defined('GK_BIND_MOD')) {
                if (isset($_arr_url[0]) && !Func::isEmpty($_arr_url[0])) {
                    $_arr_route['ctrl']  = $_arr_url[0];
                }

                if (isset($_arr_url[1]) && !Func::isEmpty($_arr_url[1])) {
                     $_arr_route['act'] = $_arr_url[1];
                }
            } else {
                if (isset($_arr_url[0]) && !Func::isEmpty($_arr_url[0])) {
                    $_arr_route['mod']  = $_arr_url[0];
                }

                if (isset($_arr_url[1]) && !Func::isEmpty($_arr_url[1])) {
                    $_arr_route['ctrl']  = $_arr_url[1];
                }

                if (isset($_arr_url[2]) && !Func::isEmpty($_arr_url[2])) {
                     $_arr_route['act'] = $_arr_url[2];
                }
            }
        }

        $_arr_route['mod']  = str_replace('-', '_', $_arr_route['mod']);
        $_arr_route['ctrl'] = str_replace('-', '_', $_arr_route['ctrl']);
        $_arr_route['act']  = str_replace('_', '-', $_arr_route['act']);
        $_arr_route['act']  = Func::toHump($_arr_route['act'], '-', true);

        self::$route = array_replace_recursive(self::$route, $_arr_route);

        self::$url   = $_arr_url;
    }


    private static function parseParam() {
        $_arr_url = self::$url;

        if (isset($_arr_url[0])) {
            unset($_arr_url[0]);
        }

        if (isset($_arr_url[1])) {
            unset($_arr_url[1]);
        }

        if (!defined('GK_BIND_MOD')) {
            if (isset($_arr_url[2])) {
                unset($_arr_url[2]);
            }
        }

        $_arr_url = array_values($_arr_url);

        $_arr_key   = array();
        $_arr_value = array();
        $_arr_param = array();

        if (!Func::isEmpty($_arr_url)) {
            foreach ($_arr_url as $_key=>$_value) {
                //$_value = (string)$_value;

                if (Func::isOdd($_key)) {
                    $_arr_value[] = $_value;
                } else {
                    $_arr_key[] = $_value;
                }
            }
        }

        foreach ($_arr_key as $_key=>$_value) {
            if (!Func::isEmpty($_value)) {
                if (isset($_arr_value[$_key])) {
                    $_arr_param[$_value] = $_arr_value[$_key];
                    $_GET[$_value] = $_arr_value[$_key];
                } else {
                    $_arr_param[$_value] = '';
                    $_GET[$_value] = '';
                }
            }
        }

        self::$param = $_arr_param;
    }


    private static function parseRequest() {
        self::$route['mod']     = self::$obj_request->request('m');
        self::$route['ctrl']    = self::$obj_request->request('c');
        self::$route['act']     = self::$obj_request->request('a');
    }


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


