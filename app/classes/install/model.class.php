<?php
/*-----------------------------------------------------------------
！！！！警告！！！！
以下为系统文件，请勿修改
-----------------------------------------------------------------*/

namespace app\classes\install;

use PDO;
use app\classes\Model as Model_Base;
use ginkgo\Db;
use ginkgo\Func;

//不能非法包含或直接执行
if (!defined('IN_GINKGO')) {
  return 'Access denied';
}

/*-------------单点登录类-------------*/
abstract class Model extends Model_Base {

  protected $_viewFrom        = '';
  protected $_viewJoin        = '';
  protected $_viewWhere       = '';
  protected $_viewWhereExp    = '';
  protected $_viewWhereValue  = '';
  protected $_viewWhereParam  = '';
  protected $_viewWhereType   = '';
  protected $_viewGroup       = '';
  protected $_viewOrder       = '';
  protected $_viewOrderType   = '';

  protected $_copyTo          = '';
  protected $_index           = '';


  protected function viewFrom($from) {
    $this->_viewFrom = $from;

    return $this;
  }


  protected function viewJoin($join) {
    $this->_viewJoin = $join;

    return $this;
  }


  protected function copyTo($dst) {
    $this->_copyTo = $dst;

    return $this;
  }


  protected function index($index) {
    $this->_index = $index;

    return $this;
  }


  protected function viewWhere($where, $exp = '', $value = '', $param = '', $type = '') {
    $this->_viewWhere       = $where;
    $this->_viewWhereExp    = $exp;
    $this->_viewWhereValue  = $value;
    $this->_viewWhereParam  = $param;
    $this->_viewWhereType   = $type;

    return $this;
  }


  protected function viewGroup($group) {
    $this->_viewGroup = $group;

    return $this;
  }


  protected function viewOrder($order, $type = '') {
    $this->_viewOrder       = $order;
    $this->_viewOrderType   = $type;

    return $this;
  }

  protected function create($field = array(), $comment = '') {
    if (Func::notEmpty($this->_index)) {
      $_arr_index = $this->getTableInfo('index');

      if (in_array($this->_index, $_arr_index)) {
        $_str_sqlDrop = 'DROP INDEX ' . $this->obj_builder->addChar($this->_index) . ' ON ' . $this->getTable();
        $_return = $this->exec($_str_sqlDrop);

        if ($_return === false) {
          return $_return;
        }
      }
    }

    $_str_sql    = $this->buildCreate($field, $comment);

    //print_r($_str_sql);

    $_return     = $this->exec($_str_sql);

    $this->resetSql();

    return $_return;
  }


  protected function alter($field = array(), $rename = '') {
    $_return = array();

    if (Func::notEmpty($field) || Func::notEmpty($rename)) {
      $_str_sql    = 'ALTER TABLE ' . $this->getTable() . ' ';

      if (Func::notEmpty($rename)) {
        $_str_sql = 'ALTER TABLE ' . $this->obj_builder->table($rename) . ' RENAME TO ' . $this->getTable();
      }

      if (Func::notEmpty($field)) {
        $_values  = array();

        foreach ($field as $_key => $_value) {
          switch ($_value[0]) {
            case 'ADD':
              $_value[1] = $this->fieldProcess($_value[1]);
              $_values[] = 'ADD COLUMN ' . $this->buildField($_key, $_value[1]);
            break;
            case 'MODIFY':
              $_value[1] = $this->fieldProcess($_value[1]);
              $_values[] = 'MODIFY COLUMN ' . $this->buildField($_key, $_value[1]);
            break;
            case 'CHANGE':
              $_value[1] = $this->fieldProcess($_value[1]);
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


  protected function copy($field = array(), $comment = '') {
    $_return = false;

    if (Func::notEmpty($field) && Func::notEmpty($this->_copyTo)) {
      $_str_sql  = 'CREATE TABLE IF NOT EXISTS ' . $this->obj_builder->table($this->_copyTo) . ' (';

      $_values   = array();

      foreach ($field as $_key => $_value) {
        if (isset($_value['target'])) {
          $_target = $_value['target'];
        } else {
          $_target = $_key;
        }

        $_value    = $this->fieldProcess($_value);
        $_values[] = $this->buildField($_target, $_value);
      }

      $_str_sql     .= implode(',', $_values);

      $_str_sql     .= ', PRIMARY KEY (' . $this->obj_builder->addChar($this->pk) . ')) ENGINE=InnoDB DEFAULT CHARSET=' . $this->config['charset'] . ' COMMENT=\'' . $comment . '\' AUTO_INCREMENT=1 COLLATE utf8_general_ci';
      $_str_sql     .= ' SELECT ';

      $_values   = array();

      foreach ($field as $_key => $_value) {
        $_as = '';
        if (isset($_value['target'])) {
          $_as = ' AS ' . $this->obj_builder->addChar($_value['target']);
        }

        $_values[] = $this->obj_builder->addChar($_key) . $_as;
      }
      $_str_sql .= implode(',', $_values);

      $_str_sql .= ' FROM ' . $this->getTable();

      //print_r($_str_sql);

      $_return  = $this->exec($_str_sql);
    }

    $this->resetSql();

    return $_return;
  }


  protected function alterProcess($create) {
    $_arr_col      = $this->getTableInfo('full_columns');
    $_arr_alter    = array();

    foreach ($create as $_key=>$_value) {
      if (isset($_value['old']) && isset($_arr_col[$_value['old']]) && !isset($_arr_col[$_key])) {
        $_arr_alter[$_value['old']] = array('CHANGE', $_value, $_key);
      } else if (isset($_arr_col[$_key])) {
        if (isset($_value['type'])) {
          $_value = $this->fieldProcess($_value);

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
    $this->_viewFrom        = '';
    $this->_viewJoin        = '';
    $this->_viewWhere       = '';
    $this->_viewWhereExp    = '';
    $this->_viewWhereValue  = '';
    $this->_viewWhereParam  = '';
    $this->_viewWhereType   = '';
    $this->_viewGroup       = '';
    $this->_viewOrder       = '';
    $this->_viewOrderType   = '';
    $this->_copyTo          = '';
    $this->_index           = '';

    Db::resetSql();
  }


  private function buildCreate($field = array(), $comment = '') {
    $_str_sql   = '';

    if (Func::notEmpty($field)) {
      if (Func::notEmpty($this->_viewFrom)) {
        $_str_sql   = 'CREATE OR REPLACE SQL SECURITY INVOKER VIEW ' . $this->getTable() . ' AS ';

        $_obj_db    = $this->table($this->_viewFrom)->join($this->_viewJoin);

        if (Func::notEmpty($this->_viewWhere)) {
          $_obj_db->where($this->_viewWhere, $this->_viewWhereExp, $this->_viewWhereValue, $this->_viewWhereParam, $this->_viewWhereType);
        }

        if (Func::notEmpty($this->_viewGroup)) {
          $_obj_db->group($this->_viewGroup);
        }

        if (Func::notEmpty($this->_viewOrder)) {
          $_obj_db->order($this->_viewOrder, $this->_viewOrderType);
        }

        $_str_sql  .= $_obj_db->fetchSql()->select($field);
      } else if (Func::notEmpty($this->_index)) {
        $_str_field = $this->obj_builder->field($field);

        $_str_sql   = 'CREATE INDEX ' . $this->obj_builder->addChar($this->_index) . ' ON ' . $this->getTable() . ' (' . $_str_field . ') USING BTREE';
      } else {
        $_str_sql   = 'CREATE TABLE IF NOT EXISTS ' . $this->getTable() . ' (';

        $_arr_field = array();

        foreach ($field as $_key => $_value) {
          $_value    = $this->fieldProcess($_value);
          $_str_sql .= $this->buildField($_key, $_value) . ',';
        }

        $_str_sql  .= ' PRIMARY KEY (' . $this->obj_builder->addChar($this->pk) . ')';

        $_str_sql  .= ') ENGINE=InnoDB DEFAULT CHARSET=' . $this->config['charset'] . ' COMMENT=\'' . $comment . '\' AUTO_INCREMENT=1 COLLATE utf8_general_ci';
      }
    }

    return $_str_sql;
  }


  private function buildField($name = '', $field = array()) {
    $_str_field = '';

    if (isset($field['type']) && Func::notEmpty($field['type'])) {
      $_str_field .= $this->obj_builder->addChar($name) . ' ' . $field['type'];

      if (isset($field['ai'])) {
        if (isset($field['not_null'])) {
          $_str_field .= ' NOT NULL';
        }
        $_str_field .= ' AUTO_INCREMENT';
      } else if ($field['type'] != 'text') {
        if (isset($field['not_null'])) {
          $_str_field .= ' NOT NULL';
        }

        if (is_numeric($field['default']) || strpos($field['default'], '(') || strpos($field['default'], '`')) {
          $_str_field .= ' DEFAULT ' . $field['default'];
        } else {
          $_str_field .= ' DEFAULT \'' . $field['default'] . '\'';
        }
      }

      if (isset($field['comment'])) {
        $_str_field .= ' COMMENT \'' . $field['comment'] . '\'';
      }
    }

    return $_str_field;
  }


  private function fieldProcess($field = array()) {
    if (isset($field['type']) && Func::notEmpty($field['type'])) {
      preg_match('/(\w+)(\(.+\))?/i', $field['type'], $_arr_matches);

      $_str_type = '';

      if (isset($_arr_matches[1])) {
        $_str_type = $_arr_matches[1];
      }

      switch ($_str_type) {
        case 'tinyint':
        case 'smallint':
        case 'mediumint':
        case 'int':
        case 'bigint':
        case 'decimal':
        case 'float':
        case 'double':
        case 'boolean':
          if (!isset($field['default']) || Func::isEmpty($field['default'])) {
            $field['default'] = 0;
          } else {
            $field['default'] = (int)$field['default'];
          }
        break;

        case 'year':
          if (!isset($field['default']) || Func::isEmpty($field['default'])) {
            $field['default'] = date('Y', 0);
          } else {
            $field['default'] = (string)$field['default'];
          }
        break;

        case 'date':
          if (!isset($field['default']) || Func::isEmpty($field['default'])) {
            $field['default'] = date('Y-m-d', 0);
          } else {
            $field['default'] = (string)$field['default'];
          }
        break;

        case 'time':
          if (!isset($field['default']) || Func::isEmpty($field['default'])) {
            $field['default'] = date('H:i:s', 0);
          } else {
            $field['default'] = (string)$field['default'];
          }
        break;

        case 'datetime':
          if (!isset($field['default']) || Func::isEmpty($field['default'])) {
            $field['default'] = date('Y-m-d H:i:s', 0);
          } else {
            $field['default'] = (string)$field['default'];
          }
        break;

        case 'timestamp':
          if (!isset($field['default']) || Func::isEmpty($field['default'])) {
            $field['default'] = 'CURRENT_TIMESTAMP()';
          } else {
            $field['default'] = (string)$field['default'];
          }
        break;

        case 'char':
        case 'varchar':
          if (!isset($field['default']) || Func::isEmpty($field['default'])) {
            $field['default'] = '';
          } else {
            $field['default'] = (string)$field['default'];
          }
        break;

        case 'enum':
          if (!isset($field['default']) || Func::isEmpty($field['default'])) {
            if (isset($_arr_matches[2])) {
              $_str_enum = $_arr_matches[2];
            } else {
              $_str_enum = '';
            }

            $_str_enum = str_replace(array('(', ')', '"', '\''), '', $_str_enum);

            $_arr_enum = explode(',', $_str_enum);

            if (isset($_arr_enum[0])) {
              $field['default'] = $_arr_enum[0];
            } else {
              $field['default'] = '';
            }
          } else {
            $field['default'] = (string)$field['default'];
          }
        break;

        default:
          $field['default'] = '';
        break;
      }

      if (!isset($field['comment'])) {
        $field['comment'] = '';
      }
    }

    return $field;
  }
}
