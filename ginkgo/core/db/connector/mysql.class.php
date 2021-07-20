<?php
/*-----------------------------------------------------------------
！！！！警告！！！！
以下为系统文件，请勿修改
-----------------------------------------------------------------*/

namespace ginkgo\db\connector;

use ginkgo\Func;
use ginkgo\Log;
use ginkgo\Config;
use ginkgo\Paginator;
use ginkgo\db\Connector;

// 不能非法包含或直接执行
if (!defined('IN_GINKGO')) {
    return 'Access denied';
}

// 数据库连接器类
class Mysql extends Connector {

    /** 取得插入 id
     * insertId function.
     *
     * @access public
     * @return void
     */
    public function insertId() {
        $_num_id        = 0;

        //print_r('test');

        $_obj_result    = $this->query('SELECT LAST_INSERT_ID()'); // 尝试使用 sql 语句

        $_inserResult   = $this->getRow(); // 取得行内容

        if ($_inserResult > 0) { // 成功直接使用
            $_num_id = $_inserResult;
        } else { // 失败使用 pdo 函数
            $_num_id = $this->lastInsertId();
        }

        return $_num_id;
    }


    /** 切换数据表
     * table function.
     *
     * @access public
     * @param mixed $table 表明
     * @return 当前实例
     */
    public function table($table) {
        $this->_tableTemp[$this->mid] = $this->obj_builder->table($table);

        return $this;
    }


    /** 强制使用索引名
     * force function.
     *
     * @access public
     * @param mixed $index 索引名
     * @return 当前实例
     */
    public function force($index) {
        $this->_force = $this->obj_builder->force($index);

        return $this;
    }


    /** join 语句处理
     * join function.
     *
     * @access public
     * @param mixed $table join 的表
     * @param string $on (default: '') on 条件
     * @param string $type (default: '') join 类型
     * @return 当前实例
     */
    public function join($table, $on = '', $type = '') {
        $this->_join = $this->obj_builder->join($table, $on, $type);

        return $this;
    }


    /** where 语句处理
     * where function.
     *
     * @access public
     * @param mixed $where 条件
     * @param string $exp (default: '') 运算符
     * @param string $value (default: '') 值
     * @param string $param (default: '') 参数
     * @param string $type (default: '') 参数类型
     * @return 当前实例
     */
    public function where($where, $exp = '', $value = '', $param = '', $type = '') {
        $_arr_sql = $this->obj_builder->where($where, $exp, $value, $param, $type);

        $this->_where = $_arr_sql['where'];
        $this->_bind  = $_arr_sql['bind'];

        return $this;
    }


    /** whereAnd 语句处理
     * whereAnd function.
     *
     * @access public
     * @param mixed $where 条件
     * @param string $exp (default: '') 运算符
     * @param string $value (default: '') 值
     * @param string $param (default: '') 参数
     * @param string $type (default: '') 参数类型
     * @return 当前实例
     */
    public function whereAnd($where, $exp = '', $value = '', $param = '', $type = '') {
        $_arr_sql = $this->obj_builder->where($where, $exp, $value, $param, $type);

        if (!Func::isEmpty($_arr_sql['where'])) {
            $this->_whereAnd[]  = '(' . $_arr_sql['where'] . ')';
            if (Func::isEmpty($this->_bind)) {
                $this->_bind        = $_arr_sql['bind'];
            } else {
                $this->_bind        = array_merge($this->_bind, $_arr_sql['bind']);
            }
        }

        return $this;
    }


    /** whereOr 语句处理
     * whereOr function.
     *
     * @access public
     * @param mixed $where 条件
     * @param string $exp (default: '') 运算符
     * @param string $value (default: '') 值
     * @param string $param (default: '') 参数
     * @param string $type (default: '') 参数类型
     * @return 当前实例
     */
    public function whereOr($where, $exp = '', $value = '', $param = '', $type = '') {
        $_arr_sql = $this->obj_builder->where($where, $exp, $value, $param, $type);

        if (!Func::isEmpty($_arr_sql['where'])) {
            $this->_whereOr[]   = '(' . $_arr_sql['where'] . ')';
            if (Func::isEmpty($this->_bind)) {
                $this->_bind        = $_arr_sql['bind'];
            } else {
                $this->_bind        = array_merge($this->_bind, $_arr_sql['bind']);
            }
        }

        return $this;
    }


    /** group 语句处理
     * group function.
     *
     * @access public
     * @param mixed $field 字段
     * @return 当前实例
     */
    public function group($field) {
        $this->_group = $this->obj_builder->group($field);

        return $this;
    }


    /** order 语句处理
     * order function.
     *
     * @access public
     * @param mixed $field
     * @param string $order (default: '')
     * @return 当前实例
     */
    public function order($field, $order = '') {
        $this->_order = $this->obj_builder->order($field, $order);

        return $this;
    }


    /** limit 语句处理
     * limit function.
     *
     * @access public
     * @param int $limit
     * @param bool $length (default: false)
     * @return 当前实例
     */
    public function limit($limit = false, $length = false) {
        $this->_limit = $this->obj_builder->limit($limit, $length);

        return $this;
    }


    /** 读取一条记录
     * find function.
     *
     * @access public
     * @param string $field (default: '') 字段名
     * @return 读取结果
     */
    public function find($field = '') {
        $this->limit(1);

        $_arr_result = $this->select($field, false); // 执行 select 查询

        return $_arr_result;
    }


    /** 执行 select 查询
     * select function.
     *
     * @access public
     * @param string $field (default: '') 字段
     * @param bool $all (default: true) 是否全部记录
     * @return 查询结果
     */
    public function select($field = '', $all = true) {
        $_arr_return    = array();
        $_arr_pageRow   = array();
        $_str_realSql   = ''; // 真实 sql 语句

        if (Func::isEmpty($this->_limit) && !Func::isEmpty($this->_paginate)) { // 如果没有指定 limit 且指定了分页参数, 则执行分页
            $_arr_pageRow = $this->pagination($this->_paginate['perpage'], $this->_paginate['current'], $this->_paginate['pageparam'], $this->_paginate['pergroup'], false);

            $this->limit($_arr_pageRow['offset'], $this->_paginate['perpage']);
        }

        $_str_sql = $this->buildSelect($field); // 构建 select 语句

        if ($this->_fetchSql === true || $this->optDebugDump === 'trace') { // 如果调试模式打开
            $_str_realSql = $this->fetchBind($_str_sql, $this->_bind); // 取得绑定处理
        }

        if ($this->_fetchSql === true) { // 如果为获取 sql
            $this->resetSql(); // 重置 sql
            return $_str_realSql; // 返回 sql 语句
        } else {
            if ($this->optDebugDump === 'trace') { // 如果调试模式为追踪模式
                Log::record($_str_realSql, 'sql'); // 记录日志
            }

            $this->prepare($_str_sql, $this->_bind); // 预处理语句

            $this->execute(); // 执行

            $_arr_dataRows = $this->getResult($all); // 获取数据集

            if (Func::isEmpty($_arr_pageRow)) {
                $_arr_return = $_arr_dataRows;
            } else {
                $_arr_return    = array(
                    'dataRows'  => $_arr_dataRows, // 获取数据集
                    'pageRow'   => $_arr_pageRow, // 获取数据集
                );
            }

            return $_arr_return;
        }
    }


    /** 插入记录
     * insert function.
     *
     * @access public
     * @param mixed $field 字段
     * @param string $value (default: '') 值
     * @param string $param (default: '') 参数
     * @param string $type (default: '') 参数类型
     * @return void
     */
    public function insert($field, $value = '', $param = '', $type = '') {
        $_arr_sql        = $this->buildInsert($field, $value, $param , $type); // 构建 insert 语句
        $_str_realSql    = ''; // 真实 sql 语句

        if ($this->_fetchSql === true || $this->optDebugDump === 'trace') { // 如果调试模式打开
            $_str_realSql = $this->fetchBind($_arr_sql['sql'], $_arr_sql['bind']); // 取得绑定处理
        }

        if ($this->_fetchSql === true) { // 如果为获取 sql
            $this->resetSql(); // 重置 sql
            return $_str_realSql; // 返回 sql 语句
        } else {
            if ($this->optDebugDump === 'trace') { // 如果调试模式为追踪模式
                Log::record($_str_realSql, 'sql'); // 记录日志
            }

            $this->prepare($_arr_sql['sql']); // 预处理语句

            $this->execute($_arr_sql['bind']); // 执行

            //print_r($this->obj_result->debugDumpParams());

            return $this->insertId(); // 取得插入的 id
        }
    }


    /** 更新记录
     * update function.
     *
     * @access public
     * @param mixed $field 字段
     * @param string $value (default: '') 值
     * @param string $param (default: '') 参数
     * @param string $type (default: '') 参数类型
     * @return void
     */
    public function update($field, $value = '', $param = '', $type = '') {
        $_arr_sql        = $this->buildUpdate($field, $value, $param , $type); // 构建 update 语句
        $_str_realSql    = ''; // 真实 sql 语句

        if ($this->_fetchSql === true || $this->optDebugDump === 'trace') { // 如果调试模式打开
            $_str_realSql = $this->fetchBind($_arr_sql['sql'], $this->_bind); // 取得绑定处理
            $_str_realSql = $this->fetchBind($_str_realSql, $_arr_sql['bind']);
        }

        if ($this->_fetchSql === true) { // 如果为获取 sql
            $this->resetSql(); // 重置 sql
            return $_str_realSql; // 返回 sql 语句
        } else {
            if ($this->optDebugDump === 'trace') { // 如果调试模式为追踪模式
                Log::record($_str_realSql, 'sql'); // 记录日志
            }

            $this->prepare($_arr_sql['sql'], $this->_bind); // 预处理语句

            $this->execute($_arr_sql['bind']); // 执行

            //print_r($this->obj_result->debugDumpParams());

            return $this->getRowCount(); // 取得影响行数
        }
    }


    /** 删除记录
     * delete function.
     *
     * @access public
     * @return void
     */
    public function delete() {
        $_str_sql        = $this->buildDelete(); // 构建 delete 语句
        $_str_realSql    = ''; // 真实 sql 语句

        if ($this->_fetchSql === true || $this->optDebugDump === 'trace') { // 如果调试模式打开
            $_str_realSql = $this->fetchBind($_str_sql, $this->_bind); // 取得绑定处理
        }

        if ($this->_fetchSql === true) { // 如果为获取 sql
            $this->resetSql(); // 重置 sql
            return $_str_realSql; // 返回 sql 语句
        } else {
            if ($this->optDebugDump === 'trace') { // 如果调试模式为追踪模式
                Log::record($_str_realSql, 'sql'); // 记录日志
            }

            $this->prepare($_str_sql, $this->_bind); // 预处理语句

            $this->execute(); // 执行

            //print_r($this->obj_result->debugDumpParams());

            return $this->getRowCount(); // 取得影响行数
        }
    }


    /** 克隆数据
     * duplicate function.
     *
     * @access public
     * @param array $field (default: array()) 字段
     * @param string $table (default: '') 目的表名
     * @return void
     */
    public function duplicate($field = array(), $table = '') {
        $_str_sql        = $this->buildDuplicate($field, $table); // 构建 duplicate 语句
        $_str_realSql    = ''; // 真实 sql 语句

        if ($this->_fetchSql === true || $this->optDebugDump === 'trace') { // 如果调试模式打开
            $_str_realSql = $this->fetchBind($_str_sql, $this->_bind); // 取得绑定处理
        }

        if ($this->_fetchSql === true) { // 如果为获取 sql
            $this->resetSql(); // 重置 sql
            return $_str_realSql; // 返回 sql 语句
        } else {
            if ($this->optDebugDump === 'trace') { // 如果调试模式为追踪模式
                Log::record($_str_realSql, 'sql'); // 记录日志
            }

            $this->prepare($_str_sql, $this->_bind); // 预处理语句

            $this->execute(); // 执行

            return $this->insertId(); // 取得插入的 id
        }
    }


    /** 统计分页
     * pagination function.
     *
     * @access public
     * @param int $perpage (default: 0) 每页记录
     * @param string $current (default: 'get') 当前页
     * @param string $pageparam (default: 'page') 分页参数
     * @param int $pergroup (default: 0) 每组页数
     * @param bool $reset (default: true) 是否重置查询条件
     * @return void
     */
    public function pagination($perpage = 0, $current = 'get', $pageparam = 'page', $pergroup = 0, $reset = true) {
        $_num_count = $this->count('', $reset);

        $_obj_paginator = Paginator::instance();

        $_obj_paginator->count($_num_count);
        $_obj_paginator->perpage($perpage);
        $_obj_paginator->pergroup($pergroup);
        $_obj_paginator->pageparam($pageparam);

        return $_obj_paginator->make($current);
    }


    /** 记录数
     * count function.
     *
     * @access public
     * @param string $field (default: '') 字段
     * @return void
     */
    public function count($field = '', $reset = true) {
        return $this->aggProcess('count', $field, $reset);
    }


    /** 最大值
     * max function.
     *
     * @access public
     * @param mixed $field 字段
     * @return void
     */
    public function max($field) {
        return $this->aggProcess('max', $field);
    }


    /** 最小值
     * min function.
     *
     * @access public
     * @param mixed $field 字段
     * @return void
     */
    public function min($field) {
        return $this->aggProcess('min', $field);
    }


    /** 平均值
     * avg function.
     *
     * @access public
     * @param mixed $field 字段
     * @return void
     */
    public function avg($field) {
        return $this->aggProcess('avg', $field);
    }


    /** 和
     * sum function.
     *
     * @access public
     * @param mixed $field 字段
     * @return void
     */
    public function sum($field) {
        return $this->aggProcess('sum', $field);
    }


    /** 构建 sql 语句
     * buildSql function.
     *
     * @access public
     * @return sql 语句
     */
    public function buildSql() {
        $_str_sql = $this->buildWhere(false); // 构建 where 语句

        //print_r($_str_sql);

        $_str_sql = trim($this->fetchBind($_str_sql, $this->_bind)); // 取得绑定处理

        $this->resetSql(); // 重置 sql

        return $_str_sql;
    }


    /** 聚合处理
     * aggProcess function.
     *
     * @access private
     * @param mixed $type 类型
     * @param mixed $field 字段
     * @return void
     */
    private function aggProcess($type, $field, $reset = true) {
        $_str_sql       = $this->buildAgg($type, $field); // 构建聚合语句
        $_str_realSql   = ''; // 真实 sql 语句

        if ($this->_fetchSql === true || $this->optDebugDump === 'trace') { // 如果调试模式打开
            $_str_realSql = $this->fetchBind($_str_sql, $this->_bind); // 取得绑定处理
        }

        if ($this->_fetchSql === true) { // 如果为获取 sql
            if ($reset) {
                $this->resetSql(); // 重置 sql
            }
            return $_str_realSql; // 返回 sql 语句
        } else {
            if ($this->optDebugDump === 'trace') { // 如果调试模式为追踪模式
                Log::record($_str_realSql, 'sql'); // 记录日志
            }

            $this->prepare($_str_sql, $this->_bind); // 预处理

            $this->execute(array(), '', '', $reset); // 执行

            //print_r($this->obj_result->debugDumpParams());

            return $this->getRow(); // 取得当前记录
        }
    }


    /** 构建 select 语句
     * buildSelect function.
     *
     * @access private
     * @param string $field (default: '') 字段
     * @return sql 语句
     */
    private function buildSelect($field = '') {
        $_str_field = $this->obj_builder->field($field);

        $_str_sql = 'SELECT';

        if ($this->_distinct === true) {
            $_str_sql .= ' DISTINCT'; // 不重复记录
        }

        $_str_sql .= ' ' . $_str_field . ' FROM ' . $this->getTable();

        if (!Func::isEmpty($this->_force)) {
            $_str_sql .= ' FORCE INDEX (' . $this->_force . ')'; // 强制使用索引
        }

        if (!Func::isEmpty($this->_join)) {
            $_str_sql .= ' ' . $this->_join; // join
        }

        $_str_sql .= $this->buildWhere(); // 构建 where 语句

        if (!Func::isEmpty($this->_group)) {
            $_str_sql .= ' GROUP BY ' . $this->_group; // group
        }

        if (!Func::isEmpty($this->_order)) {
            $_str_sql .= ' ORDER BY ' . $this->_order; // order
        }

        if (!Func::isEmpty($this->_limit)) {
            $_str_sql .= ' LIMIT ' . $this->_limit; // limit
        }

        return $_str_sql;
    }


    /** 构建 insert 语句
     * buildInsert function.
     *
     * @access private
     * @param mixed $field 字段
     * @param string $value (default: '') 值
     * @param string $param (default: '') 参数
     * @param string $type (default: '') 类型
     * @return sql 语句及绑定参数
     */
    private function buildInsert($field, $value = '', $param = '', $type = '') {
        $_arr_sql = $this->obj_builder->insert($field, $value, $param, $type);

        $_str_sql = 'INSERT INTO ' . $this->getTable() . ' SET ' . $_arr_sql['insert'];

        return array(
            'sql'   => $_str_sql,
            'bind'  => $_arr_sql['bind'],
        );
    }


    /** 构建 update 语句
     * buildUpdate function.
     *
     * @access private
     * @param mixed $field 字段
     * @param string $value (default: '') 值
     * @param string $param (default: '') 参数
     * @param string $type (default: '') 类型
     * @return sql 语句及绑定参数
     */
    private function buildUpdate($field, $value = '', $param = '', $type = '') {
        $_arr_sql = $this->obj_builder->update($field, $value, $param, $type);

        $_str_sql = 'UPDATE ' . $this->getTable() . ' SET ' . $_arr_sql['update'];

        $_str_sql .= $this->buildWhere(); // 构建 where 语句

        return array(
            'sql'   => $_str_sql,
            'bind'  => $_arr_sql['bind'],
        );
    }


    /** 构建 delete 语句
     * buildDelete function.
     *
     * @access private
     * @return sql 语句
     */
    private function buildDelete() {
        $_str_sql = 'DELETE FROM ' . $this->getTable();

        $_str_sql .= $this->buildWhere(); // 构建 where 语句

        return $_str_sql;
    }


    /** 构建 duplicate 语句
     * buildDuplicate function.
     *
     * @access private
     * @param array $field (default: array()) 字段
     * @param string $table (default: '') 目的地表名
     * @return sql 语句
     */
    private function buildDuplicate($field = array(), $table = '') {
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


    /** 构建 where 语句
     * buildWhere function.
     *
     * @access private
     * @param bool $add_where (default: true) 是否添加 "WHERE"
     * @return void
     */
    private function buildWhere($add_where = true) {
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


    /** 构建聚合 sql 语句
     * buildAgg function.
     *
     * @access private
     * @param mixed $type 类型 (聚合函数)
     * @param string $field (default: '') 字段
     * @return sql 语句
     */
    private function buildAgg($type, $field = '') {
        $type       = strtoupper($type);
        $_str_func  = '';

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

        $_str_field = $this->obj_builder->field($field); // 构建字段

        $_str_sql = 'SELECT ' . $_str_func . '(' . $_str_field . ') FROM';

        if (Func::isEmpty($this->_group)) {
            $_str_sql .= ' ' . $this->getTable();
        } else {
            $_str_sql .= ' (SELECT ' . $_str_func . '(' . $_str_field . ') FROM ' . $this->getTable();
        }


        if (!Func::isEmpty($this->_force)) {
            $_str_sql .= ' FORCE INDEX (' . $this->_force . ')'; // 强制索引
        }

        if (!Func::isEmpty($this->_join)) {
            $_str_sql .= ' ' . $this->_join; // join
        }

        $_str_sql .= $this->buildWhere(); // 构建 where 语句

        if (!Func::isEmpty($this->_group)) {
            $_str_sql .= ' GROUP BY ' . $this->_group . ') a'; // group
        }

        //print_r($_str_sql);

        return $_str_sql;
    }}
