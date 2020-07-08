<?php
/*-----------------------------------------------------------------
！！！！警告！！！！
以下为系统文件，请勿修改
-----------------------------------------------------------------*/

namespace ginkgo\db\builder;

use ginkgo\Func;

// 不能非法包含或直接执行
defined('IN_GINKGO') or exit('Access denied');

/*-------------数据库类-------------*/
class Mysql {

    protected static $instance; // 当前实例
    private $dbconfig; // 数据库配置

    // SQL 操作符
    private $exp = array(
        'EQ'                => '=',
        'NEQ'               => '<>',
        'GT'                => '>',
        'EGT'               => '>=',
        'LT'                => '<',
        'ELT'               => '<=',
        'LIKE'              => 'LIKE',
        'NOTLIKE'           => 'NOT LIKE',
        'NOT LIKE'          => 'NOT LIKE',
        'IN'                => 'IN',
        'NOTIN'             => 'NOT IN',
        'NOT IN'            => 'NOT IN',
        'BETWEEN'           => 'BETWEEN',
        'NOTBETWEEN'        => 'NOT BETWEEN',
        'NOT BETWEEN'       => 'NOT BETWEEN',
        'EXP'               => 'EXP',
    );


    /** 比较运算符
     * compopr
     *
     * @var mixed
     * @access private
     */
    private $compopr = array(
        '=', '<>', '>', '>=', '<', '<='
    );

    /** 逻辑运算符
     * logic
     *
     * @var mixed
     * @access private
     */
    private $logic = array(
        'AND', 'OR'
    );

    /** 排序方式
     * order
     *
     * @var mixed
     * @access private
     */
    private $order = array(
        'DESC', 'ASC'
    );

    /** join 方式
     * join
     *
     * @var mixed
     * @access private
     */
    private $join = array(
        'INNER', 'LEFT', 'RIGHT', 'FULL'
    );


    /** 配置参数
     * this_config
     *
     * @var mixed
     * @access private
     */
    private $this_config = array(
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

    /** 构造函数
     * __construct function.
     *
     * @access protected
     * @param array $dbconfig (default: array()) 配置
     * @return void
     */
    protected function __construct($dbconfig = array()) {
        $this->config($dbconfig);
    }

    protected function __clone() {

    }

    /** 实例化
     * instance function.
     *
     * @access public
     * @static
     * @param array $dbconfig (default: array()) 配置
     * @return 当前类的实例
     */
    public static function instance($dbconfig = array()) {
        if (Func::isEmpty(static::$instance)) {
            static::$instance = new static($dbconfig);
        }
        return static::$instance;
    }


    /** 设定配置
     * config function.
     *
     * @access public
     * @param array $dbconfig (default: array())
     * @return void
     */
    function config($dbconfig = array()) {
        $this->dbconfig = array_replace_recursive($this->this_config, $dbconfig); // 合并配置
    }


    /** 处理字段
     * field function.
     *
     * @access public
     * @param mixed $field 字段
     * @return 字段字符串
     */
    function field($field) {
        $_str_field = '*';

        if (is_array($field)) {
            foreach ($field as $_key=>$_value) {
                if (is_array($_value)) {
                    if (isset($_value[0]) && is_scalar($_value[0])) {
                        $_str_alias = '';

                        if (isset($_value[1])) {
                            $_str_alias = ' AS ' . $this->addChar($_value[1]);
                        }

                        $field[$_key] = $this->fieldProcess($_value[0]) . $_str_alias;
                    }
                } else if (is_scalar($_value)) {
                    $field[$_key] = $this->fieldProcess($_value);
                }
            }

            $_str_field = implode(',', $field);
        } else if (is_scalar($field) && !Func::isEmpty($field)) {
            $field = trim($field);
            if ($field != '*') {
                $_str_field = $this->fieldProcess($field);
            }
        }

        return $_str_field;
    }


    /** 处理 insert 语句
     * insert function.
     *
     * @access public
     * @param mixed $field 字段
     * @param string $value (default: '') 值
     * @param string $param (default: '') 参数
     * @param string $type (default: '') 参数类型
     * @return sql 语句及绑定参数
     */
    function insert($field, $value = '', $param = '', $type = '') {
        $_arr_result = $this->update($field, $value, $param, $type, 'insert');

        return array(
            'insert' => $_arr_result['update'],
            'bind'   => $_arr_result['bind'],
        );
    }


    /** 处理 update 语句
     * update function.
     *
     * @access public
     * @param mixed $field 字段
     * @param string $value (default: '') 值
     * @param string $param (default: '') 参数
     * @param string $type (default: '') 参数类型
     * @param string $from (default: 'update')
     * @return sql 语句及绑定参数
     */
    function update($field, $value = '', $param = '', $type = '', $from = 'update') {
        $_str_update    = '';
        $_arr_bind      = array();
        $_arr_condition = array();

        if (is_array($field)) {
            foreach ($field as $_key=>$_value) {
                if (is_array($_value)) {
                    if (!isset($_value[0])) {
                        $_value[0] = '';
                    }

                    if (!isset($_value[1])) {
                        $_value[1] = '';
                    }

                    if (!isset($_value[2])) {
                        $_value[2] = '';
                    }

                    $_arr_condition[$_key]  = $this->updateProcess($_key, $_value[1], $_value[0], $from);
                    $_arr_bind[]            = $this->bindProcess($_key, $_value[0], $from, $_value[1], $_value[2]);

                } else if (is_scalar($_value)) {
                    $_arr_condition[$_key]  = $this->updateProcess($_key, $_key, $_value, $from);
                    $_arr_bind[]            = $this->bindProcess($_key, $_value, $from);
                }
            }

            $_str_update = implode(',', $_arr_condition);
        } else if (is_scalar($field)) {
            if (Func::isEmpty($value)) {
                $_str_update = $field;
            } else {
                $_str_update = $this->updateProcess($field, $param, $value, $from);
                $_arr_bind   = $this->bindProcess($field, $value, $from, $param, $type);
            }
        }

        return array(
            'update' => $_str_update,
            'bind'   => $_arr_bind,
        );
    }


    /** 处理表名
     * table function.
     *
     * @access public
     * @param mixed $table 表名
     * @return 完整表名
     */
    function table($table) {
        return $this->addChar($this->dbconfig['prefix'] . $table);
    }


    /** 处理强制索引名
     * force function.
     *
     * @access public
     * @param mixed $index 索引名
     * @return 索引名
     */
    function force($index) {
        return $this->addChar($index);
    }


    /** 处理 join 命令
     * join function.
     *
     * @access public
     * @param mixed $join join 表
     * @param string $on (default: '') 条件
     * @param string $type (default: '') join 类型
     * @return sql 语句
     */
    function join($join, $on = '', $type = '') {
        $_str_join = '';

        if (is_array($join)) {
            if (isset($join[0])) {
                if (is_array($join[0])) {
                    $_arr_condition = array();

                    foreach ($join as $_key => $_value) {
                        if (is_array($_value)) {
                            if (isset($_value[0]) && is_scalar($_value[0])) {
                                if (!isset($_value[1])) {
                                    $_value[1] = '';
                                }

                                if (!isset($_value[2])) {
                                    $_value[2] = '';
                                }

                                $_str_condition = $this->joinProcess($_value[0], $_value[1], $_value[2]);

                                if (!Func::isEmpty($_str_condition)) {
                                    $_arr_condition[] = $_str_condition;
                                }
                            }
                        }
                    }

                    $_str_join = implode(' ', $_arr_condition);
                } else if (is_scalar($join[0])) {
                    if (!isset($join[1])) {
                        $join[1] = '';
                    }

                    if (!isset($join[2])) {
                        $join[2] = '';
                    }

                    $_str_join = $this->joinProcess($join[0], $join[1], $join[2]);
                }
            }
        } else if (is_scalar($join)) {
            if (Func::isEmpty($on)) {
                $_str_join = $join;
            } else {
                $_str_join = $this->joinProcess($join, $on, $type);
            }
        }

        return $_str_join;
    }


    /** 处理 where 语句
     * where function.
     *
     * @access public
     * @param mixed $where where 条件
     * @param string $exp (default: '') 运算符
     * @param string $value (default: '') 值
     * @param string $param (default: '') 参数
     * @param string $type (default: '') 参数类型
     * @return sql 语句及绑定参数
     */
    function where($where, $exp = '', $value = '', $param = '', $type = '') {
        $_str_where = '';
        $_arr_bind  = array();

        if (is_array($where)) {
            if (isset($where[0])) {
                if (is_array($where[0])) {
                    foreach ($where as $_key => $_value) {
                        if (is_array($_value)) {
                            if (isset($_value[0]) && is_scalar($_value[0])) {
                                if (!isset($_value[1])) {
                                    $_value[1] = '';
                                }

                                if (!isset($_value[2])) {
                                    $_value[2] = '';
                                }

                                if (!isset($_value[3])) {
                                    $_value[3] = '';
                                }

                                if (strpos($_value[0], '|') || strpos($_value[0], '&')) {
                                    $_str_condition = $this->whereProcessSub($_value[0], $_value[1], $_value[3], $_value[2]);
                                } else {
                                    $_str_condition = $this->whereProcess($_value[0], $_value[1], $_value[3], $_value[2]);
                                }

                                if (!Func::isEmpty($_str_condition)) {
                                    if (!isset($_value[4])) {
                                        $_value[4] = '';
                                    }

                                    if (!isset($_value[5]) || Func::isEmpty($_value[5])) {
                                        $_value[5] = 'AND';
                                    }

                                    $_value[5]   = $this->inArrayProcess($_value[5], 'logic');

                                    if ($this->isSpecBind($_value[2], $_value[1])) {
                                        $_arr_bind = $this->bindProcess($_value[0], $_value[2], 'where', $_value[3], $_value[4], $_value[1], $_arr_bind);
                                    } else {
                                        $_arr_bind[] = $this->bindProcess($_value[0], $_value[2], 'where', $_value[3], $_value[4], $_value[1]);
                                    }

                                    if (Func::isEmpty($_str_where)) {
                                        $_str_where .= $_str_condition;
                                    } else {
                                        $_str_where .= ' ' . $_value[5] . ' ' . $_str_condition;
                                    }
                                }
                            }
                        }
                    }
                } else if (is_scalar($where[0])) {
                    if (!isset($where[1])) {
                        $where[1] = '';
                    }

                    if (!isset($where[2])) {
                        $where[2] = '';
                    }

                    if (!isset($where[3])) {
                        $where[3] = '';
                    }

                    if (!isset($where[4])) {
                        $where[4] = '';
                    }

                    if (strpos($where[0], '|') || strpos($where[0], '&')) {
                        $_str_where = $this->whereProcessSub($where[0], $where[1], $where[3], $where[2]);
                    } else {
                        $_str_where = $this->whereProcess($where[0], $where[1], $where[3], $where[2]);
                    }

                    $_arr_bind = $this->bindProcess($where[0], $where[2], 'where', $where[3], $where[4], $where[1]);
                }
            }
        } else if (is_scalar($where)) {
            if (Func::isEmpty($exp)) {
                $_str_where = $where;
            } else {
                if (strpos($where, '|') || strpos($where, '&')) {
                    $_str_where = $this->whereProcessSub($where, $exp, $param, $value);
                } else {
                    $_str_where = $this->whereProcess($where, $exp, $param, $value);
                }

                $_arr_bind = $this->bindProcess($where, $value, 'where', $param, $type, $exp);
            }
        }

        return array(
            'where' => $_str_where,
            'bind'  => $_arr_bind,
        );
    }


    /** 处理 group 命令
     * group function.
     *
     * @access public
     * @param mixed $field 字段
     * @return group 语句
     */
    function group($field) {
        return $this->field($field);
    }


    /** 处理 order 语句
     * order function.
     *
     * @access public
     * @param mixed $field 字段
     * @param string $type (default: '') 排序类型
     * @return order 语句
     */
    function order($field, $type = '') {
        $_str_order  = '';

        if (is_array($field)) {
            if (isset($field[0])) {
                if (is_array($field[0])) {
                    $_arr_condition = array();

                    foreach($field as $_key => $_value) {
                        if (is_array($_value)) {
                            if (isset($_value[0]) && is_scalar($_value[0])) {
                                if (!isset($_value[1])) {
                                    $_value[1] = '';
                                }

                                $_str_condition = $this->orderProcess($_value[0], $_value[1]);

                                if (!Func::isEmpty($_str_condition)) {
                                    $_arr_condition[] = $_str_condition;
                                }
                            }
                        }
                    }

                    $_str_order = implode(',', $_arr_condition);
                } else if (is_scalar($field[0])) {
                    if (!isset($field[1])) {
                        $field[1] = '';
                    }

                    $_str_order = $this->orderProcess($field[0], $field[1]);
                }
            }
        } else if (is_scalar($field)) {
            if (Func::isEmpty($type)) {
                $_str_order = $field;
            } else {
                $_str_order = $this->orderProcess($field, $type);
            }
        }

        return $_str_order;
    }


    /** 处理 limit 语句
     * limit function.
     *
     * @access public
     * @param int $offset (default: 1) 偏离或数量
     * @param bool $length (default: false) 数量
     * @return void
     */
    function limit($offset = 1, $length = false) {
        if ($length === false) {
            return $offset;
        } else {
            return $offset . ', ' . $length;
        }
    }


    /** 添加 sql 名称界定符
     * addChar function.
     *
     * @access public
     * @param mixed $value 字符
     * @return 处理以后的字符
     */
    function addChar($value) {
        // 如果包含 * 或者 使用了 sql 函数, 则不作处理
        if ($value != '*' && strpos($value, '(') === false && strpos($value, '`') === false) {
            $value = '`' . trim($value) . '`';
        }

        return $value;
    }


    /** 字段名处理
     * fieldProcess function.
     *
     * @access public
     * @param mixed $field 字段名
     * @return 完整字段名
     */
    function fieldProcess($field) {
        if (strpos($field, '(') !== false || strpos($field, '`') !== false) {
            $_str_field = $field;
        } else {
            if (strpos($field, '.')) {
                $_arr_field = explode('.', $field);
                $_str_field = $this->table($_arr_field[0]) . '.' . $this->addChar($_arr_field[1]);
            } else {
                $_str_field = $this->addChar($field);
            }
        }

        return $_str_field;
    }


    /** 命令合法性处理, 到属性规定的数组中过滤一遍
     * inArrayProcess function.
     *
     * @access public
     * @param mixed $name 命令
     * @param string $type (default: 'order') 类型
     * @return 命令
     */
    function inArrayProcess($name, $type = 'order') {
        $_str_return = '';

        if (isset($this->$type)) {
            $name = strtoupper($name);
            if (in_array($name, $this->$type)) {
                $_str_return = $name;
            }
        }

        return $_str_return;
    }


    /** 运算符合法性处理
     * expProcess function.
     *
     * @access public
     * @param mixed $name 运算符
     * @return 运算符
     */
    function expProcess($name) {
        $_str_return = '';

        $_str_name = strtoupper($name);

        if (isset($this->exp[$_str_name])) {
            $_str_return = $this->exp[$_str_name];
        } else if (in_array($name, $this->compopr)) {
            $_str_return = $name;
        }

        return $_str_return;
    }


    /** 处理 update 语句
     * updateProcess function.
     *
     * @access private
     * @param mixed $field 字段
     * @param string $param (default: '') 参数
     * @param string $value (default: '') 值
     * @param string $from (default: 'update')
     * @return sql 语句
     */
    private function updateProcess($field, $param = '', $value = '', $from = 'update') {
        $_str_update = '';

        if (Func::isEmpty($param)) {
            $param = $field;
        }

        $field = $this->addChar($field);

        if (!Func::isEmpty($field)) {
            $param = $this->paramChar($param, true, $from);

            if (!Func::isEmpty($value)) {
                if (strpos($value, '(') !== false || strpos($value, '`') !== false) {
                    $param = $value;
                }
            }

            $_str_update = $field . '=' . $param;
        }

        return $_str_update;
    }


    /** 处理 join 语句
     * joinProcess function.
     *
     * @access private
     * @param mixed $join join 表
     * @param string $on (default: '') 条件
     * @param string $type (default: 'INNER') join 类型
     * @return sql 语句
     */
    private function joinProcess($join, $on = '', $type = 'INNER') {
        $_str_join = '';

        if (Func::isEmpty($type)) {
            $type = 'INNER';
        }

        if (is_array($on)) {
            if (isset($on[0])) {
                if (is_array($on[0])) {
                    foreach ($on[0] as $_key=>$_value) {
                        if (isset($_value[0]) && isset($_value[1]) && isset($_value[2])) {
                            $_str_condition = $this->onProcess($_value[0], $_value[1], $_value[2]);
                        }

                        if (!isset($_value[3]) || Func::isEmpty($_value[3])) {
                            $_value[3] = 'AND';
                        }

                        $_value[3]   = $this->inArrayProcess($_value[3], 'logic');

                        if (Func::isEmpty($_str_on)) {
                            $_str_on .= $_str_condition;
                        } else {
                            $_str_on .= ' ' . $_value[3] . ' ' . $_str_condition;
                        }
                    }
                } else {
                    if (isset($on[1]) && isset($on[2])) {
                        $_str_on = $this->onProcess($on[0], $on[1], $on[2]);
                    }
                }
            }
        } else if (is_scalar($on)) {
            $_str_on = $on;
        }

        //print_r($type);

        $type = strtoupper($type);

        $_str_type = $this->inArrayProcess($type, 'join');

        if (!Func::isEmpty($join) && !Func::isEmpty($_str_on)) {
            $_str_join = strtoupper($_str_type) . ' JOIN ' . $this->table($join) . ' ON (' . $_str_on . ')';
        }

        return $_str_join;
    }


    /** join 语句的 on 命令处理
     * onProcess function.
     *
     * @access private
     * @param string $fidle_1 (default: '') 字段 1
     * @param string $compopr (default: '') 运算符
     * @param string $fidle_2 (default: '') 字段 2
     * @return sql 语句
     */
    private function onProcess($fidle_1 = '', $compopr = '', $fidle_2 = '') {
        if (Func::isEmpty($compopr)) {
            $compopr = '=';
        }

        $_str_compopr = $this->inArrayProcess($compopr, 'compopr');

        return $this->fieldProcess($fidle_1) . $compopr . $this->fieldProcess($fidle_2);
    }


    /** 处理 where 语句
     * whereProcess function.
     *
     * @access private
     * @param mixed $field 字段
     * @param string $exp (default: '=') 运算符
     * @param string $param (default: '') 参数
     * @param string $value (default: '') 值
     * @return sql 语句
     */
    private function whereProcess($field, $exp = '=', $param = '', $value = '') {
        $_str_where = '';

        $_str_param = $field;

        if (!Func::isEmpty($param)) {
            $_str_param = $param;
        }

        if (Func::isEmpty($exp)) {
            $exp = '=';
        }

        $exp   = $this->expProcess($exp);

        $field = $this->addChar($field);

        if (!Func::isEmpty($field)) {
            switch ($exp) {
                case 'IN':
                case 'NOT IN':
                    if (is_array($value)) {
                        $_arr_param = array();

                        foreach ($value as $_key_sub=>$_value_sub) {
                            $_arr_param[] = $this->paramChar($_str_param . '_' . $_key_sub, true, 'where');
                        }

                        $_str_param = implode(',', $_arr_param);

                        $_str_where = $field . ' ' . $exp . ' (' . $_str_param . ')';
                    } else if (is_scalar($value)) {
                        if (strpos($value, '(') === false && strpos($value, '`') === false) {
                            $_str_where = $field . ' ' . $exp . ' (' . $this->paramChar($_str_param, true, 'where') . ')';
                        } else if (strpos($value, '(')) {
                            $_str_where = $field . ' ' . $exp . $value;
                        } else {
                            $_str_where = $field . ' ' . $exp . ' (' . $value . ')';
                        }
                    }
                break;

                case 'BETWEEN':
                case 'NOT BETWEEN':
                    if (is_array($value)) {
                        $_str_where = $field . ' ' . $exp . ' (' . $this->paramChar($_str_param . '_0', true, 'where') . ' AND ' . $this->paramChar($_str_param . '_1', true, 'where') . ')';
                    } else if (is_scalar($value)) {
                        if (strpos($value, '(') === false && strpos($value, '`') === false) {
                            $_str_where = $field . ' ' . $exp . ' (' . $this->paramChar($_str_param, true, 'where') . ')';
                        } else if (strpos($value, '(')) {
                            $_str_where = $field . ' ' . $exp . $value;
                        } else {
                            $_str_where = $field . ' ' . $exp . ' (' . $value . ')';
                        }
                    }
                break;

                case 'EXP':
                    $_str_where = $field . ' ' . $value;
                break;

                default:
                    if (is_scalar($value) && strpos($value, '(') === false && strpos($value, '`') === false) {
                        $_str_where = $field . ' ' . $exp . ' ' . $this->paramChar($_str_param, true, 'where');
                    } else if (is_scalar($value)) {
                        $_str_where = $field . ' ' . $exp . $value;
                    }
                break;
            }
        }

        return $_str_where;
    }


    /** 处理 where 子语句
     * whereProcessSub function.
     *
     * @access private
     * @param mixed $field 字段
     * @param string $exp (default: '=') 运算符
     * @param string $param (default: '') 参数
     * @param string $value (default: '') 值
     * @return sql 语句
     */
    private function whereProcessSub($where, $exp = '=', $param = '', $value = '') {
        $_str_where     = '';
        $_arr_condition = array();

        if (strpos($where, '|') || strpos($where, '&')) {
            if (strpos($where, '|')) {
                $_arr_where = explode('|', $where);
                $_str_logic = 'OR';
            } else if (strpos($where, '&')) {
                $_arr_where = explode('&', $where);
                $_str_logic = 'AND';
            }

            $_str_logic = $this->inArrayProcess($_str_logic, 'logic');

            foreach ($_arr_where as $_key=>$_value) {
                $_arr_condition[] = $this->whereProcess($_value, $exp, $param, $value);
            }

            $_str_where = '(' . implode(' ' . $_str_logic . ' ', $_arr_condition) . ')';
        }

        return $_str_where;
    }


    /** 排序语句处理
     * orderProcess function.
     *
     * @access private
     * @param mixed $field 字段
     * @param string $type (default: 'ASC') 排序类型
     * @return sql 语句
     */
    private function orderProcess($field, $type = 'ASC') {
        $_str_order = '';

        if (Func::isEmpty($type)) {
            $type = 'ASC';
        }

        $type  = $this->inArrayProcess($type, 'order');

        $field = $this->addChar($field);

        if (!Func::isEmpty($field)) {
            $_str_order = $field . ' ' . $type;
        }

        return $_str_order;
    }


    /** 绑定处理
     * bindProcess function.
     *
     * @access private
     * @param mixed $bind 绑定
     * @param mixed $value 值
     * @param string $from (default: '') 来源
     * @param string $param (default: '') 参数
     * @param string $type (default: '') 参数类型
     * @param string $exp (default: '') 运算符
     * @param array $return (default: array()) 返回值 (用于递归)
     * @return void
     */
    private function bindProcess($bind, $value, $from = '', $param = '', $type = '', $exp = '', $return = array()) {
        /*print_r('bind: ');
        print_r($bind);
        print_r(PHP_EOL);
        print_r('value: ');
        print_r($value);
        print_r(PHP_EOL);
        print_r('param: ');
        print_r($param);
        print_r(PHP_EOL);
        print_r('type: ');
        print_r($type);
        print_r(PHP_EOL);
        print_r(' ------- ');
        print_r(PHP_EOL);*/

        if ($param != '*' && strpos($param, '(') === false && strpos($param, '`') === false) {
            $_str_bind = $bind;

            if (!Func::isEmpty($param)) {
                $_str_bind = $param;
            }

            if (is_array($value)) {
                switch ($exp) {
                    case 'IN':
                    case 'NOT IN':
                        foreach ($value as $_key_sub=>$_value_sub) {
                            $return[] = array($this->paramChar($_str_bind . '_' . $_key_sub, false, $from), $_value_sub, $type);
                        }
                    break;

                    case 'BETWEEN':
                    case 'NOT BETWEEN':
                        if (!isset($value[0])) {
                            $value[0] = '';
                        }

                        if (!isset($value[1])) {
                            $value[1] = '';
                        }

                        if (is_scalar($value[0]) && is_scalar($value[1])) {
                            $return[] = array($this->paramChar($_str_bind . '_0', false, $from), $value[0], $type);
                            $return[] = array($this->paramChar($_str_bind . '_1', false, $from), $value[1], $type);
                        }
                    break;
                }
            } else if (is_scalar($value) && strpos($value, '(') === false && strpos($value, '`') === false) {
                $return = array($this->paramChar($_str_bind, false, $from), $value, $type);
            }
        }

        return $return;
    }


    /** 是否特殊绑定
     * isSpecBind function.
     *
     * @access private
     * @param mixed $value 值
     * @param mixed $exp 运算符
     * @return void
     */
    private function isSpecBind($value, $exp) {
        $_arr_specExp = array('IN', 'NOT IN', 'BETWEEN', 'NOT BETWEEN');

        return is_array($value) && in_array($exp, $_arr_specExp);
    }


    /** 绑定参数名处理
     * paramChar function.
     *
     * @access private
     * @param mixed $param 参数
     * @param bool $is_sql (default: true) 是否为 sql 命令
     * @param string $from (default: '')
     * @return void
     */
    private function paramChar($param, $is_sql = true, $from = '') {
        if ($is_sql) { // 如果不是 sql, 而是参数, 则加上前缀
            $param = ':' . $param;
        }

        $param .= '_' . $from . '__';

        return $param;
    }
}
