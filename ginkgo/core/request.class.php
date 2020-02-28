<?php
/*-----------------------------------------------------------------
！！！！警告！！！！
以下为系统文件，请勿修改
-----------------------------------------------------------------*/

namespace ginkgo;

//不能非法包含或直接执行
defined('IN_GINKGO') or exit('Access denied');

class Request {

    protected static $instance;

    public $param = array();

    public $route = array(
        'mod'   => 'index',
        'ctrl'  => 'index',
        'act'   => 'index',
    );

    public $routeOrig = array(
        'mod'   => 'index',
        'ctrl'  => 'index',
        'act'   => 'index',
    );

    protected $mimeType = array(
        'html'  => array(
            'text/html',
            'application/xhtml+xml',
            '*/*',
        ),
        'xml'   => array(
            'application/xml',
            'text/xml',
            'application/x-xml',
        ),
        'json'  => array(
            'application/json',
            'text/x-json',
            'application/jsonrequest',
            'text/json',
        ),
        'js'    => array(
            'text/javascript',
            'application/javascript',
            'application/x-javascript',
        ),
        'css'   => array(
            'text/css',
        ),
        'rss'   => array(
            'application/rss+xml',
        ),
        'yaml'  => array(
            'application/x-yaml',
            'text/yaml',
        ),
        'atom'  => array(
            'application/atom+xml',
        ),
        'pdf'   => array(
            'application/pdf',
        ),
        'text'  => array(
            'text/plain',
        ),
        'image' => array(
            'image/png',
            'image/jpg',
            'image/jpeg',
            'image/pjpeg',
            'image/gif',
            'image/webp',
            'image/*',
        ),
        'csv'   => array(
            'text/csv',
        ),
    );

    protected function __construct() {
    }

    protected function __clone() {

    }

    public static function instance() {
        if (Func::isEmpty(static::$instance)) {
            static::$instance = new static();
        }

        return static::$instance;
    }

    function setRoute($name, $value = '') {
        $_arr_route = array();

        if (is_array($name)) {
            if (isset($name['mod'])) {
                $_arr_route['mod']  = $name['mod'];
            }

            if (isset($name['ctrl'])) {
                $_arr_route['ctrl'] = $name['ctrl'];
            }

            if (isset($name['act'])) {
                $_arr_route['act']  = $name['act'];
            }
        } else if (is_scalar($name)) {
            if (array_key_exists($name, $this->route) && Func::isEmpty($value)) {
                $_arr_route[$name] = $value;
            }
        }

        $this->route = array_replace_recursive($this->route, $_arr_route);
    }

    function setRouteOrig($name, $value = '') {
        $_arr_routeOrig = array();

        if (is_array($name)) {
            if (isset($name['mod'])) {
                $_arr_routeOrig['mod']  = $name['mod'];
            }

            if (isset($name['ctrl'])) {
                $_arr_routeOrig['ctrl'] = $name['ctrl'];
            }

            if (isset($name['act'])) {
                $_arr_routeOrig['act']  = $name['act'];
            }
        } else if (is_scalar($name)) {
            if (in_array($name, $this->routeOrig) && Func::isEmpty($value)) {
                $_arr_routeOrig[$name] = $value;
            }
        }

        $this->routeOrig = array_replace_recursive($this->routeOrig, $_arr_routeOrig);
    }

    function setParam($name, $value = '') {
        $_arr_param = array();

        if (is_array($name)) {
            foreach ($name as $_key=>$_value) {
                $_arr_param[$_key]  = $_value;
            }
        } else if (is_scalar($name)) {
            $_arr_param[$name] = $value;
        }

        $this->param = array_replace_recursive($this->param, $_arr_param);
    }

    function route($name = false) {
        $_mix_return = '';

        if ($name === false) {
            $_mix_return = $this->route;
        } else {
            if (isset($this->route[$name])) {
                $_mix_return = $this->route[$name];
            }
        }

        return $_mix_return;
    }

    function routeOrig($name = false) {
        $_mix_return = '';

        if ($name === false) {
            $_mix_return = $this->routeOrig;
        } else {
            if (isset($this->routeOrig[$name])) {
                $_mix_return = $this->routeOrig[$name];
            }
        }

        return $_mix_return;
    }

    function param($name = true, $type = 'str', $default = '', $htmlmode = false) {
        $_return    = '';

        if ($name === false) {
            $_return = $this->param;
        } else if ($name === true) {
            $_return = Func::arrayEach($this->param);
        } else if (is_array($name)) {
            $_return = $this->fillParam($this->param, $name);
        } else if (is_scalar($name)) {
            if (isset($this->param[$name])) {
                $_return = $this->param[$name];
            }

            $_return = $this->input($_return, $type, $default, $htmlmode);
        }

        return $_return;
    }

    function accept() {
        return $this->server('HTTP_ACCEPT');
    }

    function type() {
        $_str_type   = 'html';
        $_str_accept = $this->accept();

        foreach ($this->mimeType as $_key=>$_value) {
            foreach ($_value as $_key_sub=>$_value_sub) {
                if (stristr($_str_accept, $_value_sub)) {
                    $_str_type = $_key;
                    break;
                }
            }
        }

        return $_str_type;
    }

    function ip() {
        if (!Func::isEmpty($this->server('REMOTE_ADDR'))) {
            $_str_ip = $this->server('REMOTE_ADDR');
        } else if (!Func::isEmpty(getenv('REMOTE_ADDR'))) {
            $_str_ip = getenv('REMOTE_ADDR');
        } else {
            $_str_ip = '0.0.0.0';
        }

        return $_str_ip;
    }

    function method() {
        $_str_method = 'GET';

        //print_r($this->server('HTTP_X_HTTP_METHOD_OVERRIDE'));

        if (!Func::isEmpty($this->server('HTTP_X_HTTP_METHOD_OVERRIDE'))) {
            $_str_method = $this->server('HTTP_X_HTTP_METHOD_OVERRIDE');
        } else if (!Func::isEmpty($this->server('REQUEST_METHOD'))) {
            $_str_method = $this->server('REQUEST_METHOD');
        }

        return strtoupper($_str_method);
    }


    /**
     * 是否为GET请求
     * @access public
     * @return bool
     */
    public function isGet() {
        return $this->method() == 'GET';
    }

    /**
     * 是否为POST请求
     * @access public
     * @return bool
     */
    public function isPost() {
        return $this->method() == 'POST';
    }


    function isAjax() {
        $_str_value  = $this->server('HTTP_X_REQUESTED_WITH');
        $_str_value  = strtolower($_str_value);

        return $_str_value == 'xmlhttprequest';
    }

    function isPjax() {
        $_str_value = $this->server('HTTP_X_PJAX');

        return !Func::isEmpty($_str_value);
    }

    function isSsl() {
        $_status = false;

        if ($this->server('HTTPS') == '1' || strtolower($this->server('HTTPS')) == 'on') {
            $_status = true;
        } else if ($this->server('REQUEST_SCHEME') == 'https') {
            $_status = true;
        } else if ($this->server('SERVER_PORT') == '443') {
            $_status = true;
        } else if ($this->server('HTTP_X_FORWARDED_PROTO') == 'https') {
            $_status = true;
        }

        return $_status;
    }


    function isMobile() {
        $_status = false;

        if (!Func::isEmpty($this->server('HTTP_VIA')) && stristr($this->server('HTTP_VIA'), 'wap')) {
            $_status = true;
        } else if (!Func::isEmpty($this->server('HTTP_ACCEPT')) && strpos(strtoupper($this->server('HTTP_ACCEPT')), 'VND.WAP.WML')) {
            $_status = true;
        } else if (!Func::isEmpty($this->server('HTTP_X_WAP_PROFILE')) || !Func::isEmpty($this->server('HTTP_PROFILE'))) {
            $_status = true;
        } else if (!Func::isEmpty($this->server('HTTP_USER_AGENT')) && preg_match('/(blackberry|configuration\/cldc|hp |hp-|htc |htc_|htc-|iemobile|kindle|midp|mmp|motorola|mobile|nokia|opera mini|opera |Googlebot-Mobile|YahooSeeker\/M1A1-R2D2|android|iphone|ipod|mobi|palm|palmos|pocket|portalmmm|ppc;|smartphone|sonyericsson|sqh|spv|symbian|treo|up.browser|up.link|vodafone|windows ce|xda |xda_)/i', $this->server('HTTP_USER_AGENT'))) {
            $_status = true;
        }

        return $_status;
    }


    function get($name = true, $type = 'str', $default = '', $htmlmode = false) {
        $_return    = '';

        if ($name === false) {
            $_return = $_GET;
        } else if ($name === true) {
            $_return = Func::arrayEach($_GET);
        } else if (is_array($name)) {
            $_return = $this->fillParam($_GET, $name);
        } else if (is_scalar($name)) {
            if (isset($_GET[$name])) {
                $_return = $_GET[$name];
            }

            $_return = $this->input($_return, $type, $default, $htmlmode);
        }

        return $_return;
    }


    function post($name = true, $type = 'str', $default = '', $htmlmode = false) {
        $_return    = '';

        if ($name === false) {
            $_return = $_POST;
        } else if ($name === true) {
            $_return = Func::arrayEach($_POST);
        } else if (is_array($name)) {
            $_return = $this->fillParam($_POST, $name);
        } else if (is_scalar($name)) {
            if (isset($_POST[$name])) {
                $_return = $_POST[$name];
            }

            $_return = $this->input($_return, $type, $default, $htmlmode);
        }

        //print_r($_return);

        return $_return;
    }


    function request($name = true, $type = 'str', $default = '', $htmlmode = false) {
        $_return    = '';

        if ($name === false) {
            $_return = $_REQUEST;
        } else if ($name === true) {
            $_return = Func::arrayEach($_REQUEST);
        } else if (is_array($name)) {
            $_return = $this->fillParam($_REQUEST, $name);
        } else if (is_scalar($name)) {
            if (isset($_REQUEST[$name])) {
                $_return = $_REQUEST[$name];
            }

            $_return = $this->input($_return, $type, $default, $htmlmode);
        }

        return $_return;
    }


    function root($with_domain = false) {
        $_str_baseFile  = $this->baseFile();
        $_str_baseFile  = str_replace('\\', '/', dirname($_str_baseFile));
        $_str_root      = rtrim($_str_baseFile, '/\\');

        if ($with_domain) {
            $_str_root = $this->domain() . $_str_root;
        }

        return strtolower($_str_root) . '/';
    }


    function url($with_domain = false) {
        $_str_url = '';

        if (!Func::isEmpty($this->server('HTTP_X_REWRITE_URL'))) {
            $_str_url = $this->server('HTTP_X_REWRITE_URL');
        } else if (!Func::isEmpty($this->server('REDIRECT_URL'))) {
            $_str_url = $this->server('REDIRECT_URL');
        } else if (!Func::isEmpty($this->server('REQUEST_URI'))) {
            $_str_url = $this->server('REQUEST_URI');
        } else if (!Func::isEmpty($this->server('ORIG_PATH_INFO'))) {
            $_str_url = $this->server('ORIG_PATH_INFO');

            if (!Func::isEmpty($this->server('QUERY_STRING'))) {
                $_str_url .= '?' . $this->server('QUERY_STRING');
            }
        }

        if ($with_domain) {
            $_str_url = $this->domain() . $_str_url;
        }

        return strtolower($_str_url);
    }


    function domain() {
        return strtolower($this->scheme() . '://' . $this->host());
    }

    function host($strict = false) {
        $_str_host = $this->server('HTTP_HOST');

        if (!Func::isEmpty($this->server('HTTP_X_REAL_HOST'))) {
            $_str_host = $this->server('HTTP_X_REAL_HOST');
        }

        if ($strict && strpos($_str_host, ':')) {
            $_str_host = strstr($_str_host, ':', true);
        }

        return strtolower($_str_host);
    }

    function scheme() {
        return strtolower($this->isSsl() ? 'https' : 'http');
    }

    function header($name = '') {
        $_arr_header = array();

        if (function_exists('apache_request_headers')) {
            $_arr_header = apache_request_headers();
        } else {
            $_arr_server = $this->server();
            foreach ($_arr_server as $_key => $_value) {
                if (strpos($_key, 'HTTP_') === 0) {
                    $_key = str_replace('_', '-', strtolower(substr($_key, 5)));
                    $_arr_header[$_key] = $_value;
                }
            }
            if (isset($_arr_server['CONTENT_TYPE'])) {
                $_arr_header['content-type'] = $_arr_server['CONTENT_TYPE'];
            }
            if (isset($_arr_server['CONTENT_LENGTH'])) {
                $_arr_header['content-length'] = $_arr_server['CONTENT_LENGTH'];
            }
        }

        if (!Func::isEmpty($_arr_header)) {
            $_arr_header = array_change_key_case($_arr_header);
        }

        if (Func::isEmpty($name)) {
            return $_arr_header;
        }

        $name = str_replace('_', '-', strtolower($name));

        $_mix_return = '';

        if (Func::isEmpty($name)) {
            $_mix_return = $_arr_header;
        } else {
            if (isset($_arr_header[$name])) {
                $_mix_return = $_arr_header[$name];
            }
        }

        return $_mix_return;
    }

    function server($name = '') {
        $name = strtoupper($name);

        $_mix_return = '';

        if (Func::isEmpty($name)) {
            $_mix_return = $_SERVER;
        } else {
            if (isset($_SERVER[$name])) {
                $_mix_return = $_SERVER[$name];
            }
        }

        return $_mix_return;
    }

    function session($name = '') {
        $name = strtoupper($name);

        $_mix_return = '';

        if (Func::isEmpty($name)) {
            $_mix_return = $_SESSION;
        } else {
            if (isset($_SESSION[$name])) {
                $_mix_return = $_SESSION[$name];
            }
        }

        return $_mix_return;
    }

    function cookie($name = '') {
        $name = strtoupper($name);

        $_mix_return = '';

        if (Func::isEmpty($name)) {
            $_mix_return = $_COOKIE;
        } else {
            if (isset($_COOKIE[$name])) {
                $_mix_return = $_COOKIE[$name];
            }
        }

        return $_mix_return;
    }


    /**
     * token function.
     *
     * @access public
     * @param string $name (default: '__token__')
     * @param string $type (default: 'md5')
     * @return void
     */
    function token($name = '__token__', $type = 'md5', $renew = false) {
        if (!$name || Func::isEmpty($name)) {
            $_str_name = '__token__';
        } else {
            $_str_name = $name;
        }

        if (!$type || Func::isEmpty($type) || !is_callable($type)) {
            $_str_type = 'md5';
        } else {
            $_str_type = $type;
        }

        if (Func::isEmpty($this->server('REQUEST_TIME_FLOAT'))) {
            $_tm_time = GK_NOW;
        } else {
            $_tm_time = $this->server('REQUEST_TIME_FLOAT');
        }

        $_str_token  = Session::get($_str_name);

        if (Func::isEmpty($_str_token) || $renew) {
            $_str_token = call_user_func($_str_type, $_tm_time);

            Session::set($_str_name, $_str_token);
        }

        if ($this->isAjax() || $this->isPost()) {
            header($_str_name . ': ' . $_str_token);
        }

        return $_str_token;
    }


    function checkDuplicate($method = 'POST') {
        $_str_input = $this->duplicateProcess($method);
        $_str_check = $this->cookie('check_duplicate');

        if (Func::isEmpty($_str_input)) {
            return false;
        }

        if (Func::isEmpty($_str_check)) {
            return false;
        }

        return $_str_check == $_str_input;
    }


    function setDuplicate($method = 'POST') {
        $_str_input = $this->duplicateProcess($method);

        Cookie::set('check_duplicate', $_str_input);
    }


    function baseFile() {
        $_str_url = '';

        $_script_name = basename($this->server('SCRIPT_FILENAME'));

        $_str_pos = strpos($this->server('PHP_SELF'), '/' . $_script_name);

        if (basename($this->server('SCRIPT_NAME')) === $_script_name) {
            $_str_url = $this->server('SCRIPT_NAME');
        } else if (basename($this->server('PHP_SELF')) === $_script_name) {
            $_str_url = $this->server('PHP_SELF');
        } else if (!Func::isEmpty($this->server('ORIG_SCRIPT_NAME')) && basename($this->server('ORIG_SCRIPT_NAME')) === $_script_name) {
            $_str_url = $this->server('ORIG_SCRIPT_NAME');
        } else if ($_str_pos !== false) {
            $_str_url = substr($this->server('SCRIPT_NAME'), 0, $_str_pos) . '/' . $_script_name;
        } else if (!Func::isEmpty($this->server('DOCUMENT_ROOT')) && strpos($this->server('SCRIPT_FILENAME'), $this->server('DOCUMENT_ROOT')) === 0) {
            $_str_url = str_replace('\\', '/', str_ireplace($this->server('DOCUMENT_ROOT'), '', $this->server('SCRIPT_FILENAME')));
        }

        return strtolower($_str_url);
    }


    function baseUrl($with_domain = false) {
        $_arr_configRoute = Config::get('route');

        $_str_baseFile  = $this->baseFile();
        $_str_url       = $this->url();

        $_str_baseRoute = $_str_baseFile;

        /*print_r($_arr_configRoute['route_type']);
        print_r('<br>');*/

        if ($_arr_configRoute['route_type'] == 'noBaseFile' || strpos($_str_url, $_str_baseFile) === false) {
            $_str_baseRoute = $this->root();
        }

        if ($with_domain) {
            $_str_baseRoute = $this->domain() . $_str_baseRoute;
        }

        return rtrim(strtolower($_str_baseRoute), '/');
    }

    function fillParam($data, $param) {
        //print_r($param);
        $_arr_return    = array();

        if (is_array($param) && !Func::isEmpty($param)) {
            foreach ($param as $_key=>$_value) {
                if (!isset($data[$_key])) {
                    $data[$_key] = '';
                }

                if (!isset($_value[0])) {
                    $_value[0] = 'str';
                }

                if (!isset($_value[1])) {
                    $_value[1] = '';
                }

                if (!isset($_value[2])) {
                    $_value[2] = false;
                }

                $_arr_return[$_key] = $this->input($data[$_key], $_value[0], $_value[1], $_value[2]);
            }
        }

        return $_arr_return;
    }

    function pagination($count = 0, $perpage = 0, $method = 'get', $param = 'page', $pergroup = 0) {
        if ($perpage > 0) {
            $_num_perpage       = $perpage;
        } else {
            $_arr_configDefault = Config::get('var_default');
            $_num_perpage       = $_arr_configDefault['perpage'];
        }

        if ($pergroup > 0) {
            $_num_pergroup = $pergroup;
        } else {
            $_num_pergroup = 10;
        }

        $method = strtolower($method);

        if ($_num_perpage < 1) {
            $_num_perpage = 1;
        }

        if (is_numeric($method)) {
            $_num_this = $method;
        } else {
            switch ($method) {
                case 'post':
                    $_num_this = $this->post($param, false, 'int', 1);
                break;

                default:
                    //print_r($this->param);
                    if (isset($this->param[$param])) {
                        $_num_this = $this->input($this->param[$param], 'int', 1);
                    } else {
                        $_num_this = $this->get($param, false, 'int', 1);
                    }
                break;
            }
        }

        if ($_num_this < 1) {
            $_num_this = 1;
        }

        $_num_total = $count / $_num_perpage;

        if (intval($_num_total) < $_num_total) {
            $_num_total = intval($_num_total) + 1;
        } else if ($_num_total < 1) {
            $_num_total = 1;
        } else {
            $_num_total = intval($_num_total);
        }

        if ($_num_this > $_num_total) {
            $_num_this = $_num_total;
        }

        if ($_num_this <= 1) {
            $_num_except = 0;
        } else {
            $_num_except = ($_num_this - 1) * $_num_perpage;
        }

        $_num_p     = intval(($_num_this - 1) / $_num_pergroup); //是否存在上十页、下十页参数
        $_num_groupBegin = $_num_p * $_num_pergroup + 1; //列表起始页
        $_num_groupEnd   = $_num_p * $_num_pergroup + $_num_pergroup; //列表结束页

        if ($_num_groupEnd >= $_num_total) {
            $_num_groupEnd = $_num_total;
        }

        $_num_first     = false;
        $_num_final     = false;
        $_num_prev      = false;
        $_num_next      = false;
        $_num_groupPrev = false;
        $_num_groupNext = false;

        if ($_num_this > 1) {
            $_num_first = 1;
            $_num_prev  = $_num_this - 1;
        }

        if ($_num_this < $_num_total) {
            $_num_final = $_num_total;
            $_num_next  = $_num_this + 1;
        }

        if ($_num_p * $_num_pergroup > 0) {
            $_num_groupPrev = $_num_p * $_num_pergroup;
        }

        if ($_num_groupEnd < $_num_final) {
            $_num_groupNext = $_num_groupEnd + 1;
        }

        return array(
            'page'          => $_num_this,
            'total'         => $_num_total,
            'except'        => $_num_except,
            'first'         => $_num_first,
            'final'         => $_num_final,
            'prev'          => $_num_prev,
            'next'          => $_num_next,
            'group_begin'   => $_num_groupBegin,
            'group_end'     => $_num_groupEnd,
            'group_prev'    => $_num_groupPrev,
            'group_next'    => $_num_groupNext,
        );
    }


    function input($input = '', $type = 'str', $default = '', $htmlmode = false) {
        //print_r($input);
        //print_r(PHP_EOL);

        if (Func::isEmpty($input)) {
            $_mix_input = $default;
        } else {
            $_mix_input = $input;
        }

        switch ($type) {
            case 'int': //整数型
                $_mix_input = trim($_mix_input);

                if (is_numeric($_mix_input)) {
                    $_return = intval($_mix_input); //如果是整数型则赋值
                } else {
                    $_return = 0; //如果默认值为空则赋值为0
                }
            break;

            case 'float':
            case 'num': //数值型
                $_mix_input = trim($_mix_input);

                if (is_numeric($_mix_input)) {
                    $_return = floatval($_mix_input); //如果是数值型则赋值
                } else {
                    $_return = 0; //如果默认值为空则赋值为0
                }
            break;

            case 'arr': //数组
                $_return = Func::arrayEach($_mix_input);
            break;

            default: //默认
                $_return = Func::safe($_mix_input, $htmlmode);
            break;

        }

        return $_return;
    }

    private function duplicateProcess($method = 'POST') {
        $method = strtoupper($method);

        $_str_input = '';

        switch ($method) {
            case 'GET':
                $_str_input = md5(serialize($_GET));
            break;

            case 'REQUEST':
                $_str_input = md5(serialize($_REQUEST));
            break;

            default:
                $_str_input = md5(serialize($_POST));
            break;
        }

        return $_str_input;
    }
}


