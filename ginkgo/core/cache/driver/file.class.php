<?php
/*-----------------------------------------------------------------
！！！！警告！！！！
以下为系统文件，请勿修改
-----------------------------------------------------------------*/

namespace ginkgo\cache\driver;

use ginkgo\Loader;
use ginkgo\Func;
use ginkgo\Config;
use ginkgo\File as File_Base;

// 不能非法包含或直接执行
defined('IN_GINKGO') or exit('Access denied');

// 文件型缓存驱动
class File extends File_Base {

    protected static $instance; // 当前实例

    // 默认配置
    private $this_config = array(
        'prefix'    => '',
        'life_time' => 1200,
    );

    /** 构造函数
     * __construct function.
     *
     * @access protected
     * @param array $config (default: array()) 配置
     * @return void
     */
    protected function __construct($config = array()) {
        $_arr_config  = Config::get('cache');

        $this->config = array_replace_recursive($this->this_config, $_arr_config);

        if (!Func::isEmpty($config)) {
            $this->config = array_replace_recursive($this->config, $config);
        }
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
    public static function instance() {
        if (Func::isEmpty(static::$instance)) {
            static::$instance = new static();
        }

        return static::$instance;
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
            return $this->config['prefix'];
        } else {
            $this->config['prefix'] = $prefix;
        }
    }

    /** 检查缓存是否存在
     * check function.
     *
     * @access public
     * @param mixed $name 缓存名称
     * @param bool $check_expire (default: false) 是否检查过期时间 (默认不检查)
     * @return 检查结果 (bool)
     */
    function check($name, $check_expire = false) {
        $_bool_return = true;
        $_str_path    = $this->getPath($name); // 取得路径

        $_bool_return = Func::isFile($_str_path); // 文件是否存在

        if ($_bool_return && $check_expire) { // 如果存在, 根据参数验证时间
            $_arr_content = Loader::load($_str_path);

            if (isset($_arr_content['expire']) && $_arr_content['expire'] > 0) {
                if ($_arr_content['expire'] < GK_NOW) { // 定义了时间, 且早于当前时间, 过期
                    $_bool_return = false;
                }
            }
        }

        return $_bool_return;
    }

    /** 读取
     * read function.
     *
     * @access public
     * @param mixed $name 缓存名称
     * @return 缓存内容
     */
    function read($name) {
        $_str_path    = $this->getPath($name); // 取得路径

        $_arr_content = Loader::load($_str_path); // 读取

        $_mix_return  = '';

        if (isset($_arr_content['expire'])) { // 如果定义了时间
            if ($_arr_content['expire'] > 0) { // 如果定义了时间
                if ($_arr_content['expire'] > GK_NOW) { // 晚于当前时间, 有效
                    $_mix_return = $_arr_content['value'];
                } else { // 早于当前时间, 直接删除
                    $this->delete($name);
                }
            } else { // 为 0 时, 永久有效
                $_mix_return = $_arr_content['value'];
            }
        } else { // 未定义直接删除
            $this->delete($name);
        }

        return $_mix_return;
    }

    /** 写入
     * write function.
     *
     * @access public
     * @param mixed $name 缓存名称
     * @param mixed $content 缓存内容
     * @return 写入字节数
     */
    function write($name, $content, $life_time = 0) {
        $_str_path = $this->getPath($name); // 取得路径

        $_str_content = '';

        if (is_string($content)) {
            $_str_content = '\'' . $content . '\''; // 如果是字符串, 进行转义
        } else {
            $_str_content = $content;
        }

        if ($life_time > 0) { // 如果参数指定了有效时间, 则直接使用
            $_tm_expire = GK_NOW + $life_time;
        } else if ($this->config['life_time'] > 0) { // 否则以配置文件为准
            $_tm_expire = GK_NOW + $this->config['life_time'];
        } else { // 配置文件未定义
            $_tm_expire = 0; // 永久有效
        }

        $_arr_outPut = array(
            'expire' => $_tm_expire,
            'value'  => $content,
        );

        $_str_outPut = '<?php return ' . var_export($_arr_outPut, true) . ';'; // 转换为 php 语句

        return $this->fileWrite($_str_path, $_str_outPut); // 写入文件
    }

    /** 删除
     * delete function.
     *
     * @access public
     * @param mixed $name 缓存名称
     * @return 删除结果 (bool)
     */
    function delete($name) {
        $_str_path = $this->getPath($name);

        return $this->fileDelete($_str_path);
    }


    /** 取得路径
     * getPath function.
     *
     * @access private
     * @param mixed $name 缓存名
     * @return 路径
     */
    private function getPath($name) {
        $_str_path = GK_PATH_CACHE; // 基本路径

        if (isset($this->config['prefix']) && !Func::isEmpty($this->config['prefix'])) {
            $_str_path .= $this->config['prefix'] . DS; // 加上前缀
        }

        $_str_path .= $name . GK_EXT; // 补全路径

        return $_str_path;
    }
}