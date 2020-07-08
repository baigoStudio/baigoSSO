<?php
/*-----------------------------------------------------------------
！！！！警告！！！！
以下为系统文件，请勿修改
-----------------------------------------------------------------*/

namespace ginkgo;

// 不能非法包含或直接执行
defined('IN_GINKGO') or exit('Access denied');

// 认证处理类
class Auth {

    protected static $instance; // 当前实例

    public $prefix;

    private $configAuth;
    private $session;
    private $cookie;
    private $remember;
    private $error;

    // 默认配置
    private $this_config = array(
        'session_expire'    => 1200,
        'remember_expire'   => 2592000,
    );

    protected function __construct($config = array(), $prefix = 'user') {
        $_arr_config        = Config::get('auth');

        $this->configAuth   = array_replace_recursive($this->this_config, $config, $_arr_config); // 合并配置
        $this->prefix       = $prefix; // 前缀
    }

    protected function __clone() {

    }

    /** 实例化
     * instance function.
     *
     * @access public
     * @static
     * @return 当前类的实例
     */
    public static function instance($config = array(), $prefix = 'user') {
        if (Func::isEmpty(static::$instance)) {
            static::$instance = new static($config, $prefix);
        }

        return static::$instance;
    }


    /** 读取认证信息
     * read function.
     *
     * @access public
     * @return 认证信息
     */
    function read() {
        $_sessionTime    = Session::get($this->prefix . '_time');

        $_arr_session = array(
            $this->prefix . '_id'           => Session::get($this->prefix . '_id'),
            $this->prefix . '_name'         => Session::get($this->prefix . '_name'),
            $this->prefix . '_hash'         => Session::get($this->prefix . '_hash'),
            $this->prefix . '_time'         => $_sessionTime,
            $this->prefix . '_time_expire'  => $_sessionTime + $this->configAuth['session_expire'],
        );

        $_cookieTime    = Cookie::get($this->prefix . '_time');

        $_arr_cookie = array(
            $this->prefix . '_id'           => Cookie::get($this->prefix . '_id'),
            $this->prefix . '_name'         => Cookie::get($this->prefix . '_name'),
            $this->prefix . '_hash'         => Cookie::get($this->prefix . '_hash'),
            $this->prefix . '_time'         => $_cookieTime,
            $this->prefix . '_time_expire'  => $_cookieTime + $this->configAuth['session_expire'],
        );

        $_rememberTime    = Cookie::get('remember_' . $this->prefix . '_time');

        $_arr_remember = array(
            $this->prefix . '_id'           => Cookie::get('remember_' . $this->prefix . '_id'),
            $this->prefix . '_name'         => Cookie::get('remember_' . $this->prefix . '_name'),
            $this->prefix . '_hash'         => Cookie::get('remember_' . $this->prefix . '_hash'),
            $this->prefix . '_time'         => $_rememberTime,
            $this->prefix . '_time_expire'  => $_rememberTime + $this->configAuth['remember_expire'],
        );

        $this->session  = $_arr_session;
        $this->cookie   = $_arr_cookie;
        $this->remember = $_arr_remember;

        return array(
            'session'   => $_arr_session,
            'cookie'    => $_arr_cookie,
            'remember'  => $_arr_remember,
        );
    }


    /** 写入认证信息
     * write function.
     *
     * @access public
     * @param mixed $userRow 认证信息
     * @param bool $regen (default: false) 是否使用新生成的会话 ID
     * @param string $loginType (default: 'form') 登录类型
     * @param string $remember (default: '') 记住登录状态
     * @param string $pathCookie (default: '/') Cookie 保存路径
     * @return void
     */
    function write($userRow, $regen = false, $loginType = 'form', $remember = '', $pathCookie = '/') {
        if ($regen) {
            session_regenerate_id(true); // 使用新生成的会话 ID 更新现有会话 ID
        }

        $_arr_optCookie = array(
            'expire'    => GK_NOW + $this->configAuth['session_expire'], // 会话过期时间
            'path'      => $pathCookie,
        );

        //print_r($userRow);

        Session::set($this->prefix . '_id', $userRow[$this->prefix . '_id']);
        Session::set($this->prefix . '_name', $userRow[$this->prefix . '_name']);
        Session::set($this->prefix . '_time', GK_NOW);
        Session::set($this->prefix . '_hash', $this->hashProcess($userRow));
        Session::set($this->prefix . '_login_type', $loginType);

        Cookie::set($this->prefix . '_id', $userRow[$this->prefix . '_id'], $_arr_optCookie);
        Cookie::set($this->prefix . '_name', $userRow[$this->prefix . '_name'], $_arr_optCookie);
        Cookie::set($this->prefix . '_time', GK_NOW, $_arr_optCookie);
        Cookie::set($this->prefix . '_hash', $this->hashProcess($userRow), $_arr_optCookie);
        Cookie::set($this->prefix . '_login_type', $loginType, $_arr_optCookie);

        $_arr_optCookie['expire'] = $this->configAuth['remember_expire']; // 保存密码过期时间

        if ($remember === 'remember') {
            Cookie::set('remember_' . $this->prefix . '_id', $userRow[$this->prefix . '_id'], $_arr_optCookie);
            Cookie::set('remember_' . $this->prefix . '_name', $userRow[$this->prefix . '_id'], $_arr_optCookie);
            Cookie::set('remember_' . $this->prefix . '_hash', $this->hashProcess($userRow), $_arr_optCookie);
            Cookie::set('remember_' . $this->prefix . '_time', GK_NOW, $_arr_optCookie);
        }
    }


    /** 结束登录
     * end function.
     *
     * @access public
     * @param int $id (default: 0)
     * @return void
     */
    function end($regen = false) {
        Session::delete($this->prefix . '_id');
        Session::delete($this->prefix . '_name');
        Session::delete($this->prefix . '_time');
        Session::delete($this->prefix . '_hash');
        Cookie::delete($this->prefix . '_id');
        Cookie::delete($this->prefix . '_name');
        Cookie::delete($this->prefix . '_time');
        Cookie::delete($this->prefix . '_hash');
        Cookie::delete('remember_' . $this->prefix . '_id');
        Cookie::delete('remember_' . $this->prefix . '_name');
        Cookie::delete('remember_' . $this->prefix . '_hash');
        Cookie::delete('remember_' . $this->prefix . '_time');

        if ($regen) {
            session_regenerate_id(true); // 使用新生成的会话 ID 更新现有会话 ID
        }
    }


    /** 验证认证信息
     * check function.
     *
     * @access public
     * @param mixed $userRow
     * @param string $pathCookie (default: '/')
     * @return bool
     */
    function check($userRow, $pathCookie = '/') {
        $_arr_session   = $this->session;
        $_arr_cookie    = $this->cookie;
        $_arr_remember  = $this->remember;

        /*print_r($_arr_cookie);
        print_r($_arr_session);
        print_r($userRow);*/

        if (!$this->checkUser($userRow)) {
            $this->end();
            return false;
        }

        if ($this->haveSession()) {
            if ($userRow[$this->prefix . '_id'] != $_arr_session[$this->prefix . '_id']) {
                $this->end();
                $this->error = 'Session ID is incorrect';
                return false;
            }

            if ($userRow[$this->prefix . '_name'] != $_arr_session[$this->prefix . '_name']) {
                $this->end();
                $this->error = 'Session ID is incorrect';
                return false;
            }

            if ($this->hashProcess($userRow) != $_arr_session[$this->prefix . '_hash']) {
                $this->end();
                $this->error = 'Session hash is incorrect';
                return false;
            }

            if ($userRow[$this->prefix . '_id'] != $_arr_cookie[$this->prefix . '_id']) {
                $this->end();
                $this->error = 'Cookie ID is incorrect';
                return false;
            }

            if ($userRow[$this->prefix . '_name'] != $_arr_cookie[$this->prefix . '_name']) {
                $this->end();
                $this->error = 'Cookie ID is incorrect';
                return false;
            }

            if ($this->hashProcess($userRow) != $_arr_cookie[$this->prefix . '_hash']){
                $this->end();
                $this->error = 'Cookie hash is incorrect';
                return false;
            }

            $this->write($userRow, false, 'form', '', $pathCookie);
        } else if ($this->haveRemenber()) {
            if ($userRow[$this->prefix . '_id'] != $_arr_remember[$this->prefix . '_id']) {
                $this->end();
                $this->error = 'Remember ID is incorrect';
                return false;
            }

            if ($userRow[$this->prefix . '_name'] != $_arr_remember[$this->prefix . '_name']) {
                $this->end();
                $this->error = 'Remember ID is incorrect';
                return false;
            }

            if ($this->hashProcess($userRow) != $_arr_remember[$this->prefix . '_hash']){
                $this->end();
                $this->error = 'Remember hash is incorrect';
                return false;
            }

            $this->write($userRow, false, 'auto', '', $pathCookie);
        } else {
            $this->end();
            $this->error = 'No session';
            return false;
        }

        return true;
    }


    /** 设置, 取得前缀
     * prefix function.
     *
     * @access public
     * @param string $prefix (default: '') 前缀
     * @return 如果参数为空则返回当前前缀, 否则无返回
     */
    public function prefix($prefix = '') {
        if (Func::isEmpty($prefix)) {
            return $this->prefix;
        } else {
            $this->prefix = $prefix;
        }
    }


    /** 取得错误消息
     * getError function.
     *
     * @access public
     * @return 错误消息
     */
    function getError() {
        return $this->error;
    }


    /** 验证 userRow 结构
     * checkUser function.
     *
     * @access private
     * @return void
     */
    private function checkUser($userRow) {
        if (!isset($userRow[$this->prefix . '_id'])) {
            $this->error = 'Missing user ID';
            return false;
        }
        if (!isset($userRow[$this->prefix . '_name'])) {
            $this->error = 'Missing user name';
            return false;
        }
        if (!isset($userRow[$this->prefix . '_time_login'])) {
            $this->error = 'Missing user login time';
            return false;
        }
        if (!isset($userRow[$this->prefix . '_id'])) {
            $this->error = 'Missing user IP';
            return false;
        }

        return true;
    }


    /** 是否有会话
     * haveSession function.
     *
     * @access private
     * @return bool
     */
    private function haveSession() {
        $_arr_session   = $this->session;
        $_arr_cookie    = $this->cookie;

        if (Func::isEmpty($_arr_session[$this->prefix . '_id'])) {
            $this->error = 'Missing session ID';
            return false;
        }
        if (Func::isEmpty($_arr_session[$this->prefix . '_name'])) {
            $this->error = 'Missing session name';
            return false;
        }
        if (Func::isEmpty($_arr_session[$this->prefix . '_time'])) {
            $this->error = 'Missing session time';
            return false;
        }
        if (Func::isEmpty($_arr_session[$this->prefix . '_hash'])) {
            $this->error = 'Missing session hash';
            return false;
        }

        if ($_arr_session[$this->prefix . '_time_expire'] < GK_NOW) {
            $this->error = 'Session expired';
            return false;
        }

        if (Func::isEmpty($_arr_cookie[$this->prefix . '_id'])) {
            $this->error = 'Missing cookie ID';
            return false;
        }
        if (Func::isEmpty($_arr_cookie[$this->prefix . '_name'])) {
            $this->error = 'Missing cookie name';
            return false;
        }
        if (Func::isEmpty($_arr_cookie[$this->prefix . '_time'])) {
            $this->error = 'Missing cookie time';
            return false;
        }
        if (Func::isEmpty($_arr_cookie[$this->prefix . '_hash'])) {
            $this->error = 'Missing cookie hash';
            return false;
        }

        if ($_arr_cookie[$this->prefix . '_time_expire'] < GK_NOW) {
            $this->error = 'Cookie expired';
            return false;
        }

        return true;
    }


    /** 是否有记住登录状态
     * haveRemenber function.
     *
     * @access private
     * @return void
     */
    private function haveRemenber() {
        $_arr_remember  = $this->remember;

        if (Func::isEmpty($_arr_remember[$this->prefix . '_id'])) {
            $this->error = 'Missing remember ID';
            return false;
        }
        if (Func::isEmpty($_arr_remember[$this->prefix . '_name'])) {
            $this->error = 'Missing remember name';
            return false;
        }
        if (Func::isEmpty($_arr_remember[$this->prefix . '_hash'])) {
            $this->error = 'Missing remember hash';
            return false;
        }
        if (Func::isEmpty($_arr_remember[$this->prefix . '_time'])) {
            $this->error = 'Missing remember time';
            return false;
        }

        if ($_arr_remember[$this->prefix . '_time_expire'] < GK_NOW) {
            $this->error = 'Remember expired';
            return false;
        }

        return true;
    }


    /** 哈希处理
     * hashProcess function.
     *
     * @access private
     * @param mixed $userRow
     * @return 哈希值
     */
    private function hashProcess($userRow) {
        $_str_return = '';

        if (isset($userRow[$this->prefix . '_id']) && isset($userRow[$this->prefix . '_name']) && isset($userRow[$this->prefix . '_time_login']) && isset($userRow[$this->prefix . '_ip'])) {
            $_str_return = Crypt::crypt($userRow[$this->prefix . '_id'] . $userRow[$this->prefix . '_name'] . $userRow[$this->prefix . '_time_login'], $userRow[$this->prefix . '_ip']);
        }

        return $_str_return;
    }
}


