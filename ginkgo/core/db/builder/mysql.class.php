<?php
/*-----------------------------------------------------------------
！！！！警告！！！！
以下为系统文件，请勿修改
-----------------------------------------------------------------*/

namespace ginkgo\db\builder;

use ginkgo\Func;

//不能非法包含或直接执行
defined('IN_GINKGO') or exit('Access denied');

/*-------------数据库类-------------*/
class Mysql {

    protected static $instance;
    private $dbconfig;

    // 查询对象实例
    private $query;

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


    private $compopr = array(
        '=', '<>', '>', '>=', '<', '<='
    );

    private $logic = array(
        'AND', 'OR'
    );

    private $order = array(
        'DESC', 'ASC'
    );

    private $join = array(
        'INNER', 'LEFT', 'RIGHT', 'FULL'
    );


    protected function __construct($dbconfig = array()) {
        $this->config($dbconfig);
    }

    protected function __clone() {

    }

    public static function instance($dbconfig = array()) {
        if (Func::isEmpty(static::$instance)) {
            static::$instance = new static($dbconfig);
        }
        return static::$instance;
    }


    function config($dbconfig = array()) {
        $this->dbconfig = $dbconfig;

        $this->configProcess();

        $this->isConfig = true;
    }


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


    function insert($field, $value = '', $param = '', $type = '') {
        $_arr_result = $this->update($field, $value, $param, $type, 'insert');

        return array(
            'insert' => $_arr_result['update'],
            'bind'   => $_arr_result['bind'],
        );
    }


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

                    $_arr_condition[$_key]  = $this->buildUpdate($_key, $_value[1], $_value[0], $from);
                    $_arr_bind[]            = $this->buildBind($_key, $_value[0], $from, $_value[1], $_value[2]);

                } else if (is_scalar($_value)) {
                    $_arr_condition[$_key]  = $this->buildUpdate($_key, $_key, $_value, $from);
                    $_arr_bind[]            = $this->buildBind($_key, $_value, $from);
                }
            }

            $_str_update = implode(',', $_arr_condition);
        } else if (is_scalar($field)) {
            if (Func::isEmpty($value)) {
                $_str_update = $field;
            } else {
                $_str_update = $this->buildUpdate($field, $param, $value, $from);
                $_arr_bind   = $this->buildBind($field, $value, $from, $param, $type);
            }
        }

        return array(
            'update' => $_str_update,
            'bind'   => $_arr_bind,
        );
    }


    function table($table) {
        return $this->addChar($this->dbconfig['prefix'] . $table);
    }


    function force($index) {
        return $this->addChar($index);
    }


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

                                $_str_condition = $this->buildJoin($_value[0], $_value[1], $_value[2]);

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

                    $_str_join = $this->buildJoin($join[0], $join[1], $join[2]);
                }
            }
        } else if (is_scalar($join)) {
            if (Func::isEmpty($on)) {
                $_str_join = $join;
            } else {
                $_str_join = $this->buildJoin($join, $on, $type);
            }
        }

        return $_str_join;
    }


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
                                    $_str_condition = $this->buildWhereSub($_value[0], $_value[1], $_value[3], $_value[2]);
                                } else {
                                    $_str_condition = $this->buildWhere($_value[0], $_value[1], $_value[3], $_value[2]);
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
                                        $_arr_bind = $this->buildBind($_value[0], $_value[2], 'where', $_value[3], $_value[4], $_value[1], $_arr_bind);
                                    } else {
                                        $_arr_bind[] = $this->buildBind($_value[0], $_value[2], 'where', $_value[3], $_value[4], $_value[1]);
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
                        $_str_where = $this->buildWhereSub($where[0], $where[1], $where[3], $where[2]);
                    } else {
                        $_str_where = $this->buildWhere($where[0], $where[1], $where[3], $where[2]);
                    }

                    $_arr_bind = $this->buildBind($where[0], $where[2], 'where', $where[3], $where[4], $where[1]);
                }
            }
        } else if (is_scalar($where)) {
            if (Func::isEmpty($exp)) {
                $_str_where = $where;
            } else {
                if (strpos($where, '|') || strpos($where, '&')) {
                    $_str_where = $this->buildWhereSub($where, $exp, $param, $value);
                } else {
                    $_str_where = $this->buildWhere($where, $exp, $param, $value);
                }

                $_arr_bind = $this->buildBind($where, $value, 'where', $param, $type, $exp);
            }
        }

        return array(
            'where' => $_str_where,
            'bind'  => $_arr_bind,
        );
    }


    function group($field) {
        return $this->field($field);
    }


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

                                $_str_condition = $this->buildOrder($_value[0], $_value[1]);

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

                    $_str_order = $this->buildOrder($field[0], $field[1]);
                }
            }
        } else if (is_scalar($field)) {
            if (Func::isEmpty($type)) {
                $_str_order = $field;
            } else {
                $_str_order = $this->buildOrder($field, $type);
            }
        }

        return $_str_order;
    }


    function limit($offset = 1, $length = false) {
        if ($length === false) {
            return $offset;
        } else {
            return $offset . ', ' . $length;
        }
    }


    function addChar($value) {
        //如果包含* 或者 使用了sql方法 则不作处理
        if ($value != '*' && strpos($value, '(') === false && strpos($value, '`') === false) {
            $value = '`' . trim($value) . '`';
        }

        return $value;
    }


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


    private function buildUpdate($field, $param = '', $value = '', $from = 'update') {
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


    private function buildJoin($join, $on = '', $type = 'INNER') {
        $_str_join = '';

        if (Func::isEmpty($type)) {
            $type = 'INNER';
        }

        if (is_array($on)) {
            if (isset($on[0])) {
                if (is_array($on[0])) {
                    foreach ($on[0] as $_key=>$_value) {
                        if (isset($_value[0]) && isset($_value[1]) && isset($_value[2])) {
                            $_str_condition = $this->buildOn($_value[0], $_value[1], $_value[2]);
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
                        $_str_on = $this->buildOn($on[0], $on[1], $on[2]);
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


    private function buildOn($fidle_1 = '', $compopr = '', $fidle_2 = '') {
        if (Func::isEmpty($compopr)) {
            $compopr = '=';
        }

        $_str_compopr = $this->inArrayProcess($compopr, 'compopr');

        return $this->fieldProcess($fidle_1) . $compopr . $this->fieldProcess($fidle_2);
    }


    private function buildWhere($field, $exp = '=', $param = '', $value = '') {
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


    private function buildWhereSub($where, $exp = '=', $param = '', $value = '') {
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
                $_arr_condition[] = $this->buildWhere($_value, $exp, $param, $value);
            }

            $_str_where = '(' . implode(' ' . $_str_logic . ' ', $_arr_condition) . ')';
        }

        return $_str_where;
    }


    private function buildOrder($field, $type = 'ASC') {
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


    private function buildBind($bind, $value, $from = '', $param = '', $type = '', $exp = '', $return = array()) {
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


    private function isSpecBind($value, $exp) {
        $_arr_specExp = array('IN', 'NOT IN', 'BETWEEN', 'NOT BETWEEN');

        return is_array($value) && in_array($exp, $_arr_specExp);
    }

    //绑定参数名处理
    private function paramChar($param, $is_sql = true, $from = '') {
        if ($is_sql) {
            $param = ':' . $param;
        }

        $param .= '_' . $from . '__';

        return $param;
    }

    private function configProcess() {
        isset($this->dbconfig['host']) or $this->dbconfig['host'] = 'localhost';
        isset($this->dbconfig['name']) or $this->dbconfig['name'] = '';
        isset($this->dbconfig['user']) or $this->dbconfig['user'] = '';
        isset($this->dbconfig['pass']) or $this->dbconfig['pass'] = '';
        isset($this->dbconfig['charset']) or $this->dbconfig['charset'] = 'utf8';
        isset($this->dbconfig['prefix']) or $this->dbconfig['prefix'] = '';
        isset($this->dbconfig['debug']) or $this->dbconfig['debug'] = false;
        isset($this->dbconfig['port']) or $this->dbconfig['port'] = 3306;
    }
}
