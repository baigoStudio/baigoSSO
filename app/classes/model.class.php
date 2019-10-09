<?php
/*-----------------------------------------------------------------
！！！！警告！！！！
以下为系统文件，请勿修改
-----------------------------------------------------------------*/

namespace app\classes;

use PDO;
use ginkgo\Model as Gk_Model;
use ginkgo\Db;
use ginkgo\Func;

//不能非法包含或直接执行
defined('IN_GINKGO') or exit('Access Denied');

/*-------------单点登录类-------------*/
abstract class Model extends Gk_Model {

    protected $_viewFrom    = '';
    protected $_viewJoin    = '';
    protected $_copyTo      = '';
    protected $_index       = '';


    function viewFrom($from) {
        $this->_viewFrom = $from;

        return $this;
    }


    function viewJoin($join) {
        $this->_viewJoin = $join;

        return $this;
    }


    function copyTo($dst) {
        $this->_copyTo = $dst;

        return $this;
    }


    function index($index) {
        $this->_index = $index;

        return $this;
    }

    function create($field = array(), $pk = '', $comment = '') {
        if (!Func::isEmpty($this->_index)) {
            $_arr_index = $this->show('index');

            if (in_array($this->_index, $_arr_index)) {
                $_str_sqlDrop = 'DROP INDEX ' . $this->obj_builder->addChar($this->_index) . ' ON ' . $this->getTable();
                $_return = $this->exec($_str_sqlDrop);

                if ($_return === false) {
                    return $_return;
                }
            }
        }

        $_str_sql    = $this->buildCreate($field, $pk, $comment);

        $_return     = $this->exec($_str_sql);

        $this->resetSql();

        return $_return;
    }


    function alter($field = array(), $rename = '') {
        $_return = array();

        if (!Func::isEmpty($field) || !Func::isEmpty($rename)) {
            $_str_sql    = 'ALTER TABLE ' . $this->getTable() . ' ';

            if (!Func::isEmpty($rename)) {
                $_str_sql = 'ALTER TABLE ' . $this->obj_builder->table($rename) . ' RENAME TO ' . $this->getTable();
            }

            if (!Func::isEmpty($field)) {
                $_values  = array();

                foreach ($field as $_key => $_value) {
                    switch ($_value[0]) {
                        case 'ADD':
                            $_values[] = 'ADD COLUMN ' . $this->buildField($_key, $_value[1]);
                        break;
                        case 'MODIFY':
                            $_values[] = 'MODIFY COLUMN ' . $this->buildField($_key, $_value[1]);
                        break;
                        case 'CHANGE':
                            $_values[] = 'CHANGE COLUMN ' . $this->obj_builder->addChar($_key) . ' ' . $this->buildField($_value[2], $_value[1]);
                        break;
                        case 'DROP':
                            $_values[] = 'DROP COLUMN ' . $this->obj_builder->addChar($_key);
                        break;
                    }
                }

                $_str_sql .= implode(',', $_values);
            }

            //print_r($_str_sql);

            $_return   = $this->exec($_str_sql);

            $this->resetSql();
        }

        return $_return;
    }


    function copy($field = array(), $pk = '', $comment = '') {
        $_return = false;

        if (!Func::isEmpty($field) && !Func::isEmpty($this->_copyTo)) {
            $_str_sql  = 'CREATE TABLE IF NOT EXISTS ' . $this->obj_builder->table($this->_copyTo) . ' (';

            $_values   = array();

            foreach ($field as $_key => $_value) {
                if (isset($_value['target'])) {
                    $_target = $_value['target'];
                } else {
                    $_target = $_value['src'];
                }

                $_values[] = $this->obj_builder->addChar($_target) . ' ' . $_value['create'];
            }
            $_str_sql     .= implode(',', $_values);

            $_str_sql     .= ', PRIMARY KEY (' . $this->obj_builder->addChar($pk) . ')) ENGINE=InnoDB DEFAULT CHARSET=' . $this->dbconfig['charset'] . ' COMMENT=\'' . $comment . '\' AUTO_INCREMENT=1 COLLATE utf8_general_ci';
            $_str_sql     .= ' SELECT ';

            $_values   = array();

            foreach ($field as $_key => $_value) {
                $_as = '';
                if (isset($_value['target'])) {
                    $_as = ' AS ' . $this->obj_builder->addChar($_value['target']);
                }

                $_values[] = $this->obj_builder->addChar($_value['src']) . $_as;
            }
            $_str_sql .= implode(',', $_values);

            $_str_sql .= ' FROM ' . $this->getTable();

            $_return  = $this->exec($_str_sql);
        }

        $this->resetSql();

        return $_return;
    }


    function show($type = '') {
        $_arr_return    = array();
        $_index         = 0;
        $_result        = PDO::FETCH_NUM;

        switch ($type) {
            case 'index':
                $_index   = 2;
                $_str_sql = 'SHOW INDEX FROM ' . $this->getTable();
            break;

            case 'table':
                $_str_sql = 'SHOW TABLES FROM ' . $this->obj_builder->addChar($this->dbconfig['name']);
            break;

            default:
                $_index   = 'Field';
                $_result  = PDO::FETCH_ASSOC;
                $_str_sql = 'SHOW FULL COLUMNS FROM ' . $this->getTable();
            break;
        }

        $_return = $this->query($_str_sql);

        $_arr_results = $this->getResult(true, $_result);

        if (!Func::isEmpty($_arr_results)) {
            foreach ($_arr_results as $_key=>$_value) {
                if (isset($_value[$_index])) {
                    switch ($type) {
                        case 'index':
                            $_arr_return[] = $_value[$_index];
                        break;

                        case 'table':
                            $_arr_return[] = str_ireplace($this->dbconfig['prefix'], '', $_value[$_index]);
                        break;

                        default:
                            $_arr_return[$_value[$_index]] = array_change_key_case($_value);
                        break;
                    }
                }
            }
        }

        return $_arr_return;
    }


    function alterProcess($create) {
        $_arr_col      = $this->show();
        $_arr_alter    = array();

        foreach ($create as $_key=>$_value) {
            if (isset($_value['old']) && isset($_arr_col[$_value['old']]) && !isset($_arr_col[$_key])) {
                $_arr_alter[$_value['old']] = array('CHANGE', $_value, $_key);
            } else if (isset($_arr_col[$_key])) {
                if (isset($_value['type'])) {
                    if (!isset($_value['default'])) {
                        $_value['default'] = '';
                    }

                    if (!isset($_value['comment'])) {
                        $_value['comment'] = '';
                    }

                    if ($_arr_col[$_key]['type'] != $_value['type'] || $_arr_col[$_key]['default'] != $_value['default'] || $_arr_col[$_key]['comment'] != $_value['comment']) {
                        $_arr_alter[$_key] = array('MODIFY', $_value);
                    }
                }
            } else {
                $_arr_alter[$_key] = array('ADD', $_value);
            }
        }

        return $_arr_alter;
    }


    protected function resetSql() {
        $this->_viewFrom    = '';
        $this->_copyTo      = '';
        $this->_viewJoin    = '';
        $this->_index       = '';

        Db::resetSql();
    }


    private function buildCreate($field = array(), $pk = '', $comment = '') {
        $_str_sql   = '';

        if (!Func::isEmpty($field)) {
            if (!Func::isEmpty($this->_viewFrom) && !Func::isEmpty($this->_viewJoin)) {
                $_str_sql   = 'CREATE OR REPLACE SQL SECURITY INVOKER VIEW ' . $this->getTable() . ' AS ';
                $_str_sql  .= $this->table($this->_viewFrom)->join($this->_viewJoin)->fetchSql()->select($field);;
            } else if (!Func::isEmpty($this->_index)) {
                $_str_field = $this->obj_builder->field($field);

                $_str_sql   = 'CREATE INDEX ' . $this->obj_builder->addChar($this->_index) . ' ON ' . $this->getTable() . ' (' . $_str_field . ') USING BTREE';
            } else {
                $_str_sql   = 'CREATE TABLE IF NOT EXISTS ' . $this->getTable() . ' (';

                $_arr_field = array();

                foreach ($field as $_key => $_value) {
                    $_str_sql .= $this->buildField($_key, $_value) . ',';
                }

                $_str_sql  .= ' PRIMARY KEY (' . $this->obj_builder->addChar($pk) . ')';

                $_str_sql  .= ') ENGINE=InnoDB DEFAULT CHARSET=' . $this->dbconfig['charset'] . ' COMMENT=\'' . $comment . '\' AUTO_INCREMENT=1 COLLATE utf8_general_ci';
            }
        }

        return $_str_sql;
    }

    private function buildField($name = '', $field = array()) {
        $_str_field = '';

        if (isset($field['type'])) {
            $_str_field .= $this->obj_builder->addChar($name) . ' ' . $field['type'];

            if (isset($field['not_null'])) {
                $_str_field .= ' NOT NULL';
            }

            if (isset($field['ai'])) {
                $_str_field .= ' AUTO_INCREMENT';
            } else {
                if (isset($field['default'])) {
                    if (is_numeric($field['default'])) {
                        $_str_field .= ' DEFAULT ' . $field['default'];
                    } else if (is_string($field['default'])) {
                        $_str_field .= ' DEFAULT \'' . $field['default'] . '\'';
                    }
                }
            }

            if (isset($field['comment'])) {
                $_str_field .= ' COMMENT \'' . $field['comment'] . '\'';
            }
        }

        return $_str_field;
    }
}
