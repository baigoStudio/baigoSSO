<?php
/*-----------------------------------------------------------------
！！！！警告！！！！
以下为系统文件，请勿修改
-----------------------------------------------------------------*/

namespace ginkgo\db\connector;

use ginkgo\Func;
use ginkgo\Config;
use ginkgo\db\Connector;

//不能非法包含或直接执行
defined('IN_GINKGO') or exit('Access denied');

/*-------------数据库类-------------*/
class Mysql extends Connector {

    function insertId() {
        $_num_id        = 0;

        //print_r('test');

        $_obj_result    = $this->query('SELECT LAST_INSERT_ID()');

        $_inserResult   = $this->getRow();

        if ($_inserResult > 0) {
            $_num_id = $_inserResult;
        } else {
            $_num_id = $this->lastInsertId();
        }

        return $_num_id;
    }


    function table($table) {
        $this->_tableTemp[$this->mid] = $this->obj_builder->table($table);

        return $this;
    }


    function force($index) {
        $this->_force = $this->obj_builder->force($index);

        return $this;
    }


    function join($table, $on = '', $type = '') {
        $this->_join = $this->obj_builder->join($table, $on, $type);

        return $this;
    }


    function where($where, $exp = '', $value = '', $type = '', $param = '') {
        $_arr_result = $this->obj_builder->where($where, $exp, $value, $type, $param);

        $this->_where = $_arr_result['where'];
        $this->_bind  = $_arr_result['bind'];

        return $this;
    }


    function whereAnd($where, $exp = '', $value = '', $type = '', $param = '') {
        $_arr_result = $this->obj_builder->where($where, $exp, $value, $type, $param);

        if (!Func::isEmpty($_arr_result['where'])) {
            $this->_whereAnd[]  = '(' . $_arr_result['where'] . ')';
            if (Func::isEmpty($this->_bind)) {
                $this->_bind        = $_arr_result['bind'];
            } else {
                $this->_bind        = array_merge($this->_bind, $_arr_result['bind']);
            }
        }

        return $this;
    }


    function whereOr($where, $exp = '', $value = '', $type = '', $param = '') {
        $_arr_result = $this->obj_builder->where($where, $exp, $value, $type, $param);

        if (!Func::isEmpty($_arr_result['where'])) {
            $this->_whereOr[]   = '(' . $_arr_result['where'] . ')';
            if (Func::isEmpty($this->_bind)) {
                $this->_bind        = $_arr_result['bind'];
            } else {
                $this->_bind        = array_merge($this->_bind, $_arr_result['bind']);
            }
        }

        return $this;
    }


    function group($field) {
        $this->_group = $this->obj_builder->group($field);

        return $this;
    }


    function order($field, $order = '') {
        $this->_order = $this->obj_builder->order($field, $order);

        return $this;
    }


    function limit($offset = 1, $length = false) {
        $this->_limit = $this->obj_builder->limit($offset, $length);

        return $this;
    }


    function find($field = '') {
        $this->limit();

        $_arr_result = $this->select($field, false);

        return $_arr_result;
    }


    function select($field = '', $all = true) {
        $_str_sql = $this->buildSelect($field);

        /*print_r($this->_bind);
        print_r(' -+-+-+-+-+-+-+-+- ');
        print_r(PHP_EOL);*/

        if ($this->_fetchSql === true) {
            return $this->fetchBind($_str_sql, $this->_bind);
        } else {
            $this->prepare($_str_sql, $this->_bind);

            $this->execute();

            //print_r($this->obj_result->debugDumpParams());
            //print_r('<br>');

            return $this->getResult($all);
        }
    }


    function insert($field, $value = '', $param = '', $type = '') {
        $_arr_result = $this->buildInsert($field, $value, $param , $type);

        if ($this->_fetchSql === true) {
            return $this->fetchBind($_arr_result['sql'], $_arr_result['bind']);
        } else {
            $this->prepare($_arr_result['sql']);

            $this->execute($_arr_result['bind']);

            //print_r($this->obj_result->debugDumpParams());

            return $this->insertId();
        }
    }


    function update($field, $value = '', $param = '', $type = '') {
        $_arr_result = $this->buildUpdate($field, $value, $param , $type);

        if ($this->_fetchSql === true) {
            $_str_sql = $this->fetchBind($_arr_result['sql'], $this->_bind);
            $_str_sql = $this->fetchBind($_arr_result['sql'], $_arr_result['bind']);

            return $_str_sql;
        } else {
            $this->prepare($_arr_result['sql'], $this->_bind);

            $this->execute($_arr_result['bind']);

            //print_r($this->obj_result->debugDumpParams());

            return $this->getRowCount();
        }
    }


    function delete() {
        $_str_sql = $this->buildDelete();

        if ($this->_fetchSql === true) {
            return $this->fetchBind($_str_sql, $this->_bind);
        } else {
            $this->prepare($_str_sql, $this->_bind);

            $this->execute();

            //print_r($this->obj_result->debugDumpParams());

            return $this->getRowCount();
        }
    }


    function duplicate($field = array(), $table = '') {
        $_str_sql = $this->buildDuplicate($field, $table);

        //print_r($_str_sql);

        if ($this->_fetchSql === true) {
            return $this->fetchBind($_str_sql, $this->_bind);
        } else {
            $this->prepare($_str_sql, $this->_bind);

            $this->execute();

            return $this->insertId();
        }
    }


    function count($field = '') {
        return $this->aggProcess('count', $field);
    }


    function max($field) {
        return $this->aggProcess('max', $field);
    }


    function min($field) {
        return $this->aggProcess('min', $field);
    }


    function avg($field) {
        return $this->aggProcess('avg', $field);
    }


    function sum($field) {
        return $this->aggProcess('sum', $field);
    }


    function buildSql() {
        $_str_sql = $this->buildWhere(false);

        //print_r($_str_sql);

        return trim($this->fetchBind($_str_sql, $this->_bind));
    }


    protected function buildSelect($field = '') {
        $_str_field = $this->obj_builder->field($field);

        $_str_sql = 'SELECT';

        if ($this->_distinct === true) {
            $_str_sql .= ' DISTINCT';
        }

        $_str_sql .= ' ' . $_str_field . ' FROM ' . $this->getTable();

        if (!Func::isEmpty($this->_force)) {
            $_str_sql .= ' FORCE INDEX (' . $this->_force . ')';
        }

        if (!Func::isEmpty($this->_join)) {
            $_str_sql .= ' ' . $this->_join;
        }

        $_str_sql .= $this->buildWhere();

        if (!Func::isEmpty($this->_group)) {
            $_str_sql .= ' GROUP BY ' . $this->_group;
        }

        if (!Func::isEmpty($this->_order)) {
            $_str_sql .= ' ORDER BY ' . $this->_order;
        }

        if (!Func::isEmpty($this->_limit)) {
            $_str_sql .= ' LIMIT ' . $this->_limit;
        }

        return $_str_sql;
    }


    protected function buildAgg($type, $field = '') {
        $type = strtoupper($type);
        $_str_func = '';

        switch ($type) {
            case 'MAX':
                $_str_func = 'MAX';
            break;

            case 'MIN':
                $_str_func = 'MIN';
            break;

            case 'AVG':
                $_str_func = 'AVG';
            break;

            case 'SUM':
                $_str_func = 'SUM';
            break;

            default:
                $_str_func = 'COUNT';
            break;
        }

        $_str_field = $this->obj_builder->field($field);

        $_str_sql = 'SELECT ' . $_str_func . '(' . $_str_field . ') FROM';

        if (Func::isEmpty($this->_group)) {
            $_str_sql .= ' ' . $this->getTable();
        } else {
            $_str_sql .= ' (SELECT ' . $_str_func . '(' . $_str_field . ') FROM ' . $this->getTable();
        }


        if (!Func::isEmpty($this->_force)) {
            $_str_sql .= ' FORCE INDEX (' . $this->_force . ')';
        }

        if (!Func::isEmpty($this->_join)) {
            $_str_sql .= ' ' . $this->_join;
        }

        $_str_sql .= $this->buildWhere();

        if (!Func::isEmpty($this->_group)) {
            $_str_sql .= ' GROUP BY ' . $this->_group . ') a';
        }

        //print_r($_str_sql);

        return $_str_sql;
    }


    protected function buildInsert($field, $value = '', $param = '', $type = '') {
        $_arr_result = $this->obj_builder->insert($field, $value, $param, $type);

        $_str_sql = 'INSERT INTO ' . $this->getTable() . ' SET ' . $_arr_result['insert'];

        return array(
            'sql'   => $_str_sql,
            'bind'  => $_arr_result['bind'],
        );
    }


    protected function buildUpdate($field, $value = '', $param = '', $type = '') {
        $_arr_result = $this->obj_builder->update($field, $value, $param, $type);

        $_str_sql = 'UPDATE ' . $this->getTable() . ' SET ' . $_arr_result['update'];

        $_str_sql .= $this->buildWhere();

        return array(
            'sql'   => $_str_sql,
            'bind'  => $_arr_result['bind'],
        );
    }


    protected function buildDelete() {
        $_str_sql = 'DELETE FROM ' . $this->getTable();

        $_str_sql .= $this->buildWhere();

        return $_str_sql;
    }


    protected function buildDuplicate($field = array(), $table = '') {
        if (Func::isEmpty($table)) {
            $_str_table = $this->getTable();
        } else {
            $_str_table = $this->obj_builder->table($table);
        }

        $_str_sql  = 'INSERT INTO ' . $_str_table;

        $_str_sql .= ' (' . $this->obj_builder->field($field) . ')';

        $_str_sql .= ' ' . $this->buildSelect($field);

        return $_str_sql;
    }


    protected function buildWhere($add_where = true) {
        $_str_sql = '';

        if (!Func::isEmpty($this->_where)) {
            $_str_sql .= $this->_where;
        }

        if (!Func::isEmpty($this->_whereAnd)) {
            $_str_whereAnd = implode(' AND ', $this->_whereAnd);

            if (!Func::isEmpty($_str_sql)) {
                $_str_sql .= ' AND';
            }

            $_str_sql .= ' ' . $_str_whereAnd;
        }

        if (!Func::isEmpty($this->_whereOr)) {
            $_str_whereOr = implode(' OR ', $this->_whereOr);

            if (!Func::isEmpty($_str_sql)) {
                $_str_sql .= ' OR';
            }

            $_str_sql .= ' ' . $_str_whereOr;
        }

        if (!Func::isEmpty($_str_sql) && $add_where) {
            $_str_sql = ' WHERE ' . $_str_sql;
        }

        return $_str_sql;
    }


    protected function aggProcess($type, $field) {
        $_str_sql = $this->buildAgg($type, $field);

        if ($this->_fetchSql === true) {
            return $this->fetchBind($_str_sql, $this->_bind);
        } else {
            $this->prepare($_str_sql, $this->_bind);

            $this->execute();

            //print_r($this->obj_result->debugDumpParams());

            return $this->getRow();
        }
    }
}
