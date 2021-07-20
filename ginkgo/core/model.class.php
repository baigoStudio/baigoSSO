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

// 模型抽象类
abstract class Model {

    protected $config = array(); // 数据库配置
    protected $obj_request; // 请求实例
    protected $obj_builder; // sql 语句构建实例
    protected $table; // 表名
    protected $className; // 类名

    private $configThis = array(
        'type'      => 'mysql',
        'host'      => '',
        'name'      => '',
        'user'      => '',
        'pass'      => '',
        'charset'   => 'utf8',
        'prefix'    => 'ginkgo_',
        'debug'     => false,
        'port'      => 3306,
    );

    private $obj_db; // 数据库实例

    /** 构造函数
     * __construct function.
     *
     * @access public
     * @param array $config (default: array()) 数据库配置
     * @return void
     */
    public function __construct($config = array()) {
        $this->obj_request  = Request::instance();

        $this->config($config); // 配置处理

        $this->obj_db       = Db::connect($this->config);

        $this->obj_builder  = $this->obj_db->obj_builder;

        $this->m_init();
    }


    /** 魔术调用
     * __call function.
     *
     * @access public
     * @param string $method 数据库方法
     * @param mixed $params 参数
     * @return void
     */
    public function __call($method, $params) {
        if (method_exists($this->obj_db, $method)) {
            $_table = $this->realClassProcess(); // 取得表名

            $this->obj_db->setModel($this->className); // 设置模型名 (防止冲突)

            if (Func::isEmpty($this->table)) { // 假如未定义表名属性, 自动设置表名
                $this->obj_db->setTable($_table);
            } else {
                $this->obj_db->setTable($this->table); // 表名属性作为表名
            }

            return call_user_func_array(array($this->obj_db, $method), $params); // 调用数据库驱动方法
        } else {
            $_obj_excpt = new Exception('Method not found', 500); // 报错
            $_obj_excpt->setData('err_detail', __CLASS__ . '->' . $method);

            throw $_obj_excpt;
        }
    }


    // 模型初始化
    protected function m_init() { }


    /** 数据库配置
     * config function.
     *
     * @access protected
     * @param array $config 配置参数
     * @return void
     */
    protected function config($config = array()) {
        $_arr_config   = Config::get('dbconfig');

        $_arr_configDo = $this->configThis;

        if (is_array($_arr_config) && !Func::isEmpty($_arr_config)) {
            $_arr_configDo = array_replace_recursive($_arr_configDo, $_arr_config); // 合并配置
        }

        if (is_array($this->config) && !Func::isEmpty($this->config)) {
            $_arr_configDo = array_replace_recursive($_arr_configDo, $this->config); // 合并配置
        }

        if (is_array($config) && !Func::isEmpty($config)) {
            $_arr_configDo = array_replace_recursive($_arr_configDo, $config); // 合并配置
        }

        $this->config  = $_arr_configDo;
    }


    /** 模型验证
     * validate function.
     *
     * @access protected
     * @param mixed $data 待验证数据
     * @param mixed $validate (default: '') 验证规则
     * @param bool $scene (default: false) 验证场景
     * @param array $only (default: array()) 仅验证字段
     * @param array $remove (default: array()) 移除规则
     * @param array $append (default: array()) 添加规则
     * @return 验证结果
     */
    protected function validate($data, $validate = '', $scene = false, $only = array(), $remove = array(), $append = array()) {
        if (Func::isEmpty($validate)) { // 如果规则参数为空, 则以当前实例的类名作为验证器名
            $validate   = $this->realClassProcess(); // 取得验证器名称
            $_vld       = Loader::validate($validate); // 载入验证器

            if (!Func::isEmpty($scene)) {
                $_vld->scene($scene); // 设置场景
            }
        } else {
            if (is_array($validate)) { // 如果规则为数组, 直接实例化验证类
                $_vld = Validate::instance(); // 实例化验证类
                $_vld->rule($validate); // 以规则参数设置规则
            } else if (is_string($validate)) { // 如果规则参数为字符串, 则认为是验证器名称, 直接实例化该验证器
                $_vld = Loader::validate($validate); // 载入验证器

                if (!Func::isEmpty($scene)) {
                    $_vld->scene($scene); // 设置场景
                }
            }
        }

        if (!Func::isEmpty($only)) {
            $_vld->only($only); // 仅验证字段
        }

        if (!Func::isEmpty($remove)) {
            $_vld->remove($remove); // 移除规则
        }

        if (!Func::isEmpty($append)) {
            $_vld->append($append); // 添加规则
        }

        if ($_vld->verify($data)) {
            $_mix_return = true; // 验证通过
        } else {
            $_mix_return = $_vld->getMessage(); // 返回错误信息
        }

        return $_mix_return;
    }


    /** 类名处理
     * realClassProcess function.
     *
     * @access private
     * @return void
     */
    private function realClassProcess() {
        $_class     = get_class($this); // 根据当前实例获得类名
        //print_r($_class);
        $_arr_class = explode('\\', $_class); // 拆分命名空间
        $_realClass = end($_arr_class); // 以最后一个元素作为真实类名

        $this->className = $_class;

        return strtolower($_realClass);
    }
}
